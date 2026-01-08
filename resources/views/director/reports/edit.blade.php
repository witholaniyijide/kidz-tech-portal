<x-app-layout>
    <x-slot name="header">
        {{ __('Edit Report') }}
    </x-slot>

    <x-slot name="title">{{ __('Edit Report Before Approval') }}</x-slot>

    <div class="min-h-screen bg-gradient-to-br from-indigo-50 via-purple-50 to-pink-50 dark:from-slate-900 dark:via-slate-800 dark:to-slate-900 py-12 relative overflow-hidden">
        <div class="absolute top-0 left-0 w-72 h-72 bg-indigo-400 dark:bg-indigo-900 rounded-full mix-blend-multiply filter blur-xl opacity-70 animate-float"></div>
        <div class="absolute top-0 right-0 w-72 h-72 bg-purple-400 dark:bg-purple-900 rounded-full mix-blend-multiply filter blur-xl opacity-70 animate-float" style="animation-delay: 2s;"></div>

        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">

            {{-- Page Header --}}
            <div class="mb-8">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-2">
                            Edit Report — {{ $report->month }}
                        </h1>
                        <p class="text-gray-600 dark:text-gray-400">
                            {{ $report->student->fullName() }} • Tutor: {{ $report->tutor->fullName() }}
                        </p>
                    </div>
                    <a href="{{ route('director.reports.show', $report) }}" class="px-4 py-2 bg-white/20 dark:bg-gray-800/30 text-gray-700 dark:text-gray-300 rounded-xl border border-white/10 hover:bg-white/30 dark:hover:bg-gray-800/50 transition-colors">
                        Cancel
                    </a>
                </div>
            </div>

            {{-- Edit Form --}}
            <form action="{{ route('director.reports.update', $report) }}" method="POST" class="space-y-6">
                @csrf
                @method('PUT')

                {{-- Performance Metrics --}}
                <div class="backdrop-blur-md bg-white/30 dark:bg-gray-900/30 border border-white/10 rounded-2xl shadow-xl p-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Performance Metrics</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="attendance_score" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Attendance Score (%)
                            </label>
                            <input type="number" id="attendance_score" name="attendance_score" min="0" max="100"
                                   value="{{ old('attendance_score', $report->attendance_score) }}"
                                   class="w-full px-4 py-3 bg-white/50 dark:bg-gray-800/50 border border-gray-300 dark:border-gray-600 rounded-xl text-gray-900 dark:text-white focus:ring-2 focus:ring-[#4F46E5] focus:border-transparent">
                            @error('attendance_score')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="performance_rating" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Performance Rating
                            </label>
                            <select id="performance_rating" name="performance_rating"
                                    class="w-full px-4 py-3 bg-white/50 dark:bg-gray-800/50 border border-gray-300 dark:border-gray-600 rounded-xl text-gray-900 dark:text-white focus:ring-2 focus:ring-[#4F46E5] focus:border-transparent">
                                <option value="">Select Rating</option>
                                <option value="excellent" {{ old('performance_rating', $report->performance_rating) == 'excellent' ? 'selected' : '' }}>Excellent</option>
                                <option value="good" {{ old('performance_rating', $report->performance_rating) == 'good' ? 'selected' : '' }}>Good</option>
                                <option value="satisfactory" {{ old('performance_rating', $report->performance_rating) == 'satisfactory' ? 'selected' : '' }}>Satisfactory</option>
                                <option value="needs-improvement" {{ old('performance_rating', $report->performance_rating) == 'needs-improvement' ? 'selected' : '' }}>Needs Improvement</option>
                            </select>
                            @error('performance_rating')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                {{-- Areas for Improvement --}}
                <div class="backdrop-blur-md bg-white/30 dark:bg-gray-900/30 border border-white/10 rounded-2xl shadow-xl p-6">
                    <label for="areas_for_improvement" class="block text-lg font-semibold text-gray-900 dark:text-white mb-4">
                        Areas for Improvement
                    </label>
                    <textarea id="areas_for_improvement" name="areas_for_improvement" rows="4"
                              class="w-full px-4 py-3 bg-white/50 dark:bg-gray-800/50 border border-gray-300 dark:border-gray-600 rounded-xl text-gray-900 dark:text-white focus:ring-2 focus:ring-[#4F46E5] focus:border-transparent resize-none"
                              placeholder="Areas where the student can improve...">{{ old('areas_for_improvement', $report->areas_for_improvement) }}</textarea>
                    @error('areas_for_improvement')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Goals for Next Month --}}
                <div class="backdrop-blur-md bg-white/30 dark:bg-gray-900/30 border border-white/10 rounded-2xl shadow-xl p-6">
                    <label for="goals_next_month" class="block text-lg font-semibold text-gray-900 dark:text-white mb-4">
                        Goals for Next Month
                    </label>
                    <textarea id="goals_next_month" name="goals_next_month" rows="4"
                              class="w-full px-4 py-3 bg-white/50 dark:bg-gray-800/50 border border-gray-300 dark:border-gray-600 rounded-xl text-gray-900 dark:text-white focus:ring-2 focus:ring-[#4F46E5] focus:border-transparent resize-none"
                              placeholder="Goals and objectives for the next month...">{{ old('goals_next_month', $report->goals_next_month) }}</textarea>
                    @error('goals_next_month')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Assignments --}}
                <div class="backdrop-blur-md bg-white/30 dark:bg-gray-900/30 border border-white/10 rounded-2xl shadow-xl p-6">
                    <label for="assignments" class="block text-lg font-semibold text-gray-900 dark:text-white mb-4">
                        Assignments Given
                    </label>
                    <textarea id="assignments" name="assignments" rows="4"
                              class="w-full px-4 py-3 bg-white/50 dark:bg-gray-800/50 border border-gray-300 dark:border-gray-600 rounded-xl text-gray-900 dark:text-white focus:ring-2 focus:ring-[#4F46E5] focus:border-transparent resize-none"
                              placeholder="Assignments given to the student...">{{ old('assignments', $report->assignments) }}</textarea>
                    @error('assignments')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Comments & Observations --}}
                <div class="backdrop-blur-md bg-white/30 dark:bg-gray-900/30 border border-white/10 rounded-2xl shadow-xl p-6">
                    <label for="comments_observation" class="block text-lg font-semibold text-gray-900 dark:text-white mb-4">
                        Comments & Observations
                    </label>
                    <textarea id="comments_observation" name="comments_observation" rows="4"
                              class="w-full px-4 py-3 bg-white/50 dark:bg-gray-800/50 border border-gray-300 dark:border-gray-600 rounded-xl text-gray-900 dark:text-white focus:ring-2 focus:ring-[#4F46E5] focus:border-transparent resize-none"
                              placeholder="Additional comments and observations...">{{ old('comments_observation', $report->comments_observation) }}</textarea>
                    @error('comments_observation')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Submit Buttons --}}
                <div class="flex justify-end gap-4">
                    <a href="{{ route('director.reports.show', $report) }}"
                       class="px-6 py-3 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-xl hover:bg-gray-200 dark:hover:bg-gray-600 transition-colors font-medium">
                        Cancel
                    </a>
                    <button type="submit"
                            class="px-6 py-3 bg-gradient-to-r from-[#4F46E5] to-[#818CF8] text-white rounded-xl hover:shadow-lg transform hover:-translate-y-1 transition-all font-bold flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        Save Changes
                    </button>
                </div>
            </form>

        </div>
    </div>
</x-app-layout>
