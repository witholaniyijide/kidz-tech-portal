<x-app-layout>
    <x-slot name="header">
        {{ __('Director Final Review') }}
    </x-slot>

    <x-slot name="title">{{ __('Director Final Review') }}</x-slot>

    {{-- Animated Background - Director Royal Blue to Purple Gradient --}}
    <div class="min-h-screen bg-gradient-to-br from-blue-50 via-indigo-50 to-purple-50 dark:from-slate-900 dark:via-slate-800 dark:to-slate-900 py-12 relative overflow-hidden">
        {{-- Floating Orbs Background --}}
        <div class="absolute top-0 left-0 w-72 h-72 bg-blue-400 dark:bg-blue-900 rounded-full mix-blend-multiply filter blur-xl opacity-70 animate-float"></div>
        <div class="absolute top-0 right-0 w-72 h-72 bg-purple-400 dark:bg-purple-900 rounded-full mix-blend-multiply filter blur-xl opacity-70 animate-float" style="animation-delay: 2s;"></div>
        <div class="absolute -bottom-8 left-20 w-72 h-72 bg-indigo-400 dark:bg-indigo-900 rounded-full mix-blend-multiply filter blur-xl opacity-70 animate-float" style="animation-delay: 4s;"></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">

            {{-- Page Header --}}
            <div class="mb-8 flex justify-between items-start">
                <div>
                    <h1 class="text-4xl font-bold text-gray-900 dark:text-white mb-2">
                        Final Approval — {{ $report->month }}
                    </h1>
                    <p class="text-gray-600 dark:text-gray-400">
                        {{ $report->student->fullName() }} • Tutor: {{ $report->tutor->fullName() }}
                    </p>
                </div>
                <div class="flex items-center gap-3">
                    <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-medium
                        @if($report->status === 'approved-by-manager') bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400
                        @elseif($report->status === 'approved-by-director') bg-purple-100 text-purple-800 dark:bg-purple-900/30 dark:text-purple-400
                        @elseif($report->status === 'rejected') bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400
                        @else bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-400
                        @endif">
                        {{ ucfirst(str_replace('-', ' ', $report->status)) }}
                    </span>
                    <a href="{{ route('director.reports.index') }}" class="px-4 py-2 bg-white/20 dark:bg-gray-800/30 text-gray-700 dark:text-gray-300 rounded-xl border border-white/10 hover:bg-white/30 dark:hover:bg-gray-800/50 transition-colors">
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

            @if(session('info'))
                <div class="mb-6 bg-blue-100 dark:bg-blue-900/30 border border-blue-400 dark:border-blue-700 text-blue-800 dark:text-blue-400 px-6 py-4 rounded-xl">
                    {{ session('info') }}
                </div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                {{-- Main Content --}}
                <div class="lg:col-span-2 space-y-6">

                    {{-- Report Metadata --}}
                    <div class="backdrop-blur-md bg-white/30 dark:bg-gray-900/30 border border-white/10 rounded-2xl shadow-xl p-6">
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
                            @if($report->approved_by_manager_at)
                            <div>
                                <label class="block text-sm font-medium text-gray-600 dark:text-gray-400 mb-1">Approved by Manager</label>
                                <p class="text-gray-900 dark:text-white">
                                    {{ $report->approved_by_manager_at->format('M d, Y g:i A') }}
                                </p>
                            </div>
                            @endif
                            @if($report->attendance_score)
                            <div>
                                <label class="block text-sm font-medium text-gray-600 dark:text-gray-400 mb-1">Attendance Score</label>
                                <p class="text-gray-900 dark:text-white font-bold">{{ $report->attendance_score }}%</p>
                            </div>
                            @endif
                            @if($report->performance_rating)
                            <div>
                                <label class="block text-sm font-medium text-gray-600 dark:text-gray-400 mb-1">Performance Rating</label>
                                <p class="text-gray-900 dark:text-white font-bold capitalize">{{ $report->performance_rating }}</p>
                            </div>
                            @endif
                        </div>
                    </div>

                    {{-- Progress Summary --}}
                    @if($report->progress_summary)
                    <div class="backdrop-blur-md bg-white/30 dark:bg-gray-900/30 border border-white/10 rounded-2xl shadow-xl p-6">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-3 flex items-center">
                            <svg class="w-5 h-5 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
                    <div class="backdrop-blur-md bg-white/30 dark:bg-gray-900/30 border border-white/10 rounded-2xl shadow-xl p-6">
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
                    <div class="backdrop-blur-md bg-white/30 dark:bg-gray-900/30 border border-white/10 rounded-2xl shadow-xl p-6">
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
                    <div class="backdrop-blur-md bg-white/30 dark:bg-gray-900/30 border border-white/10 rounded-2xl shadow-xl p-6">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-3 flex items-center">
                            <svg class="w-5 h-5 mr-2 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                            </svg>
                            Next Steps
                        </h3>
                        <div class="prose dark:prose-invert max-w-none">
                            <p class="text-gray-700 dark:text-gray-300 whitespace-pre-wrap">{{ $report->next_steps }}</p>
                        </div>
                    </div>
                    @endif

                    {{-- Manager Comment --}}
                    @if($report->manager_comment)
                    <div class="backdrop-blur-md bg-white/30 dark:bg-gray-900/30 border border-white/10 rounded-2xl shadow-xl p-6">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Manager Comment</h3>
                        <div class="bg-emerald-50 dark:bg-emerald-900/20 border border-emerald-200 dark:border-emerald-800 rounded-lg p-4">
                            <div class="flex items-center mb-2">
                                <span class="font-medium text-emerald-800 dark:text-emerald-400">Manager Feedback</span>
                                @if($report->approved_by_manager_at)
                                <span class="ml-2 text-sm text-gray-600 dark:text-gray-400">
                                    • {{ $report->approved_by_manager_at->format('M d, Y') }}
                                </span>
                                @endif
                            </div>
                            <p class="text-gray-700 dark:text-gray-300">{{ $report->manager_comment }}</p>
                        </div>
                    </div>
                    @endif

                </div>

                {{-- Sidebar - Director Actions & Audit Trail --}}
                <div class="space-y-6">

                    {{-- Director Final Approval Actions --}}
                    @if($report->status === 'approved-by-manager')
                    <div class="backdrop-blur-md bg-white/30 dark:bg-gray-900/30 border border-white/10 rounded-2xl shadow-xl p-6">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Director Final Approval</h3>

                        {{-- Approve Form --}}
                        <form action="{{ route('director.reports.approve', $report) }}" method="POST" class="mb-4" id="approveForm">
                            @csrf
                            <div class="mb-4">
                                <label for="director_comment_approve" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Director Comment (Optional)
                                </label>
                                <textarea
                                    id="director_comment_approve"
                                    name="director_comment"
                                    rows="4"
                                    maxlength="2000"
                                    placeholder="Add final approval notes or feedback..."
                                    class="w-full px-4 py-3 bg-white/50 dark:bg-gray-800/50 border border-gray-300 dark:border-gray-600 rounded-xl text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent resize-none"></textarea>
                                @error('director_comment')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                                <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                                    Optional comments for final approval
                                </p>
                            </div>
                            <button
                                type="submit"
                                onclick="return confirm('Are you sure you want to give FINAL APPROVAL to this report? This will notify the tutor and manager.')"
                                class="w-full px-6 py-3 bg-gradient-to-r from-green-500 to-emerald-600 text-white rounded-xl hover:shadow-lg transform hover:-translate-y-1 transition-all font-bold flex items-center justify-center">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                Approve (Final)
                            </button>
                        </form>

                        <div class="relative my-4">
                            <div class="absolute inset-0 flex items-center">
                                <div class="w-full border-t border-gray-300 dark:border-gray-600"></div>
                            </div>
                            <div class="relative flex justify-center text-sm">
                                <span class="px-2 bg-white/30 dark:bg-gray-900/30 text-gray-600 dark:text-gray-400">OR</span>
                            </div>
                        </div>

                        {{-- Reject Form --}}
                        <form action="{{ route('director.reports.reject', $report) }}" method="POST" id="rejectForm">
                            @csrf
                            <div class="mb-4">
                                <label for="director_comment_reject" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Rejection Reason (Required)
                                </label>
                                <textarea
                                    id="director_comment_reject"
                                    name="director_comment"
                                    rows="4"
                                    required
                                    maxlength="2000"
                                    placeholder="Explain why this report is being rejected..."
                                    class="w-full px-4 py-3 bg-white/50 dark:bg-gray-800/50 border border-gray-300 dark:border-gray-600 rounded-xl text-gray-900 dark:text-white focus:ring-2 focus:ring-red-500 focus:border-transparent resize-none"></textarea>
                                @error('director_comment')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                                <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                                    Required when rejecting a report
                                </p>
                            </div>
                            <button
                                type="submit"
                                onclick="return confirm('Are you sure you want to REJECT this report? This will notify the tutor and manager.')"
                                class="w-full px-6 py-3 bg-gradient-to-r from-red-500 to-red-600 text-white rounded-xl hover:shadow-lg transform hover:-translate-y-1 transition-all font-bold flex items-center justify-center">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                                Reject Report
                            </button>
                        </form>
                    </div>
                    @else
                    <div class="backdrop-blur-md bg-white/30 dark:bg-gray-900/30 border border-white/10 rounded-2xl shadow-xl p-6">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Report Status</h3>
                        <p class="text-gray-700 dark:text-gray-300">
                            This report has already been processed. Current status:
                            <strong class="capitalize">{{ str_replace('-', ' ', $report->status) }}</strong>
                        </p>
                    </div>
                    @endif

                    {{-- Audit Trail --}}
                    @if($report->audits()->count() > 0)
                    <div class="backdrop-blur-md bg-white/30 dark:bg-gray-900/30 border border-white/10 rounded-2xl shadow-xl p-6">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 flex items-center">
                            <svg class="w-5 h-5 mr-2 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            Audit Trail
                        </h3>
                        <div class="space-y-3">
                            @foreach($report->audits()->orderBy('created_at', 'desc')->get() as $audit)
                            <div class="bg-gray-50 dark:bg-gray-800/50 border border-gray-200 dark:border-gray-700 rounded-lg p-3">
                                <div class="flex items-center justify-between mb-1">
                                    <span class="text-xs font-semibold text-purple-600 dark:text-purple-400">
                                        {{ ucfirst(str_replace('.', ' → ', $audit->action)) }}
                                    </span>
                                    <span class="text-xs text-gray-500 dark:text-gray-400">
                                        {{ $audit->created_at->format('M d, g:i A') }}
                                    </span>
                                </div>
                                <p class="text-xs text-gray-600 dark:text-gray-400">
                                    By: <strong>{{ $audit->user->name ?? 'Unknown' }}</strong>
                                </p>
                                @if(isset($audit->meta['director_comment']) || isset($audit->meta['manager_comment']))
                                <div class="mt-2 text-xs text-gray-700 dark:text-gray-300">
                                    @if(isset($audit->meta['director_comment']))
                                    <p><em>"{{ \Illuminate\Support\Str::limit($audit->meta['director_comment'], 100) }}"</em></p>
                                    @endif
                                </div>
                                @endif
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endif

                    {{-- Quick Info --}}
                    <div class="backdrop-blur-md bg-white/30 dark:bg-gray-900/30 border border-white/10 rounded-2xl shadow-xl p-6">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Review Guidelines</h3>
                        <ul class="space-y-2 text-sm text-gray-700 dark:text-gray-300">
                            <li class="flex items-start">
                                <svg class="w-5 h-5 mr-2 text-blue-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <span>Review all report sections and manager feedback</span>
                            </li>
                            <li class="flex items-start">
                                <svg class="w-5 h-5 mr-2 text-blue-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <span>Ensure accuracy and completeness</span>
                            </li>
                            <li class="flex items-start">
                                <svg class="w-5 h-5 mr-2 text-blue-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <span>Final approval locks the report from editing</span>
                            </li>
                            <li class="flex items-start">
                                <svg class="w-5 h-5 mr-2 text-red-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                                <span>Reject with detailed feedback if needed</span>
                            </li>
                        </ul>
                    </div>

                </div>
            </div>

        </div>
    </div>
</x-app-layout>
