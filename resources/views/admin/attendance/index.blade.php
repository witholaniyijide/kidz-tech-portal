<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-2xl text-white">{{ __('Attendance Management') }}</h2>
    </x-slot>
    <x-slot name="title">{{ __('Admin - Attendance') }}</x-slot>

    <div class="min-h-screen bg-gradient-to-br from-teal-50 via-cyan-50 to-blue-50 dark:from-slate-900 dark:via-slate-800 dark:to-slate-900 py-8 relative overflow-hidden">
        <div class="absolute top-0 left-0 w-72 h-72 bg-teal-300 dark:bg-teal-900 rounded-full mix-blend-multiply filter blur-xl opacity-70 animate-float"></div>
        <div class="absolute top-0 right-0 w-72 h-72 bg-cyan-300 dark:bg-cyan-900 rounded-full mix-blend-multiply filter blur-xl opacity-70 animate-float" style="animation-delay: 2s;"></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            @if(session('success'))
                <div class="mb-6 bg-emerald-100 dark:bg-emerald-900/30 border border-emerald-400 text-emerald-800 dark:text-emerald-400 px-6 py-4 rounded-xl">
                    {{ session('success') }}
                </div>
            @endif

            {{-- Header --}}
            <div class="flex flex-wrap items-center justify-between gap-4 mb-8">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Attendance Management</h1>
                    <p class="text-gray-600 dark:text-gray-400 mt-1">Review and approve tutor-submitted attendance records</p>
                </div>
            </div>

            {{-- Stat Cards --}}
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
                <div class="bg-white/60 dark:bg-gray-800/60 backdrop-blur-md border border-white/20 rounded-2xl p-5 shadow">
                    <div class="text-3xl font-bold text-gray-900 dark:text-white">{{ $stats['total'] ?? 0 }}</div>
                    <div class="text-sm text-gray-600 dark:text-gray-400">Total Records</div>
                </div>
                <a href="{{ route('admin.attendance.index', ['status' => 'pending']) }}" class="bg-amber-50 dark:bg-amber-900/30 border border-amber-200 dark:border-amber-800/30 rounded-2xl p-5 shadow hover:shadow-lg transition-shadow">
                    <div class="text-3xl font-bold text-amber-600">{{ $stats['pending'] ?? 0 }}</div>
                    <div class="text-sm text-amber-700 dark:text-amber-400">Pending Approval</div>
                </a>
                <div class="bg-emerald-50 dark:bg-emerald-900/30 border border-emerald-200 dark:border-emerald-800/30 rounded-2xl p-5 shadow">
                    <div class="text-3xl font-bold text-emerald-600">{{ $stats['approved'] ?? 0 }}</div>
                    <div class="text-sm text-emerald-700 dark:text-emerald-400">Approved</div>
                </div>
                <a href="{{ route('admin.attendance.index', ['late_only' => 1]) }}" class="bg-red-50 dark:bg-red-900/30 border border-red-200 dark:border-red-800/30 rounded-2xl p-5 shadow hover:shadow-lg transition-shadow">
                    <div class="text-3xl font-bold text-red-600">{{ $stats['late'] ?? 0 }}</div>
                    <div class="text-sm text-red-700 dark:text-red-400">Late Submissions</div>
                </a>
            </div>

            {{-- Filters --}}
            <div class="bg-white/60 dark:bg-gray-800/60 backdrop-blur-md border border-white/20 rounded-2xl p-5 shadow mb-6">
                <form method="GET" class="flex flex-wrap items-end gap-4">
                    <div class="w-36">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Status</label>
                        <select name="status" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg focus:ring-2 focus:ring-teal-500">
                            <option value="">All</option>
                            <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="approved" {{ request('status') === 'approved' ? 'selected' : '' }}>Approved</option>
                        </select>
                    </div>
                    <div class="w-40">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Class Date</label>
                        <input type="date" name="date" value="{{ request('date') }}"
                               class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg focus:ring-2 focus:ring-teal-500">
                    </div>
                    <div class="w-44">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Tutor</label>
                        <select name="tutor_id" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg focus:ring-2 focus:ring-teal-500">
                            <option value="">All Tutors</option>
                            @foreach($tutors as $tutor)
                                <option value="{{ $tutor->id }}" {{ request('tutor_id') == $tutor->id ? 'selected' : '' }}>
                                    {{ $tutor->first_name }} {{ $tutor->last_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="w-44">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Student</label>
                        <select name="student_id" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg focus:ring-2 focus:ring-teal-500">
                            <option value="">All Students</option>
                            @foreach($students as $student)
                                <option value="{{ $student->id }}" {{ request('student_id') == $student->id ? 'selected' : '' }}>
                                    {{ $student->first_name }} {{ $student->last_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="flex items-center gap-2">
                        <input type="checkbox" name="late_only" id="late_only" value="1" {{ request('late_only') ? 'checked' : '' }}
                               class="w-4 h-4 text-red-600 border-gray-300 rounded focus:ring-red-500">
                        <label for="late_only" class="text-sm text-gray-700 dark:text-gray-300">Late Only</label>
                    </div>
                    <button type="submit" class="px-4 py-2 bg-teal-600 text-white rounded-lg hover:bg-teal-700 transition-colors">
                        Filter
                    </button>
                    @if(request()->hasAny(['status', 'date', 'tutor_id', 'student_id', 'late_only']))
                        <a href="{{ route('admin.attendance.index') }}" class="px-4 py-2 bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-300 dark:hover:bg-gray-600 transition-colors">
                            Clear
                        </a>
                    @endif
                </form>
            </div>

            {{-- Attendance Table --}}
            <div class="bg-white/60 dark:bg-gray-800/60 backdrop-blur-md border border-white/20 rounded-2xl shadow overflow-hidden">
                @if($attendances->isEmpty())
                    <div class="p-12 text-center">
                        <div class="text-6xl mb-4">📋</div>
                        <h3 class="text-xl font-semibold text-gray-700 dark:text-gray-300 mb-2">No Attendance Records Found</h3>
                        <p class="text-gray-500 dark:text-gray-400">Attendance records submitted by tutors will appear here for review.</p>
                    </div>
                @else
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead class="bg-gray-50 dark:bg-gray-700/50">
                                <tr>
                                    <th class="px-4 py-4 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">Student</th>
                                    <th class="px-4 py-4 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">Tutor</th>
                                    <th class="px-4 py-4 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">Class Date</th>
                                    <th class="px-4 py-4 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">Topic</th>
                                    <th class="px-4 py-4 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">Submitted</th>
                                    <th class="px-4 py-4 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">Status</th>
                                    <th class="px-4 py-4 text-center text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                                @foreach($attendances as $attendance)
                                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/30 transition-colors {{ $attendance->status === 'pending' ? 'bg-amber-50/50 dark:bg-amber-900/10' : '' }}">
                                        <td class="px-4 py-4">
                                            <div class="flex items-center">
                                                <div class="w-8 h-8 bg-gradient-to-br from-teal-400 to-cyan-500 rounded-full flex items-center justify-center text-white font-bold text-xs mr-2">
                                                    {{ strtoupper(substr($attendance->student->first_name ?? 'U', 0, 1)) }}
                                                </div>
                                                <span class="font-medium text-gray-900 dark:text-white">{{ $attendance->student->first_name ?? 'Unknown' }} {{ $attendance->student->last_name ?? '' }}</span>
                                            </div>
                                        </td>
                                        <td class="px-4 py-4 text-sm text-gray-600 dark:text-gray-400">
                                            {{ $attendance->tutor->first_name ?? 'Unknown' }} {{ $attendance->tutor->last_name ?? '' }}
                                        </td>
                                        <td class="px-4 py-4">
                                            <div class="text-sm text-gray-900 dark:text-white">{{ $attendance->class_date?->format('M j, Y') }}</div>
                                            <div class="text-xs text-gray-500">{{ $attendance->duration ?? 60 }} mins</div>
                                        </td>
                                        <td class="px-4 py-4">
                                            <div class="text-sm text-gray-900 dark:text-white max-w-xs truncate">{{ $attendance->topic ?? '-' }}</div>
                                        </td>
                                        <td class="px-4 py-4">
                                            {{-- Submission timestamp - key for determining if late --}}
                                            <div class="text-sm text-gray-900 dark:text-white">{{ $attendance->created_at->format('M j, Y') }}</div>
                                            <div class="text-xs text-gray-500">{{ $attendance->created_at->format('g:i A') }}</div>
                                            <div class="text-xs text-gray-400">{{ $attendance->created_at->diffForHumans() }}</div>
                                        </td>
                                        <td class="px-4 py-4">
                                            @if($attendance->status === 'approved')
                                                @if($attendance->is_late)
                                                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400">
                                                        Late ⚠️
                                                    </span>
                                                @else
                                                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-emerald-100 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-400">
                                                        Approved ✓
                                                    </span>
                                                @endif
                                            @else
                                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-amber-100 text-amber-700 dark:bg-amber-900/30 dark:text-amber-400 animate-pulse">
                                                    Pending
                                                </span>
                                            @endif
                                        </td>
                                        <td class="px-4 py-4">
                                            <div class="flex items-center justify-center gap-1">
                                                {{-- View --}}
                                                <a href="{{ route('admin.attendance.show', $attendance) }}" class="p-1.5 text-gray-600 dark:text-gray-400 hover:text-teal-600 dark:hover:text-teal-400 hover:bg-teal-50 dark:hover:bg-teal-900/20 rounded-lg transition-colors" title="View Details">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                                    </svg>
                                                </a>

                                                @if($attendance->status === 'pending')
                                                    {{-- Approve --}}
                                                    <form action="{{ route('admin.attendance.approve', $attendance) }}" method="POST" class="inline">
                                                        @csrf
                                                        <button type="submit" class="p-1.5 text-emerald-600 hover:bg-emerald-50 dark:hover:bg-emerald-900/20 rounded-lg transition-colors" title="Approve">
                                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                                            </svg>
                                                        </button>
                                                    </form>

                                                    {{-- Mark Late --}}
                                                    <form action="{{ route('admin.attendance.late', $attendance) }}" method="POST" class="inline">
                                                        @csrf
                                                        <button type="submit" class="p-1.5 text-amber-600 hover:bg-amber-50 dark:hover:bg-amber-900/20 rounded-lg transition-colors" title="Mark as Late & Approve">
                                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                            </svg>
                                                        </button>
                                                    </form>
                                                @endif

                                                {{-- Delete (Request Resubmission) --}}
                                                <form action="{{ route('admin.attendance.destroy', $attendance) }}" method="POST" class="inline" 
                                                      onsubmit="return confirm('Delete this attendance record?\n\nThe tutor ({{ $attendance->tutor->first_name ?? 'Unknown' }}) will need to resubmit.')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="p-1.5 text-red-600 hover:bg-red-50 dark:hover:bg-red-900/20 rounded-lg transition-colors" title="Delete (Request Resubmission)">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                        </svg>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    @if($attendances->hasPages())
                        <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700">
                            {{ $attendances->withQueryString()->links() }}
                        </div>
                    @endif
                @endif
            </div>

            {{-- Legend --}}
            <div class="mt-6 bg-white/40 dark:bg-gray-800/40 backdrop-blur-sm rounded-xl p-4">
                <h4 class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Action Legend</h4>
                <div class="flex flex-wrap gap-4 text-xs text-gray-600 dark:text-gray-400">
                    <div class="flex items-center gap-1">
                        <span class="w-5 h-5 bg-teal-100 text-teal-600 rounded flex items-center justify-center">👁</span>
                        <span>View Details</span>
                    </div>
                    <div class="flex items-center gap-1">
                        <span class="w-5 h-5 bg-emerald-100 text-emerald-600 rounded flex items-center justify-center">✓</span>
                        <span>Approve</span>
                    </div>
                    <div class="flex items-center gap-1">
                        <span class="w-5 h-5 bg-amber-100 text-amber-600 rounded flex items-center justify-center">⏰</span>
                        <span>Mark Late & Approve</span>
                    </div>
                    <div class="flex items-center gap-1">
                        <span class="w-5 h-5 bg-red-100 text-red-600 rounded flex items-center justify-center">🗑</span>
                        <span>Delete (Request Resubmission)</span>
                    </div>
                </div>
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
