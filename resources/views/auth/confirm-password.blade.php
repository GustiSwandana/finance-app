<x-guest-layout
    title="Konfirmasi Password"
    eyebrow="Secure Confirmation"
    heading="Konfirmasi password sebelum membuka area sensitif."
    description="Langkah ini dipakai saat aplikasi membutuhkan verifikasi ulang untuk tindakan yang lebih aman."
>
    <p class="inline-flex rounded-full bg-violet-50 px-3 py-2 text-[11px] font-black uppercase tracking-[0.22em] text-violet-700 dark:bg-violet-950/60 dark:text-violet-200">Protected Action</p>
    <h2 class="mt-5 text-3xl font-black tracking-tight text-slate-950 dark:text-white">Masukkan password Anda</h2>
    <p class="mt-3 text-sm leading-7 text-slate-500 dark:text-slate-400">Gunakan password akun saat ini untuk melanjutkan ke area aplikasi yang membutuhkan verifikasi tambahan.</p>

    @if ($errors->any())
    <div class="mt-6 rounded-2xl border border-rose-200 bg-rose-50 px-4 py-3 text-sm text-rose-700 dark:border-rose-900/40 dark:bg-rose-950/30 dark:text-rose-200">
        <p class="font-bold">Konfirmasi gagal</p>
        <ul class="mt-2 list-disc space-y-1 pl-5">
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <form method="POST" action="{{ route('password.confirm') }}" class="mt-8 space-y-5">
        @csrf

        <div>
            <label for="password" class="mb-2 block text-sm font-bold text-slate-700 dark:text-slate-100">Password</label>
            <input id="password" type="password" name="password" required autocomplete="current-password" class="h-14 w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 text-base font-semibold text-slate-800 placeholder:text-slate-400 focus:border-violet-300 focus:bg-white focus:ring-0 dark:border-slate-700 dark:bg-slate-950/60 dark:text-white dark:placeholder:text-slate-500" placeholder="Masukkan password Anda">
        </div>

        <button type="submit" class="w-full rounded-2xl bg-violet-600 px-5 py-3.5 text-sm font-bold text-white shadow-[0_18px_35px_rgba(124,58,237,0.22)] transition hover:-translate-y-0.5 hover:bg-violet-700">
            Konfirmasi dan Lanjutkan
        </button>
    </form>
</x-guest-layout>
