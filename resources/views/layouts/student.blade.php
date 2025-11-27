<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Kidz Tech') }} - Student Portal</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800,900|plus-jakarta-sans:400,500,600,700,800&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        [x-cloak] { display: none !important; }

        /* Custom Student/Parent Gradient (Sky Blue → Cyan) */
        .bg-gradient-student {
            background: linear-gradient(to right, #0ea5e9, #06b6d4);
        }

        /* Floating Animation */
        @keyframes float {
            0%, 100% {
                transform: translateY(0px);
            }
            50% {
                transform: translateY(-20px);
            }
        }

        .animate-float {
            animation: float 6s ease-in-out infinite;
        }

        /* Pulse Animation */
        @keyframes pulse-slow {
            0%, 100% {
                opacity: 1;
            }
            50% {
                opacity: 0.5;
            }
        }

        .animate-pulse-slow {
            animation: pulse-slow 3s cubic-bezier(0.4, 0, 0.6, 1) infinite;
        }

        /* Custom Scrollbar */
        ::-webkit-scrollbar {
            width: 8px;
            height: 8px;
        }

        ::-webkit-scrollbar-track {
            background: rgba(255, 255, 255, 0.1);
            border-radius: 10px;
        }

        ::-webkit-scrollbar-thumb {
            background: rgba(14, 165, 233, 0.5);
            border-radius: 10px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: rgba(14, 165, 233, 0.7);
        }

        /* Progress Circle */
        .progress-circle {
            transform: rotate(-90deg);
        }

        /* Dark mode check on load */
        @media (prefers-color-scheme: dark) {
            html:not(.light) {
                color-scheme: dark;
            }
        }

        /* Glass card hover effect */
        .glass-card {
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
        }
    </style>

    <script>
        // Dark mode initialization
        if (localStorage.getItem('theme') === 'dark' || (!localStorage.getItem('theme') && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
    </script>
</head>
<body class="h-full font-sans antialiased">
    <div class="flex h-screen overflow-hidden bg-gradient-to-br from-sky-50 via-cyan-50 to-blue-50 dark:from-gray-900 dark:via-gray-800 dark:to-gray-900">

        <!-- Sidebar Navigation -->
        <aside class="hidden lg:flex lg:flex-shrink-0 w-64" x-data="{ open: true }">
            <div class="flex flex-col w-64 border-r border-white/10 bg-white/30 dark:bg-gray-900/30 backdrop-blur-xl">
                <!-- Logo -->
                <div class="flex items-center justify-center h-16 px-4 bg-gradient-student">
                    <h1 class="text-xl font-bold text-white">KidzTech Portal</h1>
                </div>

                <!-- Navigation -->
                <nav class="flex-1 px-4 py-6 space-y-2 overflow-y-auto">
                    <a href="{{ route('student.dashboard') }}"
                       class="flex items-center px-4 py-3 text-sm font-medium rounded-xl transition-all duration-200 {{ request()->routeIs('student.dashboard') ? 'bg-sky-500 text-white shadow-lg' : 'text-gray-700 dark:text-gray-300 hover:bg-sky-100 dark:hover:bg-sky-900/30' }}">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                        </svg>
                        Dashboard
                    </a>

                    <a href="{{ route('student.progress.index') }}"
                       class="flex items-center px-4 py-3 text-sm font-medium rounded-xl transition-all duration-200 {{ request()->routeIs('student.progress.*') ? 'bg-sky-500 text-white shadow-lg' : 'text-gray-700 dark:text-gray-300 hover:bg-sky-100 dark:hover:bg-sky-900/30' }}">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                        </svg>
                        My Progress
                    </a>

                    <a href="{{ route('student.reports.index') }}"
                       class="flex items-center px-4 py-3 text-sm font-medium rounded-xl transition-all duration-200 {{ request()->routeIs('student.reports.*') ? 'bg-sky-500 text-white shadow-lg' : 'text-gray-700 dark:text-gray-300 hover:bg-sky-100 dark:hover:bg-sky-900/30' }}">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        Reports
                    </a>

                    <a href="{{ route('student.notifications.index') }}"
                       class="flex items-center px-4 py-3 text-sm font-medium rounded-xl transition-all duration-200 {{ request()->routeIs('student.notifications.*') ? 'bg-sky-500 text-white shadow-lg' : 'text-gray-700 dark:text-gray-300 hover:bg-sky-100 dark:hover:bg-sky-900/30' }}">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                        </svg>
                        Notifications
                        @if(isset($unreadNotifications) && $unreadNotifications > 0)
                            <span class="ml-auto inline-flex items-center justify-center w-5 h-5 text-xs font-bold text-white bg-red-500 rounded-full">
                                {{ $unreadNotifications }}
                            </span>
                        @endif
                    </a>
                </nav>

                <!-- User Profile -->
                <div class="p-4 border-t border-white/10">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-10 h-10 rounded-full bg-gradient-student flex items-center justify-center text-white font-bold">
                                {{ strtoupper(substr(auth()->user()->name ?? 'U', 0, 1)) }}
                            </div>
                        </div>
                        <div class="ml-3 flex-1">
                            <p class="text-sm font-medium text-gray-700 dark:text-gray-300">
                                {{ auth()->user()->name ?? 'User' }}
                            </p>
                            <p class="text-xs text-gray-500 dark:text-gray-400">
                                {{ auth()->user()->role ?? 'Student' }}
                            </p>
                        </div>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="p-2 text-gray-500 dark:text-gray-400 hover:text-red-500 dark:hover:text-red-400 transition-colors" title="Logout">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                                </svg>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </aside>

        <!-- Main Content Area -->
        <div class="flex-1 flex flex-col overflow-hidden">

            <!-- Top Bar (Mobile) -->
            <header class="lg:hidden bg-gradient-student shadow-lg" x-data="{ mobileMenuOpen: false }">
                <div class="flex items-center justify-between px-4 py-3">
                    <button @click="mobileMenuOpen = !mobileMenuOpen" class="text-white">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                        </svg>
                    </button>
                    <h1 class="text-lg font-bold text-white">KidzTech Portal</h1>
                    <a href="{{ route('student.notifications.index') }}" class="relative text-white">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                        </svg>
                        @if(isset($unreadNotifications) && $unreadNotifications > 0)
                            <span class="absolute -top-1 -right-1 inline-flex items-center justify-center w-4 h-4 text-xs font-bold text-white bg-red-500 rounded-full">
                                {{ $unreadNotifications }}
                            </span>
                        @endif
                    </a>
                </div>

                <!-- Mobile Menu -->
                <div x-show="mobileMenuOpen"
                     x-cloak
                     @click.away="mobileMenuOpen = false"
                     class="absolute top-14 left-0 right-0 bg-white dark:bg-gray-800 shadow-xl z-50 border-t border-gray-200 dark:border-gray-700">
                    <nav class="px-4 py-4 space-y-2">
                        <a href="{{ route('student.dashboard') }}"
                           class="flex items-center px-4 py-3 text-sm font-medium rounded-xl {{ request()->routeIs('student.dashboard') ? 'bg-sky-500 text-white' : 'text-gray-700 dark:text-gray-300 hover:bg-sky-100 dark:hover:bg-sky-900/30' }}">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                            </svg>
                            Dashboard
                        </a>
                        <a href="{{ route('student.progress.index') }}"
                           class="flex items-center px-4 py-3 text-sm font-medium rounded-xl {{ request()->routeIs('student.progress.*') ? 'bg-sky-500 text-white' : 'text-gray-700 dark:text-gray-300 hover:bg-sky-100 dark:hover:bg-sky-900/30' }}">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                            </svg>
                            My Progress
                        </a>
                        <a href="{{ route('student.reports.index') }}"
                           class="flex items-center px-4 py-3 text-sm font-medium rounded-xl {{ request()->routeIs('student.reports.*') ? 'bg-sky-500 text-white' : 'text-gray-700 dark:text-gray-300 hover:bg-sky-100 dark:hover:bg-sky-900/30' }}">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                            Reports
                        </a>
                        <a href="{{ route('student.notifications.index') }}"
                           class="flex items-center px-4 py-3 text-sm font-medium rounded-xl {{ request()->routeIs('student.notifications.*') ? 'bg-sky-500 text-white' : 'text-gray-700 dark:text-gray-300 hover:bg-sky-100 dark:hover:bg-sky-900/30' }}">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                            </svg>
                            Notifications
                        </a>
                        <form method="POST" action="{{ route('logout') }}" class="pt-4 border-t border-gray-200 dark:border-gray-700">
                            @csrf
                            <button type="submit" class="flex items-center w-full px-4 py-3 text-sm font-medium text-red-600 dark:text-red-400 rounded-xl hover:bg-red-50 dark:hover:bg-red-900/30">
                                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                                </svg>
                                Logout
                            </button>
                        </form>
                    </nav>
                </div>
            </header>

            <!-- Main Content -->
            <main class="flex-1 overflow-y-auto relative">
                <!-- Floating Orbs Background -->
                <div class="absolute top-0 left-0 w-72 h-72 bg-sky-300 dark:bg-sky-900 rounded-full mix-blend-multiply filter blur-xl opacity-70 animate-float pointer-events-none"></div>
                <div class="absolute top-0 right-0 w-72 h-72 bg-cyan-300 dark:bg-cyan-900 rounded-full mix-blend-multiply filter blur-xl opacity-70 animate-float pointer-events-none" style="animation-delay: 2s;"></div>
                <div class="absolute -bottom-8 left-20 w-72 h-72 bg-blue-300 dark:bg-blue-900 rounded-full mix-blend-multiply filter blur-xl opacity-70 animate-float pointer-events-none" style="animation-delay: 4s;"></div>

                <!-- Page Content -->
                <div class="relative z-10 p-4 md:p-6">
                    <!-- Flash Messages -->
                    @if(session('success'))
                        <div x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 5000)" class="mb-6 bg-green-100 dark:bg-green-900/30 border border-green-400 dark:border-green-700 text-green-700 dark:text-green-400 px-4 py-3 rounded-xl relative" role="alert">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    <span class="font-medium">{{ session('success') }}</span>
                                </div>
                                <button @click="show = false" class="text-green-700 dark:text-green-400 hover:text-green-900 dark:hover:text-green-300">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                    </svg>
                                </button>
                            </div>
                        </div>
                    @endif

                    @if(session('error'))
                        <div x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 5000)" class="mb-6 bg-red-100 dark:bg-red-900/30 border border-red-400 dark:border-red-700 text-red-700 dark:text-red-400 px-4 py-3 rounded-xl relative" role="alert">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    <span class="font-medium">{{ session('error') }}</span>
                                </div>
                                <button @click="show = false" class="text-red-700 dark:text-red-400 hover:text-red-900 dark:hover:text-red-300">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                    </svg>
                                </button>
                            </div>
                        </div>
                    @endif

                    @if(session('info'))
                        <div x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 5000)" class="mb-6 bg-blue-100 dark:bg-blue-900/30 border border-blue-400 dark:border-blue-700 text-blue-700 dark:text-blue-400 px-4 py-3 rounded-xl relative" role="alert">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    <span class="font-medium">{{ session('info') }}</span>
                                </div>
                                <button @click="show = false" class="text-blue-700 dark:text-blue-400 hover:text-blue-900 dark:hover:text-blue-300">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                    </svg>
                                </button>
                            </div>
                        </div>
                    @endif

                    @yield('content')
                </div>
            </main>
        </div>
    </div>
</body>
</html>
