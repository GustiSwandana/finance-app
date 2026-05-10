<?php

namespace App\Http\Controllers;

use App\Http\Requests\Imports\PreviewImportRequest;
use App\Http\Requests\Imports\StoreImportRequest;
use App\Models\Wallet;
use App\Models\Transaction;
use App\Models\Category;
use App\Services\TransactionImportService;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\ToArray;
use Maatwebsite\Excel\Facades\Excel;

class ImportController extends Controller
{
    public function create()
    {
        $wallets = Wallet::where('user_id', Auth::id())->get();
        return view('imports.create', compact('wallets'));
    }

    public function preview(PreviewImportRequest $request)
    {
        $validated = $request->validated();
        $wallet = $this->findUserWalletOrFail($validated['wallet_id']);
        $bankSource = $validated['bank_source'];

        ini_set('memory_limit', '512M');
        set_time_limit(300);

        try {
            $parser = new \Smalot\PdfParser\Parser();
            $service = new TransactionImportService();

            $allTransactions = [];
            $files = $request->file('bank_files');

            foreach ($files as $file) {
                $extension = strtolower($file->getClientOriginalExtension());
                $data = $extension === 'pdf'
                    ? $this->parsePdfFile($parser, $service, $file->getPathname(), $bankSource)
                    : $this->parseTabularFile($service, $file->getPathname(), $extension, $bankSource);

                if (!empty($data)) {
                    foreach ($data as $row) {
                        $row['source_file'] = $file->getClientOriginalName();
                        $allTransactions[] = $row;
                    }
                }
            }

            $sortedTransactions = collect($allTransactions)->sortBy('date')->values()->all();

            if (empty($sortedTransactions)) {
                return back()->with('error', 'Tidak ada transaksi terbaca. Pastikan format PDF sesuai.');
            }

            return view('imports.preview', [
                'transactions' => $sortedTransactions,
                'wallet' => $wallet,
                'bankSource' => $bankSource,
                'fileSummaries' => collect($sortedTransactions)
                    ->groupBy('source_file')
                    ->map(fn ($rows, $fileName) => [
                        'name' => $fileName,
                        'count' => $rows->count(),
                        'income' => $rows->where('type', 'income')->sum('amount'),
                        'expense' => $rows->where('type', 'expense')->sum('amount'),
                    ])
                    ->values()
                    ->all(),
            ]);
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal memproses file: ' . $e->getMessage());
        }
    }

    public function store(StoreImportRequest $request)
    {
        $validated = $request->validated();
        $data = $validated['transactions'] ?? null;
        $walletId = $validated['wallet_id'];

        if (!$data) {
            return redirect()->route('transactions.index')->with('error', 'Tidak ada data yang dipilih.');
        }

        $wallet = $this->findUserWalletOrFail($walletId);

        DB::transaction(function () use ($data, $wallet) {
            foreach ($data as $trxJson) {
                $trxData = json_decode($trxJson, true);

                if (! is_array($trxData)) {
                    continue;
                }

                $category = Category::firstOrCreate(
                    ['user_id' => Auth::id(), 'name' => $trxData['category_guess']],
                    ['type' => $trxData['type']]
                );

                Transaction::create([
                    'user_id' => Auth::id(),
                    'wallet_id' => $wallet->id,
                    'category_id' => $category->id,
                    'amount' => $trxData['amount'],
                    'type' => $trxData['type'],
                    'date' => Carbon::parse($trxData['date'])->toDateString(),
                    'description' => Str::limit($trxData['description'] ?? 'Mutasi bank', 250, ''),
                    'status' => 'completed'
                ]);

                if ($trxData['type'] == 'income') {
                    $wallet->increment('balance', $trxData['amount']);
                } else {
                    $wallet->decrement('balance', $trxData['amount']);
                }
            }
        });

        return redirect()->route('transactions.index')->with('success', count($data) . ' Mutasi berhasil diimpor!');
    }

    protected function findUserWalletOrFail(int|string $walletId): Wallet
    {
        return Wallet::where('user_id', Auth::id())->findOrFail($walletId);
    }

    private function parsePdfFile($parser, TransactionImportService $service, string $path, string $bankSource): array
    {
        $pdf = $parser->parseFile($path);
        $fullText = '';

        foreach ($pdf->getPages() as $page) {
            $fullText .= $page->getText() . ' ';
        }

        return $service->parse($fullText, $bankSource);
    }

    private function parseTabularFile(TransactionImportService $service, string $path, string $extension, string $bankSource): array
    {
        if (in_array($extension, ['xlsx', 'xls'], true)) {
            $sheets = Excel::toArray(new class implements ToArray {
                public function array(array $array)
                {
                    //
                }
            }, $path);

            $rows = collect($sheets)->flatten(1)->all();

            return $service->parseRows($rows, $bankSource);
        }

        return $service->parseRows($this->readDelimitedRows($path), $bankSource);
    }

    private function readDelimitedRows(string $path): array
    {
        $rows = [];
        $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) ?: [];
        $firstLine = $lines[0] ?? '';
        $delimiter = str_contains($firstLine, ';') ? ';' : ',';

        foreach ($lines as $line) {
            $rows[] = str_getcsv($line, $delimiter);
        }

        return $rows;
    }
}
