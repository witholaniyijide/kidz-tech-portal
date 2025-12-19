<x-manager-layout title="Review Report">
    {{-- Page Header --}}
    <div class="mb-8 flex justify-between items-start">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-2">
                Report Review - {{ $report->month }}
            </h1>
            <p class="text-gray-600 dark:text-gray-400">
                {{ $report->student->fullName() }} | Tutor: {{ $report->tutor->fullName() }}
            </p>
        </div>
        <div class="flex items-center gap-3">
            <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-medium
                @if($report->status === 'submitted') bg-amber-100 text-amber-800 dark:bg-amber-900/30 dark:text-amber-400
                @elseif($report->status === 'approved-by-manager') bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-400
                @elseif($report->status === 'approved-by-director') bg-emerald-100 text-emerald-800 dark:bg-emerald-900/30 dark:text-emerald-400
                @else bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300
                @endif">
                {{ ucfirst($report->status) }}
            </span>
            <a href="{{ route('manager.tutor-reports.index') }}" class="px-4 py-2 bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-xl hover:bg-gray-300 dark:hover:bg-gray-600 transition-colors">
                Back to List
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        {{-- Main Content --}}
        <div class="lg:col-span-2 space-y-6">

            {{-- Report Metadata --}}
            <div class="bg-white/70 dark:bg-gray-800/70 backdrop-blur-xl border border-white/20 dark:border-gray-700/50 rounded-xl shadow-sm p-6">
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
            <div class="bg-white/70 dark:bg-gray-800/70 backdrop-blur-xl border border-white/20 dark:border-gray-700/50 rounded-xl shadow-sm p-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-3 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-[#C15F3C]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
            <div class="bg-white/70 dark:bg-gray-800/70 backdrop-blur-xl border border-white/20 dark:border-gray-700/50 rounded-xl shadow-sm p-6">
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
            <div class="bg-white/70 dark:bg-gray-800/70 backdrop-blur-xl border border-white/20 dark:border-gray-700/50 rounded-xl shadow-sm p-6">
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
            <div class="bg-white/70 dark:bg-gray-800/70 backdrop-blur-xl border border-white/20 dark:border-gray-700/50 rounded-xl shadow-sm p-6">
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
            <div class="bg-white/70 dark:bg-gray-800/70 backdrop-blur-xl border border-white/20 dark:border-gray-700/50 rounded-xl shadow-sm p-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Previous Comments</h3>

                @if($report->manager_comment)
                <div class="mb-4 bg-[#C15F3C]/10 dark:bg-[#C15F3C]/20 border border-[#C15F3C]/30 dark:border-[#C15F3C]/40 rounded-lg p-4">
                    <div class="flex items-center mb-2">
                        <span class="font-medium text-[#C15F3C] dark:text-[#DA7756]">Manager Comment</span>
                        @if($report->approved_by_manager_at)
                        <span class="ml-2 text-sm text-gray-600 dark:text-gray-400">
                            | {{ $report->approved_by_manager_at->format('M d, Y') }}
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
                            | {{ $report->approved_by_director_at->format('M d, Y') }}
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
            <div class="bg-white/70 dark:bg-gray-800/70 backdrop-blur-xl border border-white/20 dark:border-gray-700/50 rounded-xl shadow-sm p-6">
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
                            class="w-full px-4 py-3 bg-white/50 dark:bg-gray-800/50 border border-gray-300 dark:border-gray-600 rounded-xl text-gray-900 dark:text-white focus:ring-2 focus:ring-[#C15F3C] focus:border-transparent resize-none"></textarea>
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
                        <span class="px-2 bg-white/70 dark:bg-gray-800/70 text-gray-600 dark:text-gray-400">OR</span>
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

            {{-- Export & Share Card --}}
            <div class="bg-white/70 dark:bg-gray-800/70 backdrop-blur-xl border border-white/20 dark:border-gray-700/50 rounded-xl shadow-sm p-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Export & Share</h3>
                <div class="space-y-3">
                    <a href="{{ route('manager.tutor-reports.pdf', $report) }}"
                        class="flex items-center justify-center w-full px-4 py-3 bg-gradient-to-r from-red-500 to-orange-500 text-white rounded-xl hover:shadow-lg hover:-translate-y-0.5 transition-all font-medium">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                        </svg>
                        Download PDF
                    </a>

                    <a href="{{ route('manager.tutor-reports.print', $report) }}" target="_blank"
                        class="flex items-center justify-center w-full px-4 py-3 bg-gradient-to-r from-[#C15F3C] to-[#DA7756] text-white rounded-xl hover:shadow-lg hover:-translate-y-0.5 transition-all font-medium">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                        </svg>
                        Print Report
                    </a>
                </div>
            </div>

            {{-- Quick Info --}}
            <div class="bg-white/70 dark:bg-gray-800/70 backdrop-blur-xl border border-white/20 dark:border-gray-700/50 rounded-xl shadow-sm p-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Review Guidelines</h3>
                <ul class="space-y-2 text-sm text-gray-700 dark:text-gray-300">
                    <li class="flex items-start">
                        <svg class="w-5 h-5 mr-2 text-[#C15F3C] flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span>Check all report sections are complete</span>
                    </li>
                    <li class="flex items-start">
                        <svg class="w-5 h-5 mr-2 text-[#C15F3C] flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span>Verify attendance and performance ratings</span>
                    </li>
                    <li class="flex items-start">
                        <svg class="w-5 h-5 mr-2 text-[#C15F3C] flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
</x-manager-layout>
