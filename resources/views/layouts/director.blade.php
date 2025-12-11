<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ $title ?? config('app.name', 'Laravel') }} - Director Portal</title>

        <!-- Favicon -->
        <link rel="icon" type="image/png" href="{{ asset('images/favicon.png') }}"
              onerror="this.href='data:image/svg+xml,%3Csvg xmlns=%22http://www.w3.org/2000/svg%22 viewBox=%220 0 100 100%22%3E%3Crect fill=%22%234F46E5%22 width=%22100%22 height=%22100%22 rx=%2220%22/%3E%3Ctext x=%2250%22 y=%2265%22 font-size=%2250%22 text-anchor=%22middle%22 fill=%22white%22 font-family=%22Arial, sans-serif%22 font-weight=%22bold%22%3EK%3C/text%3E%3C/svg%3E'">

        <!-- Chart.js -->
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <!-- Dark Mode Initialization -->
        <script>
            if (localStorage.getItem('darkMode') === 'true') {
                document.documentElement.classList.add('dark');
            }
        </script>

        @stack('styles')
    </head>
    <body class="min-h-screen bg-gray-100 dark:bg-gray-900 font-sans antialiased">
        <!-- Flash Messages -->
        <x-ui.flash-messages />

        <div class="flex min-h-screen">
            <!-- Director Sidebar -->
            <x-director.sidebar />

            <!-- Main Content Area -->
            <div x-data="{ collapsed: localStorage.getItem('directorSidebarCollapsed') === 'true' }"
                 x-init="window.addEventListener('storage', () => { collapsed = localStorage.getItem('directorSidebarCollapsed') === 'true'; })"
                 :class="collapsed ? 'ml-20' : 'ml-64'"
                 class="flex-1 flex flex-col min-h-screen transition-all duration-300">

                <!-- Top Bar -->
                <header class="sticky top-0 z-40 bg-white/80 dark:bg-gray-800/80 backdrop-blur-md border-b border-gray-200 dark:border-gray-700">
                    <div class="flex items-center justify-between h-16 px-6">
                        <!-- Page Title / Breadcrumb -->
                        <div>
                            @if (isset($header))
                                <h1 class="text-xl font-bold text-gray-900 dark:text-white">{{ $header }}</h1>
                            @elseif (isset($title))
                                <h1 class="text-xl font-bold text-gray-900 dark:text-white">{{ $title }}</h1>
                            @endif
                            <p class="text-sm text-gray-500 dark:text-gray-400">{{ now()->format('l, F j, Y') }}</p>
                        </div>

                        <!-- Right Side: Search, Notifications -->
                        <div class="flex items-center space-x-4">
                            <!-- Search -->
                            <div class="hidden md:block relative">
                                <input type="text" placeholder="Search..."
                                       class="w-64 pl-10 pr-4 py-2 rounded-xl border border-gray-200 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-white text-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                <svg class="w-5 h-5 absolute left-3 top-2.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                </svg>
                            </div>

                            <!-- Notifications -->
                            <x-dropdown align="right" width="96">
                                <x-slot name="trigger">
                                    <button class="relative p-2 rounded-xl text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                                        </svg>
                                        @php
                                            try {
                                                $unreadCount = Auth::user()->unreadNotifications->count();
                                            } catch (\Exception $e) {
                                                $unreadCount = 0;
                                            }
                                        @endphp
                                        @if($unreadCount > 0)
                                            <span class="absolute top-0 right-0 w-5 h-5 text-xs font-bold text-white bg-red-500 rounded-full flex items-center justify-center animate-pulse">
                                                {{ $unreadCount }}
                                            </span>
                                        @endif
                                    </button>
                                </x-slot>

                                <x-slot name="content">
                                    <div class="w-96 max-h-[32rem] overflow-y-auto">
                                        <div class="px-4 py-3 text-sm font-semibold text-gray-700 dark:text-gray-300 bg-gray-50 dark:bg-gray-700 border-b dark:border-gray-600 sticky top-0">
                                            Notifications
                                        </div>
                                        @php
                                            try {
                                                $notifications = Auth::user()->unreadNotifications->take(10);
                                            } catch (\Exception $e) {
                                                $notifications = collect();
                                            }
                                        @endphp
                                        @forelse($notifications as $notification)
                                            <a href="#" class="block px-4 py-4 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 border-b dark:border-gray-600 transition">
                                                <div class="font-semibold mb-1">{{ $notification->data['title'] ?? 'Notification' }}</div>
                                                <div class="text-xs text-gray-500 dark:text-gray-400">{{ $notification->created_at->diffForHumans() }}</div>
                                            </a>
                                        @empty
                                            <div class="px-6 py-12 text-sm text-gray-500 dark:text-gray-400 text-center">
                                                <svg class="w-16 h-16 mx-auto mb-3 text-gray-300 dark:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
                                    class="flex items-center gap-3 px-3 py-2 rounded-xl bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 transition-all"
                                >
                                    <div class="w-9 h-9 rounded-full bg-gradient-to-br from-blue-600 to-purple-600 flex items-center justify-center text-white font-bold text-sm shadow-lg">
                                        {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                                    </div>
                                    <div class="text-left hidden md:block">
                                        <div class="text-sm font-semibold text-gray-900 dark:text-white">{{ Auth::user()->name }}</div>
                                        <div class="text-xs text-gray-500 dark:text-gray-400">Director</div>
                                    </div>
                                    <svg class="w-4 h-4 text-gray-500 dark:text-gray-400 transition-transform" :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                    </svg>
                                </button>

                                <!-- Dropdown Menu -->
                                <div
                                    x-show="open"
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
                                        <p class="text-xs text-gray-500 dark:text-gray-400">Director Account</p>
                                    </div>

                                    <a href="{{ route('profile.edit') }}" class="flex items-center gap-3 px-4 py-2.5 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                        </svg>
                                        My Profile
                                    </a>

                                    <a href="{{ route('director.settings.index') }}" class="flex items-center gap-3 px-4 py-2.5 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
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

                <!-- Page Content -->
                <main class="flex-1">
                    {{ $slot }}
                </main>

                <!-- Footer -->
                <footer class="py-4 px-6 border-t border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800">
                    <div class="text-center text-sm text-gray-500 dark:text-gray-400">
&copy; {{ date('Y') }} With <span class="text-red-500">❤</span> Kidz Tech Coding Club. All rights reserved.
                    </div>
                </footer>
            </div>
        </div>

        @stack('scripts')
    </body>
</html>
