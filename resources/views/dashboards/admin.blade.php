<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-2xl text-white">
            {{ __('Admin Dashboard') }}
        </h2>
    </x-slot>

    <x-slot name="title">{{ __('Admin Dashboard') }}</x-slot>

    {{-- Animated Background --}}
    <div class="min-h-screen bg-gradient-to-br from-teal-50 via-cyan-50 to-blue-50 dark:from-slate-900 dark:via-slate-800 dark:to-slate-900 py-12 relative overflow-hidden">
        {{-- Floating Orbs Background --}}
        <div class="absolute top-0 left-0 w-72 h-72 bg-teal-300 dark:bg-teal-900 rounded-full mix-blend-multiply filter blur-xl opacity-70 animate-float"></div>
        <div class="absolute top-0 right-0 w-72 h-72 bg-cyan-300 dark:bg-cyan-900 rounded-full mix-blend-multiply filter blur-xl opacity-70 animate-float" style="animation-delay: 2s;"></div>
        <div class="absolute -bottom-8 left-20 w-72 h-72 bg-blue-300 dark:bg-blue-900 rounded-full mix-blend-multiply filter blur-xl opacity-70 animate-float" style="animation-delay: 4s;"></div>

        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 relative z-10">

            {{-- Welcome Banner --}}
            <x-ui.glass-card padding="p-8" class="mb-24">
                <div class="flex items-center justify-between flex-wrap">
                    <div>
                        <h3 class="text-3xl font-bold text-gray-900 dark:text-white mb-2">
                            Welcome back, <span class="text-transparent bg-clip-text bg-gradient-admin">{{ auth()->user()->name }}</span>!
                        </h3>
                        <p class="text-gray-600 dark:text-gray-300 text-lg">Here's what's happening with your organization today.</p>
                    </div>
                    <div class="text-right mt-4 md:mt-0">
                        <div class="text-gray-600 dark:text-gray-300">{{ now()->format('l, F j, Y') }}</div>
                        <div class="text-gray-500 dark:text-gray-400 text-sm">{{ now()->format('g:i A') }}</div>
                    </div>
                </div>
            </x-ui.glass-card>

            {{-- Main Statistics --}}
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-12">
                <x-ui.stat-card
                    title="Total Students"
                    value="{{ $stats['totalStudents'] ?? 0 }}"
                    subtitle="{{ ($stats['activeStudents'] ?? 0) }} active • {{ (($stats['totalStudents'] ?? 0) - ($stats['activeStudents'] ?? 0)) }} inactive"
                    icon='<svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>'
                    gradient="bg-gradient-admin"
                />

                <x-ui.stat-card
                    title="Total Tutors"
                    value="{{ $stats['totalTutors'] ?? 0 }}"
                    subtitle="{{ ($stats['activeTutors'] ?? 0) }} active • {{ ($stats['inactiveTutors'] ?? 0) }} inactive • {{ ($stats['onLeaveTutors'] ?? 0) }} on leave"
                    icon='<svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>'
                    gradient="bg-gradient-to-br from-teal-500 to-cyan-600"
                />

                <x-ui.stat-card
                    title="Today's Classes"
                    value="{{ $stats['todayClasses'] ?? 0 }}"
                    subtitle="{{ ($stats['completedClasses'] ?? 0) }} completed • {{ ($stats['upcomingClasses'] ?? 0) }} upcoming"
                    icon='<svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>'
                    gradient="bg-gradient-to-br from-blue-500 to-indigo-600"
                />

                <x-ui.stat-card
                    title="Pending Attendance"
                    value="{{ $stats['pendingAttendance'] ?? 0 }}"
                    subtitle="Awaiting approval"
                    icon='<svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>'
                    gradient="bg-gradient-to-br from-orange-500 to-red-600"
                />
            </div>

            {{-- Daily Class Schedule & To-Do List --}}
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
                {{-- Daily Class Schedule --}}
                <div class="lg:col-span-2">
                    <x-ui.glass-card>
                        <x-ui.section-title>Today's Schedule</x-ui.section-title>
                        @if(isset($classes) && count($classes) > 0)
                            <div class="space-y-3">
                                @foreach($classes as $class)
                                <div class="p-4 rounded-xl bg-white/30 dark:bg-gray-800/30 border border-white/20 dark:border-gray-700/20">
                                    <div class="flex justify-between items-center">
                                        <div>
                                            <h4 class="font-semibold text-gray-900 dark:text-white">{{ $class->name ?? 'Class' }}</h4>
                                            <p class="text-sm text-gray-600 dark:text-gray-400">{{ $class->tutor ?? 'Tutor Name' }}</p>
                                        </div>
                                        <div class="text-right">
                                            <p class="text-sm font-semibold text-teal-600 dark:text-teal-400">{{ $class->time ?? '10:00 AM' }}</p>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        @else
                            <p class="text-gray-600 dark:text-gray-400 text-center py-8">No classes scheduled for today.</p>
                        @endif
                    </x-ui.glass-card>
                </div>

                {{-- To-Do List --}}
                <div class="lg:col-span-1">
                    <x-ui.glass-card>
                        <x-ui.section-title>To-Do List</x-ui.section-title>
                        @if(isset($todos) && count($todos) > 0)
                            <div class="space-y-2">
                                @foreach($todos as $todo)
                                <div class="flex items-center p-3 rounded-lg bg-white/30 dark:bg-gray-800/30">
                                    <input type="checkbox" class="rounded text-teal-600 mr-3" {{ $todo->completed ?? false ? 'checked' : '' }}>
                                    <span class="text-sm text-gray-700 dark:text-gray-300">{{ $todo->task ?? 'Task' }}</span>
                                </div>
                                @endforeach
                            </div>
                        @else
                            <p class="text-gray-600 dark:text-gray-400 text-center py-8">No pending tasks.</p>
                        @endif
                    </x-ui.glass-card>
                </div>
            </div>

            {{-- Notice Board & Quick Actions --}}
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
                <x-ui.glass-card>
                    <x-ui.section-title>Notice Board</x-ui.section-title>
                    @if(isset($notices) && count($notices) > 0)
                        <div class="space-y-3">
                            @foreach($notices as $notice)
                            <div class="p-4 rounded-xl bg-white/30 dark:bg-gray-800/30 border-l-4 border-teal-500">
                                <h4 class="font-semibold text-gray-900 dark:text-white">{{ $notice->title ?? 'Notice' }}</h4>
                                <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">{{ $notice->content ?? 'Notice content' }}</p>
                            </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-gray-600 dark:text-gray-400 text-center py-8">No notices available.</p>
                    @endif
                </x-ui.glass-card>

                <x-ui.glass-card>
                    <x-ui.section-title>Quick Actions</x-ui.section-title>
                    <div class="grid grid-cols-2 gap-4 mt-4">
                        <x-ui.gradient-button href="{{ route('students.index') }}" gradient="bg-gradient-admin">
                            Manage Students
                        </x-ui.gradient-button>
                        <x-ui.gradient-button href="{{ route('tutors.index') }}" gradient="bg-gradient-to-r from-teal-500 to-cyan-600">
                            Manage Tutors
                        </x-ui.gradient-button>
                        <x-ui.gradient-button href="{{ route('classes.index') }}" gradient="bg-gradient-to-r from-blue-500 to-indigo-600">
                            View Classes
                        </x-ui.gradient-button>
                        <x-ui.gradient-button href="{{ route('reports.index') }}" gradient="bg-gradient-to-r from-purple-500 to-pink-600">
                            View Reports
                        </x-ui.gradient-button>
                    </div>
                </x-ui.glass-card>
            </div>

        </div>
    </div>
</x-app-layout>
