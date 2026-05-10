<x-guest-layout
    title="Reset Password"
    eyebrow="Password Reset"
    heading="Tetapkan password baru dan kembali ke dashboard."
    description="Gunakan password yang kuat dan mudah Anda kelola. Setelah disimpan, akun bisa dipakai login seperti biasa."
>
    <p class="inline-flex rounded-full bg-sky-50 px-3 py-2 text-[11px] font-black uppercase tracking-[0.22em] text-sky-700 dark:bg-sky-950/60 dark:text-sky-200">New Password</p>
    <h2 class="mt-5 text-3xl font-black tracking-tight text-slate-950 dark:text-white">Reset password akun</h2>
    <p class="mt-3 text-sm leading-7 text-slate-500 dark:text-slate-400">Isi email, password baru, dan konfirmasinya untuk menyelesaikan proses reset.</p>

    @if ($errors->any())
    <div class="mt-6 rounded-2xl border border-rose-200 bg-rose-50 px-4 py-3 text-sm text-rose-700 dark:border-rose-900/40 dark:bg-rose-950/30 dark:text-rose-200">
        <p class="font-bold">Password belum bisa diperbarui</p>
        <ul class="mt-2 list-disc space-y-1 pl-5">
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <form method="POST" action="{{ route('password.store') }}" class="mt-8 space-y-5">
        @csrf
        <input type="hidden" name="token" value="{{ $request->route('token') }}">

        <div>
            <label for="email" class="mb-2 block text-sm font-bold text-slate-700 dark:text-slate-100">Email</label>
            <input id="email" type="email" name="email" value="{{ old('email', $request->email) }}" required autofocus autocomplete="username" class="h-14 w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 text-base font-semibold text-slate-800 placeholder:text-slate-400 focus:border-sky-300 focus:bg-white focus:ring-0 dark:border-slate-700 dark:bg-slate-950/60 dark:text-white dark:placeholder:text-slate-500">
        </div>

        <div>
            <label for="password" class="mb-2 block text-sm font-bold text-slate-700 dark:text-slate-100">Password baru</label>
            <input id="password" type="password" name="password" required autocomplete="new-password" class="h-14 w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 text-base font-semibold text-slate-800 placeholder:text-slate-400 focus:border-sky-300 focus:bg-white focus:ring-0 dark:border-slate-700 dark:bg-slate-950/60 dark:text-white dark:placeholder:text-slate-500" placeholder="Minimal 8 karakter">
        </div>

        <div>
            <label for="password_confirmation" class="mb-2 block text-sm font-bold text-slate-700 dark:text-slate-100">Konfirmasi password baru</label>
            <input id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password" class="h-14 w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 text-base font-semibold text-slate-800 placeholder:text-slate-400 focus:border-sky-300 focus:bg-white focus:ring-0 dark:border-slate-700 dark:bg-slate-950/60 dark:text-white dark:placeholder:text-slate-500" placeholder="Ulangi password baru">
        </div>

        <button type="submit" class="w-full rounded-2xl bg-sky-600 px-5 py-3.5 text-sm font-bold text-white shadow-[0_18px_35px_rgba(37,99,235,0.22)] transition hover:-translate-y-0.5 hover:bg-sky-700">
            Simpan Password Baru
        </button>
    </form>
</x-guest-layout>
