<x-parent-layout title="Message">
    <div class="space-y-6">
        <!-- Back Button -->
        <div>
            <a href="{{ route('parent.messages.index') }}" class="inline-flex items-center text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white transition-colors">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Back to Messages
            </a>
        </div>

        <!-- Message Thread -->
        <div class="glass-card rounded-2xl overflow-hidden">
            <!-- Header -->
            <div class="p-4 border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800">
                <h1 class="text-lg font-bold text-gray-900 dark:text-white">{{ $message->subject }}</h1>
            </div>

            <!-- Messages -->
            <div class="divide-y divide-gray-200 dark:divide-gray-700">
                @foreach($thread as $msg)
                    <div class="p-4 {{ $msg->sender_id === auth()->id() ? 'bg-amber-50 dark:bg-amber-900/10' : '' }}">
                        <div class="flex items-start space-x-4">
                            <div class="w-10 h-10 rounded-full {{ $msg->sender_id === auth()->id() ? 'bg-gradient-parent' : 'bg-gray-200 dark:bg-gray-700' }} flex items-center justify-center text-{{ $msg->sender_id === auth()->id() ? 'white' : 'gray-600 dark:text-gray-300' }} font-semibold flex-shrink-0">
                                {{ substr($msg->sender->name ?? 'U', 0, 1) }}
                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center justify-between">
                                    <p class="font-medium text-gray-900 dark:text-white">
                                        {{ $msg->sender_id === auth()->id() ? 'You' : ($msg->sender->name ?? 'Unknown') }}
                                    </p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">
                                        {{ $msg->created_at->format('M d, Y \a\t g:i A') }}
                                    </p>
                                </div>
                                <div class="mt-2 text-gray-700 dark:text-gray-300 whitespace-pre-wrap">
                                    {{ $msg->body }}
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Reply Form -->
            <div class="p-4 border-t border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800">
                <form method="POST" action="{{ route('parent.messages.reply', $message) }}" class="space-y-4">
                    @csrf
                    <div>
                        <label for="body" class="sr-only">Reply</label>
                        <textarea id="body" name="body" rows="4" required
                                  class="w-full rounded-xl border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-[#F5A623] focus:border-[#F5A623]"
                                  placeholder="Write your reply..."></textarea>
                        @error('body')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="flex justify-end">
                        <button type="submit" class="btn-parent-primary px-6 py-2.5 rounded-xl font-medium inline-flex items-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6"/>
                            </svg>
                            Send Reply
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-parent-layout>
