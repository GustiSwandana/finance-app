<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Auth\Events\Verified;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\RedirectResponse;

class VerifyEmailController extends Controller
{
    /**
     * Mark the authenticated user's email address as verified.
     */
    public function __invoke(EmailVerificationRequest $request): RedirectResponse
    {
        if ($request->user()->hasVerifiedEmail()) {
            return $this->redirectAfterVerification($request->user());
        }

        if ($request->user()->markEmailAsVerified()) {
            event(new Verified($request->user()));
        }

        return $this->redirectAfterVerification($request->user());
    }

    private function redirectAfterVerification($user): RedirectResponse
    {
        return $user->is_active
            ? redirect()->intended(route('dashboard', absolute: false).'?verified=1')
            : redirect()->route('approval.pending')->with('status', 'Email berhasil diverifikasi. Akun Anda masih menunggu persetujuan admin.');
    }
}
