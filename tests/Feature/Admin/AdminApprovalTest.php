<?php

use App\Models\User;

test('admin can review pending users', function () {
    $admin = User::factory()->create([
        'role' => 'admin',
        'is_active' => true,
    ]);

    $pendingUser = User::factory()->create([
        'is_active' => false,
    ]);

    $response = $this->actingAs($admin)->get(route('admin.approvals.index'));

    $response->assertOk();
    $response->assertSee($pendingUser->email);
});

test('admin can approve pending users', function () {
    $admin = User::factory()->create([
        'role' => 'admin',
        'is_active' => true,
    ]);

    $pendingUser = User::factory()->create([
        'is_active' => false,
    ]);

    $response = $this->actingAs($admin)->post(route('admin.approve', $pendingUser));

    $response->assertRedirect();
    expect($pendingUser->fresh()->is_active)->toBeTrue();
});

test('admin can not approve users before email verification', function () {
    $admin = User::factory()->create([
        'role' => 'admin',
        'is_active' => true,
    ]);

    $pendingUser = User::factory()->unverified()->create([
        'is_active' => false,
    ]);

    $response = $this->actingAs($admin)->post(route('admin.approve', $pendingUser));

    $response->assertSessionHas('error');
    expect($pendingUser->fresh()->is_active)->toBeFalse();
});

test('non admin users can not access approval routes', function () {
    $user = User::factory()->create([
        'role' => 'user',
        'is_active' => true,
    ]);

    $response = $this->actingAs($user)->get(route('admin.approvals.index'));

    $response->assertForbidden();
});
