<?php

use App\Models\User;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Support\Facades\Notification;

test('registration screen can be rendered', function () {
    $response = $this->get('/register');

    $response->assertStatus(200);
});

test('first registered user becomes active admin', function () {
    Notification::fake();

    $response = $this->post('/register', [
        'name' => 'Test User',
        'email' => 'test@example.com',
        'password' => 'password',
        'password_confirmation' => 'password',
    ]);

    $this->assertAuthenticated();
    $response->assertRedirect(route('verification.notice', absolute: false));

    $user = User::first();

    expect($user->role)->toBe('admin')
        ->and($user->is_active)->toBeTrue()
        ->and($user->hasVerifiedEmail())->toBeFalse();

    Notification::assertSentTo($user, VerifyEmail::class);
});

test('new users stay pending when an admin already exists', function () {
    Notification::fake();

    User::factory()->create([
        'role' => 'admin',
        'is_active' => true,
    ]);

    $response = $this->post('/register', [
        'name' => 'Pending User',
        'email' => 'pending@example.com',
        'password' => 'password',
        'password_confirmation' => 'password',
    ]);

    $this->assertAuthenticated();
    $response->assertRedirect(route('verification.notice', absolute: false));
    $response->assertSessionHas('status');

    $user = User::where('email', 'pending@example.com')->first();

    expect($user->role)->toBe('user')
        ->and($user->is_active)->toBeFalse()
        ->and($user->hasVerifiedEmail())->toBeFalse();

    Notification::assertSentTo($user, VerifyEmail::class);
});

test('unverified email can be registered again and reuses the same account', function () {
    Notification::fake();

    User::factory()->create([
        'role' => 'admin',
        'is_active' => true,
    ]);

    $existingUser = User::factory()->unverified()->create([
        'name' => 'Old Name',
        'email' => 'retry@example.com',
        'is_active' => false,
    ]);

    $response = $this->post('/register', [
        'name' => 'New Name',
        'email' => 'retry@example.com',
        'password' => 'new-password',
        'password_confirmation' => 'new-password',
    ]);

    $response->assertRedirect(route('verification.notice', absolute: false));
    $this->assertAuthenticatedAs($existingUser);

    expect(User::where('email', 'retry@example.com')->count())->toBe(1)
        ->and($existingUser->fresh()->name)->toBe('New Name')
        ->and($existingUser->fresh()->hasVerifiedEmail())->toBeFalse()
        ->and($existingUser->fresh()->is_active)->toBeFalse();

    Notification::assertSentTo($existingUser->fresh(), VerifyEmail::class);
});

test('verified email can not be registered again', function () {
    $existingUser = User::factory()->create([
        'email' => 'verified@example.com',
    ]);

    $response = $this->from('/register')->post('/register', [
        'name' => 'Another User',
        'email' => $existingUser->email,
        'password' => 'password',
        'password_confirmation' => 'password',
    ]);

    $this->assertGuest();
    $response->assertRedirect('/register');
    $response->assertSessionHasErrors('email');
});
