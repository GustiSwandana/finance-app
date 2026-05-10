<aside class="sidebar-wrapper fixed top-0 z-30 h-full w-[308px] border-r border-slate-200/70 bg-[linear-gradient(180deg,rgba(255,255,255,0.98),rgba(244,247,251,0.96))] backdrop-blur dark:border-slate-800 dark:bg-[linear-gradient(180deg,rgba(2,6,23,0.98),rgba(15,23,42,0.96))] sm:hidden xl:block">
    <div class="sidebar-header relative z-30 flex h-[108px] w-full items-center justify-between border-b border-slate-200/80 px-8 dark:border-slate-800">
        <a href="{{ route('dashboard') }}" class="flex items-center gap-3">
            <span class="flex h-12 w-12 items-center justify-center rounded-2xl bg-slate-950 text-sm font-black uppercase tracking-[0.3em] text-white dark:bg-white dark:text-slate-950">FA</span>
            <div>
                <p class="text-xs font-semibold uppercase tracking-[0.24em] text-slate-400">Finance App</p>
                <p class="mt-1 text-base font-black tracking-tight text-slate-950 dark:text-white">Personal Console</p>
            </div>
        </a>
        <button type="button" class="drawer-btn rounded-2xl border border-slate-200 bg-white p-2 text-slate-500 transition hover:border-emerald-200 hover:text-emerald-600 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-300 dark:hover:border-emerald-900/40 dark:hover:text-emerald-300" title="Ctrl+b">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M15 18l-6-6 6-6" />
            </svg>
        </button>
    </div>

    <div class="sidebar-body overflow-style-none relative z-30 h-screen w-full overflow-y-auto px-5 pb-32 pt-5">
        <div class="mb-6 rounded-[28px] border border-slate-200 bg-white/90 p-5 shadow-sm dark:border-slate-800 dark:bg-[linear-gradient(180deg,rgba(15,23,42,0.96),rgba(30,41,59,0.86))] dark:shadow-none">
            <p class="text-xs font-semibold uppercase tracking-[0.24em] text-slate-400">Masuk sebagai</p>
            <p class="mt-3 line-clamp-2 text-base font-black tracking-tight text-slate-950 dark:text-white">{{ auth()->user()?->name }}</p>
            <div class="mt-3 flex items-center gap-2">
                <span class="rounded-full px-3 py-1 text-[10px] font-black uppercase tracking-[0.2em] {{ auth()->user()?->isAdmin() ? 'bg-sky-100 text-sky-700 dark:bg-sky-950/60 dark:text-sky-200' : 'bg-slate-100 text-slate-600 dark:bg-slate-800 dark:text-slate-200' }}">
                    {{ auth()->user()?->role ?? 'user' }}
                </span>
                <span class="rounded-full px-3 py-1 text-[10px] font-black uppercase tracking-[0.2em] {{ auth()->user()?->is_active ? 'bg-emerald-100 text-emerald-700 dark:bg-emerald-950/60 dark:text-emerald-200' : 'bg-amber-100 text-amber-700 dark:bg-amber-950/60 dark:text-amber-200' }}">
                    {{ auth()->user()?->is_active ? 'aktif' : 'pending' }}
                </span>
            </div>
        </div>

        @php
            $sections = [
                'Menu Utama' => [
                    ['route' => 'dashboard', 'match' => 'dashboard', 'label' => 'Dashboard', 'icon' => '<path d="M2 13H8V3H2V13ZM9 3V13H22V3H9ZM2 21H8V15H2V21ZM9 21H22V15H9V21Z" fill="currentColor" />'],
                    ['route' => 'transactions.index', 'match' => 'transactions.index', 'label' => 'Transaksi', 'icon' => '<path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line><polyline points="10 9 9 9 8 9"></polyline>'],
                    ['route' => 'transfer.create', 'match' => 'transfer.create', 'label' => 'Transfer Dana', 'icon' => '<path d="M17 8L21 12L17 16M3 12H20M7 16L3 12L7 8" />'],
                    ['route' => 'bank-mutations.create', 'match' => 'bank-mutations.*', 'label' => 'Import Mutasi', 'icon' => '<path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4" /><polyline points="17 8 12 3 7 8" /><line x1="12" y1="3" x2="12" y2="15" />'],
                    ['route' => 'calculator.index', 'match' => 'calculator.*', 'label' => 'Kalkulator', 'icon' => '<rect x="4" y="2" width="16" height="20" rx="2" /><line x1="8" y1="6" x2="16" y2="6" /><line x1="8" y1="10" x2="8.01" y2="10" /><line x1="12" y1="10" x2="12.01" y2="10" /><line x1="16" y1="10" x2="16.01" y2="10" /><line x1="8" y1="14" x2="8.01" y2="14" /><line x1="12" y1="14" x2="12.01" y2="14" /><line x1="16" y1="14" x2="16.01" y2="14" /><line x1="8" y1="18" x2="12" y2="18" /><line x1="16" y1="18" x2="16.01" y2="18" />'],
                ],
                'Laporan & Tagihan' => [
                    ['route' => 'subscriptions.index', 'match' => 'subscriptions.index', 'label' => 'Tagihan Rutin', 'icon' => '<rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line>'],
                    ['route' => 'debts.index', 'match' => 'debts.index', 'label' => 'Utang Piutang', 'icon' => '<path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2" /><circle cx="9" cy="7" r="4" /><path d="M23 21v-2a4 4 0 0 0-3-3.87" /><path d="M16 3.13a4 4 0 0 1 0 7.75" />'],
                    ['route' => 'reports.index', 'match' => 'reports.index', 'label' => 'Laporan', 'icon' => '<path d="M21.21 15.89A10 10 0 1 1 8 2.83" /><path d="M22 12A10 10 0 0 0 12 2v10z" />'],
                    ['route' => 'budgets.index', 'match' => 'budgets.index', 'label' => 'Anggaran Bulanan', 'icon' => '<circle cx="12" cy="12" r="10" /><path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z" /><path d="M2 12h20" />'],
                ],
                'Pengaturan' => [
                    ['route' => 'wallets.index', 'match' => 'wallets.index', 'label' => 'Data Bank', 'icon' => '<rect x="1" y="4" width="22" height="16" rx="2" ry="2"></rect><line x1="1" y1="10" x2="23" y2="10"></line>'],
                    ['route' => 'categories.index', 'match' => 'categories.index', 'label' => 'Data Kategori', 'icon' => '<path d="M20.59 13.41l-7.17 7.17a2 2 0 0 1-2.83 0L2 12V2h10l8.59 8.59a2 2 0 0 1 0 2.82z"></path><line x1="7" y1="7" x2="7.01" y2="7"></line>'],
                    ['route' => 'profile.edit', 'match' => 'profile.edit', 'label' => 'Profil Saya', 'icon' => '<path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2" /><circle cx="12" cy="7" r="4" />'],
                ],
            ];

            if (auth()->user()?->isAdmin()) {
                array_splice($sections['Menu Utama'], 1, 0, [
                    ['route' => 'users.index', 'match' => 'users.*', 'label' => 'Manajemen User', 'icon' => '<path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2" /><circle cx="9" cy="7" r="4" /><path d="M23 21v-2a4 4 0 0 0-3-3.87" /><path d="M16 3.13a4 4 0 0 1 0 7.75" />'],
                    ['route' => 'admin.approvals.index', 'match' => 'admin.approvals.*', 'label' => 'Persetujuan User', 'icon' => '<path d="M9 12l2 2 4-4" /><path d="M21 12c0 1.66-.33 3.24-.93 4.68A9 9 0 1 1 12 3a8.96 8.96 0 0 1 6.36 2.64" />'],
                ]);
            }
        @endphp

        @foreach($sections as $title => $items)
        <div class="mb-7">
            <h4 class="px-2 text-[11px] font-semibold uppercase tracking-[0.26em] text-slate-400 dark:text-slate-500">{{ $title }}</h4>
            <ul class="mt-3 space-y-2">
                @foreach($items as $item)
                @php $active = request()->routeIs($item['match']); @endphp
                <li>
                    <a href="{{ route($item['route']) }}" class="group flex items-center gap-3 rounded-2xl px-4 py-3 text-sm font-bold transition {{ $active ? 'bg-white text-slate-950 shadow-[0_12px_30px_rgba(15,23,42,0.10)] dark:bg-slate-100 dark:text-slate-950' : 'text-slate-600 hover:bg-white hover:text-slate-950 dark:text-slate-300 dark:hover:bg-slate-900 dark:hover:text-white' }}">
                        <span class="flex h-11 w-11 items-center justify-center rounded-2xl {{ $active ? 'bg-white/12 text-white dark:bg-slate-200 dark:text-slate-950' : 'bg-slate-100 text-slate-500 group-hover:bg-slate-950 group-hover:text-white dark:bg-slate-800 dark:text-slate-300 dark:group-hover:bg-white dark:group-hover:text-slate-950' }}">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">{!! $item['icon'] !!}</svg>
                        </span>
                        <span>{{ $item['label'] }}</span>
                    </a>
                </li>
                @endforeach
            </ul>
        </div>
        @endforeach

        <div class="mt-8 rounded-[28px] border border-rose-100 bg-rose-50/70 p-4 dark:border-rose-900/30 dark:bg-rose-950/20">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="flex w-full items-center gap-3 rounded-2xl px-3 py-2 text-left text-sm font-bold text-rose-600 transition hover:bg-white dark:text-rose-200 dark:hover:bg-slate-900/70">
                    <span class="flex h-11 w-11 items-center justify-center rounded-2xl bg-white text-rose-500 dark:bg-slate-900 dark:text-rose-200">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path>
                            <polyline points="16 17 21 12 16 7"></polyline>
                            <line x1="21" y1="12" x2="9" y2="12"></line>
                        </svg>
                    </span>
                    <span>Keluar Aplikasi</span>
                </button>
            </form>
        </div>
    </div>
</aside>
