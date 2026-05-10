<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $existingUser = User::where('email', $request->email)->first();

        if ($existingUser?->hasVerifiedEmail()) {
            throw ValidationException::withMessages([
                'email' => 'Email sudah terdaftar dan sudah diverifikasi.',
            ]);
        }

        $shouldBootstrapAdmin = ! User::where('role', 'admin')
            ->when($existingUser, fn ($query) => $query->whereKeyNot($existingUser->id))
            ->exists();

        $user = $existingUser ?? new User([
            'email' => $request->email,
        ]);

        $user->forceFill([
            'name' => $request->name,
            'role' => $shouldBootstrapAdmin ? 'admin' : ($user->role ?: 'user'),
            'is_active' => $shouldBootstrapAdmin || (bool) $user->is_active,
            'password' => Hash::make($request->password),
        ])->save();

        event(new Registered($user));

        Auth::login($user);

        return redirect()
            ->route('verification.notice')
            ->with('status', $shouldBootstrapAdmin
                ? 'Pendaftaran berhasil. Silakan verifikasi email sebelum masuk dashboard.'
                : 'Pendaftaran berhasil. Silakan verifikasi email, lalu tunggu persetujuan admin.');
    }
}
