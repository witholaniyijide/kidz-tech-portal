<x-tutor-layout>
    <!-- Page Header -->
    <div class="mb-8 flex justify-between items-start">
        <div>
            <h1 class="text-4xl font-bold bg-gradient-to-r from-purple-600 to-pink-600 bg-clip-text text-transparent mb-2">
                {{ $report->student->fullName() }} - {{ date('F Y', strtotime($report->month . '-01')) }}
            </h1>
            <p class="text-gray-600 dark:text-gray-400">
                Report submitted {{ $report->submitted_at ? $report->submitted_at->diffForHumans() : 'Not yet submitted' }}
            </p>
        </div>
        <div class="flex items-center space-x-3">
            @if(in_array($report->status, ['draft', 'returned']))
                <a href="{{ route('tutor.reports.edit', $report) }}"
                    class="px-5 py-2.5 bg-white/20 dark:bg-gray-800/30 text-gray-700 dark:text-gray-300 rounded-xl border border-white/10 hover:bg-white/30 dark:hover:bg-gray-800/50 transition-colors flex items-center font-medium">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                    </svg>
                    Edit
                </a>
            @endif
            @if($report->status === 'draft')
                <form action="{{ route('tutor.reports.submit', $report) }}" method="POST" class="inline-block"
                    onsubmit="return confirm('Are you sure you want to submit this report for review?');">
                    @csrf
                    <button type="submit"
                        class="px-5 py-2.5 bg-gradient-to-r from-purple-500 to-pink-500 text-white rounded-xl hover:shadow-lg hover:-translate-y-0.5 transition-all font-medium flex items-center">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        Submit for Review
                    </button>
                </form>
            @endif
        </div>
    </div>

    <!-- Status Badge -->
    <div class="mb-8">
        <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-semibold shadow-lg
            @if($report->status === 'submitted') bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-400
            @elseif($report->status === 'draft') bg-gray-100 text-gray-800 dark:bg-gray-900/30 dark:text-gray-400
            @elseif($report->status === 'manager_review') bg-purple-100 text-purple-800 dark:bg-purple-900/30 dark:text-purple-400
            @elseif($report->status === 'director_approved') bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400
            @elseif($report->status === 'returned') bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-400
            @else bg-purple-100 text-purple-800 dark:bg-purple-900/30 dark:text-purple-400
            @endif">
            {{ ucfirst(str_replace('_', ' ', $report->status)) }}
        </span>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Progress Summary Section -->
            <div class="bg-white/20 dark:bg-gray-900/30 backdrop-blur-xl border border-white/10 rounded-2xl p-8 shadow-lg">
                <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-4 flex items-center">
                    <svg class="w-6 h-6 mr-2 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                    </svg>
                    Progress Summary
                </h2>
                <p class="text-gray-700 dark:text-gray-300 whitespace-pre-wrap leading-relaxed">{{ $report->progress_summary }}</p>
            </div>

            <!-- Strengths Section -->
            <div class="bg-white/20 dark:bg-gray-900/30 backdrop-blur-xl border border-white/10 rounded-2xl p-8 shadow-lg">
                <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-4 flex items-center">
                    <svg class="w-6 h-6 mr-2 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z" />
                    </svg>
                    Strengths
                </h2>
                <p class="text-gray-700 dark:text-gray-300 whitespace-pre-wrap leading-relaxed">{{ $report->strengths }}</p>
            </div>

            <!-- Areas for Improvement Section -->
            <div class="bg-white/20 dark:bg-gray-900/30 backdrop-blur-xl border border-white/10 rounded-2xl p-8 shadow-lg">
                <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-4 flex items-center">
                    <svg class="w-6 h-6 mr-2 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                    </svg>
                    Areas for Improvement
                </h2>
                <p class="text-gray-700 dark:text-gray-300 whitespace-pre-wrap leading-relaxed">{{ $report->weaknesses }}</p>
            </div>

            <!-- Next Steps Section -->
            <div class="bg-white/20 dark:bg-gray-900/30 backdrop-blur-xl border border-white/10 rounded-2xl p-8 shadow-lg">
                <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-4 flex items-center">
                    <svg class="w-6 h-6 mr-2 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 9l3 3m0 0l-3 3m3-3H8m13 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    Next Steps & Recommendations
                </h2>
                <p class="text-gray-700 dark:text-gray-300 whitespace-pre-wrap leading-relaxed">{{ $report->next_steps }}</p>
            </div>

            <!-- Comments Section -->
            <div class="bg-white/20 dark:bg-gray-900/30 backdrop-blur-xl border border-white/10 rounded-2xl p-8 shadow-lg">
                <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-6">Comments & Feedback</h2>

                @if($report->comments->isEmpty())
                    <p class="text-gray-600 dark:text-gray-400 text-center py-8">No comments yet</p>
                @else
                    <div class="space-y-4 mb-6">
                        @foreach($report->comments as $comment)
                            <div class="bg-white/10 dark:bg-gray-800/30 rounded-xl p-4 border border-white/5">
                                <div class="flex items-start justify-between mb-2">
                                    <div class="flex items-center space-x-2">
                                        <span class="font-semibold text-gray-900 dark:text-white">{{ $comment->user->name }}</span>
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium
                                            @if($comment->role === 'tutor') bg-purple-100 text-purple-800 dark:bg-purple-900/30 dark:text-purple-400
                                            @else bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-400
                                            @endif">
                                            {{ ucfirst($comment->role) }}
                                        </span>
                                    </div>
                                    <span class="text-sm text-gray-600 dark:text-gray-400">{{ $comment->created_at->diffForHumans() }}</span>
                                </div>
                                <p class="text-gray-700 dark:text-gray-300">{{ $comment->comment }}</p>
                            </div>
                        @endforeach
                    </div>
                @endif

                <!-- Add Comment Form -->
                <form action="{{ route('tutor.reports.comments.store', $report) }}" method="POST" class="mt-6">
                    @csrf
                    <div class="mb-4">
                        <label for="comment" class="block text-sm font-semibold text-gray-900 dark:text-white mb-2">
                            Add a comment
                        </label>
                        <textarea id="comment" name="comment" rows="3" required maxlength="2000"
                            class="w-full px-4 py-3 bg-white/50 dark:bg-gray-800/50 border border-gray-300 dark:border-gray-600 rounded-xl text-gray-900 dark:text-white focus:ring-2 focus:ring-purple-500 focus:border-transparent"
                            placeholder="Enter your comment..."></textarea>
                        @error('comment')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>
                    <button type="submit"
                        class="px-6 py-3 bg-gradient-to-r from-purple-500 to-pink-500 text-white rounded-xl hover:shadow-lg hover:-translate-y-0.5 transition-all font-medium">
                        Post Comment
                    </button>
                </form>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Performance Metrics Card -->
            <div class="bg-white/20 dark:bg-gray-900/30 backdrop-blur-xl border border-white/10 rounded-2xl p-6 shadow-lg">
                <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4">Performance Metrics</h3>
                
                <!-- Attendance Score with Progress Bar -->
                <div class="mb-6">
                    <div class="flex justify-between items-center mb-2">
                        <label class="text-sm font-semibold text-gray-700 dark:text-gray-300">Attendance Score</label>
                        <span class="text-2xl font-bold text-purple-600 dark:text-purple-400">{{ $report->attendance_score }}%</span>
                    </div>
                    <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-3 overflow-hidden">
                        <div class="bg-gradient-to-r from-purple-500 to-pink-500 h-3 rounded-full transition-all duration-500"
                            style="width: {{ $report->attendance_score }}%"></div>
                    </div>
                </div>

                <!-- Performance Rating -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Performance Rating</label>
                    <div class="flex items-center space-x-2">
                        @if($report->performance_rating === 'excellent')
                            <span class="flex-1 px-4 py-2 bg-green-100 dark:bg-green-900/30 text-green-800 dark:text-green-400 rounded-lg text-center font-semibold">
                                🌟 Excellent
                            </span>
                        @elseif($report->performance_rating === 'good')
                            <span class="flex-1 px-4 py-2 bg-blue-100 dark:bg-blue-900/30 text-blue-800 dark:text-blue-400 rounded-lg text-center font-semibold">
                                👍 Good
                            </span>
                        @elseif($report->performance_rating === 'average')
                            <span class="flex-1 px-4 py-2 bg-yellow-100 dark:bg-yellow-900/30 text-yellow-800 dark:text-yellow-400 rounded-lg text-center font-semibold">
                                ⚖️ Average
                            </span>
                        @else
                            <span class="flex-1 px-4 py-2 bg-red-100 dark:bg-red-900/30 text-red-800 dark:text-red-400 rounded-lg text-center font-semibold">
                                ⚠️ Needs Improvement
                            </span>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Student Info Card -->
            <div class="bg-white/20 dark:bg-gray-900/30 backdrop-blur-xl border border-white/10 rounded-2xl p-6 shadow-lg">
                <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4">Student Information</h3>
                <div class="space-y-3">
                    <div>
                        <label class="block text-sm font-medium text-gray-600 dark:text-gray-400 mb-1">Name</label>
                        <p class="font-semibold text-gray-900 dark:text-white">{{ $report->student->fullName() }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-600 dark:text-gray-400 mb-1">Age</label>
                        <p class="font-semibold text-gray-900 dark:text-white">{{ $report->student->age }} years</p>
                    </div>
                    @if($report->student->location)
                        <div>
                            <label class="block text-sm font-medium text-gray-600 dark:text-gray-400 mb-1">Location</label>
                            <p class="font-semibold text-gray-900 dark:text-white">{{ $report->student->location }}</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Report Timeline Card -->
            <div class="bg-white/20 dark:bg-gray-900/30 backdrop-blur-xl border border-white/10 rounded-2xl p-6 shadow-lg">
                <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4">Report Timeline</h3>
                <div class="space-y-3">
                    <div>
                        <label class="block text-sm font-medium text-gray-600 dark:text-gray-400 mb-1">Created</label>
                        <p class="text-sm text-gray-900 dark:text-white">{{ $report->created_at->format('M d, Y g:i A') }}</p>
                    </div>
                    @if($report->submitted_at)
                        <div>
                            <label class="block text-sm font-medium text-gray-600 dark:text-gray-400 mb-1">Submitted</label>
                            <p class="text-sm text-gray-900 dark:text-white">{{ $report->submitted_at->format('M d, Y g:i A') }}</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Export & Share Card -->
            <div class="bg-white/20 dark:bg-gray-900/30 backdrop-blur-xl border border-white/10 rounded-2xl p-6 shadow-lg">
                <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4">Export & Share</h3>
                <div class="space-y-3">
                    <a href="{{ route('tutor.reports.pdf', $report) }}"
                        class="flex items-center justify-center w-full px-4 py-3 bg-gradient-to-r from-red-500 to-orange-500 text-white rounded-xl hover:shadow-lg hover:-translate-y-0.5 transition-all font-medium">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                        </svg>
                        Download PDF
                    </a>

                    <a href="{{ route('tutor.reports.print', $report) }}" target="_blank"
                        class="flex items-center justify-center w-full px-4 py-3 bg-gradient-to-r from-blue-500 to-cyan-500 text-white rounded-xl hover:shadow-lg hover:-translate-y-0.5 transition-all font-medium">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                        </svg>
                        Print Report
                    </a>
                </div>
            </div>

            <!-- Actions Card -->
            <div class="bg-white/20 dark:bg-gray-900/30 backdrop-blur-xl border border-white/10 rounded-2xl p-6 shadow-lg">
                <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4">Actions</h3>
                <div class="space-y-3">
                    <a href="{{ route('tutor.reports.index') }}"
                        class="block w-full px-4 py-3 bg-white/20 dark:bg-gray-800/30 text-gray-700 dark:text-gray-300 rounded-xl border border-white/10 hover:bg-white/30 dark:hover:bg-gray-800/50 transition-colors text-center font-medium">
                        ← Back to Reports
                    </a>
                    @if(in_array($report->status, ['draft', 'returned']))
                        <a href="{{ route('tutor.reports.edit', $report) }}"
                            class="block w-full px-4 py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-xl transition-colors text-center font-medium">
                            Edit Report
                        </a>
                    @endif
                    @if($report->status === 'draft')
                        <form action="{{ route('tutor.reports.destroy', $report) }}" method="POST"
                            onsubmit="return confirm('Are you sure you want to delete this report?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                class="block w-full px-4 py-3 bg-red-600 hover:bg-red-700 text-white rounded-xl transition-colors text-center font-medium">
                                Delete Report
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-tutor-layout>
