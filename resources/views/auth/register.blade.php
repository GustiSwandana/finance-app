<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Daftar Akun Baru - MyFinance</title>

    <link rel="stylesheet" href="{{ asset('assets/css/output.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}" />
</head>
<body>
    <section class="bg-white dark:bg-darkblack-500">
        <div class="flex flex-col lg:flex-row justify-between min-h-screen">

            <div class="lg:w-1/2 px-5 xl:pl-12 pt-10">
                <header>
                    <a href="/" class="flex items-center gap-2">
                        <img src="{{ asset('assets/images/logo/logo-color.svg') }}" class="block dark:hidden h-10" alt="Logo" />
                        <img src="{{ asset('assets/images/logo/logo-white.svg') }}" class="hidden dark:block h-10" alt="Logo" />
                        <span class="text-xl font-bold text-bgray-900 dark:text-white">MyFinance</span>
                    </a>
                </header>

                <div class="max-w-[460px] m-auto pt-24 pb-16">
                    <header class="text-center mb-8">
                        <h2 class="text-bgray-900 dark:text-white text-4xl font-semibold font-poppins mb-2">
                            Buat Akun Baru
                        </h2>
                        <p class="font-urbanis text-base font-medium text-bgray-600 dark:text-darkblack-300">
                            Mulai kelola keuangan BCA, Mandiri, dan BRI Anda.
                        </p>
                    </header>

                    <div class="relative mt-6 mb-5">
                        <div class="absolute inset-0 flex items-center">
                            <div class="w-full border-t border-gray-300 dark:border-darkblack-400"></div>
                        </div>
                        <div class="relative flex justify-center text-sm">
                            <span class="bg-white dark:bg-darkblack-500 px-2 text-base text-bgray-600">
                                Isi data diri Anda
                            </span>
                        </div>
                    </div>

                    <form method="POST" action="{{ route('register') }}">
                        @csrf

                        @if ($errors->any())
                        <div class="mb-4 p-3 bg-red-100 border border-red-400 text-red-700 rounded relative" role="alert">
                            <strong class="font-bold">Ada kesalahan!</strong>
                            <ul class="list-disc pl-5 mt-1 text-sm">
                                @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                        @endif

                        <div class="mb-4">
                            <input type="text" name="name" value="{{ old('name') }}" class="text-bgray-800 dark:text-white dark:bg-darkblack-500 dark:border-darkblack-400 text-base border border-bgray-300 h-14 w-full focus:border-success-300 focus:ring-0 rounded-lg px-4 py-3.5 placeholder:text-bgray-500 placeholder:text-base" placeholder="Nama Lengkap" required autofocus />
                        </div>

                        <div class="mb-4">
                            <input type="email" name="email" value="{{ old('email') }}" class="text-bgray-800 dark:text-white dark:bg-darkblack-500 dark:border-darkblack-400 text-base border border-bgray-300 h-14 w-full focus:border-success-300 focus:ring-0 rounded-lg px-4 py-3.5 placeholder:text-bgray-500 placeholder:text-base" placeholder="Email Address" required />
                        </div>

                        <div class="mb-6 relative">
                            <input type="password" name="password" class="text-bgray-800 dark:text-white dark:bg-darkblack-500 dark:border-darkblack-400 text-base border border-bgray-300 h-14 w-full focus:border-success-300 focus:ring-0 rounded-lg px-4 py-3.5 placeholder:text-bgray-500 placeholder:text-base" placeholder="Password" required />
                            <button type="button" class="absolute top-4 right-4 bottom-4">
                                <svg width="22" height="20" viewBox="0 0 22 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M2 1L20 19" stroke="#718096" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                    <path d="M9.58445 8.58704C9.20917 8.96205 8.99823 9.47079 8.99805 10.0013C8.99786 10.5319 9.20844 11.0408 9.58345 11.416C9.95847 11.7913 10.4672 12.0023 10.9977 12.0024C11.5283 12.0026 12.0372 11.7921 12.4125 11.417" stroke="#718096" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                    <path d="M8.363 3.36506C9.22042 3.11978 10.1082 2.9969 11 3.00006C15 3.00006 18.333 5.33306 21 10.0001C20.222 11.3611 19.388 12.5241 18.497 13.4881M16.357 15.3491C14.726 16.4491 12.942 17.0001 11 17.0001C7 17.0001 3.667 14.6671 1 10.0001C2.369 7.60506 3.913 5.82506 5.632 4.65906" stroke="#718096" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                            </button>
                        </div>

                        <div class="mb-6 relative">
                            <input type="password" name="password_confirmation" class="text-bgray-800 dark:text-white dark:bg-darkblack-500 dark:border-darkblack-400 text-base border border-bgray-300 h-14 w-full focus:border-success-300 focus:ring-0 rounded-lg px-4 py-3.5 placeholder:text-bgray-500 placeholder:text-base" placeholder="Ulangi Password" required />
                            <button type="button" class="absolute top-4 right-4 bottom-4">
                                <svg width="22" height="20" viewBox="0 0 22 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M2 1L20 19" stroke="#718096" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                    <path d="M9.58445 8.58704C9.20917 8.96205 8.99823 9.47079 8.99805 10.0013C8.99786 10.5319 9.20844 11.0408 9.58345 11.416C9.95847 11.7913 10.4672 12.0023 10.9977 12.0024C11.5283 12.0026 12.0372 11.7921 12.4125 11.417" stroke="#718096" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                    <path d="M8.363 3.36506C9.22042 3.11978 10.1082 2.9969 11 3.00006C15 3.00006 18.333 5.33306 21 10.0001C20.222 11.3611 19.388 12.5241 18.497 13.4881M16.357 15.3491C14.726 16.4491 12.942 17.0001 11 17.0001C7 17.0001 3.667 14.6671 1 10.0001C2.369 7.60506 3.913 5.82506 5.632 4.65906" stroke="#718096" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                            </button>
                        </div>

                        <div class="flex justify-between mb-7">
                            <div class="flex items-center gap-x-3">
                                <input type="checkbox" required class="w-5 h-5 focus:ring-transparent rounded-md border border-bgray-300 focus:accent-success-300 text-success-300 dark:bg-transparent dark:border-darkblack-400" name="terms" id="terms" />
                                <label for="terms" class="text-bgray-600 dark:text-bgray-50 text-base">
                                    Saya menyetujui <span class="text-bgray-900 dark:text-white font-bold">Syarat & Ketentuan</span> MyFinance.
                                </label>
                            </div>
                        </div>

                        <button type="submit" class="py-3.5 flex items-center justify-center text-white font-bold bg-success-300 hover:bg-success-400 transition-all rounded-lg w-full">
                            Buat Akun Sekarang
                        </button>
                    </form>
                    <p class="text-center text-bgray-900 dark:text-bgray-50 text-base font-medium pt-7">
                        Sudah punya akun?
                        <a href="{{ route('login') }}" class="font-semibold underline text-success-300">Masuk di sini</a>
                    </p>

                    <nav class="flex items-center justify-center flex-wrap gap-x-11 pt-24">
                        <a href="#" class="text-sm text-bgray-700 dark:text-bgray-50">Terms & Condition</a>
                        <a href="#" class="text-sm text-bgray-700 dark:text-bgray-50">Privacy Policy</a>
                        <a href="#" class="text-sm text-bgray-700 dark:text-bgray-50">Help</a>
                    </nav>

                    <p class="text-bgray-600 dark:text-darkblack-300 text-center text-sm mt-6">
                        &copy; {{ date('Y') }} MyFinance Personal App by Gusti Swandana. All Right Reserved.
                    </p>
                </div>
            </div>

            <div class="lg:w-1/2 lg:block hidden bg-[#F6FAFF] dark:bg-darkblack-600 p-20 relative">
                <ul>
                    <li class="absolute top-10 left-8">
                        <img src="{{ asset('assets/images/shapes/square.svg') }}" alt="" />
                    </li>
                    <li class="absolute right-12 top-14">
                        <img src="{{ asset('assets/images/shapes/vline.svg') }}" alt="" />
                    </li>
                    <li class="absolute bottom-7 left-8">
                        <img src="{{ asset('assets/images/shapes/dotted.svg') }}" alt="" />
                    </li>
                </ul>

                <div class="mb-10 flex justify-center">
                    <img src="{{ asset('assets/images/illustration/signup.svg') }}" alt="Ilustrasi" />
                </div>

                <div>
                    <div class="text-center max-w-lg px-1.5 m-auto">
                        <h3 class="text-bgray-900 dark:text-white font-semibold font-popins text-4xl mb-4">
                            Manajemen Keuangan Mudah
                        </h3>
                        <p class="text-bgray-600 dark:text-darkblack-300 text-sm font-medium leading-relaxed">
                            Aplikasi ini membantu Anda mencatat setiap pengeluaran kategori Makanan, Tabungan, dan Lain-lain.
                            <br>Daftar sekarang untuk mulai memonitor saldo Anda!
                        </p>
                    </div>
                </div>
            </div>

        </div>
    </section>

    <script src="{{ asset('assets/js/jquery-3.6.0.min.js') }}"></script>
    <script src="{{ asset('assets/js/main.js') }}"></script>
</body>
</html>
