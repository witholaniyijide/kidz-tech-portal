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
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6 p-4 bg-gray-50 dark:bg-gray-700/50 rounded-xl">
                    <div>
                        <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Tutor</label>
                        <p class="text-gray-800 dark:text-white font-medium">{{ $assessment->tutor->first_name ?? 'Unknown' }} {{ $assessment->tutor->last_name ?? '' }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Assessment Period</label>
                        <p class="text-gray-800 dark:text-white font-medium">{{ $assessment->assessment_period }}</p>
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

                {{-- Areas of Concern --}}
                <div class="mb-6">
                    <label for="weaknesses" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Areas of Concern</label>
                    <textarea name="weaknesses" id="weaknesses" rows="4"
                              class="w-full border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-xl p-3 focus:ring-2 focus:ring-[#C15F3C] focus:border-[#C15F3C]"
                              placeholder="Areas of concern...">{{ old('weaknesses', $assessment->weaknesses) }}</textarea>
                    @error('weaknesses')
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
