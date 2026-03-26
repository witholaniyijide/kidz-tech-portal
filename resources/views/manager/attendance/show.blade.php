<x-manager-layout title="Attendance Details">
    {{-- Back Link --}}
    <a href="{{ route('manager.attendance.index') }}" class="inline-flex items-center text-gray-600 dark:text-gray-400 hover:text-[#C15F3C] dark:hover:text-[#DA7756] mb-6">
        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
        </svg>
        Back to Attendance
    </a>

    <div class="max-w-4xl">
        {{-- Header Card --}}
        <div class="bg-white/70 dark:bg-gray-800/70 backdrop-blur-xl border border-white/20 dark:border-gray-700/50 rounded-2xl p-6 mb-6 shadow-sm">
            <div class="flex items-start justify-between">
                <div class="flex items-center gap-4">
                    <div class="w-16 h-16 rounded-full bg-gradient-to-r from-[#C15F3C] to-[#DA7756] flex items-center justify-center text-white font-bold text-xl">
                        {{ strtoupper(substr($attendance->student->first_name ?? 'S', 0, 1)) }}{{ strtoupper(substr($attendance->student->last_name ?? '', 0, 1)) }}
                    </div>
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">
                            <a href="{{ route('manager.students.show', $attendance->student) }}" class="hover:text-[#423A8E] dark:hover:text-[#00CCCD] transition-colors">
                                {{ $attendance->student->first_name ?? 'N/A' }} {{ $attendance->student->last_name ?? '' }}
                            </a>
                        </h1>
                        <p class="text-gray-500 dark:text-gray-400">Student ID: {{ $attendance->student->student_id ?? 'N/A' }}</p>
                    </div>
                </div>
                <span class="px-3 py-1.5 text-sm rounded-full font-semibold
                    @if($attendance->status === 'approved') bg-emerald-100 text-emerald-800 dark:bg-emerald-900/30 dark:text-emerald-300
                    @elseif($attendance->status === 'pending') bg-amber-100 text-amber-800 dark:bg-amber-900/30 dark:text-amber-300
                    @elseif($attendance->status === 'rejected') bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-300
                    @else bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300
                    @endif">
                    {{ ucfirst($attendance->status) }}
                </span>
            </div>
        </div>

        {{-- Details Grid --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            {{-- Class Information --}}
            <div class="bg-white/70 dark:bg-gray-800/70 backdrop-blur-xl border border-white/20 dark:border-gray-700/50 rounded-2xl p-6 shadow-sm">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 flex items-center gap-2">
                    <svg class="w-5 h-5 text-[#C15F3C]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                    </svg>
                    Class Information
                </h3>
                <dl class="space-y-4">
                    <div>
                        <dt class="text-sm text-gray-500 dark:text-gray-400">Date</dt>
                        <dd class="text-gray-900 dark:text-white font-medium">
                            {{ $attendance->class_date ? \Carbon\Carbon::parse($attendance->class_date)->format('l, F j, Y') : 'N/A' }}
                        </dd>
                    </div>
                    <div>
                        <dt class="text-sm text-gray-500 dark:text-gray-400">Time</dt>
                        <dd class="text-gray-900 dark:text-white font-medium">{{ $attendance->class_time ?? 'N/A' }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm text-gray-500 dark:text-gray-400">Duration</dt>
                        <dd class="text-gray-900 dark:text-white font-medium">{{ $attendance->duration ?? 60 }} minutes</dd>
                    </div>
                    @if($attendance->courses_covered && count($attendance->courses_covered) > 0)
                        <div>
                            <dt class="text-sm text-gray-500 dark:text-gray-400 mb-2">Course(s) Covered</dt>
                            <dd class="flex flex-wrap gap-2">
                                @foreach($attendance->courses_covered as $course)
                                    <span class="inline-flex px-3 py-1 text-xs font-medium rounded-full bg-[#C15F3C]/10 text-[#C15F3C] dark:bg-[#C15F3C]/20 dark:text-orange-300">
                                        {{ $course }}
                                    </span>
                                @endforeach
                            </dd>
                        </div>
                    @endif
                    @if($attendance->topic)
                        <div>
                            <dt class="text-sm text-gray-500 dark:text-gray-400">Topic/Lesson Details</dt>
                            <dd class="text-gray-900 dark:text-white font-medium">{{ $attendance->topic }}</dd>
                        </div>
                    @endif
                </dl>
            </div>

            {{-- Tutor Information --}}
            <div class="bg-white/70 dark:bg-gray-800/70 backdrop-blur-xl border border-white/20 dark:border-gray-700/50 rounded-2xl p-6 shadow-sm">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 flex items-center gap-2">
                    <svg class="w-5 h-5 text-[#C15F3C]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                    Submitted By
                </h3>
                <div class="flex items-center gap-4 mb-4">
                    <div class="w-12 h-12 rounded-full bg-gradient-to-r from-blue-500 to-indigo-500 flex items-center justify-center text-white font-bold">
                        {{ strtoupper(substr($attendance->tutor->first_name ?? 'T', 0, 1)) }}{{ strtoupper(substr($attendance->tutor->last_name ?? '', 0, 1)) }}
                    </div>
                    <div>
                        <p class="font-semibold text-gray-900 dark:text-white">
                            <a href="{{ route('manager.tutors.show', $attendance->tutor) }}" class="hover:text-[#423A8E] dark:hover:text-[#00CCCD] transition-colors">
                                {{ $attendance->tutor->first_name ?? 'N/A' }} {{ $attendance->tutor->last_name ?? '' }}
                            </a>
                        </p>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Tutor</p>
                    </div>
                </div>
                <dl class="space-y-4">
                    <div>
                        <dt class="text-sm text-gray-500 dark:text-gray-400">Submitted On</dt>
                        <dd class="text-gray-900 dark:text-white font-medium">
                            {{ $attendance->created_at ? $attendance->created_at->format('M d, Y \a\t h:i A') : 'N/A' }}
                        </dd>
                    </div>
                    @if($attendance->is_late_submission)
                        <div>
                            <dt class="text-sm text-gray-500 dark:text-gray-400">Submission Status</dt>
                            <dd class="inline-flex items-center text-red-600 dark:text-red-400 font-medium">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                Late Submission
                            </dd>
                        </div>
                    @endif
                </dl>
            </div>
        </div>

        {{-- Notes Section --}}
        @if($attendance->notes)
            <div class="bg-white/70 dark:bg-gray-800/70 backdrop-blur-xl border border-white/20 dark:border-gray-700/50 rounded-2xl p-6 mb-6 shadow-sm">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 flex items-center gap-2">
                    <svg class="w-5 h-5 text-[#C15F3C]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                    </svg>
                    Session Notes
                </h3>
                <div class="prose dark:prose-invert max-w-none text-gray-700 dark:text-gray-300">
                    {{ $attendance->notes }}
                </div>
            </div>
        @endif

        {{-- Approval Information (if approved/rejected) --}}
        @if($attendance->status !== 'pending' && ($attendance->approved_by || $attendance->rejected_by))
            <div class="bg-white/70 dark:bg-gray-800/70 backdrop-blur-xl border border-white/20 dark:border-gray-700/50 rounded-2xl p-6 mb-6 shadow-sm">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 flex items-center gap-2">
                    <svg class="w-5 h-5 text-[#C15F3C]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    Review Information
                </h3>
                <dl class="space-y-4">
                    @if($attendance->status === 'approved')
                        <div>
                            <dt class="text-sm text-gray-500 dark:text-gray-400">Approved By</dt>
                            <dd class="text-emerald-600 dark:text-emerald-400 font-medium">
                                {{ $attendance->approver->name ?? 'Admin' }}
                            </dd>
                        </div>
                        <div>
                            <dt class="text-sm text-gray-500 dark:text-gray-400">Approved On</dt>
                            <dd class="text-gray-900 dark:text-white font-medium">
                                {{ $attendance->approved_at ? \Carbon\Carbon::parse($attendance->approved_at)->format('M d, Y \a\t h:i A') : 'N/A' }}
                            </dd>
                        </div>
                    @elseif($attendance->status === 'rejected')
                        <div>
                            <dt class="text-sm text-gray-500 dark:text-gray-400">Rejected By</dt>
                            <dd class="text-red-600 dark:text-red-400 font-medium">
                                {{ $attendance->rejector->name ?? 'Admin' }}
                            </dd>
                        </div>
                        @if($attendance->rejection_reason)
                            <div>
                                <dt class="text-sm text-gray-500 dark:text-gray-400">Rejection Reason</dt>
                                <dd class="text-gray-900 dark:text-white">{{ $attendance->rejection_reason }}</dd>
                            </div>
                        @endif
                    @endif
                </dl>
            </div>
        @endif

        {{-- Info Box for Pending --}}
        @if($attendance->status === 'pending')
            <div class="bg-amber-50 dark:bg-amber-900/20 border border-amber-200 dark:border-amber-800/30 rounded-xl p-4">
                <div class="flex items-start gap-3">
                    <svg class="w-5 h-5 text-amber-600 dark:text-amber-400 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <div>
                        <p class="text-sm text-amber-800 dark:text-amber-300 font-medium">Awaiting Admin Approval</p>
                        <p class="text-xs text-amber-600 dark:text-amber-400 mt-1">
                            This attendance record is pending review by an administrator. You will be notified once it has been processed.
                        </p>
                    </div>
                </div>
            </div>
        @endif
    </div>
</x-manager-layout>
