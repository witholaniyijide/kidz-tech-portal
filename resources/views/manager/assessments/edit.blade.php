<x-manager-layout title="Edit Assessment">
    <div class="max-w-4xl mx-auto">
        {{-- Header --}}
        <div class="mb-6 flex justify-between items-center">
            <div>
                <h1 class="text-2xl font-semibold text-gray-800 dark:text-white">Edit Assessment</h1>
                <p class="text-gray-500 dark:text-gray-400 mt-1">Update assessment for {{ $assessment->tutor->first_name ?? 'Unknown' }} {{ $assessment->tutor->last_name ?? '' }}</p>
            </div>
            <a href="{{ route('manager.assessments.index') }}" class="px-4 py-2 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-600 transition-all">
                Back to List
            </a>
        </div>

        @if(session('error'))
            <div class="mb-6 bg-red-100 dark:bg-red-900/30 border border-red-400 text-red-800 dark:text-red-400 px-6 py-4 rounded-xl">
                {{ session('error') }}
            </div>
        @endif

        <div class="bg-white/70 dark:bg-gray-800/70 backdrop-blur-xl border border-white/20 dark:border-gray-700/50 rounded-2xl shadow-sm p-6">
            <form action="{{ route('manager.assessments.update', $assessment) }}" method="POST">
                @csrf
                @method('PUT')

                {{-- Assessment Info (Read-only) --}}
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6 p-4 bg-gray-50 dark:bg-gray-700/50 rounded-xl">
                    <div>
                        <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Tutor</label>
                        <p class="text-gray-800 dark:text-white font-medium">{{ $assessment->tutor->first_name ?? 'Unknown' }} {{ $assessment->tutor->last_name ?? '' }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Student</label>
                        <p class="text-gray-800 dark:text-white font-medium">{{ $assessment->student ? $assessment->student->first_name . ' ' . $assessment->student->last_name : 'N/A' }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Assessment Period</label>
                        <p class="text-gray-800 dark:text-white font-medium">{{ $assessment->assessment_month }}</p>
                    </div>
                </div>

                {{-- Performance Score --}}
                <div class="mb-6">
                    <label for="performance_score" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Performance Score (0-100)</label>
                    <input type="number" name="performance_score" id="performance_score" min="0" max="100"
                           value="{{ old('performance_score', $assessment->performance_score) }}"
                           class="w-full md:w-48 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white px-3 py-2 rounded-lg focus:ring-2 focus:ring-[#C15F3C] focus:border-[#C15F3C]">
                    @error('performance_score')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Ratings --}}
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                    <div>
                        <label for="professionalism_rating" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Professionalism (1-5)</label>
                        <select name="professionalism_rating" id="professionalism_rating"
                                class="w-full border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white px-3 py-2 rounded-lg focus:ring-2 focus:ring-[#C15F3C] focus:border-[#C15F3C]">
                            <option value="">Select...</option>
                            @for($i = 1; $i <= 5; $i++)
                                <option value="{{ $i }}" {{ old('professionalism_rating', $assessment->professionalism_rating) == $i ? 'selected' : '' }}>{{ $i }}</option>
                            @endfor
                        </select>
                    </div>
                    <div>
                        <label for="communication_rating" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Communication (1-5)</label>
                        <select name="communication_rating" id="communication_rating"
                                class="w-full border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white px-3 py-2 rounded-lg focus:ring-2 focus:ring-[#C15F3C] focus:border-[#C15F3C]">
                            <option value="">Select...</option>
                            @for($i = 1; $i <= 5; $i++)
                                <option value="{{ $i }}" {{ old('communication_rating', $assessment->communication_rating) == $i ? 'selected' : '' }}>{{ $i }}</option>
                            @endfor
                        </select>
                    </div>
                    <div>
                        <label for="punctuality_rating" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Punctuality (1-5)</label>
                        <select name="punctuality_rating" id="punctuality_rating"
                                class="w-full border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white px-3 py-2 rounded-lg focus:ring-2 focus:ring-[#C15F3C] focus:border-[#C15F3C]">
                            <option value="">Select...</option>
                            @for($i = 1; $i <= 5; $i++)
                                <option value="{{ $i }}" {{ old('punctuality_rating', $assessment->punctuality_rating) == $i ? 'selected' : '' }}>{{ $i }}</option>
                            @endfor
                        </select>
                    </div>
                </div>

                {{-- Strengths --}}
                <div class="mb-6">
                    <label for="strengths" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Strengths</label>
                    <textarea name="strengths" id="strengths" rows="4"
                              class="w-full border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-xl p-3 focus:ring-2 focus:ring-[#C15F3C] focus:border-[#C15F3C]"
                              placeholder="Document tutor's strengths...">{{ old('strengths', $assessment->strengths) }}</textarea>
                    @error('strengths')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Weaknesses --}}
                <div class="mb-6">
                    <label for="weaknesses" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Areas for Improvement</label>
                    <textarea name="weaknesses" id="weaknesses" rows="4"
                              class="w-full border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-xl p-3 focus:ring-2 focus:ring-[#C15F3C] focus:border-[#C15F3C]"
                              placeholder="Areas that need improvement...">{{ old('weaknesses', $assessment->weaknesses) }}</textarea>
                    @error('weaknesses')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Recommendations --}}
                <div class="mb-6">
                    <label for="recommendations" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Recommendations</label>
                    <textarea name="recommendations" id="recommendations" rows="3"
                              class="w-full border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-xl p-3 focus:ring-2 focus:ring-[#C15F3C] focus:border-[#C15F3C]"
                              placeholder="Any recommendations...">{{ old('recommendations', $assessment->recommendations) }}</textarea>
                    @error('recommendations')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Manager Comment --}}
                <div class="mb-6">
                    <label for="manager_comment" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Manager Comment</label>
                    <textarea name="manager_comment" id="manager_comment" rows="3"
                              class="w-full border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-xl p-3 focus:ring-2 focus:ring-[#C15F3C] focus:border-[#C15F3C]"
                              placeholder="Additional notes...">{{ old('manager_comment', $assessment->manager_comment) }}</textarea>
                    @error('manager_comment')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Actions --}}
                <div class="flex gap-3 flex-wrap">
                    <button type="submit" class="bg-gradient-to-r from-[#C15F3C] to-[#DA7756] text-white px-6 py-2.5 rounded-xl font-medium shadow-lg hover:shadow-xl transition-all hover:-translate-y-0.5">
                        Update Assessment
                    </button>
                    <a href="{{ route('manager.assessments.index') }}" class="bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 px-6 py-2.5 rounded-xl font-medium hover:bg-gray-50 dark:hover:bg-gray-600 transition-all">
                        Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
</x-manager-layout>
