<?php

namespace App\Http\Controllers;

use App\Http\Requests\Users\StoreUserRequest;
use App\Http\Requests\Users\UpdateUserRequest;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    public function index()
    {
        $users = User::query()
            ->orderByRaw("role = 'admin' desc")
            ->orderBy('name')
            ->paginate(10);

        return view('users.index', compact('users'));
    }

    public function create()
    {
        return view('users.create');
    }

    public function store(StoreUserRequest $request)
    {
        $validated = $request->validated();

        User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'role' => $validated['role'],
            'is_active' => $request->boolean('is_active'),
            'password' => Hash::make($validated['password']),
        ]);

        return redirect()->route('users.index')->with('success', 'User baru berhasil ditambahkan!');
    }

    public function edit(User $user)
    {
        return view('users.edit', compact('user'));
    }

    public function update(UpdateUserRequest $request, User $user)
    {
        $validated = $request->validated();

        $data = [
            'name' => $validated['name'],
            'email' => $validated['email'],
            'role' => $validated['role'],
            'is_active' => $request->boolean('is_active'),
        ];

        if ($request->hasFile('avatar')) {
            if ($user->avatar && Storage::disk('public')->exists($user->avatar)) {
                Storage::disk('public')->delete($user->avatar);
            }

            $path = $request->file('avatar')->store('avatars', 'public');
            $data['avatar'] = $path;
        }

        if ($request->filled('password')) {
            $data['password'] = Hash::make($validated['password']);
        }

        $this->ensureUserManagementKeepsAnActiveAdmin($user, $data);

        $user->update($data);

        return redirect()->route('users.index')->with('success', 'Data user berhasil diperbarui!');
    }

    public function destroy(User $user)
    {
        $this->ensureUserManagementKeepsAnActiveAdmin($user, null, true);

        if ($user->avatar && Storage::disk('public')->exists($user->avatar)) {
            Storage::disk('public')->delete($user->avatar);
        }

        $user->delete();

        return redirect()->route('users.index')->with('success', 'User berhasil dihapus.');
    }

    protected function ensureUserManagementKeepsAnActiveAdmin(User $user, ?array $data = null, bool $isDeleting = false): void
    {
        $nextRole = $data['role'] ?? $user->role;
        $nextActive = $data['is_active'] ?? $user->is_active;

        $removesAdminAccess = $isDeleting || $nextRole !== 'admin' || ! $nextActive;

        if ($user->role !== 'admin' || ! $user->is_active || ! $removesAdminAccess) {
            return;
        }

        $otherActiveAdminExists = User::query()
            ->where('role', 'admin')
            ->where('is_active', true)
            ->whereKeyNot($user->id)
            ->exists();

        if (! $otherActiveAdminExists) {
            throw ValidationException::withMessages([
                'role' => 'Aplikasi harus memiliki minimal satu admin aktif.',
            ]);
        }
    }
}
