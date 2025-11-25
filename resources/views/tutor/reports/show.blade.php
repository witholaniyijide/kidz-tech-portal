<x-tutor-layout>
    <!-- Page Header -->
    <div class="mb-8 flex justify-between items-center">
        <div>
            <h1 class="text-4xl font-bold text-gray-900 dark:text-white mb-2">
                {{ $report->title }}
            </h1>
            <p class="text-gray-600 dark:text-gray-400">
                {{ $report->student->fullName() }} • {{ $report->month }}
            </p>
        </div>
        <div class="flex items-center space-x-3">
            @if(in_array($report->status, ['draft', 'returned']))
                <a href="{{ route('tutor.reports.edit', $report) }}"
                    class="px-6 py-3 bg-white/20 dark:bg-gray-800/30 text-gray-700 dark:text-gray-300 rounded-xl border border-white/10 hover:bg-white/30 dark:hover:bg-gray-800/50 transition-colors flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                    </svg>
                    Edit Report
                </a>
            @endif
            @if($report->status === 'draft')
                <form action="{{ route('tutor.reports.submit', $report) }}" method="POST" class="inline-block"
                    onsubmit="return confirm('Are you sure you want to submit this report for review?');">
                    @csrf
                    <button type="submit"
                        class="px-6 py-3 bg-gradient-to-r from-purple-500 to-pink-500 text-white rounded-xl hover:shadow-lg hover:-translate-y-0.5 transition-all font-medium flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        Submit for Review
                    </button>
                </form>
            @endif
        </div>
    </div>

    <!-- Report Details Card -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-8">
            <!-- Report Info -->
            <div class="bg-white/20 dark:bg-gray-900/30 backdrop-blur-xl border border-white/10 rounded-2xl p-8 shadow-lg">
                <!-- Status -->
                <div class="mb-6">
                    <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-medium
                        @if($report->status === 'submitted') bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-400
                        @elseif($report->status === 'draft') bg-gray-100 text-gray-800 dark:bg-gray-900/30 dark:text-gray-400
                        @elseif($report->status === 'approved') bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400
                        @elseif($report->status === 'returned') bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-400
                        @else bg-purple-100 text-purple-800 dark:bg-purple-900/30 dark:text-purple-400
                        @endif">
                        {{ ucfirst($report->status) }}
                    </span>
                </div>

                <!-- Metadata -->
                <div class="grid grid-cols-2 gap-4 mb-6 pb-6 border-b border-gray-200 dark:border-gray-700">
                    <div>
                        <label class="block text-sm font-medium text-gray-600 dark:text-gray-400 mb-1">Student</label>
                        <p class="text-gray-900 dark:text-white">{{ $report->student->fullName() }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-600 dark:text-gray-400 mb-1">Month</label>
                        <p class="text-gray-900 dark:text-white">{{ $report->month }}</p>
                    </div>
                    @if($report->period_from && $report->period_to)
                        <div>
                            <label class="block text-sm font-medium text-gray-600 dark:text-gray-400 mb-1">Period</label>
                            <p class="text-gray-900 dark:text-white">
                                {{ $report->period_from->format('M d, Y') }} - {{ $report->period_to->format('M d, Y') }}
                            </p>
                        </div>
                    @endif
                    @if($report->rating)
                        <div>
                            <label class="block text-sm font-medium text-gray-600 dark:text-gray-400 mb-1">Rating</label>
                            <p class="text-gray-900 dark:text-white">{{ $report->rating }}/10</p>
                        </div>
                    @endif
                    <div>
                        <label class="block text-sm font-medium text-gray-600 dark:text-gray-400 mb-1">Created</label>
                        <p class="text-gray-900 dark:text-white">{{ $report->created_at->format('M d, Y g:i A') }}</p>
                    </div>
                    @if($report->submitted_at)
                        <div>
                            <label class="block text-sm font-medium text-gray-600 dark:text-gray-400 mb-1">Submitted</label>
                            <p class="text-gray-900 dark:text-white">{{ $report->submitted_at->format('M d, Y g:i A') }}</p>
                        </div>
                    @endif
                </div>

                <!-- Summary -->
                @if($report->summary)
                    <div class="mb-6">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">Summary</h3>
                        <p class="text-gray-700 dark:text-gray-300">{{ $report->summary }}</p>
                    </div>
                @endif

                <!-- Content -->
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Report Content</h3>
                    <div class="prose dark:prose-invert max-w-none text-gray-700 dark:text-gray-300 whitespace-pre-wrap">{{ $report->content }}</div>
                </div>
            </div>

            <!-- Comments Section -->
            <div class="bg-white/20 dark:bg-gray-900/30 backdrop-blur-xl border border-white/10 rounded-2xl p-8 shadow-lg">
                <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-6">Comments</h3>

                <!-- Comments List -->
                @if($report->comments->isEmpty())
                    <p class="text-gray-600 dark:text-gray-400 text-center py-8">No comments yet</p>
                @else
                    <div class="space-y-4 mb-6">
                        @foreach($report->comments as $comment)
                            <div class="bg-white/10 dark:bg-gray-800/30 rounded-xl p-4 border border-white/5">
                                <div class="flex items-start justify-between mb-2">
                                    <div class="flex items-center space-x-2">
                                        <span class="font-medium text-gray-900 dark:text-white">{{ $comment->user->name }}</span>
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
                        <label for="comment" class="block text-sm font-medium text-gray-900 dark:text-white mb-2">
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
            <!-- Actions Card -->
            <div class="bg-white/20 dark:bg-gray-900/30 backdrop-blur-xl border border-white/10 rounded-2xl p-6 shadow-lg">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Actions</h3>
                <div class="space-y-3">
                    <a href="{{ route('tutor.reports.index') }}"
                        class="block w-full px-4 py-3 bg-white/20 dark:bg-gray-800/30 text-gray-700 dark:text-gray-300 rounded-xl border border-white/10 hover:bg-white/30 dark:hover:bg-gray-800/50 transition-colors text-center">
                        Back to Reports
                    </a>
                    @if(in_array($report->status, ['draft', 'returned']))
                        <a href="{{ route('tutor.reports.edit', $report) }}"
                            class="block w-full px-4 py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-xl transition-colors text-center">
                            Edit Report
                        </a>
                    @endif
                    @if($report->status === 'draft')
                        <form action="{{ route('tutor.reports.destroy', $report) }}" method="POST"
                            onsubmit="return confirm('Are you sure you want to delete this report?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                class="block w-full px-4 py-3 bg-red-600 hover:bg-red-700 text-white rounded-xl transition-colors text-center">
                                Delete Report
                            </button>
                        </form>
                    @endif
                </div>
            </div>

            <!-- Student Info Card -->
            <div class="bg-white/20 dark:bg-gray-900/30 backdrop-blur-xl border border-white/10 rounded-2xl p-6 shadow-lg">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Student Information</h3>
                <div class="space-y-3">
                    <div>
                        <label class="block text-sm font-medium text-gray-600 dark:text-gray-400">Name</label>
                        <p class="text-gray-900 dark:text-white">{{ $report->student->fullName() }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-600 dark:text-gray-400">Age</label>
                        <p class="text-gray-900 dark:text-white">{{ $report->student->age }} years</p>
                    </div>
                    @if($report->student->location)
                        <div>
                            <label class="block text-sm font-medium text-gray-600 dark:text-gray-400">Location</label>
                            <p class="text-gray-900 dark:text-white">{{ $report->student->location }}</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-tutor-layout>
