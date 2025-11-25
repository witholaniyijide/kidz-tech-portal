<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-2xl text-white">
            {{ __('Review Report') }}
        </h2>
    </x-slot>

    <x-slot name="title">{{ __('Review Report') }}</x-slot>

    {{-- Animated Background --}}
    <div class="min-h-screen bg-gradient-to-br from-emerald-50 via-green-50 to-teal-50 dark:from-slate-900 dark:via-slate-800 dark:to-slate-900 py-12 relative overflow-hidden">
        {{-- Floating Orbs Background --}}
        <div class="absolute top-0 left-0 w-72 h-72 bg-emerald-300 dark:bg-emerald-900 rounded-full mix-blend-multiply filter blur-xl opacity-70 animate-float"></div>
        <div class="absolute top-0 right-0 w-72 h-72 bg-green-300 dark:bg-green-900 rounded-full mix-blend-multiply filter blur-xl opacity-70 animate-float" style="animation-delay: 2s;"></div>
        <div class="absolute -bottom-8 left-20 w-72 h-72 bg-teal-300 dark:bg-teal-900 rounded-full mix-blend-multiply filter blur-xl opacity-70 animate-float" style="animation-delay: 4s;"></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">

            {{-- Page Header --}}
            <div class="mb-8 flex justify-between items-start">
                <div>
                    <h1 class="text-4xl font-bold text-gray-900 dark:text-white mb-2">
                        Report Review - {{ $report->month }}
                    </h1>
                    <p class="text-gray-600 dark:text-gray-400">
                        {{ $report->student->fullName() }} • Tutor: {{ $report->tutor->fullName() }}
                    </p>
                </div>
                <div class="flex items-center gap-3">
                    <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-medium bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-400">
                        {{ ucfirst($report->status) }}
                    </span>
                    <a href="{{ route('manager.tutor-reports.index') }}" class="px-4 py-2 bg-white/20 dark:bg-gray-800/30 text-gray-700 dark:text-gray-300 rounded-xl border border-white/10 hover:bg-white/30 dark:hover:bg-gray-800/50 transition-colors">
                        Back to List
                    </a>
                </div>
            </div>

            {{-- Success/Error Messages --}}
            @if(session('success'))
                <div class="mb-6 bg-green-100 dark:bg-green-900/30 border border-green-400 dark:border-green-700 text-green-800 dark:text-green-400 px-6 py-4 rounded-xl">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="mb-6 bg-red-100 dark:bg-red-900/30 border border-red-400 dark:border-red-700 text-red-800 dark:text-red-400 px-6 py-4 rounded-xl">
                    {{ session('error') }}
                </div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                {{-- Main Content --}}
                <div class="lg:col-span-2 space-y-6">

                    {{-- Report Metadata --}}
                    <div class="backdrop-blur-lg bg-white/40 dark:bg-gray-900/40 border border-white/10 rounded-xl shadow-xl p-6">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Report Details</h3>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-600 dark:text-gray-400 mb-1">Student</label>
                                <p class="text-gray-900 dark:text-white font-medium">{{ $report->student->fullName() }}</p>
                                <p class="text-sm text-gray-600 dark:text-gray-400">Age {{ $report->student->age }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-600 dark:text-gray-400 mb-1">Tutor</label>
                                <p class="text-gray-900 dark:text-white font-medium">{{ $report->tutor->fullName() }}</p>
                                <p class="text-sm text-gray-600 dark:text-gray-400">{{ $report->tutor->email }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-600 dark:text-gray-400 mb-1">Month</label>
                                <p class="text-gray-900 dark:text-white">{{ $report->month }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-600 dark:text-gray-400 mb-1">Submitted</label>
                                <p class="text-gray-900 dark:text-white">
                                    {{ $report->submitted_at ? $report->submitted_at->format('M d, Y g:i A') : 'N/A' }}
                                </p>
                            </div>
                            @if($report->attendance_score)
                            <div>
                                <label class="block text-sm font-medium text-gray-600 dark:text-gray-400 mb-1">Attendance Score</label>
                                <p class="text-gray-900 dark:text-white font-bold">{{ $report->attendance_score }}%</p>
                            </div>
                            @endif
                            @if($report->performance_rating)
                            <div>
                                <label class="block text-sm font-medium text-gray-600 dark:text-gray-400 mb-1">Performance Rating</label>
                                <p class="text-gray-900 dark:text-white font-bold">{{ $report->performance_rating }}/10</p>
                            </div>
                            @endif
                        </div>
                    </div>

                    {{-- Progress Summary --}}
                    @if($report->progress_summary)
                    <div class="backdrop-blur-lg bg-white/40 dark:bg-gray-900/40 border border-white/10 rounded-xl shadow-xl p-6">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-3 flex items-center">
                            <svg class="w-5 h-5 mr-2 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            Progress Summary
                        </h3>
                        <div class="prose dark:prose-invert max-w-none">
                            <p class="text-gray-700 dark:text-gray-300 whitespace-pre-wrap">{{ $report->progress_summary }}</p>
                        </div>
                    </div>
                    @endif

                    {{-- Strengths --}}
                    @if($report->strengths)
                    <div class="backdrop-blur-lg bg-white/40 dark:bg-gray-900/40 border border-white/10 rounded-xl shadow-xl p-6">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-3 flex items-center">
                            <svg class="w-5 h-5 mr-2 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 10h4.764a2 2 0 011.789 2.894l-3.5 7A2 2 0 0115.263 21h-4.017c-.163 0-.326-.02-.485-.06L7 20m7-10V5a2 2 0 00-2-2h-.095c-.5 0-.905.405-.905.905 0 .714-.211 1.412-.608 2.006L7 11v9m7-10h-2M7 20H5a2 2 0 01-2-2v-6a2 2 0 012-2h2.5" />
                            </svg>
                            Strengths
                        </h3>
                        <div class="prose dark:prose-invert max-w-none">
                            <p class="text-gray-700 dark:text-gray-300 whitespace-pre-wrap">{{ $report->strengths }}</p>
                        </div>
                    </div>
                    @endif

                    {{-- Weaknesses --}}
                    @if($report->weaknesses)
                    <div class="backdrop-blur-lg bg-white/40 dark:bg-gray-900/40 border border-white/10 rounded-xl shadow-xl p-6">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-3 flex items-center">
                            <svg class="w-5 h-5 mr-2 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                            </svg>
                            Areas for Improvement
                        </h3>
                        <div class="prose dark:prose-invert max-w-none">
                            <p class="text-gray-700 dark:text-gray-300 whitespace-pre-wrap">{{ $report->weaknesses }}</p>
                        </div>
                    </div>
                    @endif

                    {{-- Next Steps --}}
                    @if($report->next_steps)
                    <div class="backdrop-blur-lg bg-white/40 dark:bg-gray-900/40 border border-white/10 rounded-xl shadow-xl p-6">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-3 flex items-center">
                            <svg class="w-5 h-5 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                            </svg>
                            Next Steps
                        </h3>
                        <div class="prose dark:prose-invert max-w-none">
                            <p class="text-gray-700 dark:text-gray-300 whitespace-pre-wrap">{{ $report->next_steps }}</p>
                        </div>
                    </div>
                    @endif

                    {{-- Previous Comments Section --}}
                    @if($report->manager_comment || $report->director_comment)
                    <div class="backdrop-blur-lg bg-white/40 dark:bg-gray-900/40 border border-white/10 rounded-xl shadow-xl p-6">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Previous Comments</h3>

                        @if($report->manager_comment)
                        <div class="mb-4 bg-emerald-50 dark:bg-emerald-900/20 border border-emerald-200 dark:border-emerald-800 rounded-lg p-4">
                            <div class="flex items-center mb-2">
                                <span class="font-medium text-emerald-800 dark:text-emerald-400">Manager Comment</span>
                                @if($report->approved_by_manager_at)
                                <span class="ml-2 text-sm text-gray-600 dark:text-gray-400">
                                    • {{ $report->approved_by_manager_at->format('M d, Y') }}
                                </span>
                                @endif
                            </div>
                            <p class="text-gray-700 dark:text-gray-300">{{ $report->manager_comment }}</p>
                        </div>
                        @endif

                        @if($report->director_comment)
                        <div class="bg-purple-50 dark:bg-purple-900/20 border border-purple-200 dark:border-purple-800 rounded-lg p-4">
                            <div class="flex items-center mb-2">
                                <span class="font-medium text-purple-800 dark:text-purple-400">Director Comment</span>
                                @if($report->approved_by_director_at)
                                <span class="ml-2 text-sm text-gray-600 dark:text-gray-400">
                                    • {{ $report->approved_by_director_at->format('M d, Y') }}
                                </span>
                                @endif
                            </div>
                            <p class="text-gray-700 dark:text-gray-300">{{ $report->director_comment }}</p>
                        </div>
                        @endif
                    </div>
                    @endif

                </div>

                {{-- Sidebar - Manager Review Box --}}
                <div class="space-y-6">

                    {{-- Manager Review Actions --}}
                    <div class="backdrop-blur-lg bg-white/40 dark:bg-gray-900/40 border border-white/10 rounded-xl shadow-xl p-6">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Manager Review</h3>

                        {{-- Approve Form --}}
                        <form action="{{ route('manager.tutor-reports.approve', $report) }}" method="POST" class="mb-4" id="approveForm">
                            @csrf
                            <div class="mb-4">
                                <label for="manager_comment_approve" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Manager Comment (Optional)
                                </label>
                                <textarea
                                    id="manager_comment_approve"
                                    name="manager_comment"
                                    rows="4"
                                    maxlength="2000"
                                    placeholder="Add feedback or notes for the tutor and director..."
                                    class="w-full px-4 py-3 bg-white/50 dark:bg-gray-800/50 border border-gray-300 dark:border-gray-600 rounded-xl text-gray-900 dark:text-white focus:ring-2 focus:ring-emerald-500 focus:border-transparent resize-none"></textarea>
                                @error('manager_comment')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                                <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                                    Comments help provide context and feedback
                                </p>
                            </div>
                            <button
                                type="submit"
                                onclick="return confirm('Are you sure you want to approve this report? It will be forwarded to the director.')"
                                class="w-full px-6 py-3 bg-gradient-to-r from-emerald-500 to-green-500 text-white rounded-xl hover:shadow-lg hover:-translate-y-0.5 transition-all font-medium flex items-center justify-center">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                Approve Report
                            </button>
                        </form>

                        <div class="relative my-4">
                            <div class="absolute inset-0 flex items-center">
                                <div class="w-full border-t border-gray-300 dark:border-gray-600"></div>
                            </div>
                            <div class="relative flex justify-center text-sm">
                                <span class="px-2 bg-white/40 dark:bg-gray-900/40 text-gray-600 dark:text-gray-400">OR</span>
                            </div>
                        </div>

                        {{-- Send Back for Correction Form --}}
                        <form action="{{ route('manager.tutor-reports.correction', $report) }}" method="POST" id="correctionForm">
                            @csrf
                            <div class="mb-4">
                                <label for="manager_comment_correction" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Correction Notes (Required)
                                </label>
                                <textarea
                                    id="manager_comment_correction"
                                    name="manager_comment"
                                    rows="4"
                                    required
                                    maxlength="2000"
                                    placeholder="Explain what needs to be corrected..."
                                    class="w-full px-4 py-3 bg-white/50 dark:bg-gray-800/50 border border-gray-300 dark:border-gray-600 rounded-xl text-gray-900 dark:text-white focus:ring-2 focus:ring-amber-500 focus:border-transparent resize-none"></textarea>
                                @error('manager_comment')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                                <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                                    Required when sending back for corrections
                                </p>
                            </div>
                            <button
                                type="submit"
                                onclick="return confirm('Are you sure you want to send this report back to the tutor for corrections?')"
                                class="w-full px-6 py-3 bg-gradient-to-r from-amber-500 to-orange-500 text-white rounded-xl hover:shadow-lg hover:-translate-y-0.5 transition-all font-medium flex items-center justify-center">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                </svg>
                                Send Back for Correction
                            </button>
                        </form>
                    </div>

                    {{-- Quick Info --}}
                    <div class="backdrop-blur-lg bg-white/40 dark:bg-gray-900/40 border border-white/10 rounded-xl shadow-xl p-6">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Review Guidelines</h3>
                        <ul class="space-y-2 text-sm text-gray-700 dark:text-gray-300">
                            <li class="flex items-start">
                                <svg class="w-5 h-5 mr-2 text-emerald-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <span>Check all report sections are complete</span>
                            </li>
                            <li class="flex items-start">
                                <svg class="w-5 h-5 mr-2 text-emerald-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <span>Verify attendance and performance ratings</span>
                            </li>
                            <li class="flex items-start">
                                <svg class="w-5 h-5 mr-2 text-emerald-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <span>Add comments to provide context</span>
                            </li>
                            <li class="flex items-start">
                                <svg class="w-5 h-5 mr-2 text-amber-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                </svg>
                                <span>Send back if corrections are needed</span>
                            </li>
                        </ul>
                    </div>

                </div>
            </div>

        </div>
    </div>
</x-app-layout>
