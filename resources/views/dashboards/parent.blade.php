<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-2xl text-white">
            {{ __('Parent Dashboard') }}
        </h2>
    </x-slot>

    <x-slot name="title">{{ __('Parent Dashboard') }}</x-slot>

    <div class="min-h-screen bg-gradient-to-br from-rose-50 via-pink-50 to-red-50 dark:from-gray-900 dark:via-gray-800 dark:to-gray-900 py-12 relative overflow-hidden">
        <!-- Floating Orbs -->
        <div class="absolute top-0 left-0 w-72 h-72 bg-rose-300 dark:bg-rose-900 rounded-full mix-blend-multiply filter blur-xl opacity-70 animate-float"></div>
        <div class="absolute top-0 right-0 w-72 h-72 bg-pink-300 dark:bg-pink-900 rounded-full mix-blend-multiply filter blur-xl opacity-70 animate-float" style="animation-delay: 2s;"></div>
        <div class="absolute -bottom-8 left-20 w-72 h-72 bg-red-300 dark:bg-red-900 rounded-full mix-blend-multiply filter blur-xl opacity-70 animate-float" style="animation-delay: 4s;"></div>

        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 relative z-10">

            <!-- Welcome Section -->
            <x-ui.glass-card padding="p-8" class="mb-16">
                <div class="flex items-center justify-between flex-wrap">
                    <div>
                        <h3 class="text-3xl font-bold text-gray-900 dark:text-white mb-2">
                            Welcome back, <span class="text-transparent bg-clip-text bg-gradient-parent">{{ Auth::user()->name }}</span>!
                        </h3>
                        <p class="text-gray-600 dark:text-gray-300 text-lg">Track your child's progress and stay connected with their education.</p>
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
                    title="My Children"
                    value="{{ $totalChildren ?? 0 }}"
                    icon='<svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>'
                    gradient="bg-gradient-parent"
                />

                <x-ui.stat-card
                    title="Attendance Rate"
                    value="{{ $attendanceRate ?? 0 }}%"
                    icon='<svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>'
                    gradient="bg-gradient-to-br from-rose-500 to-pink-600"
                />

                <x-ui.stat-card
                    title="Active Classes"
                    value="{{ $activeClasses ?? 0 }}"
                    icon='<svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>'
                    gradient="bg-gradient-to-br from-pink-500 to-rose-600"
                />

                <x-ui.stat-card
                    title="Upcoming Events"
                    value="{{ $upcomingEvents ?? 0 }}"
                    icon='<svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>'
                    gradient="bg-gradient-to-br from-red-500 to-orange-600"
                />
            </div>

            <!-- Content Grid -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-12">

                <!-- Children Overview -->
                <x-ui.glass-card>
                    <x-ui.section-title>My Children</x-ui.section-title>
                    <div class="space-y-3 mt-4">
                        @if(isset($children) && count($children) > 0)
                            @foreach($children as $child)
                            <div class="p-4 rounded-xl bg-white/30 dark:bg-gray-800/30 border border-white/20 dark:border-gray-700/20">
                                <div class="flex justify-between items-center">
                                    <div>
                                        <h4 class="font-semibold text-gray-900 dark:text-white">{{ $child->name ?? 'Student Name' }}</h4>
                                        <p class="text-sm text-gray-600 dark:text-gray-400">{{ $child->class ?? 'Class Name' }}</p>
                                    </div>
                                    <div class="text-right">
                                        <span class="px-3 py-1 rounded-full bg-green-100 dark:bg-green-900 text-green-700 dark:text-green-300 text-xs font-semibold">
                                            {{ $child->attendance ?? '95' }}% Attendance
                                        </span>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        @else
                            <p class="text-gray-600 dark:text-gray-400 text-center py-8">No children enrolled yet.</p>
                        @endif
                    </div>
                </x-ui.glass-card>

                <!-- Recent Updates -->
                <x-ui.glass-card>
                    <x-ui.section-title>Recent Updates</x-ui.section-title>
                    <div class="space-y-3 mt-4">
                        <div class="p-4 rounded-xl bg-white/30 dark:bg-gray-800/30 border-l-4 border-rose-500">
                            <h4 class="font-semibold text-gray-900 dark:text-white">Progress Report Available</h4>
                            <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Your child's monthly progress report is now available.</p>
                            <p class="text-xs text-gray-500 dark:text-gray-500 mt-2">2 days ago</p>
                        </div>
                        <div class="p-4 rounded-xl bg-white/30 dark:bg-gray-800/30 border-l-4 border-blue-500">
                            <h4 class="font-semibold text-gray-900 dark:text-white">Class Schedule Update</h4>
                            <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">New class schedule for next week has been posted.</p>
                            <p class="text-xs text-gray-500 dark:text-gray-500 mt-2">5 days ago</p>
                        </div>
                    </div>
                </x-ui.glass-card>

            </div>

            <!-- Schedule & Notices -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-12">

                <!-- This Week's Schedule -->
                <x-ui.glass-card>
                    <x-ui.section-title>This Week's Schedule</x-ui.section-title>
                    <div class="space-y-3 mt-4">
                        <div class="p-4 rounded-xl bg-white/30 dark:bg-gray-800/30 border border-white/20 dark:border-gray-700/20">
                            <div class="flex justify-between">
                                <div>
                                    <h4 class="font-semibold text-gray-900 dark:text-white">Python Programming</h4>
                                    <p class="text-sm text-gray-600 dark:text-gray-400">Monday & Wednesday</p>
                                </div>
                                <span class="text-sm font-semibold text-rose-600 dark:text-rose-400">10:00 AM</span>
                            </div>
                        </div>
                        <div class="p-4 rounded-xl bg-white/30 dark:bg-gray-800/30 border border-white/20 dark:border-gray-700/20">
                            <div class="flex justify-between">
                                <div>
                                    <h4 class="font-semibold text-gray-900 dark:text-white">Web Development</h4>
                                    <p class="text-sm text-gray-600 dark:text-gray-400">Tuesday & Thursday</p>
                                </div>
                                <span class="text-sm font-semibold text-rose-600 dark:text-rose-400">2:00 PM</span>
                            </div>
                        </div>
                    </div>
                </x-ui.glass-card>

                <!-- Important Notices -->
                <x-ui.glass-card>
                    <x-ui.section-title>Important Notices</x-ui.section-title>
                    <div class="space-y-3 mt-4">
                        <div class="p-4 rounded-xl bg-white/30 dark:bg-gray-800/30 border-l-4 border-yellow-500">
                            <h4 class="font-semibold text-gray-900 dark:text-white">Payment Reminder</h4>
                            <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Monthly tuition payment due by end of month.</p>
                        </div>
                        <div class="p-4 rounded-xl bg-white/30 dark:bg-gray-800/30 border-l-4 border-green-500">
                            <h4 class="font-semibold text-gray-900 dark:text-white">Parent-Teacher Meeting</h4>
                            <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Scheduled for next Friday at 3:00 PM.</p>
                        </div>
                    </div>
                </x-ui.glass-card>

            </div>

            <!-- Quick Actions -->
            <x-ui.glass-card padding="p-8">
                <x-ui.section-title>Quick Actions</x-ui.section-title>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mt-6">
                    <x-ui.gradient-button href="{{ route('reports.index') }}" gradient="bg-gradient-parent">
                        View Reports
                    </x-ui.gradient-button>
                    <x-ui.gradient-button href="{{ route('attendance.index') }}" gradient="bg-gradient-to-r from-rose-500 to-pink-600">
                        Check Attendance
                    </x-ui.gradient-button>
                    <x-ui.gradient-button href="{{ route('payments.index') }}" gradient="bg-gradient-to-r from-pink-500 to-rose-600">
                        Payments
                    </x-ui.gradient-button>
                    <x-ui.gradient-button href="{{ route('messages.index') }}" gradient="bg-gradient-to-r from-red-500 to-orange-600">
                        Messages
                    </x-ui.gradient-button>
                </div>
            </x-ui.glass-card>

        </div>
    </div>
</x-app-layout>
