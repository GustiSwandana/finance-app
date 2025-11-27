<?php

namespace App\Http\Controllers;

use App\Models\Wallet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $userId = Auth::id();

        // 1. Ambil Data Wallet User
        // Kita gunakan first() karena kita tahu user hanya punya 1 akun per bank (sesuai logika bisnis Anda)
        $bca = Wallet::where('user_id', $userId)->where('bank_name', 'BCA')->first();
        $mandiri = Wallet::where('user_id', $userId)->where('bank_name', 'Mandiri')->first();
        $bri = Wallet::where('user_id', $userId)->where('bank_name', 'BRI')->first();

        // 2. Hitung Total Saldo (Opsional, untuk info tambahan)
        $totalSaldo = ($bca->balance ?? 0) + ($mandiri->balance ?? 0) + ($bri->balance ?? 0);

        // 3. Kirim data ke View dashboard
        return view('dashboard', compact('bca', 'mandiri', 'bri', 'totalSaldo'));
    }
}