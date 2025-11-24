<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-2xl text-white">
            {{ __('Tutor Dashboard') }}
        </h2>
    </x-slot>

    <x-slot name="title">{{ __('Tutor Dashboard') }}</x-slot>

    <div class="min-h-screen bg-gradient-to-br from-purple-50 via-pink-50 to-rose-50 dark:from-gray-900 dark:via-gray-800 dark:to-gray-900 py-12 relative overflow-hidden">
        <!-- Floating Orbs -->
        <div class="absolute top-0 left-0 w-72 h-72 bg-purple-300 dark:bg-purple-900 rounded-full mix-blend-multiply filter blur-xl opacity-70 animate-float"></div>
        <div class="absolute top-0 right-0 w-72 h-72 bg-pink-300 dark:bg-pink-900 rounded-full mix-blend-multiply filter blur-xl opacity-70 animate-float" style="animation-delay: 2s;"></div>
        <div class="absolute -bottom-8 left-20 w-72 h-72 bg-rose-300 dark:bg-rose-900 rounded-full mix-blend-multiply filter blur-xl opacity-70 animate-float" style="animation-delay: 4s;"></div>

        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 relative z-10">

            <!-- Welcome Section -->
            <x-ui.glass-card padding="p-8" class="mb-24">
                <div class="flex items-center justify-between flex-wrap">
                    <div>
                        <h3 class="text-3xl font-bold text-gray-900 dark:text-white mb-2">
                            Welcome back, <span class="text-transparent bg-clip-text bg-gradient-tutor">{{ $tutor ? $tutor->first_name . ' ' . $tutor->last_name : Auth::user()->name }}</span>!
                        </h3>
                        <p class="text-gray-600 dark:text-gray-300 text-lg">Inspire and educate your students today.</p>
                    </div>
                    <div class="text-right mt-4 md:mt-0">
                        <div class="text-gray-600 dark:text-gray-300">{{ now()->format('l, F j, Y') }}</div>
                        <div class="text-gray-500 dark:text-gray-400 text-sm">{{ now()->format('g:i A') }}</div>
                    </div>
                </div>
            </x-ui.glass-card>

            <!-- Stats Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-12">
                <x-ui.stat-card
                    title="My Students"
                    value="{{ $totalStudents ?? 0 }}"
                    icon='<svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>'
                    gradient="bg-gradient-tutor"
                />

                <x-ui.stat-card
                    title="My Reports"
                    value="{{ $myReports ?? 0 }}"
                    icon='<svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>'
                    gradient="bg-gradient-to-br from-purple-500 to-pink-600"
                />

                <x-ui.stat-card
                    title="Avg Attendance"
                    value="{{ $avgAttendance ?? 0 }}%"
                    icon='<svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>'
                    gradient="bg-gradient-to-br from-pink-500 to-rose-600"
                />

                <x-ui.stat-card
                    title="Classes Today"
                    value="{{ $classesToday ?? 0 }}"
                    icon='<svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>'
                    gradient="bg-gradient-to-br from-rose-500 to-red-600"
                />
            </div>

            <!-- Content Grid -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-12">

                <!-- Today's Classes -->
                <x-ui.glass-card>
                    <x-ui.section-title>Today's Classes</x-ui.section-title>
                    <div class="space-y-3 mt-4">
                        <div class="p-4 rounded-xl bg-white/30 dark:bg-gray-800/30 border border-white/20 dark:border-gray-700/20">
                            <div class="flex justify-between">
                                <div>
                                    <h4 class="font-semibold text-gray-900 dark:text-white">Python Programming</h4>
                                    <p class="text-sm text-gray-600 dark:text-gray-400">Beginner Level - 12 students</p>
                                </div>
                                <span class="text-sm font-semibold text-purple-600 dark:text-purple-400">10:00 AM</span>
                            </div>
                        </div>
                        <div class="p-4 rounded-xl bg-white/30 dark:bg-gray-800/30 border border-white/20 dark:border-gray-700/20">
                            <div class="flex justify-between">
                                <div>
                                    <h4 class="font-semibold text-gray-900 dark:text-white">Web Development</h4>
                                    <p class="text-sm text-gray-600 dark:text-gray-400">Intermediate - 8 students</p>
                                </div>
                                <span class="text-sm font-semibold text-purple-600 dark:text-purple-400">2:00 PM</span>
                            </div>
                        </div>
                    </div>
                </x-ui.glass-card>

                <!-- My Reports -->
                <x-ui.glass-card>
                    <x-ui.section-title>Recent Reports</x-ui.section-title>
                    <div class="space-y-3 mt-4">
                        <div class="p-4 rounded-xl bg-white/30 dark:bg-gray-800/30 border-l-4 border-purple-500">
                            <div class="flex justify-between items-center">
                                <div>
                                    <h4 class="font-semibold text-gray-900 dark:text-white">Weekly Progress Report</h4>
                                    <p class="text-sm text-gray-600 dark:text-gray-400">Submitted 2 days ago</p>
                                </div>
                                <span class="px-3 py-1 rounded-full bg-green-100 dark:bg-green-900 text-green-700 dark:text-green-300 text-xs font-semibold">Approved</span>
                            </div>
                        </div>
                        <div class="p-4 rounded-xl bg-white/30 dark:bg-gray-800/30 border-l-4 border-yellow-500">
                            <div class="flex justify-between items-center">
                                <div>
                                    <h4 class="font-semibold text-gray-900 dark:text-white">Monthly Assessment</h4>
                                    <p class="text-sm text-gray-600 dark:text-gray-400">Submitted yesterday</p>
                                </div>
                                <span class="px-3 py-1 rounded-full bg-yellow-100 dark:bg-yellow-900 text-yellow-700 dark:text-yellow-300 text-xs font-semibold">Pending</span>
                            </div>
                        </div>
                    </div>
                </x-ui.glass-card>

            </div>

            <!-- Quick Actions -->
            <x-ui.glass-card padding="p-8">
                <x-ui.section-title>Quick Actions</x-ui.section-title>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mt-6">
                    <x-ui.gradient-button href="{{ route('attendance.create') }}" gradient="bg-gradient-tutor">
                        Mark Attendance
                    </x-ui.gradient-button>
                    <x-ui.gradient-button href="{{ route('reports.create') }}" gradient="bg-gradient-to-r from-purple-500 to-pink-600">
                        Submit Report
                    </x-ui.gradient-button>
                    <x-ui.gradient-button href="{{ route('students.index') }}" gradient="bg-gradient-to-r from-pink-500 to-rose-600">
                        View Students
                    </x-ui.gradient-button>
                    <x-ui.gradient-button href="{{ route('classes.index') }}" gradient="bg-gradient-to-r from-rose-500 to-red-600">
                        My Classes
                    </x-ui.gradient-button>
                </div>
            </x-ui.glass-card>

        </div>
    </div>
</x-app-layout>
