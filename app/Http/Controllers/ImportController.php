<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Wallet;
use App\Models\Transaction;
use App\Models\Category;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Smalot\PdfParser\Parser; // Library PDF
use App\Services\TransactionImportService; // Service kita

class ImportController extends Controller
{
    // 1. TAMPILKAN FORM UPLOAD
    public function create()
    {
        $wallets = Wallet::where('user_id', Auth::id())->get();
        return view('imports.create', compact('wallets'));
    }

    // 2. PROSES PREVIEW (Fungsi yang hilang)
    public function preview(Request $request)
    {
        // 1. Validasi
        $request->validate([
            'bank_files' => 'required',
            'bank_files.*' => 'mimes:pdf|max:10240', // Max 10MB per file
            'wallet_id' => 'required|exists:wallets,id',
        ]);

        // 2. TINGKATKAN LIMIT MEMORI & WAKTU (PENTING UNTUK FILE TEBAL)
        ini_set('memory_limit', '512M'); // Izinkan pakai RAM sampai 512MB
        set_time_limit(300); // Izinkan proses berjalan sampai 5 menit

        try {
            $parser = new \Smalot\PdfParser\Parser();
            $service = new \App\Services\TransactionImportService();

            $allTransactions = [];
            $files = $request->file('bank_files');

            foreach ($files as $file) {
                // Parse PDF
                $pdf = $parser->parseFile($file->getPathname());

                // --- PERBAIKAN: BACA PER HALAMAN ---
                $text = "";
                $pages = $pdf->getPages(); // Ambil semua halaman

                // DI DALAM FUNGSI PREVIEW
                foreach ($files as $file) {
                    $pdf = $parser->parseFile($file->getPathname());

                    // Gabungkan semua halaman menjadi satu teks panjang
                    $fullText = "";
                    foreach ($pdf->getPages() as $page) {
                        $fullText .= $page->getText() . " "; // Pakai spasi pemisah
                    }

                    // Kirim ke Service
                    $data = $service->parse($fullText);

                    if (!empty($data)) {
                        $allTransactions = array_merge($allTransactions, $data);
                    }
                }
                // -----------------------------------

                // Kirim Teks Lengkap ke Service
                $data = $service->parse($text);

                if (!empty($data)) {
                    $allTransactions = array_merge($allTransactions, $data);
                }
            }

            // Urutkan Data (Terlama ke Terbaru)
            $sortedTransactions = collect($allTransactions)->sortBy('date')->values()->all();
            $wallet = \App\Models\Wallet::find($request->wallet_id);

            if (empty($sortedTransactions)) {
                // Debugging: Jika kosong, uncomment baris bawah ini untuk lihat teks mentah
                // dd($text); 
                return back()->with('error', 'Tidak ada transaksi terbaca. Pastikan format PDF sesuai.');
            }

            return view('imports.preview', [
                'transactions' => $sortedTransactions,
                'wallet' => $wallet
            ]);
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal memproses file: ' . $e->getMessage());
        }
    }

    // 3. SIMPAN DATA TERPILIH KE DATABASE
    public function store(Request $request)
    {
        $data = $request->transactions; // Array hasil centang user
        $walletId = $request->wallet_id;

        if (!$data) {
            return redirect()->route('transactions.index')->with('error', 'Tidak ada data yang dipilih.');
        }

        DB::transaction(function () use ($data, $walletId) {
            $wallet = Wallet::find($walletId);

            foreach ($data as $trxJson) {
                // Decode JSON string dari value checkbox
                $trxData = json_decode($trxJson, true);

                // Cari atau Buat Kategori Baru jika belum ada
                $category = Category::firstOrCreate(
                    ['user_id' => Auth::id(), 'name' => $trxData['category_guess']],
                    ['type' => $trxData['type']]
                );

                // Simpan Transaksi
                Transaction::create([
                    'user_id' => Auth::id(),
                    'wallet_id' => $walletId,
                    'category_id' => $category->id,
                    'amount' => $trxData['amount'],
                    'type' => $trxData['type'],
                    'date' => $trxData['date'] . ' ' . now()->format('H:i:s'), // Tambah jam sekarang
                    'description' => $trxData['description'],
                    'status' => 'completed'
                ]);

                // Update Saldo Dompet
                if ($trxData['type'] == 'income') {
                    $wallet->increment('balance', $trxData['amount']);
                } else {
                    $wallet->decrement('balance', $trxData['amount']);
                }
            }
        });

        return redirect()->route('transactions.index')->with('success', count($data) . ' Mutasi berhasil diimpor!');
    }
}
