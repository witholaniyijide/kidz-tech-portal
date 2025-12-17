<x-app-layout>
    <x-slot name="header">
        {{ __('Edit Attendance Record') }}
    </x-slot>
    <x-slot name="title">{{ __('Edit Attendance') }}</x-slot>

    <div class="min-h-screen bg-gradient-to-br from-blue-50 via-purple-50 to-pink-50 dark:from-gray-900 dark:via-gray-800 dark:to-gray-900 py-8">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">

            <!-- Back Button -->
            <div class="mb-4">
                <a href="{{ route('director.attendance.index') }}" class="inline-flex items-center text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white transition-colors">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    Back to Attendance
                </a>
            </div>

            <x-ui.glass-card>
                <div class="mb-6">
                    <h3 class="text-xl font-bold text-gray-900 dark:text-white">Edit Attendance Record</h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Update the attendance information below</p>
                </div>

                <form method="POST" action="{{ route('director.attendance.update', $attendance) }}">
                    @csrf
                    @method('PUT')

                    <div class="space-y-5">
                        <!-- Student Selection -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Student *</label>
                            <select name="student_id" required class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                <option value="">Select Student</option>
                                @foreach($students as $student)
                                    <option value="{{ $student->id }}" {{ old('student_id', $attendance->student_id) == $student->id ? 'selected' : '' }}>
                                        {{ $student->first_name }} {{ $student->last_name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('student_id')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Tutor Selection -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Tutor *</label>
                            <select name="tutor_id" required class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                <option value="">Select Tutor</option>
                                @foreach($tutors as $tutor)
                                    <option value="{{ $tutor->id }}" {{ old('tutor_id', $attendance->tutor_id) == $tutor->id ? 'selected' : '' }}>
                                        {{ $tutor->first_name }} {{ $tutor->last_name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('tutor_id')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Class Date -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Class Date *</label>
                            <input type="date" name="class_date" value="{{ old('class_date', $attendance->class_date ? \Carbon\Carbon::parse($attendance->class_date)->format('Y-m-d') : '') }}" required
                                   class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            @error('class_date')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Attendance Status -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Attendance Status *</label>
                            <select name="attendance_status" required class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                <option value="present" {{ old('attendance_status', $attendance->attendance_status ?? 'present') == 'present' ? 'selected' : '' }}>Present</option>
                                <option value="absent" {{ old('attendance_status', $attendance->attendance_status ?? '') == 'absent' ? 'selected' : '' }}>Absent</option>
                                <option value="late" {{ old('attendance_status', $attendance->attendance_status ?? '') == 'late' ? 'selected' : '' }}>Late</option>
                                <option value="excused" {{ old('attendance_status', $attendance->attendance_status ?? '') == 'excused' ? 'selected' : '' }}>Excused</option>
                            </select>
                            @error('attendance_status')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Approval Status -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Approval Status</label>
                            <select name="status" class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                <option value="pending" {{ old('status', $attendance->status) == 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="approved" {{ old('status', $attendance->status) == 'approved' ? 'selected' : '' }}>Approved</option>
                                <option value="rejected" {{ old('status', $attendance->status) == 'rejected' ? 'selected' : '' }}>Rejected</option>
                            </select>
                            @error('status')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Class Time -->
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Start Time</label>
                                <input type="time" name="class_start_time" value="{{ old('class_start_time', $attendance->class_start_time ? \Carbon\Carbon::parse($attendance->class_start_time)->format('H:i') : '') }}"
                                       class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                @error('class_start_time')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">End Time</label>
                                <input type="time" name="class_end_time" value="{{ old('class_end_time', $attendance->class_end_time ? \Carbon\Carbon::parse($attendance->class_end_time)->format('H:i') : '') }}"
                                       class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                @error('class_end_time')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Topics Covered -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Topics Covered</label>
                            <textarea name="topics_covered" rows="3" placeholder="What was covered in the class..."
                                      class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 focus:ring-blue-500">{{ old('topics_covered', $attendance->topics_covered) }}</textarea>
                            @error('topics_covered')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Notes -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Notes</label>
                            <textarea name="notes" rows="2" placeholder="Additional notes..."
                                      class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 focus:ring-blue-500">{{ old('notes', $attendance->notes) }}</textarea>
                            @error('notes')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="flex justify-end space-x-3 mt-6 pt-4 border-t border-gray-200 dark:border-gray-700">
                        <a href="{{ route('director.attendance.index') }}"
                           class="px-4 py-2 bg-gray-200 dark:bg-gray-600 text-gray-700 dark:text-gray-200 rounded-lg hover:bg-gray-300 dark:hover:bg-gray-500 transition-all">
                            Cancel
                        </a>
                        <button type="submit" class="px-4 py-2 bg-gradient-to-r from-blue-600 to-purple-600 text-white rounded-lg hover:from-blue-700 hover:to-purple-700 transition-all">
                            Update Attendance
                        </button>
                    </div>
                </form>
            </x-ui.glass-card>
        </div>
    </div>
</x-app-layout>
