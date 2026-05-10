@php
    $pageTitle = match (true) {
        request()->routeIs('dashboard') => 'Dashboard',
        request()->routeIs('calculator.*') => 'Kalkulator',
        request()->routeIs('transactions.*') => 'Transaksi',
        request()->routeIs('income.*') => 'Tambah Pemasukan',
        request()->routeIs('expense.*') => 'Tambah Pengeluaran',
        request()->routeIs('transfer.*') => 'Transfer Dana',
        request()->routeIs('wallets.*') => 'Kelola Bank',
        request()->routeIs('categories.*') => 'Kelola Kategori',
        request()->routeIs('reports.detail*') => 'Laporan Detail',
        request()->routeIs('reports.*') => 'Laporan Keuangan',
        request()->routeIs('subscriptions.*') => 'Tagihan Rutin',
        request()->routeIs('users.*') => 'Manajemen User',
        request()->routeIs('admin.approvals.*') => 'Persetujuan User',
        request()->routeIs('debts.*') => 'Utang & Piutang',
        request()->routeIs('budgets.*') => 'Anggaran Bulanan',
        request()->routeIs('bank-mutations.*', 'import.*') => 'Import Mutasi',
        default => 'Halaman',
    };
@endphp

<header class="header-wrapper fixed left-0 right-0 z-40 hidden md:block xl:left-[308px] xl:w-[calc(100%-308px)]">
    <div class="mx-4 mt-3 rounded-[26px] app-panel xl:mx-8 2xl:mx-10">
        <div class="relative flex h-[88px] w-full items-center justify-between px-7 2xl:px-10">
            <div class="flex items-center gap-5">
                <button title="Ctrl+b" type="button" class="drawer-btn flex h-12 w-12 items-center justify-center rounded-2xl bg-white/80 text-slate-500 shadow-sm transition-transform hover:scale-105 dark:bg-slate-900 dark:text-slate-200">
                    <span>
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M4 6H20M4 12H20M4 18H20" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                    </span>
                </button>

                <div>
                    <div class="mb-1 flex items-center gap-3">
                        <span class="rounded-full bg-teal-50 px-3 py-1 text-[10px] font-extrabold uppercase tracking-[0.2em] text-teal-700 dark:bg-teal-950/60 dark:text-teal-200">Workspace</span>
                        <span class="text-xs font-medium text-slate-400 dark:text-slate-500">{{ now()->format('d M Y') }}</span>
                    </div>
                    <h3 class="text-2xl font-extrabold text-slate-950 dark:text-white">
                        {{ $pageTitle }}
                    </h3>
                    <p class="text-sm font-medium text-slate-500 dark:text-slate-300">
                        {{ date('l, d F Y') }}
                    </p>
                </div>
            </div>

            <div class="quick-access-wrapper relative">
                <div class="flex items-center space-x-4">
                    <button type="button" data-theme-toggle class="theme-toggle hidden xl:inline-flex" aria-label="Toggle dark mode" aria-pressed="false">
                        <span class="flex h-9 w-9 items-center justify-center rounded-full bg-slate-100 text-slate-700 dark:bg-slate-800 dark:text-slate-100">
                            <svg data-theme-icon-dark width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M21 12.79A9 9 0 1 1 11.21 3c0 .28 0 .57.02.85A7 7 0 0 0 20.15 12c.28.02.57.02.85.02Z"></path>
                            </svg>
                            <svg data-theme-icon-light class="hidden" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
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
                        <span data-theme-label class="text-sm font-bold">Dark Mode</span>
                    </button>

                    <div class="hidden items-center gap-3 rounded-2xl bg-white/80 px-4 py-3 shadow-sm dark:bg-slate-900 xl:flex">
                        <div class="flex h-10 w-10 items-center justify-center rounded-2xl bg-teal-50 text-teal-700 dark:bg-teal-950/60 dark:text-teal-200">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M12 2v20" />
                                <path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-[11px] font-bold uppercase tracking-[0.18em] text-slate-400 dark:text-slate-500">Status</p>
                            <p class="text-sm font-bold text-slate-950 dark:text-white">{{ auth()->user()->isAdmin() ? 'Administrator' : 'Pengguna Aktif' }}</p>
                        </div>
                    </div>

                    <div onclick="profileAction()" class="flex cursor-pointer items-center gap-4 rounded-2xl bg-white/80 px-4 py-3 shadow-sm transition-opacity hover:opacity-90 dark:bg-slate-900">
                        <div class="flex h-[52px] w-[52px] items-center justify-center overflow-hidden rounded-2xl border border-slate-200 bg-slate-100 p-0.5 text-base font-black text-slate-700 dark:border-slate-700 dark:bg-slate-800 dark:text-slate-100">
                            @if (Auth::user()->avatar)
                            <img class="h-full w-full rounded-2xl object-cover" src="{{ asset('storage/' . Auth::user()->avatar) }}" alt="Profile Avatar" />
                            @else
                            <span>{{ strtoupper(substr(Auth::user()->name ?? 'U', 0, 1)) }}</span>
                            @endif
                        </div>
                        <div class="hidden 2xl:block">
                            <div class="flex items-center gap-2">
                                <h3 class="text-base font-bold leading-[28px] text-slate-950 dark:text-white">
                                    {{ Auth::user()->name ?? 'User' }}
                                </h3>
                                <svg class="stroke-slate-900 dark:stroke-white" width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M6 9L12 15L18 9" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                            </div>
                            <p class="text-sm font-medium leading-[20px] text-teal-700 dark:text-teal-300">Siap bekerja</p>
                        </div>
                    </div>

                    <div class="profile-wrapper">
                        <div onclick="profileAction()" class="profile-outside fixed inset-0 hidden z-40"></div>
                        <div class="profile-box absolute right-0 top-[84px] hidden w-[270px] overflow-hidden rounded-2xl app-panel z-50">
                            <div class="border-b border-slate-200 px-5 py-4 dark:border-slate-800">
                                <p class="text-xs font-extrabold uppercase tracking-[0.18em] text-slate-400 dark:text-slate-500">Akun Aktif</p>
                                <p class="mt-2 text-base font-bold text-slate-950 dark:text-white">{{ Auth::user()->email }}</p>
                            </div>
                            <div class="p-3">
                                <ul class="flex flex-col gap-1">
                                    <li class="w-full">
                                        <a href="{{ route('profile.edit') }}" class="flex w-full items-center gap-3 rounded-xl p-3 text-slate-600 transition-all hover:bg-teal-50 hover:text-teal-700 dark:text-slate-200 dark:hover:bg-teal-950/40 dark:hover:text-teal-200">
                                            <div class="flex h-8 w-8 items-center justify-center rounded-full bg-slate-100 text-slate-600 dark:bg-slate-800 dark:text-slate-200">
                                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                    <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                                                    <circle cx="12" cy="7" r="4"></circle>
                                                </svg>
                                            </div>
                                            <span class="text-sm font-semibold">Profil Saya</span>
                                        </a>
                                    </li>
                                    <li class="w-full">
                                        <form method="POST" action="{{ route('logout') }}">
                                            @csrf
                                            <button type="submit" class="flex w-full items-center gap-3 rounded-xl p-3 text-slate-600 transition-all hover:bg-rose-50 hover:text-rose-500 dark:text-slate-200 dark:hover:bg-rose-950/30 dark:hover:text-rose-200">
                                                <div class="flex h-8 w-8 items-center justify-center rounded-full bg-slate-100 text-slate-600 dark:bg-slate-800 dark:text-slate-200">
                                                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                        <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path>
                                                        <polyline points="16 17 21 12 16 7"></polyline>
                                                        <line x1="21" y1="12" x2="9" y2="12"></line>
                                                    </svg>
                                                </div>
                                                <span class="text-sm font-semibold">Keluar Aplikasi</span>
                                            </button>
                                        </form>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>

<header class="mobile-wrapper fixed z-20 block w-full md:hidden">
    <div class="mx-4 mt-3 rounded-[22px] app-panel">
        <div class="flex h-[76px] w-full items-center justify-between px-5">
            <div class="flex h-full items-center gap-4">
                <button type="button" class="drawer-btn flex h-11 w-11 items-center justify-center rounded-2xl bg-white/80 text-slate-500 shadow-sm dark:bg-slate-900 dark:text-slate-200">
                    <span>
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M4 6H20M4 12H20M4 18H20" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                    </span>
                </button>
                <div>
                    <p class="text-[10px] font-extrabold uppercase tracking-[0.2em] text-slate-400 dark:text-slate-500">MyFinance</p>
                    <h4 class="text-base font-extrabold text-slate-950 dark:text-white">{{ $pageTitle }}</h4>
                </div>
            </div>

            <div class="flex items-center gap-3">
                <button type="button" data-theme-toggle class="theme-toggle px-3 py-2.5" aria-label="Toggle dark mode" aria-pressed="false">
                    <span class="flex h-8 w-8 items-center justify-center rounded-full bg-slate-100 text-slate-700 dark:bg-slate-800 dark:text-slate-100">
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
                </button>

                <div onclick="profileAction()" class="relative">
                    <div class="flex h-10 w-10 items-center justify-center overflow-hidden rounded-2xl border border-slate-300 bg-slate-100 text-sm font-black text-slate-700 dark:border-slate-700 dark:bg-slate-800 dark:text-slate-100">
                        @if (Auth::user()->avatar)
                        <img class="h-full w-full rounded-2xl object-cover" src="{{ asset('storage/' . Auth::user()->avatar) }}" alt="Profile Avatar" />
                        @else
                        <span>{{ strtoupper(substr(Auth::user()->name ?? 'U', 0, 1)) }}</span>
                        @endif
                    </div>

                    <div class="profile-wrapper">
                        <div onclick="profileAction()" class="profile-outside fixed inset-0 hidden z-40"></div>
                        <div class="profile-box absolute right-0 top-[54px] hidden w-[220px] overflow-hidden rounded-2xl app-panel z-50">
                            <div class="p-3">
                                <a href="{{ route('profile.edit') }}" class="mb-1 flex w-full items-center gap-3 rounded-xl p-3 text-slate-600 hover:bg-teal-50 hover:text-teal-700 dark:text-slate-200 dark:hover:bg-teal-950/40 dark:hover:text-teal-200">
                                    <span class="text-sm font-semibold">Profil Saya</span>
                                </a>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="flex w-full items-center gap-3 rounded-xl p-3 text-rose-500 hover:bg-rose-50 dark:text-rose-300 dark:hover:bg-rose-950/30">
                                        <span class="text-sm font-bold">Keluar Aplikasi</span>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</header>
