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
            <x-ui.glass-card padding="p-8" class="mb-8">
                <h3 class="text-3xl font-bold text-gray-900 dark:text-white mb-2">
                    Welcome back, <span class="text-transparent bg-clip-text bg-gradient-admin">{{ auth()->user()->name }}</span>!
                </h3>
                <p class="text-gray-600 dark:text-gray-300 text-lg">Manage your platform efficiently from here.</p>
            </x-ui.glass-card>

            {{-- Main Statistics --}}
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <x-ui.stat-card
                    title="Total Students"
                    value="{{ $stats['total_students'] ?? 0 }}"
                    icon='<svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>'
                    gradient="bg-gradient-admin"
                />

                <x-ui.stat-card
                    title="Total Tutors"
                    value="{{ $stats['total_tutors'] ?? 0 }}"
                    icon='<svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>'
                    gradient="bg-gradient-to-br from-teal-500 to-cyan-600"
                />

                <x-ui.stat-card
                    title="Active Classes"
                    value="{{ $stats['active_classes'] ?? 0 }}"
                    icon='<svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>'
                    gradient="bg-gradient-to-br from-blue-500 to-indigo-600"
                />

                <x-ui.stat-card
                    title="Pending Tasks"
                    value="{{ $stats['pending_tasks'] ?? 0 }}"
                    icon='<svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>'
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
