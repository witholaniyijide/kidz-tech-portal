@props(['user' => null])

{{-- Admin Purple Theme: #423A8E (Primary) with Accent: #00CCCD (Teal) / Supporting: #FFC107, #DC3545, #198754, #0D6EFD --}}
<aside x-data="{
    collapsed: localStorage.getItem('adminSidebarCollapsed') === 'true',
    darkMode: localStorage.getItem('darkMode') !== null ? localStorage.getItem('darkMode') === 'true' : window.matchMedia('(prefers-color-scheme: dark)').matches,
    toggleCollapse() {
        this.collapsed = !this.collapsed;
        localStorage.setItem('adminSidebarCollapsed', this.collapsed);
        window.dispatchEvent(new Event('admin-sidebar-toggled'));
    },
    toggleDarkMode() {
        document.documentElement.classList.add('theme-transitioning');
        this.darkMode = !this.darkMode;
        localStorage.setItem('darkMode', this.darkMode);
        this.applyDarkMode();
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
x-show="$parent.mobileMenuOpen || window.innerWidth >= 768"
x-cloak
x-transition:enter="transition ease-out duration-300 transform md:transition-none"
x-transition:enter-start="-translate-x-full md:translate-x-0"
x-transition:enter-end="translate-x-0"
x-transition:leave="transition ease-in duration-200 transform md:transition-none"
x-transition:leave-start="translate-x-0 md:translate-x-0"
x-transition:leave-end="-translate-x-full md:translate-x-0"
:class="collapsed ? 'md:w-20' : 'md:w-64'"
class="fixed left-0 top-0 h-screen w-64 md:w-auto bg-white dark:bg-slate-900 flex flex-col transition-all duration-300 z-50 shadow-xl border-r border-gray-200 dark:border-slate-700">

    {{-- Logo Section with Toggle --}}
    <div class="p-4 border-b border-gray-200 dark:border-slate-700 safe-area-top">
        <div class="flex items-center justify-between">
            {{-- Logo --}}
            <a href="{{ route('admin.dashboard') }}" class="flex-shrink-0 flex items-center justify-center" x-cloak>
                {{-- Light Mode Logo --}}
                <img x-show="!darkMode"
                     src="{{ asset('images/logo_light.png') }}"
                     alt="KidzTech Logo"
                     class="w-12 h-12 md:w-14 md:h-14 object-contain transition-all duration-300"
                     onerror="this.style.display='none'">
                {{-- Dark Mode Logo --}}
                <img x-show="darkMode"
                     src="{{ asset('images/logo_dark.png') }}"
                     alt="KidzTech Logo"
                     class="w-12 h-12 md:w-14 md:h-14 object-contain transition-all duration-300"
                     onerror="this.style.display='none'">
            </a>

            {{-- Close button for mobile --}}
            <button @click="$parent.mobileMenuOpen = false"
                    class="md:hidden p-2 rounded-lg hover:bg-gray-100 dark:hover:bg-slate-700 transition-colors text-gray-500 dark:text-slate-400 hover:text-gray-700 dark:hover:text-white">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>

            {{-- Toggle Button (desktop only, only when expanded) --}}
            <button @click="toggleCollapse()"
                    x-show="!collapsed"
                    class="hidden md:block p-2 rounded-lg hover:bg-gray-100 dark:hover:bg-slate-700 transition-colors text-gray-500 dark:text-slate-400 hover:text-gray-700 dark:hover:text-white"
                    title="Collapse Sidebar">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 19l-7-7 7-7m8 14l-7-7 7-7"/>
                </svg>
            </button>
        </div>
        {{-- Expand button (centered below logo when collapsed) - desktop only --}}
        <div x-show="collapsed" class="hidden md:flex justify-center mt-2">
            <button @click="toggleCollapse()"
                    class="p-2 rounded-lg hover:bg-gray-100 dark:hover:bg-slate-700 transition-colors text-gray-500 dark:text-slate-400 hover:text-gray-700 dark:hover:text-white"
                    title="Expand Sidebar">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 5l7 7-7 7M5 5l7 7-7 7"/>
                </svg>
            </button>
        </div>
    </div>

    {{-- Navigation Links (10 Menu Items for Admin) --}}
    <nav class="flex-1 overflow-y-auto py-4 px-3 space-y-1">
        {{-- 1. Dashboard --}}
        <a href="{{ route('admin.dashboard') }}"
           class="flex items-center px-3 py-3 md:py-2.5 rounded-xl transition-all duration-200 group no-select
                  {{ request()->routeIs('admin.dashboard') ? 'bg-gradient-to-r from-[#423A8E] to-[#00CCCD] text-white shadow-lg' : 'text-gray-700 dark:text-slate-300 hover:bg-gray-100 dark:hover:bg-slate-700/50 hover:text-gray-900 dark:hover:text-white' }}"
           :title="collapsed ? 'Dashboard' : ''">
            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
            </svg>
            <span x-show="!collapsed" x-transition class="ml-3 font-medium">Dashboard</span>
        </a>

        {{-- 2. Students --}}
        <a href="{{ route('admin.students.index') }}"
           class="flex items-center px-3 py-3 md:py-2.5 rounded-xl transition-all duration-200 group no-select
                  {{ request()->routeIs('admin.students.*') ? 'bg-gradient-to-r from-[#423A8E] to-[#00CCCD] text-white shadow-lg' : 'text-gray-700 dark:text-slate-300 hover:bg-gray-100 dark:hover:bg-slate-700/50 hover:text-gray-900 dark:hover:text-white' }}"
           :title="collapsed ? 'Students' : ''">
            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z"/>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"/>
            </svg>
            <span x-show="!collapsed" x-transition class="ml-3 font-medium">Students</span>
        </a>

        {{-- 3. Tutors --}}
        <a href="{{ route('admin.tutors.index') }}"
           class="flex items-center px-3 py-3 md:py-2.5 rounded-xl transition-all duration-200 group no-select
                  {{ request()->routeIs('admin.tutors.*') ? 'bg-gradient-to-r from-[#423A8E] to-[#00CCCD] text-white shadow-lg' : 'text-gray-700 dark:text-slate-300 hover:bg-gray-100 dark:hover:bg-slate-700/50 hover:text-gray-900 dark:hover:text-white' }}"
           :title="collapsed ? 'Tutors' : ''">
            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
            </svg>
            <span x-show="!collapsed" x-transition class="ml-3 font-medium">Tutors</span>
        </a>

        {{-- 4. Schedules --}}
        <a href="{{ route('admin.schedules.index') }}"
           class="flex items-center px-3 py-3 md:py-2.5 rounded-xl transition-all duration-200 group no-select
                  {{ request()->routeIs('admin.schedules.*') ? 'bg-gradient-to-r from-[#423A8E] to-[#00CCCD] text-white shadow-lg' : 'text-gray-700 dark:text-slate-300 hover:bg-gray-100 dark:hover:bg-slate-700/50 hover:text-gray-900 dark:hover:text-white' }}"
           :title="collapsed ? 'Schedules' : ''">
            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
            </svg>
            <span x-show="!collapsed" x-transition class="ml-3 font-medium">Schedules</span>
        </a>

        {{-- 5. Attendance --}}
        <a href="{{ route('admin.attendance.index') }}"
           class="flex items-center px-3 py-3 md:py-2.5 rounded-xl transition-all duration-200 group no-select
                  {{ request()->routeIs('admin.attendance.*') ? 'bg-gradient-to-r from-[#423A8E] to-[#00CCCD] text-white shadow-lg' : 'text-gray-700 dark:text-slate-300 hover:bg-gray-100 dark:hover:bg-slate-700/50 hover:text-gray-900 dark:hover:text-white' }}"
           :title="collapsed ? 'Attendance' : ''">
            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
            </svg>
            <span x-show="!collapsed" x-transition class="ml-3 font-medium">Attendance</span>
        </a>

        {{-- 6. Reports --}}
        <a href="{{ route('admin.reports.index') }}"
           class="flex items-center px-3 py-3 md:py-2.5 rounded-xl transition-all duration-200 group no-select
                  {{ request()->routeIs('admin.reports.*') ? 'bg-gradient-to-r from-[#423A8E] to-[#00CCCD] text-white shadow-lg' : 'text-gray-700 dark:text-slate-300 hover:bg-gray-100 dark:hover:bg-slate-700/50 hover:text-gray-900 dark:hover:text-white' }}"
           :title="collapsed ? 'Reports' : ''">
            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
            </svg>
            <span x-show="!collapsed" x-transition class="ml-3 font-medium">Reports</span>
        </a>

        {{-- 7. Assessments --}}
        <a href="{{ route('admin.assessments.index') }}"
           class="flex items-center px-3 py-3 md:py-2.5 rounded-xl transition-all duration-200 group no-select
                  {{ request()->routeIs('admin.assessments.*') ? 'bg-gradient-to-r from-[#423A8E] to-[#00CCCD] text-white shadow-lg' : 'text-gray-700 dark:text-slate-300 hover:bg-gray-100 dark:hover:bg-slate-700/50 hover:text-gray-900 dark:hover:text-white' }}"
           :title="collapsed ? 'Assessments' : ''">
            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
            </svg>
            <span x-show="!collapsed" x-transition class="ml-3 font-medium">Assessments</span>
        </a>

        {{-- 8. Notices --}}
        <a href="{{ route('admin.notices.index') }}"
           class="flex items-center px-3 py-3 md:py-2.5 rounded-xl transition-all duration-200 group no-select
                  {{ request()->routeIs('admin.notices.*') ? 'bg-gradient-to-r from-[#423A8E] to-[#00CCCD] text-white shadow-lg' : 'text-gray-700 dark:text-slate-300 hover:bg-gray-100 dark:hover:bg-slate-700/50 hover:text-gray-900 dark:hover:text-white' }}"
           :title="collapsed ? 'Notices' : ''">
            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"/>
            </svg>
            <span x-show="!collapsed" x-transition class="ml-3 font-medium">Notices</span>
        </a>

        {{-- 9. Analytics --}}
        <a href="{{ route('admin.analytics.index') }}"
           class="flex items-center px-3 py-3 md:py-2.5 rounded-xl transition-all duration-200 group no-select
                  {{ request()->routeIs('admin.analytics.*') ? 'bg-gradient-to-r from-[#423A8E] to-[#00CCCD] text-white shadow-lg' : 'text-gray-700 dark:text-slate-300 hover:bg-gray-100 dark:hover:bg-slate-700/50 hover:text-gray-900 dark:hover:text-white' }}"
           :title="collapsed ? 'Analytics' : ''">
            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z"/>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z"/>
            </svg>
            <span x-show="!collapsed" x-transition class="ml-3 font-medium">Analytics</span>
        </a>

        {{-- Divider --}}
        <div class="my-4 border-t border-gray-200 dark:border-slate-700"></div>

        {{-- 10. Settings --}}
        <a href="{{ route('admin.settings.index') }}"
           class="flex items-center px-3 py-3 md:py-2.5 rounded-xl transition-all duration-200 group no-select
                  {{ request()->routeIs('admin.settings.*') ? 'bg-gradient-to-r from-[#423A8E] to-[#00CCCD] text-white shadow-lg' : 'text-gray-700 dark:text-slate-300 hover:bg-gray-100 dark:hover:bg-slate-700/50 hover:text-gray-900 dark:hover:text-white' }}"
           :title="collapsed ? 'Settings' : ''">
            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
            </svg>
            <span x-show="!collapsed" x-transition class="ml-3 font-medium">Settings</span>
        </a>
    </nav>

    {{-- Bottom Section: Dark Mode, User Profile & Logout --}}
    <div class="p-3 border-t border-gray-200 dark:border-slate-700 safe-area-bottom">
        {{-- Dark Mode Toggle --}}
        <button @click="toggleDarkMode()"
                class="w-full flex items-center px-3 py-3 md:py-2.5 rounded-xl text-gray-700 dark:text-slate-300 hover:bg-gray-100 dark:hover:bg-slate-700/50 hover:text-gray-900 dark:hover:text-white transition-all duration-200 mb-2"
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

        {{-- User Profile --}}
        <div class="flex items-center px-3 py-3 md:py-2.5 rounded-xl bg-gray-100 dark:bg-slate-700/30" :class="collapsed ? 'justify-center' : ''">
            <div class="w-9 h-9 rounded-full bg-gradient-to-r from-[#423A8E] to-[#00CCCD] flex items-center justify-center text-white font-semibold text-sm flex-shrink-0">
                {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
            </div>
            <div x-show="!collapsed" x-transition class="ml-3 flex-1 min-w-0">
                <p class="text-sm font-medium text-gray-900 dark:text-white truncate">{{ Auth::user()->name }}</p>
                <p class="text-xs text-gray-500 dark:text-slate-400 truncate">Admin</p>
            </div>
        </div>

        {{-- Logout --}}
        <form method="POST" action="{{ route('logout') }}" class="mt-2">
            @csrf
            <button type="submit"
                    class="w-full flex items-center px-3 py-3 md:py-2.5 rounded-xl text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-500/20 hover:text-red-700 dark:hover:text-red-300 transition-all duration-200"
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
