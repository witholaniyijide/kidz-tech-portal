<x-app-layout>
    <x-slot name="header">
        {{ __('Tutor Details') }}
    </x-slot>
    <x-slot name="title">{{ $tutor->first_name }} {{ $tutor->last_name }}</x-slot>

    <div class="min-h-screen bg-gradient-to-br from-blue-50 via-purple-50 to-pink-50 dark:from-gray-900 dark:via-gray-800 dark:to-gray-900 py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Action Buttons -->
            <div class="flex justify-end space-x-3 mb-4">
                <a href="{{ route('director.tutors.edit', $tutor) }}" class="inline-flex items-center px-4 py-2 bg-yellow-500 hover:bg-yellow-600 text-white font-semibold rounded-lg transition-all">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                    Edit
                </a>
                <a href="{{ route('director.tutors.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white font-semibold rounded-lg transition-all">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                    Back
                </a>
            </div>
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Profile Card -->
                <div class="lg:col-span-1">
                    <x-ui.glass-card>
                        <div class="text-center">
                            <div class="w-24 h-24 mx-auto rounded-full bg-gradient-to-br from-purple-500 to-pink-600 flex items-center justify-center text-white text-3xl font-bold">
                                {{ strtoupper(substr($tutor->first_name, 0, 1) . substr($tutor->last_name, 0, 1)) }}
                            </div>
                            <h3 class="mt-4 text-xl font-bold text-gray-900 dark:text-white">{{ $tutor->first_name }} {{ $tutor->last_name }}</h3>
                            <p class="text-gray-500 dark:text-gray-400">{{ $tutor->tutor_id }}</p>
                            <div class="mt-2"><x-ui.status-badge :status="$tutor->status" /></div>
                        </div>

                        <div class="mt-6 space-y-4">
                            @if($tutor->email)
                            <div class="flex items-center text-gray-600 dark:text-gray-300">
                                <svg class="w-5 h-5 mr-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                                {{ $tutor->email }}
                            </div>
                            @endif
                            @if($tutor->phone)
                            <div class="flex items-center text-gray-600 dark:text-gray-300">
                                <svg class="w-5 h-5 mr-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                                {{ $tutor->phone }}
                            </div>
                            @endif
                            @if($tutor->specialization)
                            <div class="flex items-center text-gray-600 dark:text-gray-300">
                                <svg class="w-5 h-5 mr-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/></svg>
                                {{ $tutor->specialization }}
                            </div>
                            @endif
                        </div>
                    </x-ui.glass-card>

                    <!-- Stats -->
                    <x-ui.glass-card class="mt-6">
                        <h4 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Statistics</h4>
                        <div class="space-y-4">
                            <div class="flex justify-between items-center">
                                <span class="text-gray-600 dark:text-gray-400">Total Students</span>
                                <span class="font-semibold text-gray-900 dark:text-white">{{ $totalStudents }}</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-gray-600 dark:text-gray-400">Total Reports</span>
                                <span class="font-semibold text-gray-900 dark:text-white">{{ $totalReports }}</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-gray-600 dark:text-gray-400">Pending Reports</span>
                                <span class="font-semibold text-yellow-600">{{ $pendingReports }}</span>
                            </div>
                        </div>
                    </x-ui.glass-card>
                </div>

                <!-- Details -->
                <div class="lg:col-span-2 space-y-6">
                    <x-ui.glass-card>
                        <h4 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Professional Information</h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <p class="text-sm text-gray-500 dark:text-gray-400">Hire Date</p>
                                <p class="font-medium text-gray-900 dark:text-white">{{ $tutor->hire_date ? \Carbon\Carbon::parse($tutor->hire_date)->format('M d, Y') : 'Not set' }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500 dark:text-gray-400">Hourly Rate</p>
                                <p class="font-medium text-gray-900 dark:text-white">{{ $tutor->hourly_rate ? '₦' . number_format($tutor->hourly_rate, 2) : 'Not set' }}</p>
                            </div>
                        </div>
                        @if($tutor->bio)
                        <div class="mt-4">
                            <p class="text-sm text-gray-500 dark:text-gray-400">Bio</p>
                            <p class="text-gray-600 dark:text-gray-300 mt-1">{{ $tutor->bio }}</p>
                        </div>
                        @endif
                    </x-ui.glass-card>

                    <!-- Assigned Students -->
                    <x-ui.glass-card>
                        <h4 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Assigned Students ({{ $tutor->students->count() }})</h4>
                        @if($tutor->students->count() > 0)
                        <div class="space-y-3">
                            @foreach($tutor->students->take(5) as $student)
                            <div class="flex items-center p-3 bg-gray-50 dark:bg-gray-700/50 rounded-lg">
                                <div class="w-10 h-10 rounded-full bg-gradient-to-br from-blue-500 to-cyan-600 flex items-center justify-center text-white font-semibold">
                                    {{ strtoupper(substr($student->first_name, 0, 1)) }}
                                </div>
                                <div class="ml-3 flex-1">
                                    <p class="font-medium text-gray-900 dark:text-white">{{ $student->first_name }} {{ $student->last_name }}</p>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">{{ $student->current_level ?? 'No level set' }}</p>
                                </div>
                                <x-ui.status-badge :status="$student->status" />
                            </div>
                            @endforeach
                        </div>
                        @if($tutor->students->count() > 5)
                        <p class="text-sm text-gray-500 dark:text-gray-400 mt-3">And {{ $tutor->students->count() - 5 }} more...</p>
                        @endif
                        @else
                        <p class="text-gray-500 dark:text-gray-400">No students assigned yet.</p>
                        @endif
                    </x-ui.glass-card>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
