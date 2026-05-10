<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Login - MyFinance</title>
    <script>
        (function() {
            const storedTheme = localStorage.getItem('theme');
            const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
            const theme = storedTheme === 'light' || storedTheme === 'dark' ? storedTheme : (prefersDark ? 'dark' : 'light');
            document.documentElement.classList.toggle('dark', theme === 'dark');
            document.documentElement.dataset.theme = theme;
        })();
    </script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-slate-100 text-slate-900 dark:bg-slate-950 dark:text-white">
    <div class="grid min-h-screen lg:grid-cols-2">
        <section class="flex items-center justify-center px-6 py-10 sm:px-10">
            <div class="w-full max-w-xl">
                <a href="/" class="inline-flex items-center gap-3">
                    <span class="flex h-12 w-12 items-center justify-center rounded-2xl bg-slate-950 text-sm font-black uppercase tracking-[0.3em] text-white dark:bg-white dark:text-slate-950">FA</span>
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-[0.24em] text-slate-400">MyFinance</p>
                        <p class="text-base font-black text-slate-950 dark:text-white">Personal Console</p>
                    </div>
                </a>

                <div class="mt-10 rounded-[32px] border border-slate-200 bg-white p-8 shadow-[0_24px_55px_rgba(15,23,42,0.08)] dark:border-slate-800 dark:bg-slate-900/80 dark:shadow-none sm:p-10">
                    <p class="inline-flex rounded-full bg-emerald-50 px-3 py-2 text-[11px] font-black uppercase tracking-[0.22em] text-emerald-700 dark:bg-emerald-950/60 dark:text-emerald-200">Welcome Back</p>
                    <h1 class="mt-5 text-3xl font-black tracking-tight text-slate-950 dark:text-white sm:text-4xl">Masuk untuk melanjutkan pencatatan keuangan Anda.</h1>
                    <p class="mt-3 text-sm leading-7 text-slate-500 dark:text-slate-400">Akses dashboard, transaksi, laporan, dan approval user dari satu akun yang sama.</p>

                    <form method="POST" action="{{ route('login') }}" class="mt-8 space-y-5">
                        @csrf

                        @if (session('status'))
                        <div class="rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-medium text-emerald-700 dark:border-emerald-900/40 dark:bg-emerald-950/30 dark:text-emerald-200">
                            {{ session('status') }}
                        </div>
                        @endif

                        @if (session('error'))
                        <div class="rounded-2xl border border-amber-200 bg-amber-50 px-4 py-3 text-sm font-medium text-amber-700 dark:border-amber-900/40 dark:bg-amber-950/30 dark:text-amber-200">
                            {{ session('error') }}
                        </div>
                        @endif

                        @if ($errors->any())
                        <div class="rounded-2xl border border-rose-200 bg-rose-50 px-4 py-3 text-sm text-rose-700 dark:border-rose-900/40 dark:bg-rose-950/30 dark:text-rose-200">
                            <p class="font-bold">Login gagal</p>
                            <ul class="mt-2 list-disc space-y-1 pl-5">
                                @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                        @endif

                        <div>
                            <label class="mb-2 block text-sm font-bold text-slate-700 dark:text-slate-100">Email</label>
                            <input type="email" name="email" value="{{ old('email') }}" class="h-14 w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 text-base font-semibold text-slate-800 placeholder:text-slate-400 focus:border-emerald-300 focus:bg-white focus:ring-0 dark:border-slate-700 dark:bg-slate-950/60 dark:text-white dark:placeholder:text-slate-500" placeholder="Masukkan email Anda" required autofocus />
                        </div>

                        <div>
                            <label class="mb-2 block text-sm font-bold text-slate-700 dark:text-slate-100">Password</label>
                            <input type="password" name="password" class="h-14 w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 text-base font-semibold text-slate-800 placeholder:text-slate-400 focus:border-emerald-300 focus:bg-white focus:ring-0 dark:border-slate-700 dark:bg-slate-950/60 dark:text-white dark:placeholder:text-slate-500" placeholder="Masukkan password" required />
                        </div>

                        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                            <label class="flex items-center gap-3 text-sm font-semibold text-slate-600 dark:text-slate-300">
                                <input type="checkbox" name="remember" id="remember" class="h-5 w-5 rounded border-slate-300 text-emerald-500 focus:ring-emerald-500 dark:border-slate-600 dark:bg-slate-900" />
                                Ingat saya
                            </label>
                            @if (Route::has('password.request'))
                            <a href="{{ route('password.request') }}" class="text-sm font-bold text-emerald-600 transition hover:text-emerald-700 dark:text-emerald-300 dark:hover:text-emerald-200">Lupa password?</a>
                            @endif
                        </div>

                        <button type="submit" class="w-full rounded-2xl bg-emerald-500 px-5 py-3.5 text-sm font-bold text-white shadow-[0_18px_35px_rgba(16,185,129,0.22)] transition hover:-translate-y-0.5 hover:bg-emerald-600">
                            Masuk Sekarang
                        </button>
                    </form>

                    <p class="mt-6 text-center text-sm font-medium text-slate-500 dark:text-slate-400">
                        Belum punya akun?
                        <a href="{{ route('register') }}" class="font-bold text-emerald-600 underline underline-offset-4 dark:text-emerald-300">Daftar di sini</a>
                    </p>
                </div>
            </div>
        </section>

        <section class="relative hidden overflow-hidden bg-[radial-gradient(circle_at_top_left,_rgba(255,255,255,0.20),_transparent_25%),linear-gradient(135deg,#0f766e,#155e75_42%,#1d4ed8)] lg:flex lg:flex-col lg:justify-between lg:p-16">
            <div class="absolute inset-0 bg-[linear-gradient(rgba(255,255,255,0.06)_1px,transparent_1px),linear-gradient(90deg,rgba(255,255,255,0.06)_1px,transparent_1px)] bg-[size:28px_28px] opacity-40"></div>
            <div class="relative z-10 max-w-xl">
                <p class="inline-flex rounded-full bg-white/15 px-3 py-2 text-[11px] font-black uppercase tracking-[0.22em] text-white/80">Personal Finance</p>
                <h2 class="mt-6 text-5xl font-black tracking-tight text-white">Semua arus kas Anda, dalam satu workspace yang rapi.</h2>
                <p class="mt-5 text-base leading-8 text-white/80">Cocok untuk mencatat transaksi harian, memantau saldo antar rekening, mengelola tagihan, dan meninjau laporan kapan saja.</p>
            </div>
            <div class="relative z-10 grid gap-4 sm:grid-cols-3">
                <div class="rounded-3xl bg-white/12 p-5 backdrop-blur-sm">
                    <p class="text-[11px] font-black uppercase tracking-[0.22em] text-white/70">Input</p>
                    <p class="mt-2 text-lg font-black text-white">Transaksi</p>
                </div>
                <div class="rounded-3xl bg-white/12 p-5 backdrop-blur-sm">
                    <p class="text-[11px] font-black uppercase tracking-[0.22em] text-white/70">Review</p>
                    <p class="mt-2 text-lg font-black text-white">Laporan</p>
                </div>
                <div class="rounded-3xl bg-white/12 p-5 backdrop-blur-sm">
                    <p class="text-[11px] font-black uppercase tracking-[0.22em] text-white/70">Control</p>
                    <p class="mt-2 text-lg font-black text-white">Approval</p>
                </div>
            </div>
        </section>
    </div>
</body>
</html>
