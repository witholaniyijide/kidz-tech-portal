@props(['title' => 'Parent Portal', 'header' => null])

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="theme-color" content="#F5A623">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="default">

    <title>{{ config('app.name', 'Kidz Tech') }} - {{ $title }}</title>

    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('images/favicon.png') }}"
          onerror="this.href='data:image/svg+xml,%3Csvg xmlns=%22http://www.w3.org/2000/svg%22 viewBox=%220 0 100 100%22%3E%3Crect fill=%22%23F5A623%22 width=%22100%22 height=%22100%22 rx=%2220%22/%3E%3Ctext x=%2250%22 y=%2265%22 font-size=%2250%22 text-anchor=%22middle%22 fill=%22white%22 font-family=%22Arial, sans-serif%22 font-weight=%22bold%22%3EK%3C/text%3E%3C/svg%3E'">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800&display=swap" rel="stylesheet" />

    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        [x-cloak] { display: none !important; }

        /* ========================================
           PARENT PORTAL - ORANGE THEME
           Primary: #F5A623
           Light: #F7B74A
           Dark: #D4910C
        ======================================== */

        :root {
            --parent-primary: #F5A623;
            --parent-primary-light: #F7B74A;
            --parent-primary-dark: #D4910C;
            --safe-area-inset-bottom: env(safe-area-inset-bottom, 0px);
        }

        /* Primary Gradient - Orange */
        .bg-gradient-parent {
            background: linear-gradient(135deg, #F5A623 0%, #F7B74A 100%);
        }
        .bg-parent-gradient {
            background: linear-gradient(135deg, #F5A623 0%, #F7B74A 100%);
        }

        /* Button Gradient */
        .btn-parent-primary {
            background: linear-gradient(135deg, #F5A623 0%, #F7B74A 100%);
            color: white;
            transition: all 0.3s ease;
        }
        .btn-parent-primary:hover {
            opacity: 0.9;
            transform: translateY(-1px);
            box-shadow: 0 10px 25px -5px rgba(245, 166, 35, 0.4);
        }

        /* Custom Scrollbar - Orange Theme */
        ::-webkit-scrollbar {
            width: 6px;
            height: 6px;
        }
        ::-webkit-scrollbar-track {
            background: rgba(245, 166, 35, 0.1);
            border-radius: 10px;
        }
        ::-webkit-scrollbar-thumb {
            background: linear-gradient(180deg, #F5A623 0%, #F7B74A 100%);
            border-radius: 10px;
        }

        /* Glass Card */
        .glass-card {
            background: rgba(255, 255, 255, 0.7);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border: 1px solid rgba(255, 255, 255, 0.3);
        }
        .dark .glass-card {
            background: rgba(30, 41, 59, 0.7);
            border: 1px solid rgba(71, 85, 105, 0.3);
        }

        /* Hover Lift Effect */
        .hover-lift {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        .hover-lift:hover {
            transform: translateY(-4px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
        }

        /* Form Input Focus - Orange */
        input:focus, select:focus, textarea:focus {
            border-color: #F5A623 !important;
            box-shadow: 0 0 0 3px rgba(245, 166, 35, 0.1) !important;
        }

        /* Mobile Sidebar Overlay */
        .sidebar-overlay {
            transition: opacity 0.3s ease;
        }

        /* Mobile Sidebar Slide */
        .sidebar-mobile-enter {
            transform: translateX(-100%);
        }
        .sidebar-mobile-active {
            transform: translateX(0);
        }

        /* Safe area for iOS */
        .safe-bottom {
            padding-bottom: var(--safe-area-inset-bottom);
        }

        /* Mobile touch improvements */
        @media (max-width: 768px) {
            .touch-target {
                min-height: 44px;
                min-width: 44px;
            }

            /* Prevent zoom on input focus */
            input, select, textarea {
                font-size: 16px !important;
            }
        }

        /* Floating Animation */
        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-20px); }
        }
        .animate-float {
            animation: float 6s ease-in-out infinite;
        }
    </style>

    <script>
        // Dark mode initialization
        if (localStorage.getItem('darkMode') === 'true' || (!localStorage.getItem('darkMode') && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
    </script>

    @stack('styles')
</head>
<body class="h-full font-sans antialiased">
    <!-- Flash Messages -->
    <x-ui.flash-messages />

    <div x-data="{
        sidebarOpen: false,
        collapsed: window.innerWidth >= 768 ? localStorage.getItem('parentSidebarCollapsed') === 'true' : false,
        isMobile: window.innerWidth < 768,
        init() {
            this.handleResize();
            window.addEventListener('resize', () => this.handleResize());
            window.addEventListener('parent-sidebar-toggled', () => {
                if (!this.isMobile) {
                    this.collapsed = localStorage.getItem('parentSidebarCollapsed') === 'true';
                }
            });
        },
        handleResize() {
            this.isMobile = window.innerWidth < 768;
            if (!this.isMobile) {
                this.sidebarOpen = false;
            }
        },
        toggleSidebar() {
            if (this.isMobile) {
                this.sidebarOpen = !this.sidebarOpen;
            } else {
                this.collapsed = !this.collapsed;
                localStorage.setItem('parentSidebarCollapsed', this.collapsed);
                window.dispatchEvent(new Event('parent-sidebar-toggled'));
            }
        },
        closeSidebar() {
            if (this.isMobile) {
                this.sidebarOpen = false;
            }
        }
    }" class="flex h-screen overflow-hidden bg-gradient-to-br from-amber-50/50 via-orange-50/30 to-yellow-50/50 dark:from-slate-900 dark:via-slate-800 dark:to-slate-900">

        <!-- Mobile Sidebar Overlay -->
        <div x-show="sidebarOpen && isMobile"
             x-transition:enter="transition-opacity ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition-opacity ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             @click="closeSidebar()"
             class="fixed inset-0 z-40 bg-black/50 backdrop-blur-sm md:hidden"
             x-cloak></div>

        <!-- Sidebar -->
        <x-parent.sidebar />

        <!-- Main Content Area -->
        <div :class="{'md:ml-64': !collapsed && !isMobile, 'md:ml-20': collapsed && !isMobile}"
             class="flex-1 flex flex-col overflow-hidden transition-all duration-300 w-full">

            <!-- Top Bar -->
            <header class="sticky top-0 z-30 bg-white/80 dark:bg-gray-800/80 backdrop-blur-md border-b border-gray-200 dark:border-gray-700">
                <div class="flex items-center justify-between h-14 md:h-16 px-4 md:px-6">
                    <!-- Left: Hamburger & Title -->
                    <div class="flex items-center gap-3">
                        <!-- Mobile Hamburger Button -->
                        <button @click="toggleSidebar()"
                                class="p-2 -ml-2 rounded-xl text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors touch-target md:hidden">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                            </svg>
                        </button>

                        <!-- Desktop Collapse Button -->
                        <button @click="toggleSidebar()"
                                class="hidden md:flex p-2 rounded-xl text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                            <svg x-show="!collapsed" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 19l-7-7 7-7m8 14l-7-7 7-7"/>
                            </svg>
                            <svg x-show="collapsed" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 5l7 7-7 7M5 5l7 7-7 7"/>
                            </svg>
                        </button>

                        <!-- Page Title -->
                        <div class="min-w-0">
                            @if (isset($header))
                                <h1 class="text-base md:text-xl font-bold text-gray-900 dark:text-white truncate">{{ $header }}</h1>
                            @elseif (isset($title))
                                <h1 class="text-base md:text-xl font-bold text-gray-900 dark:text-white truncate">{{ $title }}</h1>
                            @else
                                <h1 class="text-base md:text-xl font-bold text-gray-900 dark:text-white">Parent Portal</h1>
                            @endif
                            <p class="text-xs md:text-sm text-gray-500 dark:text-gray-400 hidden sm:block">{{ now()->format('l, F j, Y') }}</p>
                        </div>
                    </div>

                    <!-- Right Side: Notifications, Profile -->
                    <div class="flex items-center gap-2 md:gap-4">
                        <!-- Notifications -->
                        <x-dropdown align="right" width="80">
                            <x-slot name="trigger">
                                <button class="relative p-2 rounded-xl text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors touch-target">
                                    <svg class="w-5 h-5 md:w-6 md:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                                    </svg>
                                    @php
                                        try {
                                            $unreadCount = \App\Models\ParentNotification::where('parent_id', auth()->id())->whereNull('read_at')->count();
                                        } catch (\Exception $e) {
                                            $unreadCount = 0;
                                        }
                                    @endphp
                                    @if($unreadCount > 0)
                                        <span class="absolute -top-1 -right-1 w-5 h-5 text-xs font-bold text-white bg-red-500 rounded-full flex items-center justify-center">
                                            {{ $unreadCount > 9 ? '9+' : $unreadCount }}
                                        </span>
                                    @endif
                                </button>
                            </x-slot>

                            <x-slot name="content">
                                <div class="max-h-[70vh] overflow-y-auto">
                                    <div class="px-4 py-3 text-sm font-semibold text-gray-700 dark:text-gray-300 bg-gray-50 dark:bg-gray-700 border-b dark:border-gray-600 sticky top-0">
                                        Notifications
                                    </div>
                                    @php
                                        try {
                                            $notifications = \App\Models\ParentNotification::where('parent_id', auth()->id())
                                                ->whereNull('read_at')
                                                ->orderBy('created_at', 'desc')
                                                ->take(10)
                                                ->get();
                                        } catch (\Exception $e) {
                                            $notifications = collect();
                                        }
                                    @endphp
                                    @forelse($notifications as $notification)
                                        <a href="{{ route('parent.notifications.index') }}" class="block px-4 py-4 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 border-b dark:border-gray-600 transition">
                                            <div class="font-semibold mb-1">{{ $notification->title ?? 'Notification' }}</div>
                                            <div class="text-xs text-gray-500 dark:text-gray-400">{{ $notification->created_at->diffForHumans() }}</div>
                                        </a>
                                    @empty
                                        <div class="px-6 py-8 text-sm text-gray-500 dark:text-gray-400 text-center">
                                            <svg class="w-12 h-12 mx-auto mb-3 text-gray-300 dark:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                                            </svg>
                                            <div class="font-medium">No new notifications</div>
                                        </div>
                                    @endforelse
                                </div>
                            </x-slot>
                        </x-dropdown>

                        <!-- Profile Dropdown -->
                        <div x-data="{ open: false }" class="relative">
                            <button
                                @click="open = !open"
                                class="flex items-center gap-2 md:gap-3 p-1.5 md:px-3 md:py-2 rounded-xl bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 transition-all touch-target"
                            >
                                <div class="w-8 h-8 md:w-9 md:h-9 rounded-full bg-gradient-to-br from-[#F5A623] to-[#F7B74A] flex items-center justify-center text-white font-bold text-sm shadow-lg">
                                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                                </div>
                                <div class="text-left hidden md:block">
                                    <div class="text-sm font-semibold text-gray-900 dark:text-white">{{ Auth::user()->name }}</div>
                                    <div class="text-xs text-gray-500 dark:text-gray-400">Parent</div>
                                </div>
                                <svg class="w-4 h-4 text-gray-500 dark:text-gray-400 hidden md:block" :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                </svg>
                            </button>

                            <!-- Dropdown Menu -->
                            <div
                                x-show="open"
                                x-cloak
                                @click.away="open = false"
                                x-transition:enter="transition ease-out duration-200"
                                x-transition:enter-start="opacity-0 scale-95"
                                x-transition:enter-end="opacity-100 scale-100"
                                x-transition:leave="transition ease-in duration-150"
                                x-transition:leave-start="opacity-100 scale-100"
                                x-transition:leave-end="opacity-0 scale-95"
                                class="absolute right-0 mt-2 w-56 bg-white dark:bg-gray-800 rounded-xl shadow-xl border border-gray-200 dark:border-gray-700 py-2 z-50"
                            >
                                <div class="px-4 py-3 border-b border-gray-200 dark:border-gray-700">
                                    <p class="text-sm font-semibold text-gray-900 dark:text-white">{{ Auth::user()->name }}</p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">Parent Account</p>
                                </div>

                                <a href="{{ route('parent.settings.index') }}" class="flex items-center gap-3 px-4 py-2.5 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    </svg>
                                    Settings
                                </a>

                                <div class="border-t border-gray-200 dark:border-gray-700 my-2"></div>

                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="flex items-center gap-3 w-full px-4 py-2.5 text-sm text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/20 transition-colors">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                                        </svg>
                                        Sign Out
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Main Content -->
            <main class="flex-1 overflow-y-auto relative">
                <!-- Floating Orbs Background - Hidden on mobile for performance -->
                <div class="hidden md:block">
                    <div class="absolute top-0 left-0 w-72 h-72 bg-amber-200 dark:bg-amber-900/50 rounded-full mix-blend-multiply dark:mix-blend-soft-light filter blur-xl opacity-60 animate-float pointer-events-none"></div>
                    <div class="absolute top-0 right-0 w-72 h-72 bg-orange-200 dark:bg-orange-900/50 rounded-full mix-blend-multiply dark:mix-blend-soft-light filter blur-xl opacity-60 animate-float pointer-events-none" style="animation-delay: 2s;"></div>
                    <div class="absolute -bottom-8 left-20 w-72 h-72 bg-yellow-200 dark:bg-yellow-900/50 rounded-full mix-blend-multiply dark:mix-blend-soft-light filter blur-xl opacity-60 animate-float pointer-events-none" style="animation-delay: 4s;"></div>
                </div>

                <!-- Page Content -->
                <div class="relative z-10 p-4 md:p-6">
                    {{ $slot }}
                </div>

                <!-- Footer -->
                <footer class="relative z-10 py-4 px-4 md:px-6 border-t border-gray-200 dark:border-gray-700 bg-white/50 dark:bg-gray-800/50 backdrop-blur-sm safe-bottom">
                    <div class="text-center text-xs md:text-sm text-gray-500 dark:text-gray-400">
                        &copy; {{ date('Y') }} Kidz Tech Coding Club
                    </div>
                </footer>
            </main>
        </div>
    </div>

    @stack('scripts')
</body>
</html>
