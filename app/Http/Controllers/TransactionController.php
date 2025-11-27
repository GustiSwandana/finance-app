<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Transaction;
use App\Models\Wallet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Services\ReceiptScanner;

class TransactionController extends Controller
{
    // Menampilkan Daftar Riwayat Transaksi
    public function index()
    {
        $userId = Auth::id();

        // 1. Ambil Data untuk Form Input
        $wallets = Wallet::where('user_id', $userId)->get();
        $incomeCategories = Category::where('user_id', $userId)->where('type', 'income')->get();
        $expenseCategories = Category::where('user_id', $userId)->where('type', 'expense')->get();

        // 2. Ambil Data untuk Tabel Riwayat
        $transactions = Transaction::where('user_id', $userId)
            ->with(['wallet', 'category'])
            ->orderBy('date', 'desc')
            ->orderBy('created_at', 'desc')
            ->paginate(5); // Kita kurangi jadi 5 agar halaman tidak terlalu panjang

        return view('transactions.index', compact('wallets', 'incomeCategories', 'expenseCategories', 'transactions'));
    }

    // A. Menampilkan Halaman Form Pemasukan
    public function createIncome()
    {
        // Ambil dompet user
        $wallets = Wallet::where('user_id', Auth::id())->get();

        // Ambil kategori khusus 'income' (Pemasukan)
        $categories = Category::where('user_id', Auth::id())
            ->where('type', 'income')
            ->get();

        return view('transactions.create_income', compact('wallets', 'categories'));
    }

    // B. Memproses Simpan Data
    public function storeIncome(Request $request)
    {
        // 1. Validasi Input
        $request->validate([
            'amount' => 'required|numeric|min:1000',
            'wallet_id' => 'required',
            'category_id' => 'required',
            'date' => 'required|date',
            'description' => 'nullable|string',
        ]);

        // 2. Jalankan Transaksi Database (Agar aman)
        DB::transaction(function () use ($request) {

            // Simpan ke tabel transactions
            Transaction::create([
                'user_id' => Auth::id(),
                'wallet_id' => $request->wallet_id,
                'category_id' => $request->category_id,
                'amount' => $request->amount,
                'type' => 'income', // Tipe Pemasukan
                'date' => $request->date,
                'description' => $request->description,
                'status' => 'completed'
            ]);

            // Tambah Saldo Dompet
            $wallet = Wallet::find($request->wallet_id);
            $wallet->increment('balance', $request->amount);
        });

        // 3. Kembali ke dashboard dengan pesan sukses
        return redirect()->route('dashboard')->with('success', 'Saldo berhasil ditambahkan!');
    }

    // C. Tampilkan Form Pengeluaran
    public function createExpense()
    {
        $wallets = Wallet::where('user_id', Auth::id())->get();

        // Ambil kategori khusus 'expense' (Pengeluaran)
        $categories = Category::where('user_id', Auth::id())
            ->where('type', 'expense')
            ->get();

        return view('transactions.create_expense', compact('wallets', 'categories'));
    }

    // D. Proses Simpan Pengeluaran
    public function storeExpense(Request $request)
    {
        // 1. Validasi Input Dasar
        $request->validate([
            'amount' => 'required|numeric|min:1000',
            'wallet_id' => 'required',
            'category_id' => 'required',
            'date' => 'required|date',
        ]);

        // 2. LOGIKA SPESIFIK: Makanan harus pakai BCA
        $category = Category::find($request->category_id);
        $wallet = Wallet::find($request->wallet_id);

        // Cek apakah kategori mengandung kata "Makanan" (case insensitive) dan Bank bukan BCA
        if (stripos($category->name, 'Makanan') !== false && $wallet->bank_name !== 'BCA') {
            return back()->withErrors(['msg' => 'Aturan Pribadi: Untuk Makanan & Minuman, wajib menggunakan saldo BCA!'])->withInput();
        }

        // Cek Saldo Cukup atau Tidak
        if ($wallet->balance < $request->amount) {
            return back()->withErrors(['msg' => 'Saldo tidak cukup di dompet ' . $wallet->bank_name])->withInput();
        }

        // 3. Jalankan Transaksi
        DB::transaction(function () use ($request, $wallet) {
            // Simpan Riwayat
            Transaction::create([
                'user_id' => Auth::id(),
                'wallet_id' => $request->wallet_id,
                'category_id' => $request->category_id,
                'amount' => $request->amount,
                'type' => 'expense', // TIPE PENGELUARAN
                'date' => $request->date,
                'description' => $request->description,
                'status' => 'completed'
            ]);

            // Kurangi Saldo (Decrement)
            $wallet->decrement('balance', $request->amount);
        });

        return redirect()->route('dashboard')->with('success', 'Pengeluaran berhasil dicatat!');
    }

    // E. Tampilkan Form Transfer
    public function createTransfer()
    {
        $userId = Auth::id();
        $wallets = Wallet::where('user_id', $userId)->get();

        // JIKA BELUM PUNYA BANK, KIRIM ERROR ATAU REDIRECT KE SETUP
        if ($wallets->isEmpty()) {
            // Opsi A: Auto create (Cepat)
            return redirect('/setup-data');

            // Opsi B: Redirect ke halaman kelola bank dengan pesan (Lebih sopan)
            // return redirect()->route('wallets.index')->with('error', 'Anda belum memiliki akun bank! Silakan buat minimal 2 akun untuk transfer.');
        }

        // Jika bank cuma 1, tidak bisa transfer
        if ($wallets->count() < 2) {
            return redirect()->route('wallets.index')->with('error', 'Minimal butuh 2 akun bank untuk melakukan transfer.');
        }

        return view('transactions.create_transfer', compact('wallets'));
    }
    // F. Proses Simpan Transfer
    public function storeTransfer(Request $request)
    {
        // 1. Validasi
        $request->validate([
            'source_wallet_id' => 'required|exists:wallets,id',
            'destination_wallet_id' => 'required|exists:wallets,id|different:source_wallet_id',
            'amount' => 'required|numeric|min:1000',
            'date' => 'required|date',
        ]);

        $sourceWallet = Wallet::find($request->source_wallet_id);
        $destWallet = Wallet::find($request->destination_wallet_id);

        // 2. Cek Saldo Cukup?
        if ($sourceWallet->balance < $request->amount) {
            return back()->withErrors(['msg' => 'Saldo di ' . $sourceWallet->bank_name . ' tidak cukup untuk transfer!'])->withInput();
        }

        // 3. Eksekusi Pemindahan (Database Transaction)
        DB::transaction(function () use ($request, $sourceWallet, $destWallet) {

            // A. Kurangi Saldo Pengirim
            $sourceWallet->decrement('balance', $request->amount);

            // B. Tambah Saldo Penerima
            $destWallet->increment('balance', $request->amount);

            // C. Catat Riwayat (Opsional: Kita catat 2 kali agar muncul di history kedua bank)

            // Catatan untuk Pengirim (Uang Keluar)
            Transaction::create([
                'user_id' => Auth::id(),
                'wallet_id' => $sourceWallet->id,
                'amount' => $request->amount,
                'type' => 'transfer', // Tipe Transfer
                'date' => $request->date,
                'description' => 'Transfer ke ' . $destWallet->bank_name,
                'status' => 'completed'
            ]);

            // Catatan untuk Penerima (Uang Masuk)
            Transaction::create([
                'user_id' => Auth::id(),
                'wallet_id' => $destWallet->id,
                'amount' => $request->amount,
                'type' => 'transfer',
                'date' => $request->date,
                'description' => 'Terima dari ' . $sourceWallet->bank_name,
                'status' => 'completed'
            ]);
        });

        return redirect()->route('dashboard')->with('success', 'Transfer dana berhasil!');
    }

    // G. Tampilkan Halaman Edit
    public function edit($id)
    {
        $transaction = Transaction::where('user_id', Auth::id())->findOrFail($id);
        $wallets = Wallet::where('user_id', Auth::id())->get();

        // Ambil kategori sesuai tipe transaksi (income/expense)
        $categories = Category::where('user_id', Auth::id())
            ->where('type', $transaction->type)
            ->get();

        return view('transactions.edit', compact('transaction', 'wallets', 'categories'));
    }

    // H. Proses Update Data
    public function update(Request $request, $id)
    {
        $transaction = Transaction::where('user_id', Auth::id())->findOrFail($id);

        // 1. Validasi
        $request->validate([
            'amount' => 'required|numeric|min:1000',
            'wallet_id' => 'required',
            'category_id' => 'required',
            'date' => 'required|date',
        ]);

        // 2. LOGIKA KHUSUS (Validasi Makanan = BCA) - Hanya jika Expense
        if ($transaction->type == 'expense') {
            $category = Category::find($request->category_id);
            $wallet = Wallet::find($request->wallet_id);
            // Cek apakah kategori mengandung kata "Makanan" dan Bank bukan BCA
            if (stripos($category->name, 'Makanan') !== false && $wallet->bank_name !== 'BCA') {
                return back()->withErrors(['msg' => 'Aturan Pribadi: Edit gagal! Makanan tetap wajib pakai BCA.']);
            }
        }

        // 3. EKSEKUSI UPDATE & PENYESUAIAN SALDO
        DB::transaction(function () use ($request, $transaction) {

            // A. Kembalikan Saldo Lama (Revert) ke kondisi sebelum transaksi
            $oldWallet = Wallet::find($transaction->wallet_id);
            if ($transaction->type == 'income') {
                $oldWallet->decrement('balance', $transaction->amount); // Tarik kembali uang masuk
            } elseif ($transaction->type == 'expense') {
                $oldWallet->increment('balance', $transaction->amount); // Kembalikan uang keluar
            }

            // B. Update Data Transaksi di Database
            $transaction->update([
                'wallet_id' => $request->wallet_id,
                'category_id' => $request->category_id,
                'amount' => $request->amount,
                'date' => $request->date,
                'description' => $request->description,
            ]);

            // C. Terapkan Saldo Baru (Apply New) ke dompet yang (mungkin) baru
            $newWallet = Wallet::find($request->wallet_id);
            if ($transaction->type == 'income') {
                $newWallet->increment('balance', $request->amount);
            } elseif ($transaction->type == 'expense') {
                $newWallet->decrement('balance', $request->amount);
            }
        });

        return redirect()->route('transactions.index')->with('success', 'Transaksi berhasil diperbarui!');
    }

    // I. Hapus Transaksi
    public function destroy($id)
    {
        $transaction = Transaction::where('user_id', Auth::id())->findOrFail($id);

        DB::transaction(function () use ($transaction) {
            // Kembalikan Saldo sebelum dihapus
            $wallet = Wallet::find($transaction->wallet_id);

            if ($transaction->type == 'income') {
                // Hapus pemasukan = Saldo berkurang
                $wallet->decrement('balance', $transaction->amount);
            } elseif ($transaction->type == 'expense') {
                // Hapus pengeluaran = Uang balik (Saldo bertambah)
                $wallet->increment('balance', $transaction->amount);
            }

            // Hapus Data Permanen
            $transaction->delete();
        });

        return back()->with('success', 'Transaksi dihapus & saldo telah disesuaikan.');
    }

    public function storeFromReceipt(Request $request)
    {
        $request->validate([
            'receipt' => 'required|image|max:4096', // Max 4MB
            'wallet_id' => 'required|exists:wallets,id', // User tetap harus pilih sumber dana
        ]);

        // 1. Upload Gambar Dulu
        $path = $request->file('receipt')->store('receipts', 'public');

        // 2. Kirim ke AI untuk dibaca
        $scanner = new ReceiptScanner();
        $data = $scanner->scan(storage_path('app/public/' . $path));

        if (!$data) {
            return back()->withErrors(['msg' => 'Gagal membaca struk. Coba foto lebih jelas.']);
        }

        // 3. Cari ID Kategori berdasarkan tebakan AI
        // Misal AI menebak "Makanan", kita cari ID kategori "Makanan" di DB
        $category = Category::where('user_id', Auth::id())
            ->where('name', 'like', '%' . $data['category'] . '%')
            ->first();
            
        // Jika kategori tidak ketemu, pakai kategori 'Lainnya' atau null
        $categoryId = $category ? $category->id : null;

        // 4. Simpan Transaksi Otomatis
        DB::transaction(function () use ($request, $data, $path, $categoryId) {
            Transaction::create([
                'user_id' => Auth::id(),
                'wallet_id' => $request->wallet_id,
                'category_id' => $categoryId,
                'amount' => $data['amount'],
                'type' => 'expense', // Asumsi struk biasanya pengeluaran
                'date' => $data['date'] ?? now(),
                'description' => 'Scan: ' . $data['merchant'],
                'receipt_image' => $path,
                'status' => 'completed'
            ]);

            // Potong Saldo
            $wallet = Wallet::find($request->wallet_id);
            $wallet->decrement('balance', $data['amount']);
        });

        return redirect()->route('transactions.index')->with('success', 'Struk berhasil discan & dicatat! Merchant: ' . $data['merchant']);
    }
}
