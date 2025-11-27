<header class="header-wrapper fixed z-30 hidden w-full md:block">
    <div class="relative flex h-[108px] w-full items-center justify-between bg-white px-10 dark:bg-darkblack-600 2xl:px-[76px] border-b border-bgray-200 dark:border-darkblack-400">

        <div class="flex items-center gap-6">
            <button title="Ctrl+b" type="button" class="drawer-btn rotate-180 transform transition-transform hover:scale-110">
                <span>
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M4 6H20M4 12H20M4 18H20" stroke="#A0AEC0" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                </span>
            </button>

            <div>
                <h3 class="text-xl font-bold text-bgray-900 dark:text-bgray-50 lg:text-3xl lg:leading-[36.4px]">
                    @if(request()->routeIs('dashboard')) Dashboard
                    @elseif(request()->routeIs('transactions.*')) Transaksi
                    @elseif(request()->routeIs('wallets.*')) Kelola Bank
                    @elseif(request()->routeIs('categories.*')) Kelola Kategori
                    @elseif(request()->routeIs('reports.*')) Laporan Keuangan
                    @elseif(request()->routeIs('subscriptions.*')) Tagihan Rutin
                    @else Halaman @endif
                </h3>
                <p class="text-xs font-medium text-bgray-600 dark:text-bgray-50 lg:text-sm lg:leading-[25.2px]">
                    {{ date('l, d F Y') }}
                </p>
            </div>
        </div>

        <div class="quick-access-wrapper relative">
            <div class="flex items-center space-x-[43px]">

                <div onclick="profileAction()" class="flex cursor-pointer items-center gap-4 transition-opacity hover:opacity-80">
                    <div class="h-[52px] w-[52px] overflow-hidden rounded-full border-2 border-bgray-200 p-0.5">
                        <img class="h-full w-full object-cover rounded-full" src="{{ Auth::user()->avatar ? asset('storage/' . Auth::user()->avatar) : asset('assets/images/avatar/profile-52x52.png') }}" alt="Profile Avatar" />
                    </div>
                    <div class="hidden 2xl:block">
                        <div class="flex items-center gap-2">
                            <h3 class="text-base font-bold leading-[28px] text-bgray-900 dark:text-white">
                                {{ Auth::user()->name ?? 'User' }}
                            </h3>
                            <svg class="stroke-bgray-900 dark:stroke-white" width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M6 9L12 15L18 9" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                        </div>
                        <p class="text-sm font-medium leading-[20px] text-success-300">Online</p>
                    </div>
                </div>

                <div class="profile-wrapper">
                    <div onclick="profileAction()" class="profile-outside fixed inset-0 hidden z-40"></div>
                    <div style="filter: drop-shadow(0px 10px 20px rgba(0, 0, 0, 0.1));" class="profile-box absolute right-0 top-[80px] hidden w-[250px] overflow-hidden rounded-xl bg-white dark:bg-darkblack-600 z-50 border border-bgray-100 dark:border-darkblack-400">
                        <div class="relative w-full p-2">
                            <ul class="flex flex-col gap-1">
                                <li class="w-full">
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit" class="flex w-full items-center gap-3 rounded-lg p-3 text-bgray-600 hover:bg-error-50 hover:text-error-300 transition-all">
                                            <div class="flex h-8 w-8 items-center justify-center rounded-full bg-bgray-100 text-bgray-600 group-hover:bg-error-100 group-hover:text-error-300">
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
</header>

<header class="mobile-wrapper fixed z-20 block w-full md:hidden border-b border-bgray-200 dark:border-darkblack-400">
    <div class="flex h-[80px] w-full items-center justify-between bg-white px-6 dark:bg-darkblack-600">

        <div class="flex h-full items-center gap-4">
            <button type="button" class="drawer-btn transform transition-transform hover:scale-110">
                <span>
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M4 6H20M4 12H20M4 18H20" stroke="#A0AEC0" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                </span>
            </button>
            <a href="{{ route('dashboard') }}">
                <img src="{{ asset('assets/images/logo/logo-color.svg') }}" class="h-8 block dark:hidden" alt="logo" />
                <img src="{{ asset('assets/images/logo/logo-white.svg') }}" class="h-8 hidden dark:block" alt="logo" />
            </a>
        </div>

        <div onclick="profileAction()" class="relative">
            <div class="h-10 w-10 overflow-hidden rounded-full border border-bgray-300">
                <img class="h-full w-full object-cover rounded-full" src="{{ Auth::user()->avatar ? asset('storage/' . Auth::user()->avatar) : asset('assets/images/avatar/profile-52x52.png') }}" alt="Profile Avatar" />
            </div>

            <div class="profile-wrapper">
                <div onclick="profileAction()" class="profile-outside fixed inset-0 hidden z-40"></div>
                <div style="filter: drop-shadow(0px 10px 20px rgba(0, 0, 0, 0.1));" class="profile-box absolute right-0 top-[50px] hidden w-[200px] overflow-hidden rounded-xl bg-white dark:bg-darkblack-600 z-50 border border-bgray-100">
                    <div class="p-2">
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="flex w-full items-center gap-3 rounded-lg p-3 text-error-300 hover:bg-error-50">
                                <span class="text-sm font-bold">Log Out</span>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

    </div>
</header>
