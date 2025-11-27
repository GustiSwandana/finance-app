<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta http-equiv="X-UA-Compatible" content="ie=edge" />
    <link href="{{ asset('assets/css/output.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/css/swiper-bundle.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/css/aos.css') }}" rel="stylesheet" />
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}" />
    <title>MyFinance - Kelola Keuangan Pribadi</title>
</head>
<body class="relative box-border w-full overflow-x-hidden font-urbanist 2xl:px-0">

    <section class="b-gray-300 relative z-50 flex w-full items-center justify-between gap-9 border-b bg-white transition-all xl:hidden">
        <div class="fixed top-0 z-40 flex w-full justify-between bg-white p-6">
            <a href="/" class="flex h-fit gap-1.5">
                <img src="{{ asset('assets/images/logo/logoBlack.svg') }}" alt="logo" class="w-5 sm:w-7 lg:w-10 xl:w-fit" />
                <span class="flex h-full items-center justify-center pr-16 text-xl font-extrabold leading-extra-loose tracking-tight text-primary lg:text-4xl">
                    <span class="text-gray-900">My</span>Finance
                </span>
            </a>
            <div class="m-menu-btn flex w-6 lg:w-10">
                <img src="{{ asset('assets/images/icon/bars-solid.svg') }}" alt="" class="bars block transition-all" />
                <img src="{{ asset('assets/images/icon/xmark-solid.svg') }}" alt="" class="xmark hidden transition-all" />
            </div>
        </div>
        <div class="m-menu fixed -left-[100%] top-[93px] z-40 flex h-full w-full max-w-sm flex-col gap-4 rounded-br-md bg-white py-6 pl-[5%] pr-[5%] text-xl font-medium leading-9 text-gray-900 transition-all duration-500 lg:text-2xl">
            <div class="flex w-full flex-col gap-4">
                @if (Route::has('login'))
                @auth
                <a href="{{ url('/dashboard') }}" class="group relative z-50 inline-flex shrink-0 items-center justify-center gap-3 overflow-hidden rounded-xl border-[2.333px] border-primary transition-all">
                    <div class="z-10 h-full w-full px-6 py-3 text-center text-xl font-semibold leading-8 text-primary group-hover:text-white">Dashboard</div>
                </a>
                @else
                <a href="{{ route('login') }}" class="group relative z-50 inline-flex shrink-0 items-center justify-center gap-3 overflow-hidden rounded-xl border-[2.333px] border-primary transition-all">
                    <div class="absolute bottom-0 right-0 z-0 h-0 w-0 bg-primary transition-all group-hover:h-full group-hover:w-full"></div>
                    <div class="z-10 h-full w-full px-6 py-3 text-center text-xl font-semibold leading-8 text-primary group-hover:text-white">Masuk</div>
                </a>
                @if (Route::has('register'))
                <a href="{{ route('register') }}" class="group relative z-40 inline-flex shrink-0 items-center justify-center gap-3 overflow-hidden rounded-xl border-[2.333px] border-primary transition-all">
                    <div class="absolute bottom-0 right-0 z-0 h-full w-full bg-primary transition-all group-hover:h-0 group-hover:w-0"></div>
                    <span class="z-10 h-full w-full px-6 py-3 text-center text-xl font-semibold leading-8 text-white group-hover:text-primary">Daftar Akun</span>
                </a>
                @endif
                @endauth
                @endif
            </div>
        </div>
    </section>
    <section class="b-gray-300 hidden items-center justify-between gap-9 border-b bg-white p-6 xl:flex">
        <a href="/" class="flex h-fit gap-1.5">
            <img src="{{ asset('assets/images/logo/logoBlack.svg') }}" alt="logo" />
            <span class="flex h-full items-center justify-center pr-16 text-4xl font-extrabold leading-extra-loose tracking-tight text-primary">
                <span class="text-gray-900">My</span>Finance
            </span>
        </a>

        <div class="flex gap-4">
            @if (Route::has('login'))
            @auth
            <a href="{{ url('/dashboard') }}" class="group relative z-50 inline-flex h-[60px] shrink-0 items-center justify-center gap-3 overflow-hidden rounded-xl border-[2.333px] border-primary transition-all">
                <span class="z-10 h-full w-full px-9 py-2.5 text-center text-xl font-semibold leading-8 text-primary group-hover:text-white">Ke Dashboard</span>
            </a>
            @else
            <a href="{{ route('login') }}" class="group relative z-50 inline-flex h-[60px] shrink-0 items-center justify-center gap-3 overflow-hidden rounded-xl border-[2.333px] border-primary transition-all">
                <div class="absolute bottom-0 right-0 z-0 h-0 w-0 bg-primary transition-all group-hover:h-full group-hover:w-full"></div>
                <span class="z-10 h-full w-full px-9 py-2.5 text-center text-xl font-semibold leading-8 text-primary group-hover:text-white">Masuk</span>
            </a>
            @if (Route::has('register'))
            <a href="{{ route('register') }}" class="group relative z-40 inline-flex h-[60px] shrink-0 items-center justify-center gap-3 overflow-hidden rounded-xl border-[2.333px] border-primary transition-all">
                <div class="absolute bottom-0 right-0 z-0 h-full w-full bg-primary transition-all group-hover:h-0 group-hover:w-0"></div>
                <div class="z-10 h-full w-full px-9 py-2.5 text-center text-xl font-semibold leading-8 text-white group-hover:text-primary">Daftar Baru</div>
            </a>
            @endif
            @endauth
            @endif
        </div>
    </section>
    <section class="flex w-full items-center justify-center xl:px-[5%] 2xl:px-0" data-aos="fade-up">
        <div class="relative flex w-full max-w-[1320px] flex-col items-center justify-center p-10 pb-36 pt-28 transition-all duration-1000">
            <div class="overFlowHidden moving-element absolute h-full w-full xl:block">
                <img class="absolute right-[2%] top-[6%] z-10 2xl:right-[1%]" src="{{ asset('assets/images/shape/path.svg') }}" alt="" />
                <img class="absolute left-[3%] top-[6%] z-10" src="{{ asset('assets/images/shape/path0.svg') }}" alt="" />
                <img class="absolute left-[-1%] top-[16%] z-10 2xl:left-[-2%]" src="{{ asset('assets/images/shape/path1.svg') }}" alt="" />
            </div>

            <div class="w-full max-w-[1193px] text-center">
                <span class="text-center font-poppins text-3xl font-bold leading-big-loose decoration-[#090F32] sm:text-5xl xl:text-6xl 2xl:text-7xl">
                    <span class="relative">
                        <img class="absolute bottom-3 left-0 w-[150px] max-w-full xl:w-auto" src="{{ asset('assets/images/shape/fill3.svg') }}" alt="" />
                        Atur
                    </span>
                    <span class="leading-big-loose"> Keuangan </span>
                    <span class="bg-primary-new px-2 leading-big-loose"> Pribadi </span>
                    dengan Logika Cerdas
                </span>
            </div>
            <div class="mt-5 w-full max-w-[784px] text-center">
                <span class="text-lg font-medium leading-160 text-[#48494E] decoration-[#48494E] xl:text-xl">
                    Pantau saldo BCA, Mandiri, dan BRI Anda dalam satu dashboard terintegrasi. Sistem otomatis mengatur kategori pengeluaran Anda.
                </span>
            </div>
            <div class="relative mt-10 flex items-center justify-center gap-9">
                @if (Route::has('login'))
                @auth
                <a href="{{ url('/dashboard') }}" class="group relative z-40 inline-flex h-[60px] shrink-0 items-center justify-center gap-3 overflow-hidden rounded-xl border-[2.333px] border-primary transition-all">
                    <div class="absolute bottom-0 right-0 z-0 h-full w-full bg-primary transition-all group-hover:h-0 group-hover:w-0"></div>
                    <div class="z-10 h-full w-full px-9 py-2.5 text-center text-xl font-semibold leading-8 text-white group-hover:text-primary">Buka Dashboard</div>
                </a>
                @else
                <a href="{{ route('register') }}" class="group relative z-40 inline-flex h-[60px] shrink-0 items-center justify-center gap-3 overflow-hidden rounded-xl border-[2.333px] border-primary transition-all">
                    <div class="absolute bottom-0 right-0 z-0 h-full w-full bg-primary transition-all group-hover:h-0 group-hover:w-0"></div>
                    <div class="z-10 h-full w-full px-9 py-2.5 text-center text-xl font-semibold leading-8 text-white group-hover:text-primary">Mulai Sekarang</div>
                </a>
                <a href="{{ route('login') }}" class="group relative z-50 inline-flex h-[60px] shrink-0 items-center justify-center gap-3 overflow-hidden rounded-xl border-[2.333px] border-primary transition-all">
                    <div class="absolute bottom-0 right-0 z-0 h-0 w-0 bg-primary transition-all group-hover:h-full group-hover:w-full"></div>
                    <span class="z-10 h-full w-full px-9 py-2.5 text-center text-xl font-semibold leading-8 text-primary group-hover:text-white">Masuk</span>
                </a>
                @endauth
                @endif
            </div>

            <div class="relative z-0 flex items-center justify-center">
                <div class="mt-[70px] box-border flex w-full max-w-[1320px] items-center justify-center rounded-20 border-[15px] border-[#F3F7F8] bg-[#F3F7F8] sm:p-7 xl:p-14">
                    <img class="z-50 h-full w-full rounded-20" src="{{ asset('assets/images/dashboard/dashboard.jpg') }}" alt="dashboard preview" />
                </div>
            </div>
        </div>
    </section>
    <section class="flex w-full flex-col items-center justify-center px-[5%] 2xl:pt-28" data-aos="fade-up" data-aos-anchor-placement="top-bottom">
        <div class="w-full max-w-[614px] text-center">
            <span class="text-center font-poppins text-3xl font-bold leading-big-loose text-gray-800 sm:text-4xl xl:text-4xl 2xl:text-5xl">
                Fitur Utama
            </span>
        </div>

        <div class="flex w-full max-w-[1320px] flex-wrap items-center justify-center gap-x-7 gap-y-3 pt-20">

            <div class="overflow-hidden pt-4" data-aos="flip-left" data-aos-easing="ease-out-cubic">
                <div class="active group relative flex w-[306px] flex-col items-center justify-center overflow-hidden rounded-20 border border-solid border-gray-200 p-6 drop-shadow-[10px_10px_40px_0px_rgba(39,218,104,0.10)] duration-500 ease-in-out hover:bg-primary current:bg-primary">
                    <img src="{{ asset('assets/images/cards/cardBg.png') }}" alt="" class="absolute -bottom-[55px] -right-[58px]" />
                    <div class="max-w-[120px]">
                        <img class="active hidden group-hover:block current:block" src="{{ asset('assets/images/features/friendlyW.svg') }}" alt="icon" />
                        <img class="active block group-hover:hidden current:hidden" src="{{ asset('assets/images/features/friendly.svg') }}" alt="icon" />
                    </div>
                    <div class="z-10 text-center font-urbanist">
                        <span class="active text-3xl font-bold leading-150 text-gray-900 group-hover:text-white current:text-white">Multi Akun</span><br />
                        <span class="active z-10 px-2 text-base font-medium text-gray-600 group-hover:text-gray-300 current:text-gray-300">
                            Kelola BCA, Mandiri, dan BRI dalam satu tempat.
                        </span>
                    </div>
                </div>
            </div>

            <div class="overflow-hidden pt-4" data-aos="flip-right" data-aos-easing="ease-out-cubic">
                <div class="group relative flex w-[306px] flex-col items-center justify-center overflow-hidden rounded-20 border border-solid border-gray-200 p-6 drop-shadow-[10px_10px_40px_0px_rgba(39,218,104,0.10)] duration-500 ease-in-out hover:bg-primary current:bg-primary">
                    <img src="{{ asset('assets/images/cards/cardBg.png') }}" alt="" class="absolute -bottom-[55px] -right-[58px]" />
                    <div class="max-w-[120px]">
                        <img class="hidden group-hover:block current:block" src="{{ asset('assets/images/features/reactW.svg') }}" alt="icon" />
                        <img class="block group-hover:hidden current:hidden" src="{{ asset('assets/images/features/react.svg') }}" alt="icon" />
                    </div>
                    <div class="z-10 text-center font-urbanist">
                        <span class="text-3xl font-bold leading-150 text-gray-900 group-hover:text-white current:text-white">Logika Cerdas</span><br />
                        <span class="z-10 px-2 text-base font-medium text-gray-600 group-hover:text-gray-300 current:text-gray-300">
                            Otomatisasi sumber dana berdasarkan kategori (Makanan -> BCA).
                        </span>
                    </div>
                </div>
            </div>

            <div class="overflow-hidden pt-4" data-aos="flip-left" data-aos-easing="ease-out-cubic">
                <div class="group relative flex w-[306px] flex-col items-center justify-center overflow-hidden rounded-20 border border-solid border-gray-200 p-6 drop-shadow-[10px_10px_40px_0px_rgba(39,218,104,0.10)] duration-500 ease-in-out hover:bg-primary current:bg-primary">
                    <img src="{{ asset('assets/images/cards/cardBg.png') }}" alt="" class="absolute -bottom-[55px] -right-[58px]" />
                    <div class="max-w-[120px]">
                        <img class="hidden group-hover:block current:block" src="{{ asset('assets/images/features/customerServiceW.svg') }}" alt="icon" />
                        <img class="block group-hover:hidden current:hidden" src="{{ asset('assets/images/features/customerService.svg') }}" alt="icon" />
                    </div>
                    <div class="z-10 text-center font-urbanist">
                        <span class="text-3xl font-bold leading-150 text-gray-900 group-hover:text-white current:text-white">Riwayat Transaksi</span><br />
                        <span class="z-10 px-2 text-base font-medium text-gray-600 group-hover:text-gray-300 current:text-gray-300">
                            Rekap lengkap pemasukan dan pengeluaran Anda.
                        </span>
                    </div>
                </div>
            </div>

        </div>
    </section>
    <section class="relative mt-[60px] flex w-full flex-wrap items-center justify-center p-3 xl:p-[5%] 2xl:mt-[120px] 2xl:p-0" data-aos="fade-up" data-aos-easing="fade-down">
        <div class="relative flex w-full max-w-[1320px] flex-col justify-between overflow-hidden rounded-20 bg-gray-900 pl-8 pt-10 lg:flex-row xl:pb-0 xl:pl-16 xl:pt-[73px]">
            <div class="absolute bottom-32 left-[-160px] h-[651px] w-12 rounded-full bg-white bg-gradient-to-l xl:w-[651px] xl:p-24" style="background: linear-gradient(to left, rgba(255, 255, 255, 0.2), rgba(255, 255, 255, 0)); z-index: -0;">
                <div class="h-full w-full rounded-full bg-basicInterface"></div>
            </div>

            <div class="z-10 pr-5">
                <div class="flex w-full max-w-[513px] flex-col">
                    <span class="font-poppins text-xl font-bold leading-big-loose text-white sm:text-3xl xl:text-4xl 2xl:text-5xl">
                        Siap mengatur keuangan Anda?
                    </span>
                    <span class="pb-12 pt-7 text-lg font-medium leading-160 text-white 2xl:text-xl">
                        Daftar sekarang gratis dan mulai kelola arus kas harian, tabungan, dan kebutuhan lainnya dengan lebih teratur.
                    </span>
                    <div class="flex gap-3 xl:gap-8 pb-10">
                        <a href="{{ route('register') }}" class="group relative z-40 inline-flex h-[60px] shrink-0 items-center justify-center gap-3 overflow-hidden rounded-xl border border-primary px-5 py-1 transition-all xl:border-[2.333px] xl:px-9 xl:py-2.5">
                            <div class="absolute bottom-0 right-0 z-0 h-full w-full bg-primary transition-all group-hover:h-0 group-hover:w-0"></div>
                            <span class="z-10 text-center text-lg font-semibold leading-8 text-white group-hover:text-primary xl:text-xl">
                                Buat Akun
                            </span>
                        </a>
                    </div>
                </div>
            </div>
            <div class="flex w-full justify-end lg:w-1/2">
                <div class="z-10 mt-10 w-fit pb-12 xl:mt-0">
                    <div class="rounded-l-20 bg-lightGray py-4 pl-4 xl:pl-12">
                        <img src="{{ asset('assets/images/dashboard/dashboard2.jpg') }}" alt="dashboard" />
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="relative flex w-full flex-col items-center justify-center bg-basicInterface px-[5%] mt-[60px] 2xl:px-0">
        <img src="{{ asset('assets/images/footer/bg.png') }}" alt="" class="absolute right-0 top-0 z-0" />
        <div class="z-10 flex w-full max-w-[1320px] flex-col gap-5 py-[60px] xl:flex-row xl:justify-between">

            <div class="w-full max-w-[270px]">
                <a href="#" class="flex items-center gap-[6.88px] pb-5">
                    <span class="text-3xl font-extrabold leading-big-loose text-white xl:text-4xl">
                        My<span class="text-primary">Finance</span>
                    </span>
                </a>
                <span class="text-base font-normal leading-180 text-white xl:text-lg">
                    Aplikasi manajemen keuangan pribadi yang simpel dan efisien.
                </span>
            </div>

            <div class="flex flex-col gap-6 xl:flex-row xl:gap-[74px] text-white">
                <div>
                    <h3 class="text-xl font-bold mb-4">Akses Cepat</h3>
                    <ul class="flex flex-col gap-2 text-basicInterface2">
                        <li><a href="{{ route('login') }}">Masuk</a></li>
                        <li><a href="{{ route('register') }}">Daftar</a></li>
                    </ul>
                </div>
            </div>

        </div>
        <hr class="w-full bg-[#7476813B] opacity-25" />
        <span class="p-[22px] text-center text-sm font-medium leading-160 text-white xl:text-xl">
            Copyright © {{ date('Y') }} – MyFinance Personal App by Gusti Swandana. All Right Reserved.
        </span>
    </section>
    <script src="{{ asset('assets/js/swiper-bundle.min.js') }}"></script>
    <script src="{{ asset('assets/js/aos.js') }}"></script>
    <script src="{{ asset('assets/js/app.js') }}"></script>
    <script>
        AOS.init();

    </script>
</body>
</html>
