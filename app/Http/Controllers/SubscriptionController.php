<?php

namespace App\Http\Controllers;

use App\Models\Subscription;
use App\Models\Transaction;
use App\Models\Wallet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SubscriptionController extends Controller
{
    public function index()
    {
        $subscriptions = Subscription::where('user_id', Auth::id())->orderBy('due_date', 'asc')->get();
        $wallets = Wallet::where('user_id', Auth::id())->get(); // Untuk pilihan bayar pakai apa
        
        return view('subscriptions.index', compact('subscriptions', 'wallets'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:50',
            'amount' => 'required|numeric',
            'due_date' => 'required|integer|min:1|max:31',
        ]);

        Subscription::create([
            'user_id' => Auth::id(),
            'name' => $request->name,
            'amount' => $request->amount,
            'due_date' => $request->due_date,
        ]);

        return back()->with('success', 'Langganan berhasil disimpan!');
    }

    // FUNGSI UTAMA: BAYAR TAGIHAN
    public function pay(Request $request, $id)
    {
        $request->validate(['wallet_id' => 'required|exists:wallets,id']);

        $sub = Subscription::where('user_id', Auth::id())->findOrFail($id);
        $wallet = Wallet::where('user_id', Auth::id())->findOrFail($request->wallet_id);

        // Cek Saldo
        if ($wallet->balance < $sub->amount) {
            return back()->withErrors(['msg' => 'Saldo tidak cukup di ' . $wallet->bank_name]);
        }

        DB::transaction(function () use ($sub, $wallet) {
            // 1. Buat Transaksi Pengeluaran
            Transaction::create([
                'user_id' => Auth::id(),
                'wallet_id' => $wallet->id,
                'amount' => $sub->amount,
                'type' => 'expense',
                'date' => now(),
                'description' => 'Bayar Tagihan: ' . $sub->name,
                'status' => 'completed'
            ]);

            // 2. Potong Saldo
            $wallet->decrement('balance', $sub->amount);

            // 3. Update Status Langganan (Sudah bayar bulan ini)
            $sub->update(['last_paid_at' => now()]);
        });

        return back()->with('success', 'Tagihan ' . $sub->name . ' berhasil dibayar!');
    }
    
    public function destroy($id)
    {
        Subscription::where('user_id', Auth::id())->findOrFail($id)->delete();
        return back()->with('success', 'Langganan dihapus.');
    }
}