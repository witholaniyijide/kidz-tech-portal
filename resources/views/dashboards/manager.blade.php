<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-2xl text-white">
            {{ __('Manager Dashboard') }}
        </h2>
    </x-slot>

    <x-slot name="title">{{ __('Manager Dashboard') }}</x-slot>

    <div class="min-h-screen bg-gradient-to-br from-green-50 via-emerald-50 to-teal-50 dark:from-gray-900 dark:via-gray-800 dark:to-gray-900 py-12 relative overflow-hidden">
        <!-- Floating Orbs -->
        <div class="absolute top-0 left-0 w-72 h-72 bg-green-300 dark:bg-green-900 rounded-full mix-blend-multiply filter blur-xl opacity-70 animate-float"></div>
        <div class="absolute top-0 right-0 w-72 h-72 bg-emerald-300 dark:bg-emerald-900 rounded-full mix-blend-multiply filter blur-xl opacity-70 animate-float" style="animation-delay: 2s;"></div>
        <div class="absolute -bottom-8 left-20 w-72 h-72 bg-teal-300 dark:bg-teal-900 rounded-full mix-blend-multiply filter blur-xl opacity-70 animate-float" style="animation-delay: 4s;"></div>

        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 relative z-10">

            <!-- Welcome Section -->
            <x-ui.glass-card padding="p-8" class="mb-8">
                <h3 class="text-3xl font-bold text-gray-900 dark:text-white mb-2">
                    Welcome back, <span class="text-transparent bg-clip-text bg-gradient-manager">{{ Auth::user()->name }}</span>!
                </h3>
                <p class="text-gray-600 dark:text-gray-300 text-lg">Oversee operations and manage your team effectively.</p>
            </x-ui.glass-card>

            <!-- Stats Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <x-ui.stat-card
                    title="Total Students"
                    value="{{ $totalStudents ?? 0 }}"
                    icon='<svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>'
                    gradient="bg-gradient-manager"
                />

                <x-ui.stat-card
                    title="Total Tutors"
                    value="{{ $totalTutors ?? 0 }}"
                    icon='<svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>'
                    gradient="bg-gradient-to-br from-green-500 to-emerald-600"
                />

                <x-ui.stat-card
                    title="Active Classes"
                    value="{{ $totalClasses ?? 0 }}"
                    icon='<svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>'
                    gradient="bg-gradient-to-br from-teal-500 to-cyan-600"
                />

                <x-ui.stat-card
                    title="Attendance Rate"
                    value="{{ $attendanceRate ?? 0 }}%"
                    icon='<svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>'
                    gradient="bg-gradient-to-br from-blue-500 to-indigo-600"
                />
            </div>

            <!-- Content Grid -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">

                <!-- Today's Schedule -->
                <x-ui.glass-card>
                    <x-ui.section-title>Today's Schedule</x-ui.section-title>
                    <div class="space-y-3 mt-4">
                        <div class="p-4 rounded-xl bg-white/30 dark:bg-gray-800/30 border border-white/20 dark:border-gray-700/20">
                            <div class="flex justify-between">
                                <div>
                                    <h4 class="font-semibold text-gray-900 dark:text-white">Morning Briefing</h4>
                                    <p class="text-sm text-gray-600 dark:text-gray-400">Team meeting</p>
                                </div>
                                <span class="text-sm font-semibold text-green-600 dark:text-green-400">9:00 AM</span>
                            </div>
                        </div>
                        <div class="p-4 rounded-xl bg-white/30 dark:bg-gray-800/30 border border-white/20 dark:border-gray-700/20">
                            <div class="flex justify-between">
                                <div>
                                    <h4 class="font-semibold text-gray-900 dark:text-white">Class Review</h4>
                                    <p class="text-sm text-gray-600 dark:text-gray-400">Performance analysis</p>
                                </div>
                                <span class="text-sm font-semibold text-green-600 dark:text-green-400">2:00 PM</span>
                            </div>
                        </div>
                    </div>
                </x-ui.glass-card>

                <!-- Team Overview -->
                <x-ui.glass-card>
                    <x-ui.section-title>Team Overview</x-ui.section-title>
                    <div class="space-y-3 mt-4">
                        <div class="flex items-center justify-between p-3 rounded-lg bg-white/30 dark:bg-gray-800/30">
                            <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Active Tutors</span>
                            <span class="px-3 py-1 rounded-full bg-green-100 dark:bg-green-900 text-green-700 dark:text-green-300 text-sm font-semibold">{{ $activeTutors ?? 0 }}</span>
                        </div>
                        <div class="flex items-center justify-between p-3 rounded-lg bg-white/30 dark:bg-gray-800/30">
                            <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Pending Reports</span>
                            <span class="px-3 py-1 rounded-full bg-yellow-100 dark:bg-yellow-900 text-yellow-700 dark:text-yellow-300 text-sm font-semibold">{{ $pendingReports ?? 0 }}</span>
                        </div>
                        <div class="flex items-center justify-between p-3 rounded-lg bg-white/30 dark:bg-gray-800/30">
                            <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Tasks Completed</span>
                            <span class="px-3 py-1 rounded-full bg-blue-100 dark:bg-blue-900 text-blue-700 dark:text-blue-300 text-sm font-semibold">{{ $completedTasks ?? 0 }}</span>
                        </div>
                    </div>
                </x-ui.glass-card>

            </div>

            <!-- Quick Actions -->
            <x-ui.glass-card padding="p-8">
                <x-ui.section-title>Quick Actions</x-ui.section-title>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mt-6">
                    <x-ui.gradient-button href="{{ route('classes.index') }}" gradient="bg-gradient-manager">
                        Manage Classes
                    </x-ui.gradient-button>
                    <x-ui.gradient-button href="{{ route('tutors.index') }}" gradient="bg-gradient-to-r from-green-500 to-emerald-600">
                        View Tutors
                    </x-ui.gradient-button>
                    <x-ui.gradient-button href="{{ route('reports.index') }}" gradient="bg-gradient-to-r from-teal-500 to-cyan-600">
                        View Reports
                    </x-ui.gradient-button>
                    <x-ui.gradient-button href="{{ route('attendance.index') }}" gradient="bg-gradient-to-r from-blue-500 to-indigo-600">
                        Attendance
                    </x-ui.gradient-button>
                </div>
            </x-ui.glass-card>

        </div>
    </div>
</x-app-layout>
