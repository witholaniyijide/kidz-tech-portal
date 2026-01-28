@props(['user' => null])

{{-- Student Portal Theme: #F5A623 (Primary Orange/Amber) - Same as Parent for consistency --}}
<aside x-data="{
    collapsed: localStorage.getItem('studentSidebarCollapsed') === 'true',
    darkMode: localStorage.getItem('darkMode') !== null ? localStorage.getItem('darkMode') === 'true' : window.matchMedia('(prefers-color-scheme: dark)').matches,
    toggleCollapse() {
        this.collapsed = !this.collapsed;
        localStorage.setItem('studentSidebarCollapsed', this.collapsed);
        window.dispatchEvent(new Event('student-sidebar-toggled'));
    },
    toggleDarkMode() {
        // Add transitioning class for smooth animation
        document.documentElement.classList.add('theme-transitioning');

        this.darkMode = !this.darkMode;
        localStorage.setItem('darkMode', this.darkMode);
        this.applyDarkMode();

        // Remove transitioning class after animation completes
        setTimeout(() => {
            document.documentElement.classList.remove('theme-transitioning');
        }, 400);
    },
    applyDarkMode() {
        if (this.darkMode) {
            document.documentElement.classList.add('dark');
            document.body.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
            document.body.classList.remove('dark');
        }
    }
}"
x-init="applyDarkMode()"
:class="collapsed ? 'md:w-20' : 'md:w-64'"
class="fixed left-0 top-0 h-screen w-64 bg-white dark:bg-slate-900 flex flex-col transition-all duration-300 z-50 shadow-xl border-r border-gray-200 dark:border-slate-700">

    {{-- Logo Section with Toggle --}}
    <div class="p-4 border-b border-gray-200 dark:border-slate-700">
        <div class="flex items-center" :class="collapsed ? 'justify-center' : 'justify-between'">
            {{-- Logo --}}
            <a href="{{ route('student.dashboard') }}" class="flex-shrink-0 flex items-center justify-center" x-cloak>
                {{-- Light Mode Logo --}}
                <img x-show="!darkMode"
                     src="{{ asset('images/logo_light.png') }}"
                     alt="KidzTech Logo"
                     :class="collapsed ? 'w-10 h-10' : 'w-16 h-16'"
                     class="object-contain transition-all duration-300"
                     onerror="this.style.display='none'">
                {{-- Dark Mode Logo --}}
                <img x-show="darkMode"
                     src="{{ asset('images/logo_dark.png') }}"
                     alt="KidzTech Logo"
                     :class="collapsed ? 'w-10 h-10' : 'w-16 h-16'"
                     class="object-contain transition-all duration-300"
                     onerror="this.style.display='none'">
            </a>

            {{-- Toggle Button (only when expanded) --}}
            <button @click="toggleCollapse()"
                    x-show="!collapsed || window.innerWidth < 768"
                    class="p-2 rounded-lg hover:bg-gray-100 dark:hover:bg-slate-700 transition-colors text-gray-500 dark:text-slate-400 hover:text-gray-700 dark:hover:text-white"
                    title="Collapse Sidebar">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 19l-7-7 7-7m8 14l-7-7 7-7"/>
                </svg>
            </button>
        </div>
        {{-- Expand button (centered below logo when collapsed) --}}
        <div x-show="collapsed" class="flex justify-center mt-2">
            <button @click="toggleCollapse()"
                    class="p-2 rounded-lg hover:bg-gray-100 dark:hover:bg-slate-700 transition-colors text-gray-500 dark:text-slate-400 hover:text-gray-700 dark:hover:text-white"
                    title="Expand Sidebar">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 5l7 7-7 7M5 5l7 7-7 7"/>
                </svg>
            </button>
        </div>
    </div>

    {{-- Navigation Links (6 Menu Items for Student) --}}
    <nav class="flex-1 overflow-y-auto py-4 px-3 space-y-1">
        {{-- 1. Dashboard --}}
        <a href="{{ route('student.dashboard') }}"
           class="flex items-center px-3 py-2.5 rounded-xl transition-all duration-200 group
                  {{ request()->routeIs('student.dashboard') ? 'bg-gradient-to-r from-[#F5A623] to-[#F7B74A] text-white shadow-lg' : 'text-gray-700 dark:text-slate-300 hover:bg-gray-100 dark:hover:bg-slate-700/50 hover:text-gray-900 dark:hover:text-white' }}"
           :title="collapsed ? 'Dashboard' : ''">
            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
            </svg>
            <span x-show="!collapsed || window.innerWidth < 768" x-transition class="ml-3 font-medium">Dashboard</span>
        </a>

        {{-- 2. My Reports --}}
        <a href="{{ route('student.reports.index') }}"
           class="flex items-center px-3 py-2.5 rounded-xl transition-all duration-200 group
                  {{ request()->routeIs('student.reports.*') ? 'bg-gradient-to-r from-[#F5A623] to-[#F7B74A] text-white shadow-lg' : 'text-gray-700 dark:text-slate-300 hover:bg-gray-100 dark:hover:bg-slate-700/50 hover:text-gray-900 dark:hover:text-white' }}"
           :title="collapsed ? 'My Reports' : ''">
            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
            </svg>
            <span x-show="!collapsed || window.innerWidth < 768" x-transition class="ml-3 font-medium">My Reports</span>
        </a>

        {{-- 3. My Attendance --}}
        <a href="{{ route('student.attendance.index') }}"
           class="flex items-center px-3 py-2.5 rounded-xl transition-all duration-200 group
                  {{ request()->routeIs('student.attendance.*') ? 'bg-gradient-to-r from-[#F5A623] to-[#F7B74A] text-white shadow-lg' : 'text-gray-700 dark:text-slate-300 hover:bg-gray-100 dark:hover:bg-slate-700/50 hover:text-gray-900 dark:hover:text-white' }}"
           :title="collapsed ? 'My Attendance' : ''">
            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
            </svg>
            <span x-show="!collapsed || window.innerWidth < 768" x-transition class="ml-3 font-medium">My Attendance</span>
        </a>

        {{-- 4. My Schedule --}}
        <a href="{{ route('student.schedule.index') }}"
           class="flex items-center px-3 py-2.5 rounded-xl transition-all duration-200 group
                  {{ request()->routeIs('student.schedule.*') ? 'bg-gradient-to-r from-[#F5A623] to-[#F7B74A] text-white shadow-lg' : 'text-gray-700 dark:text-slate-300 hover:bg-gray-100 dark:hover:bg-slate-700/50 hover:text-gray-900 dark:hover:text-white' }}"
           :title="collapsed ? 'My Schedule' : ''">
            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
            </svg>
            <span x-show="!collapsed || window.innerWidth < 768" x-transition class="ml-3 font-medium">My Schedule</span>
        </a>

        {{-- 5. Notices --}}
        <a href="{{ route('student.notices.index') }}"
           class="flex items-center px-3 py-2.5 rounded-xl transition-all duration-200 group
                  {{ request()->routeIs('student.notices.*') ? 'bg-gradient-to-r from-[#F5A623] to-[#F7B74A] text-white shadow-lg' : 'text-gray-700 dark:text-slate-300 hover:bg-gray-100 dark:hover:bg-slate-700/50 hover:text-gray-900 dark:hover:text-white' }}"
           :title="collapsed ? 'Notices' : ''">
            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"/>
            </svg>
            <span x-show="!collapsed || window.innerWidth < 768" x-transition class="ml-3 font-medium">Notices</span>
        </a>

        {{-- Divider --}}
        <div class="my-4 border-t border-gray-200 dark:border-slate-700"></div>

        {{-- 6. My Profile --}}
        <a href="{{ route('student.profile') }}"
           class="flex items-center px-3 py-2.5 rounded-xl transition-all duration-200 group
                  {{ request()->routeIs('student.profile*') ? 'bg-gradient-to-r from-[#F5A623] to-[#F7B74A] text-white shadow-lg' : 'text-gray-700 dark:text-slate-300 hover:bg-gray-100 dark:hover:bg-slate-700/50 hover:text-gray-900 dark:hover:text-white' }}"
           :title="collapsed ? 'My Profile' : ''">
            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
            </svg>
            <span x-show="!collapsed || window.innerWidth < 768" x-transition class="ml-3 font-medium">My Profile</span>
        </a>
    </nav>

    {{-- Bottom Section: Dark Mode, User Profile & Logout --}}
    <div class="p-3 border-t border-gray-200 dark:border-slate-700">
        {{-- Dark Mode Toggle --}}
        <button @click="toggleDarkMode()"
                class="w-full flex items-center px-3 py-2.5 rounded-xl text-gray-700 dark:text-slate-300 hover:bg-gray-100 dark:hover:bg-slate-700/50 hover:text-gray-900 dark:hover:text-white transition-all duration-200 mb-2"
                :class="collapsed ? 'justify-center' : ''"
                :title="collapsed ? (darkMode ? 'Light Mode' : 'Dark Mode') : ''">
            <svg x-show="!darkMode" class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/>
            </svg>
            <svg x-show="darkMode" class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/>
            </svg>
            <span x-show="!collapsed || window.innerWidth < 768" x-transition class="ml-3 font-medium" x-text="darkMode ? 'Light Mode' : 'Dark Mode'"></span>
        </button>

        {{-- User Profile --}}
        <div class="flex items-center px-3 py-2.5 rounded-xl bg-gray-100 dark:bg-slate-700/30" :class="collapsed ? 'justify-center' : ''">
            <div class="w-9 h-9 rounded-full bg-gradient-to-r from-[#F5A623] to-[#F7B74A] flex items-center justify-center text-white font-semibold text-sm flex-shrink-0">
                {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
            </div>
            <div x-show="!collapsed || window.innerWidth < 768" x-transition class="ml-3 flex-1 min-w-0">
                <p class="text-sm font-medium text-gray-900 dark:text-white truncate">{{ Auth::user()->name }}</p>
                <p class="text-xs text-gray-500 dark:text-slate-400 truncate">Student</p>
            </div>
        </div>

        {{-- Logout --}}
        <form method="POST" action="{{ route('logout') }}" class="mt-2">
            @csrf
            <button type="submit"
                    class="w-full flex items-center px-3 py-2.5 rounded-xl text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-500/20 hover:text-red-700 dark:hover:text-red-300 transition-all duration-200"
                    :class="collapsed ? 'justify-center' : ''"
                    :title="collapsed ? 'Logout' : ''">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                </svg>
                <span x-show="!collapsed || window.innerWidth < 768" x-transition class="ml-3 font-medium">Logout</span>
            </button>
        </form>
    </div>
</aside>
