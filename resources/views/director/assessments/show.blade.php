<x-app-layout>
    <x-slot name="header">
        {{ __('Tutor Assessment Review') }}
    </x-slot>

    <x-slot name="title">Assessment Review</x-slot>

    <div class="min-h-screen bg-gradient-to-br from-indigo-50 via-purple-50 to-pink-50 dark:from-slate-900 dark:via-slate-800 dark:to-slate-900 py-12 relative overflow-hidden">
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
                    <a href="{{ route('director.assessments.index') }}" class="px-4 py-2 bg-white/20 dark:bg-gray-800/30 text-gray-700 dark:text-gray-300 rounded-xl border border-white/10 hover:bg-white/30 transition-colors">
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

                    {{-- Performance Breakdown --}}
                    @if($assessment->ratings && $assessment->ratings->count() > 0)
                    <div class="backdrop-blur-md bg-white/30 dark:bg-gray-900/30 border border-white/10 rounded-2xl shadow-xl p-6">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 flex items-center">
                            🧾 Performance Breakdown
                        </h3>
                        <div class="overflow-x-auto">
                            <table class="w-full">
                                <thead>
                                    <tr class="bg-gray-800 text-white text-left">
                                        <th class="px-4 py-3 rounded-tl-lg">Criteria</th>
                                        <th class="px-4 py-3">Performance</th>
                                        <th class="px-4 py-3">Visual</th>
                                        <th class="px-4 py-3 rounded-tr-lg">Rating</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($assessment->ratings as $rating)
                                        @php
                                            $percentage = $rating->percentage;
                                            $ratingInfo = getEmojiAndLabel($percentage);
                                            $visualBar = createVisualBar($percentage);
                                        @endphp
                                        <tr class="{{ $loop->even ? 'bg-gray-50 dark:bg-gray-800/50' : 'bg-white dark:bg-gray-900/30' }}">
                                            <td class="px-4 py-3 font-medium text-gray-900 dark:text-white">{{ $rating->criteria->name }}</td>
                                            <td class="px-4 py-3 text-gray-700 dark:text-gray-300">{{ number_format($percentage, 1) }}%</td>
                                            <td class="px-4 py-3 font-mono text-lg tracking-wider text-gray-600 dark:text-gray-400">{{ $visualBar }}</td>
                                            <td class="px-4 py-3">
                                                <span class="inline-flex items-center gap-1 text-sm">
                                                    {{ $ratingInfo['emoji'] }} {{ $ratingInfo['label'] }}
                                                </span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        @php
                            $overallScore = $assessment->calculateOverallScore();
                            $overallInfo = getEmojiAndLabel($overallScore);
                            $overallClass = getOverallRatingClass($overallScore);
                        @endphp
                        <div class="mt-6 p-4 rounded-xl text-center {{ $overallClass }}">
                            <span class="text-lg">Overall Rating:</span>
                            <span class="text-3xl font-bold ml-2">{{ number_format($overallScore, 1) }}%</span>
                            <span class="text-lg ml-2">({{ $overallInfo['label'] }})</span>
                        </div>
                    </div>
                    @elseif($assessment->criteria_assessed)
                    {{-- Fallback to legacy display --}}
                    <div class="backdrop-blur-md bg-white/30 dark:bg-gray-900/30 border border-white/10 rounded-2xl shadow-xl p-6">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-3 flex items-center">
                            <svg class="w-5 h-5 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                            </svg>
                            Criteria Assessed
                        </h3>
                        <div class="prose dark:prose-invert max-w-none">
                            @if(is_array($assessment->criteria_assessed))
                                <div class="flex flex-wrap gap-2">
                                    @foreach($assessment->criteria_assessed as $criteria)
                                        <span class="px-3 py-1 bg-blue-100 dark:bg-blue-900/30 text-blue-800 dark:text-blue-300 rounded-full text-sm">{{ $criteria }}</span>
                                    @endforeach
                                </div>
                            @else
                                <p class="text-gray-700 dark:text-gray-300 whitespace-pre-wrap">{{ $assessment->criteria_assessed }}</p>
                            @endif
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

                {{-- Sidebar - Director Actions --}}
                <div class="space-y-6">
                    @if($assessment->canDirectorApprove())
                    @php
                        // Calculate suggested penalty from ratings
                        $suggestedPenalty = 0;
                        $penaltyDetails = [];
                        if ($assessment->ratings) {
                            $criteria = \App\Models\AssessmentCriteria::active()->get();
                            foreach ($assessment->ratings as $rating) {
                                $criterion = $criteria->firstWhere('id', $rating->criteria_id);
                                if ($criterion && isset($criterion->penalty_rules[$rating->rating])) {
                                    $rule = $criterion->penalty_rules[$rating->rating];
                                    if (isset($rule['amount'])) {
                                        $suggestedPenalty += $rule['amount'];
                                        $penaltyDetails[] = [
                                            'criteria' => $criterion->name,
                                            'rating' => $rating->rating,
                                            'amount' => $rule['amount'],
                                            'label' => $rule['label'] ?? ''
                                        ];
                                    }
                                }
                            }
                        }
                    @endphp

                    {{-- Penalty Summary --}}
                    @if(count($penaltyDetails) > 0)
                    <div class="backdrop-blur-md bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800/50 rounded-2xl shadow-xl p-6">
                        <h3 class="text-lg font-semibold text-red-800 dark:text-red-300 mb-4 flex items-center">
                            ⚠️ Suggested Penalties
                        </h3>
                        <div class="space-y-2">
                            @foreach($penaltyDetails as $detail)
                                <div class="flex justify-between text-sm">
                                    <span class="text-red-700 dark:text-red-400">{{ $detail['criteria'] }}: {{ $detail['rating'] }}</span>
                                    <span class="font-semibold text-red-800 dark:text-red-300">₦{{ number_format($detail['amount'], 2) }}</span>
                                </div>
                            @endforeach
                            <div class="border-t border-red-300 dark:border-red-700 pt-2 mt-2">
                                <div class="flex justify-between font-bold">
                                    <span class="text-red-800 dark:text-red-300">Total Suggested:</span>
                                    <span class="text-red-900 dark:text-red-200 text-lg">₦{{ number_format($suggestedPenalty, 2) }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif

                    <div class="backdrop-blur-md bg-white/30 dark:bg-gray-900/30 border border-white/10 rounded-2xl shadow-xl p-6">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Director Approval</h3>

                        {{-- Director Comment --}}
                        <div class="mb-4">
                            <label for="director_comment" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Director Comment (Optional)
                            </label>
                            <textarea
                                id="director_comment"
                                name="director_comment_input"
                                rows="4"
                                maxlength="2000"
                                placeholder="Add approval notes..."
                                class="w-full px-4 py-3 bg-white/50 dark:bg-gray-800/50 border border-gray-300 dark:border-gray-600 rounded-xl text-gray-900 dark:text-white focus:ring-2 focus:ring-[#4F46E5] resize-none"></textarea>
                        </div>

                        @if($suggestedPenalty > 0)
                        {{-- Penalty Amount Input --}}
                        <div class="mb-4">
                            <label for="penalty_amount" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Penalty Amount (₦)
                            </label>
                            <input
                                type="number"
                                id="penalty_amount"
                                name="penalty_amount_input"
                                value="{{ $suggestedPenalty }}"
                                min="0"
                                step="0.01"
                                class="w-full px-4 py-3 bg-white/50 dark:bg-gray-800/50 border border-gray-300 dark:border-gray-600 rounded-xl text-gray-900 dark:text-white focus:ring-2 focus:ring-[#4F46E5]">
                        </div>
                        @endif

                        <div class="space-y-3">
                            @if($suggestedPenalty > 0)
                            {{-- Approve with Penalty --}}
                            <form action="{{ route('director.assessments.approve-with-penalty', $assessment) }}" method="POST" id="approve-penalty-form">
                                @csrf
                                <input type="hidden" name="director_comment" id="comment_penalty">
                                <input type="hidden" name="penalty_amount" id="penalty_hidden">
                                <input type="hidden" name="suggested_penalty" value="{{ $suggestedPenalty }}">
                                <button
                                    type="submit"
                                    onclick="document.getElementById('comment_penalty').value = document.querySelector('[name=director_comment_input]').value; document.getElementById('penalty_hidden').value = document.getElementById('penalty_amount').value; return confirm('Apply penalty of ₦' + document.getElementById('penalty_amount').value + '?');"
                                    class="w-full px-6 py-3 bg-gradient-to-r from-red-500 to-red-600 text-white rounded-xl hover:shadow-lg transform hover:-translate-y-1 transition-all font-bold flex items-center justify-center">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    Approve with Penalty
                                </button>
                            </form>
                            @endif

                            {{-- Approve without Penalty --}}
                            <form action="{{ route('director.assessments.approve-no-penalty', $assessment) }}" method="POST" id="approve-no-penalty-form">
                                @csrf
                                <input type="hidden" name="director_comment" id="comment_no_penalty">
                                <input type="hidden" name="suggested_penalty" value="{{ $suggestedPenalty }}">
                                <button
                                    type="submit"
                                    onclick="document.getElementById('comment_no_penalty').value = document.querySelector('[name=director_comment_input]').value; return confirm('Approve without penalty?');"
                                    class="w-full px-6 py-3 bg-gradient-to-r from-green-500 to-emerald-600 text-white rounded-xl hover:shadow-lg transform hover:-translate-y-1 transition-all font-bold flex items-center justify-center">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    Approve {{ $suggestedPenalty > 0 ? 'without Penalty' : '' }}
                                </button>
                            </form>
                        </div>
                    </div>
                    @else
                    <div class="backdrop-blur-md bg-white/30 dark:bg-gray-900/30 border border-white/10 rounded-2xl shadow-xl p-6">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Assessment Status</h3>
                        <p class="text-gray-700 dark:text-gray-300">
                            This assessment has been processed. Current status: <strong>{{ ucfirst(str_replace('-', ' ', $assessment->status)) }}</strong>
                        </p>
                        @if($assessment->directorAction)
                            <div class="mt-4 p-3 bg-gray-100 dark:bg-gray-800 rounded-lg">
                                <p class="text-sm text-gray-600 dark:text-gray-400">
                                    <strong>Action:</strong> {{ $assessment->directorAction->action_type === 'approve' ? 'Approved with Penalty' : 'Approved without Penalty' }}
                                </p>
                                @if($assessment->directorAction->penalty_amount > 0)
                                    <p class="text-sm text-red-600 dark:text-red-400">
                                        <strong>Penalty:</strong> ₦{{ number_format($assessment->directorAction->penalty_amount, 2) }}
                                    </p>
                                @endif
                            </div>
                        @endif
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    @push('styles')
    <style>
        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-20px); }
        }
        .animate-float { animation: float 6s ease-in-out infinite; }
    </style>
    @endpush
</x-app-layout>
