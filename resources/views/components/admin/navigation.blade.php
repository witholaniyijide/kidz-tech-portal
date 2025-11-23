@props(['user' => null])

<nav class="bg-white dark:bg-slate-800 border-b border-gray-200 dark:border-slate-700 shadow-sm" role="navigation" aria-label="Admin navigation">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex space-x-8">
                {{-- Dashboard Link --}}
                <a
                    href="{{ route('dashboard') }}"
                    class="inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium transition-colors font-inter
                        {{ request()->routeIs('dashboard') ? 'border-teal-500 text-gray-900 dark:text-white' : 'border-transparent text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 hover:border-gray-300' }}"
                    aria-current="{{ request()->routeIs('dashboard') ? 'page' : 'false' }}"
                >
                    Dashboard
                </a>

                {{-- Students Dropdown --}}
                <div class="relative" x-data="{ open: false }" @click.away="open = false">
                    <button
                        @click="open = !open"
                        class="inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium transition-colors font-inter focus:outline-none
                            {{ request()->routeIs('students.*') ? 'border-teal-500 text-gray-900 dark:text-white' : 'border-transparent text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 hover:border-gray-300' }}"
                        aria-expanded="false"
                        aria-haspopup="true"
                        :aria-expanded="open.toString()"
                    >
                        Students
                        <svg class="ml-1 h-4 w-4 transition-transform" :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>
                    <div
                        x-show="open"
                        x-transition:enter="transition ease-out duration-200"
                        x-transition:enter-start="opacity-0 scale-95"
                        x-transition:enter-end="opacity-100 scale-100"
                        x-transition:leave="transition ease-in duration-150"
                        x-transition:leave-start="opacity-100 scale-100"
                        x-transition:leave-end="opacity-0 scale-95"
                        class="absolute z-10 mt-2 w-56 rounded-xl shadow-xl bg-white dark:bg-slate-800 ring-1 ring-black ring-opacity-5 overflow-hidden"
                        style="display: none;"
                        role="menu"
                        aria-orientation="vertical"
                    >
                        <a href="{{ route('students.index') }}" class="block px-4 py-3 text-sm text-gray-700 dark:text-gray-300 hover:bg-teal-50 dark:hover:bg-teal-900/20 transition-colors font-inter" role="menuitem">All Students</a>
                        <a href="{{ route('students.create') }}" class="block px-4 py-3 text-sm text-gray-700 dark:text-gray-300 hover:bg-teal-50 dark:hover:bg-teal-900/20 transition-colors font-inter" role="menuitem">Add Student</a>
                        <a href="{{ route('students.inactive') }}" class="block px-4 py-3 text-sm text-gray-700 dark:text-gray-300 hover:bg-teal-50 dark:hover:bg-teal-900/20 transition-colors font-inter" role="menuitem">Inactive Students</a>
                    </div>
                </div>

                {{-- Tutors Dropdown --}}
                <div class="relative" x-data="{ open: false }" @click.away="open = false">
                    <button
                        @click="open = !open"
                        class="inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium transition-colors font-inter focus:outline-none
                            {{ request()->routeIs('tutors.*') ? 'border-teal-500 text-gray-900 dark:text-white' : 'border-transparent text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 hover:border-gray-300' }}"
                        aria-expanded="false"
                        aria-haspopup="true"
                        :aria-expanded="open.toString()"
                    >
                        Tutors
                        <svg class="ml-1 h-4 w-4 transition-transform" :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>
                    <div
                        x-show="open"
                        x-transition:enter="transition ease-out duration-200"
                        x-transition:enter-start="opacity-0 scale-95"
                        x-transition:enter-end="opacity-100 scale-100"
                        x-transition:leave="transition ease-in duration-150"
                        x-transition:leave-start="opacity-100 scale-100"
                        x-transition:leave-end="opacity-0 scale-95"
                        class="absolute z-10 mt-2 w-56 rounded-xl shadow-xl bg-white dark:bg-slate-800 ring-1 ring-black ring-opacity-5 overflow-hidden"
                        style="display: none;"
                        role="menu"
                        aria-orientation="vertical"
                    >
                        <a href="{{ route('tutors.index') }}" class="block px-4 py-3 text-sm text-gray-700 dark:text-gray-300 hover:bg-teal-50 dark:hover:bg-teal-900/20 transition-colors font-inter" role="menuitem">All Tutors</a>
                        <a href="{{ route('tutors.create') }}" class="block px-4 py-3 text-sm text-gray-700 dark:text-gray-300 hover:bg-teal-50 dark:hover:bg-teal-900/20 transition-colors font-inter" role="menuitem">Add Tutor</a>
                        <a href="{{ route('tutors.assign') }}" class="block px-4 py-3 text-sm text-gray-700 dark:text-gray-300 hover:bg-teal-50 dark:hover:bg-teal-900/20 transition-colors font-inter" role="menuitem">Assign to Students</a>
                    </div>
                </div>

                {{-- Attendance Dropdown --}}
                <div class="relative" x-data="{ open: false }" @click.away="open = false">
                    <button
                        @click="open = !open"
                        class="inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium transition-colors font-inter focus:outline-none
                            {{ request()->routeIs('attendance.*') ? 'border-teal-500 text-gray-900 dark:text-white' : 'border-transparent text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 hover:border-gray-300' }}"
                        aria-expanded="false"
                        aria-haspopup="true"
                        :aria-expanded="open.toString()"
                    >
                        Attendance
                        <svg class="ml-1 h-4 w-4 transition-transform" :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>
                    <div
                        x-show="open"
                        x-transition:enter="transition ease-out duration-200"
                        x-transition:enter-start="opacity-0 scale-95"
                        x-transition:enter-end="opacity-100 scale-100"
                        x-transition:leave="transition ease-in duration-150"
                        x-transition:leave-start="opacity-100 scale-100"
                        x-transition:leave-end="opacity-0 scale-95"
                        class="absolute z-10 mt-2 w-56 rounded-xl shadow-xl bg-white dark:bg-slate-800 ring-1 ring-black ring-opacity-5 overflow-hidden"
                        style="display: none;"
                        role="menu"
                        aria-orientation="vertical"
                    >
                        <a href="{{ route('attendance.pending') }}" class="block px-4 py-3 text-sm text-gray-700 dark:text-gray-300 hover:bg-teal-50 dark:hover:bg-teal-900/20 transition-colors font-inter" role="menuitem">Pending Approvals</a>
                        <a href="{{ route('attendance.index') }}" class="block px-4 py-3 text-sm text-gray-700 dark:text-gray-300 hover:bg-teal-50 dark:hover:bg-teal-900/20 transition-colors font-inter" role="menuitem">All Records</a>
                        <a href="{{ route('attendance.calendar') }}" class="block px-4 py-3 text-sm text-gray-700 dark:text-gray-300 hover:bg-teal-50 dark:hover:bg-teal-900/20 transition-colors font-inter" role="menuitem">Calendar View</a>
                    </div>
                </div>

                {{-- Reports Dropdown --}}
                <div class="relative" x-data="{ open: false }" @click.away="open = false">
                    <button
                        @click="open = !open"
                        class="inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium transition-colors font-inter focus:outline-none
                            {{ request()->routeIs('reports.*') ? 'border-teal-500 text-gray-900 dark:text-white' : 'border-transparent text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 hover:border-gray-300' }}"
                        aria-expanded="false"
                        aria-haspopup="true"
                        :aria-expanded="open.toString()"
                    >
                        Reports
                        <svg class="ml-1 h-4 w-4 transition-transform" :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>
                    <div
                        x-show="open"
                        x-transition:enter="transition ease-out duration-200"
                        x-transition:enter-start="opacity-0 scale-95"
                        x-transition:enter-end="opacity-100 scale-100"
                        x-transition:leave="transition ease-in duration-150"
                        x-transition:leave-start="opacity-100 scale-100"
                        x-transition:leave-end="opacity-0 scale-95"
                        class="absolute z-10 mt-2 w-56 rounded-xl shadow-xl bg-white dark:bg-slate-800 ring-1 ring-black ring-opacity-5 overflow-hidden"
                        style="display: none;"
                        role="menu"
                        aria-orientation="vertical"
                    >
                        <a href="{{ route('reports.index') }}" class="block px-4 py-3 text-sm text-gray-700 dark:text-gray-300 hover:bg-teal-50 dark:hover:bg-teal-900/20 transition-colors font-inter" role="menuitem">All Reports</a>
                        <a href="{{ route('reports.by-student') }}" class="block px-4 py-3 text-sm text-gray-700 dark:text-gray-300 hover:bg-teal-50 dark:hover:bg-teal-900/20 transition-colors font-inter" role="menuitem">By Student</a>
                        <a href="{{ route('reports.by-tutor') }}" class="block px-4 py-3 text-sm text-gray-700 dark:text-gray-300 hover:bg-teal-50 dark:hover:bg-teal-900/20 transition-colors font-inter" role="menuitem">By Tutor</a>
                        <a href="{{ route('reports.by-month') }}" class="block px-4 py-3 text-sm text-gray-700 dark:text-gray-300 hover:bg-teal-50 dark:hover:bg-teal-900/20 transition-colors font-inter" role="menuitem">By Month</a>
                    </div>
                </div>

                {{-- Schedule Dropdown --}}
                <div class="relative" x-data="{ open: false }" @click.away="open = false">
                    <button
                        @click="open = !open"
                        class="inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium transition-colors font-inter focus:outline-none
                            {{ request()->routeIs('schedule.*') ? 'border-teal-500 text-gray-900 dark:text-white' : 'border-transparent text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 hover:border-gray-300' }}"
                        aria-expanded="false"
                        aria-haspopup="true"
                        :aria-expanded="open.toString()"
                    >
                        Schedule
                        <svg class="ml-1 h-4 w-4 transition-transform" :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>
                    <div
                        x-show="open"
                        x-transition:enter="transition ease-out duration-200"
                        x-transition:enter-start="opacity-0 scale-95"
                        x-transition:enter-end="opacity-100 scale-100"
                        x-transition:leave="transition ease-in duration-150"
                        x-transition:leave-start="opacity-100 scale-100"
                        x-transition:leave-end="opacity-0 scale-95"
                        class="absolute z-10 mt-2 w-56 rounded-xl shadow-xl bg-white dark:bg-slate-800 ring-1 ring-black ring-opacity-5 overflow-hidden"
                        style="display: none;"
                        role="menu"
                        aria-orientation="vertical"
                    >
                        <a href="{{ route('schedule.today') }}" class="block px-4 py-3 text-sm text-gray-700 dark:text-gray-300 hover:bg-teal-50 dark:hover:bg-teal-900/20 transition-colors font-inter" role="menuitem">Today's Schedule</a>
                        <a href="{{ route('schedule.weekly') }}" class="block px-4 py-3 text-sm text-gray-700 dark:text-gray-300 hover:bg-teal-50 dark:hover:bg-teal-900/20 transition-colors font-inter" role="menuitem">Weekly View</a>
                        <a href="{{ route('schedule.settings') }}" class="block px-4 py-3 text-sm text-gray-700 dark:text-gray-300 hover:bg-teal-50 dark:hover:bg-teal-900/20 transition-colors font-inter" role="menuitem">Auto-Post Settings</a>
                    </div>
                </div>

                {{-- Notice Board Dropdown --}}
                <div class="relative" x-data="{ open: false }" @click.away="open = false">
                    <button
                        @click="open = !open"
                        class="inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium transition-colors font-inter focus:outline-none
                            {{ request()->routeIs('noticeboard.*') ? 'border-teal-500 text-gray-900 dark:text-white' : 'border-transparent text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 hover:border-gray-300' }}"
                        aria-expanded="false"
                        aria-haspopup="true"
                        :aria-expanded="open.toString()"
                    >
                        Notice Board
                        <svg class="ml-1 h-4 w-4 transition-transform" :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>
                    <div
                        x-show="open"
                        x-transition:enter="transition ease-out duration-200"
                        x-transition:enter-start="opacity-0 scale-95"
                        x-transition:enter-end="opacity-100 scale-100"
                        x-transition:leave="transition ease-in duration-150"
                        x-transition:leave-start="opacity-100 scale-100"
                        x-transition:leave-end="opacity-0 scale-95"
                        class="absolute z-10 mt-2 w-56 rounded-xl shadow-xl bg-white dark:bg-slate-800 ring-1 ring-black ring-opacity-5 overflow-hidden"
                        style="display: none;"
                        role="menu"
                        aria-orientation="vertical"
                    >
                        <a href="{{ route('noticeboard.index') }}" class="block px-4 py-3 text-sm text-gray-700 dark:text-gray-300 hover:bg-teal-50 dark:hover:bg-teal-900/20 transition-colors font-inter" role="menuitem">All Notices</a>
                        <a href="{{ route('noticeboard.create') }}" class="block px-4 py-3 text-sm text-gray-700 dark:text-gray-300 hover:bg-teal-50 dark:hover:bg-teal-900/20 transition-colors font-inter" role="menuitem">Post Notice</a>
                    </div>
                </div>

                {{-- Analytics Dropdown --}}
                <div class="relative" x-data="{ open: false }" @click.away="open = false">
                    <button
                        @click="open = !open"
                        class="inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium transition-colors font-inter focus:outline-none
                            {{ request()->routeIs('analytics.*') ? 'border-teal-500 text-gray-900 dark:text-white' : 'border-transparent text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 hover:border-gray-300' }}"
                        aria-expanded="false"
                        aria-haspopup="true"
                        :aria-expanded="open.toString()"
                    >
                        Analytics
                        <svg class="ml-1 h-4 w-4 transition-transform" :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>
                    <div
                        x-show="open"
                        x-transition:enter="transition ease-out duration-200"
                        x-transition:enter-start="opacity-0 scale-95"
                        x-transition:enter-end="opacity-100 scale-100"
                        x-transition:leave="transition ease-in duration-150"
                        x-transition:leave-start="opacity-100 scale-100"
                        x-transition:leave-end="opacity-0 scale-95"
                        class="absolute z-10 mt-2 w-56 rounded-xl shadow-xl bg-white dark:bg-slate-800 ring-1 ring-black ring-opacity-5 overflow-hidden"
                        style="display: none;"
                        role="menu"
                        aria-orientation="vertical"
                    >
                        <a href="{{ route('analytics.students') }}" class="block px-4 py-3 text-sm text-gray-700 dark:text-gray-300 hover:bg-teal-50 dark:hover:bg-teal-900/20 transition-colors font-inter" role="menuitem">Students Growth</a>
                        <a href="{{ route('analytics.tutors') }}" class="block px-4 py-3 text-sm text-gray-700 dark:text-gray-300 hover:bg-teal-50 dark:hover:bg-teal-900/20 transition-colors font-inter" role="menuitem">Tutors Performance</a>
                        <a href="{{ route('analytics.attendance') }}" class="block px-4 py-3 text-sm text-gray-700 dark:text-gray-300 hover:bg-teal-50 dark:hover:bg-teal-900/20 transition-colors font-inter" role="menuitem">Attendance Analytics</a>
                        <a href="{{ route('analytics.reports') }}" class="block px-4 py-3 text-sm text-gray-700 dark:text-gray-300 hover:bg-teal-50 dark:hover:bg-teal-900/20 transition-colors font-inter" role="menuitem">Reports Analytics</a>
                    </div>
                </div>
            </div>

            {{-- Right side - User menu --}}
            <div class="flex items-center">
                <div class="relative" x-data="{ open: false }" @click.away="open = false">
                    <button
                        @click="open = !open"
                        class="flex items-center text-sm font-medium text-gray-700 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white transition-colors focus:outline-none focus-visible:ring-2 focus-visible:ring-teal-500 focus-visible:ring-offset-2 rounded-lg px-3 py-2"
                        aria-expanded="false"
                        aria-haspopup="true"
                        :aria-expanded="open.toString()"
                    >
                        <div class="h-8 w-8 rounded-full bg-gradient-to-br from-teal-400 to-cyan-500 flex items-center justify-center text-white font-semibold mr-2">
                            {{ strtoupper(substr(auth()->user()->name ?? 'A', 0, 1)) }}
                        </div>
                        {{ auth()->user()->name ?? 'Admin' }}
                        <svg class="ml-1 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>
                    <div
                        x-show="open"
                        x-transition:enter="transition ease-out duration-200"
                        x-transition:enter-start="opacity-0 scale-95"
                        x-transition:enter-end="opacity-100 scale-100"
                        x-transition:leave="transition ease-in duration-150"
                        x-transition:leave-start="opacity-100 scale-100"
                        x-transition:leave-end="opacity-0 scale-95"
                        class="absolute right-0 mt-2 w-48 rounded-xl shadow-xl bg-white dark:bg-slate-800 ring-1 ring-black ring-opacity-5 overflow-hidden"
                        style="display: none;"
                        role="menu"
                        aria-orientation="vertical"
                    >
                        <a href="{{ route('profile.edit') }}" class="block px-4 py-3 text-sm text-gray-700 dark:text-gray-300 hover:bg-teal-50 dark:hover:bg-teal-900/20 transition-colors font-inter" role="menuitem">Profile</a>
                        <form method="POST" action="{{ route('logout') }}" class="block">
                            @csrf
                            <button type="submit" class="w-full text-left px-4 py-3 text-sm text-gray-700 dark:text-gray-300 hover:bg-red-50 dark:hover:bg-red-900/20 transition-colors font-inter" role="menuitem">
                                Logout
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</nav>
