<?php

namespace App\Http\Controllers;

use App\Http\Requests\Transactions\StoreExpenseRequest;
use App\Http\Requests\Transactions\StoreIncomeRequest;
use App\Http\Requests\Transactions\StoreReceiptTransactionRequest;
use App\Http\Requests\Transactions\StoreTransferRequest;
use App\Http\Requests\Transactions\UpdateTransactionRequest;
use App\Models\Category;
use App\Models\Transaction;
use App\Models\Wallet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use App\Services\ReceiptScanner;

class TransactionController extends Controller
{
    // Menampilkan Daftar Riwayat Transaksi
    public function index()
    {
        $userId = Auth::id();

        // 1. Ambil Data untuk Form Input
        $wallets = Wallet::where('user_id', $userId)->get();
        $incomeCategories = Category::where('user_id', $userId)->where('type', 'income')->get();
        $expenseCategories = Category::where('user_id', $userId)->where('type', 'expense')->get();

        // 2. Ambil Data untuk Tabel Riwayat
        $transactions = Transaction::where('user_id', $userId)
            ->with(['wallet', 'category'])
            ->orderBy('date', 'desc')
            ->orderBy('created_at', 'desc')
            ->paginate(5); // Kita kurangi jadi 5 agar halaman tidak terlalu panjang

        $transactionCount = Transaction::where('user_id', $userId)->count();

        return view('transactions.index', compact('wallets', 'incomeCategories', 'expenseCategories', 'transactions', 'transactionCount'));
    }

    // A. Menampilkan Halaman Form Pemasukan
    public function createIncome()
    {
        $wallets = $this->userWallets();
        $categories = $this->userCategoriesByType('income');

        return view('transactions.create_income', compact('wallets', 'categories'));
    }

    // B. Memproses Simpan Data
    public function storeIncome(StoreIncomeRequest $request)
    {
        $validated = $request->validated();
        $wallet = $this->findUserWalletOrFail($validated['wallet_id']);
        $category = $this->findUserCategoryOrFail($validated['category_id'], 'income');

        DB::transaction(function () use ($validated, $wallet, $category) {
            Transaction::create([
                'user_id' => Auth::id(),
                'wallet_id' => $wallet->id,
                'category_id' => $category->id,
                'amount' => $validated['amount'],
                'type' => 'income',
                'date' => $validated['date'],
                'description' => $validated['description'] ?? null,
                'status' => 'completed'
            ]);

            $wallet->increment('balance', $validated['amount']);
        });

        return redirect()->route('dashboard')->with('success', 'Saldo berhasil ditambahkan!');
    }

    // C. Tampilkan Form Pengeluaran
    public function createExpense()
    {
        $wallets = $this->userWallets();
        $categories = $this->userCategoriesByType('expense');

        return view('transactions.create_expense', compact('wallets', 'categories'));
    }

    // D. Proses Simpan Pengeluaran
    public function storeExpense(StoreExpenseRequest $request)
    {
        $validated = $request->validated();
        $category = $this->findUserCategoryOrFail($validated['category_id'], 'expense');
        $wallet = $this->findUserWalletOrFail($validated['wallet_id']);

        if (stripos($category->name, 'Makanan') !== false && $wallet->bank_name !== 'BCA') {
            return back()->withErrors(['msg' => 'Aturan Pribadi: Untuk Makanan & Minuman, wajib menggunakan saldo BCA!'])->withInput();
        }

        if ($wallet->balance < $validated['amount']) {
            return back()->withErrors(['msg' => 'Saldo tidak cukup di dompet ' . $wallet->bank_name])->withInput();
        }

        DB::transaction(function () use ($validated, $wallet, $category) {
            Transaction::create([
                'user_id' => Auth::id(),
                'wallet_id' => $wallet->id,
                'category_id' => $category->id,
                'amount' => $validated['amount'],
                'type' => 'expense',
                'date' => $validated['date'],
                'description' => $validated['description'] ?? null,
                'status' => 'completed'
            ]);

            $wallet->decrement('balance', $validated['amount']);
        });

        return redirect()->route('dashboard')->with('success', 'Pengeluaran berhasil dicatat!');
    }

    // E. Tampilkan Form Transfer
    public function createTransfer()
    {
        $wallets = $this->userWallets();

        if ($wallets->isEmpty()) {
            return redirect('/setup-data');
        }

        if ($wallets->count() < 2) {
            return redirect()->route('wallets.index')->with('error', 'Minimal butuh 2 akun bank untuk melakukan transfer.');
        }

        return view('transactions.create_transfer', compact('wallets'));
    }
    // F. Proses Simpan Transfer
    public function storeTransfer(StoreTransferRequest $request)
    {
        $validated = $request->validated();
        $sourceWallet = $this->findUserWalletOrFail($validated['source_wallet_id']);
        $destWallet = $this->findUserWalletOrFail($validated['destination_wallet_id']);

        if ($sourceWallet->balance < $validated['amount']) {
            return back()->withErrors(['msg' => 'Saldo di ' . $sourceWallet->bank_name . ' tidak cukup untuk transfer!'])->withInput();
        }

        DB::transaction(function () use ($validated, $sourceWallet, $destWallet) {
            $sourceWallet->decrement('balance', $validated['amount']);
            $destWallet->increment('balance', $validated['amount']);

            $description = $validated['description'] ?? 'Transfer internal';

            Transaction::create([
                'user_id' => Auth::id(),
                'wallet_id' => $sourceWallet->id,
                'amount' => $validated['amount'],
                'type' => 'transfer',
                'date' => $validated['date'],
                'description' => $description . ' | Transfer ke ' . $destWallet->bank_name,
                'status' => 'completed'
            ]);

            Transaction::create([
                'user_id' => Auth::id(),
                'wallet_id' => $destWallet->id,
                'amount' => $validated['amount'],
                'type' => 'transfer',
                'date' => $validated['date'],
                'description' => $description . ' | Terima dari ' . $sourceWallet->bank_name,
                'status' => 'completed'
            ]);
        });

        return redirect()->route('dashboard')->with('success', 'Transfer dana berhasil!');
    }

    // G. Tampilkan Halaman Edit
    public function edit($id)
    {
        $transaction = Transaction::where('user_id', Auth::id())->findOrFail($id);
        $wallets = $this->userWallets();
        $categories = $this->userCategoriesByType($transaction->type);

        return view('transactions.edit', compact('transaction', 'wallets', 'categories'));
    }

    // H. Proses Update Data
    public function update(UpdateTransactionRequest $request, $id)
    {
        $transaction = Transaction::where('user_id', Auth::id())->findOrFail($id);
        $validated = $request->validated();
        $newWallet = $this->findUserWalletOrFail($validated['wallet_id']);
        $newCategory = $this->findUserCategoryOrFail($validated['category_id'], $transaction->type);

        if ($transaction->type == 'expense') {
            if (stripos($newCategory->name, 'Makanan') !== false && $newWallet->bank_name !== 'BCA') {
                return back()->withErrors(['msg' => 'Aturan Pribadi: Edit gagal! Makanan tetap wajib pakai BCA.']);
            }
        }

        $oldWallet = $this->findUserWalletOrFail($transaction->wallet_id);
        $availableBalance = $newWallet->id === $oldWallet->id && $transaction->type === 'expense'
            ? $newWallet->balance + $transaction->amount
            : $newWallet->balance;

        if ($transaction->type === 'expense' && $availableBalance < $validated['amount']) {
            return back()->withErrors(['msg' => 'Saldo tidak cukup di dompet ' . $newWallet->bank_name])->withInput();
        }

        DB::transaction(function () use ($validated, $transaction, $oldWallet, $newWallet, $newCategory) {
            if ($transaction->type == 'income') {
                $oldWallet->decrement('balance', $transaction->amount);
            } elseif ($transaction->type == 'expense') {
                $oldWallet->increment('balance', $transaction->amount);
            }

            $transaction->update([
                'wallet_id' => $newWallet->id,
                'category_id' => $newCategory->id,
                'amount' => $validated['amount'],
                'date' => $validated['date'],
                'description' => $validated['description'] ?? null,
            ]);

            if ($transaction->type == 'income') {
                $newWallet->increment('balance', $validated['amount']);
            } elseif ($transaction->type == 'expense') {
                $newWallet->decrement('balance', $validated['amount']);
            }
        });

        return redirect()->route('transactions.index')->with('success', 'Transaksi berhasil diperbarui!');
    }

    // I. Hapus Transaksi
    public function destroy($id)
    {
        $transaction = Transaction::where('user_id', Auth::id())->findOrFail($id);

        DB::transaction(function () use ($transaction) {
            $wallet = $this->findUserWalletOrFail($transaction->wallet_id);

            if ($transaction->type == 'income') {
                $wallet->decrement('balance', $transaction->amount);
            } elseif ($transaction->type == 'expense') {
                $wallet->increment('balance', $transaction->amount);
            }

            $transaction->delete();
        });

        return back()->with('success', 'Transaksi dihapus & saldo telah disesuaikan.');
    }

    public function resetAll(Request $request)
    {
        $request->validateWithBag('transactionReset', [
            'password' => ['required', 'current_password'],
            'confirmation_text' => ['required', Rule::in(['RESET TRANSAKSI'])],
            'confirm_reset_transactions' => ['accepted'],
        ]);

        $userId = Auth::id();
        $deletedCount = 0;

        DB::transaction(function () use ($userId, &$deletedCount) {
            $deletedCount = Transaction::where('user_id', $userId)->count();

            Transaction::where('user_id', $userId)->delete();
            Wallet::where('user_id', $userId)->update(['balance' => 0]);
        });

        return redirect()
            ->route('profile.edit')
            ->with('success', $deletedCount . ' transaksi berhasil dihapus. Saldo semua wallet direset ke Rp 0.');
    }

    public function storeFromReceipt(StoreReceiptTransactionRequest $request)
    {
        $validated = $request->validated();
        $wallet = $this->findUserWalletOrFail($validated['wallet_id']);

        $path = $request->file('receipt')->store('receipts', 'public');

        $scanner = new ReceiptScanner();
        $data = $scanner->scan(storage_path('app/public/' . $path));

        if (!$data) {
            return back()->withErrors(['msg' => 'Gagal membaca struk. Coba foto lebih jelas.']);
        }

        $category = Category::where('user_id', Auth::id())
            ->where('name', 'like', '%' . $data['category'] . '%')
            ->first();

        $categoryId = $category ? $category->id : null;

        if ($wallet->balance < $data['amount']) {
            return back()->withErrors(['msg' => 'Saldo tidak cukup di dompet ' . $wallet->bank_name])->withInput();
        }

        DB::transaction(function () use ($validated, $data, $path, $categoryId, $wallet) {
            Transaction::create([
                'user_id' => Auth::id(),
                'wallet_id' => $validated['wallet_id'],
                'category_id' => $categoryId,
                'amount' => $data['amount'],
                'type' => 'expense',
                'date' => $data['date'] ?? now(),
                'description' => 'Scan: ' . $data['merchant'],
                'receipt_image' => $path,
                'status' => 'completed'
            ]);

            $wallet->decrement('balance', $data['amount']);
        });

        return redirect()->route('transactions.index')->with('success', 'Struk berhasil discan & dicatat! Merchant: ' . $data['merchant']);
    }

    protected function findUserWalletOrFail(int|string $walletId): Wallet
    {
        return Wallet::where('user_id', Auth::id())->findOrFail($walletId);
    }

    protected function findUserCategoryOrFail(int|string $categoryId, ?string $type = null): Category
    {
        $query = Category::where('user_id', Auth::id())->whereKey($categoryId);

        if ($type !== null) {
            $query->where('type', $type);
        }

        return $query->firstOrFail();
    }

    protected function userWallets()
    {
        return Wallet::where('user_id', Auth::id())
            ->orderBy('bank_name')
            ->get();
    }

    protected function userCategoriesByType(string $type)
    {
        return Category::where('user_id', Auth::id())
            ->where('type', $type)
            ->orderBy('name')
            ->get();
    }
}
