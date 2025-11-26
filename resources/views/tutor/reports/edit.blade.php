<x-tutor-layout>
    <!-- Breadcrumbs -->
    <x-tutor.breadcrumbs :items="[
        ['label' => 'My Reports', 'url' => route('tutor.reports.index')],
        ['label' => 'Edit Report']
    ]" />

    <!-- Page Header -->
    <div class="mb-8">
        <h1 class="text-4xl font-bold text-gray-900 dark:text-white mb-2">
            Edit Report
        </h1>
        <p class="text-gray-600 dark:text-gray-400">
            Update your student progress report
        </p>
    </div>

    @if(!in_array($report->status, ['draft', 'returned']))
        <!-- Submission Lockdown Notice -->
        <div class="mb-6 bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-800 rounded-xl p-4">
            <div class="flex items-start">
                <svg class="w-5 h-5 text-yellow-600 dark:text-yellow-400 mt-0.5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>
                <div>
                    <h3 class="font-semibold text-yellow-800 dark:text-yellow-300 mb-1">This report has been submitted and cannot be edited.</h3>
                    <p class="text-sm text-yellow-700 dark:text-yellow-400">
                        Only draft or returned reports can be edited. Contact your manager if changes are needed.
                    </p>
                </div>
            </div>
        </div>
    @endif

    <!-- Report Form -->
    <div class="max-w-5xl">
        <form action="{{ route('tutor.reports.update', $report) }}" method="POST" id="reportForm"
            class="bg-white/20 dark:bg-gray-900/30 backdrop-blur-xl border border-white/10 rounded-2xl p-8 shadow-lg">
            @csrf
            @method('PUT')

            <!-- Status Badge -->
            <div class="mb-6">
                <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-medium
                    @if($report->status === 'draft') bg-gray-100 text-gray-800 dark:bg-gray-900/30 dark:text-gray-400
                    @elseif($report->status === 'submitted') bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-400
                    @elseif($report->status === 'returned') bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-400
                    @else bg-purple-100 text-purple-800 dark:bg-purple-900/30 dark:text-purple-400
                    @endif">
                    {{ ucfirst($report->status) }}
                </span>
            </div>

            <!-- Student Selection -->
            <div class="mb-6">
                <label for="student_id" class="block text-sm font-semibold text-gray-900 dark:text-white mb-2">
                    Student <span class="text-red-500">*</span>
                </label>
                <select id="student_id" name="student_id" required
                    {{ !in_array($report->status, ['draft', 'returned']) ? 'disabled' : '' }}
                    class="w-full px-4 py-3 bg-white/50 dark:bg-gray-800/50 border border-gray-300 dark:border-gray-600 rounded-xl text-gray-900 dark:text-white focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                    <option value="">Select a student</option>
                    @foreach($students as $student)
                        <option value="{{ $student->id }}" {{ old('student_id', $report->student_id) == $student->id ? 'selected' : '' }}>
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
                <input type="month" id="month" name="month" value="{{ old('month', $report->month) }}" required
                    {{ !in_array($report->status, ['draft', 'returned']) ? 'disabled' : '' }}
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
                    {{ !in_array($report->status, ['draft', 'returned']) ? 'disabled' : '' }}
                    class="w-full px-4 py-3 bg-white/50 dark:bg-gray-800/50 border border-gray-300 dark:border-gray-600 rounded-xl text-gray-900 dark:text-white focus:ring-2 focus:ring-purple-500 focus:border-transparent"
                    placeholder="Provide an overview of the student's progress this month...">{{ old('progress_summary', $report->progress_summary) }}</textarea>
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
                    {{ !in_array($report->status, ['draft', 'returned']) ? 'disabled' : '' }}
                    class="w-full px-4 py-3 bg-white/50 dark:bg-gray-800/50 border border-gray-300 dark:border-gray-600 rounded-xl text-gray-900 dark:text-white focus:ring-2 focus:ring-purple-500 focus:border-transparent"
                    placeholder="What areas did the student excel in this month?">{{ old('strengths', $report->strengths) }}</textarea>
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
                    {{ !in_array($report->status, ['draft', 'returned']) ? 'disabled' : '' }}
                    class="w-full px-4 py-3 bg-white/50 dark:bg-gray-800/50 border border-gray-300 dark:border-gray-600 rounded-xl text-gray-900 dark:text-white focus:ring-2 focus:ring-purple-500 focus:border-transparent"
                    placeholder="What areas need improvement or more focus?">{{ old('weaknesses', $report->weaknesses) }}</textarea>
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
                    {{ !in_array($report->status, ['draft', 'returned']) ? 'disabled' : '' }}
                    class="w-full px-4 py-3 bg-white/50 dark:bg-gray-800/50 border border-gray-300 dark:border-gray-600 rounded-xl text-gray-900 dark:text-white focus:ring-2 focus:ring-purple-500 focus:border-transparent"
                    placeholder="What are the recommended next steps for this student?">{{ old('next_steps', $report->next_steps) }}</textarea>
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
                    <input type="number" id="attendance_score" name="attendance_score" value="{{ old('attendance_score', $report->attendance_score) }}"
                        min="0" max="100" required
                        {{ !in_array($report->status, ['draft', 'returned']) ? 'disabled' : '' }}
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
                        {{ !in_array($report->status, ['draft', 'returned']) ? 'disabled' : '' }}
                        class="w-full px-4 py-3 bg-white/50 dark:bg-gray-800/50 border border-gray-300 dark:border-gray-600 rounded-xl text-gray-900 dark:text-white focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                        <option value="">Select rating</option>
                        <option value="excellent" {{ old('performance_rating', $report->performance_rating) === 'excellent' ? 'selected' : '' }}>Excellent</option>
                        <option value="good" {{ old('performance_rating', $report->performance_rating) === 'good' ? 'selected' : '' }}>Good</option>
                        <option value="average" {{ old('performance_rating', $report->performance_rating) === 'average' ? 'selected' : '' }}>Average</option>
                        <option value="poor" {{ old('performance_rating', $report->performance_rating) === 'poor' ? 'selected' : '' }}>Poor</option>
                    </select>
                    @error('performance_rating')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Action Buttons -->
            @if(in_array($report->status, ['draft', 'returned']))
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-4">
                        <a href="{{ route('tutor.reports.show', $report) }}"
                            class="px-6 py-3 bg-white/20 dark:bg-gray-800/30 text-gray-700 dark:text-gray-300 rounded-xl border border-white/10 hover:bg-white/30 dark:hover:bg-gray-800/50 transition-colors font-medium">
                            Cancel
                        </a>
                        <button type="submit"
                            class="px-6 py-3 bg-gradient-to-r from-purple-500 to-pink-500 text-white rounded-xl hover:shadow-lg hover:-translate-y-0.5 transition-all font-medium">
                            Save Changes
                        </button>
                    </div>

                    @if($report->status === 'draft')
                        <form action="{{ route('tutor.reports.submit', $report) }}" method="POST" class="inline-block"
                            onsubmit="return confirm('Are you sure you want to submit this report for review?');">
                            @csrf
                            <button type="submit"
                                class="px-6 py-3 bg-green-600 hover:bg-green-700 text-white rounded-xl hover:shadow-lg hover:-translate-y-0.5 transition-all font-medium">
                                Submit for Review
                            </button>
                        </form>
                    @endif
                </div>
            @else
                <div class="flex items-center justify-end">
                    <a href="{{ route('tutor.reports.show', $report) }}"
                        class="px-6 py-3 bg-white/20 dark:bg-gray-800/30 text-gray-700 dark:text-gray-300 rounded-xl border border-white/10 hover:bg-white/30 dark:hover:bg-gray-800/50 transition-colors font-medium">
                        Back to Report
                    </a>
                </div>
            @endif
        </form>
    </div>
</x-tutor-layout>
