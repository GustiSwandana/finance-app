<?php

namespace App\Http\Controllers;

use App\Models\Debt;
use App\Models\Wallet;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DebtController extends Controller
{
    public function index()
    {
        $userId = Auth::id();
        // Ambil hutang (Saya Utang) belum lunas
        $payables = Debt::where('user_id', $userId)->where('type', 'payable')->where('is_paid', false)->with('wallet')->get();
        // Ambil piutang (Orang Utang) belum lunas
        $receivables = Debt::where('user_id', $userId)->where('type', 'receivable')->where('is_paid', false)->with('wallet')->get();

        $wallets = Wallet::where('user_id', $userId)->get();

        return view('debts.index', compact('payables', 'receivables', 'wallets'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'type' => 'required|in:payable,receivable',
            'name' => 'required|string',
            'amount' => 'required|numeric|min:1000',
            'wallet_id' => 'required',
            'due_date' => 'nullable|date',
        ]);

        $wallet = $this->findUserWalletOrFail($request->wallet_id);

        if ($request->type === 'receivable' && $wallet->balance < $request->amount) {
            return back()->withErrors(['msg' => 'Saldo tidak cukup di dompet ' . $wallet->bank_name])->withInput();
        }

        DB::transaction(function () use ($request, $wallet) {
            $debt = Debt::create([
                'user_id' => Auth::id(),
                'wallet_id' => $wallet->id,
                'type' => $request->type,
                'name' => $request->name,
                'amount' => $request->amount,
                'due_date' => $request->due_date,
                'description' => $request->description,
            ]);

            if ($request->type == 'payable') {
                $wallet->increment('balance', $request->amount);
                Transaction::create([
                    'user_id' => Auth::id(),
                    'wallet_id' => $wallet->id,
                    'amount' => $request->amount,
                    'type' => 'income',
                    'date' => now(),
                    'description' => 'Pinjaman dari ' . $request->name,
                    'status' => 'completed',
                ]);
            } else {
                $wallet->decrement('balance', $request->amount);
                Transaction::create([
                    'user_id' => Auth::id(),
                    'wallet_id' => $wallet->id,
                    'amount' => $request->amount,
                    'type' => 'expense',
                    'date' => now(),
                    'description' => 'Pinjamkan ke ' . $request->name,
                    'status' => 'completed',
                ]);
            }
        });

        return back()->with('success', 'Data utang berhasil disimpan & saldo disesuaikan.');
    }

    public function edit($id)
    {
        $debt = Debt::where('user_id', Auth::id())->findOrFail($id);
        $wallets = Wallet::where('user_id', Auth::id())->orderBy('bank_name')->get();

        return view('debts.edit', compact('debt', 'wallets'));
    }

    public function update(Request $request, $id)
    {
        $debt = Debt::where('user_id', Auth::id())->findOrFail($id);

        if ($debt->is_paid) {
            return redirect()->route('debts.index')->withErrors(['msg' => 'Data yang sudah lunas tidak bisa diedit.']);
        }

        $validated = $request->validate([
            'type' => 'required|in:payable,receivable',
            'name' => 'required|string|max:255',
            'amount' => 'required|numeric|min:1000',
            'wallet_id' => 'required',
            'due_date' => 'nullable|date',
            'description' => 'nullable|string|max:255',
        ]);

        $newWallet = $this->findUserWalletOrFail($validated['wallet_id']);
        $oldWallet = $debt->wallet_id ? $this->findUserWalletOrFail($debt->wallet_id) : null;

        $availableBalance = $newWallet->balance;
        if ($oldWallet && $oldWallet->id === $newWallet->id && $debt->type === 'receivable') {
            $availableBalance += $debt->amount;
        }

        if ($validated['type'] === 'receivable' && $availableBalance < $validated['amount']) {
            return back()->withErrors(['msg' => 'Saldo tidak cukup di dompet ' . $newWallet->bank_name])->withInput();
        }

        DB::transaction(function () use ($debt, $validated, $oldWallet, $newWallet) {
            if ($oldWallet) {
                $this->reverseDebtBalanceEffect($debt, $oldWallet);
            }

            $debt->update([
                'wallet_id' => $newWallet->id,
                'type' => $validated['type'],
                'name' => $validated['name'],
                'amount' => $validated['amount'],
                'due_date' => $validated['due_date'] ?? null,
                'description' => $validated['description'] ?? null,
            ]);

            $this->applyDebtBalanceEffect($debt->fresh(), $newWallet);
        });

        return redirect()->route('debts.index')->with('success', 'Data utang/piutang berhasil diperbarui dan saldo disesuaikan.');
    }

    // FUNGSI PELUNASAN
    public function markAsPaid(Request $request, $id)
    {
        $request->validate(['wallet_id' => 'required']);
        $debt = Debt::where('user_id', Auth::id())->findOrFail($id);
        $wallet = $this->findUserWalletOrFail($request->wallet_id);

        if ($debt->type === 'payable' && $wallet->balance < $debt->amount) {
            return back()->withErrors(['msg' => 'Saldo tidak cukup di dompet ' . $wallet->bank_name])->withInput();
        }

        DB::transaction(function () use ($debt, $wallet) {
            if ($debt->type == 'payable') {
                $wallet->decrement('balance', $debt->amount);
                Transaction::create([
                    'user_id' => Auth::id(),
                    'wallet_id' => $wallet->id,
                    'amount' => $debt->amount,
                    'type' => 'expense',
                    'date' => now(),
                    'description' => 'Pelunasan utang ke ' . $debt->name,
                    'status' => 'completed',
                ]);
            } else {
                $wallet->increment('balance', $debt->amount);
                Transaction::create([
                    'user_id' => Auth::id(),
                    'wallet_id' => $wallet->id,
                    'amount' => $debt->amount,
                    'type' => 'income',
                    'date' => now(),
                    'description' => 'Pelunasan piutang dari ' . $debt->name,
                    'status' => 'completed',
                ]);
            }

            $debt->update(['is_paid' => true]);
        });

        return back()->with('success', 'Status lunas! Saldo telah diupdate.');
    }

    public function destroy($id)
    {
        $debt = Debt::where('user_id', Auth::id())->findOrFail($id);

        DB::transaction(function () use ($debt) {
            if (! $debt->is_paid && $debt->wallet_id) {
                $wallet = $this->findUserWalletOrFail($debt->wallet_id);
                $this->reverseDebtBalanceEffect($debt, $wallet);
            }

            $debt->delete();
        });

        return redirect()->route('debts.index')->with('success', 'Data utang/piutang berhasil dihapus.');
    }

    protected function findUserWalletOrFail(int|string $walletId): Wallet
    {
        return Wallet::where('user_id', Auth::id())->findOrFail($walletId);
    }

    private function applyDebtBalanceEffect(Debt $debt, Wallet $wallet): void
    {
        if ($debt->type === 'payable') {
            $wallet->increment('balance', $debt->amount);
            return;
        }

        $wallet->decrement('balance', $debt->amount);
    }

    private function reverseDebtBalanceEffect(Debt $debt, Wallet $wallet): void
    {
        if ($debt->type === 'payable') {
            $wallet->decrement('balance', $debt->amount);
            return;
        }

        $wallet->increment('balance', $debt->amount);
    }
}
