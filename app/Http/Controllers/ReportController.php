<?php

namespace App\Http\Controllers;

use App\Exports\TransactionDetailExport;
use App\Models\Transaction;
use App\Models\Wallet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
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
        $monthlyStats = $this->monthlyStatsQuery()->get();
        $pdf = Pdf::loadView('reports.export_view', compact('monthlyStats'));

        return $pdf->download('Laporan_Keuangan.pdf');
    }

    public function detail(Request $request)
    {
        $wallets = Wallet::where('user_id', Auth::id())->orderBy('bank_name')->get();
        $filters = $this->resolveDetailFilters($request);
        $baseQuery = $this->buildDetailQuery($filters);
        $transactions = (clone $baseQuery)
            ->with(['wallet', 'category'])
            ->paginate(20)
            ->withQueryString();
        $summary = $this->buildDetailSummary(clone $baseQuery);

        return view('reports.detail', array_merge($filters, $summary, [
            'wallets' => $wallets,
            'transactions' => $transactions,
        ]));
    }

    public function exportDetailExcel(Request $request)
    {
        $filters = $this->resolveDetailFilters($request);
        $transactions = $this->buildDetailQuery($filters)
            ->with(['wallet', 'category'])
            ->get();

        return Excel::download(new TransactionDetailExport($transactions, $filters), 'Laporan_Detail_Transaksi.xlsx');
    }

    public function exportDetailPdf(Request $request)
    {
        $filters = $this->resolveDetailFilters($request);
        $transactions = $this->buildDetailQuery($filters)
            ->with(['wallet', 'category'])
            ->get();
        $summary = $this->buildDetailSummary($this->buildDetailQuery($filters));

        $pdf = Pdf::loadView('reports.print_detail', array_merge($filters, $summary, [
            'transactions' => $transactions,
        ]));

        return $pdf->download('Laporan_Detail_Transaksi.pdf');
    }

    public function index()
    {
        $monthlyStats = $this->monthlyStatsQuery()->get();
        $weeklyStats = $this->weeklyStatsQuery()->get();

        return view('reports.index', compact('monthlyStats', 'weeklyStats'));
    }

    protected function resolveDetailFilters(Request $request): array
    {
        return [
            'search' => trim((string) $request->input('search', '')),
            'startDate' => $request->input('start_date', now()->startOfMonth()->toDateString()),
            'endDate' => $request->input('end_date', now()->toDateString()),
            'walletId' => $request->input('wallet_id', 'all'),
            'type' => $request->input('type', 'all'),
            'sort' => $request->input('sort', 'latest'),
        ];
    }

    protected function buildDetailQuery(array $filters)
    {
        $query = Transaction::query()
            ->where('user_id', Auth::id())
            ->whereDate('date', '>=', $filters['startDate'])
            ->whereDate('date', '<=', $filters['endDate']);

        if ($filters['search'] !== '') {
            $search = $filters['search'];
            $query->where(function ($builder) use ($search) {
                $builder->where('description', 'like', "%{$search}%")
                    ->orWhereHas('category', fn ($categoryQuery) => $categoryQuery->where('name', 'like', "%{$search}%"));
            });
        }

        if ($filters['walletId'] !== 'all') {
            $query->where('wallet_id', $filters['walletId']);
        }

        if ($filters['type'] !== 'all') {
            $query->where('type', $filters['type']);
        }

        return match ($filters['sort']) {
            'oldest' => $query->orderBy('date')->orderBy('created_at'),
            'highest' => $query->orderByDesc('amount')->orderByDesc('date'),
            'lowest' => $query->orderBy('amount')->orderByDesc('date'),
            default => $query->orderByDesc('date')->orderByDesc('created_at'),
        };
    }

    protected function buildDetailSummary($query): array
    {
        $summary = $query
            ->selectRaw('SUM(CASE WHEN type = "income" THEN amount ELSE 0 END) as total_income')
            ->selectRaw('SUM(CASE WHEN type = "expense" THEN amount ELSE 0 END) as total_expense')
            ->first();

        $totalIncome = (float) ($summary->total_income ?? 0);
        $totalExpense = (float) ($summary->total_expense ?? 0);

        return [
            'totalIncome' => $totalIncome,
            'totalExpense' => $totalExpense,
            'netBalance' => $totalIncome - $totalExpense,
        ];
    }

    protected function monthlyStatsQuery()
    {
        return Transaction::where('user_id', Auth::id())
            ->select(
                DB::raw('DATE_FORMAT(date, "%Y-%m") as month'),
                DB::raw('SUM(CASE WHEN type = "income" THEN amount ELSE 0 END) as total_income'),
                DB::raw('SUM(CASE WHEN type = "expense" THEN amount ELSE 0 END) as total_expense')
            )
            ->groupBy('month')
            ->orderBy('month', 'desc');
    }

    protected function weeklyStatsQuery()
    {
        return Transaction::where('user_id', Auth::id())
            ->whereYear('date', now()->year)
            ->select(
                DB::raw('WEEK(date, 1) as week_number'),
                DB::raw('MIN(date) as start_date'),
                DB::raw('MAX(date) as end_date'),
                DB::raw('SUM(CASE WHEN type = "income" THEN amount ELSE 0 END) as total_income'),
                DB::raw('SUM(CASE WHEN type = "expense" THEN amount ELSE 0 END) as total_expense')
            )
            ->groupBy('week_number')
            ->orderBy('week_number', 'desc');
    }
}
