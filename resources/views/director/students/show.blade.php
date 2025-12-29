<x-app-layout>
    <x-slot name="header">{{ __('Student Details') }}</x-slot>
    <x-slot name="title">{{ __('Director - Student Details') }}</x-slot>

    <div class="min-h-screen bg-gradient-to-br from-[#423A8E]/5 via-[#00CCCD]/5 to-blue-50 dark:from-slate-900 dark:via-slate-800 dark:to-slate-900 py-4 sm:py-8 relative overflow-hidden">
        <div class="absolute top-0 left-0 w-72 h-72 bg-[#423A8E]/30 dark:bg-[#423A8E]/30 rounded-full mix-blend-multiply filter blur-xl opacity-70 animate-float hidden sm:block"></div>
        <div class="absolute top-0 right-0 w-72 h-72 bg-[#00CCCD]/30 dark:bg-[#00CCCD]/40 rounded-full mix-blend-multiply filter blur-xl opacity-70 animate-float hidden sm:block" style="animation-delay: 2s;"></div>

        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            {{-- Flash Messages --}}
            @if(session('success'))
                <div class="mb-6 bg-emerald-100 dark:bg-emerald-900/30 border border-emerald-400 text-emerald-800 dark:text-emerald-400 px-4 sm:px-6 py-4 rounded-xl text-sm">
                    {{ session('success') }}
                </div>
            @endif

            {{-- Header --}}
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6 sm:mb-8">
                <div class="flex items-center gap-3 sm:gap-4">
                    <div class="w-12 h-12 sm:w-16 sm:h-16 bg-gradient-to-br from-[#00CCCD] to-[#00CCCD] rounded-xl sm:rounded-2xl flex items-center justify-center text-white font-bold text-lg sm:text-2xl shadow-lg">
                        {{ strtoupper(substr($student->first_name, 0, 1)) }}{{ strtoupper(substr($student->last_name, 0, 1)) }}
                    </div>
                    <div>
                        <h1 class="text-xl sm:text-3xl font-bold text-gray-900 dark:text-white">
                            {{ $student->first_name }} {{ $student->other_name }} {{ $student->last_name }}
                        </h1>
                        <p class="text-gray-600 dark:text-gray-400 text-sm sm:text-base">{{ $student->email ?? 'No email provided' }}</p>
                    </div>
                </div>
                <div class="flex gap-2">
                    <a href="{{ route('director.students.edit', $student) }}" class="inline-flex items-center justify-center px-3 sm:px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors text-sm">
                        <svg class="w-4 h-4 mr-1 sm:mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                        </svg>
                        <span class="hidden sm:inline">Edit</span>
                    </a>
                    <a href="{{ route('director.students.index') }}" class="inline-flex items-center justify-center px-3 sm:px-4 py-2 bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-300 dark:hover:bg-gray-600 transition-colors text-sm">
                        <svg class="w-4 h-4 mr-1 sm:mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                        </svg>
                        <span class="hidden sm:inline">Back</span>
                    </a>
                </div>
            </div>

            {{-- Status Badge --}}
            <div class="mb-6">
                <span class="inline-flex px-3 sm:px-4 py-1.5 sm:py-2 text-xs sm:text-sm font-semibold rounded-full
                    @if($student->status === 'active') bg-emerald-100 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-400
                    @elseif($student->status === 'inactive') bg-gray-100 text-gray-700 dark:bg-gray-700 dark:text-gray-300
                    @elseif($student->status === 'graduated') bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400
                    @else bg-amber-100 text-amber-700 dark:bg-amber-900/30 dark:text-amber-400
                    @endif">
                    {{ ucfirst($student->status) }}
                </span>
            </div>

            {{-- Student Info --}}
            <div class="bg-white/60 dark:bg-gray-800/60 backdrop-blur-md border border-white/20 rounded-2xl shadow mb-6 overflow-hidden">
                <div class="px-4 sm:px-6 py-4 bg-gradient-to-r from-[#423A8E] to-[#00CCCD] text-white">
                    <h3 class="text-base sm:text-lg font-semibold">Student Information</h3>
                </div>
                <div class="p-4 sm:p-6">
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6">
                        <div>
                            <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 uppercase mb-1">Full Name</label>
                            <p class="text-gray-900 dark:text-white font-medium text-sm sm:text-base">{{ $student->first_name }} {{ $student->other_name }} {{ $student->last_name }}</p>
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 uppercase mb-1">Email</label>
                            <p class="text-gray-900 dark:text-white text-sm sm:text-base break-all">{{ $student->email ?? '-' }}</p>
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 uppercase mb-1">Date of Birth</label>
                            <p class="text-gray-900 dark:text-white text-sm sm:text-base">{{ $student->date_of_birth?->format('M j, Y') ?? '-' }}</p>
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 uppercase mb-1">Gender</label>
                            <p class="text-gray-900 dark:text-white text-sm sm:text-base">{{ ucfirst($student->gender ?? '-') }}</p>
                        </div>
                        <div class="sm:col-span-2">
                            <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 uppercase mb-1">Coding Experience</label>
                            <p class="text-gray-900 dark:text-white text-sm sm:text-base">{{ $student->coding_experience ?? '-' }}</p>
                        </div>
                        <div class="sm:col-span-2">
                            <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 uppercase mb-1">Career Interest</label>
                            <p class="text-gray-900 dark:text-white text-sm sm:text-base">{{ $student->career_interest ?? '-' }}</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Class Info --}}
            <div class="bg-white/60 dark:bg-gray-800/60 backdrop-blur-md border border-white/20 rounded-2xl shadow mb-6 overflow-hidden">
                <div class="px-4 sm:px-6 py-4 bg-gradient-to-r from-blue-500 to-indigo-600 text-white">
                    <h3 class="text-base sm:text-lg font-semibold">Class Information</h3>
                </div>
                <div class="p-4 sm:p-6">
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6">
                        <div>
                            <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 uppercase mb-1">Tutor Assigned</label>
                            <p class="text-gray-900 dark:text-white font-medium text-sm sm:text-base">
                                @if($student->tutor)
                                    {{ $student->tutor->first_name }} {{ $student->tutor->last_name }}
                                @else
                                    <span class="text-gray-400 italic">Unassigned</span>
                                @endif
                            </p>
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 uppercase mb-1">Classes Per Week</label>
                            <p class="text-gray-900 dark:text-white text-sm sm:text-base">{{ $student->classes_per_week ?? '-' }}</p>
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 uppercase mb-1">Starting Course Level</label>
                            <p class="text-gray-900 dark:text-white text-sm sm:text-base">{{ $student->starting_course_level ? 'Level ' . $student->starting_course_level : '-' }}</p>
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 uppercase mb-1">Enrollment Date</label>
                            <p class="text-gray-900 dark:text-white text-sm sm:text-base">{{ $student->enrollment_date?->format('M j, Y') ?? '-' }}</p>
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 uppercase mb-1">Est. Classes per Month</label>
                            <p class="text-gray-900 dark:text-white text-sm sm:text-base">{{ ($student->classes_per_week ?? 1) * 4 }} classes</p>
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 uppercase mb-1">Class Link</label>
                            @if($student->class_link)
                                <a href="{{ $student->class_link }}" target="_blank" class="text-[#423A8E] dark:text-[#00CCCD] hover:underline truncate block text-sm sm:text-base">{{ $student->class_link }}</a>
                            @else
                                <p class="text-gray-400 text-sm sm:text-base">-</p>
                            @endif
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 uppercase mb-1">Google Classroom</label>
                            @if($student->google_classroom_link)
                                <a href="{{ $student->google_classroom_link }}" target="_blank" class="text-[#423A8E] dark:text-[#00CCCD] hover:underline truncate block text-sm sm:text-base">{{ $student->google_classroom_link }}</a>
                            @else
                                <p class="text-gray-400 text-sm sm:text-base">-</p>
                            @endif
                        </div>
                    </div>

                    {{-- Class Schedule --}}
                    @php
                        $classSchedule = $student->class_schedule;
                        if (is_string($classSchedule)) {
                            $classSchedule = json_decode($classSchedule, true) ?? [];
                        }
                    @endphp
                    @if($classSchedule && is_array($classSchedule) && count($classSchedule) > 0)
                        <div class="mt-6 pt-6 border-t border-gray-200 dark:border-gray-700">
                            <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 uppercase mb-3">Class Schedule</label>
                            <div class="flex flex-wrap gap-2">
                                @foreach($classSchedule as $schedule)
                                    @if(isset($schedule['day']) && isset($schedule['time']))
                                        <span class="inline-flex items-center px-2 sm:px-3 py-1 sm:py-1.5 bg-blue-50 dark:bg-blue-900/30 text-blue-700 dark:text-blue-300 rounded-lg text-xs sm:text-sm">
                                            <span class="font-medium">{{ ucfirst($schedule['day']) }}</span>
                                            <span class="mx-1">-</span>
                                            <span>{{ $schedule['time'] }}</span>
                                        </span>
                                    @endif
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Parent Info --}}
            <div class="bg-white/60 dark:bg-gray-800/60 backdrop-blur-md border border-white/20 rounded-2xl shadow mb-6 overflow-hidden">
                <div class="px-4 sm:px-6 py-4 bg-gradient-to-r from-purple-500 to-pink-600 text-white">
                    <h3 class="text-base sm:text-lg font-semibold">Parent Information</h3>
                </div>
                <div class="p-4 sm:p-6">
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 sm:gap-8">
                        {{-- Father --}}
                        <div>
                            <h4 class="font-semibold text-gray-800 dark:text-white mb-4 flex items-center text-sm sm:text-base">
                                <span class="w-7 h-7 sm:w-8 sm:h-8 bg-blue-100 dark:bg-blue-900/30 rounded-full flex items-center justify-center mr-2 text-blue-600 text-sm">👨</span>
                                Father
                            </h4>
                            <div class="space-y-2 sm:space-y-3 text-xs sm:text-sm">
                                <div class="flex justify-between">
                                    <span class="text-gray-500 dark:text-gray-400">Name</span>
                                    <span class="text-gray-900 dark:text-white text-right">{{ $student->father_name ?? '-' }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-500 dark:text-gray-400">Phone</span>
                                    <span class="text-gray-900 dark:text-white text-right">{{ $student->father_phone ?? '-' }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-500 dark:text-gray-400">Email</span>
                                    <span class="text-gray-900 dark:text-white text-right break-all">{{ $student->father_email ?? '-' }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-500 dark:text-gray-400">Occupation</span>
                                    <span class="text-gray-900 dark:text-white text-right">{{ $student->father_occupation ?? '-' }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-500 dark:text-gray-400">Location</span>
                                    <span class="text-gray-900 dark:text-white text-right">{{ $student->father_location ?? '-' }}</span>
                                </div>
                            </div>
                        </div>
                        {{-- Mother --}}
                        <div>
                            <h4 class="font-semibold text-gray-800 dark:text-white mb-4 flex items-center text-sm sm:text-base">
                                <span class="w-7 h-7 sm:w-8 sm:h-8 bg-pink-100 dark:bg-pink-900/30 rounded-full flex items-center justify-center mr-2 text-pink-600 text-sm">👩</span>
                                Mother
                            </h4>
                            <div class="space-y-2 sm:space-y-3 text-xs sm:text-sm">
                                <div class="flex justify-between">
                                    <span class="text-gray-500 dark:text-gray-400">Name</span>
                                    <span class="text-gray-900 dark:text-white text-right">{{ $student->mother_name ?? '-' }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-500 dark:text-gray-400">Phone</span>
                                    <span class="text-gray-900 dark:text-white text-right">{{ $student->mother_phone ?? '-' }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-500 dark:text-gray-400">Email</span>
                                    <span class="text-gray-900 dark:text-white text-right break-all">{{ $student->mother_email ?? '-' }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-500 dark:text-gray-400">Occupation</span>
                                    <span class="text-gray-900 dark:text-white text-right">{{ $student->mother_occupation ?? '-' }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-500 dark:text-gray-400">Location</span>
                                    <span class="text-gray-900 dark:text-white text-right">{{ $student->mother_location ?? '-' }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Recent Attendance --}}
            @if($student->attendances && $student->attendances->count() > 0)
                <div class="bg-white/60 dark:bg-gray-800/60 backdrop-blur-md border border-white/20 rounded-2xl shadow overflow-hidden">
                    <div class="px-4 sm:px-6 py-4 border-b border-gray-200 dark:border-gray-700 flex justify-between items-center">
                        <h3 class="text-base sm:text-lg font-semibold text-gray-900 dark:text-white">Recent Attendance</h3>
                        <a href="{{ route('director.attendance.index', ['student_id' => $student->id]) }}" class="text-xs sm:text-sm text-[#423A8E] dark:text-[#00CCCD] hover:underline">View All</a>
                    </div>
                    <div class="p-4 sm:p-6">
                        <div class="space-y-3">
                            @foreach($student->attendances->take(5) as $attendance)
                                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 p-3 bg-gray-50 dark:bg-gray-700/50 rounded-lg">
                                    <div>
                                        <div class="font-medium text-gray-900 dark:text-white text-sm sm:text-base">{{ $attendance->topic ?? 'No topic' }}</div>
                                        <div class="text-xs sm:text-sm text-gray-500">{{ $attendance->class_date?->format('M j, Y') }} - {{ $attendance->duration }} mins</div>
                                    </div>
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full self-start sm:self-auto
                                        @if($attendance->status === 'approved') bg-emerald-100 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-400
                                        @elseif($attendance->status === 'pending') bg-amber-100 text-amber-700 dark:bg-amber-900/30 dark:text-amber-400
                                        @else bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400
                                        @endif">
                                        {{ ucfirst($attendance->status) }}
                                    </span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>

    @push('styles')
    <style>
        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-20px); }
        }
        .animate-float { animation: float 6s ease-in-out infinite; }
    </style>
    @endpush
</x-app-layout>
