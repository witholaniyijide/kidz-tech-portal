<x-tutor-layout>
    <!-- Page Header -->
    <div class="mb-8">
        <h1 class="text-4xl font-bold text-gray-900 dark:text-white mb-2">
            Submit Attendance
        </h1>
        <p class="text-gray-600 dark:text-gray-400">
            Record class attendance for your students
        </p>
    </div>

    <!-- Attendance Form -->
    <div class="max-w-3xl">
        <form action="{{ route('tutor.attendance.store') }}" method="POST" class="bg-white/20 dark:bg-gray-900/30 backdrop-blur-xl border border-white/10 rounded-2xl p-8 shadow-lg">
            @csrf

            <!-- Student Selection -->
            <div class="mb-6">
                <label for="student_id" class="block text-sm font-medium text-gray-900 dark:text-white mb-2">
                    Student <span class="text-red-500">*</span>
                </label>
                <select id="student_id" name="student_id" required
                    class="w-full px-4 py-3 bg-white/50 dark:bg-gray-800/50 border border-gray-300 dark:border-gray-600 rounded-xl text-gray-900 dark:text-white focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                    <option value="">Select a student</option>
                    @foreach($students as $student)
                        <option value="{{ $student->id }}" {{ old('student_id') == $student->id ? 'selected' : '' }}>
                            {{ $student->fullName() }}
                        </option>
                    @endforeach
                </select>
                @error('student_id')
                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                @enderror
            </div>

            <!-- Class Date -->
            <div class="mb-6">
                <label for="class_date" class="block text-sm font-medium text-gray-900 dark:text-white mb-2">
                    Class Date <span class="text-red-500">*</span>
                </label>
                <input type="date" id="class_date" name="class_date" value="{{ old('class_date', date('Y-m-d')) }}" required max="{{ date('Y-m-d') }}"
                    class="w-full px-4 py-3 bg-white/50 dark:bg-gray-800/50 border border-gray-300 dark:border-gray-600 rounded-xl text-gray-900 dark:text-white focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                @error('class_date')
                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                @enderror
            </div>

            <!-- Class Time -->
            <div class="mb-6">
                <label for="class_time" class="block text-sm font-medium text-gray-900 dark:text-white mb-2">
                    Class Time <span class="text-red-500">*</span>
                </label>
                <input type="time" id="class_time" name="class_time" value="{{ old('class_time') }}" required
                    class="w-full px-4 py-3 bg-white/50 dark:bg-gray-800/50 border border-gray-300 dark:border-gray-600 rounded-xl text-gray-900 dark:text-white focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                @error('class_time')
                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                @enderror
            </div>

            <!-- Duration -->
            <div class="mb-6">
                <label for="duration_minutes" class="block text-sm font-medium text-gray-900 dark:text-white mb-2">
                    Duration (minutes) <span class="text-red-500">*</span>
                </label>
                <input type="number" id="duration_minutes" name="duration_minutes" value="{{ old('duration_minutes', 60) }}" required min="15" max="240"
                    class="w-full px-4 py-3 bg-white/50 dark:bg-gray-800/50 border border-gray-300 dark:border-gray-600 rounded-xl text-gray-900 dark:text-white focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                @error('duration_minutes')
                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                @enderror
                <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Enter duration between 15 and 240 minutes</p>
            </div>

            <!-- Topic -->
            <div class="mb-6">
                <label for="topic" class="block text-sm font-medium text-gray-900 dark:text-white mb-2">
                    Topic (Optional)
                </label>
                <input type="text" id="topic" name="topic" value="{{ old('topic') }}" maxlength="255"
                    class="w-full px-4 py-3 bg-white/50 dark:bg-gray-800/50 border border-gray-300 dark:border-gray-600 rounded-xl text-gray-900 dark:text-white focus:ring-2 focus:ring-purple-500 focus:border-transparent"
                    placeholder="e.g., Introduction to Python">
                @error('topic')
                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                @enderror
            </div>

            <!-- Notes -->
            <div class="mb-6">
                <label for="notes" class="block text-sm font-medium text-gray-900 dark:text-white mb-2">
                    Notes (Optional)
                </label>
                <textarea id="notes" name="notes" rows="4" maxlength="2000"
                    class="w-full px-4 py-3 bg-white/50 dark:bg-gray-800/50 border border-gray-300 dark:border-gray-600 rounded-xl text-gray-900 dark:text-white focus:ring-2 focus:ring-purple-500 focus:border-transparent"
                    placeholder="Any additional notes about the class...">{{ old('notes') }}</textarea>
                @error('notes')
                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                @enderror
            </div>

            <!-- Info Box -->
            <div class="mb-6 bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-xl p-4">
                <div class="flex items-start">
                    <svg class="w-5 h-5 text-blue-600 dark:text-blue-400 mt-0.5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <p class="text-sm text-blue-800 dark:text-blue-300">
                        Attendance will be reviewed by your Manager before approval. You'll be notified once it's processed.
                    </p>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex items-center justify-end space-x-4">
                <a href="{{ route('tutor.dashboard') }}"
                    class="px-6 py-3 bg-white/20 dark:bg-gray-800/30 text-gray-700 dark:text-gray-300 rounded-xl border border-white/10 hover:bg-white/30 dark:hover:bg-gray-800/50 transition-colors">
                    Cancel
                </a>
                <button type="submit"
                    class="px-6 py-3 bg-gradient-to-r from-purple-500 to-pink-500 text-white rounded-xl hover:shadow-lg hover:-translate-y-0.5 transition-all font-medium">
                    Submit Attendance
                </button>
            </div>
        </form>
    </div>
</x-tutor-layout>
