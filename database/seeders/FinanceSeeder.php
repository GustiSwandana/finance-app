<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Wallet;
use App\Models\Category;
use App\Models\User;

class FinanceSeeder extends Seeder
{
    public function run(): void
    {
        // Pastikan ada user dulu (kita ambil user pertama)
        $user = User::first();

        if ($user) {
            // 1. Buat 3 Akun Bank Tetap
            $wallets = [
                ['bank_name' => 'BCA', 'type' => 'main', 'balance' => 0],
                ['bank_name' => 'Mandiri', 'type' => 'savings', 'balance' => 0],
                ['bank_name' => 'BRI', 'type' => 'expense', 'balance' => 0],
            ];

            foreach ($wallets as $w) {
                Wallet::create([
                    'user_id' => $user->id,
                    'bank_name' => $w['bank_name'],
                    'type' => $w['type'],
                    'balance' => $w['balance']
                ]);
            }

            // 2. Buat Kategori Pemasukan
            $incomeCategories = ['Gaji Bulanan', 'Bonus', 'Penjualan', 'Investasi', 'Lainnya'];
            foreach ($incomeCategories as $cat) {
                Category::create([
                    'user_id' => $user->id,
                    'name' => $cat,
                    'type' => 'income'
                ]);
            }

            // 3. Buat Kategori Pengeluaran (Untuk nanti)
            $expenseCategories = ['Makanan/Minuman', 'Transportasi', 'Belanja', 'Tagihan'];
            foreach ($expenseCategories as $cat) {
                Category::create([
                    'user_id' => $user->id,
                    'name' => $cat,
                    'type' => 'expense'
                ]);
            }
        }
    }
}