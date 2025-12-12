@props(['user' => null])

<aside x-data="{
    collapsed: localStorage.getItem('directorSidebarCollapsed') === 'true',
    darkMode: localStorage.getItem('darkMode') === 'true',
    toggleCollapse() {
        this.collapsed = !this.collapsed;
        localStorage.setItem('directorSidebarCollapsed', this.collapsed);
        window.dispatchEvent(new Event('sidebar-toggled'));
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
x-init="if (darkMode) { document.documentElement.classList.add('dark'); }"
:class="collapsed ? 'w-20' : 'w-64'"
class="fixed left-0 top-0 h-screen bg-white dark:bg-slate-900 flex flex-col transition-all duration-300 z-50 shadow-xl border-r border-gray-200 dark:border-slate-700">

    {{-- Logo Section with Toggle --}}
    <div class="p-4 border-b border-gray-200 dark:border-slate-700">
        <div class="flex items-center" :class="collapsed ? 'justify-center' : 'justify-between'">
            {{-- Logo - Simple gradient "K" logo --}}
            <a href="{{ route('dashboard') }}" class="flex-shrink-0 flex items-center justify-center">
                <div :class="collapsed ? 'w-10 h-10' : 'w-16 h-16'"
                     class="rounded-xl bg-gradient-to-br from-blue-500 to-purple-600 flex items-center justify-center shadow-lg transition-all duration-300">
                    <span :class="collapsed ? 'text-lg' : 'text-3xl'" class="text-white font-bold">K</span>
                </div>
            </a>

            {{-- Toggle Button (only when expanded) --}}
            <button @click="toggleCollapse()"
                    x-show="!collapsed"
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

    {{-- Navigation Links --}}
    <nav class="flex-1 overflow-y-auto py-4 px-3 space-y-1">
        {{-- Dashboard --}}
        <a href="{{ route('dashboard') }}"
           class="flex items-center px-3 py-2.5 rounded-xl transition-all duration-200 group
                  {{ request()->routeIs('dashboard') ? 'bg-gradient-to-r from-blue-600 to-purple-600 text-white shadow-lg' : 'text-gray-700 dark:text-slate-300 hover:bg-gray-100 dark:hover:bg-slate-700/50 hover:text-gray-900 dark:hover:text-white' }}"
           :title="collapsed ? 'Dashboard' : ''">
            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
            </svg>
            <span x-show="!collapsed" x-transition class="ml-3 font-medium">Dashboard</span>
        </a>

        {{-- Students --}}
        <a href="{{ route('director.students.index') }}"
           class="flex items-center px-3 py-2.5 rounded-xl transition-all duration-200 group
                  {{ request()->routeIs('director.students.*') ? 'bg-gradient-to-r from-blue-600 to-purple-600 text-white shadow-lg' : 'text-gray-700 dark:text-slate-300 hover:bg-gray-100 dark:hover:bg-slate-700/50 hover:text-gray-900 dark:hover:text-white' }}"
           :title="collapsed ? 'Students' : ''">
            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z"/>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"/>
            </svg>
            <span x-show="!collapsed" x-transition class="ml-3 font-medium">Students</span>
        </a>

        {{-- Tutors --}}
        <a href="{{ route('director.tutors.index') }}"
           class="flex items-center px-3 py-2.5 rounded-xl transition-all duration-200 group
                  {{ request()->routeIs('director.tutors.*') ? 'bg-gradient-to-r from-blue-600 to-purple-600 text-white shadow-lg' : 'text-gray-700 dark:text-slate-300 hover:bg-gray-100 dark:hover:bg-slate-700/50 hover:text-gray-900 dark:hover:text-white' }}"
           :title="collapsed ? 'Tutors' : ''">
            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
            </svg>
            <span x-show="!collapsed" x-transition class="ml-3 font-medium">Tutors</span>
        </a>

        {{-- Attendance --}}
        <a href="{{ route('director.attendance.index') }}"
           class="flex items-center px-3 py-2.5 rounded-xl transition-all duration-200 group
                  {{ request()->routeIs('director.attendance.*') ? 'bg-gradient-to-r from-blue-600 to-purple-600 text-white shadow-lg' : 'text-gray-700 dark:text-slate-300 hover:bg-gray-100 dark:hover:bg-slate-700/50 hover:text-gray-900 dark:hover:text-white' }}"
           :title="collapsed ? 'Attendance' : ''">
            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
            </svg>
            <span x-show="!collapsed" x-transition class="ml-3 font-medium">Attendance</span>
        </a>

        {{-- Finance --}}
        <a href="{{ route('director.finance.index') }}"
           class="flex items-center px-3 py-2.5 rounded-xl transition-all duration-200 group
                  {{ request()->routeIs('director.finance.*') ? 'bg-gradient-to-r from-blue-600 to-purple-600 text-white shadow-lg' : 'text-gray-700 dark:text-slate-300 hover:bg-gray-100 dark:hover:bg-slate-700/50 hover:text-gray-900 dark:hover:text-white' }}"
           :title="collapsed ? 'Finance' : ''">
            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <span x-show="!collapsed" x-transition class="ml-3 font-medium">Finance</span>
        </a>

        {{-- Reports --}}
        <a href="{{ route('director.reports.index') }}"
           class="flex items-center px-3 py-2.5 rounded-xl transition-all duration-200 group
                  {{ request()->routeIs('director.reports.*') ? 'bg-gradient-to-r from-blue-600 to-purple-600 text-white shadow-lg' : 'text-gray-700 dark:text-slate-300 hover:bg-gray-100 dark:hover:bg-slate-700/50 hover:text-gray-900 dark:hover:text-white' }}"
           :title="collapsed ? 'Reports' : ''">
            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
            </svg>
            <span x-show="!collapsed" x-transition class="ml-3 font-medium">Reports</span>
        </a>

        {{-- Notices --}}
        <a href="{{ route('director.notices.index') }}"
           class="flex items-center px-3 py-2.5 rounded-xl transition-all duration-200 group
                  {{ request()->routeIs('director.notices.*') ? 'bg-gradient-to-r from-blue-600 to-purple-600 text-white shadow-lg' : 'text-gray-700 dark:text-slate-300 hover:bg-gray-100 dark:hover:bg-slate-700/50 hover:text-gray-900 dark:hover:text-white' }}"
           :title="collapsed ? 'Notices' : ''">
            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"/>
            </svg>
            <span x-show="!collapsed" x-transition class="ml-3 font-medium">Notices</span>
        </a>

        {{-- Messages --}}
        <a href="{{ route('director.messages.index') }}"
           class="flex items-center px-3 py-2.5 rounded-xl transition-all duration-200 group
                  {{ request()->routeIs('director.messages.*') ? 'bg-gradient-to-r from-blue-600 to-purple-600 text-white shadow-lg' : 'text-gray-700 dark:text-slate-300 hover:bg-gray-100 dark:hover:bg-slate-700/50 hover:text-gray-900 dark:hover:text-white' }}"
           :title="collapsed ? 'Messages' : ''">
            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/>
            </svg>
            <span x-show="!collapsed" x-transition class="ml-3 font-medium">Messages</span>
        </a>

        {{-- Assessments --}}
        <a href="{{ route('director.assessments.index') }}"
           class="flex items-center px-3 py-2.5 rounded-xl transition-all duration-200 group
                  {{ request()->routeIs('director.assessments.*') ? 'bg-gradient-to-r from-blue-600 to-purple-600 text-white shadow-lg' : 'text-gray-700 dark:text-slate-300 hover:bg-gray-100 dark:hover:bg-slate-700/50 hover:text-gray-900 dark:hover:text-white' }}"
           :title="collapsed ? 'Assessments' : ''">
            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
            </svg>
            <span x-show="!collapsed" x-transition class="ml-3 font-medium">Assessments</span>
        </a>

        {{-- Analytics --}}
        <a href="{{ route('director.analytics.index') }}"
           class="flex items-center px-3 py-2.5 rounded-xl transition-all duration-200 group
                  {{ request()->routeIs('director.analytics.*') ? 'bg-gradient-to-r from-blue-600 to-purple-600 text-white shadow-lg' : 'text-gray-700 dark:text-slate-300 hover:bg-gray-100 dark:hover:bg-slate-700/50 hover:text-gray-900 dark:hover:text-white' }}"
           :title="collapsed ? 'Analytics' : ''">
            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z"/>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z"/>
            </svg>
            <span x-show="!collapsed" x-transition class="ml-3 font-medium">Analytics</span>
        </a>

        {{-- Activity Logs --}}
        <a href="{{ route('director.activity-logs.index') }}"
           class="flex items-center px-3 py-2.5 rounded-xl transition-all duration-200 group
                  {{ request()->routeIs('director.activity-logs.*') ? 'bg-gradient-to-r from-blue-600 to-purple-600 text-white shadow-lg' : 'text-gray-700 dark:text-slate-300 hover:bg-gray-100 dark:hover:bg-slate-700/50 hover:text-gray-900 dark:hover:text-white' }}"
           :title="collapsed ? 'Activity Logs' : ''">
            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <span x-show="!collapsed" x-transition class="ml-3 font-medium">Activity Logs</span>
        </a>

        {{-- Divider --}}
        <div class="my-4 border-t border-gray-200 dark:border-slate-700"></div>

        {{-- Settings --}}
        <a href="{{ route('director.settings.index') }}"
           class="flex items-center px-3 py-2.5 rounded-xl transition-all duration-200 group
                  {{ request()->routeIs('director.settings.*') ? 'bg-gradient-to-r from-blue-600 to-purple-600 text-white shadow-lg' : 'text-gray-700 dark:text-slate-300 hover:bg-gray-100 dark:hover:bg-slate-700/50 hover:text-gray-900 dark:hover:text-white' }}"
           :title="collapsed ? 'Settings' : ''">
            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
            </svg>
            <span x-show="!collapsed" x-transition class="ml-3 font-medium">Settings</span>
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
            <span x-show="!collapsed" x-transition class="ml-3 font-medium" x-text="darkMode ? 'Light Mode' : 'Dark Mode'"></span>
        </button>

        {{-- User Profile --}}
        <div class="flex items-center px-3 py-2.5 rounded-xl bg-gray-100 dark:bg-slate-700/30" :class="collapsed ? 'justify-center' : ''">
            <div class="w-9 h-9 rounded-full bg-gradient-to-r from-blue-500 to-purple-600 flex items-center justify-center text-white font-semibold text-sm flex-shrink-0">
                {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
            </div>
            <div x-show="!collapsed" x-transition class="ml-3 flex-1 min-w-0">
                <p class="text-sm font-medium text-gray-900 dark:text-white truncate">{{ Auth::user()->name }}</p>
                <p class="text-xs text-gray-500 dark:text-slate-400 truncate">Director</p>
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
                <span x-show="!collapsed" x-transition class="ml-3 font-medium">Logout</span>
            </button>
        </form>
    </div>
</aside>
