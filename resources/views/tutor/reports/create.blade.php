<x-tutor-layout>
    <!-- Page Header -->
    <div class="mb-8">
        <h1 class="text-4xl font-bold text-gray-900 dark:text-white mb-2">
            Create Student Report
        </h1>
        <p class="text-gray-600 dark:text-gray-400">
            Create a comprehensive progress report for your student
        </p>
    </div>

    <!-- Report Form -->
    <div class="max-w-5xl">
        <form action="{{ route('tutor.reports.store') }}" method="POST" id="reportForm"
            class="bg-white/20 dark:bg-gray-900/30 backdrop-blur-xl border border-white/10 rounded-2xl p-8 shadow-lg">
            @csrf

            <!-- Student Selection -->
            <div class="mb-6">
                <label for="student_id" class="block text-sm font-semibold text-gray-900 dark:text-white mb-2">
                    Student <span class="text-red-500">*</span>
                </label>
                <select id="student_id" name="student_id" required
                    class="w-full px-4 py-3 bg-white/50 dark:bg-gray-800/50 border border-gray-300 dark:border-gray-600 rounded-xl text-gray-900 dark:text-white focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                    <option value="">Select a student</option>
                    @foreach($students as $student)
                        <option value="{{ $student->id }}" {{ old('student_id', request('student_id')) == $student->id ? 'selected' : '' }}>
                            {{ $student->fullName() }}
                        </option>
                    @endforeach
                </select>
                @error('student_id')
                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                @enderror
            </div>

            <!-- Month -->
            <div class="mb-6">
                <label for="month" class="block text-sm font-semibold text-gray-900 dark:text-white mb-2">
                    Report Month <span class="text-red-500">*</span>
                </label>
                <input type="month" id="month" name="month" value="{{ old('month', now()->format('Y-m')) }}" required
                    class="w-full px-4 py-3 bg-white/50 dark:bg-gray-800/50 border border-gray-300 dark:border-gray-600 rounded-xl text-gray-900 dark:text-white focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                @error('month')
                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                @enderror
            </div>

            <!-- Progress Summary -->
            <div class="mb-6">
                <label for="progress_summary" class="block text-sm font-semibold text-gray-900 dark:text-white mb-2">
                    Progress Summary <span class="text-red-500">*</span>
                </label>
                <textarea id="progress_summary" name="progress_summary" rows="4" required
                    class="w-full px-4 py-3 bg-white/50 dark:bg-gray-800/50 border border-gray-300 dark:border-gray-600 rounded-xl text-gray-900 dark:text-white focus:ring-2 focus:ring-purple-500 focus:border-transparent"
                    placeholder="Provide an overview of the student's progress this month...">{{ old('progress_summary') }}</textarea>
                @error('progress_summary')
                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                @enderror
            </div>

            <!-- Strengths -->
            <div class="mb-6">
                <label for="strengths" class="block text-sm font-semibold text-gray-900 dark:text-white mb-2">
                    Strengths <span class="text-red-500">*</span>
                </label>
                <textarea id="strengths" name="strengths" rows="4" required
                    class="w-full px-4 py-3 bg-white/50 dark:bg-gray-800/50 border border-gray-300 dark:border-gray-600 rounded-xl text-gray-900 dark:text-white focus:ring-2 focus:ring-purple-500 focus:border-transparent"
                    placeholder="What areas did the student excel in this month?">{{ old('strengths') }}</textarea>
                @error('strengths')
                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                @enderror
            </div>

            <!-- Weaknesses -->
            <div class="mb-6">
                <label for="weaknesses" class="block text-sm font-semibold text-gray-900 dark:text-white mb-2">
                    Areas for Improvement <span class="text-red-500">*</span>
                </label>
                <textarea id="weaknesses" name="weaknesses" rows="4" required
                    class="w-full px-4 py-3 bg-white/50 dark:bg-gray-800/50 border border-gray-300 dark:border-gray-600 rounded-xl text-gray-900 dark:text-white focus:ring-2 focus:ring-purple-500 focus:border-transparent"
                    placeholder="What areas need improvement or more focus?">{{ old('weaknesses') }}</textarea>
                @error('weaknesses')
                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                @enderror
            </div>

            <!-- Next Steps -->
            <div class="mb-6">
                <label for="next_steps" class="block text-sm font-semibold text-gray-900 dark:text-white mb-2">
                    Next Steps & Recommendations <span class="text-red-500">*</span>
                </label>
                <textarea id="next_steps" name="next_steps" rows="4" required
                    class="w-full px-4 py-3 bg-white/50 dark:bg-gray-800/50 border border-gray-300 dark:border-gray-600 rounded-xl text-gray-900 dark:text-white focus:ring-2 focus:ring-purple-500 focus:border-transparent"
                    placeholder="What are the recommended next steps for this student?">{{ old('next_steps') }}</textarea>
                @error('next_steps')
                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                @enderror
            </div>

            <!-- Attendance Score & Performance Rating -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <!-- Attendance Score -->
                <div>
                    <label for="attendance_score" class="block text-sm font-semibold text-gray-900 dark:text-white mb-2">
                        Attendance Score (0-100) <span class="text-red-500">*</span>
                    </label>
                    <input type="number" id="attendance_score" name="attendance_score" value="{{ old('attendance_score') }}"
                        min="0" max="100" required
                        class="w-full px-4 py-3 bg-white/50 dark:bg-gray-800/50 border border-gray-300 dark:border-gray-600 rounded-xl text-gray-900 dark:text-white focus:ring-2 focus:ring-purple-500 focus:border-transparent"
                        placeholder="0-100">
                    @error('attendance_score')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Performance Rating -->
                <div>
                    <label for="performance_rating" class="block text-sm font-semibold text-gray-900 dark:text-white mb-2">
                        Performance Rating <span class="text-red-500">*</span>
                    </label>
                    <select id="performance_rating" name="performance_rating" required
                        class="w-full px-4 py-3 bg-white/50 dark:bg-gray-800/50 border border-gray-300 dark:border-gray-600 rounded-xl text-gray-900 dark:text-white focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                        <option value="">Select rating</option>
                        <option value="excellent" {{ old('performance_rating') === 'excellent' ? 'selected' : '' }}>Excellent</option>
                        <option value="good" {{ old('performance_rating') === 'good' ? 'selected' : '' }}>Good</option>
                        <option value="average" {{ old('performance_rating') === 'average' ? 'selected' : '' }}>Average</option>
                        <option value="poor" {{ old('performance_rating') === 'poor' ? 'selected' : '' }}>Poor</option>
                    </select>
                    @error('performance_rating')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Info Box -->
            <div class="mb-8 bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-xl p-4">
                <div class="flex items-start">
                    <svg class="w-5 h-5 text-blue-600 dark:text-blue-400 mt-0.5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <p class="text-sm text-blue-800 dark:text-blue-300">
                        Save as draft to continue editing later, or submit directly for manager review. Once submitted, the report cannot be edited unless returned by a manager.
                    </p>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex items-center justify-end space-x-4">
                <a href="{{ route('tutor.reports.index') }}"
                    class="px-6 py-3 bg-white/20 dark:bg-gray-800/30 text-gray-700 dark:text-gray-300 rounded-xl border border-white/10 hover:bg-white/30 dark:hover:bg-gray-800/50 transition-colors font-medium">
                    Cancel
                </a>
                <button type="submit" name="status" value="draft"
                    class="px-6 py-3 bg-white/20 dark:bg-gray-800/30 text-gray-700 dark:text-gray-300 rounded-xl border border-white/10 hover:bg-white/30 dark:hover:bg-gray-800/50 transition-colors font-medium">
                    Save as Draft
                </button>
                <button type="submit" name="status" value="submitted" id="submitBtn"
                    class="px-6 py-3 bg-gradient-to-r from-purple-500 to-pink-500 text-white rounded-xl hover:shadow-lg hover:-translate-y-0.5 transition-all font-medium">
                    Submit for Review
                </button>
            </div>
        </form>
    </div>

    @push('scripts')
    <script>
        // Confirm before submitting
        document.getElementById('submitBtn').addEventListener('click', function(e) {
            if (!confirm('Are you sure you want to submit this report? Once submitted, it will be reviewed by the manager.')) {
                e.preventDefault();
            }
        });
    </script>
    @endpush
</x-tutor-layout>
