<?php

use App\Models\Debt;
use App\Models\User;
use App\Models\Wallet;

test('debt edit page can be rendered', function () {
    $user = User::factory()->create();
    $wallet = Wallet::create([
        'user_id' => $user->id,
        'bank_name' => 'BCA',
        'type' => 'general',
        'balance' => 100000,
    ]);
    $debt = Debt::create([
        'user_id' => $user->id,
        'wallet_id' => $wallet->id,
        'type' => 'payable',
        'name' => 'Budi',
        'amount' => 50000,
    ]);

    $response = $this->actingAs($user)->get(route('debts.edit', $debt));

    $response->assertOk();
    $response->assertSee('Budi');
});

test('updating payable debt adjusts wallet balance', function () {
    $user = User::factory()->create();
    $wallet = Wallet::create([
        'user_id' => $user->id,
        'bank_name' => 'BCA',
        'type' => 'general',
        'balance' => 150000,
    ]);
    $debt = Debt::create([
        'user_id' => $user->id,
        'wallet_id' => $wallet->id,
        'type' => 'payable',
        'name' => 'Budi',
        'amount' => 50000,
    ]);

    $response = $this->actingAs($user)->put(route('debts.update', $debt), [
        'type' => 'payable',
        'name' => 'Budi Update',
        'amount' => 80000,
        'wallet_id' => $wallet->id,
        'due_date' => null,
        'description' => 'Revisi nominal',
    ]);

    $response->assertRedirect(route('debts.index', absolute: false));

    expect((float) $wallet->fresh()->balance)->toBe(180000.0)
        ->and($debt->fresh()->name)->toBe('Budi Update')
        ->and((float) $debt->fresh()->amount)->toBe(80000.0);
});

test('deleting unpaid receivable debt reverses wallet balance effect', function () {
    $user = User::factory()->create();
    $wallet = Wallet::create([
        'user_id' => $user->id,
        'bank_name' => 'BCA',
        'type' => 'general',
        'balance' => 250000,
    ]);
    $debt = Debt::create([
        'user_id' => $user->id,
        'wallet_id' => $wallet->id,
        'type' => 'receivable',
        'name' => 'Andi',
        'amount' => 50000,
    ]);

    $response = $this->actingAs($user)->delete(route('debts.destroy', $debt));

    $response->assertRedirect(route('debts.index', absolute: false));

    expect($debt->fresh())->toBeNull()
        ->and((float) $wallet->fresh()->balance)->toBe(300000.0);
});
