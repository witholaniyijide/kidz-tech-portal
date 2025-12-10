<x-app-layout>
    <x-slot name="title">{{ __('Assessment Details') }}</x-slot>

    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Assessment Review
            </h2>
            <div class="flex gap-2">
                <a href="{{ route('manager.assessments.index') }}" style="display: inline-block; padding: 10px 20px; background: linear-gradient(to right, #0ea5e9, #06b6d4); color: white; font-weight: bold; border-radius: 8px; text-decoration: none;">
                    ← Back to Assessments
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">

            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

            @if(session('info'))
                <div class="bg-blue-100 border border-blue-400 text-blue-700 px-4 py-3 rounded relative mb-4">
                    <span class="block sm:inline">{{ session('info') }}</span>
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <div class="text-center mb-6">
                        <h1 class="text-2xl font-bold text-gray-900">Kidz Tech Coding Club</h1>
                        <p class="text-lg text-gray-600">Monthly Progress Report</p>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6 pb-6 border-b">
                        <div>
                            <h3 class="text-sm font-medium text-gray-500">Student</h3>
                            <p class="mt-1 text-lg font-semibold text-gray-900">{{ $assessment->student->full_name }}</p>
                            <p class="text-sm text-gray-500">{{ $assessment->student->student_id }}</p>
                        </div>
                        <div>
                            <h3 class="text-sm font-medium text-gray-500">Month/Year</h3>
                            <p class="mt-1 text-lg font-semibold text-gray-900">{{ $assessment->month }} {{ $assessment->year }}</p>
                        </div>
                        <div>
                            <h3 class="text-sm font-medium text-gray-500">Instructor</h3>
                            <p class="mt-1 text-gray-900">{{ $assessment->instructor->name }}</p>
                        </div>
                        <div>
                            <h3 class="text-sm font-medium text-gray-500">Status</h3>
                            <p class="mt-1">
                                @if($assessment->status == 'draft')
                                    <span class="px-3 py-1 inline-flex text-sm leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">Draft</span>
                                @elseif($assessment->status == 'submitted' || $assessment->status == 'submitted_to_manager')
                                    <span class="px-3 py-1 inline-flex text-sm leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">Pending Review</span>
                                @elseif($assessment->status == 'approved_by_manager')
                                    <span class="px-3 py-1 inline-flex text-sm leading-5 font-semibold rounded-full bg-green-100 text-green-800">Reviewed by Manager</span>
                                @elseif($assessment->status == 'approved')
                                    <span class="px-3 py-1 inline-flex text-sm leading-5 font-semibold rounded-full bg-green-100 text-green-800">Approved</span>
                                @else
                                    <span class="px-3 py-1 inline-flex text-sm leading-5 font-semibold rounded-full bg-red-100 text-red-800">Rejected</span>
                                @endif
                            </p>
                            @if($assessment->approvedBy)
                                <p class="text-xs text-gray-500 mt-1">
                                    By {{ $assessment->approvedBy->name }} on {{ $assessment->approved_at->format('M d, Y') }}
                                </p>
                            @endif
                        </div>
                    </div>

                    <div class="mb-6 pb-6 border-b">
                        <h3 class="text-lg font-semibold text-gray-900 mb-3">Course(s) Taught</h3>
                        <div class="flex flex-wrap gap-2">
                            @foreach($assessment->courses as $course)
                                <span class="px-3 py-1 bg-purple-100 text-purple-800 rounded-full text-sm font-medium">{{ $course }}</span>
                            @endforeach
                        </div>
                    </div>

                    <div class="mb-6 pb-6 border-b" style="background-color: #F0F9FF; padding: 20px; border-radius: 12px;">
                        <h3 class="text-lg font-semibold text-gray-900 mb-3">1. Progress Overview</h3>

                        <div class="mb-4">
                            <h4 class="text-sm font-medium text-gray-700 mb-2">Skills Mastered</h4>
                            <div class="flex flex-wrap gap-2">
                                @foreach($assessment->skills_mastered as $skill)
                                    <span class="px-3 py-1 bg-green-100 text-green-800 rounded-full text-sm">{{ $skill }}</span>
                                @endforeach
                            </div>
                        </div>

                        @if($assessment->skills_new && count($assessment->skills_new) > 0)
                        <div>
                            <h4 class="text-sm font-medium text-gray-700 mb-2">New Skills</h4>
                            <div class="flex flex-wrap gap-2">
                                @foreach($assessment->skills_new as $skill)
                                    <span class="px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-sm">{{ $skill }}</span>
                                @endforeach
                            </div>
                        </div>
                        @endif
                    </div>

                    <div class="mb-6 pb-6 border-b" style="background-color: #ECFDF5; padding: 20px; border-radius: 12px;">
                        <h3 class="text-lg font-semibold text-gray-900 mb-3">2. Projects/Activities Completed</h3>
                        <div class="space-y-3">
                            @foreach($assessment->projects as $index => $project)
                                <div class="bg-white p-4 rounded-lg border">
                                    <div class="font-semibold text-gray-900">Project {{ $index + 1 }}: {{ $project['title'] }}</div>
                                    @if(isset($project['link']) && $project['link'])
                                        <div class="text-sm text-gray-600 mt-1">{{ $project['link'] }}</div>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <div class="mb-6 pb-6 border-b" style="background-color: #FFF2F2; padding: 20px; border-radius: 12px;">
                        <h3 class="text-lg font-semibold text-gray-900 mb-3">3. Areas for Improvement</h3>
                        <p class="text-gray-700 whitespace-pre-line">{{ $assessment->improvement }}</p>
                    </div>

                    <div class="mb-6 pb-6 border-b" style="background-color: #FFFBEB; padding: 20px; border-radius: 12px;">
                        <h3 class="text-lg font-semibold text-gray-900 mb-3">4. Goals for Next Month</h3>
                        <p class="text-gray-700 whitespace-pre-line">{{ $assessment->goals }}</p>
                    </div>

                    <div class="mb-6 pb-6 border-b" style="background-color: #F0F9FF; padding: 20px; border-radius: 12px;">
                        <h3 class="text-lg font-semibold text-gray-900 mb-3">5. Assignment/Projects during the month</h3>
                        <p class="text-gray-700 whitespace-pre-line">{{ $assessment->assignments }}</p>
                    </div>

                    <div class="mb-6 pb-6 border-b" style="background-color: #ECFDF5; padding: 20px; border-radius: 12px;">
                        <h3 class="text-lg font-semibold text-gray-900 mb-3">6. Comments/Observation</h3>
                        <p class="text-gray-700 whitespace-pre-line">{{ $assessment->comments }}</p>
                    </div>

                    <!-- Manager Feedback Section -->
                    <div class="mt-8 pt-6 border-t" style="background: linear-gradient(to right, #e0f2fe, #cffafe); padding: 24px; border-radius: 12px;">
                        <h3 class="text-xl font-bold text-gray-900 mb-4 flex items-center">
                            <svg class="w-6 h-6 mr-2 text-sky-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"/>
                            </svg>
                            Manager Feedback
                        </h3>

                        <form action="{{ route('manager.assessments.comment', $assessment) }}" method="POST">
                            @csrf
                            <div class="mb-4">
                                <label for="comment" class="block text-sm font-medium text-gray-700 mb-2">
                                    Add your review or feedback for this assessment
                                </label>
                                <textarea
                                    id="comment"
                                    name="comment"
                                    rows="5"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-sky-500 focus:border-transparent resize-none"
                                    placeholder="Enter your feedback, comments, or suggestions..."
                                    required
                                >{{ old('comment') }}</textarea>
                                @error('comment')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="flex gap-3">
                                <button
                                    type="submit"
                                    class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-sky-500 to-cyan-500 hover:from-sky-600 hover:to-cyan-600 text-white font-semibold rounded-lg shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all duration-200"
                                >
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"/>
                                    </svg>
                                    Add Manager Feedback
                                </button>
                            </div>
                        </form>

                        <div class="mt-6 text-sm text-gray-600 bg-white/50 p-4 rounded-lg">
                            <p class="font-medium mb-2">📌 Note for Managers:</p>
                            <ul class="list-disc list-inside space-y-1 text-gray-700">
                                <li>Your feedback will be appended to the assessment with a timestamp</li>
                                <li>Managers review assessments but do not finalize approval</li>
                                <li>Final approval authority rests with Directors</li>
                            </ul>
                        </div>
                    </div>

                </div>
            </div>

        </div>
    </div>

    <style>
        @media print {
            .no-print, header, form, button {
                display: none !important;
            }
            body {
                background: white;
            }
        }
    </style>
</x-app-layout>
