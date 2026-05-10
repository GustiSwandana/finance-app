<?php

use App\Models\Category;
use App\Models\Wallet;
use App\Models\User;

test('user can not create an expense using another users wallet', function () {
    $user = User::factory()->create();
    $otherUser = User::factory()->create();

    $foreignWallet = Wallet::create([
        'user_id' => $otherUser->id,
        'bank_name' => 'BCA',
        'type' => 'general',
        'balance' => 500000,
    ]);

    $category = Category::create([
        'user_id' => $user->id,
        'name' => 'Makanan',
        'type' => 'expense',
    ]);

    $response = $this->actingAs($user)->post('/expense', [
        'amount' => 10000,
        'wallet_id' => $foreignWallet->id,
        'category_id' => $category->id,
        'date' => now()->toDateString(),
    ]);

    $response->assertNotFound();
});

test('user can not create an expense using another users category', function () {
    $user = User::factory()->create();
    $otherUser = User::factory()->create();

    $wallet = Wallet::create([
        'user_id' => $user->id,
        'bank_name' => 'BCA',
        'type' => 'general',
        'balance' => 500000,
    ]);

    $foreignCategory = Category::create([
        'user_id' => $otherUser->id,
        'name' => 'Makanan',
        'type' => 'expense',
    ]);

    $response = $this->actingAs($user)->post('/expense', [
        'amount' => 10000,
        'wallet_id' => $wallet->id,
        'category_id' => $foreignCategory->id,
        'date' => now()->toDateString(),
    ]);

    $response->assertNotFound();
});
