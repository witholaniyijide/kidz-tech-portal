@props(['title' => 'Tutor Portal', 'header' => null])

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=1024">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="theme-color" content="#4B49AC">

    <title>{{ config('app.name', 'Kidz Tech') }} - {{ $title }}</title>

    <!-- PWA Meta Tags -->
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="default">
    <meta name="apple-mobile-web-app-title" content="KidzTech">
    <link rel="manifest" href="{{ asset('manifest.json') }}">
    <link rel="apple-touch-icon" href="{{ asset('images/icons/icon-192x192.png') }}">

    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('images/favicon.png') }}">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800&display=swap" rel="stylesheet" />

    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        [x-cloak] { display: none !important; }
        :root { --tutor-primary: #4B49AC; }
        .bg-gradient-tutor { background: linear-gradient(135deg, #4B49AC 0%, #7978E9 100%); }
        ::-webkit-scrollbar { width: 6px; height: 6px; }
        ::-webkit-scrollbar-track { background: rgba(75, 73, 172, 0.1); border-radius: 10px; }
        ::-webkit-scrollbar-thumb { background: linear-gradient(180deg, #4B49AC 0%, #7978E9 100%); border-radius: 10px; }
        .glass-card { background: rgba(255, 255, 255, 0.7); backdrop-filter: blur(16px); border: 1px solid rgba(255, 255, 255, 0.3); }
        .dark .glass-card { background: rgba(30, 41, 59, 0.7); border: 1px solid rgba(71, 85, 105, 0.3); }
        input:focus, select:focus, textarea:focus { border-color: #4B49AC !important; box-shadow: 0 0 0 3px rgba(75, 73, 172, 0.1) !important; }
        @keyframes float { 0%, 100% { transform: translateY(0px); } 50% { transform: translateY(-20px); } }
        .animate-float { animation: float 6s ease-in-out infinite; }
    </style>

    <script>
        if (localStorage.getItem('darkMode') === 'true') { document.documentElement.classList.add('dark'); }
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

    <div class="flex h-screen overflow-hidden bg-gradient-to-br from-indigo-50/50 via-purple-50/30 to-blue-50/50 dark:from-slate-900 dark:via-slate-800 dark:to-slate-900">
        <x-tutor.sidebar />

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

        <div x-data="{ collapsed: localStorage.getItem('tutorSidebarCollapsed') === 'true' }"
             x-init="window.addEventListener('tutor-sidebar-toggled', () => { collapsed = localStorage.getItem('tutorSidebarCollapsed') === 'true'; })"
             :class="collapsed ? 'md:ml-20' : 'md:ml-64'"
             class="flex-1 flex flex-col overflow-hidden transition-all duration-300 w-full">

            <header class="sticky top-0 z-30 bg-white/80 dark:bg-gray-800/80 backdrop-blur-md border-b border-gray-200 dark:border-gray-700">
                <div class="flex items-center justify-between h-14 md:h-16 px-3 md:px-6">
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
                                <h1 class="text-base md:text-xl font-bold text-gray-900 dark:text-white truncate">{{ $header }}</h1>
                            @elseif (isset($title))
                                <h1 class="text-base md:text-xl font-bold text-gray-900 dark:text-white truncate">{{ $title }}</h1>
                            @else
                                <h1 class="text-base md:text-xl font-bold text-gray-900 dark:text-white truncate">Tutor Portal</h1>
                            @endif
                            <p class="text-xs md:text-sm text-gray-500 dark:text-gray-400 hidden md:block">{{ now()->format('l, F j, Y') }}</p>
                        </div>
                    </div>

                    <div class="flex items-center gap-2 md:gap-4">
                        <div class="relative hidden lg:block">
                            <input type="text" placeholder="Search..." class="w-64 pl-10 pr-4 py-2 rounded-xl border border-gray-200 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-white text-sm focus:ring-2 focus:ring-[#4B49AC]">
                            <svg class="w-5 h-5 absolute left-3 top-2.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                        </div>

                        <div x-data="{ notifOpen: false }" class="relative">
                            <button @click="notifOpen = !notifOpen" class="relative p-2 rounded-lg text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                                <svg class="w-5 h-5 md:w-6 md:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                                </svg>
                                @php
                                    $tutorUnreadCount = \App\Models\TutorNotification::where('tutor_id', Auth::user()->tutor?->id)->where('is_read', false)->count();
                                @endphp
                                @if($tutorUnreadCount > 0)
                                    <span class="absolute top-0 right-0 w-5 h-5 bg-red-500 text-white text-xs font-bold rounded-full flex items-center justify-center animate-pulse">
                                        {{ $tutorUnreadCount > 9 ? '9+' : $tutorUnreadCount }}
                                    </span>
                                @endif
                            </button>

                            {{-- Notifications Dropdown --}}
                            <div x-show="notifOpen" x-cloak @click.away="notifOpen = false" x-transition
                                 class="absolute right-0 mt-2 w-80 bg-white dark:bg-gray-800 rounded-xl shadow-xl border border-gray-200 dark:border-gray-700 z-50 overflow-hidden">
                                <div class="px-4 py-3 bg-gray-50 dark:bg-gray-700 border-b dark:border-gray-600 flex justify-between items-center">
                                    <span class="font-semibold text-gray-900 dark:text-white">Notifications</span>
                                    @if($tutorUnreadCount > 0)
                                        <span class="px-2 py-0.5 text-xs rounded-full bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400">{{ $tutorUnreadCount }} new</span>
                                    @endif
                                </div>
                                <div class="max-h-80 overflow-y-auto">
                                    @php
                                        $recentNotifications = \App\Models\TutorNotification::where('tutor_id', Auth::user()->tutor?->id)
                                            ->orderBy('created_at', 'desc')
                                            ->take(5)
                                            ->get();
                                    @endphp
                                    @forelse($recentNotifications as $notification)
                                        <a href="{{ $notification->meta['link'] ?? route('tutor.notifications.index') }}"
                                           class="block px-4 py-3 hover:bg-gray-50 dark:hover:bg-gray-700 border-b dark:border-gray-600 {{ !$notification->is_read ? 'bg-indigo-50 dark:bg-indigo-900/20' : '' }}">
                                            <div class="flex items-start gap-2">
                                                @if(!$notification->is_read)
                                                    <span class="w-2 h-2 mt-1.5 bg-[#4B49AC] rounded-full flex-shrink-0"></span>
                                                @endif
                                                <div class="flex-1 min-w-0">
                                                    <p class="text-sm font-medium text-gray-900 dark:text-white truncate">{{ $notification->title }}</p>
                                                    <p class="text-xs text-gray-600 dark:text-gray-400 truncate">{{ Str::limit($notification->body, 60) }}</p>
                                                    <p class="text-xs text-gray-500 dark:text-gray-500 mt-1">{{ $notification->created_at->diffForHumans() }}</p>
                                                </div>
                                            </div>
                                        </a>
                                    @empty
                                        <div class="px-4 py-8 text-center text-gray-500 dark:text-gray-400">
                                            <svg class="w-10 h-10 mx-auto mb-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                                            </svg>
                                            <p class="text-sm">No notifications</p>
                                        </div>
                                    @endforelse
                                </div>
                                <a href="{{ route('tutor.notifications.index') }}" class="block px-4 py-3 text-center text-sm text-[#4B49AC] hover:bg-gray-50 dark:hover:bg-gray-700 font-medium border-t dark:border-gray-600">
                                    View All Notifications
                                </a>
                            </div>
                        </div>

                        <div x-data="{ open: false }" class="relative">
                            <button @click="open = !open" class="flex items-center gap-2 md:gap-3 px-2 md:px-3 py-2 rounded-lg md:rounded-xl bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 transition-all">
                                <div class="w-8 h-8 md:w-9 md:h-9 rounded-full bg-gradient-to-br from-[#4B49AC] to-[#7978E9] flex items-center justify-center text-white font-bold text-xs md:text-sm shadow-lg flex-shrink-0">
                                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                                </div>
                                <div class="text-left hidden md:block">
                                    <div class="text-sm font-semibold text-gray-900 dark:text-white">{{ Auth::user()->name }}</div>
                                    <div class="text-xs text-gray-500 dark:text-gray-400">Tutor</div>
                                </div>
                            </button>
                            <div x-show="open" x-cloak @click.away="open = false" x-transition
                                 class="absolute right-0 mt-2 w-56 bg-white dark:bg-gray-800 rounded-xl shadow-xl border border-gray-200 dark:border-gray-700 py-2 z-50">
                                <a href="{{ route('tutor.profile.edit') }}" class="flex items-center gap-3 px-4 py-2.5 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                    My Profile
                                </a>
                                <div class="border-t border-gray-200 dark:border-gray-700 my-2"></div>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="flex items-center gap-3 w-full px-4 py-2.5 text-sm text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/20">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                                        Sign Out
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </header>

            <main class="flex-1 overflow-y-auto relative">
                <div class="absolute top-0 left-0 w-72 h-72 bg-indigo-200 dark:bg-indigo-900/50 rounded-full mix-blend-multiply dark:mix-blend-soft-light filter blur-xl opacity-60 animate-float pointer-events-none"></div>
                <div class="absolute top-0 right-0 w-72 h-72 bg-purple-200 dark:bg-purple-900/50 rounded-full mix-blend-multiply dark:mix-blend-soft-light filter blur-xl opacity-60 animate-float pointer-events-none" style="animation-delay: 2s;"></div>
                <div class="relative z-10 p-6">{{ $slot }}</div>
                <footer class="relative z-10 py-4 px-6 border-t border-gray-200 dark:border-gray-700 bg-white/50 dark:bg-gray-800/50 backdrop-blur-sm">
                    <div class="text-center text-sm text-gray-500 dark:text-gray-400">&copy; {{ date('Y') }} Kidz Tech Coding Club</div>
                </footer>
            </main>
        </div>
    </div>

    <script>
        if ('serviceWorker' in navigator) { window.addEventListener('load', () => { navigator.serviceWorker.register('/sw.js').catch(() => {}); }); }
    </script>
    @stack('scripts')
</body>
</html>
