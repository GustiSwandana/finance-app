<x-guest-layout
    title="Lupa Password"
    eyebrow="Password Recovery"
    heading="Pulihkan akses akun Anda tanpa kehilangan momentum."
    description="Masukkan email akun MyFinance Anda. Kami akan mengirimkan tautan reset password ke alamat tersebut."
>
    <p class="inline-flex rounded-full bg-amber-50 px-3 py-2 text-[11px] font-black uppercase tracking-[0.22em] text-amber-700 dark:bg-amber-950/60 dark:text-amber-200">Reset Access</p>
    <h2 class="mt-5 text-3xl font-black tracking-tight text-slate-950 dark:text-white">Lupa password?</h2>
    <p class="mt-3 text-sm leading-7 text-slate-500 dark:text-slate-400">Kami akan kirim link reset ke email Anda. Gunakan email yang sama dengan akun yang terdaftar.</p>

    @if (session('status'))
    <div class="mt-6 rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-medium text-emerald-700 dark:border-emerald-900/40 dark:bg-emerald-950/30 dark:text-emerald-200">
        {{ session('status') }}
    </div>
    @endif

    @if ($errors->any())
    <div class="mt-6 rounded-2xl border border-rose-200 bg-rose-50 px-4 py-3 text-sm text-rose-700 dark:border-rose-900/40 dark:bg-rose-950/30 dark:text-rose-200">
        <p class="font-bold">Permintaan belum bisa diproses</p>
        <ul class="mt-2 list-disc space-y-1 pl-5">
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <form method="POST" action="{{ route('password.email') }}" class="mt-8 space-y-5">
        @csrf

        <div>
            <label for="email" class="mb-2 block text-sm font-bold text-slate-700 dark:text-slate-100">Email</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus class="h-14 w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 text-base font-semibold text-slate-800 placeholder:text-slate-400 focus:border-amber-300 focus:bg-white focus:ring-0 dark:border-slate-700 dark:bg-slate-950/60 dark:text-white dark:placeholder:text-slate-500" placeholder="Masukkan email akun Anda">
        </div>

        <button type="submit" class="w-full rounded-2xl bg-amber-500 px-5 py-3.5 text-sm font-bold text-white shadow-[0_18px_35px_rgba(245,158,11,0.22)] transition hover:-translate-y-0.5 hover:bg-amber-600">
            Kirim Link Reset Password
        </button>
    </form>

    <p class="mt-6 text-center text-sm font-medium text-slate-500 dark:text-slate-400">
        Sudah ingat password?
        <a href="{{ route('login') }}" class="font-bold text-emerald-600 underline underline-offset-4 dark:text-emerald-300">Kembali ke login</a>
    </p>
</x-guest-layout>
