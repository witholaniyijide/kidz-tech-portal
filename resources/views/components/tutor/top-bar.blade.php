@props(['userName' => 'Tutor'])

<div class="bg-white/20 dark:bg-gray-900/30 backdrop-blur-xl border-b border-white/10 dark:border-gray-700/10 px-6 py-4">
    <div class="flex items-center justify-between gap-4">
        <!-- Left: Search Bar -->
        <div class="flex-1 max-w-md">
            <div class="relative">
                <input
                    type="text"
                    placeholder="Search students, reports..."
                    class="w-full px-4 py-2 pl-10 bg-white/30 dark:bg-gray-800/30 border border-white/10 dark:border-gray-700/10 rounded-xl text-gray-900 dark:text-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-purple-500 transition-all"
                >
                <svg class="w-5 h-5 absolute left-3 top-1/2 -translate-y-1/2 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
            </div>
        </div>

        <!-- Right: Actions -->
        <div class="flex items-center gap-4">
            <!-- Dark Mode Toggle -->
            <button class="p-2 rounded-xl bg-white/30 dark:bg-gray-800/30 hover:bg-white/40 dark:hover:bg-gray-800/40 transition-all" onclick="toggleDarkMode()">
                <svg class="w-5 h-5 text-gray-700 dark:text-gray-300 hidden dark:block" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/>
                </svg>
                <svg class="w-5 h-5 text-gray-700 dark:text-gray-300 block dark:hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/>
                </svg>
            </button>

            <!-- Notifications -->
            <button class="relative p-2 rounded-xl bg-white/30 dark:bg-gray-800/30 hover:bg-white/40 dark:hover:bg-gray-800/40 transition-all">
                <svg class="w-5 h-5 text-gray-700 dark:text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                </svg>
                <!-- Notification Badge -->
                <span class="absolute -top-1 -right-1 w-5 h-5 bg-gradient-to-r from-purple-500 to-pink-500 text-white text-xs font-bold rounded-full flex items-center justify-center">3</span>
            </button>

            <!-- Profile Dropdown -->
            <div class="relative">
                <button class="flex items-center gap-3 px-3 py-2 rounded-xl bg-white/30 dark:bg-gray-800/30 hover:bg-white/40 dark:hover:bg-gray-800/40 transition-all">
                    <div class="w-8 h-8 rounded-full bg-gradient-to-r from-purple-500 to-pink-500 flex items-center justify-center text-white font-bold">
                        {{ substr($userName, 0, 1) }}
                    </div>
                    <div class="text-left hidden md:block">
                        <div class="text-sm font-semibold text-gray-900 dark:text-white">{{ $userName }}</div>
                        <div class="text-xs text-gray-600 dark:text-gray-400">Tutor</div>
                    </div>
                    <svg class="w-4 h-4 text-gray-600 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                    </svg>
                </button>
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
