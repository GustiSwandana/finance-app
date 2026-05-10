<?php

use App\Models\Category;
use App\Models\Transaction;
use App\Models\User;
use App\Models\Wallet;

test('user can reset all own transactions and wallet balances', function () {
    $user = User::factory()->create();
    $otherUser = User::factory()->create();

    $wallet = Wallet::create([
        'user_id' => $user->id,
        'bank_name' => 'BCA',
        'type' => 'general',
        'balance' => 750000,
    ]);
    $otherWallet = Wallet::create([
        'user_id' => $otherUser->id,
        'bank_name' => 'Mandiri',
        'type' => 'general',
        'balance' => 900000,
    ]);

    $category = Category::create([
        'user_id' => $user->id,
        'name' => 'Gaji',
        'type' => 'income',
    ]);
    $otherCategory = Category::create([
        'user_id' => $otherUser->id,
        'name' => 'Gaji',
        'type' => 'income',
    ]);

    Transaction::create([
        'user_id' => $user->id,
        'wallet_id' => $wallet->id,
        'category_id' => $category->id,
        'amount' => 500000,
        'type' => 'income',
        'date' => now()->toDateString(),
        'description' => 'Gaji user',
        'status' => 'completed',
    ]);
    Transaction::create([
        'user_id' => $otherUser->id,
        'wallet_id' => $otherWallet->id,
        'category_id' => $otherCategory->id,
        'amount' => 900000,
        'type' => 'income',
        'date' => now()->toDateString(),
        'description' => 'Gaji user lain',
        'status' => 'completed',
    ]);

    $response = $this->actingAs($user)->delete(route('transactions.reset-all'), [
        'password' => 'password',
        'confirmation_text' => 'RESET TRANSAKSI',
        'confirm_reset_transactions' => '1',
    ]);

    $response->assertRedirect(route('profile.edit', absolute: false));
    $response->assertSessionHas('success');

    expect(Transaction::where('user_id', $user->id)->count())->toBe(0)
        ->and((float) $wallet->fresh()->balance)->toBe(0.0)
        ->and(Transaction::where('user_id', $otherUser->id)->count())->toBe(1)
        ->and((float) $otherWallet->fresh()->balance)->toBe(900000.0);
});

test('reset all transactions requires password text and checkbox confirmation', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)
        ->from('/profile')
        ->delete(route('transactions.reset-all'), [
            'password' => 'password',
            'confirmation_text' => 'reset transaksi',
        ]);

    $response
        ->assertSessionHasErrorsIn('transactionReset', ['confirmation_text', 'confirm_reset_transactions'])
        ->assertRedirect('/profile');
});
