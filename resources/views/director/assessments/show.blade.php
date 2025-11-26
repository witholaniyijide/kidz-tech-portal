<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-2xl text-white">Tutor Assessment Review</h2>
    </x-slot>

    <x-slot name="title">Assessment Review</x-slot>

    <div class="min-h-screen bg-gradient-to-br from-blue-50 via-indigo-50 to-purple-50 dark:from-slate-900 dark:via-slate-800 dark:to-slate-900 py-12 relative overflow-hidden">
        <div class="absolute top-0 left-0 w-72 h-72 bg-blue-400 dark:bg-blue-900 rounded-full mix-blend-multiply filter blur-xl opacity-70 animate-float"></div>
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
                                <label class="block text-sm font-medium text-gray-600 dark:text-gray-400 mb-1">Assessment Month</label>
                                <p class="text-gray-900 dark:text-white">{{ $assessment->assessment_month }}</p>
                            </div>
                            @if($assessment->performance_score)
                            <div>
                                <label class="block text-sm font-medium text-gray-600 dark:text-gray-400 mb-1">Performance Score</label>
                                <div class="flex items-center gap-2">
                                    <div class="text-3xl font-bold text-blue-600">{{ $assessment->performance_score }}</div>
                                    <div class="text-sm text-gray-600 dark:text-gray-400">/100</div>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>

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
                    <div class="backdrop-blur-md bg-white/30 dark:bg-gray-900/30 border border-white/10 rounded-2xl shadow-xl p-6">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Director Approval</h3>

                        <form action="{{ route('director.assessments.approve', $assessment) }}" method="POST" class="mb-4">
                            @csrf
                            <div class="mb-4">
                                <label for="director_comment" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Director Comment (Optional)
                                </label>
                                <textarea
                                    id="director_comment"
                                    name="director_comment"
                                    rows="4"
                                    maxlength="2000"
                                    placeholder="Add approval notes..."
                                    class="w-full px-4 py-3 bg-white/50 dark:bg-gray-800/50 border border-gray-300 dark:border-gray-600 rounded-xl text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 resize-none"></textarea>
                            </div>
                            <button
                                type="submit"
                                onclick="return confirm('Approve this assessment? This will notify the tutor and manager.')"
                                class="w-full px-6 py-3 bg-gradient-to-r from-green-500 to-emerald-600 text-white rounded-xl hover:shadow-lg transform hover:-translate-y-1 transition-all font-bold flex items-center justify-center">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                Approve Assessment
                            </button>
                        </form>
                    </div>
                    @else
                    <div class="backdrop-blur-md bg-white/30 dark:bg-gray-900/30 border border-white/10 rounded-2xl shadow-xl p-6">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Assessment Status</h3>
                        <p class="text-gray-700 dark:text-gray-300">
                            This assessment has been processed. Current status: <strong>{{ ucfirst(str_replace('-', ' ', $assessment->status)) }}</strong>
                        </p>
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
