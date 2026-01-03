<x-manager-layout title="Assessment Review">
    <div class="min-h-screen py-6 relative overflow-hidden">
        <div class="absolute top-0 left-0 w-72 h-72 bg-indigo-400 dark:bg-indigo-900 rounded-full mix-blend-multiply filter blur-xl opacity-70 animate-float"></div>
        <div class="absolute top-0 right-0 w-72 h-72 bg-purple-400 dark:bg-purple-900 rounded-full mix-blend-multiply filter blur-xl opacity-70 animate-float" style="animation-delay: 2s;"></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="mb-8 flex justify-between items-start">
                <div>
                    <h1 class="text-4xl font-bold text-gray-900 dark:text-white mb-2">Tutor Assessment — {{ $assessment->assessment_month }}</h1>
                    <p class="text-gray-600 dark:text-gray-400">{{ $assessment->tutor->fullName() }}</p>
                </div>
                <div class="flex items-center gap-3">
                    <x-ui.status-badge :status="$assessment->status" />
                    <a href="{{ route('manager.assessments.index') }}" class="px-4 py-2 bg-white/20 dark:bg-gray-800/30 text-gray-700 dark:text-gray-300 rounded-xl border border-white/10 hover:bg-white/30 transition-colors">
                        Back to List
                    </a>
                </div>
            </div>

            @if(session('success'))
                <div class="mb-6 bg-green-100 dark:bg-green-900/30 border border-green-400 text-green-800 dark:text-green-400 px-6 py-4 rounded-xl" role="alert">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="mb-6 bg-red-100 dark:bg-red-900/30 border border-red-400 text-red-800 dark:text-red-400 px-6 py-4 rounded-xl" role="alert">
                    {{ session('error') }}
                </div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                {{-- Main Content --}}
                <div class="lg:col-span-2 space-y-6">
                    {{-- Assessment Details --}}
                    <div class="backdrop-blur-md bg-white/30 dark:bg-gray-900/30 border border-white/10 rounded-2xl shadow-xl p-6">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Assessment Details</h3>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-600 dark:text-gray-400 mb-1">Tutor</label>
                                <p class="text-gray-900 dark:text-white font-medium">{{ $assessment->tutor->fullName() }}</p>
                                <p class="text-sm text-gray-600 dark:text-gray-400">{{ $assessment->tutor->email }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-600 dark:text-gray-400 mb-1">Student Assessed</label>
                                <p class="text-gray-900 dark:text-white font-medium">{{ $assessment->student ? $assessment->student->first_name . ' ' . $assessment->student->last_name : 'N/A' }}</p>
                                @if($assessment->student && $assessment->student->email)
                                <p class="text-sm text-gray-600 dark:text-gray-400">{{ $assessment->student->email }}</p>
                                @endif
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-600 dark:text-gray-400 mb-1">Assessment Month</label>
                                <p class="text-gray-900 dark:text-white">{{ $assessment->assessment_month }}</p>
                            </div>
                            @if($assessment->class_date)
                            <div>
                                <label class="block text-sm font-medium text-gray-600 dark:text-gray-400 mb-1">Class Date</label>
                                <p class="text-gray-900 dark:text-white">{{ $assessment->class_date->format('M d, Y') }}</p>
                            </div>
                            @endif
                            @if($assessment->week)
                            <div>
                                <label class="block text-sm font-medium text-gray-600 dark:text-gray-400 mb-1">Week</label>
                                <p class="text-gray-900 dark:text-white">Week {{ $assessment->week }}</p>
                            </div>
                            @endif
                            @if($assessment->session)
                            <div>
                                <label class="block text-sm font-medium text-gray-600 dark:text-gray-400 mb-1">Session</label>
                                <p class="text-gray-900 dark:text-white">{{ $assessment->session }}</p>
                            </div>
                            @endif
                            @if($assessment->performance_score)
                            <div>
                                <label class="block text-sm font-medium text-gray-600 dark:text-gray-400 mb-1">Performance Score</label>
                                <div class="flex items-center gap-2">
                                    <div class="text-3xl font-bold text-[#4F46E5]">{{ $assessment->performance_score }}</div>
                                    <div class="text-sm text-gray-600 dark:text-gray-400">/100</div>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>

                    {{-- Criteria Assessed --}}
                    @if($assessment->criteria_assessed)
                    <div class="backdrop-blur-md bg-white/30 dark:bg-gray-900/30 border border-white/10 rounded-2xl shadow-xl p-6">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-3 flex items-center">
                            <svg class="w-5 h-5 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                            </svg>
                            Criteria Assessed
                        </h3>
                        <div class="flex flex-wrap gap-2">
                            @php
                                $criteriaList = is_array($assessment->criteria_assessed) ? $assessment->criteria_assessed : [$assessment->criteria_assessed];
                            @endphp
                            @foreach($criteriaList as $criteria)
                                <span class="px-3 py-1 bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-300 rounded-full text-sm">{{ $criteria }}</span>
                            @endforeach
                        </div>
                    </div>
                    @endif

                    {{-- Criteria Ratings --}}
                    @if($assessment->criteria_ratings)
                    <div class="backdrop-blur-md bg-white/30 dark:bg-gray-900/30 border border-white/10 rounded-2xl shadow-xl p-6">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-3 flex items-center">
                            <svg class="w-5 h-5 mr-2 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" />
                            </svg>
                            Criteria Ratings
                        </h3>
                        <div class="space-y-3">
                            @php
                                $ratings = is_array($assessment->criteria_ratings) ? $assessment->criteria_ratings : [];
                            @endphp
                            @foreach($ratings as $criteriaCode => $rating)
                                <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-800/50 rounded-lg">
                                    <span class="font-medium text-gray-700 dark:text-gray-300 capitalize">{{ str_replace('_', ' ', $criteriaCode) }}</span>
                                    <span class="px-3 py-1 rounded-full text-sm font-medium
                                        @if(in_array($rating, ['Excellent', 'On Time'])) bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-300
                                        @elseif($rating === 'Good') bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-300
                                        @elseif($rating === 'Acceptable') bg-yellow-100 text-yellow-700 dark:bg-yellow-900/30 dark:text-yellow-300
                                        @else bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-300
                                        @endif">
                                        {{ $rating }}
                                    </span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    @endif

                    {{-- Ratings --}}
                    @if($assessment->professionalism_rating || $assessment->communication_rating || $assessment->punctuality_rating)
                    <div class="backdrop-blur-md bg-white/30 dark:bg-gray-900/30 border border-white/10 rounded-2xl shadow-xl p-6">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Ratings</h3>
                        <div class="space-y-4">
                            @if($assessment->professionalism_rating)
                            <div>
                                <x-ui.progress-bar :value="$assessment->professionalism_rating" label="Professionalism" />
                            </div>
                            @endif
                            @if($assessment->communication_rating)
                            <div>
                                <x-ui.progress-bar :value="$assessment->communication_rating" label="Communication" />
                            </div>
                            @endif
                            @if($assessment->punctuality_rating)
                            <div>
                                <x-ui.progress-bar :value="$assessment->punctuality_rating" label="Punctuality" />
                            </div>
                            @endif
                        </div>
                    </div>
                    @endif

                    {{-- Strengths --}}
                    @if($assessment->strengths)
                    <div class="backdrop-blur-md bg-white/30 dark:bg-gray-900/30 border border-white/10 rounded-2xl shadow-xl p-6">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-3 flex items-center">
                            <svg class="w-5 h-5 mr-2 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 10h4.764a2 2 0 011.789 2.894l-3.5 7A2 2 0 0115.263 21h-4.017c-.163 0-.326-.02-.485-.06L7 20m7-10V5a2 2 0 00-2-2h-.095c-.5 0-.905.405-.905.905 0 .714-.211 1.412-.608 2.006L7 11v9m7-10h-2M7 20H5a2 2 0 01-2-2v-6a2 2 0 012-2h2.5" />
                            </svg>
                            Strengths
                        </h3>
                        <p class="text-gray-700 dark:text-gray-300 whitespace-pre-wrap">{{ $assessment->strengths }}</p>
                    </div>
                    @endif

                    {{-- Weaknesses --}}
                    @if($assessment->weaknesses)
                    <div class="backdrop-blur-md bg-white/30 dark:bg-gray-900/30 border border-white/10 rounded-2xl shadow-xl p-6">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-3 flex items-center">
                            <svg class="w-5 h-5 mr-2 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                            </svg>
                            Areas for Improvement
                        </h3>
                        <p class="text-gray-700 dark:text-gray-300 whitespace-pre-wrap">{{ $assessment->weaknesses }}</p>
                    </div>
                    @endif

                    {{-- Recommendations --}}
                    @if($assessment->recommendations)
                    <div class="backdrop-blur-md bg-white/30 dark:bg-gray-900/30 border border-white/10 rounded-2xl shadow-xl p-6">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-3 flex items-center">
                            <svg class="w-5 h-5 mr-2 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                            </svg>
                            Recommendations
                        </h3>
                        <p class="text-gray-700 dark:text-gray-300 whitespace-pre-wrap">{{ $assessment->recommendations }}</p>
                    </div>
                    @endif

                    {{-- Manager Comment --}}
                    @if($assessment->manager_comment)
                    <div class="backdrop-blur-md bg-white/30 dark:bg-gray-900/30 border border-white/10 rounded-2xl shadow-xl p-6">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Manager Comment</h3>
                        <div class="bg-emerald-50 dark:bg-emerald-900/20 border border-emerald-200 dark:border-emerald-800 rounded-lg p-4">
                            <p class="text-gray-700 dark:text-gray-300">{{ $assessment->manager_comment }}</p>
                        </div>
                    </div>
                    @endif
                </div>

                {{-- Sidebar - Assessment Info and Actions --}}
                <div class="space-y-6">
                    {{-- Assessment Status --}}
                    <div class="backdrop-blur-md bg-white/30 dark:bg-gray-900/30 border border-white/10 rounded-2xl shadow-xl p-6">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Assessment Status</h3>
                        <div class="space-y-3">
                            <div class="flex items-center justify-between">
                                <span class="text-gray-600 dark:text-gray-400">Status:</span>
                                <x-ui.status-badge :status="$assessment->status" />
                            </div>
                            @if($assessment->approved_by_manager_at)
                            <div class="flex items-center justify-between text-sm">
                                <span class="text-gray-600 dark:text-gray-400">Completed by Manager:</span>
                                <span class="text-gray-800 dark:text-white">{{ $assessment->approved_by_manager_at->format('M d, Y') }}</span>
                            </div>
                            @endif
                            @if($assessment->approved_by_director_at)
                            <div class="flex items-center justify-between text-sm">
                                <span class="text-gray-600 dark:text-gray-400">Approved by Director:</span>
                                <span class="text-gray-800 dark:text-white">{{ $assessment->approved_by_director_at->format('M d, Y') }}</span>
                            </div>
                            @endif
                        </div>
                    </div>

                    {{-- Director Comment (if present) --}}
                    @if($assessment->director_comment)
                    <div class="backdrop-blur-md bg-white/30 dark:bg-gray-900/30 border border-white/10 rounded-2xl shadow-xl p-6">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 flex items-center">
                            <svg class="w-5 h-5 mr-2 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                            </svg>
                            Director's Comment
                        </h3>
                        <div class="bg-purple-50 dark:bg-purple-900/20 border border-purple-200 dark:border-purple-800 rounded-lg p-4">
                            <p class="text-gray-700 dark:text-gray-300 whitespace-pre-wrap">{{ $assessment->director_comment }}</p>
                        </div>
                    </div>
                    @endif

                    {{-- Manager Actions (for draft assessments) --}}
                    @if(in_array($assessment->status, ['draft', 'submitted']))
                    <div class="backdrop-blur-md bg-white/30 dark:bg-gray-900/30 border border-white/10 rounded-2xl shadow-xl p-6">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Actions</h3>
                        <div class="space-y-3">
                            <a href="{{ route('manager.assessments.edit', $assessment) }}" class="w-full px-4 py-2.5 bg-gradient-to-r from-[#C15F3C] to-[#DA7756] text-white rounded-xl hover:shadow-lg transition-all font-medium flex items-center justify-center">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                </svg>
                                Edit Assessment
                            </a>
                            <form action="{{ route('manager.assessments.mark-complete', $assessment) }}" method="POST">
                                @csrf
                                <button type="submit" onclick="return confirm('Mark this assessment as complete? It will be sent for director review.')" class="w-full px-4 py-2.5 bg-gradient-to-r from-green-500 to-emerald-600 text-white rounded-xl hover:shadow-lg transition-all font-medium flex items-center justify-center">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    Mark Complete
                                </button>
                            </form>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <style>
        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-20px); }
        }
        .animate-float { animation: float 6s ease-in-out infinite; }
    </style>
</x-manager-layout>
