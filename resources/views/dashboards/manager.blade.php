<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-2xl text-white">
            {{ __('Manager Dashboard') }}
        </h2>
    </x-slot>

    <x-slot name="title">{{ __('Manager Dashboard') }}</x-slot>

    {{-- Animated Background --}}
    <div class="min-h-screen bg-gradient-to-br from-sky-50 via-blue-50 to-cyan-50 dark:from-slate-900 dark:via-slate-800 dark:to-slate-900 py-12 relative overflow-hidden">
        {{-- Floating Orbs Background --}}
        <div class="absolute top-0 left-0 w-72 h-72 bg-sky-300 dark:bg-sky-900 rounded-full mix-blend-multiply filter blur-xl opacity-70 animate-float"></div>
        <div class="absolute top-0 right-0 w-72 h-72 bg-blue-300 dark:bg-blue-900 rounded-full mix-blend-multiply filter blur-xl opacity-70 animate-float" style="animation-delay: 2s;"></div>
        <div class="absolute -bottom-8 left-20 w-72 h-72 bg-cyan-300 dark:bg-cyan-900 rounded-full mix-blend-multiply filter blur-xl opacity-70 animate-float" style="animation-delay: 4s;"></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">

            {{-- Welcome Banner --}}
            <div class="bg-white/20 dark:bg-gray-900/30 backdrop-blur-xl border border-white/10 rounded-2xl shadow-md p-8 mb-10">
                <div class="flex items-center justify-between flex-wrap gap-4">
                    <div class="flex items-center gap-4">
                        <div class="bg-gradient-to-r from-sky-500 to-cyan-400 p-4 rounded-2xl shadow-lg">
                            <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-3xl font-bold text-gray-900 dark:text-white mb-1">
                                Welcome back, <span class="text-transparent bg-clip-text bg-gradient-to-r from-sky-500 to-cyan-400">{{ auth()->user()->name ?? 'Manager' }}</span>!
                            </h3>
                            <p class="text-gray-600 dark:text-gray-300 text-lg">Operations & Tutor Performance Coordination Hub</p>
                        </div>
                    </div>
                    <div class="text-right">
                        <div class="text-gray-700 dark:text-gray-300 font-semibold">{{ now()->format('l, F j, Y') }}</div>
                        <div class="text-gray-500 dark:text-gray-400 text-sm">{{ now()->format('g:i A') }}</div>
                    </div>
                </div>
            </div>

            {{-- Main Statistics Cards --}}
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-10">
                {{-- Active Students Card --}}
                <div class="bg-white/20 dark:bg-gray-900/30 backdrop-blur-xl border border-white/10 rounded-2xl shadow-md p-6 hover:-translate-y-1 transition-all duration-300">
                    <div class="flex items-center justify-between mb-4">
                        <div class="bg-gradient-to-r from-sky-500 to-cyan-400 p-3 rounded-xl">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                            </svg>
                        </div>
                    </div>
                    <div class="text-4xl font-extrabold text-gray-900 dark:text-white mb-2">13</div>
                    <div class="text-gray-600 dark:text-gray-300 font-medium">Active Students</div>
                    <div class="text-sm text-gray-500 dark:text-gray-400 mt-2">13 active • 0 inactive</div>
                </div>

                {{-- Active Tutors Card --}}
                <div class="bg-white/20 dark:bg-gray-900/30 backdrop-blur-xl border border-white/10 rounded-2xl shadow-md p-6 hover:-translate-y-1 transition-all duration-300">
                    <div class="flex items-center justify-between mb-4">
                        <div class="bg-gradient-to-r from-cyan-500 to-blue-500 p-3 rounded-xl">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                        </div>
                    </div>
                    <div class="text-4xl font-extrabold text-gray-900 dark:text-white mb-2">6</div>
                    <div class="text-gray-600 dark:text-gray-300 font-medium">Active Tutors</div>
                    <div class="text-sm text-gray-500 dark:text-gray-400 mt-2">5 active • 1 on leave</div>
                </div>

                {{-- Today's Classes Card --}}
                <div class="bg-white/20 dark:bg-gray-900/30 backdrop-blur-xl border border-white/10 rounded-2xl shadow-md p-6 hover:-translate-y-1 transition-all duration-300">
                    <div class="flex items-center justify-between mb-4">
                        <div class="bg-gradient-to-r from-amber-500 to-orange-400 p-3 rounded-xl">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                        </div>
                    </div>
                    <div class="text-4xl font-extrabold text-gray-900 dark:text-white mb-2">8</div>
                    <div class="text-gray-600 dark:text-gray-300 font-medium">Today's Classes</div>
                    <div class="text-sm text-gray-500 dark:text-gray-400 mt-2">5 completed • 3 upcoming</div>
                </div>

                {{-- Pending Assessments Card --}}
                <div class="bg-white/20 dark:bg-gray-900/30 backdrop-blur-xl border border-white/10 rounded-2xl shadow-md p-6 hover:-translate-y-1 transition-all duration-300">
                    <div class="flex items-center justify-between mb-4">
                        <div class="bg-gradient-to-r from-rose-500 to-pink-500 p-3 rounded-xl">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                            </svg>
                        </div>
                    </div>
                    <div class="text-4xl font-extrabold text-gray-900 dark:text-white mb-2">4</div>
                    <div class="text-gray-600 dark:text-gray-300 font-medium">Pending Assessments</div>
                    <div class="text-sm text-gray-500 dark:text-gray-400 mt-2">Awaiting approval</div>
                </div>
            </div>

            {{-- Main Content Grid: Schedule (Full Width) + To-Do & Notices (Below) --}}
            <div class="space-y-6 mb-10">
                {{-- Daily Class Schedule (Full Width) --}}
                <div>
                    <div class="bg-white/20 dark:bg-gray-900/30 backdrop-blur-xl border border-white/10 rounded-2xl shadow-md p-6 h-full">
                        <div class="flex items-center justify-between mb-6">
                            <div class="flex items-center gap-3">
                                <div class="bg-gradient-to-r from-sky-500 to-cyan-400 p-2 rounded-lg">
                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                </div>
                                <h3 class="text-2xl font-bold text-gray-900 dark:text-white">Today's Class Schedule</h3>
                            </div>
                            <span class="px-3 py-1 bg-sky-100 dark:bg-sky-900/30 text-sky-700 dark:text-sky-300 rounded-full text-sm font-semibold">
                                {{ now()->format('l') }}
                            </span>
                        </div>

                        <div class="space-y-3 max-h-96 overflow-y-auto">
                            {{-- Dummy Schedule Items --}}
                            @php
                                $scheduleItems = [
                                    ['student' => 'Tolu Williams', 'time' => '10:00 AM', 'tutor' => 'David Okoronkwo', 'status' => 'completed'],
                                    ['student' => 'Chiamaka Williams', 'time' => '11:30 AM', 'tutor' => 'Chioma Adebayo', 'status' => 'completed'],
                                    ['student' => 'Ayo Balogun', 'time' => '1:00 PM', 'tutor' => 'Emmanuel Johnson', 'status' => 'completed'],
                                    ['student' => 'Funke Adeyemi', 'time' => '2:30 PM', 'tutor' => 'David Okoronkwo', 'status' => 'in_progress'],
                                    ['student' => 'Emeka Obi', 'time' => '4:00 PM', 'tutor' => 'Chioma Adebayo', 'status' => 'upcoming'],
                                    ['student' => 'Blessing Nnamdi', 'time' => '5:30 PM', 'tutor' => 'Emmanuel Johnson', 'status' => 'upcoming'],
                                ];
                            @endphp

                            @forelse($scheduleItems as $item)
                                <div class="bg-white/30 dark:bg-gray-800/30 backdrop-blur-sm rounded-xl p-4 border border-white/10 hover:bg-white/40 dark:hover:bg-gray-800/40 transition-all">
                                    <div class="flex items-center justify-between flex-wrap gap-2">
                                        <div class="flex items-center gap-3 flex-1">
                                            <div class="bg-gradient-to-r from-sky-500 to-cyan-400 w-10 h-10 rounded-full flex items-center justify-center text-white font-bold text-sm">
                                                {{ substr($item['student'], 0, 1) }}
                                            </div>
                                            <div>
                                                <div class="font-semibold text-gray-900 dark:text-white">{{ $item['student'] }}</div>
                                                <div class="text-sm text-gray-600 dark:text-gray-400">Tutor: {{ $item['tutor'] }}</div>
                                            </div>
                                        </div>
                                        <div class="flex items-center gap-3">
                                            <div class="text-right">
                                                <div class="font-semibold text-gray-700 dark:text-gray-300">{{ $item['time'] }}</div>
                                                @if($item['status'] === 'completed')
                                                    <span class="text-xs px-2 py-1 bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-300 rounded-full">Completed</span>
                                                @elseif($item['status'] === 'in_progress')
                                                    <span class="text-xs px-2 py-1 bg-amber-100 dark:bg-amber-900/30 text-amber-700 dark:text-amber-300 rounded-full">In Progress</span>
                                                @else
                                                    <span class="text-xs px-2 py-1 bg-gray-100 dark:bg-gray-700/30 text-gray-700 dark:text-gray-300 rounded-full">Upcoming</span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="text-center py-12">
                                    <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                    <p class="text-gray-500 dark:text-gray-400">No classes scheduled for today</p>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>

                {{-- To-Do List & Notice Board (Below Schedule) --}}
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    {{-- To-Do List --}}
                    <div class="bg-white/20 dark:bg-gray-900/30 backdrop-blur-xl border border-white/10 rounded-2xl shadow-md p-6">
                        <div class="flex items-center gap-3 mb-6">
                            <div class="bg-gradient-to-r from-sky-500 to-cyan-400 p-2 rounded-lg">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                </svg>
                            </div>
                            <h3 class="text-xl font-bold text-gray-900 dark:text-white">Today's To-Do List</h3>
                        </div>

                        <div id="todoList" class="space-y-3 mb-4">
                            {{-- Tasks will be loaded by JavaScript --}}
                        </div>

                        <div class="pt-4 border-t border-white/10">
                            <div class="flex gap-2">
                                <input type="text" id="newTaskInput" placeholder="Add a new task..." class="flex-1 px-4 py-2 bg-white/30 dark:bg-gray-800/30 border border-white/10 rounded-lg text-gray-900 dark:text-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-sky-500">
                                <button id="addTaskBtn" class="px-4 py-2 bg-gradient-to-r from-sky-500 to-cyan-400 text-white rounded-lg hover:-translate-y-0.5 transition-all shadow-md">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                    </svg>
                                </button>
                            </div>
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-2">Tasks are saved automatically in your browser</p>
                        </div>
                    </div>

                    {{-- Notice Board Preview --}}
                    <div class="bg-white/20 dark:bg-gray-900/30 backdrop-blur-xl border border-white/10 rounded-2xl shadow-md p-6">
                        <div class="flex items-center justify-between mb-6">
                            <div class="flex items-center gap-3">
                                <div class="bg-gradient-to-r from-amber-500 to-orange-400 p-2 rounded-lg">
                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z" />
                                    </svg>
                                </div>
                                <h3 class="text-xl font-bold text-gray-900 dark:text-white">Notice Board</h3>
                            </div>
                        </div>

                        <div class="space-y-3 mb-4">
                            @php
                                $notices = [
                                    ['title' => 'New Class Time Updates', 'priority' => 'important', 'time' => '2 days ago'],
                                    ['title' => 'Tutor Training Session', 'priority' => 'general', 'time' => '5 days ago'],
                                    ['title' => 'Monthly Reports Due', 'priority' => 'reminder', 'time' => '1 week ago'],
                                ];
                            @endphp

                            @foreach($notices as $notice)
                                <div class="bg-white/30 dark:bg-gray-800/30 backdrop-blur-sm rounded-lg p-3 border border-white/10 hover:bg-white/40 dark:hover:bg-gray-800/40 transition-all">
                                    <div class="flex items-start justify-between gap-2">
                                        <div class="flex-1">
                                            <div class="font-semibold text-gray-900 dark:text-white text-sm">{{ $notice['title'] }}</div>
                                            <div class="text-xs text-gray-500 dark:text-gray-400 mt-1">{{ $notice['time'] }}</div>
                                        </div>
                                        @if($notice['priority'] === 'important')
                                            <span class="px-2 py-1 bg-rose-100 dark:bg-rose-900/30 text-rose-700 dark:text-rose-300 text-xs rounded-full font-semibold whitespace-nowrap">Important</span>
                                        @elseif($notice['priority'] === 'reminder')
                                            <span class="px-2 py-1 bg-amber-100 dark:bg-amber-900/30 text-amber-700 dark:text-amber-300 text-xs rounded-full font-semibold whitespace-nowrap">Reminder</span>
                                        @else
                                            <span class="px-2 py-1 bg-sky-100 dark:bg-sky-900/30 text-sky-700 dark:text-sky-300 text-xs rounded-full font-semibold whitespace-nowrap">General</span>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <div class="flex gap-2">
                            <a href="{{ route('manager.notices.create') }}" class="flex-1 px-4 py-2 bg-gradient-to-r from-sky-500 to-cyan-400 text-white rounded-lg hover:-translate-y-0.5 transition-all shadow-md font-semibold text-sm text-center">
                                Create Notice
                            </a>
                            <a href="{{ route('manager.notices.index') }}" class="px-4 py-2 bg-white/30 dark:bg-gray-800/30 border border-white/10 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-white/40 dark:hover:bg-gray-800/40 transition-all font-semibold text-sm">
                                View All
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Recent Students & Tutors Tables --}}
            <div class="grid grid-cols-1 xl:grid-cols-2 gap-6 mb-10">
                {{-- Recent Students Table --}}
                <div class="bg-white/20 dark:bg-gray-900/30 backdrop-blur-xl border border-white/10 rounded-2xl shadow-md p-6">
                    <div class="flex items-center justify-between mb-6">
                        <div class="flex items-center gap-3">
                            <div class="bg-gradient-to-r from-sky-500 to-cyan-400 p-2 rounded-lg">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                                </svg>
                            </div>
                            <h3 class="text-xl font-bold text-gray-900 dark:text-white">Recent Students</h3>
                        </div>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead>
                                <tr class="border-b border-white/10">
                                    <th class="text-left py-3 px-2 text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase tracking-wider">Name</th>
                                    <th class="text-left py-3 px-2 text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase tracking-wider">Tutor</th>
                                    <th class="text-left py-3 px-2 text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase tracking-wider">Last Class</th>
                                    <th class="text-center py-3 px-2 text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase tracking-wider">Action</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-white/10">
                                @php
                                    $recentStudents = [
                                        ['name' => 'Tolu Williams', 'email' => 'tolu.williams@student.com', 'tutor' => 'Unassigned', 'lastClass' => '1 day ago'],
                                        ['name' => 'Chiamaka Williams', 'email' => 'chiamaka.williams@student.com', 'tutor' => 'Unassigned', 'lastClass' => '1 day ago'],
                                        ['name' => 'Ayo Balogun', 'email' => 'ayo.balogun@student.com', 'tutor' => 'Unassigned', 'lastClass' => '1 day ago'],
                                    ];
                                @endphp

                                @foreach($recentStudents as $student)
                                    <tr class="hover:bg-white/10 dark:hover:bg-gray-800/20 transition-colors">
                                        <td class="py-4 px-2">
                                            <div class="flex items-center gap-3">
                                                <div class="w-10 h-10 bg-gradient-to-r from-sky-500 to-cyan-400 rounded-full flex items-center justify-center text-white font-bold">
                                                    {{ substr($student['name'], 0, 1) }}
                                                </div>
                                                <div>
                                                    <div class="font-semibold text-gray-900 dark:text-white text-sm">{{ $student['name'] }}</div>
                                                    <div class="text-xs text-gray-500 dark:text-gray-400">{{ $student['email'] }}</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="py-4 px-2">
                                            <span class="text-sm text-gray-700 dark:text-gray-300">{{ $student['tutor'] }}</span>
                                        </td>
                                        <td class="py-4 px-2">
                                            <span class="text-sm text-gray-600 dark:text-gray-400">{{ $student['lastClass'] }}</span>
                                        </td>
                                        <td class="py-4 px-2 text-center">
                                            <button class="px-3 py-1 bg-sky-500/20 text-sky-600 dark:text-sky-400 rounded-lg hover:bg-sky-500/30 transition-all text-sm font-semibold">
                                                View
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4 text-center">
                        <a href="{{ route('manager.students.index') }}" class="text-sky-600 dark:text-sky-400 hover:underline font-semibold text-sm">
                            View All Students →
                        </a>
                    </div>
                </div>

                {{-- Recent Tutors Table --}}
                <div class="bg-white/20 dark:bg-gray-900/30 backdrop-blur-xl border border-white/10 rounded-2xl shadow-md p-6">
                    <div class="flex items-center justify-between mb-6">
                        <div class="flex items-center gap-3">
                            <div class="bg-gradient-to-r from-cyan-500 to-blue-500 p-2 rounded-lg">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                </svg>
                            </div>
                            <h3 class="text-xl font-bold text-gray-900 dark:text-white">Recent Tutors</h3>
                        </div>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead>
                                <tr class="border-b border-white/10">
                                    <th class="text-left py-3 px-2 text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase tracking-wider">Name</th>
                                    <th class="text-left py-3 px-2 text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase tracking-wider">Students</th>
                                    <th class="text-left py-3 px-2 text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase tracking-wider">Last Active</th>
                                    <th class="text-center py-3 px-2 text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase tracking-wider">Action</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-white/10">
                                @php
                                    $recentTutors = [
                                        ['name' => 'David Okoronkwo', 'email' => 'david.okoronkwo@kidstech.com', 'students' => '0 students', 'lastActive' => '1 day ago'],
                                        ['name' => 'Chioma Adebayo', 'email' => 'chioma.adebayo@kidstech.com', 'students' => '0 students', 'lastActive' => '1 day ago'],
                                        ['name' => 'Emmanuel Johnson', 'email' => 'emmanuel.johnson@kidstech.com', 'students' => '0 students', 'lastActive' => '1 day ago'],
                                    ];
                                @endphp

                                @foreach($recentTutors as $tutor)
                                    <tr class="hover:bg-white/10 dark:hover:bg-gray-800/20 transition-colors">
                                        <td class="py-4 px-2">
                                            <div class="flex items-center gap-3">
                                                <div class="w-10 h-10 bg-gradient-to-r from-cyan-500 to-blue-500 rounded-full flex items-center justify-center text-white font-bold">
                                                    {{ substr($tutor['name'], 0, 1) }}
                                                </div>
                                                <div>
                                                    <div class="font-semibold text-gray-900 dark:text-white text-sm">{{ $tutor['name'] }}</div>
                                                    <div class="text-xs text-gray-500 dark:text-gray-400">{{ $tutor['email'] }}</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="py-4 px-2">
                                            <span class="text-sm text-gray-700 dark:text-gray-300">{{ $tutor['students'] }}</span>
                                        </td>
                                        <td class="py-4 px-2">
                                            <span class="text-sm text-gray-600 dark:text-gray-400">{{ $tutor['lastActive'] }}</span>
                                        </td>
                                        <td class="py-4 px-2 text-center">
                                            <button class="px-3 py-1 bg-cyan-500/20 text-cyan-600 dark:text-cyan-400 rounded-lg hover:bg-cyan-500/30 transition-all text-sm font-semibold">
                                                View Profile
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4 text-center">
                        <a href="{{ route('manager.tutors.index') }}" class="text-cyan-600 dark:text-cyan-400 hover:underline font-semibold text-sm">
                            View All Tutors →
                        </a>
                    </div>
                </div>
            </div>

            {{-- Quick Actions --}}
            <div class="bg-white/20 dark:bg-gray-900/30 backdrop-blur-xl border border-white/10 rounded-2xl shadow-md p-6 mb-10">
                <div class="flex items-center gap-3 mb-6">
                    <div class="bg-gradient-to-r from-sky-500 to-cyan-400 p-2 rounded-lg">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 dark:text-white">Quick Actions</h3>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4">
                    {{-- View All Students --}}
                    <a href="{{ route('manager.students.index') }}" class="flex flex-col items-center justify-center p-6 bg-gradient-to-r from-sky-500 to-cyan-400 text-white rounded-xl shadow-lg hover:-translate-y-1 transition-all">
                        <svg class="w-10 h-10 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                        </svg>
                        <span class="font-semibold text-center">View All Students</span>
                    </a>

                    {{-- View All Tutors --}}
                    <a href="{{ route('manager.tutors.index') }}" class="flex flex-col items-center justify-center p-6 bg-gradient-to-r from-cyan-500 to-blue-500 text-white rounded-xl shadow-lg hover:-translate-y-1 transition-all">
                        <svg class="w-10 h-10 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                        <span class="font-semibold text-center">View All Tutors</span>
                    </a>

                    {{-- View Attendance --}}
                    <a href="{{ route('manager.attendance.index') }}" class="flex flex-col items-center justify-center p-6 bg-gradient-to-r from-amber-500 to-orange-400 text-white rounded-xl shadow-lg hover:-translate-y-1 transition-all">
                        <svg class="w-10 h-10 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        <span class="font-semibold text-center">View Attendance</span>
                    </a>

                    {{-- View Reports --}}
                    <a href="{{ route('manager.reports.index') }}" class="flex flex-col items-center justify-center p-6 bg-gradient-to-r from-purple-500 to-pink-500 text-white rounded-xl shadow-lg hover:-translate-y-1 transition-all">
                        <svg class="w-10 h-10 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        <span class="font-semibold text-center">View Reports</span>
                    </a>

                    {{-- Pending Attendance --}}
                    <a href="{{ route('manager.attendance.pending') }}" class="flex flex-col items-center justify-center p-6 bg-gradient-to-r from-rose-500 to-red-500 text-white rounded-xl shadow-lg hover:-translate-y-1 transition-all relative">
                        @php
                            $pendingCount = \App\Models\AttendanceRecord::where('status', 'pending')->count();
                        @endphp
                        @if($pendingCount > 0)
                        <div class="absolute -top-2 -right-2 bg-white text-rose-600 rounded-full w-8 h-8 flex items-center justify-center font-bold text-sm shadow-lg">
                            {{ $pendingCount }}
                        </div>
                        @endif
                        <svg class="w-10 h-10 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                        </svg>
                        <span class="font-semibold text-center">Pending Attendance</span>
                    </a>
                </div>
            </div>

        </div>
    </div>

    {{-- Todo List JavaScript --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const todoList = document.getElementById('todoList');
            const newTaskInput = document.getElementById('newTaskInput');
            const addTaskBtn = document.getElementById('addTaskBtn');

            // Default tasks
            const defaultTasks = [
                'Join today\'s classes',
                'Assess tutor performance',
                'Create assessment report',
                'Follow up with inactive students',
                'View pending assessments'
            ];

            // Load tasks from localStorage or use defaults
            function loadTasks() {
                const savedTasks = localStorage.getItem('managerTodos');
                return savedTasks ? JSON.parse(savedTasks) : defaultTasks.map((text, index) => ({
                    id: Date.now() + index,
                    text: text,
                    checked: false
                }));
            }

            // Save tasks to localStorage
            function saveTasks(tasks) {
                localStorage.setItem('managerTodos', JSON.stringify(tasks));
            }

            // Render tasks
            function renderTasks() {
                const tasks = loadTasks();
                todoList.innerHTML = '';

                tasks.forEach(task => {
                    const taskEl = document.createElement('label');
                    taskEl.className = 'flex items-center gap-3 p-3 bg-white/30 dark:bg-gray-800/30 backdrop-blur-sm rounded-lg border border-white/10 hover:bg-white/40 dark:hover:bg-gray-800/40 transition-all cursor-pointer group';

                    taskEl.innerHTML = `
                        <input type="checkbox" ${task.checked ? 'checked' : ''}
                            data-id="${task.id}"
                            class="task-checkbox w-5 h-5 text-sky-500 border-gray-300 rounded focus:ring-sky-500 focus:ring-2">
                        <span class="text-gray-700 dark:text-gray-300 flex-1 ${task.checked ? 'line-through opacity-60' : ''}">${task.text}</span>
                        <button class="remove-task opacity-0 group-hover:opacity-100 transition-opacity text-red-500 hover:text-red-700 p-1" data-id="${task.id}">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                            </svg>
                        </button>
                    `;

                    todoList.appendChild(taskEl);
                });

                // Add event listeners
                document.querySelectorAll('.task-checkbox').forEach(checkbox => {
                    checkbox.addEventListener('change', toggleTask);
                });

                document.querySelectorAll('.remove-task').forEach(btn => {
                    btn.addEventListener('click', removeTask);
                });
            }

            // Toggle task checked status
            function toggleTask(e) {
                const taskId = parseInt(e.target.dataset.id);
                const tasks = loadTasks();
                const task = tasks.find(t => t.id === taskId);
                if (task) {
                    task.checked = e.target.checked;
                    saveTasks(tasks);
                    renderTasks();
                }
            }

            // Remove task
            function removeTask(e) {
                e.preventDefault();
                const taskId = parseInt(e.currentTarget.dataset.id);
                let tasks = loadTasks();
                tasks = tasks.filter(t => t.id !== taskId);
                saveTasks(tasks);
                renderTasks();
            }

            // Add new task
            function addTask() {
                const taskText = newTaskInput.value.trim();
                if (taskText) {
                    const tasks = loadTasks();
                    tasks.push({
                        id: Date.now(),
                        text: taskText,
                        checked: false
                    });
                    saveTasks(tasks);
                    newTaskInput.value = '';
                    renderTasks();
                }
            }

            // Event listeners
            addTaskBtn.addEventListener('click', addTask);
            newTaskInput.addEventListener('keypress', function(e) {
                if (e.key === 'Enter') {
                    addTask();
                }
            });

            // Initial render
            renderTasks();
        });
    </script>

    {{-- Custom Animations --}}
    <style>
        @keyframes float {
            0%, 100% {
                transform: translateY(0px);
            }
            50% {
                transform: translateY(-20px);
            }
        }

        .animate-float {
            animation: float 6s ease-in-out infinite;
        }

        /* Smooth scrollbar for schedule */
        .overflow-y-auto::-webkit-scrollbar {
            width: 8px;
        }

        .overflow-y-auto::-webkit-scrollbar-track {
            background: rgba(255, 255, 255, 0.1);
            border-radius: 10px;
        }

        .overflow-y-auto::-webkit-scrollbar-thumb {
            background: rgba(14, 165, 233, 0.5);
            border-radius: 10px;
        }

        .overflow-y-auto::-webkit-scrollbar-thumb:hover {
            background: rgba(14, 165, 233, 0.7);
        }

        /* Focus visible styles for accessibility */
        *:focus-visible {
            outline: 2px solid #0ea5e9;
            outline-offset: 2px;
            border-radius: 0.375rem;
        }
    </style>
</x-app-layout>
