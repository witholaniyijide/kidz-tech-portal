<!-- Sidebar Navigation -->
<div x-data="{
    sidebarOpen: localStorage.getItem('sidebarOpen') === 'true' || localStorage.getItem('sidebarOpen') === null,
    darkMode: localStorage.getItem('darkMode') === 'true',
    mobileMenuOpen: false,
    toggleSidebar() {
        this.sidebarOpen = !this.sidebarOpen;
        localStorage.setItem('sidebarOpen', this.sidebarOpen);
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
x-init="
    if (darkMode) {
        document.documentElement.classList.add('dark');
    }
"
class="relative">

    <!-- Mobile Overlay -->
    <div x-show="mobileMenuOpen"
         @click="mobileMenuOpen = false"
         x-transition:enter="transition-opacity ease-linear duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition-opacity ease-linear duration-300"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 bg-gray-900 bg-opacity-50 z-20 lg:hidden"
         style="display: none;">
    </div>

    <!-- Sidebar -->
    <aside :class="sidebarOpen ? 'w-64' : 'w-20'"
           class="fixed left-0 top-0 h-screen bg-white dark:bg-gray-800 border-r border-gray-200 dark:border-gray-700 z-30 transition-all duration-300 ease-in-out overflow-hidden hidden lg:block"
           x-cloak>

        <!-- Logo Section -->
        <div class="h-16 flex items-center justify-between px-4 border-b border-gray-200 dark:border-gray-700">
            <a href="{{ route('dashboard') }}" class="flex items-center space-x-3 overflow-hidden">
                <img src="{{ asset('images/logo_light.png') }}"
                     alt="Logo"
                     class="h-10 w-10 flex-shrink-0 dark:hidden object-contain"
                     onerror="this.src='data:image/svg+xml,%3Csvg xmlns=%22http://www.w3.org/2000/svg%22 viewBox=%220 0 100 100%22%3E%3Crect fill=%22%234F46E5%22 width=%22100%22 height=%22100%22 rx=%2220%22/%3E%3Ctext x=%2250%22 y=%2265%22 font-size=%2250%22 text-anchor=%22middle%22 fill=%22white%22 font-family=%22Arial, sans-serif%22 font-weight=%22bold%22%3EK%3C/text%3E%3C/svg%3E'">
                <img src="{{ asset('images/logo_dark.png') }}"
                     alt="Logo"
                     class="h-10 w-10 flex-shrink-0 hidden dark:block object-contain"
                     onerror="this.src='data:image/svg+xml,%3Csvg xmlns=%22http://www.w3.org/2000/svg%22 viewBox=%220 0 100 100%22%3E%3Crect fill=%22%234F46E5%22 width=%22100%22 height=%22100%22 rx=%2220%22/%3E%3Ctext x=%2250%22 y=%2265%22 font-size=%2250%22 text-anchor=%22middle%22 fill=%22white%22 font-family=%22Arial, sans-serif%22 font-weight=%22bold%22%3EK%3C/text%3E%3C/svg%3E'">
                <span x-show="sidebarOpen"
                      x-transition:enter="transition ease-out duration-200"
                      x-transition:enter-start="opacity-0 transform scale-95"
                      x-transition:enter-end="opacity-100 transform scale-100"
                      class="font-bold text-lg text-gray-900 dark:text-white whitespace-nowrap">
                    Kidz Tech
                </span>
            </a>

            <!-- Hamburger Toggle (Desktop) -->
            <button @click="toggleSidebar()"
                    class="hidden lg:flex p-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 text-gray-600 dark:text-gray-300 transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                </svg>
            </button>
        </div>

        <!-- Navigation Links -->
        <nav class="flex-1 px-3 py-4 space-y-1 overflow-y-auto">

            <!-- Dashboard -->
            <a href="{{ route('dashboard') }}"
               class="flex items-center space-x-3 px-3 py-2.5 rounded-lg transition-all duration-200 group {{ request()->routeIs('dashboard') ? 'bg-blue-50 dark:bg-blue-900/20 text-blue-600 dark:text-blue-400' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700' }}">
                <svg class="w-6 h-6 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                </svg>
                <span x-show="sidebarOpen"
                      x-transition:enter="transition ease-out duration-200 delay-100"
                      x-transition:enter-start="opacity-0"
                      x-transition:enter-end="opacity-100"
                      class="font-medium whitespace-nowrap">
                    Dashboard
                </span>
            </a>

            <!-- Students -->
            <a href="{{ route('students.index') }}"
               class="flex items-center space-x-3 px-3 py-2.5 rounded-lg transition-all duration-200 group {{ request()->routeIs('students.*') ? 'bg-blue-50 dark:bg-blue-900/20 text-blue-600 dark:text-blue-400' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700' }}">
                <svg class="w-6 h-6 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                </svg>
                <span x-show="sidebarOpen"
                      x-transition:enter="transition ease-out duration-200 delay-100"
                      x-transition:enter-start="opacity-0"
                      x-transition:enter-end="opacity-100"
                      class="font-medium whitespace-nowrap">
                    Students
                </span>
            </a>

            <!-- Tutors -->
            <a href="{{ route('tutors.index') }}"
               class="flex items-center space-x-3 px-3 py-2.5 rounded-lg transition-all duration-200 group {{ request()->routeIs('tutors.*') ? 'bg-blue-50 dark:bg-blue-900/20 text-blue-600 dark:text-blue-400' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700' }}">
                <svg class="w-6 h-6 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                </svg>
                <span x-show="sidebarOpen"
                      x-transition:enter="transition ease-out duration-200 delay-100"
                      x-transition:enter-start="opacity-0"
                      x-transition:enter-end="opacity-100"
                      class="font-medium whitespace-nowrap">
                    Tutors
                </span>
            </a>

            <!-- Finance -->
            <a href="{{ route('payments.index') }}"
               class="flex items-center space-x-3 px-3 py-2.5 rounded-lg transition-all duration-200 group {{ request()->routeIs('payments.*') ? 'bg-blue-50 dark:bg-blue-900/20 text-blue-600 dark:text-blue-400' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700' }}">
                <svg class="w-6 h-6 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <span x-show="sidebarOpen"
                      x-transition:enter="transition ease-out duration-200 delay-100"
                      x-transition:enter-start="opacity-0"
                      x-transition:enter-end="opacity-100"
                      class="font-medium whitespace-nowrap">
                    Finance
                </span>
            </a>

            <!-- Analytics -->
            <a href="{{ route('analytics') }}"
               class="flex items-center space-x-3 px-3 py-2.5 rounded-lg transition-all duration-200 group {{ request()->routeIs('analytics') ? 'bg-blue-50 dark:bg-blue-900/20 text-blue-600 dark:text-blue-400' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700' }}">
                <svg class="w-6 h-6 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                </svg>
                <span x-show="sidebarOpen"
                      x-transition:enter="transition ease-out duration-200 delay-100"
                      x-transition:enter-start="opacity-0"
                      x-transition:enter-end="opacity-100"
                      class="font-medium whitespace-nowrap">
                    Analytics
                </span>
            </a>

            <!-- Attendance -->
            <a href="{{ route('attendance.index') }}"
               class="flex items-center space-x-3 px-3 py-2.5 rounded-lg transition-all duration-200 group {{ request()->routeIs('attendance.*') ? 'bg-blue-50 dark:bg-blue-900/20 text-blue-600 dark:text-blue-400' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700' }}">
                <svg class="w-6 h-6 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                </svg>
                <span x-show="sidebarOpen"
                      x-transition:enter="transition ease-out duration-200 delay-100"
                      x-transition:enter-start="opacity-0"
                      x-transition:enter-end="opacity-100"
                      class="font-medium whitespace-nowrap">
                    Attendance
                </span>
            </a>

            <!-- Reports -->
            <a href="{{ route('reports.index') }}"
               class="flex items-center space-x-3 px-3 py-2.5 rounded-lg transition-all duration-200 group {{ request()->routeIs('reports.*') ? 'bg-blue-50 dark:bg-blue-900/20 text-blue-600 dark:text-blue-400' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700' }}">
                <svg class="w-6 h-6 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                <span x-show="sidebarOpen"
                      x-transition:enter="transition ease-out duration-200 delay-100"
                      x-transition:enter-start="opacity-0"
                      x-transition:enter-end="opacity-100"
                      class="font-medium whitespace-nowrap">
                    Reports
                </span>
            </a>

        </nav>

        <!-- Bottom Section -->
        <div class="border-t border-gray-200 dark:border-gray-700">
            <!-- User Section -->
            <div class="p-3">
                <x-dropdown align="bottom" width="48">
                    <x-slot name="trigger">
                        <button class="w-full flex items-center space-x-3 px-3 py-2.5 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                            <div class="w-8 h-8 rounded-full bg-gradient-to-r from-blue-500 to-purple-600 flex items-center justify-center text-white font-semibold text-sm flex-shrink-0">
                                {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                            </div>
                            <div x-show="sidebarOpen"
                                 x-transition:enter="transition ease-out duration-200 delay-100"
                                 x-transition:enter-start="opacity-0"
                                 x-transition:enter-end="opacity-100"
                                 class="flex-1 text-left overflow-hidden">
                                <div class="text-sm font-medium text-gray-900 dark:text-white truncate">{{ Auth::user()->name }}</div>
                                <div class="text-xs text-gray-500 dark:text-gray-400 truncate">{{ Auth::user()->email }}</div>
                            </div>
                            <svg x-show="sidebarOpen" class="w-4 h-4 text-gray-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <x-dropdown-link :href="route('profile.edit')">
                            <div class="flex items-center">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                </svg>
                                {{ __('Profile') }}
                            </div>
                        </x-dropdown-link>

                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <x-dropdown-link :href="route('logout')"
                                    onclick="event.preventDefault();
                                                this.closest('form').submit();">
                                <div class="flex items-center text-red-600">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                                    </svg>
                                    {{ __('Log Out') }}
                                </div>
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>
        </div>

    </aside>

    <!-- Top Bar (for mobile and utility buttons) -->
    <header :class="sidebarOpen ? 'lg:ml-64' : 'lg:ml-20'"
            class="fixed top-0 right-0 left-0 lg:left-auto h-16 bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700 z-10 transition-all duration-300">
        <div class="h-full px-4 flex items-center justify-between">

            <!-- Mobile Menu Toggle -->
            <button @click="mobileMenuOpen = !mobileMenuOpen"
                    class="lg:hidden p-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 text-gray-600 dark:text-gray-300">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                </svg>
            </button>

            <!-- Page Title (Mobile) -->
            <div class="lg:hidden font-semibold text-gray-900 dark:text-white">
                @if(isset($header))
                    {{ $header }}
                @else
                    Kidz Tech Portal
                @endif
            </div>

            <!-- Right Side Buttons -->
            <div class="flex items-center space-x-2">

                <!-- Dark Mode Toggle -->
                <button @click="toggleDarkMode()"
                        class="p-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 text-gray-600 dark:text-gray-300 transition-colors"
                        title="Toggle Dark Mode">
                    <svg x-show="!darkMode" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/>
                    </svg>
                    <svg x-show="darkMode" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="display: none;">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/>
                    </svg>
                </button>

                <!-- Notifications -->
                <x-dropdown align="right" width="80">
                    <x-slot name="trigger">
                        <button class="relative p-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 text-gray-600 dark:text-gray-300 transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                            </svg>
                            @if(Auth::user()->unreadNotifications->count() > 0)
                                <span class="absolute top-1 right-1 inline-flex items-center justify-center w-4 h-4 text-xs font-bold text-white bg-red-500 rounded-full">
                                    {{ Auth::user()->unreadNotifications->count() }}
                                </span>
                            @endif
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <div class="py-2 max-h-96 overflow-y-auto">
                            <div class="px-4 py-2 text-xs font-semibold text-gray-700 dark:text-gray-300 bg-gray-50 dark:bg-gray-700 border-b dark:border-gray-600 sticky top-0">
                                Notifications
                            </div>

                            @forelse(Auth::user()->unreadNotifications->take(5) as $notification)
                                <a href="{{ $notification->data['report_id'] ?? '#' }}"
                                   class="block px-4 py-3 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 border-b dark:border-gray-600 transition"
                                   onclick="markAsRead('{{ $notification->id }}')">
                                    <div class="font-semibold">{{ $notification->data['student_name'] ?? 'Report Update' }}</div>
                                    <div class="text-xs text-gray-500 dark:text-gray-400">{{ $notification->created_at->diffForHumans() }}</div>
                                </a>
                            @empty
                                <div class="px-4 py-8 text-sm text-gray-500 dark:text-gray-400 text-center">
                                    <svg class="w-12 h-12 mx-auto mb-2 text-gray-300 dark:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                                    </svg>
                                    No new notifications
                                </div>
                            @endforelse

                            @if(Auth::user()->unreadNotifications->count() > 0)
                                <a href="{{ route('notifications.markAllRead') }}" class="block px-4 py-2 text-xs text-center text-blue-600 dark:text-blue-400 hover:bg-gray-100 dark:hover:bg-gray-700 font-medium border-t dark:border-gray-600">
                                    Mark all as read
                                </a>
                            @endif
                        </div>
                    </x-slot>
                </x-dropdown>

            </div>
        </div>
    </header>

</div>

<script>
    function markAsRead(notificationId) {
        fetch(`/notifications/${notificationId}/mark-read`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        });
    }
</script>
