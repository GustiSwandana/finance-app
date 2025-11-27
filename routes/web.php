<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\TransactionController;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\SubscriptionController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Halaman Depan (Landing Page)
Route::get('/', function () {
    return view('welcome');
});

// --- GROUP 1: PROFILE (Bawaan Laravel Breeze) ---
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// --- GROUP 2: APLIKASI UTAMA (Dashboard & Transaksi) ---
Route::middleware(['auth', 'verified'])->group(function () {

    // 1. Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Route Riwayat Transaksi (Index)
    Route::get('/transactions', [TransactionController::class, 'index'])->name('transactions.index');

    // 2. Fitur Pemasukan (Income)
    Route::get('/income/create', [TransactionController::class, 'createIncome'])->name('income.create');
    Route::post('/income', [TransactionController::class, 'storeIncome'])->name('income.store');

    // 3. Fitur Pengeluaran (Expense)
    Route::get('/expense/create', [TransactionController::class, 'createExpense'])->name('expense.create');
    Route::post('/expense', [TransactionController::class, 'storeExpense'])->name('expense.store');

    // 4. Fitur Transfer (TAMBAHKAN BAGIAN INI) <---
    Route::get('/transfer/create', [TransactionController::class, 'createTransfer'])->name('transfer.create');
    Route::post('/transfer', [TransactionController::class, 'storeTransfer'])->name('transfer.store');

    // 5. Manajemen Bank & Kategori (Dinamis)
    Route::resource('wallets', \App\Http\Controllers\WalletController::class)->only(['index', 'store', 'destroy']);
    Route::resource('categories', \App\Http\Controllers\CategoryController::class)->only(['index', 'store', 'destroy']);

    // Route Edit & Update
    Route::get('/transaction/{id}/edit', [TransactionController::class, 'edit'])->name('transactions.edit');
    Route::put('/transaction/{id}', [TransactionController::class, 'update'])->name('transactions.update');

    // Route Delete
    Route::delete('/transaction/{id}', [TransactionController::class, 'destroy'])->name('transactions.destroy');

    // Laporan Keuangan
    Route::get('/reports', [App\Http\Controllers\ReportController::class, 'index'])->name('reports.index');

    // Fitur Langganan / Tagihan
    Route::get('/subscriptions', [App\Http\Controllers\SubscriptionController::class, 'index'])->name('subscriptions.index');
    Route::post('/subscriptions', [App\Http\Controllers\SubscriptionController::class, 'store'])->name('subscriptions.store');
    Route::post('/subscriptions/{id}/pay', [App\Http\Controllers\SubscriptionController::class, 'pay'])->name('subscriptions.pay');
    Route::delete('/subscriptions/{id}', [App\Http\Controllers\SubscriptionController::class, 'destroy'])->name('subscriptions.destroy');

    // Route Export
    Route::get('/reports/export/excel', [ReportController::class, 'exportExcel'])->name('reports.export.excel');
    Route::get('/reports/export/pdf', [ReportController::class, 'exportPdf'])->name('reports.export.pdf');

    // Route Edit & Update
    Route::get('/transaction/{id}/edit', [TransactionController::class, 'edit'])->name('transactions.edit');
    Route::put('/transaction/{id}', [TransactionController::class, 'update'])->name('transactions.update');
    
    // Route Delete
    Route::delete('/transaction/{id}', [TransactionController::class, 'destroy'])->name('transactions.destroy');

    // Route Quick Add (AJAX)
    Route::post('/wallets/ajax', [App\Http\Controllers\WalletController::class, 'storeAjax'])->name('wallets.storeAjax');
    Route::post('/categories/ajax', [App\Http\Controllers\CategoryController::class, 'storeAjax'])->name('categories.storeAjax');

    // Manajemen User
    Route::resource('users', \App\Http\Controllers\UserController::class);

    // Utang Piutang
    Route::get('/debts', [App\Http\Controllers\DebtController::class, 'index'])->name('debts.index');
    Route::post('/debts', [App\Http\Controllers\DebtController::class, 'store'])->name('debts.store');
    Route::post('/debts/{id}/pay', [App\Http\Controllers\DebtController::class, 'markAsPaid'])->name('debts.pay');

    // Fitur Budgeting
    Route::get('/budgets', [App\Http\Controllers\BudgetController::class, 'index'])->name('budgets.index');
    Route::post('/budgets', [App\Http\Controllers\BudgetController::class, 'store'])->name('budgets.store');

    // Route untuk Scan Struk (OCR)
    Route::post('/transactions/scan', [TransactionController::class, 'storeFromReceipt'])->name('transactions.scan');
});

// --- ROUTE KHUSUS: SETUP DATA AWAL (Hanya dijalankan sekali) ---
Route::get('/setup-data', function () {
    $user = Auth::user();

    if (!$user) {
        return "Error: Anda harus Login dulu baru bisa klik link ini!";
    }

    // 1. Buat Data Bank (Jika belum ada)
    \App\Models\Wallet::firstOrCreate(
        ['user_id' => $user->id, 'bank_name' => 'BCA'],
        ['type' => 'main', 'balance' => 0]
    );
    \App\Models\Wallet::firstOrCreate(
        ['user_id' => $user->id, 'bank_name' => 'Mandiri'],
        ['type' => 'savings', 'balance' => 0]
    );
    \App\Models\Wallet::firstOrCreate(
        ['user_id' => $user->id, 'bank_name' => 'BRI'],
        ['type' => 'expense', 'balance' => 0]
    );

    // 2. Buat Kategori Pemasukan
    $income = ['Gaji Bulanan', 'Bonus/THR', 'Penjualan', 'Investasi', 'Lainnya'];
    foreach ($income as $cat) {
        \App\Models\Category::firstOrCreate(
            ['user_id' => $user->id, 'name' => $cat, 'type' => 'income']
        );
    }

    // 3. Buat Kategori Pengeluaran
    $expense = ['Makanan & Minuman', 'Transportasi', 'Belanja Bulanan', 'Tagihan (Listrik/Air)', 'Hiburan', 'Lainnya'];
    foreach ($expense as $cat) {
        \App\Models\Category::firstOrCreate(
            ['user_id' => $user->id, 'name' => $cat, 'type' => 'expense']
        );
    }

    return redirect()->route('dashboard')->with('success', 'Data Bank & Kategori berhasil dibuat! Silakan mulai transaksi.');
})->middleware('auth');



require __DIR__ . '/auth.php';
