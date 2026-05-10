<?php

use App\Models\Category;
use App\Models\Transaction;
use App\Models\User;
use App\Models\Wallet;

test('report detail page can filter transactions and keep transfer visible as a filter option', function () {
    $user = User::factory()->create();
    $wallet = Wallet::create([
        'user_id' => $user->id,
        'bank_name' => 'BCA',
        'type' => 'main',
        'balance' => 0,
    ]);
    $category = Category::create([
        'user_id' => $user->id,
        'name' => 'Gaji',
        'type' => 'income',
    ]);

    Transaction::create([
        'user_id' => $user->id,
        'wallet_id' => $wallet->id,
        'category_id' => $category->id,
        'amount' => 1500000,
        'type' => 'income',
        'date' => now()->toDateString(),
        'description' => 'Gaji Bulanan',
        'status' => 'completed',
    ]);

    Transaction::create([
        'user_id' => $user->id,
        'wallet_id' => $wallet->id,
        'amount' => 300000,
        'type' => 'transfer',
        'date' => now()->toDateString(),
        'description' => 'Transfer ke Mandiri',
        'status' => 'completed',
    ]);

    $response = $this->actingAs($user)->get(route('reports.detail', [
        'search' => 'Gaji',
        'type' => 'all',
    ]));

    $response->assertOk();
    $response->assertSee('Laporan Detail');
    $response->assertSee('Gaji Bulanan');
    $response->assertSee('Transfer');
});
