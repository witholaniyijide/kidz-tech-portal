<x-app-layout>
    <x-slot name="header">
        {{ __('Attendance Details') }}
    </x-slot>
    <x-slot name="title">{{ __('Attendance Details') }}</x-slot>

    <div class="min-h-screen bg-gradient-to-br from-blue-50 via-purple-50 to-pink-50 dark:from-gray-900 dark:via-gray-800 dark:to-gray-900 py-8">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <!-- Action Button -->
            <div class="flex justify-end mb-4">
                <a href="{{ route('director.attendance.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white font-semibold rounded-lg transition-all">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                    Back
                </a>
            </div>
            <x-ui.glass-card>
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-xl font-bold text-gray-900 dark:text-white">Attendance Record</h3>
                    <x-ui.status-badge :status="$attendance->status" />
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Student</p>
                        <p class="font-medium text-gray-900 dark:text-white">{{ $attendance->student->first_name ?? 'N/A' }} {{ $attendance->student->last_name ?? '' }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Tutor</p>
                        <p class="font-medium text-gray-900 dark:text-white">{{ $attendance->tutor->first_name ?? 'N/A' }} {{ $attendance->tutor->last_name ?? '' }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Class Date</p>
                        <p class="font-medium text-gray-900 dark:text-white">{{ $attendance->class_date ? \Carbon\Carbon::parse($attendance->class_date)->format('M d, Y') : 'N/A' }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Submitted At</p>
                        <p class="font-medium text-gray-900 dark:text-white">{{ $attendance->created_at->format('M d, Y H:i') }}</p>
                    </div>
                </div>

                @if($attendance->status == 'pending')
                <div class="mt-8 p-4 bg-yellow-50 dark:bg-yellow-900/20 rounded-lg">
                    <h4 class="font-semibold text-yellow-800 dark:text-yellow-200 mb-4">Approve Attendance</h4>
                    <form method="POST" action="{{ route('director.attendance.approve', $attendance) }}">
                        @csrf
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Comment (Optional)</label>
                            <textarea name="comment" rows="2" class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 focus:ring-blue-500"></textarea>
                        </div>
                        <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-all">Approve Attendance</button>
                    </form>
                </div>
                @endif

                @if($attendance->approved_at)
                <div class="mt-6 p-4 bg-green-50 dark:bg-green-900/20 rounded-lg">
                    <p class="text-sm text-green-800 dark:text-green-200">
                        <strong>Approved:</strong> {{ \Carbon\Carbon::parse($attendance->approved_at)->format('M d, Y H:i') }}
                        @if($attendance->approval_comment)
                        <br><strong>Comment:</strong> {{ $attendance->approval_comment }}
                        @endif
                    </p>
                </div>
                @endif
            </x-ui.glass-card>
        </div>
    </div>
</x-app-layout>
