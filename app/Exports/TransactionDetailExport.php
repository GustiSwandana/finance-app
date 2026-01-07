<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class TransactionDetailExport implements FromView, ShouldAutoSize
{
    protected $transactions;

    // Terima data transaksi dari Controller
    public function __construct($transactions)
    {
        $this->transactions = $transactions;
    }

    public function view(): View
    {
        // Kita akan menggunakan view khusus cetak yang bersih
        return view('reports.print_detail', [
            'transactions' => $this->transactions
        ]);
    }
}