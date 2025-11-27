<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0" />
    <meta http-equiv="X-UA-Compatible" content="ie=edge" />

    <title>Finance - Cygnus Dashboard</title>

    <link rel="stylesheet" href="{{ asset('assets/css/slick.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/aos.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/flatpickr.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/output.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}" />
</head>

<body>
    <div class="layout-wrapper active w-full">
        <div class="relative flex w-full">

            @include('partials.sidebar')

            <div class="body-wrapper flex flex-col flex-1 min-h-screen overflow-x-hidden bg-[#F5F5F5] dark:bg-darkblack-500 relative">

                @include('partials.header')

                <main class="w-full flex-grow px-6 pb-6 pt-[100px] sm:pt-[156px] xl:px-[48px] xl:pb-[48px]">
                    @yield('content')
                </main>

                <footer class="w-full px-6 pb-8 pt-4">
                    <div class="border-t border-bgray-200 dark:border-darkblack-400 pt-6 flex flex-col items-center justify-center gap-2">
                        <p class="text-sm font-medium text-bgray-500 dark:text-bgray-400">
                            © {{ date('Y') }} <span class="font-bold text-bgray-900 dark:text-white">MyFinance App Gusti Swandana</span>. All Rights Reserved.
                        </p>
                    </div>
                </footer>

            </div>
        </div>
    </div>

    <script src="{{ asset('assets/js/jquery-3.6.0.min.js') }}"></script>
    <script src="{{ asset('assets/js/flatpickr.js') }}"></script>
    <script>
        // Min Calender Config
        $("#min-calender").flatpickr({
            enableTime: true
            , dateFormat: "Y-m-d H:i"
            , inline: true
        , });

    </script>
    <script src="{{ asset('assets/js/slick.min.js') }}"></script>
    <script src="{{ asset('assets/js/aos.js') }}"></script>
    <script>
        AOS.init();

    </script>
    <script src="{{ asset('assets/js/main.js') }}"></script>
    <script src="{{ asset('assets/js/chart.js') }}"></script>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const drawerBtns = document.querySelectorAll('.drawer-btn');
            const sidebar = document.querySelector('.sidebar-wrapper');
            const overlay = document.querySelector('.aside-overlay');

            function toggleSidebar() {
                sidebar.classList.toggle('sm:hidden');
                sidebar.classList.toggle('block');
                overlay.classList.toggle('hidden');
            }

            drawerBtns.forEach(btn => {
                btn.addEventListener('click', (e) => {
                    e.preventDefault();
                    toggleSidebar();
                });
            });

            if (overlay) {
                overlay.addEventListener('click', () => {
                    sidebar.classList.add('sm:hidden');
                    sidebar.classList.remove('block');
                    overlay.classList.add('hidden');
                });
            }
        });

    </script>

    @stack('scripts')

</body>
</html>
