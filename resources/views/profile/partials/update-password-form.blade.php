<section>
    <header class="border-b border-slate-200 pb-5">
        <p class="app-eyebrow">Security</p>
        <h2 class="mt-3 text-2xl font-black tracking-tight text-slate-950">
            {{ __('Ubah password') }}
        </h2>

        <p class="mt-2 text-sm text-slate-500">
            {{ __('Gunakan password yang panjang dan unik agar akses akun tetap aman.') }}
        </p>
    </header>

    <form method="post" action="{{ route('password.update') }}" class="mt-6 space-y-6">
        @csrf
        @method('put')

        <div>
            <label for="update_password_current_password" class="mb-2 block text-sm font-bold text-slate-700">{{ __('Password saat ini') }}</label>
            <input id="update_password_current_password" name="current_password" type="password" class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm font-semibold text-slate-800 focus:border-amber-300 focus:bg-white focus:ring-0" autocomplete="current-password" />
            <x-input-error :messages="$errors->updatePassword->get('current_password')" class="mt-2" />
        </div>

        <div>
            <label for="update_password_password" class="mb-2 block text-sm font-bold text-slate-700">{{ __('Password baru') }}</label>
            <input id="update_password_password" name="password" type="password" class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm font-semibold text-slate-800 focus:border-amber-300 focus:bg-white focus:ring-0" autocomplete="new-password" />
            <x-input-error :messages="$errors->updatePassword->get('password')" class="mt-2" />
        </div>

        <div>
            <label for="update_password_password_confirmation" class="mb-2 block text-sm font-bold text-slate-700">{{ __('Konfirmasi password') }}</label>
            <input id="update_password_password_confirmation" name="password_confirmation" type="password" class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm font-semibold text-slate-800 focus:border-amber-300 focus:bg-white focus:ring-0" autocomplete="new-password" />
            <x-input-error :messages="$errors->updatePassword->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="flex items-center gap-4 border-t border-slate-200 pt-4">
            <button type="submit" class="rounded-2xl bg-amber-500 px-5 py-3 text-sm font-bold text-white shadow-[0_18px_35px_rgba(245,158,11,0.22)] transition hover:-translate-y-0.5 hover:bg-amber-600">
                {{ __('Perbarui password') }}
            </button>

            @if (session('status') === 'password-updated')
            <p
                x-data="{ show: true }"
                x-show="show"
                x-transition
                x-init="setTimeout(() => show = false, 2000)"
                class="text-sm font-medium text-slate-500"
            >{{ __('Tersimpan.') }}</p>
            @endif
        </div>
    </form>
</section>
