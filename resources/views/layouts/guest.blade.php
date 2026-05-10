@props([
    'title' => config('app.name', 'MyFinance'),
    'eyebrow' => 'Secure Access',
    'heading' => 'Masuk ke workspace keuangan Anda.',
    'description' => 'Kelola akun, reset akses, dan verifikasi identitas dari satu antarmuka yang konsisten.',
])

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <script>
            (function() {
                const storedTheme = localStorage.getItem('theme');
                const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
                const theme = storedTheme === 'light' || storedTheme === 'dark' ? storedTheme : (prefersDark ? 'dark' : 'light');
                document.documentElement.classList.toggle('dark', theme === 'dark');
                document.documentElement.dataset.theme = theme;
            })();
        </script>

        <title>{{ $title }} - MyFinance</title>

        <link rel="preconnect" href="https://fonts.googleapis.com" />
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
        <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&display=swap" rel="stylesheet" />

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="min-h-screen bg-slate-100 text-slate-900 dark:bg-slate-950 dark:text-white" style="font-family: 'Manrope', sans-serif;">
        <div class="relative min-h-screen overflow-hidden bg-[radial-gradient(circle_at_top_left,_rgba(15,118,110,0.10),_transparent_24%),radial-gradient(circle_at_top_right,_rgba(29,78,216,0.08),_transparent_22%),linear-gradient(180deg,#f7f5ef_0%,#f1efe8_100%)] dark:bg-[radial-gradient(circle_at_top_left,_rgba(20,184,166,0.16),_transparent_22%),radial-gradient(circle_at_top_right,_rgba(59,130,246,0.14),_transparent_22%),linear-gradient(180deg,#020617_0%,#0f172a_100%)]">
            <div class="pointer-events-none absolute inset-0 bg-[linear-gradient(rgba(255,255,255,0.10)_1px,transparent_1px),linear-gradient(90deg,rgba(255,255,255,0.10)_1px,transparent_1px)] bg-[size:24px_24px] opacity-30 dark:opacity-10"></div>

            <div class="relative z-10 mx-auto flex min-h-screen w-full max-w-7xl flex-col px-5 py-6 sm:px-8 lg:px-10">
                <header class="flex items-center justify-between rounded-[28px] border border-slate-200/80 bg-white/85 px-5 py-4 shadow-[0_18px_45px_rgba(15,23,42,0.08)] backdrop-blur-xl dark:border-slate-800 dark:bg-slate-900/80 dark:shadow-none">
                    <a href="{{ url('/') }}" class="inline-flex items-center gap-3">
                        <span class="flex h-12 w-12 items-center justify-center rounded-2xl bg-slate-950 text-sm font-black uppercase tracking-[0.3em] text-white dark:bg-white dark:text-slate-950">FA</span>
                        <div>
                            <p class="text-xs font-semibold uppercase tracking-[0.24em] text-slate-400">Finance App</p>
                            <p class="text-base font-black text-slate-950 dark:text-white">Personal Console</p>
                        </div>
                    </a>

                    <div class="flex items-center gap-3">
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
                            <span data-theme-label class="hidden sm:inline">Dark Mode</span>
                        </button>

                        @if (Route::has('login'))
                            @auth
                            <a href="{{ route('dashboard') }}" class="inline-flex items-center rounded-2xl bg-emerald-500 px-4 py-2.5 text-sm font-bold text-white shadow-[0_18px_35px_rgba(16,185,129,0.22)] transition hover:-translate-y-0.5 hover:bg-emerald-600">
                                Dashboard
                            </a>
                            @else
                            <a href="{{ route('login') }}" class="inline-flex items-center rounded-2xl border border-slate-200 bg-slate-50 px-4 py-2.5 text-sm font-bold text-slate-700 transition hover:bg-white dark:border-slate-700 dark:bg-slate-900 dark:text-slate-100 dark:hover:bg-slate-950">
                                Masuk
                            </a>
                            @endauth
                        @endif
                    </div>
                </header>

                <div class="grid flex-1 gap-8 py-8 lg:grid-cols-[minmax(0,1fr)_minmax(420px,520px)] lg:items-center">
                    <section class="relative hidden overflow-hidden rounded-[36px] bg-[radial-gradient(circle_at_top_left,_rgba(255,255,255,0.18),_transparent_22%),linear-gradient(135deg,#0f766e,#155e75_42%,#1d4ed8)] px-8 py-10 text-white shadow-[0_26px_70px_rgba(15,118,110,0.22)] lg:flex lg:min-h-[620px] lg:flex-col lg:justify-between">
                        <div class="absolute inset-0 bg-[linear-gradient(rgba(255,255,255,0.06)_1px,transparent_1px),linear-gradient(90deg,rgba(255,255,255,0.06)_1px,transparent_1px)] bg-[size:28px_28px] opacity-40"></div>
                        <div class="relative z-10 max-w-xl">
                            <p class="inline-flex rounded-full bg-white/14 px-3 py-2 text-[11px] font-black uppercase tracking-[0.22em] text-white/80">{{ $eyebrow }}</p>
                            <h1 class="mt-6 text-5xl font-black tracking-tight">{{ $heading }}</h1>
                            <p class="mt-5 text-base leading-8 text-white/80">{{ $description }}</p>
                        </div>
                        <div class="relative z-10 grid gap-4 sm:grid-cols-3">
                            <div class="rounded-3xl bg-white/12 p-5 backdrop-blur-sm">
                                <p class="text-[11px] font-black uppercase tracking-[0.22em] text-white/70">Control</p>
                                <p class="mt-2 text-lg font-black text-white">Akses Aman</p>
                            </div>
                            <div class="rounded-3xl bg-white/12 p-5 backdrop-blur-sm">
                                <p class="text-[11px] font-black uppercase tracking-[0.22em] text-white/70">State</p>
                                <p class="mt-2 text-lg font-black text-white">Tema Sinkron</p>
                            </div>
                            <div class="rounded-3xl bg-white/12 p-5 backdrop-blur-sm">
                                <p class="text-[11px] font-black uppercase tracking-[0.22em] text-white/70">Flow</p>
                                <p class="mt-2 text-lg font-black text-white">Langsung Jelas</p>
                            </div>
                        </div>
                    </section>

                    <section class="mx-auto flex w-full max-w-xl items-center">
                        <div class="w-full rounded-[32px] border border-slate-200 bg-white/92 p-7 shadow-[0_24px_55px_rgba(15,23,42,0.08)] backdrop-blur-xl dark:border-slate-800 dark:bg-slate-900/82 dark:shadow-none sm:p-10">
                            {{ $slot }}
                        </div>
                    </section>
                </div>
            </div>
        </div>
    </body>
</html>
