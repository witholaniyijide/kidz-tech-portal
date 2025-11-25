<x-tutor-layout>
    <!-- Page Header -->
    <div class="mb-8">
        <h1 class="text-4xl font-bold text-gray-900 dark:text-white mb-2">
            Create Report
        </h1>
        <p class="text-gray-600 dark:text-gray-400">
            Create a new student progress report
        </p>
    </div>

    <!-- Report Form -->
    <div class="max-w-4xl">
        <form action="{{ route('tutor.reports.store') }}" method="POST" id="reportForm"
            class="bg-white/20 dark:bg-gray-900/30 backdrop-blur-xl border border-white/10 rounded-2xl p-8 shadow-lg">
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
                        <option value="{{ $student->id }}" {{ old('student_id', request('student_id')) == $student->id ? 'selected' : '' }}>
                            {{ $student->fullName() }}
                        </option>
                    @endforeach
                </select>
                @error('student_id')
                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                @enderror
            </div>

            <!-- Report Title -->
            <div class="mb-6">
                <label for="title" class="block text-sm font-medium text-gray-900 dark:text-white mb-2">
                    Report Title <span class="text-red-500">*</span>
                </label>
                <input type="text" id="title" name="title" value="{{ old('title') }}" required maxlength="255"
                    class="w-full px-4 py-3 bg-white/50 dark:bg-gray-800/50 border border-gray-300 dark:border-gray-600 rounded-xl text-gray-900 dark:text-white focus:ring-2 focus:ring-purple-500 focus:border-transparent"
                    placeholder="e.g., October 2024 Progress Report">
                @error('title')
                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                @enderror
            </div>

            <!-- Month -->
            <div class="mb-6">
                <label for="month" class="block text-sm font-medium text-gray-900 dark:text-white mb-2">
                    Report Month <span class="text-red-500">*</span>
                </label>
                <input type="text" id="month" name="month" value="{{ old('month') }}" required maxlength="50"
                    class="w-full px-4 py-3 bg-white/50 dark:bg-gray-800/50 border border-gray-300 dark:border-gray-600 rounded-xl text-gray-900 dark:text-white focus:ring-2 focus:ring-purple-500 focus:border-transparent"
                    placeholder="e.g., October 2024">
                @error('month')
                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                @enderror
            </div>

            <!-- Period From/To -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div>
                    <label for="period_from" class="block text-sm font-medium text-gray-900 dark:text-white mb-2">
                        Period From (Optional)
                    </label>
                    <input type="date" id="period_from" name="period_from" value="{{ old('period_from') }}"
                        class="w-full px-4 py-3 bg-white/50 dark:bg-gray-800/50 border border-gray-300 dark:border-gray-600 rounded-xl text-gray-900 dark:text-white focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                    @error('period_from')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="period_to" class="block text-sm font-medium text-gray-900 dark:text-white mb-2">
                        Period To (Optional)
                    </label>
                    <input type="date" id="period_to" name="period_to" value="{{ old('period_to') }}"
                        class="w-full px-4 py-3 bg-white/50 dark:bg-gray-800/50 border border-gray-300 dark:border-gray-600 rounded-xl text-gray-900 dark:text-white focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                    @error('period_to')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Summary -->
            <div class="mb-6">
                <label for="summary" class="block text-sm font-medium text-gray-900 dark:text-white mb-2">
                    Summary (Optional)
                </label>
                <textarea id="summary" name="summary" rows="3" maxlength="1000"
                    class="w-full px-4 py-3 bg-white/50 dark:bg-gray-800/50 border border-gray-300 dark:border-gray-600 rounded-xl text-gray-900 dark:text-white focus:ring-2 focus:ring-purple-500 focus:border-transparent"
                    placeholder="Brief summary of the report...">{{ old('summary') }}</textarea>
                @error('summary')
                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                @enderror
            </div>

            <!-- Content -->
            <div class="mb-6">
                <label for="content" class="block text-sm font-medium text-gray-900 dark:text-white mb-2">
                    Report Content <span class="text-red-500">*</span>
                </label>
                <textarea id="content" name="content" rows="12" required
                    class="w-full px-4 py-3 bg-white/50 dark:bg-gray-800/50 border border-gray-300 dark:border-gray-600 rounded-xl text-gray-900 dark:text-white focus:ring-2 focus:ring-purple-500 focus:border-transparent"
                    placeholder="Detailed report content...">{{ old('content') }}</textarea>
                @error('content')
                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                @enderror
            </div>

            <!-- Rating -->
            <div class="mb-6">
                <label for="rating" class="block text-sm font-medium text-gray-900 dark:text-white mb-2">
                    Performance Rating (1-10, Optional)
                </label>
                <input type="number" id="rating" name="rating" value="{{ old('rating') }}" min="1" max="10"
                    class="w-full px-4 py-3 bg-white/50 dark:bg-gray-800/50 border border-gray-300 dark:border-gray-600 rounded-xl text-gray-900 dark:text-white focus:ring-2 focus:ring-purple-500 focus:border-transparent"
                    placeholder="1-10">
                @error('rating')
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
                        Save as draft to continue editing later, or submit directly for manager review.
                    </p>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex items-center justify-end space-x-4">
                <a href="{{ route('tutor.reports.index') }}"
                    class="px-6 py-3 bg-white/20 dark:bg-gray-800/30 text-gray-700 dark:text-gray-300 rounded-xl border border-white/10 hover:bg-white/30 dark:hover:bg-gray-800/50 transition-colors">
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
