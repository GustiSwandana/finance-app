<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Transaction;
use App\Models\User;
use App\Models\Wallet;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class WhatsAppTransactionController extends Controller
{
    public function store(Request $request): JsonResponse
    {
        $token = (string) config('services.whatsapp_transactions.token');
        $incomingToken = (string) ($request->header('X-Webhook-Token') ?: $request->input('token'));

        if ($token === '' || ! hash_equals($token, $incomingToken)) {
            return $this->reply('Token webhook WhatsApp tidak valid.', 403);
        }

        $message = trim((string) (
            $request->input('message')
            ?: $request->input('text')
            ?: $request->input('body')
            ?: data_get($request->all(), 'message.text')
        ));

        $email = trim((string) ($request->input('user_email') ?: $request->input('email')));
        $user = User::where('email', $email)
            ->whereNotNull('email_verified_at')
            ->where('is_active', true)
            ->first();

        if (! $user) {
            return $this->reply('User tidak ditemukan, belum aktif, atau email belum diverifikasi.', 422);
        }

        if ($message === '' || preg_match('/^trx\s+(bantuan|help)$/i', $message)) {
            return $this->reply($this->helpText());
        }

        try {
            if (preg_match('/^trx\s+(masuk|income|pemasukan)\s+/i', $message)) {
                return $this->createTransactionFromMessage($user, $message, 'income');
            }

            if (preg_match('/^trx\s+(keluar|expense|pengeluaran)\s+/i', $message)) {
                return $this->createTransactionFromMessage($user, $message, 'expense');
            }

            if (preg_match('/^trx\s+edit\s+/i', $message)) {
                return $this->updateTransactionFromMessage($user, $message);
            }
        } catch (\InvalidArgumentException $exception) {
            return $this->reply($exception->getMessage(), 422);
        }

        return $this->reply('Format pesan tidak dikenali. Kirim: trx bantuan', 422);
    }

    private function createTransactionFromMessage(User $user, string $message, string $type): JsonResponse
    {
        $actionPattern = $type === 'income'
            ? '(masuk|income|pemasukan)'
            : '(keluar|expense|pengeluaran)';

        if (! preg_match('/^trx\s+' . $actionPattern . '\s+(\S+)\s+(.+)$/i', $message, $matches)) {
            throw new \InvalidArgumentException('Format salah. Contoh: trx masuk 50000 BCA Gaji Bonus proyek');
        }

        $amount = $this->parseAmount($matches[2]);
        $tail = trim($matches[3]);
        [$wallet, $tail] = $this->extractWallet($user, $tail);
        [$category, $description] = $this->extractCategory($user, $type, $tail);

        $transaction = DB::transaction(function () use ($user, $wallet, $category, $amount, $type, $description) {
            $lockedWallet = Wallet::where('user_id', $user->id)->whereKey($wallet->id)->lockForUpdate()->firstOrFail();

            if ($type === 'expense' && $lockedWallet->balance < $amount) {
                throw new \InvalidArgumentException('Saldo tidak cukup di dompet ' . $lockedWallet->bank_name . '.');
            }

            $transaction = Transaction::create([
                'user_id' => $user->id,
                'wallet_id' => $lockedWallet->id,
                'category_id' => $category->id,
                'amount' => $amount,
                'type' => $type,
                'date' => now()->toDateString(),
                'description' => $this->limitDescription($description),
                'status' => 'completed',
            ]);

            $type === 'income'
                ? $lockedWallet->increment('balance', $amount)
                : $lockedWallet->decrement('balance', $amount);

            return $transaction;
        });

        $label = $type === 'income' ? 'pemasukan' : 'pengeluaran';

        return $this->reply(
            'Berhasil mencatat ' . $label . ' Rp ' . number_format($amount, 0, ',', '.') . '. ID transaksi: ' . $transaction->id
        );
    }

    private function updateTransactionFromMessage(User $user, string $message): JsonResponse
    {
        if (! preg_match('/^trx\s+edit\s+(\d+)\s+(.+)$/i', $message, $matches)) {
            throw new \InvalidArgumentException('Format edit salah. Contoh: trx edit 12 nominal 75000 catatan Bonus direvisi');
        }

        $transactionId = (int) $matches[1];
        $body = trim($matches[2]);
        $transaction = Transaction::where('user_id', $user->id)->findOrFail($transactionId);

        if ($transaction->type === 'transfer') {
            throw new \InvalidArgumentException('Transaksi transfer belum bisa diedit lewat WhatsApp. Edit dari halaman aplikasi.');
        }

        $updates = $this->parseUpdateFields($body);
        $newAmount = $updates['amount'] ?? (float) $transaction->amount;
        $newDate = $updates['date'] ?? $transaction->date->toDateString();
        $newDescription = array_key_exists('description', $updates)
            ? $this->limitDescription($updates['description'])
            : $transaction->description;

        $newWallet = array_key_exists('wallet', $updates)
            ? $this->findWalletByName($user, $updates['wallet'])
            : Wallet::where('user_id', $user->id)->findOrFail($transaction->wallet_id);

        $newCategory = array_key_exists('category', $updates)
            ? $this->findOrCreateCategory($user, $transaction->type, $updates['category'])
            : Category::where('user_id', $user->id)->findOrFail($transaction->category_id);

        DB::transaction(function () use ($user, $transaction, $newWallet, $newCategory, $newAmount, $newDate, $newDescription) {
            $oldWallet = Wallet::where('user_id', $user->id)->whereKey($transaction->wallet_id)->lockForUpdate()->firstOrFail();
            $lockedNewWallet = Wallet::where('user_id', $user->id)->whereKey($newWallet->id)->lockForUpdate()->firstOrFail();

            if ($transaction->type === 'income') {
                $oldWallet->decrement('balance', $transaction->amount);
            } else {
                $oldWallet->increment('balance', $transaction->amount);
            }

            if ($transaction->type === 'expense' && $lockedNewWallet->fresh()->balance < $newAmount) {
                throw new \InvalidArgumentException('Saldo tidak cukup di dompet ' . $lockedNewWallet->bank_name . ' untuk update transaksi.');
            }

            $transaction->update([
                'wallet_id' => $lockedNewWallet->id,
                'category_id' => $newCategory->id,
                'amount' => $newAmount,
                'date' => $newDate,
                'description' => $newDescription,
            ]);

            $transaction->type === 'income'
                ? $lockedNewWallet->increment('balance', $newAmount)
                : $lockedNewWallet->decrement('balance', $newAmount);
        });

        return $this->reply('Transaksi ID ' . $transaction->id . ' berhasil diperbarui lewat WhatsApp.');
    }

    private function parseUpdateFields(string $body): array
    {
        $updates = [];

        if (preg_match('/\bnominal\s+(\S+)/i', $body, $matches)) {
            $updates['amount'] = $this->parseAmount($matches[1]);
        }

        if (preg_match('/\btanggal\s+(\d{4}-\d{2}-\d{2})/i', $body, $matches)) {
            $validator = Validator::make(['date' => $matches[1]], ['date' => ['date']]);

            if ($validator->fails()) {
                throw new \InvalidArgumentException('Tanggal tidak valid. Gunakan format YYYY-MM-DD.');
            }

            $updates['date'] = Carbon::parse($matches[1])->toDateString();
        }

        foreach (['wallet', 'kategori', 'catatan'] as $field) {
            if (preg_match('/\b' . $field . '\s+(.+?)(?=\s+(wallet|kategori|catatan|nominal|tanggal)\b|$)/i', $body, $matches)) {
                $key = $field === 'kategori' ? 'category' : ($field === 'catatan' ? 'description' : 'wallet');
                $updates[$key] = trim($matches[1]);
            }
        }

        if ($updates === []) {
            throw new \InvalidArgumentException('Tidak ada field yang bisa diupdate. Gunakan nominal, wallet, kategori, tanggal, atau catatan.');
        }

        return $updates;
    }

    private function extractWallet(User $user, string $text): array
    {
        $wallets = Wallet::where('user_id', $user->id)->orderByRaw('LENGTH(bank_name) DESC')->get();

        foreach ($wallets as $wallet) {
            if (Str::startsWith(Str::lower($text), Str::lower($wallet->bank_name))) {
                return [$wallet, trim(Str::substr($text, Str::length($wallet->bank_name)))];
            }
        }

        throw new \InvalidArgumentException('Wallet tidak ditemukan di pesan. Pastikan nama wallet sesuai, contoh: BCA.');
    }

    private function extractCategory(User $user, string $type, string $text): array
    {
        $categories = Category::where('user_id', $user->id)
            ->where('type', $type)
            ->orderByRaw('LENGTH(name) DESC')
            ->get();

        foreach ($categories as $category) {
            if (Str::startsWith(Str::lower($text), Str::lower($category->name))) {
                return [$category, trim(Str::substr($text, Str::length($category->name))) ?: null];
            }
        }

        $parts = preg_split('/\s+/', $text, 2);
        $categoryName = $parts[0] ?? 'Lainnya';
        $description = $parts[1] ?? null;

        return [$this->findOrCreateCategory($user, $type, $categoryName), $description];
    }

    private function findWalletByName(User $user, string $name): Wallet
    {
        $wallet = Wallet::where('user_id', $user->id)
            ->where('bank_name', 'like', trim($name))
            ->first();

        if (! $wallet) {
            throw new \InvalidArgumentException('Wallet "' . $name . '" tidak ditemukan.');
        }

        return $wallet;
    }

    private function findOrCreateCategory(User $user, string $type, string $name): Category
    {
        return Category::firstOrCreate([
            'user_id' => $user->id,
            'name' => trim($name),
            'type' => $type,
        ]);
    }

    private function parseAmount(string $rawAmount): float
    {
        $amount = (float) preg_replace('/\D+/', '', $rawAmount);

        if ($amount <= 0) {
            throw new \InvalidArgumentException('Nominal harus lebih dari 0.');
        }

        return $amount;
    }

    private function limitDescription(?string $description): ?string
    {
        $description = trim((string) $description);

        return $description === '' ? null : Str::limit($description, 250, '');
    }

    private function helpText(): string
    {
        return implode("\n", [
            'Format WhatsApp transaksi:',
            'trx masuk 50000 BCA Gaji Bonus proyek',
            'trx keluar 25000 BCA Makanan Makan siang',
            'trx edit 12 nominal 75000 catatan Bonus direvisi',
            'Field edit: nominal, wallet, kategori, tanggal YYYY-MM-DD, catatan.',
        ]);
    }

    private function reply(string $message, int $status = 200): JsonResponse
    {
        return response()->json([
            'success' => $status >= 200 && $status < 300,
            'reply' => $message,
        ], $status);
    }
}
