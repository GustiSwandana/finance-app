<?php

use App\Models\Transaction;
use App\Models\User;
use App\Models\Wallet;

test('dashboard total saldo includes wallets outside the three default banks', function () {
    $user = User::factory()->create();

    $bca = Wallet::create([
        'user_id' => $user->id,
        'bank_name' => 'BCA',
        'type' => 'main',
        'balance' => 100000,
    ]);

    Wallet::create([
        'user_id' => $user->id,
        'bank_name' => 'Bank Jago',
        'type' => 'savings',
        'balance' => 250000,
    ]);

    Transaction::create([
        'user_id' => $user->id,
        'wallet_id' => $bca->id,
        'amount' => 50000,
        'type' => 'income',
        'date' => now()->toDateString(),
        'description' => 'Setoran',
        'status' => 'completed',
    ]);

    $response = $this->actingAs($user)->get('/dashboard');

    $response->assertOk();
    $response->assertSee('Rp 350.000');
    $response->assertSee('Bank Jago');
});
