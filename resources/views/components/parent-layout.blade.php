@props(['title' => 'Parent Portal', 'header' => null])

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="theme-color" content="#F5A623">

    <title>{{ config('app.name', 'Kidz Tech') }} - {{ $title }}</title>

    <!-- PWA Meta Tags -->
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="default">
    <meta name="apple-mobile-web-app-title" content="KidzTech">
    <link rel="manifest" href="{{ asset('manifest.json') }}">
    <link rel="apple-touch-icon" href="{{ asset('images/icons/icon-192x192.png') }}">

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

        :root {
            --parent-primary: #F5A623;
            --parent-primary-light: #F7B74A;
            --parent-primary-dark: #D4910C;
        }

        .bg-gradient-parent {
            background: linear-gradient(135deg, #F5A623 0%, #F7B74A 100%);
        }

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

        .bg-parent-gradient {
            background: linear-gradient(135deg, #F5A623 0%, #F7B74A 100%);
        }

        ::-webkit-scrollbar { width: 6px; height: 6px; }
        ::-webkit-scrollbar-track { background: rgba(245, 166, 35, 0.1); border-radius: 10px; }
        ::-webkit-scrollbar-thumb { background: linear-gradient(180deg, #F5A623 0%, #F7B74A 100%); border-radius: 10px; }

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

        input:focus, select:focus, textarea:focus {
            border-color: #F5A623 !important;
            box-shadow: 0 0 0 3px rgba(245, 166, 35, 0.1) !important;
        }

        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-20px); }
        }
        .animate-float { animation: float 6s ease-in-out infinite; }
    </style>

    <script>
        if (localStorage.getItem('darkMode') === 'true' || (!localStorage.getItem('darkMode') && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
    </script>

    <!-- Alpine Store for Mobile Menu -->
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.store('mobileMenu', { open: false });
        });
    </script>

    @stack('styles')
</head>
<body class="h-full font-sans antialiased">
    <x-ui.flash-messages />

    <div class="flex h-screen overflow-hidden bg-gradient-to-br from-amber-50/50 via-orange-50/30 to-yellow-50/50 dark:from-slate-900 dark:via-slate-800 dark:to-slate-900">
        <!-- Sidebar -->
        <x-parent.sidebar />

        <!-- Mobile Overlay -->
        <div x-show="$store.mobileMenu.open"
             x-cloak
             @click="$store.mobileMenu.open = false"
             x-transition:enter="transition-opacity ease-linear duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition-opacity ease-linear duration-300"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="fixed inset-0 bg-gray-900/50 backdrop-blur-sm z-40 md:hidden"></div>

        <!-- Main Content Area -->
        <div x-data="{ collapsed: localStorage.getItem('parentSidebarCollapsed') === 'true' }"
             x-init="window.addEventListener('parent-sidebar-toggled', () => { collapsed = localStorage.getItem('parentSidebarCollapsed') === 'true'; })"
             :class="collapsed ? 'md:ml-20' : 'md:ml-64'"
             class="flex-1 flex flex-col overflow-hidden transition-all duration-300 w-full">

            <!-- Top Bar -->
            <header class="sticky top-0 z-30 bg-white/80 dark:bg-gray-800/80 backdrop-blur-md border-b border-gray-200 dark:border-gray-700 safe-area-top">
                <div class="flex items-center justify-between h-14 md:h-16 px-3 md:px-6">
                    <!-- Left: Hamburger + Title -->
                    <div class="flex items-center gap-3 flex-1 min-w-0">
                        <!-- Hamburger Menu Button (Mobile Only) -->
                        <button @click="$store.mobileMenu.open = !$store.mobileMenu.open"
                                class="md:hidden p-2 -ml-2 rounded-lg text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors flex-shrink-0">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                            </svg>
                        </button>

                        <div class="min-w-0 flex-1">
                            @if (isset($header))
                                <h1 class="text-lg md:text-xl font-bold text-gray-900 dark:text-white truncate">{{ $header }}</h1>
                            @elseif (isset($title))
                                <h1 class="text-lg md:text-xl font-bold text-gray-900 dark:text-white truncate">{{ $title }}</h1>
                            @else
                                <h1 class="text-lg md:text-xl font-bold text-gray-900 dark:text-white">Parent Portal</h1>
                            @endif
                            <p class="text-xs md:text-sm text-gray-500 dark:text-gray-400 hidden sm:block">{{ now()->format('l, F j, Y') }}</p>
                        </div>
                    </div>

                    <div class="flex items-center space-x-2 md:space-x-4">
                        <!-- Notifications -->
                        <x-dropdown align="right" width="96">
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
                                        <span class="absolute top-0 right-0 w-4 h-4 md:w-5 md:h-5 text-xs font-bold text-white bg-red-500 rounded-full flex items-center justify-center animate-pulse">
                                            {{ $unreadCount > 9 ? '9+' : $unreadCount }}
                                        </span>
                                    @endif
                                </button>
                            </x-slot>

                            <x-slot name="content">
                                <div class="w-80 md:w-96 max-h-[70vh] md:max-h-[32rem] overflow-y-auto">
                                    <div class="px-4 py-3 text-sm font-semibold text-gray-700 dark:text-gray-300 bg-gray-50 dark:bg-gray-700 border-b dark:border-gray-600 sticky top-0 flex items-center justify-between">
                                        <span>Notifications</span>
                                        @if($unreadCount > 0)
                                            <form action="{{ route('parent.notifications.mark-all-read') }}" method="POST" class="inline">
                                                @csrf
                                                <button type="submit" class="text-xs text-sky-500 hover:text-sky-600 font-medium">
                                                    Mark all read
                                                </button>
                                            </form>
                                        @endif
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
                                        <div class="flex items-start gap-2 px-4 py-4 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 border-b dark:border-gray-600 transition">
                                            <a href="{{ route('parent.notifications.index') }}" class="flex-1 min-w-0">
                                                <div class="font-semibold mb-1">{{ $notification->title ?? $notification->data['title'] ?? 'Notification' }}</div>
                                                <div class="text-xs text-gray-600 dark:text-gray-400 mb-1">{{ \Illuminate\Support\Str::limit($notification->message ?? $notification->data['body'] ?? '', 80) }}</div>
                                                <div class="text-xs text-gray-500 dark:text-gray-400">{{ $notification->created_at->diffForHumans() }}</div>
                                            </a>
                                            <form action="{{ route('parent.notifications.mark-read', $notification) }}" method="POST" class="flex-shrink-0">
                                                @csrf
                                                <button type="submit" class="p-1.5 text-gray-400 hover:text-sky-500 hover:bg-sky-500/10 rounded-lg transition" title="Mark as read">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                                    </svg>
                                                </button>
                                            </form>
                                        </div>
                                    @empty
                                        <div class="px-6 py-8 text-sm text-gray-500 dark:text-gray-400 text-center">
                                            <svg class="w-12 h-12 mx-auto mb-3 text-gray-300 dark:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                                            </svg>
                                            <div class="font-medium">No new notifications</div>
                                        </div>
                                    @endforelse
                                    @if($notifications->count() > 0)
                                        <a href="{{ route('parent.notifications.index') }}" class="block px-4 py-3 text-center text-sm font-medium text-sky-500 hover:bg-gray-50 dark:hover:bg-gray-700 border-t dark:border-gray-600 transition">
                                            View all notifications
                                        </a>
                                    @endif
                                </div>
                            </x-slot>
                        </x-dropdown>

                        <!-- Profile Dropdown -->
                        <div x-data="{ open: false }" class="relative">
                            <button @click="open = !open"
                                    class="flex items-center gap-2 md:gap-3 px-2 md:px-3 py-1.5 md:py-2 rounded-xl bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 transition-all touch-target">
                                <div class="w-8 h-8 md:w-9 md:h-9 rounded-full bg-gradient-to-br from-[#F5A623] to-[#F7B74A] flex items-center justify-center text-white font-bold text-sm shadow-lg flex-shrink-0">
                                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                                </div>
                                <div class="text-left hidden md:block">
                                    <div class="text-sm font-semibold text-gray-900 dark:text-white">{{ Auth::user()->name }}</div>
                                    <div class="text-xs text-gray-500 dark:text-gray-400">Parent</div>
                                </div>
                                <svg class="w-4 h-4 text-gray-500 dark:text-gray-400 transition-transform hidden md:block" :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                </svg>
                            </button>

                            <div x-show="open" x-cloak @click.away="open = false"
                                 x-transition class="absolute right-0 mt-2 w-56 bg-white dark:bg-gray-800 rounded-xl shadow-xl border border-gray-200 dark:border-gray-700 py-2 z-50">
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
                <!-- Floating Orbs Background - Orange Theme (hidden on mobile for performance) -->
                <div class="hidden md:block absolute top-0 left-0 w-72 h-72 bg-amber-200 dark:bg-amber-900/50 rounded-full mix-blend-multiply dark:mix-blend-soft-light filter blur-xl opacity-60 animate-float pointer-events-none"></div>
                <div class="hidden md:block absolute top-0 right-0 w-72 h-72 bg-orange-200 dark:bg-orange-900/50 rounded-full mix-blend-multiply dark:mix-blend-soft-light filter blur-xl opacity-60 animate-float pointer-events-none" style="animation-delay: 2s;"></div>
                <div class="hidden md:block absolute -bottom-8 left-20 w-72 h-72 bg-yellow-200 dark:bg-yellow-900/50 rounded-full mix-blend-multiply dark:mix-blend-soft-light filter blur-xl opacity-60 animate-float pointer-events-none" style="animation-delay: 4s;"></div>

                <div class="relative z-10 p-4 md:p-6">
                    {{ $slot }}
                </div>

                <footer class="relative z-10 py-4 px-4 md:px-6 border-t border-gray-200 dark:border-gray-700 bg-white/50 dark:bg-gray-800/50 backdrop-blur-sm safe-area-bottom">
                    <div class="text-center text-xs md:text-sm text-gray-500 dark:text-gray-400">
                        &copy; {{ date('Y') }} With <span class="text-red-500">&hearts;</span> Kidz Tech Coding Club. All rights reserved.
                    </div>
                </footer>
            </main>
        </div>
    </div>

    <!-- PWA Service Worker -->
    <script>
        if ('serviceWorker' in navigator) {
            window.addEventListener('load', () => {
                navigator.serviceWorker.register('/sw.js').catch(() => {});
            });
        }
    </script>

    @stack('scripts')
</body>
</html>
