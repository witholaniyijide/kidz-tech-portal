<nav x-data="{
    open: false,
    darkMode: localStorage.getItem('darkMode') === 'true',
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
class="bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700 sticky top-0 z-50 shadow-sm">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}">
                        <img src="{{ asset('images/logo_light.png') }}"
                             alt="Kidz Tech Logo"
                             class="h-10 w-auto dark:hidden object-contain"
                             onerror="this.src='data:image/svg+xml,%3Csvg xmlns=%22http://www.w3.org/2000/svg%22 viewBox=%220 0 100 100%22%3E%3Crect fill=%22%234F46E5%22 width=%22100%22 height=%22100%22 rx=%2220%22/%3E%3Ctext x=%2250%22 y=%2265%22 font-size=%2250%22 text-anchor=%22middle%22 fill=%22white%22 font-family=%22Arial, sans-serif%22 font-weight=%22bold%22%3EK%3C/text%3E%3C/svg%3E'">
                        <img src="{{ asset('images/logo_dark.png') }}"
                             alt="Kidz Tech Logo"
                             class="h-10 w-auto hidden dark:block object-contain"
                             onerror="this.src='data:image/svg+xml,%3Csvg xmlns=%22http://www.w3.org/2000/svg%22 viewBox=%220 0 100 100%22%3E%3Crect fill=%22%234F46E5%22 width=%22100%22 height=%22100%22 rx=%2220%22/%3E%3Ctext x=%2250%22 y=%2265%22 font-size=%2250%22 text-anchor=%22middle%22 fill=%22white%22 font-family=%22Arial, sans-serif%22 font-weight=%22bold%22%3EK%3C/text%3E%3C/svg%3E'">
                    </a>
                </div>

                <!-- Navigation Links -->
                @if(Auth::check() && Auth::user()->hasRole('admin'))
                    {{-- Admin-specific navigation (hidden, will use custom admin nav instead) --}}
                @elseif(Auth::check() && Auth::user()->hasRole('manager'))
                    {{-- Manager-specific navigation --}}
                    <div class="hidden space-x-4 sm:-my-px sm:ms-10 sm:flex">
                        <x-nav-link :href="route('manager.dashboard')" :active="request()->routeIs('manager.dashboard')">
                            <svg class="w-4 h-4 mr-1 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                            {{ __('Dashboard') }}
                        </x-nav-link>

                        <x-nav-link :href="route('manager.students.index')" :active="request()->routeIs('manager.students.*')">
                            <svg class="w-4 h-4 mr-1 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"/></svg>
                            {{ __('Students') }}
                        </x-nav-link>

                        <x-nav-link :href="route('manager.tutors.index')" :active="request()->routeIs('manager.tutors.*')">
                            <svg class="w-4 h-4 mr-1 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                            {{ __('Tutors') }}
                        </x-nav-link>

                        <x-nav-link :href="route('manager.attendance.index')" :active="request()->routeIs('manager.attendance.*')">
                            <svg class="w-4 h-4 mr-1 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
                            {{ __('Attendance') }}
                        </x-nav-link>

                        <x-nav-link :href="route('manager.reports.index')" :active="request()->routeIs('manager.reports.*', 'manager.tutor-reports.*')">
                            <svg class="w-4 h-4 mr-1 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                            {{ __('Reports') }}
                        </x-nav-link>

                        <x-nav-link :href="route('manager.assessments.index')" :active="request()->routeIs('manager.assessments.*')">
                            <svg class="w-4 h-4 mr-1 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                            {{ __('Assessment') }}
                        </x-nav-link>

                        <x-nav-link :href="route('manager.notices.index')" :active="request()->routeIs('manager.notices.*')">
                            <svg class="w-4 h-4 mr-1 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"/></svg>
                            {{ __('Notices') }}
                        </x-nav-link>
                    </div>
                @elseif(Auth::check() && Auth::user()->hasRole('director'))
                    {{-- Director-specific navigation --}}
                    <div class="hidden space-x-4 sm:-my-px sm:ms-10 sm:flex">
                        <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                            <svg class="w-4 h-4 mr-1 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                            {{ __('Dashboard') }}
                        </x-nav-link>

                        <x-nav-link :href="route('director.students.index')" :active="request()->routeIs('director.students.*')">
                            <svg class="w-4 h-4 mr-1 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"/></svg>
                            {{ __('Students') }}
                        </x-nav-link>

                        <x-nav-link :href="route('director.tutors.index')" :active="request()->routeIs('director.tutors.*')">
                            <svg class="w-4 h-4 mr-1 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                            {{ __('Tutors') }}
                        </x-nav-link>

                        <x-nav-link :href="route('director.attendance.index')" :active="request()->routeIs('director.attendance.*')">
                            <svg class="w-4 h-4 mr-1 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
                            {{ __('Attendance') }}
                        </x-nav-link>

                        <x-nav-link :href="route('director.reports.index')" :active="request()->routeIs('director.reports.*')">
                            <svg class="w-4 h-4 mr-1 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                            {{ __('Reports') }}
                        </x-nav-link>

                        <x-nav-link :href="route('director.assessments.index')" :active="request()->routeIs('director.assessments.*')">
                            <svg class="w-4 h-4 mr-1 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                            {{ __('Assessment') }}
                        </x-nav-link>

                        <x-nav-link :href="route('director.finance.index')" :active="request()->routeIs('director.finance.*')">
                            <svg class="w-4 h-4 mr-1 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            {{ __('Finance') }}
                        </x-nav-link>

                        <x-nav-link :href="route('director.analytics.index')" :active="request()->routeIs('director.analytics.*')">
                            <svg class="w-4 h-4 mr-1 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z"/></svg>
                            {{ __('Analytics') }}
                        </x-nav-link>

                        <x-nav-link :href="route('director.notices.index')" :active="request()->routeIs('director.notices.*')">
                            <svg class="w-4 h-4 mr-1 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"/></svg>
                            {{ __('Notices') }}
                        </x-nav-link>

                        <x-nav-link :href="route('director.messages.index')" :active="request()->routeIs('director.messages.*')">
                            <svg class="w-4 h-4 mr-1 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/></svg>
                            {{ __('Messages') }}
                        </x-nav-link>
                    </div>
                @else
                    {{-- Default navigation for other roles --}}
                    <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                        <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                            {{ __('Dashboard') }}
                        </x-nav-link>

                        <x-nav-link :href="route('students.index')" :active="request()->routeIs('students.*')">
                            {{ __('Students') }}
                        </x-nav-link>

                        <x-nav-link :href="route('tutors.index')" :active="request()->routeIs('tutors.*')">
                            {{ __('Tutors') }}
                        </x-nav-link>

                        @if(!Auth::user()->hasRole('admin'))
                            <x-nav-link :href="route('payments.index')" :active="request()->routeIs('payments.*')">
                                {{ __('Finance') }}
                            </x-nav-link>
                        @endif

                        <x-nav-link :href="route('analytics')" :active="request()->routeIs('analytics')">
                            {{ __('Analytics') }}
                        </x-nav-link>

                        <x-nav-link :href="route('attendance.index')" :active="request()->routeIs('attendance.*')">
                            {{ __('Attendance') }}
                        </x-nav-link>

                        <x-nav-link :href="route('reports.index')" :active="request()->routeIs('reports.*')">
                            {{ __('Reports') }}
                        </x-nav-link>
                    </div>
                @endif
            </div>

            <!-- Right Side: Dark Mode, Notifications, User -->
            <div class="hidden sm:flex sm:items-center sm:ms-6 sm:space-x-3">

                <!-- Dark Mode Toggle -->
                <button @click="toggleDarkMode()"
                        class="relative inline-flex items-center justify-center p-2 rounded-full text-gray-600 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white hover:bg-gray-100 dark:hover:bg-gray-700 focus:outline-none transition ease-in-out duration-150"
                        title="Toggle Dark Mode">
                    <svg x-show="!darkMode" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/>
                    </svg>
                    <svg x-show="darkMode" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="display: none;">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/>
                    </svg>
                </button>

                <!-- Notification Bell -->
                <x-dropdown align="right" width="96">
                    <x-slot name="trigger">
                        <button class="relative inline-flex items-center justify-center p-2 rounded-full text-gray-600 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white hover:bg-gray-100 dark:hover:bg-gray-700 focus:outline-none transition ease-in-out duration-150">
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
                                <span class="absolute top-0 right-0 inline-flex items-center justify-center w-5 h-5 text-xs font-bold text-white bg-red-500 rounded-full animate-pulse">
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
                                <a href="{{ $notification->data['report_id'] ?? '#' }}"
                                   class="block px-4 py-4 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 border-b dark:border-gray-600 transition"
                                   onclick="markAsRead('{{ $notification->id }}')">
                                    <div class="font-semibold mb-1">{{ $notification->data['student_name'] ?? 'Report Update' }}</div>
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

                            @if($unreadCount > 0)
                                <a href="{{ route('notifications.markAllRead') }}" class="block px-4 py-3 text-sm text-center text-blue-600 dark:text-blue-400 hover:bg-gray-100 dark:hover:bg-gray-700 font-medium border-t dark:border-gray-600">
                                    Mark all as read
                                </a>
                            @endif
                        </div>
                    </x-slot>
                </x-dropdown>

                <!-- User Dropdown -->
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none transition ease-in-out duration-150">
                            <div class="flex items-center space-x-2">
                                <div class="w-8 h-8 rounded-full bg-gradient-to-r from-blue-500 to-purple-600 flex items-center justify-center text-white font-semibold text-sm">
                                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                                </div>
                                <div>{{ Auth::user()->name }}</div>
                            </div>

                            <div class="ms-1">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <div class="px-4 py-2 text-xs text-gray-500 dark:text-gray-400 border-b dark:border-gray-600">
                            {{ Auth::user()->email }}
                        </div>

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

            <!-- Hamburger (Mobile) -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 dark:text-gray-500 hover:text-gray-500 dark:hover:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-900 focus:outline-none focus:bg-gray-100 dark:focus:bg-gray-900 focus:text-gray-500 dark:focus:text-gray-400 transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                {{ __('Dashboard') }}
            </x-responsive-nav-link>

            <x-responsive-nav-link :href="route('students.index')" :active="request()->routeIs('students.*')">
                {{ __('Students') }}
            </x-responsive-nav-link>

            <x-responsive-nav-link :href="route('tutors.index')" :active="request()->routeIs('tutors.*')">
                {{ __('Tutors') }}
            </x-responsive-nav-link>

            <x-responsive-nav-link :href="route('payments.index')" :active="request()->routeIs('payments.*')">
                {{ __('Finance') }}
            </x-responsive-nav-link>

            <x-responsive-nav-link :href="route('analytics')" :active="request()->routeIs('analytics')">
                {{ __('Analytics') }}
            </x-responsive-nav-link>

            <x-responsive-nav-link :href="route('attendance.index')" :active="request()->routeIs('attendance.*')">
                {{ __('Attendance') }}
            </x-responsive-nav-link>

            <x-responsive-nav-link :href="route('reports.index')" :active="request()->routeIs('reports.*')">
                {{ __('Reports') }}
            </x-responsive-nav-link>
        </div>

        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-1 border-t border-gray-200 dark:border-gray-600">
            <div class="px-4">
                <div class="font-medium text-base text-gray-800 dark:text-gray-200">{{ Auth::user()->name }}</div>
                <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
            </div>

            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('profile.edit')">
                    {{ __('Profile') }}
                </x-responsive-nav-link>

                <!-- Dark Mode Toggle Mobile -->
                <button @click="toggleDarkMode()" class="w-full text-left block px-4 py-2 text-base font-medium text-gray-600 dark:text-gray-400 hover:text-gray-800 dark:hover:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700 focus:outline-none focus:text-gray-800 dark:focus:text-gray-200 focus:bg-gray-100 dark:focus:bg-gray-700 transition duration-150 ease-in-out">
                    <span x-text="darkMode ? 'Light Mode' : 'Dark Mode'"></span>
                </button>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf

                    <x-responsive-nav-link :href="route('logout')"
                            onclick="event.preventDefault();
                                        this.closest('form').submit();">
                        {{ __('Log Out') }}
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>

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
