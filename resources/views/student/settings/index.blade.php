@extends('layouts.student')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-sky-50 via-cyan-50 to-blue-50 dark:from-gray-900 dark:via-gray-900 dark:to-gray-900 py-8">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">

        <!-- Header -->
        <div class="mb-8">
            <div class="flex items-center justify-between mb-2">
                <h1 class="text-3xl font-bold text-transparent bg-clip-text bg-gradient-to-r from-sky-600 to-cyan-600 dark:from-sky-400 dark:to-cyan-400">
                    My Settings
                </h1>
                <a href="{{ route('student.dashboard') }}"
                   class="text-sm text-gray-600 dark:text-gray-400 hover:text-sky-600 dark:hover:text-sky-400 transition-colors">
                    ← Back to Dashboard
                </a>
            </div>
            <p class="text-gray-600 dark:text-gray-400">View your profile and learning information</p>
        </div>

        <!-- Read-Only Notice -->
        <div class="mb-6 glass-card rounded-xl p-4 bg-blue-50/90 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800" role="alert">
            <div class="flex items-start">
                <svg class="w-5 h-5 text-blue-600 dark:text-blue-400 mr-3 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <div>
                    <p class="text-blue-800 dark:text-blue-200 font-medium">Information Only</p>
                    <p class="text-sm text-blue-700 dark:text-blue-300 mt-1">
                        This page displays your account information. To update your details, please contact your tutor or the administrator.
                    </p>
                </div>
            </div>
        </div>

        <!-- Personal Information Section -->
        <div class="glass-card rounded-xl shadow-xl bg-white/90 dark:bg-gray-800/90 border border-white/20 dark:border-gray-700/50 mb-6 overflow-hidden">
            <div class="p-6 bg-gradient-to-r from-sky-500 to-cyan-500 dark:from-sky-600 dark:to-cyan-600">
                <h3 class="text-xl font-bold text-white flex items-center">
                    <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                    Personal Information
                </h3>
            </div>

            <div class="p-6 space-y-4">
                <!-- Full Name -->
                <div class="flex items-start py-3 border-b border-gray-200 dark:border-gray-700">
                    <div class="w-1/3">
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Full Name</p>
                    </div>
                    <div class="w-2/3">
                        <p class="text-gray-900 dark:text-white font-medium">{{ $student->fullName() }}</p>
                    </div>
                </div>

                <!-- Email -->
                <div class="flex items-start py-3 border-b border-gray-200 dark:border-gray-700">
                    <div class="w-1/3">
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Email</p>
                    </div>
                    <div class="w-2/3">
                        <p class="text-gray-900 dark:text-white">{{ $student->email ?? 'Not provided' }}</p>
                    </div>
                </div>

                <!-- Enrollment Date -->
                <div class="flex items-start py-3 border-b border-gray-200 dark:border-gray-700">
                    <div class="w-1/3">
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Enrollment Date</p>
                    </div>
                    <div class="w-2/3">
                        <p class="text-gray-900 dark:text-white">
                            {{ $student->enrollment_date ? $student->enrollment_date->format('F j, Y') : 'Not available' }}
                        </p>
                    </div>
                </div>

                <!-- Student ID -->
                <div class="flex items-start py-3">
                    <div class="w-1/3">
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Student ID</p>
                    </div>
                    <div class="w-2/3">
                        <p class="text-gray-900 dark:text-white font-mono">{{ $student->student_id ?? 'Not assigned' }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Class Information Section -->
        <div class="glass-card rounded-xl shadow-xl bg-white/90 dark:bg-gray-800/90 border border-white/20 dark:border-gray-700/50 mb-6 overflow-hidden">
            <div class="p-6 bg-gradient-to-r from-purple-500 to-pink-500 dark:from-purple-600 dark:to-pink-600">
                <h3 class="text-xl font-bold text-white flex items-center">
                    <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                    </svg>
                    Class Information
                </h3>
            </div>

            <div class="p-6 space-y-4">
                <!-- Class Schedule -->
                @if($student->class_schedule && is_array($student->class_schedule))
                <div class="flex items-start py-3 border-b border-gray-200 dark:border-gray-700">
                    <div class="w-1/3">
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Class Schedule</p>
                    </div>
                    <div class="w-2/3">
                        <div class="space-y-1">
                            @foreach($student->class_schedule as $schedule)
                                <p class="text-gray-900 dark:text-white">
                                    <span class="font-medium">{{ $schedule['day'] ?? '' }}</span>
                                    <span class="text-gray-600 dark:text-gray-400 ml-2">{{ $schedule['time'] ?? '' }}</span>
                                </p>
                            @endforeach
                        </div>
                    </div>
                </div>
                @endif

                <!-- Class Link -->
                @if($student->class_link)
                <div class="flex items-start py-3 border-b border-gray-200 dark:border-gray-700">
                    <div class="w-1/3">
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Class Link</p>
                    </div>
                    <div class="w-2/3">
                        <a href="{{ $student->class_link }}"
                           target="_blank"
                           rel="noopener noreferrer"
                           class="text-sky-600 dark:text-sky-400 hover:underline inline-flex items-center">
                            Join Class
                            <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                            </svg>
                        </a>
                    </div>
                </div>
                @endif

                <!-- Google Classroom Link -->
                @if($student->google_classroom_link)
                <div class="flex items-start py-3">
                    <div class="w-1/3">
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Google Classroom</p>
                    </div>
                    <div class="w-2/3">
                        <a href="{{ $student->google_classroom_link }}"
                           target="_blank"
                           rel="noopener noreferrer"
                           class="text-sky-600 dark:text-sky-400 hover:underline inline-flex items-center">
                            Open Google Classroom
                            <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                            </svg>
                        </a>
                    </div>
                </div>
                @endif
            </div>
        </div>

        <!-- Tutor Information Section -->
        @if($student->tutor)
        <div class="glass-card rounded-xl shadow-xl bg-white/90 dark:bg-gray-800/90 border border-white/20 dark:border-gray-700/50 mb-6 overflow-hidden">
            <div class="p-6 bg-gradient-to-r from-emerald-500 to-teal-500 dark:from-emerald-600 dark:to-teal-600">
                <h3 class="text-xl font-bold text-white flex items-center">
                    <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                    My Tutor
                </h3>
            </div>

            <div class="p-6 space-y-4">
                <!-- Tutor Name -->
                <div class="flex items-start py-3 border-b border-gray-200 dark:border-gray-700">
                    <div class="w-1/3">
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Tutor Name</p>
                    </div>
                    <div class="w-2/3">
                        <p class="text-gray-900 dark:text-white font-medium">{{ $student->tutor->name }}</p>
                    </div>
                </div>

                <!-- Tutor Phone -->
                @if($student->tutor->phone)
                <div class="flex items-start py-3 border-b border-gray-200 dark:border-gray-700">
                    <div class="w-1/3">
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Phone</p>
                    </div>
                    <div class="w-2/3">
                        <a href="tel:{{ $student->tutor->phone }}"
                           class="text-sky-600 dark:text-sky-400 hover:underline">
                            {{ $student->tutor->phone }}
                        </a>
                    </div>
                </div>
                @endif

                <!-- Tutor Email -->
                @if($student->tutor->email)
                <div class="flex items-start py-3">
                    <div class="w-1/3">
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Email</p>
                    </div>
                    <div class="w-2/3">
                        <a href="mailto:{{ $student->tutor->email }}"
                           class="text-sky-600 dark:text-sky-400 hover:underline">
                            {{ $student->tutor->email }}
                        </a>
                    </div>
                </div>
                @endif
            </div>
        </div>
        @endif

        <!-- Progress Summary Section -->
        <div class="glass-card rounded-xl shadow-xl bg-white/90 dark:bg-gray-800/90 border border-white/20 dark:border-gray-700/50 overflow-hidden">
            <div class="p-6 bg-gradient-to-r from-orange-500 to-amber-500 dark:from-orange-600 dark:to-amber-600">
                <h3 class="text-xl font-bold text-white flex items-center">
                    <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                    </svg>
                    Learning Progress
                </h3>
            </div>

            <div class="p-6 space-y-4">
                <!-- Progress Percentage -->
                <div class="flex items-start py-3 border-b border-gray-200 dark:border-gray-700">
                    <div class="w-1/3">
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Progress</p>
                    </div>
                    <div class="w-2/3">
                        <div class="flex items-center">
                            <div class="flex-1 mr-4">
                                <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-3">
                                    <div class="bg-gradient-to-r from-sky-500 to-cyan-500 h-3 rounded-full transition-all duration-500"
                                         style="width: {{ $progressPercentage }}%"
                                         role="progressbar"
                                         aria-valuenow="{{ $progressPercentage }}"
                                         aria-valuemin="0"
                                         aria-valuemax="100">
                                    </div>
                                </div>
                            </div>
                            <span class="text-lg font-bold text-transparent bg-clip-text bg-gradient-to-r from-sky-600 to-cyan-600 dark:from-sky-400 dark:to-cyan-400">
                                {{ $progressPercentage }}%
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Current Stage -->
                @if($student->roadmap_stage)
                <div class="flex items-start py-3 border-b border-gray-200 dark:border-gray-700">
                    <div class="w-1/3">
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Current Stage</p>
                    </div>
                    <div class="w-2/3">
                        <p class="text-gray-900 dark:text-white">{{ $student->roadmap_stage }}</p>
                    </div>
                </div>
                @endif

                <!-- Completed Periods -->
                @if($student->completed_periods !== null && $student->total_periods !== null)
                <div class="flex items-start py-3">
                    <div class="w-1/3">
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Classes Completed</p>
                    </div>
                    <div class="w-2/3">
                        <p class="text-gray-900 dark:text-white">
                            {{ $student->completed_periods }} / {{ $student->total_periods }} classes
                        </p>
                    </div>
                </div>
                @endif
            </div>
        </div>

    </div>
</div>
@endsection
