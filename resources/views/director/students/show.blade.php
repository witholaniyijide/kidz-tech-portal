<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-bold text-2xl text-white">
                {{ __('Student Details') }}
            </h2>
            <div class="flex space-x-3">
                <a href="{{ route('director.students.edit', $student) }}" 
                   class="inline-flex items-center px-4 py-2 bg-yellow-500/80 hover:bg-yellow-500 text-white font-semibold rounded-lg transition-all duration-200">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                    </svg>
                    Edit
                </a>
                <a href="{{ route('director.students.index') }}" 
                   class="inline-flex items-center px-4 py-2 bg-white/20 hover:bg-white/30 text-white font-semibold rounded-lg transition-all duration-200">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    Back
                </a>
            </div>
        </div>
    </x-slot>

    <x-slot name="title">{{ $student->first_name }} {{ $student->last_name }}</x-slot>

    <div class="min-h-screen bg-gradient-to-br from-blue-50 via-purple-50 to-pink-50 dark:from-gray-900 dark:via-gray-800 dark:to-gray-900 py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Left Column - Profile -->
                <div class="lg:col-span-1">
                    <x-ui.glass-card>
                        <div class="text-center">
                            <div class="w-24 h-24 mx-auto rounded-full bg-gradient-to-br from-blue-500 to-purple-600 flex items-center justify-center text-white text-3xl font-bold">
                                {{ strtoupper(substr($student->first_name, 0, 1) . substr($student->last_name, 0, 1)) }}
                            </div>
                            <h3 class="mt-4 text-xl font-bold text-gray-900 dark:text-white">
                                {{ $student->first_name }} {{ $student->last_name }}
                            </h3>
                            <p class="text-gray-500 dark:text-gray-400">{{ $student->student_id }}</p>
                            <div class="mt-2">
                                <x-ui.status-badge :status="$student->status" />
                            </div>
                        </div>

                        <div class="mt-6 space-y-4">
                            @if($student->email)
                            <div class="flex items-center text-gray-600 dark:text-gray-300">
                                <svg class="w-5 h-5 mr-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                </svg>
                                {{ $student->email }}
                            </div>
                            @endif
                            @if($student->phone)
                            <div class="flex items-center text-gray-600 dark:text-gray-300">
                                <svg class="w-5 h-5 mr-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                                </svg>
                                {{ $student->phone }}
                            </div>
                            @endif
                            @if($student->date_of_birth)
                            <div class="flex items-center text-gray-600 dark:text-gray-300">
                                <svg class="w-5 h-5 mr-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                                {{ \Carbon\Carbon::parse($student->date_of_birth)->format('M d, Y') }}
                                ({{ \Carbon\Carbon::parse($student->date_of_birth)->age }} years old)
                            </div>
                            @endif
                            @if($student->address)
                            <div class="flex items-start text-gray-600 dark:text-gray-300">
                                <svg class="w-5 h-5 mr-3 mt-0.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                </svg>
                                {{ $student->address }}
                            </div>
                            @endif
                        </div>
                    </x-ui.glass-card>
                </div>

                <!-- Right Column - Details -->
                <div class="lg:col-span-2 space-y-6">
                    <!-- Academic Info -->
                    <x-ui.glass-card>
                        <h4 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Academic Information</h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <p class="text-sm text-gray-500 dark:text-gray-400">Current Level</p>
                                <p class="font-medium text-gray-900 dark:text-white">{{ $student->current_level ?? 'Not set' }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500 dark:text-gray-400">Enrollment Date</p>
                                <p class="font-medium text-gray-900 dark:text-white">
                                    {{ $student->enrollment_date ? \Carbon\Carbon::parse($student->enrollment_date)->format('M d, Y') : 'Not set' }}
                                </p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500 dark:text-gray-400">Assigned Tutor</p>
                                <p class="font-medium text-gray-900 dark:text-white">
                                    {{ $student->tutor ? $student->tutor->first_name . ' ' . $student->tutor->last_name : 'Unassigned' }}
                                </p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500 dark:text-gray-400">Classes Per Week</p>
                                <p class="font-medium text-gray-900 dark:text-white">{{ $student->classes_per_week ?? 'Not set' }}</p>
                            </div>
                        </div>
                    </x-ui.glass-card>

                    <!-- Class Schedule -->
                    @if($student->class_schedule)
                    <x-ui.glass-card>
                        <h4 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Class Schedule</h4>
                        <div class="space-y-2">
                            @php $schedules = json_decode($student->class_schedule, true) ?? []; @endphp
                            @forelse($schedules as $schedule)
                                <div class="flex items-center p-3 bg-gray-50 dark:bg-gray-700/50 rounded-lg">
                                    <svg class="w-5 h-5 mr-3 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    <span class="font-medium text-gray-900 dark:text-white">{{ $schedule['day'] ?? '' }}</span>
                                    <span class="mx-2 text-gray-400">at</span>
                                    <span class="text-gray-600 dark:text-gray-300">{{ $schedule['time'] ?? '' }}</span>
                                </div>
                            @empty
                                <p class="text-gray-500 dark:text-gray-400">No schedule set</p>
                            @endforelse
                        </div>
                    </x-ui.glass-card>
                    @endif

                    <!-- Guardian Info -->
                    @if($student->guardians->count() > 0)
                    <x-ui.glass-card>
                        <h4 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Parent/Guardian</h4>
                        @foreach($student->guardians as $guardian)
                            <div class="flex items-center p-3 bg-gray-50 dark:bg-gray-700/50 rounded-lg">
                                <div class="w-10 h-10 rounded-full bg-gradient-to-br from-green-500 to-teal-600 flex items-center justify-center text-white font-semibold">
                                    {{ strtoupper(substr($guardian->name, 0, 1)) }}
                                </div>
                                <div class="ml-3">
                                    <p class="font-medium text-gray-900 dark:text-white">{{ $guardian->name }}</p>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">{{ $guardian->email }}</p>
                                </div>
                            </div>
                        @endforeach
                    </x-ui.glass-card>
                    @endif

                    <!-- Notes -->
                    @if($student->notes)
                    <x-ui.glass-card>
                        <h4 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Notes</h4>
                        <p class="text-gray-600 dark:text-gray-300">{{ $student->notes }}</p>
                    </x-ui.glass-card>
                    @endif
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
