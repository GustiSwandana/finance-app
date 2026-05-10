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
            const theme = storedTheme === 'light' || storedTheme === 'dark'
                ? storedTheme
                : (prefersDark ? 'dark' : 'light');
            const sidebarDesktopOpen = window.matchMedia('(min-width: 1280px)').matches
                && localStorage.getItem('finance.sidebar.desktopOpen') !== 'closed';

            document.documentElement.classList.toggle('dark', theme === 'dark');
            document.documentElement.classList.toggle('sidebar-desktop-open', sidebarDesktopOpen);
            document.documentElement.dataset.theme = theme;
        })();
    </script>

    <title>MyFinance - Personal Console</title>

    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&display=swap" rel="stylesheet" />

    <link rel="stylesheet" href="{{ asset('assets/css/slick.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/aos.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/flatpickr.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/output.css') }}?v={{ filemtime(public_path('assets/css/output.css')) }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}?v={{ filemtime(public_path('assets/css/style.css')) }}" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        :root {
            --app-surface: rgba(255, 255, 255, 0.88);
            --app-border: rgba(148, 163, 184, 0.18);
            --app-shadow: 0 20px 45px rgba(15, 23, 42, 0.08);
            --app-body-text: #0f172a;
        }

        html {
            color-scheme: light;
        }

        html.dark {
            color-scheme: dark;
            --app-surface: rgba(15, 23, 42, 0.78);
            --app-border: rgba(148, 163, 184, 0.14);
            --app-shadow: 0 24px 55px rgba(2, 6, 23, 0.34);
            --app-body-text: #e2e8f0;
        }

        html, body {
            font-family: 'Manrope', sans-serif;
            background:
                radial-gradient(circle at top left, rgba(15, 118, 110, 0.10), transparent 28%),
                radial-gradient(circle at top right, rgba(217, 119, 6, 0.10), transparent 24%),
                linear-gradient(180deg, #f7f5ef 0%, #f1efe8 100%);
            color: var(--app-body-text);
        }

        html.dark,
        html.dark body {
            background:
                radial-gradient(circle at top left, rgba(20, 184, 166, 0.14), transparent 24%),
                radial-gradient(circle at top right, rgba(59, 130, 246, 0.12), transparent 22%),
                linear-gradient(180deg, #020617 0%, #0f172a 100%);
        }

        body::before {
            content: "";
            position: fixed;
            inset: 0;
            pointer-events: none;
            background-image:
                linear-gradient(rgba(255,255,255,0.10) 1px, transparent 1px),
                linear-gradient(90deg, rgba(255,255,255,0.10) 1px, transparent 1px);
            background-size: 24px 24px;
            mask-image: linear-gradient(to bottom, rgba(0,0,0,0.20), transparent 70%);
            opacity: 0.35;
            z-index: 0;
        }

        html.dark body::before {
            opacity: 0.12;
        }

        .app-shell {
            position: relative;
            z-index: 1;
        }

        .app-panel {
            background: var(--app-surface);
            backdrop-filter: blur(18px);
            border: 1px solid var(--app-border);
            box-shadow: var(--app-shadow);
        }

        .app-hero {
            position: relative;
            overflow: hidden;
            border-radius: 28px;
            background:
                radial-gradient(circle at top right, rgba(255,255,255,0.22), transparent 28%),
                linear-gradient(135deg, #0f766e 0%, #155e75 42%, #1d4ed8 100%);
            color: white;
            box-shadow: 0 22px 60px rgba(14, 116, 144, 0.24);
        }

        .app-hero::after {
            content: "";
            position: absolute;
            inset: auto -8% -35% auto;
            width: 220px;
            height: 220px;
            border-radius: 999px;
            background: rgba(255,255,255,0.10);
        }

        .app-eyebrow {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 8px 12px;
            border-radius: 999px;
            background: rgba(255,255,255,0.14);
            color: rgba(255,255,255,0.9);
            font-size: 11px;
            font-weight: 800;
            letter-spacing: 0.12em;
            text-transform: uppercase;
        }

        .metric-card {
            position: relative;
            overflow: hidden;
            border-radius: 24px;
            background: rgba(255,255,255,0.84);
            border: 1px solid rgba(148, 163, 184, 0.16);
            box-shadow: var(--app-shadow);
            backdrop-filter: blur(16px);
        }

        .metric-card::before {
            content: "";
            position: absolute;
            inset: 0 auto auto 0;
            width: 100%;
            height: 4px;
            background: linear-gradient(90deg, rgba(15, 118, 110, 0.85), rgba(59, 130, 246, 0.78));
        }

        html.dark .metric-card {
            background: rgba(15, 23, 42, 0.88);
            border-color: rgba(148, 163, 184, 0.16);
        }

        .theme-toggle {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            border-radius: 18px;
            border: 1px solid rgba(148, 163, 184, 0.18);
            background: rgba(255, 255, 255, 0.82);
            padding: 12px 14px;
            color: #334155;
            box-shadow: 0 10px 24px rgba(15, 23, 42, 0.08);
            transition: background-color 0.18s ease, color 0.18s ease, transform 0.18s ease, border-color 0.18s ease;
        }

        .theme-toggle:hover {
            transform: translateY(-1px);
        }

        html.dark .theme-toggle {
            background: rgba(15, 23, 42, 0.88);
            border-color: rgba(148, 163, 184, 0.14);
            color: #e2e8f0;
            box-shadow: 0 12px 28px rgba(2, 6, 23, 0.28);
        }

        @media (max-width: 767px) {
            main {
                padding-left: 1rem !important;
                padding-right: 1rem !important;
                padding-top: 6rem !important;
            }
        }
    </style>
</head>

<body>
    <div class="layout-wrapper w-full app-shell">
        <div class="relative flex w-full">

            @include('partials.sidebar')
            <div class="aside-overlay hidden" aria-hidden="true"></div>

            <div class="body-wrapper relative flex min-h-screen flex-1 flex-col overflow-x-hidden xl:ml-[308px]">

                @include('partials.header')

                <main class="w-full flex-grow px-6 pb-6 pt-[96px] sm:pt-[136px] xl:px-10 xl:pb-10 2xl:px-12">
                    @yield('content')
                </main>

                <footer class="w-full px-6 pb-8 pt-2 xl:px-10 2xl:px-12">
                    <div class="app-panel flex flex-col items-center justify-center gap-2 rounded-[22px] px-6 py-5">
                        <p class="text-center text-sm font-medium text-slate-500 dark:text-slate-400">
                            &copy; {{ date('Y') }} <span class="font-bold text-slate-950 dark:text-white">MyFinance App Gusti Swandana</span>. All Rights Reserved.
                        </p>
                        <p class="text-center text-xs text-slate-400 dark:text-slate-500">Dasbor keuangan pribadi untuk transaksi, laporan, dan manajemen akses.</p>
                    </div>
                </footer>

            </div>
        </div>
    </div>

    <script src="{{ asset('assets/js/jquery-3.6.0.min.js') }}"></script>
    <script src="{{ asset('assets/js/flatpickr.js') }}"></script>
    <script>
        $("#min-calender").flatpickr({
            enableTime: true,
            dateFormat: "Y-m-d H:i",
            inline: true,
        });
    </script>
    <script src="{{ asset('assets/js/slick.min.js') }}"></script>
    <script src="{{ asset('assets/js/aos.js') }}"></script>
    <script>
        AOS.init();
    </script>
    <script src="{{ asset('assets/js/main.js') }}?v={{ filemtime(public_path('assets/js/main.js')) }}"></script>
    <script src="{{ asset('assets/js/chart.js') }}"></script>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const drawerBtns = document.querySelectorAll('.drawer-btn');
            const layout = document.querySelector('.layout-wrapper');
            const sidebar = document.querySelector('.sidebar-wrapper');
            const sidebarBody = document.querySelector('.sidebar-body');
            const overlay = document.querySelector('.aside-overlay');

            if (!layout || !sidebar || !overlay) {
                return;
            }

            const desktopQuery = window.matchMedia('(min-width: 1280px)');
            const sidebarStorageKey = 'finance.sidebar.desktopOpen';
            const sidebarScrollStorageKey = 'finance.sidebar.scrollTop';

            function isDesktop() {
                return desktopQuery.matches;
            }

            function getStoredDesktopSidebarState() {
                return localStorage.getItem(sidebarStorageKey) !== 'closed';
            }

            function syncRootSidebarClass(isOpen) {
                document.documentElement.classList.toggle('sidebar-desktop-open', isDesktop() && isOpen);
            }

            function storeDesktopSidebarState(isOpen) {
                if (isDesktop()) {
                    localStorage.setItem(sidebarStorageKey, isOpen ? 'open' : 'closed');
                }
            }

            function storeSidebarScroll() {
                if (sidebarBody) {
                    localStorage.setItem(sidebarScrollStorageKey, String(sidebarBody.scrollTop));
                }
            }

            function restoreSidebarScroll() {
                if (!sidebarBody) {
                    return;
                }

                const storedScroll = Number(localStorage.getItem(sidebarScrollStorageKey) || 0);
                if (Number.isFinite(storedScroll) && storedScroll > 0) {
                    sidebarBody.scrollTop = storedScroll;
                    requestAnimationFrame(() => {
                        sidebarBody.scrollTop = storedScroll;
                    });
                }
            }

            function openSidebar(shouldPersist = false) {
                layout.classList.add('active');
                overlay.classList.toggle('hidden', isDesktop());
                syncRootSidebarClass(true);

                if (shouldPersist) {
                    storeDesktopSidebarState(true);
                }
            }

            function closeSidebar(shouldPersist = false) {
                layout.classList.remove('active');
                overlay.classList.add('hidden');
                syncRootSidebarClass(false);

                if (shouldPersist) {
                    storeDesktopSidebarState(false);
                }
            }

            function toggleSidebar() {
                if (layout.classList.contains('active')) {
                    closeSidebar(true);
                } else {
                    openSidebar(true);
                }
            }

            function syncSidebarForViewport() {
                if (isDesktop()) {
                    if (getStoredDesktopSidebarState()) {
                        openSidebar();
                    } else {
                        closeSidebar();
                    }
                } else {
                    closeSidebar();
                }
            }

            drawerBtns.forEach(btn => {
                btn.addEventListener('click', (e) => {
                    e.preventDefault();
                    toggleSidebar();
                });
            });

            overlay.addEventListener('click', closeSidebar);

            if (sidebarBody) {
                sidebarBody.addEventListener('scroll', storeSidebarScroll, { passive: true });
            }

            sidebar.querySelectorAll('a').forEach(link => {
                link.addEventListener('click', () => {
                    storeSidebarScroll();

                    if (!isDesktop()) {
                        closeSidebar();
                    } else {
                        storeDesktopSidebarState(layout.classList.contains('active'));
                    }
                });
            });

            document.addEventListener('keydown', (e) => {
                if (e.key === 'Escape' && layout.classList.contains('active') && !isDesktop()) {
                    closeSidebar();
                }

                if (e.ctrlKey && e.key.toLowerCase() === 'b') {
                    e.preventDefault();
                    toggleSidebar();
                }
            });

            if (desktopQuery.addEventListener) {
                desktopQuery.addEventListener('change', syncSidebarForViewport);
            } else {
                desktopQuery.addListener(syncSidebarForViewport);
            }

            syncSidebarForViewport();
            restoreSidebarScroll();
        });
    </script>

    @stack('scripts')

</body>
</html>
