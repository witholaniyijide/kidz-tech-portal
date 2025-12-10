<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-bold text-2xl text-white truncate max-w-lg">{{ $message->subject }}</h2>
            <a href="{{ route('director.messages.index') }}" 
               class="inline-flex items-center px-4 py-2 bg-white/20 hover:bg-white/30 text-white font-semibold rounded-lg transition-all">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Back
            </a>
        </div>
    </x-slot>
    <x-slot name="title">{{ __('Message') }}</x-slot>

    <div class="min-h-screen bg-gradient-to-br from-blue-50 via-purple-50 to-pink-50 dark:from-gray-900 dark:via-gray-800 dark:to-gray-900 py-8">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <!-- Original Message -->
            <x-ui.glass-card>
                <div class="flex items-start space-x-4">
                    <!-- Avatar -->
                    <div class="w-12 h-12 rounded-full bg-gradient-to-br from-purple-500 to-pink-600 flex items-center justify-center text-white font-bold text-lg flex-shrink-0">
                        {{ strtoupper(substr($message->sender->name, 0, 1)) }}
                    </div>
                    <div class="flex-1">
                        <div class="flex items-center justify-between mb-2">
                            <div>
                                <span class="font-semibold text-gray-900 dark:text-white">{{ $message->sender->name }}</span>
                                <span class="text-sm text-gray-500 dark:text-gray-400 ml-2">
                                    to {{ $message->recipient->name }}
                                </span>
                            </div>
                            <span class="text-sm text-gray-500 dark:text-gray-400">
                                {{ $message->created_at->format('M d, Y h:i A') }}
                            </span>
                        </div>

                        @if($message->student)
                            <span class="inline-flex items-center mb-3 px-2 py-0.5 text-xs bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-200 rounded-full">
                                Regarding: {{ $message->student->first_name }} {{ $message->student->last_name }}
                            </span>
                        @endif

                        <div class="prose prose-sm dark:prose-invert max-w-none">
                            {!! nl2br(e($message->body)) !!}
                        </div>
                    </div>
                </div>
            </x-ui.glass-card>

            <!-- Replies -->
            @if($message->replies->count() > 0)
                <div class="space-y-4">
                    <h3 class="text-sm font-semibold text-gray-500 dark:text-gray-400 px-2">
                        {{ $message->replies->count() }} {{ Str::plural('Reply', $message->replies->count()) }}
                    </h3>

                    @foreach($message->replies->sortBy('created_at') as $reply)
                        <x-ui.glass-card>
                            <div class="flex items-start space-x-4">
                                <div class="w-10 h-10 rounded-full {{ $reply->sender_id === auth()->id() ? 'bg-gradient-to-br from-blue-500 to-cyan-600' : 'bg-gradient-to-br from-purple-500 to-pink-600' }} flex items-center justify-center text-white font-semibold flex-shrink-0">
                                    {{ strtoupper(substr($reply->sender->name, 0, 1)) }}
                                </div>
                                <div class="flex-1">
                                    <div class="flex items-center justify-between mb-2">
                                        <span class="font-semibold text-gray-900 dark:text-white">
                                            {{ $reply->sender->name }}
                                            @if($reply->sender_id === auth()->id())
                                                <span class="text-xs text-gray-400">(You)</span>
                                            @endif
                                        </span>
                                        <span class="text-xs text-gray-500 dark:text-gray-400">
                                            {{ $reply->created_at->diffForHumans() }}
                                        </span>
                                    </div>
                                    <div class="text-sm text-gray-700 dark:text-gray-300">
                                        {!! nl2br(e($reply->body)) !!}
                                    </div>
                                </div>
                            </div>
                        </x-ui.glass-card>
                    @endforeach
                </div>
            @endif

            <!-- Reply Form -->
            <x-ui.glass-card>
                <h3 class="font-semibold text-gray-900 dark:text-white mb-4">Reply</h3>
                <form method="POST" action="{{ route('director.messages.reply', $message) }}">
                    @csrf
                    <div class="mb-4">
                        <textarea name="body" rows="4" required
                                  placeholder="Type your reply..."
                                  class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 focus:ring-blue-500">{{ old('body') }}</textarea>
                        @error('body')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="flex justify-end">
                        <button type="submit" 
                                class="px-4 py-2 bg-gradient-to-r from-blue-600 to-purple-600 text-white font-semibold rounded-lg hover:from-blue-700 hover:to-purple-700 transition-all">
                            <svg class="w-5 h-5 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6"/>
                            </svg>
                            Send Reply
                        </button>
                    </div>
                </form>
            </x-ui.glass-card>

            <!-- Delete Button -->
            <div class="flex justify-end">
                <button 
                    type="button"
                    @click="$dispatch('open-delete-modal', { action: '{{ route('director.messages.destroy', $message) }}', name: '{{ addslashes($message->subject) }}' })"
                    class="text-red-600 hover:text-red-800 text-sm font-medium"
                >
                    <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                    </svg>
                    Delete Conversation
                </button>
            </div>

        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <x-ui.delete-modal 
        title="Delete Message" 
        message="Are you sure you want to delete this conversation? All replies will also be deleted."
    />
</x-app-layout>
