<section>
    <header class="border-b border-slate-200 pb-5">
        <p class="app-eyebrow">Identity</p>
        <h2 class="mt-3 text-2xl font-black tracking-tight text-slate-950">
            {{ __('Informasi profil') }}
        </h2>

        <p class="mt-2 text-sm text-slate-500">
            {{ __("Perbarui nama dan email utama akun Anda.") }}
        </p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="mt-6 space-y-6">
        @csrf
        @method('patch')

        <div>
            <label for="name" class="mb-2 block text-sm font-bold text-slate-700">{{ __('Nama') }}</label>
            <input id="name" name="name" type="text" class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm font-semibold text-slate-800 focus:border-emerald-300 focus:bg-white focus:ring-0" value="{{ old('name', $user->name) }}" required autofocus autocomplete="name" />
            <x-input-error class="mt-2" :messages="$errors->get('name')" />
        </div>

        <div>
            <label for="email" class="mb-2 block text-sm font-bold text-slate-700">{{ __('Email') }}</label>
            <input id="email" name="email" type="email" class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm font-semibold text-slate-800 focus:border-emerald-300 focus:bg-white focus:ring-0" value="{{ old('email', $user->email) }}" required autocomplete="username" />
            <x-input-error class="mt-2" :messages="$errors->get('email')" />

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
            <div class="mt-4 rounded-2xl border border-amber-200 bg-amber-50 px-4 py-3">
                <p class="text-sm text-amber-800">
                    {{ __('Alamat email Anda belum terverifikasi.') }}
                </p>

                <button form="send-verification" class="mt-2 text-sm font-bold text-amber-700 underline underline-offset-4 transition hover:text-amber-900">
                    {{ __('Kirim ulang email verifikasi') }}
                </button>

                @if (session('status') === 'verification-link-sent')
                <p class="mt-2 text-sm font-medium text-emerald-700">
                    {{ __('Link verifikasi baru sudah dikirim ke email Anda.') }}
                </p>
                @endif
            </div>
            @endif
        </div>

        <div class="flex items-center gap-4 border-t border-slate-200 pt-4">
            <button type="submit" class="rounded-2xl bg-emerald-500 px-5 py-3 text-sm font-bold text-white shadow-[0_18px_35px_rgba(16,185,129,0.22)] transition hover:-translate-y-0.5 hover:bg-emerald-600">
                {{ __('Simpan perubahan') }}
            </button>

            @if (session('status') === 'profile-updated')
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
