@props(['user' => null])

{{-- Parent Portal Theme: #F5A623 (Primary Orange/Amber) --}}
<aside x-data="{
    collapsed: localStorage.getItem('parentSidebarCollapsed') === 'true',
    darkMode: localStorage.getItem('darkMode') === 'true',
    toggleCollapse() {
        this.collapsed = !this.collapsed;
        localStorage.setItem('parentSidebarCollapsed', this.collapsed);
        window.dispatchEvent(new Event('parent-sidebar-toggled'));
    },
    toggleDarkMode() {
        this.darkMode = !this.darkMode;
        localStorage.setItem('darkMode', this.darkMode);
        if (this.darkMode) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
    }
}"
:class="collapsed ? 'w-20' : 'w-64'"
class="fixed left-0 top-0 h-screen bg-white dark:bg-slate-900 flex flex-col transition-all duration-300 z-50 shadow-xl border-r border-gray-200 dark:border-slate-700">

    {{-- Logo Section --}}
    <div class="p-4 border-b border-gray-200 dark:border-slate-700">
        <div class="flex items-center" :class="collapsed ? 'justify-center' : 'justify-between'">
            <a href="{{ route('parent.dashboard') }}" class="flex-shrink-0 flex items-center justify-center">
                <img x-cloak x-show="!darkMode" src="{{ asset('images/logo_light.png') }}" alt="KidzTech Logo"
                     :class="collapsed ? 'w-10 h-10' : 'w-16 h-16'"
                     class="object-contain transition-all duration-300" onerror="this.style.display='none'">
                <img x-cloak x-show="darkMode" src="{{ asset('images/logo_dark.png') }}" alt="KidzTech Logo"
                     :class="collapsed ? 'w-10 h-10' : 'w-16 h-16'"
                     class="object-contain transition-all duration-300" onerror="this.style.display='none'">
                {{-- Fallback logo that shows immediately, hides once Alpine initializes --}}
                <img x-data x-init="$el.remove()" src="{{ asset('images/logo_light.png') }}" alt="KidzTech Logo"
                     class="w-16 h-16 object-contain" onerror="this.style.display='none'">
            </a>
            <button @click="toggleCollapse()" x-show="!collapsed"
                    class="p-2 rounded-lg hover:bg-gray-100 dark:hover:bg-slate-700 transition-colors text-gray-500 dark:text-slate-400">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 19l-7-7 7-7m8 14l-7-7 7-7"/>
                </svg>
            </button>
        </div>
        <div x-show="collapsed" class="flex justify-center mt-2">
            <button @click="toggleCollapse()" class="p-2 rounded-lg hover:bg-gray-100 dark:hover:bg-slate-700 transition-colors text-gray-500 dark:text-slate-400">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 5l7 7-7 7M5 5l7 7-7 7"/>
                </svg>
            </button>
        </div>
    </div>

    {{-- Navigation Links --}}
    <nav class="flex-1 overflow-y-auto py-4 px-3 space-y-1">
        <a href="{{ route('parent.dashboard') }}"
           class="flex items-center px-3 py-2.5 rounded-xl transition-all duration-200 group
                  {{ request()->routeIs('parent.dashboard') ? 'bg-gradient-to-r from-[#F5A623] to-[#F7B74A] text-white shadow-lg' : 'text-gray-700 dark:text-slate-300 hover:bg-gray-100 dark:hover:bg-slate-700/50' }}"
           :title="collapsed ? 'Dashboard' : ''">
            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
            </svg>
            <span x-show="!collapsed" x-transition class="ml-3 font-medium">Dashboard</span>
        </a>

        <a href="{{ route('parent.children.index') }}"
           class="flex items-center px-3 py-2.5 rounded-xl transition-all duration-200 group
                  {{ request()->routeIs('parent.children.*') ? 'bg-gradient-to-r from-[#F5A623] to-[#F7B74A] text-white shadow-lg' : 'text-gray-700 dark:text-slate-300 hover:bg-gray-100 dark:hover:bg-slate-700/50' }}"
           :title="collapsed ? 'My Children' : ''">
            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
            </svg>
            <span x-show="!collapsed" x-transition class="ml-3 font-medium">My Children</span>
        </a>

        <a href="{{ route('parent.reports.index') }}"
           class="flex items-center px-3 py-2.5 rounded-xl transition-all duration-200 group
                  {{ request()->routeIs('parent.reports.*') ? 'bg-gradient-to-r from-[#F5A623] to-[#F7B74A] text-white shadow-lg' : 'text-gray-700 dark:text-slate-300 hover:bg-gray-100 dark:hover:bg-slate-700/50' }}"
           :title="collapsed ? 'Progress Reports' : ''">
            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
            </svg>
            <span x-show="!collapsed" x-transition class="ml-3 font-medium">Progress Reports</span>
        </a>

        <a href="{{ route('parent.schedule.index') }}"
           class="flex items-center px-3 py-2.5 rounded-xl transition-all duration-200 group
                  {{ request()->routeIs('parent.schedule.*') ? 'bg-gradient-to-r from-[#F5A623] to-[#F7B74A] text-white shadow-lg' : 'text-gray-700 dark:text-slate-300 hover:bg-gray-100 dark:hover:bg-slate-700/50' }}"
           :title="collapsed ? 'Class Schedule' : ''">
            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
            </svg>
            <span x-show="!collapsed" x-transition class="ml-3 font-medium">Class Schedule</span>
        </a>

        <a href="{{ route('parent.performance.index') }}"
           class="flex items-center px-3 py-2.5 rounded-xl transition-all duration-200 group
                  {{ request()->routeIs('parent.performance.*') ? 'bg-gradient-to-r from-[#F5A623] to-[#F7B74A] text-white shadow-lg' : 'text-gray-700 dark:text-slate-300 hover:bg-gray-100 dark:hover:bg-slate-700/50' }}"
           :title="collapsed ? 'Performance' : ''">
            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
            </svg>
            <span x-show="!collapsed" x-transition class="ml-3 font-medium">Performance</span>
        </a>

        <a href="{{ route('parent.messages.index') }}"
           class="flex items-center px-3 py-2.5 rounded-xl transition-all duration-200 group
                  {{ request()->routeIs('parent.messages.*') ? 'bg-gradient-to-r from-[#F5A623] to-[#F7B74A] text-white shadow-lg' : 'text-gray-700 dark:text-slate-300 hover:bg-gray-100 dark:hover:bg-slate-700/50' }}"
           :title="collapsed ? 'Messages' : ''">
            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
            </svg>
            <span x-show="!collapsed" x-transition class="ml-3 font-medium">Messages</span>
        </a>

        <div class="my-4 border-t border-gray-200 dark:border-slate-700"></div>

        <a href="{{ route('parent.settings.index') }}"
           class="flex items-center px-3 py-2.5 rounded-xl transition-all duration-200 group
                  {{ request()->routeIs('parent.settings.*') ? 'bg-gradient-to-r from-[#F5A623] to-[#F7B74A] text-white shadow-lg' : 'text-gray-700 dark:text-slate-300 hover:bg-gray-100 dark:hover:bg-slate-700/50' }}"
           :title="collapsed ? 'Settings' : ''">
            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
            </svg>
            <span x-show="!collapsed" x-transition class="ml-3 font-medium">Settings</span>
        </a>
    </nav>

    {{-- Bottom Section --}}
    <div class="p-3 border-t border-gray-200 dark:border-slate-700">
        <button @click="toggleDarkMode()"
                class="w-full flex items-center px-3 py-2.5 rounded-xl text-gray-700 dark:text-slate-300 hover:bg-gray-100 dark:hover:bg-slate-700/50 transition-all duration-200 mb-2"
                :class="collapsed ? 'justify-center' : ''"
                :title="collapsed ? (darkMode ? 'Light Mode' : 'Dark Mode') : ''">
            <svg x-show="!darkMode" class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/>
            </svg>
            <svg x-show="darkMode" class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/>
            </svg>
            <span x-show="!collapsed" x-transition class="ml-3 font-medium" x-text="darkMode ? 'Light Mode' : 'Dark Mode'"></span>
        </button>

        <div class="flex items-center px-3 py-2.5 rounded-xl bg-gray-100 dark:bg-slate-700/30" :class="collapsed ? 'justify-center' : ''">
            <div class="w-9 h-9 rounded-full bg-gradient-to-r from-[#F5A623] to-[#F7B74A] flex items-center justify-center text-white font-semibold text-sm flex-shrink-0">
                {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
            </div>
            <div x-show="!collapsed" x-transition class="ml-3 flex-1 min-w-0">
                <p class="text-sm font-medium text-gray-900 dark:text-white truncate">{{ Auth::user()->name }}</p>
                <p class="text-xs text-gray-500 dark:text-slate-400 truncate">Parent</p>
            </div>
        </div>

        <form method="POST" action="{{ route('logout') }}" class="mt-2">
            @csrf
            <button type="submit"
                    class="w-full flex items-center px-3 py-2.5 rounded-xl text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-500/20 transition-all duration-200"
                    :class="collapsed ? 'justify-center' : ''"
                    :title="collapsed ? 'Logout' : ''">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                </svg>
                <span x-show="!collapsed" x-transition class="ml-3 font-medium">Logout</span>
            </button>
        </form>
    </div>
</aside>
