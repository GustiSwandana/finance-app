<aside class="sidebar-wrapper fixed top-0 z-30 block h-full w-[308px] bg-white dark:bg-darkblack-600 sm:hidden xl:block">
    <div class="sidebar-header relative z-30 flex h-[108px] w-full items-center border-b border-r border-b-[#F7F7F7] border-r-[#F7F7F7] pl-[50px] dark:border-darkblack-400">
        <a href="{{ route('dashboard') }}" class="flex items-center gap-3">
            <img src="{{ asset('assets/images/logo/logo-color.svg') }}" class="block dark:hidden" alt="logo" />
            <img src="{{ asset('assets/images/logo/logo-white.svg') }}" class="hidden dark:block" alt="logo" />
        </a>
        <button type="button" class="drawer-btn absolute right-0 top-auto" title="Ctrl+b">
            <span>
                <svg width="16" height="40" viewBox="0 0 16 40" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M0 10C0 4.47715 4.47715 0 10 0H16V40H10C4.47715 40 0 35.5228 0 30V10Z" fill="#22C55E" />
                    <path d="M10 15L6 20.0049L10 25.0098" stroke="#ffffff" stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round" />
                </svg>
            </span>
        </button>
    </div>

    <div class="sidebar-body overflow-style-none relative z-30 h-screen w-full overflow-y-scroll pb-[200px] pl-[48px] pt-[14px]">
        <div class="nav-wrapper mb-[36px] pr-[50px]">

            <div class="item-wrapper mb-5">
                <h4 class="border-b border-bgray-200 text-sm font-medium leading-7 text-bgray-700 dark:border-darkblack-400 dark:text-bgray-50">
                    Menu Utama
                </h4>
                <ul class="mt-2.5">

                    <li class="item py-[11px] {{ request()->routeIs('dashboard') ? 'text-success-300 font-bold' : 'text-bgray-900 dark:text-white' }}">
                        <a href="{{ route('dashboard') }}">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center space-x-2.5">
                                    <span class="item-ico">
                                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M2 13H8V3H2V13ZM9 3V13H22V3H9ZM2 21H8V15H2V21ZM9 21H22V15H9V21Z" fill="currentColor" /></svg>
                                    </span>
                                    <span class="item-text text-lg leading-none">Dashboard</span>
                                </div>
                            </div>
                        </a>
                    </li>

                    <li class="item py-[11px] {{ request()->routeIs('users.*') ? 'text-success-300 font-bold' : 'text-bgray-900 dark:text-white' }}">
                        <a href="{{ route('users.index') }}">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center space-x-2.5">
                                    <span class="item-ico">
                                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2" />
                                            <circle cx="9" cy="7" r="4" />
                                            <path d="M23 21v-2a4 4 0 0 0-3-3.87" />
                                            <path d="M16 3.13a4 4 0 0 1 0 7.75" /></svg>
                                    </span>
                                    <span class="item-text text-lg leading-none">Manajemen User</span>
                                </div>
                            </div>
                        </a>
                    </li>

                    <li class="item py-[11px] {{ request()->routeIs('transactions.index') ? 'text-success-300 font-bold' : 'text-bgray-900 dark:text-white' }}">
                        <a href="{{ route('transactions.index') }}">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center space-x-2.5">
                                    <span class="item-ico">
                                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                                            <polyline points="14 2 14 8 20 8"></polyline>
                                            <line x1="16" y1="13" x2="8" y2="13"></line>
                                            <line x1="16" y1="17" x2="8" y2="17"></line>
                                            <polyline points="10 9 9 9 8 9"></polyline>
                                        </svg>
                                    </span>
                                    <span class="item-text text-lg leading-none">Transaksi</span>
                                </div>
                            </div>
                        </a>
                    </li>

                    <li class="item py-[11px] {{ request()->routeIs('transfer.create') ? 'text-success-300 font-bold' : 'text-bgray-900 dark:text-white' }}">
                        <a href="{{ route('transfer.create') }}">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center space-x-2.5">
                                    <span class="item-ico">
                                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M17 8L21 12L17 16M3 12H20M7 16L3 12L7 8" /></svg>
                                    </span>
                                    <span class="item-text text-lg leading-none">Transfer Dana</span>
                                </div>
                            </div>
                        </a>
                    </li>

                </ul>
            </div>

            <div class="item-wrapper mb-5">
                <h4 class="border-b border-bgray-200 text-sm font-medium leading-7 text-bgray-700 dark:border-darkblack-400 dark:text-bgray-50">
                    Laporan & Tagihan
                </h4>
                <ul class="mt-2.5">

                    <li class="item py-[11px] {{ request()->routeIs('subscriptions.index') ? 'text-success-300 font-bold' : 'text-bgray-900 dark:text-white' }}">
                        <a href="{{ route('subscriptions.index') }}">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center space-x-2.5">
                                    <span class="item-ico">
                                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                                            <line x1="16" y1="2" x2="16" y2="6"></line>
                                            <line x1="8" y1="2" x2="8" y2="6"></line>
                                            <line x1="3" y1="10" x2="21" y2="10"></line>
                                        </svg>
                                    </span>
                                    <span class="item-text text-lg leading-none">Tagihan Rutin</span>
                                </div>
                            </div>
                        </a>
                    </li>

                    <li class="item py-[11px] {{ request()->routeIs('debts.index') ? 'text-success-300 font-bold' : 'text-bgray-900 dark:text-white' }}">
                        <a href="{{ route('debts.index') }}">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center space-x-2.5">
                                    <span class="item-ico">
                                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2" />
                                            <circle cx="9" cy="7" r="4" />
                                            <path d="M23 21v-2a4 4 0 0 0-3-3.87" />
                                            <path d="M16 3.13a4 4 0 0 1 0 7.75" /></svg>
                                    </span>
                                    <span class="item-text text-lg leading-none">Utang Piutang</span>
                                </div>
                            </div>
                        </a>
                    </li>

                    <li class="item py-[11px] {{ request()->routeIs('reports.index') ? 'text-success-300 font-bold' : 'text-bgray-900 dark:text-white' }}">
                        <a href="{{ route('reports.index') }}">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center space-x-2.5">
                                    <span class="item-ico">
                                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M21.21 15.89A10 10 0 1 1 8 2.83" />
                                            <path d="M22 12A10 10 0 0 0 12 2v10z" /></svg>
                                    </span>
                                    <span class="item-text text-lg leading-none">Laporan</span>
                                </div>
                            </div>
                        </a>
                    </li>

                    <li class="item py-[11px] {{ request()->routeIs('budgets.index') ? 'text-success-300 font-bold' : 'text-bgray-900 dark:text-white' }}">
                        <a href="{{ route('budgets.index') }}">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center space-x-2.5">
                                    <span class="item-ico">
                                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <circle cx="12" cy="12" r="10" />
                                            <path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z" />
                                            <path d="M2 12h20" /></svg>
                                    </span>
                                    <span class="item-text text-lgleading-none">Anggaran Bulanan</span>
                                </div>
                            </div>
                        </a>
                    </li>
                </ul>
            </div>



            <div class="item-wrapper mb-5">
                <h4 class="border-b border-bgray-200 text-sm font-medium leading-7 text-bgray-700 dark:border-darkblack-400 dark:text-bgray-50">
                    Pengaturan
                </h4>
                <ul class="mt-2.5">

                    <li class="item py-[11px] {{ request()->routeIs('wallets.index') ? 'text-success-300 font-bold' : 'text-bgray-900 dark:text-white' }}">
                        <a href="{{ route('wallets.index') }}">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center space-x-2.5">
                                    <span class="item-ico">
                                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <rect x="1" y="4" width="22" height="16" rx="2" ry="2"></rect>
                                            <line x1="1" y1="10" x2="23" y2="10"></line>
                                        </svg>
                                    </span>
                                    <span class="item-text text-lg leading-none">Data Bank</span>
                                </div>
                            </div>
                        </a>
                    </li>

                    <li class="item py-[11px] {{ request()->routeIs('categories.index') ? 'text-success-300 font-bold' : 'text-bgray-900 dark:text-white' }}">
                        <a href="{{ route('categories.index') }}">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center space-x-2.5">
                                    <span class="item-ico">
                                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M20.59 13.41l-7.17 7.17a2 2 0 0 1-2.83 0L2 12V2h10l8.59 8.59a2 2 0 0 1 0 2.82z"></path>
                                            <line x1="7" y1="7" x2="7.01" y2="7"></line>
                                        </svg>
                                    </span>
                                    <span class="item-text text-lg leading-none">Data Kategori</span>
                                </div>
                            </div>
                        </a>
                    </li>

                    <li class="item py-[11px] text-bgray-900 dark:text-white">
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <a href="#" onclick="event.preventDefault(); this.closest('form').submit();">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center space-x-2.5">
                                        <span class="item-ico">
                                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#EF4444" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path>
                                                <polyline points="16 17 21 12 16 7"></polyline>
                                                <line x1="21" y1="12" x2="9" y2="12"></line>
                                            </svg>
                                        </span>
                                        <span class="item-text text-lg leading-none text-error-300">Keluar Aplikasi</span>
                                    </div>
                                </div>
                            </a>
                        </form>
                    </li>

                </ul>
            </div>
        </div>

    </div>
</aside>

<div style="z-index: 25" class="aside-overlay fixed left-0 top-0 block h-full w-full bg-black bg-opacity-30 sm:hidden"></div>

<aside class="relative hidden w-[96px] bg-white dark:bg-black sm:block">
    <div class="sidebar-wrapper-collapse relative top-0 z-30 w-full">
        <div class="sidebar-header sticky top-0 z-20 flex h-[108px] w-full items-center justify-center border-b border-r border-b-[#F7F7F7] border-r-[#F7F7F7] bg-white dark:border-darkblack-500 dark:bg-darkblack-600">
            <a href="{{ route('dashboard') }}">
                <img src="{{ asset('assets/images/logo/logo-short.svg') }}" class="block dark:hidden" alt="logo" />
                <img src="{{ asset('assets/images/logo/logo-short-white.svg') }}" class="hidden dark:block" alt="logo" />
            </a>
        </div>
        <div class="sidebar-body w-full pt-[14px]">
            <div class="flex flex-col items-center">
                <div class="nav-wrapper mb-[36px]">
                    <div class="item-wrapper mb-5">
                        <ul class="mt-2.5 flex flex-col items-center justify-center">

                            <li class="item px-[43px] py-[11px]">
                                <a href="{{ route('dashboard') }}" title="Dashboard">
                                    <span class="item-ico">
                                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M2 13H8V3H2V13ZM9 3V13H22V3H9ZM2 21H8V15H2V21ZM9 21H22V15H9V21Z" fill="#22C55E" /></svg>
                                    </span>
                                </a>
                            </li>

                            <li class="item px-[43px] py-[11px]">
                                <a href="{{ route('transactions.index') }}" title="Transaksi">
                                    <span class="item-ico">
                                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                                            <polyline points="14 2 14 8 20 8"></polyline>
                                            <line x1="16" y1="13" x2="8" y2="13"></line>
                                            <line x1="16" y1="17" x2="8" y2="17"></line>
                                            <polyline points="10 9 9 9 8 9"></polyline>
                                        </svg>
                                    </span>
                                </a>
                            </li>

                            <li class="item px-[43px] py-[11px]">
                                <a href="{{ route('reports.index') }}" title="Laporan">
                                    <span class="item-ico">
                                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M21.21 15.89A10 10 0 1 1 8 2.83" />
                                            <path d="M22 12A10 10 0 0 0 12 2v10z" /></svg>
                                    </span>
                                </a>
                            </li>

                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</aside>
