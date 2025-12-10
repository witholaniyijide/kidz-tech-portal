<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-2xl text-white">{{ __('Attendance Details') }}</h2>
    </x-slot>
    <x-slot name="title">{{ __('Admin - Attendance Details') }}</x-slot>

    <div class="min-h-screen bg-gradient-to-br from-teal-50 via-cyan-50 to-blue-50 dark:from-slate-900 dark:via-slate-800 dark:to-slate-900 py-8 relative overflow-hidden">
        <div class="absolute top-0 left-0 w-72 h-72 bg-teal-300 dark:bg-teal-900 rounded-full mix-blend-multiply filter blur-xl opacity-70 animate-float"></div>
        <div class="absolute top-0 right-0 w-72 h-72 bg-cyan-300 dark:bg-cyan-900 rounded-full mix-blend-multiply filter blur-xl opacity-70 animate-float" style="animation-delay: 2s;"></div>

        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            @if(session('success'))
                <div class="mb-6 bg-emerald-100 dark:bg-emerald-900/30 border border-emerald-400 text-emerald-800 dark:text-emerald-400 px-6 py-4 rounded-xl">
                    {{ session('success') }}
                </div>
            @endif

            {{-- Header --}}
            <div class="flex flex-wrap items-center justify-between gap-4 mb-8">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Attendance Record</h1>
                    <p class="text-gray-600 dark:text-gray-400 mt-1">{{ $attendance->class_date?->format('l, M j, Y') }}</p>
                </div>
                <a href="{{ route('admin.attendance.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-300 dark:hover:bg-gray-600 transition-colors">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    Back to List
                </a>
            </div>

            {{-- Status Banner --}}
            <div class="mb-6 p-4 rounded-xl 
                @if($attendance->status === 'approved')
                    @if($attendance->is_late)
                        bg-red-100 dark:bg-red-900/30 border border-red-300 dark:border-red-700
                    @else
                        bg-emerald-100 dark:bg-emerald-900/30 border border-emerald-300 dark:border-emerald-700
                    @endif
                @else
                    bg-amber-100 dark:bg-amber-900/30 border border-amber-300 dark:border-amber-700
                @endif">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        @if($attendance->status === 'approved')
                            @if($attendance->is_late)
                                <span class="text-2xl">⚠️</span>
                                <div>
                                    <p class="font-semibold text-red-800 dark:text-red-400">Late Submission - Approved</p>
                                    <p class="text-sm text-red-700 dark:text-red-500">This attendance was marked as a late submission</p>
                                </div>
                            @else
                                <span class="text-2xl">✅</span>
                                <div>
                                    <p class="font-semibold text-emerald-800 dark:text-emerald-400">Approved</p>
                                    <p class="text-sm text-emerald-700 dark:text-emerald-500">
                                        Approved by {{ $attendance->approver->name ?? 'Admin' }} 
                                        on {{ $attendance->approved_at?->format('M j, Y \a\t g:i A') }}
                                    </p>
                                </div>
                            @endif
                        @else
                            <span class="text-2xl">⏳</span>
                            <div>
                                <p class="font-semibold text-amber-800 dark:text-amber-400">Pending Approval</p>
                                <p class="text-sm text-amber-700 dark:text-amber-500">This attendance record is awaiting your review</p>
                            </div>
                        @endif
                    </div>

                    @if($attendance->status === 'pending')
                        <div class="flex gap-2">
                            <form action="{{ route('admin.attendance.approve', $attendance) }}" method="POST">
                                @csrf
                                <button type="submit" class="px-4 py-2 bg-emerald-600 text-white rounded-lg hover:bg-emerald-700 transition-colors text-sm font-medium">
                                    ✓ Approve
                                </button>
                            </form>
                            <form action="{{ route('admin.attendance.late', $attendance) }}" method="POST">
                                @csrf
                                <button type="submit" class="px-4 py-2 bg-amber-600 text-white rounded-lg hover:bg-amber-700 transition-colors text-sm font-medium">
                                    ⏰ Mark Late
                                </button>
                            </form>
                        </div>
                    @endif
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                {{-- Class Information --}}
                <div class="bg-white/60 dark:bg-gray-800/60 backdrop-blur-md border border-white/20 rounded-2xl shadow overflow-hidden">
                    <div class="px-6 py-4 bg-gradient-to-r from-teal-500 to-cyan-600 text-white">
                        <h3 class="text-lg font-semibold">Class Information</h3>
                    </div>
                    <div class="p-6 space-y-4">
                        <div class="flex justify-between">
                            <span class="text-gray-500 dark:text-gray-400">Class Date</span>
                            <span class="font-medium text-gray-900 dark:text-white">{{ $attendance->class_date?->format('M j, Y') }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-500 dark:text-gray-400">Duration</span>
                            <span class="font-medium text-gray-900 dark:text-white">{{ $attendance->duration ?? 60 }} minutes</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-500 dark:text-gray-400">Course Level</span>
                            <span class="font-medium text-gray-900 dark:text-white">Level {{ $attendance->course_level ?? '-' }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-500 dark:text-gray-400">Attendance #</span>
                            <span class="font-medium text-gray-900 dark:text-white">{{ $attendance->attendance_number ?? '-' }}</span>
                        </div>
                        <div class="pt-4 border-t border-gray-200 dark:border-gray-700">
                            <span class="text-gray-500 dark:text-gray-400 text-sm">Topic Covered</span>
                            <p class="mt-1 text-gray-900 dark:text-white">{{ $attendance->topic ?? 'No topic specified' }}</p>
                        </div>
                        @if($attendance->notes)
                            <div class="pt-4 border-t border-gray-200 dark:border-gray-700">
                                <span class="text-gray-500 dark:text-gray-400 text-sm">Notes</span>
                                <p class="mt-1 text-gray-900 dark:text-white">{{ $attendance->notes }}</p>
                            </div>
                        @endif
                    </div>
                </div>

                {{-- Submission Details --}}
                <div class="bg-white/60 dark:bg-gray-800/60 backdrop-blur-md border border-white/20 rounded-2xl shadow overflow-hidden">
                    <div class="px-6 py-4 bg-gradient-to-r from-blue-500 to-indigo-600 text-white">
                        <h3 class="text-lg font-semibold">Submission Details</h3>
                    </div>
                    <div class="p-6 space-y-4">
                        <div class="flex justify-between">
                            <span class="text-gray-500 dark:text-gray-400">Submitted On</span>
                            <span class="font-medium text-gray-900 dark:text-white">{{ $attendance->created_at->format('M j, Y') }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-500 dark:text-gray-400">Submitted At</span>
                            <span class="font-medium text-gray-900 dark:text-white">{{ $attendance->created_at->format('g:i A') }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-500 dark:text-gray-400">Time Since Submission</span>
                            <span class="font-medium text-gray-900 dark:text-white">{{ $attendance->created_at->diffForHumans() }}</span>
                        </div>
                        @if($attendance->status === 'approved')
                            <div class="pt-4 border-t border-gray-200 dark:border-gray-700">
                                <div class="flex justify-between mb-2">
                                    <span class="text-gray-500 dark:text-gray-400">Approved By</span>
                                    <span class="font-medium text-gray-900 dark:text-white">{{ $attendance->approver->name ?? 'Admin' }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-500 dark:text-gray-400">Approved On</span>
                                    <span class="font-medium text-gray-900 dark:text-white">{{ $attendance->approved_at?->format('M j, Y \a\t g:i A') }}</span>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Student & Tutor Info --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
                {{-- Student --}}
                <div class="bg-white/60 dark:bg-gray-800/60 backdrop-blur-md border border-white/20 rounded-2xl shadow p-6">
                    <h4 class="text-sm font-semibold text-gray-500 dark:text-gray-400 uppercase mb-4">Student</h4>
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 bg-gradient-to-br from-teal-400 to-cyan-500 rounded-full flex items-center justify-center text-white font-bold">
                            {{ strtoupper(substr($attendance->student->first_name ?? 'U', 0, 1)) }}{{ strtoupper(substr($attendance->student->last_name ?? '', 0, 1)) }}
                        </div>
                        <div>
                            <p class="font-semibold text-gray-900 dark:text-white">
                                {{ $attendance->student->first_name ?? 'Unknown' }} {{ $attendance->student->last_name ?? '' }}
                            </p>
                            <p class="text-sm text-gray-500">{{ $attendance->student->email ?? '-' }}</p>
                        </div>
                        <a href="{{ route('admin.students.show', $attendance->student) }}" class="ml-auto text-teal-600 hover:text-teal-700">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                            </svg>
                        </a>
                    </div>
                </div>

                {{-- Tutor --}}
                <div class="bg-white/60 dark:bg-gray-800/60 backdrop-blur-md border border-white/20 rounded-2xl shadow p-6">
                    <h4 class="text-sm font-semibold text-gray-500 dark:text-gray-400 uppercase mb-4">Tutor (Submitted By)</h4>
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 bg-gradient-to-br from-blue-400 to-indigo-500 rounded-full flex items-center justify-center text-white font-bold">
                            {{ strtoupper(substr($attendance->tutor->first_name ?? 'U', 0, 1)) }}{{ strtoupper(substr($attendance->tutor->last_name ?? '', 0, 1)) }}
                        </div>
                        <div>
                            <p class="font-semibold text-gray-900 dark:text-white">
                                {{ $attendance->tutor->first_name ?? 'Unknown' }} {{ $attendance->tutor->last_name ?? '' }}
                            </p>
                            <p class="text-sm text-gray-500">{{ $attendance->tutor->email ?? '-' }}</p>
                        </div>
                        <a href="{{ route('admin.tutors.show', $attendance->tutor) }}" class="ml-auto text-teal-600 hover:text-teal-700">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                            </svg>
                        </a>
                    </div>
                </div>
            </div>

            {{-- Actions --}}
            <div class="mt-8 flex justify-between items-center">
                <form action="{{ route('admin.attendance.destroy', $attendance) }}" method="POST"
                      onsubmit="return confirm('Delete this attendance record?\n\nThe tutor ({{ $attendance->tutor->first_name ?? 'Unknown' }}) will need to resubmit.')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="inline-flex items-center px-4 py-2 bg-red-100 text-red-700 rounded-lg hover:bg-red-200 transition-colors">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                        </svg>
                        Delete & Request Resubmission
                    </button>
                </form>

                @if($attendance->status === 'pending')
                    <div class="flex gap-3">
                        <form action="{{ route('admin.attendance.late', $attendance) }}" method="POST">
                            @csrf
                            <button type="submit" class="inline-flex items-center px-5 py-2 bg-amber-600 text-white rounded-lg hover:bg-amber-700 transition-colors">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                Mark as Late & Approve
                            </button>
                        </form>
                        <form action="{{ route('admin.attendance.approve', $attendance) }}" method="POST">
                            @csrf
                            <button type="submit" class="inline-flex items-center px-5 py-2 bg-emerald-600 text-white rounded-lg hover:bg-emerald-700 transition-colors">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                                Approve
                            </button>
                        </form>
                    </div>
                @endif
            </div>
        </div>
    </div>

    @push('styles')
    <style>
        @keyframes float { 0%, 100% { transform: translateY(0px); } 50% { transform: translateY(-20px); } }
        .animate-float { animation: float 6s ease-in-out infinite; }
    </style>
    @endpush
</x-app-layout>
