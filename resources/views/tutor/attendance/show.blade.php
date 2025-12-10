<x-tutor-layout title="Attendance Details">
<div class="space-y-6">
    <!-- Page Header -->
    <div class="flex flex-wrap items-center justify-between gap-4">
        <div>
            <h1 class="text-3xl font-bold text-slate-900 dark:text-white">Attendance Details</h1>
            <p class="text-slate-600 dark:text-slate-400 mt-1">View attendance record information</p>
        </div>
        <a href="{{ route('tutor.attendance.index') }}" class="inline-flex items-center gap-2 px-4 py-2 text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white transition-colors">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            Back to History
        </a>
    </div>

    <!-- Status Banner -->
    <div class="@if($attendance->status === 'approved') bg-emerald-50 dark:bg-emerald-900/20 border-emerald-200 dark:border-emerald-800
                @elseif($attendance->status === 'rejected') bg-rose-50 dark:bg-rose-900/20 border-rose-200 dark:border-rose-800
                @else bg-amber-50 dark:bg-amber-900/20 border-amber-200 dark:border-amber-800
                @endif border rounded-xl p-4">
        <div class="flex items-center gap-3">
            @if($attendance->status === 'approved')
                <div class="w-10 h-10 bg-emerald-100 dark:bg-emerald-900/50 rounded-full flex items-center justify-center">
                    <svg class="w-6 h-6 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div>
                    <h3 class="font-semibold text-emerald-800 dark:text-emerald-300">Approved</h3>
                    <p class="text-sm text-emerald-600 dark:text-emerald-400">This attendance has been approved</p>
                </div>
            @elseif($attendance->status === 'rejected')
                <div class="w-10 h-10 bg-rose-100 dark:bg-rose-900/50 rounded-full flex items-center justify-center">
                    <svg class="w-6 h-6 text-rose-600 dark:text-rose-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div>
                    <h3 class="font-semibold text-rose-800 dark:text-rose-300">Rejected</h3>
                    <p class="text-sm text-rose-600 dark:text-rose-400">This attendance was rejected</p>
                </div>
            @else
                <div class="w-10 h-10 bg-amber-100 dark:bg-amber-900/50 rounded-full flex items-center justify-center">
                    <svg class="w-6 h-6 text-amber-600 dark:text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div>
                    <h3 class="font-semibold text-amber-800 dark:text-amber-300">Pending Approval</h3>
                    <p class="text-sm text-amber-600 dark:text-amber-400">Awaiting manager review</p>
                </div>
            @endif
        </div>
    </div>

    <!-- Main Content -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Attendance Details -->
        <div class="lg:col-span-2 glass-card rounded-2xl shadow-lg overflow-hidden">
            <div class="px-6 py-4 bg-gradient-to-r from-[#1D2A6D] to-[#4B51FF]">
                <h2 class="text-lg font-semibold text-white flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                    </svg>
                    Class Information
                </h2>
            </div>
            <div class="p-6 space-y-6">
                <!-- Student Info -->
                <div class="flex items-center gap-4 p-4 bg-slate-50 dark:bg-slate-800/50 rounded-xl">
                    <div class="w-14 h-14 bg-gradient-to-br from-[#4B51FF] to-[#22D3EE] rounded-full flex items-center justify-center text-white font-bold text-lg">
                        {{ strtoupper(substr($attendance->student->first_name, 0, 1)) }}{{ strtoupper(substr($attendance->student->last_name, 0, 1)) }}
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-slate-900 dark:text-white">
                            {{ $attendance->student->first_name }} {{ $attendance->student->last_name }}
                        </h3>
                        <div class="flex items-center gap-2 mt-1">
                            @if(isset($isStandIn) && $isStandIn)
                                <span class="px-2 py-0.5 text-xs font-semibold bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400 rounded">Stand-in</span>
                                @if($attendance->student->tutor)
                                    <span class="text-sm text-slate-500">Assigned to: {{ $attendance->student->tutor->first_name }} {{ $attendance->student->tutor->last_name }}</span>
                                @endif
                            @else
                                <span class="text-sm text-slate-500">Your student</span>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Stand-in Reason -->
                @if(isset($isStandIn) && $isStandIn && $attendance->stand_in_reason)
                    <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-xl p-4">
                        <label class="block text-sm font-medium text-blue-700 dark:text-blue-400 mb-1">Stand-in Reason</label>
                        <p class="text-blue-900 dark:text-blue-300">
                            @switch($attendance->stand_in_reason)
                                @case('tutor_sick') Assigned tutor is sick @break
                                @case('tutor_leave') Assigned tutor on leave @break
                                @case('tutor_emergency') Assigned tutor has emergency @break
                                @case('schedule_conflict') Schedule conflict @break
                                @case('manager_request') Manager requested cover @break
                                @default {{ ucfirst(str_replace('_', ' ', $attendance->stand_in_reason)) }}
                            @endswitch
                        </p>
                    </div>
                @endif

                <!-- Date & Time -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-slate-500 dark:text-slate-400 mb-1">Class Date</label>
                        <p class="text-lg font-semibold text-slate-900 dark:text-white">{{ $attendance->class_date->format('F d, Y') }}</p>
                        <p class="text-sm text-slate-500">{{ $attendance->class_date->format('l') }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-500 dark:text-slate-400 mb-1">Class Time</label>
                        <p class="text-lg font-semibold text-slate-900 dark:text-white">
                            {{ $attendance->class_time ? \Carbon\Carbon::parse($attendance->class_time)->format('g:i A') : 'N/A' }}
                        </p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-500 dark:text-slate-400 mb-1">Duration</label>
                        <p class="text-lg font-semibold text-slate-900 dark:text-white">{{ $attendance->duration_minutes }} minutes</p>
                    </div>
                </div>

                <!-- Topic -->
                @if($attendance->topic)
                    <div>
                        <label class="block text-sm font-medium text-slate-500 dark:text-slate-400 mb-1">Topic Covered</label>
                        <p class="text-slate-900 dark:text-white">{{ $attendance->topic }}</p>
                    </div>
                @endif

                <!-- Notes -->
                @if($attendance->notes)
                    <div>
                        <label class="block text-sm font-medium text-slate-500 dark:text-slate-400 mb-1">Notes</label>
                        <div class="p-4 bg-slate-50 dark:bg-slate-800/50 rounded-xl">
                            <p class="text-slate-700 dark:text-slate-300 whitespace-pre-wrap">{{ $attendance->notes }}</p>
                        </div>
                    </div>
                @endif

                <!-- Late Badge -->
                @if($attendance->is_late)
                    <div class="bg-rose-50 dark:bg-rose-900/20 border border-rose-200 dark:border-rose-800 rounded-xl p-4">
                        <div class="flex items-center gap-2 text-rose-700 dark:text-rose-400">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                            </svg>
                            <span class="font-medium">This attendance was submitted late</span>
                        </div>
                    </div>
                @endif
            </div>
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Status Card -->
            <div class="glass-card rounded-2xl shadow-lg overflow-hidden">
                <div class="px-6 py-4 border-b border-slate-200 dark:border-slate-700">
                    <h3 class="font-semibold text-slate-900 dark:text-white">Status Details</h3>
                </div>
                <div class="p-4 space-y-4">
                    <div>
                        <label class="block text-xs font-medium text-slate-500 dark:text-slate-400 mb-1">Current Status</label>
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold
                            @if($attendance->status === 'approved') badge-approved
                            @elseif($attendance->status === 'rejected') badge-late
                            @else badge-pending
                            @endif">
                            {{ ucfirst($attendance->status) }}
                        </span>
                    </div>

                    @if($attendance->approved_by)
                        <div>
                            <label class="block text-xs font-medium text-slate-500 dark:text-slate-400 mb-1">
                                {{ $attendance->status === 'approved' ? 'Approved By' : 'Reviewed By' }}
                            </label>
                            <p class="text-slate-900 dark:text-white">{{ $attendance->approver->name ?? 'Manager' }}</p>
                            @if($attendance->approved_at)
                                <p class="text-xs text-slate-500 mt-1">{{ $attendance->approved_at->format('M d, Y g:i A') }}</p>
                            @endif
                        </div>
                    @endif

                    @if($attendance->rejection_reason)
                        <div class="bg-rose-50 dark:bg-rose-900/20 rounded-lg p-3">
                            <label class="block text-xs font-medium text-rose-700 dark:text-rose-400 mb-1">Rejection Reason</label>
                            <p class="text-sm text-rose-600 dark:text-rose-300">{{ $attendance->rejection_reason }}</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Timeline Card -->
            <div class="glass-card rounded-2xl shadow-lg overflow-hidden">
                <div class="px-6 py-4 border-b border-slate-200 dark:border-slate-700">
                    <h3 class="font-semibold text-slate-900 dark:text-white">Timeline</h3>
                </div>
                <div class="p-4 space-y-4">
                    <div class="flex items-start gap-3">
                        <div class="w-2 h-2 bg-[#4B51FF] rounded-full mt-2"></div>
                        <div>
                            <p class="text-sm font-medium text-slate-900 dark:text-white">Submitted</p>
                            <p class="text-xs text-slate-500">{{ $attendance->created_at->format('M d, Y g:i A') }}</p>
                        </div>
                    </div>
                    @if($attendance->updated_at != $attendance->created_at)
                        <div class="flex items-start gap-3">
                            <div class="w-2 h-2 bg-slate-400 rounded-full mt-2"></div>
                            <div>
                                <p class="text-sm font-medium text-slate-900 dark:text-white">Last Updated</p>
                                <p class="text-xs text-slate-500">{{ $attendance->updated_at->format('M d, Y g:i A') }}</p>
                            </div>
                        </div>
                    @endif
                    @if($attendance->approved_at)
                        <div class="flex items-start gap-3">
                            <div class="w-2 h-2 {{ $attendance->status === 'approved' ? 'bg-emerald-500' : 'bg-rose-500' }} rounded-full mt-2"></div>
                            <div>
                                <p class="text-sm font-medium text-slate-900 dark:text-white">
                                    {{ $attendance->status === 'approved' ? 'Approved' : 'Rejected' }}
                                </p>
                                <p class="text-xs text-slate-500">{{ $attendance->approved_at->format('M d, Y g:i A') }}</p>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Actions -->
    <div class="flex flex-wrap items-center justify-between gap-4">
        <a href="{{ route('tutor.attendance.index') }}" class="inline-flex items-center gap-2 px-4 py-2 text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white transition-colors">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            Back to History
        </a>
        <a href="{{ route('tutor.attendance.create') }}" class="inline-flex items-center gap-2 px-4 py-2.5 bg-gradient-to-r from-[#1D2A6D] to-[#4B51FF] text-white font-medium rounded-xl hover:opacity-90 transition-all shadow-lg">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Submit Another
        </a>
    </div>
</div>
</x-tutor-layout>
