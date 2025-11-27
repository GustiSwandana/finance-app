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
        $payables = Debt::where('user_id', $userId)->where('type', 'payable')->where('is_paid', false)->get();
        // Ambil piutang (Orang Utang) belum lunas
        $receivables = Debt::where('user_id', $userId)->where('type', 'receivable')->where('is_paid', false)->get();

        $wallets = Wallet::where('user_id', $userId)->get();

        return view('debts.index', compact('payables', 'receivables', 'wallets'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'type' => 'required|in:payable,receivable',
            'name' => 'required|string',
            'amount' => 'required|numeric|min:1000',
            'wallet_id' => 'required|exists:wallets,id', // Bank yg terlibat
            'due_date' => 'nullable|date',
        ]);

        DB::transaction(function () use ($request) {
            // 1. Simpan Catatan Utang
            $debt = Debt::create([
                'user_id' => Auth::id(),
                'type' => $request->type,
                'name' => $request->name,
                'amount' => $request->amount,
                'due_date' => $request->due_date,
                'description' => $request->description,
            ]);

            $wallet = Wallet::find($request->wallet_id);

            // 2. Mutasi Saldo Otomatis
            if ($request->type == 'payable') {
                // SAYA UTANG = Uang Masuk (Income)
                $wallet->increment('balance', $request->amount);
                Transaction::create([
                    'user_id' => Auth::id(),
                    'wallet_id' => $wallet->id,
                    'amount' => $request->amount,
                    'type' => 'income',
                    'date' => now(),
                    'description' => 'Pinjaman dari ' . $request->name,
                ]);
            } else {
                // ORANG UTANG = Uang Keluar (Expense)
                $wallet->decrement('balance', $request->amount);
                Transaction::create([
                    'user_id' => Auth::id(),
                    'wallet_id' => $wallet->id,
                    'amount' => $request->amount,
                    'type' => 'expense',
                    'date' => now(),
                    'description' => 'Pinjamkan ke ' . $request->name,
                ]);
            }
        });

        return back()->with('success', 'Data utang berhasil disimpan & saldo disesuaikan.');
    }

    // FUNGSI PELUNASAN
    public function markAsPaid(Request $request, $id)
    {
        $request->validate(['wallet_id' => 'required|exists:wallets,id']);
        $debt = Debt::where('user_id', Auth::id())->findOrFail($id);
        $wallet = Wallet::find($request->wallet_id);

        DB::transaction(function () use ($debt, $wallet) {
            if ($debt->type == 'payable') {
                // SAYA BAYAR UTANG = Pengeluaran
                $wallet->decrement('balance', $debt->amount);
                Transaction::create([
                    'user_id' => Auth::id(),
                    'wallet_id' => $wallet->id,
                    'amount' => $debt->amount,
                    'type' => 'expense',
                    'date' => now(),
                    'description' => 'Pelunasan utang ke ' . $debt->name,
                ]);
            } else {
                // ORANG BAYAR KE SAYA = Pemasukan
                $wallet->increment('balance', $debt->amount);
                Transaction::create([
                    'user_id' => Auth::id(),
                    'wallet_id' => $wallet->id,
                    'amount' => $debt->amount,
                    'type' => 'income',
                    'date' => now(),
                    'description' => 'Pelunasan piutang dari ' . $debt->name,
                ]);
            }

            $debt->update(['is_paid' => true]);
        });

        return back()->with('success', 'Status lunas! Saldo telah diupdate.');
    }
}
