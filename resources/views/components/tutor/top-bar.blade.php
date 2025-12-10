@props(['userName' => 'Tutor'])

<div class="bg-white/80 dark:bg-slate-800/80 backdrop-blur-xl border-b border-slate-200 dark:border-slate-700 px-6 py-4">
    <div class="flex items-center justify-between gap-4">
        <!-- Left: Mobile Menu Toggle + Search Bar -->
        <div class="flex items-center gap-4 flex-1">
            <!-- Mobile Menu Toggle -->
            <button 
                @click="sidebarOpen = !sidebarOpen"
                class="lg:hidden p-2 rounded-xl bg-slate-100 dark:bg-slate-700 hover:bg-slate-200 dark:hover:bg-slate-600 transition-all"
            >
                <svg class="w-5 h-5 text-slate-600 dark:text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                </svg>
            </button>

            <!-- Search Bar -->
            <div class="flex-1 max-w-md hidden sm:block">
                <div class="relative">
                    <input
                        type="text"
                        placeholder="Search students, reports..."
                        class="w-full px-4 py-2.5 pl-10 bg-slate-100 dark:bg-slate-700/50 border border-slate-200 dark:border-slate-600 rounded-xl text-slate-900 dark:text-white placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-[#4B51FF] focus:border-transparent transition-all"
                    >
                    <svg class="w-5 h-5 absolute left-3 top-1/2 -translate-y-1/2 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Right: Actions -->
        <div class="flex items-center gap-3">
            <!-- Dark Mode Toggle -->
            <button 
                onclick="toggleDarkMode()"
                class="p-2.5 rounded-xl bg-slate-100 dark:bg-slate-700 hover:bg-slate-200 dark:hover:bg-slate-600 transition-all"
                title="Toggle dark mode"
            >
                <svg class="w-5 h-5 text-amber-500 hidden dark:block" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/>
                </svg>
                <svg class="w-5 h-5 text-slate-600 block dark:hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/>
                </svg>
            </button>

            <!-- Notifications -->
            <a 
                href="{{ route('tutor.notices.index') }}"
                class="relative p-2.5 rounded-xl bg-slate-100 dark:bg-slate-700 hover:bg-slate-200 dark:hover:bg-slate-600 transition-all"
                title="Notifications"
            >
                <svg class="w-5 h-5 text-slate-600 dark:text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                </svg>
                <!-- Notification Badge -->
                @if(isset($unreadNotices) && $unreadNotices > 0)
                <span class="absolute -top-1 -right-1 w-5 h-5 bg-gradient-to-r from-[#4B51FF] to-[#22D3EE] text-white text-xs font-bold rounded-full flex items-center justify-center animate-pulse">
                    {{ $unreadNotices > 9 ? '9+' : $unreadNotices }}
                </span>
                @endif
            </a>

            <!-- Profile Dropdown -->
            <div x-data="{ open: false }" class="relative">
                <button 
                    @click="open = !open"
                    class="flex items-center gap-3 px-3 py-2 rounded-xl bg-slate-100 dark:bg-slate-700 hover:bg-slate-200 dark:hover:bg-slate-600 transition-all"
                >
                    <div class="w-9 h-9 rounded-full bg-gradient-to-br from-[#1D2A6D] to-[#4B51FF] flex items-center justify-center text-white font-bold text-sm shadow-lg">
                        {{ strtoupper(substr($userName, 0, 1)) }}
                    </div>
                    <div class="text-left hidden md:block">
                        <div class="text-sm font-semibold text-slate-900 dark:text-white">{{ $userName }}</div>
                        <div class="text-xs text-slate-500 dark:text-slate-400">Tutor</div>
                    </div>
                    <svg class="w-4 h-4 text-slate-500 dark:text-slate-400 transition-transform" :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
                    class="absolute right-0 mt-2 w-56 bg-white dark:bg-slate-800 rounded-xl shadow-xl border border-slate-200 dark:border-slate-700 py-2 z-50"
                >
                    <div class="px-4 py-3 border-b border-slate-200 dark:border-slate-700">
                        <p class="text-sm font-semibold text-slate-900 dark:text-white">{{ $userName }}</p>
                        <p class="text-xs text-slate-500 dark:text-slate-400">Tutor Account</p>
                    </div>
                    
                    <a href="{{ route('tutor.profile.edit') }}" class="flex items-center gap-3 px-4 py-2.5 text-sm text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-700 transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                        My Profile
                    </a>
                    
                    <a href="{{ route('tutor.availability.index') }}" class="flex items-center gap-3 px-4 py-2.5 text-sm text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-700 transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        My Availability
                    </a>
                    
                    <div class="border-t border-slate-200 dark:border-slate-700 mt-2 pt-2">
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="w-full flex items-center gap-3 px-4 py-2.5 text-sm text-rose-600 dark:text-rose-400 hover:bg-rose-50 dark:hover:bg-rose-900/20 transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                                </svg>
                                Logout
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function toggleDarkMode() {
        if (document.documentElement.classList.contains('dark')) {
            document.documentElement.classList.remove('dark');
            localStorage.setItem('theme', 'light');
        } else {
            document.documentElement.classList.add('dark');
            localStorage.setItem('theme', 'dark');
        }
    }
</script>
