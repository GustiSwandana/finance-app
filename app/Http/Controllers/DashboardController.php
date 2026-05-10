<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\Wallet;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $userId = Auth::id();
        $monthStart = now()->startOfMonth()->toDateString();
        $monthEnd = now()->endOfMonth()->toDateString();

        $wallets = Wallet::where('user_id', $userId)->orderBy('bank_name')->get();
        $bca = $wallets->firstWhere('bank_name', 'BCA');
        $mandiri = $wallets->firstWhere('bank_name', 'Mandiri');
        $bri = $wallets->firstWhere('bank_name', 'BRI');
        $otherWallets = $wallets->reject(fn (Wallet $wallet) => in_array($wallet->bank_name, ['BCA', 'Mandiri', 'BRI'], true))->values();
        $totalSaldo = $wallets->sum('balance');

        $monthlyIncome = Transaction::where('user_id', $userId)
            ->where('type', 'income')
            ->whereBetween('date', [$monthStart, $monthEnd])
            ->sum('amount');

        $monthlyExpense = Transaction::where('user_id', $userId)
            ->where('type', 'expense')
            ->whereBetween('date', [$monthStart, $monthEnd])
            ->sum('amount');

        $monthlyNet = $monthlyIncome - $monthlyExpense;
        $monthlyTransactionCount = Transaction::where('user_id', $userId)
            ->whereBetween('date', [$monthStart, $monthEnd])
            ->count();

        $todayTransactionCount = Transaction::where('user_id', $userId)
            ->whereDate('date', now()->toDateString())
            ->count();

        $expenseRatio = $monthlyIncome > 0
            ? min(100, round(($monthlyExpense / $monthlyIncome) * 100))
            : ($monthlyExpense > 0 ? 100 : 0);

        $walletCards = $wallets->sortByDesc('balance')->values();

        $latestTransactions = Transaction::where('user_id', $userId)
            ->with(['category', 'wallet'])
            ->orderBy('date', 'desc')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        return view('dashboard', compact(
            'bca',
            'mandiri',
            'bri',
            'otherWallets',
            'totalSaldo',
            'latestTransactions',
            'monthlyIncome',
            'monthlyExpense',
            'monthlyNet',
            'monthlyTransactionCount',
            'todayTransactionCount',
            'expenseRatio',
            'walletCards'
        ));
    }
}
