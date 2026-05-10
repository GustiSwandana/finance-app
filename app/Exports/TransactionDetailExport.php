<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class TransactionDetailExport implements FromView, ShouldAutoSize
{
    protected $transactions;
    protected $filters;

    public function __construct($transactions, array $filters = [])
    {
        $this->transactions = $transactions;
        $this->filters = $filters;
    }

    public function view(): View
    {
        $totalIn = $this->transactions->where('type', 'income')->sum('amount');
        $totalOut = $this->transactions->where('type', 'expense')->sum('amount');

        return view('reports.print_detail', [
            'transactions' => $this->transactions,
            'totalIncome' => $totalIn,
            'totalExpense' => $totalOut,
            'netBalance' => $totalIn - $totalOut,
            'startDate' => $this->filters['startDate'] ?? now()->startOfMonth()->toDateString(),
            'endDate' => $this->filters['endDate'] ?? now()->toDateString(),
        ]);
    }
}
