<?php

use App\Models\Category;
use App\Models\Transaction;
use App\Models\User;
use App\Models\Wallet;

beforeEach(function () {
    config(['services.whatsapp_transactions.token' => 'secret-token']);
});

test('whatsapp transaction webhook requires a valid token', function () {
    $response = $this->postJson(route('webhooks.whatsapp.transactions'), [
        'token' => 'wrong-token',
        'user_email' => 'user@example.com',
        'message' => 'trx masuk 50000 BCA Gaji Bonus',
    ]);

    $response
        ->assertForbidden()
        ->assertJsonPath('success', false);
});

test('user can create income transaction from whatsapp message', function () {
    $user = User::factory()->create(['email' => 'user@example.com']);
    $wallet = Wallet::create([
        'user_id' => $user->id,
        'bank_name' => 'BCA',
        'type' => 'main',
        'balance' => 100000,
    ]);
    $category = Category::create([
        'user_id' => $user->id,
        'name' => 'Gaji',
        'type' => 'income',
    ]);

    $response = $this->postJson(route('webhooks.whatsapp.transactions'), [
        'token' => 'secret-token',
        'user_email' => $user->email,
        'message' => 'trx masuk 50000 BCA Gaji Bonus proyek',
    ]);

    $response
        ->assertOk()
        ->assertJsonPath('success', true);

    $transaction = Transaction::first();

    expect(Transaction::count())->toBe(1)
        ->and((float) $wallet->fresh()->balance)->toBe(150000.0)
        ->and((float) $transaction->amount)->toBe(50000.0)
        ->and($transaction->category_id)->toBe($category->id)
        ->and($transaction->description)->toBe('Bonus proyek');
});

test('user can create expense transaction from whatsapp message', function () {
    $user = User::factory()->create(['email' => 'user@example.com']);
    $wallet = Wallet::create([
        'user_id' => $user->id,
        'bank_name' => 'BCA',
        'type' => 'main',
        'balance' => 100000,
    ]);
    Category::create([
        'user_id' => $user->id,
        'name' => 'Makanan',
        'type' => 'expense',
    ]);

    $response = $this->postJson(route('webhooks.whatsapp.transactions'), [
        'token' => 'secret-token',
        'user_email' => $user->email,
        'message' => 'trx keluar 25000 BCA Makanan Makan siang',
    ]);

    $response
        ->assertOk()
        ->assertJsonPath('success', true);

    expect(Transaction::count())->toBe(1)
        ->and((float) $wallet->fresh()->balance)->toBe(75000.0)
        ->and(Transaction::first()->description)->toBe('Makan siang');
});

test('user can update transaction from whatsapp message and wallet balance is adjusted', function () {
    $user = User::factory()->create(['email' => 'user@example.com']);
    $wallet = Wallet::create([
        'user_id' => $user->id,
        'bank_name' => 'BCA',
        'type' => 'main',
        'balance' => 150000,
    ]);
    $category = Category::create([
        'user_id' => $user->id,
        'name' => 'Gaji',
        'type' => 'income',
    ]);

    $transaction = Transaction::create([
        'user_id' => $user->id,
        'wallet_id' => $wallet->id,
        'category_id' => $category->id,
        'amount' => 50000,
        'type' => 'income',
        'date' => now()->toDateString(),
        'description' => 'Bonus awal',
        'status' => 'completed',
    ]);

    $response = $this->postJson(route('webhooks.whatsapp.transactions'), [
        'token' => 'secret-token',
        'user_email' => $user->email,
        'message' => 'trx edit ' . $transaction->id . ' nominal 75000 catatan Bonus direvisi',
    ]);

    $response
        ->assertOk()
        ->assertJsonPath('success', true);

    expect((float) $transaction->fresh()->amount)->toBe(75000.0)
        ->and($transaction->fresh()->description)->toBe('Bonus direvisi')
        ->and((float) $wallet->fresh()->balance)->toBe(175000.0);
});
