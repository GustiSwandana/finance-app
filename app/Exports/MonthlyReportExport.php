<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Transaction;

class MonthlyReportExport implements FromView
{
    public function view(): View
    {
        $userId = Auth::id();

        // Ambil Data Bulanan (Sama seperti di Controller)
        $monthlyStats = Transaction::where('user_id', $userId)
            ->select(
                DB::raw('DATE_FORMAT(date, "%Y-%m") as month'),
                DB::raw('SUM(CASE WHEN type = "income" THEN amount ELSE 0 END) as total_income'),
                DB::raw('SUM(CASE WHEN type = "expense" THEN amount ELSE 0 END) as total_expense')
            )
            ->groupBy('month')
            ->orderBy('month', 'desc')
            ->get();

        // Kita pakai view khusus untuk export (akan kita buat di langkah 4)
        return view('reports.export_view', compact('monthlyStats'));
    }
}