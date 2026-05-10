<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta http-equiv="X-UA-Compatible" content="ie=edge" />
    <script>
        (function() {
            const storedTheme = localStorage.getItem('theme');
            const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
            const theme = storedTheme === 'light' || storedTheme === 'dark' ? storedTheme : (prefersDark ? 'dark' : 'light');
            document.documentElement.classList.toggle('dark', theme === 'dark');
            document.documentElement.dataset.theme = theme;
        })();
    </script>
    <title>MyFinance - Kelola Keuangan Pribadi</title>
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-slate-100 text-slate-900 dark:bg-slate-950 dark:text-white" style="font-family: 'Manrope', sans-serif;">
    <div class="relative overflow-hidden bg-[radial-gradient(circle_at_top_left,_rgba(15,118,110,0.10),_transparent_24%),radial-gradient(circle_at_top_right,_rgba(29,78,216,0.08),_transparent_22%),linear-gradient(180deg,#f7f5ef_0%,#f1efe8_100%)] dark:bg-[radial-gradient(circle_at_top_left,_rgba(20,184,166,0.16),_transparent_22%),radial-gradient(circle_at_top_right,_rgba(59,130,246,0.14),_transparent_22%),linear-gradient(180deg,#020617_0%,#0f172a_100%)]">
        <div class="pointer-events-none absolute inset-0 bg-[linear-gradient(rgba(255,255,255,0.10)_1px,transparent_1px),linear-gradient(90deg,rgba(255,255,255,0.10)_1px,transparent_1px)] bg-[size:24px_24px] opacity-30 dark:opacity-10"></div>

        <div class="relative z-10 mx-auto flex min-h-screen w-full max-w-7xl flex-col px-5 py-6 sm:px-8 lg:px-10">
            <header class="rounded-[28px] border border-slate-200/80 bg-white/85 px-5 py-4 shadow-[0_18px_45px_rgba(15,23,42,0.08)] backdrop-blur-xl dark:border-slate-800 dark:bg-slate-900/80 dark:shadow-none">
                <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
                    <a href="{{ url('/') }}" class="inline-flex items-center gap-3">
                        <span class="flex h-12 w-12 items-center justify-center rounded-2xl bg-slate-950 text-sm font-black uppercase tracking-[0.3em] text-white dark:bg-white dark:text-slate-950">FA</span>
                        <div>
                            <p class="text-xs font-semibold uppercase tracking-[0.24em] text-slate-400">Finance App</p>
                            <p class="text-base font-black text-slate-950 dark:text-white">Personal Console</p>
                        </div>
                    </a>

                    <div class="flex flex-wrap items-center gap-3">
                        <button type="button" data-theme-toggle class="inline-flex items-center gap-3 rounded-2xl border border-slate-200 bg-slate-50 px-3 py-2.5 text-sm font-bold text-slate-700 transition hover:-translate-y-0.5 hover:bg-white dark:border-slate-700 dark:bg-slate-900 dark:text-slate-100 dark:hover:bg-slate-950" aria-label="Toggle dark mode" aria-pressed="false">
                            <span class="flex h-8 w-8 items-center justify-center rounded-full bg-white text-slate-700 dark:bg-slate-800 dark:text-slate-100">
                                <svg data-theme-icon-dark width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M21 12.79A9 9 0 1 1 11.21 3c0 .28 0 .57.02.85A7 7 0 0 0 20.15 12c.28.02.57.02.85.02Z"></path>
                                </svg>
                                <svg data-theme-icon-light class="hidden" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <circle cx="12" cy="12" r="4"></circle>
                                    <path d="M12 2v2"></path>
                                    <path d="M12 20v2"></path>
                                    <path d="m4.93 4.93 1.41 1.41"></path>
                                    <path d="m17.66 17.66 1.41 1.41"></path>
                                    <path d="M2 12h2"></path>
                                    <path d="M20 12h2"></path>
                                    <path d="m6.34 17.66-1.41 1.41"></path>
                                    <path d="m19.07 4.93-1.41 1.41"></path>
                                </svg>
                            </span>
                            <span data-theme-label>Dark Mode</span>
                        </button>

                        @if (Route::has('login'))
                            @auth
                            <a href="{{ route('dashboard') }}" class="inline-flex items-center rounded-2xl bg-emerald-500 px-4 py-2.5 text-sm font-bold text-white shadow-[0_18px_35px_rgba(16,185,129,0.22)] transition hover:-translate-y-0.5 hover:bg-emerald-600">
                                Buka Dashboard
                            </a>
                            @else
                            <a href="{{ route('login') }}" class="inline-flex items-center rounded-2xl border border-slate-200 bg-slate-50 px-4 py-2.5 text-sm font-bold text-slate-700 transition hover:bg-white dark:border-slate-700 dark:bg-slate-900 dark:text-slate-100 dark:hover:bg-slate-950">
                                Masuk
                            </a>
                            @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="inline-flex items-center rounded-2xl bg-slate-950 px-4 py-2.5 text-sm font-bold text-white transition hover:-translate-y-0.5 dark:bg-white dark:text-slate-950">
                                Daftar Akun
                            </a>
                            @endif
                            @endauth
                        @endif
                    </div>
                </div>
            </header>

            <main class="flex-1 py-8 lg:py-10">
                <section class="relative overflow-hidden rounded-[36px] bg-[radial-gradient(circle_at_top_left,_rgba(255,255,255,0.16),_transparent_24%),linear-gradient(135deg,#0f766e,#155e75_42%,#1d4ed8)] px-6 py-8 text-white shadow-[0_26px_70px_rgba(15,118,110,0.22)] sm:px-8 lg:px-10 lg:py-12">
                    <div class="absolute inset-0 bg-[linear-gradient(rgba(255,255,255,0.06)_1px,transparent_1px),linear-gradient(90deg,rgba(255,255,255,0.06)_1px,transparent_1px)] bg-[size:28px_28px] opacity-40"></div>
                    <div class="relative z-10 grid gap-8 lg:grid-cols-[minmax(0,1.1fr)_minmax(360px,0.9fr)] lg:items-center">
                        <div class="max-w-3xl">
                            <p class="inline-flex rounded-full bg-white/14 px-3 py-2 text-[11px] font-black uppercase tracking-[0.22em] text-white/80">Personal Finance Workspace</p>
                            <h1 class="mt-6 text-4xl font-black tracking-tight sm:text-5xl lg:text-6xl">Pantau saldo, catat transaksi, dan kelola approval user dari satu tempat.</h1>
                            <p class="mt-5 max-w-2xl text-base leading-8 text-white/80">MyFinance dirancang untuk workflow harian yang cepat: input transaksi, transfer antar wallet, import mutasi, review laporan, dan kontrol akses admin dalam satu console yang konsisten.</p>

                            <div class="mt-8 flex flex-col gap-3 sm:flex-row">
                                @auth
                                <a href="{{ route('dashboard') }}" class="inline-flex items-center justify-center rounded-2xl bg-white px-5 py-3.5 text-sm font-black text-slate-950 transition hover:-translate-y-0.5">
                                    Lanjut ke Dashboard
                                </a>
                                @else
                                @if (Route::has('register'))
                                <a href="{{ route('register') }}" class="inline-flex items-center justify-center rounded-2xl bg-white px-5 py-3.5 text-sm font-black text-slate-950 transition hover:-translate-y-0.5">
                                    Mulai Gratis
                                </a>
                                @endif
                                <a href="{{ route('login') }}" class="inline-flex items-center justify-center rounded-2xl border border-white/25 bg-white/10 px-5 py-3.5 text-sm font-black text-white transition hover:-translate-y-0.5 hover:bg-white/16">
                                    Masuk ke Workspace
                                </a>
                                @endauth
                            </div>
                        </div>

                        <div class="grid gap-4">
                            <article class="rounded-[28px] bg-white/12 p-5 backdrop-blur-sm">
                                <p class="text-[11px] font-black uppercase tracking-[0.22em] text-white/70">Track</p>
                                <p class="mt-3 text-2xl font-black">Wallet & Saldo</p>
                                <p class="mt-2 text-sm leading-7 text-white/75">Pantau semua rekening bank, e-wallet, dan kas dalam satu perhitungan total saldo.</p>
                            </article>
                            <article class="rounded-[28px] bg-white/12 p-5 backdrop-blur-sm">
                                <p class="text-[11px] font-black uppercase tracking-[0.22em] text-white/70">Control</p>
                                <p class="mt-3 text-2xl font-black">Approval & User Management</p>
                                <p class="mt-2 text-sm leading-7 text-white/75">Admin dapat menyetujui pendaftaran baru, mengelola role, dan menjaga akses tetap aman.</p>
                            </article>
                            <article class="rounded-[28px] bg-white/12 p-5 backdrop-blur-sm">
                                <p class="text-[11px] font-black uppercase tracking-[0.22em] text-white/70">Review</p>
                                <p class="mt-3 text-2xl font-black">Laporan & Import</p>
                                <p class="mt-2 text-sm leading-7 text-white/75">Backfill mutasi PDF, cek preview transaksi, lalu olah data menjadi laporan yang lebih rapi.</p>
                            </article>
                        </div>
                    </div>
                </section>

                <section class="mt-8 grid gap-4 md:grid-cols-3">
                    <article class="rounded-[30px] border border-slate-200 bg-white/90 p-6 shadow-[0_18px_45px_rgba(15,23,42,0.06)] backdrop-blur-xl dark:border-slate-800 dark:bg-slate-900/82 dark:shadow-none">
                        <p class="text-xs font-black uppercase tracking-[0.22em] text-slate-400">Modul Utama</p>
                        <h2 class="mt-3 text-2xl font-black text-slate-950 dark:text-white">Transaksi Harian</h2>
                        <p class="mt-3 text-sm leading-7 text-slate-500 dark:text-slate-400">Catat pemasukan, pengeluaran, transfer internal, dan scan struk dengan alur yang ringkas.</p>
                    </article>
                    <article class="rounded-[30px] border border-slate-200 bg-white/90 p-6 shadow-[0_18px_45px_rgba(15,23,42,0.06)] backdrop-blur-xl dark:border-slate-800 dark:bg-slate-900/82 dark:shadow-none">
                        <p class="text-xs font-black uppercase tracking-[0.22em] text-slate-400">Kontrol</p>
                        <h2 class="mt-3 text-2xl font-black text-slate-950 dark:text-white">Budget & Tagihan</h2>
                        <p class="mt-3 text-sm leading-7 text-slate-500 dark:text-slate-400">Awasi anggaran bulanan, langganan rutin, dan utang piutang dari dashboard yang sama.</p>
                    </article>
                    <article class="rounded-[30px] border border-slate-200 bg-white/90 p-6 shadow-[0_18px_45px_rgba(15,23,42,0.06)] backdrop-blur-xl dark:border-slate-800 dark:bg-slate-900/82 dark:shadow-none">
                        <p class="text-xs font-black uppercase tracking-[0.22em] text-slate-400">Insight</p>
                        <h2 class="mt-3 text-2xl font-black text-slate-950 dark:text-white">Laporan Detail</h2>
                        <p class="mt-3 text-sm leading-7 text-slate-500 dark:text-slate-400">Filter transaksi berdasarkan tanggal, wallet, tipe, kategori, lalu export sesuai kebutuhan.</p>
                    </article>
                </section>

                <section class="mt-8 rounded-[34px] border border-slate-200 bg-white/90 p-6 shadow-[0_18px_45px_rgba(15,23,42,0.06)] backdrop-blur-xl dark:border-slate-800 dark:bg-slate-900/82 dark:shadow-none sm:p-8">
                    <div class="flex flex-col gap-6 lg:flex-row lg:items-center lg:justify-between">
                        <div class="max-w-3xl">
                            <p class="inline-flex rounded-full bg-slate-100 px-3 py-2 text-[11px] font-black uppercase tracking-[0.22em] text-slate-600 dark:bg-slate-800 dark:text-slate-200">Ready to Start</p>
                            <h2 class="mt-4 text-3xl font-black tracking-tight text-slate-950 dark:text-white">Siap merapikan workflow keuangan Anda?</h2>
                            <p class="mt-3 text-sm leading-7 text-slate-500 dark:text-slate-400">Masuk jika Anda sudah punya akun, atau daftar akun baru untuk mulai mengelola wallet, transaksi, dan laporan dari satu workspace.</p>
                        </div>
                        <div class="flex flex-col gap-3 sm:flex-row">
                            @if (Route::has('login'))
                                @auth
                                <a href="{{ route('dashboard') }}" class="inline-flex items-center justify-center rounded-2xl bg-emerald-500 px-5 py-3.5 text-sm font-bold text-white shadow-[0_18px_35px_rgba(16,185,129,0.22)] transition hover:-translate-y-0.5 hover:bg-emerald-600">
                                    Buka Dashboard
                                </a>
                                @else
                                <a href="{{ route('login') }}" class="inline-flex items-center justify-center rounded-2xl border border-slate-200 bg-slate-50 px-5 py-3.5 text-sm font-bold text-slate-700 transition hover:bg-white dark:border-slate-700 dark:bg-slate-900 dark:text-slate-100 dark:hover:bg-slate-950">
                                    Masuk
                                </a>
                                @if (Route::has('register'))
                                <a href="{{ route('register') }}" class="inline-flex items-center justify-center rounded-2xl bg-slate-950 px-5 py-3.5 text-sm font-bold text-white transition hover:-translate-y-0.5 dark:bg-white dark:text-slate-950">
                                    Buat Akun
                                </a>
                                @endif
                                @endauth
                            @endif
                        </div>
                    </div>
                </section>
            </main>

            <footer class="pb-4">
                <div class="rounded-[28px] border border-slate-200/80 bg-white/85 px-5 py-5 shadow-[0_18px_45px_rgba(15,23,42,0.08)] backdrop-blur-xl dark:border-slate-800 dark:bg-slate-900/80 dark:shadow-none">
                    <div class="flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
                        <div>
                            <p class="text-sm font-bold text-slate-900 dark:text-white">MyFinance Personal App</p>
                            <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">Workspace keuangan pribadi untuk transaksi, laporan, approval user, dan pengelolaan akun.</p>
                        </div>
                        <p class="text-sm text-slate-500 dark:text-slate-400">&copy; {{ date('Y') }} Gusti Swandana. All rights reserved.</p>
                    </div>
                </div>
            </footer>
        </div>
    </div>
</body>
</html>
