<?php

use App\Models\User;
use App\Models\Wallet;
use Illuminate\Http\UploadedFile;

test('import create page can be rendered for authenticated active users', function () {
    $user = User::factory()->create();

    Wallet::create([
        'user_id' => $user->id,
        'bank_name' => 'BCA',
        'type' => 'general',
        'balance' => 0,
    ]);

    $response = $this->actingAs($user)->get(route('bank-mutations.create'));

    $response->assertOk();
    $response->assertSee('Import Mutasi');
});

test('csv bank mutation file can be previewed', function () {
    $user = User::factory()->create();

    $wallet = Wallet::create([
        'user_id' => $user->id,
        'bank_name' => 'Bank Jago',
        'type' => 'general',
        'balance' => 0,
    ]);

    $file = UploadedFile::fake()->createWithContent(
        'mutasi.csv',
        "Tanggal,Keterangan,Debit,Kredit,Saldo\n2026-05-01,Transfer masuk,,150000,150000\n2026-05-02,Bayar listrik,75000,,75000\n"
    );

    $response = $this->actingAs($user)->post(route('bank-mutations.preview'), [
        'wallet_id' => $wallet->id,
        'bank_source' => 'generic',
        'bank_files' => [$file],
    ]);

    $response->assertOk();
    $response->assertSee('Transfer masuk');
    $response->assertSee('Bayar listrik');
});

test('multiple bank mutation files can be previewed in one batch', function () {
    $user = User::factory()->create();

    $wallet = Wallet::create([
        'user_id' => $user->id,
        'bank_name' => 'Bank Jago',
        'type' => 'general',
        'balance' => 0,
    ]);

    $firstFile = UploadedFile::fake()->createWithContent(
        'mutasi-april.csv',
        "Tanggal,Keterangan,Debit,Kredit\n2026-04-01,Gaji April,,5000000\n"
    );
    $secondFile = UploadedFile::fake()->createWithContent(
        'mutasi-mei.csv',
        "Tanggal,Keterangan,Debit,Kredit\n2026-05-01,Bayar internet,350000,\n"
    );

    $response = $this->actingAs($user)->post(route('bank-mutations.preview'), [
        'wallet_id' => $wallet->id,
        'bank_source' => 'generic',
        'bank_files' => [$firstFile, $secondFile],
    ]);

    $response->assertOk();
    $response->assertSee('mutasi-april.csv');
    $response->assertSee('mutasi-mei.csv');
    $response->assertSee('Gaji April');
    $response->assertSee('Bayar internet');
});

test('bank mutation expense can be imported even when wallet balance is insufficient', function () {
    $user = User::factory()->create();

    $wallet = Wallet::create([
        'user_id' => $user->id,
        'bank_name' => 'BCA',
        'type' => 'general',
        'balance' => 0,
    ]);

    $transaction = [
        'date' => '2026-05-01',
        'description' => 'Tarik tunai ATM',
        'amount' => 250000,
        'type' => 'expense',
        'category_guess' => 'Tarik Tunai',
    ];

    $response = $this->actingAs($user)->post(route('bank-mutations.store'), [
        'wallet_id' => $wallet->id,
        'transactions' => [json_encode($transaction)],
    ]);

    $response->assertRedirect(route('transactions.index', absolute: false));
    $response->assertSessionHas('success');

    expect((float) $wallet->fresh()->balance)->toBe(-250000.0);
});

test('old import create route redirects to bank mutation feature', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->get(route('import.create'));

    $response->assertRedirect('/bank-mutations');
});
