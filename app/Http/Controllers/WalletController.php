<?php

namespace App\Http\Controllers;

use App\Models\Wallet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WalletController extends Controller
{
    public function index()
    {
        $wallets = Wallet::where('user_id', Auth::id())->get();
        return view('wallets.index', compact('wallets'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'bank_name' => 'required|string|max:50',
            'balance' => 'required|numeric|min:0'
        ]);

        Wallet::create([
            'user_id' => Auth::id(),
            'bank_name' => $request->bank_name,
            'type' => 'general', // Default type
            'balance' => $request->balance
        ]);

        return back()->with('success', 'Bank baru berhasil ditambahkan!');
    }

    public function destroy($id)
    {
        $wallet = Wallet::where('user_id', Auth::id())->where('id', $id)->firstOrFail();
        // Opsional: Cek jika ada transaksi terkait sebelum hapus
        $wallet->delete();
        
        return back()->with('success', 'Bank berhasil dihapus.');
    }

    // Fungsi Quick Add Bank via AJAX
    public function storeAjax(Request $request)
    {
        $request->validate([
            'bank_name' => 'required|string|max:50',
            'balance' => 'nullable|numeric|min:0' // Saldo awal boleh kosong (0)
        ]);

        $wallet = \App\Models\Wallet::create([
            'user_id' => \Illuminate\Support\Facades\Auth::id(),
            'bank_name' => $request->bank_name,
            'type' => 'general',
            'balance' => $request->balance ?? 0
        ]);

        return response()->json([
            'success' => true,
            'data' => $wallet
        ]);
    }
}