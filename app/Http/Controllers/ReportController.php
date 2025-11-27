<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Exports\MonthlyReportExport;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;

class ReportController extends Controller
{
    // 1. Fungsi Export EXCEL
    public function exportExcel()
    {
        return Excel::download(new MonthlyReportExport, 'Laporan_Bulanan.xlsx');
    }

    // 2. Fungsi Export PDF
    public function exportPdf()
    {
        $userId = Auth::id();

        // Kita ambil data lagi (Copy logic query bulanan)
        $monthlyStats = Transaction::where('user_id', $userId)
            ->select(
                DB::raw('DATE_FORMAT(date, "%Y-%m") as month'),
                DB::raw('SUM(CASE WHEN type = "income" THEN amount ELSE 0 END) as total_income'),
                DB::raw('SUM(CASE WHEN type = "expense" THEN amount ELSE 0 END) as total_expense')
            )
            ->groupBy('month')
            ->orderBy('month', 'desc')
            ->get();

        // Load View khusus PDF
        $pdf = Pdf::loadView('reports.export_view', compact('monthlyStats'));
        
        // Download file
        return $pdf->download('Laporan_Keuangan.pdf');
    }

    public function index()
    {
        $userId = Auth::id();

        // --- 1. LOGIKA REKAP BULANAN ---
        $monthlyStats = Transaction::where('user_id', $userId)
            ->select(
                DB::raw('DATE_FORMAT(date, "%Y-%m") as month'),
                DB::raw('SUM(CASE WHEN type = "income" THEN amount ELSE 0 END) as total_income'),
                DB::raw('SUM(CASE WHEN type = "expense" THEN amount ELSE 0 END) as total_expense')
            )
            ->groupBy('month')
            ->orderBy('month', 'desc')
            ->get();

        // --- 2. LOGIKA REKAP MINGGUAN (Tahun Ini) ---
        $weeklyStats = Transaction::where('user_id', $userId)
            ->whereYear('date', date('Y')) // Hanya tahun ini agar tidak kebanyakan
            ->select(
                DB::raw('WEEK(date, 1) as week_number'), // Mode 1 = Senin awal minggu
                DB::raw('MIN(date) as start_date'),
                DB::raw('MAX(date) as end_date'),
                DB::raw('SUM(CASE WHEN type = "income" THEN amount ELSE 0 END) as total_income'),
                DB::raw('SUM(CASE WHEN type = "expense" THEN amount ELSE 0 END) as total_expense')
            )
            ->groupBy('week_number')
            ->orderBy('week_number', 'desc')
            ->get();

        return view('reports.index', compact('monthlyStats', 'weeklyStats'));
    }
}