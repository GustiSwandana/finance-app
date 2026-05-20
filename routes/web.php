<?php

use App\Http\Controllers\AdminApprovalController;
use App\Http\Controllers\BudgetController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DebtController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ImportController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\SubscriptionController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\WalletController;
use App\Http\Controllers\WhatsAppTransactionController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Halaman Depan (Landing Page)
Route::get('/', function () {
    return view('welcome');
});

Route::post('/webhooks/whatsapp/transactions', [WhatsAppTransactionController::class, 'store'])
    ->name('webhooks.whatsapp.transactions');

Route::middleware(['auth', 'active'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['auth', 'active', 'verified'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::view('/calculator', 'calculator.index')->name('calculator.index');
    Route::get('/transactions', [TransactionController::class, 'index'])->name('transactions.index');

    Route::get('/income/create', [TransactionController::class, 'createIncome'])->name('income.create');
    Route::post('/income', [TransactionController::class, 'storeIncome'])->name('income.store');

    Route::get('/expense/create', [TransactionController::class, 'createExpense'])->name('expense.create');
    Route::post('/expense', [TransactionController::class, 'storeExpense'])->name('expense.store');

    Route::get('/transfer/create', [TransactionController::class, 'createTransfer'])->name('transfer.create');
    Route::post('/transfer', [TransactionController::class, 'storeTransfer'])->name('transfer.store');

    Route::resource('wallets', WalletController::class)->only(['index', 'store', 'destroy']);
    Route::resource('categories', CategoryController::class)->only(['index', 'store', 'destroy']);

    Route::get('/transaction/{id}/edit', [TransactionController::class, 'edit'])->name('transactions.edit');
    Route::put('/transaction/{id}', [TransactionController::class, 'update'])->name('transactions.update');
    Route::delete('/transaction/{id}', [TransactionController::class, 'destroy'])->name('transactions.destroy');
    Route::delete('/transactions/reset/all', [TransactionController::class, 'resetAll'])->name('transactions.reset-all');

    Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
    Route::get('/reports/detail', [ReportController::class, 'detail'])->name('reports.detail');
    Route::get('/reports/detail/export/excel', [ReportController::class, 'exportDetailExcel'])->name('reports.detail.excel');
    Route::get('/reports/detail/export/pdf', [ReportController::class, 'exportDetailPdf'])->name('reports.detail.pdf');

    Route::get('/subscriptions', [SubscriptionController::class, 'index'])->name('subscriptions.index');
    Route::post('/subscriptions', [SubscriptionController::class, 'store'])->name('subscriptions.store');
    Route::post('/subscriptions/{id}/pay', [SubscriptionController::class, 'pay'])->name('subscriptions.pay');
    Route::delete('/subscriptions/{id}', [SubscriptionController::class, 'destroy'])->name('subscriptions.destroy');

    Route::get('/reports/export/excel', [ReportController::class, 'exportExcel'])->name('reports.export.excel');
    Route::get('/reports/export/pdf', [ReportController::class, 'exportPdf'])->name('reports.export.pdf');

    Route::post('/wallets/ajax', [WalletController::class, 'storeAjax'])->name('wallets.storeAjax');
    Route::post('/categories/ajax', [CategoryController::class, 'storeAjax'])->name('categories.storeAjax');

    Route::get('/debts', [DebtController::class, 'index'])->name('debts.index');
    Route::post('/debts', [DebtController::class, 'store'])->name('debts.store');
    Route::get('/debts/{id}/edit', [DebtController::class, 'edit'])->name('debts.edit');
    Route::put('/debts/{id}', [DebtController::class, 'update'])->name('debts.update');
    Route::post('/debts/{id}/pay', [DebtController::class, 'markAsPaid'])->name('debts.pay');
    Route::delete('/debts/{id}', [DebtController::class, 'destroy'])->name('debts.destroy');

    Route::get('/budgets', [BudgetController::class, 'index'])->name('budgets.index');
    Route::post('/budgets', [BudgetController::class, 'store'])->name('budgets.store');

    Route::get('/bank-mutations', [ImportController::class, 'create'])->name('bank-mutations.create');
    Route::post('/bank-mutations/preview', [ImportController::class, 'preview'])->name('bank-mutations.preview');
    Route::post('/bank-mutations', [ImportController::class, 'store'])->name('bank-mutations.store');

    Route::redirect('/imports/create', '/bank-mutations')->name('import.create');
    Route::post('/imports/preview', [ImportController::class, 'preview'])->name('import.preview');
    Route::post('/imports', [ImportController::class, 'store'])->name('import.store');

    Route::post('/transactions/scan', [TransactionController::class, 'storeFromReceipt'])->name('transactions.scan');
});

Route::middleware(['auth', 'active', 'admin'])->group(function () {
    Route::get('/admin/approvals', [AdminApprovalController::class, 'index'])->name('admin.approvals.index');
    Route::post('/admin/approvals/{id}/approve', [AdminApprovalController::class, 'approve'])->name('admin.approve');
    Route::delete('/admin/approvals/{id}/reject', [AdminApprovalController::class, 'reject'])->name('admin.reject');

    Route::resource('users', UserController::class)->except(['show']);
});

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
})->middleware(['auth', 'active']);

require __DIR__ . '/auth.php';
