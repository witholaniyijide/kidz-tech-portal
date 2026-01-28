<x-app-layout>
    <x-slot name="header">
        {{ $message->subject }}
    </x-slot>
    <x-slot name="title">{{ __('Message') }}</x-slot>

    <div class="min-h-screen bg-gradient-to-br from-indigo-50 via-purple-50 to-pink-50 dark:from-gray-900 dark:via-gray-800 dark:to-gray-900 py-8">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            <!-- Action Button -->
            <div class="flex justify-between items-center">
                <a href="{{ route('director.messages.index') }}"
                   class="inline-flex items-center px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white font-semibold rounded-lg transition-all">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    Back
                </a>
                <button
                    type="button"
                    @click="$dispatch('open-delete-modal', { action: '{{ route('director.messages.destroy', $message) }}', name: '{{ addslashes($message->subject) }}' })"
                    class="text-red-600 hover:text-red-800 text-sm font-medium inline-flex items-center"
                >
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                    </svg>
                    Delete
                </button>
            </div>

            <!-- WhatsApp Style Message Thread -->
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl overflow-hidden flex flex-col" style="min-height: 70vh;">
                <!-- Header with sender info -->
                <div class="p-4 border-b border-gray-200 dark:border-gray-700 bg-gradient-to-r from-[#4F46E5] to-[#818CF8]">
                    <div class="flex items-center">
                        @php
                            $otherParty = $message->sender_id === auth()->id() ? $message->recipient : $message->sender;
                        @endphp
                        <div class="w-12 h-12 rounded-full bg-white/20 flex items-center justify-center text-white font-bold text-lg mr-3">
                            {{ strtoupper(substr($otherParty->name ?? 'U', 0, 1)) }}
                        </div>
                        <div class="flex-1">
                            <h1 class="text-lg font-bold text-white">{{ $otherParty->name ?? 'Unknown' }}</h1>
                            <p class="text-sm text-white/80">{{ $message->subject }}</p>
                        </div>
                        @if($message->student)
                            <span class="px-2 py-1 text-xs bg-white/20 text-white rounded-full">
                                Re: {{ $message->student->first_name }} {{ $message->student->last_name }}
                            </span>
                        @endif
                    </div>
                </div>

                <!-- Chat Messages Container -->
                <div class="flex-1 overflow-y-auto p-4 space-y-4 bg-[#e5ddd5] dark:bg-gray-800" style="background-image: url('data:image/svg+xml,%3Csvg width=&quot;60&quot; height=&quot;60&quot; viewBox=&quot;0 0 60 60&quot; xmlns=&quot;http://www.w3.org/2000/svg&quot;%3E%3Cg fill=&quot;none&quot; fill-rule=&quot;evenodd&quot;%3E%3Cg fill=&quot;%239C92AC&quot; fill-opacity=&quot;0.05&quot;%3E%3Cpath d=&quot;M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z&quot;/%3E%3C/g%3E%3C/g%3E%3C/svg%3E');">

                    <!-- Original Message -->
                    @php
                        $isOwnMessage = $message->sender_id === auth()->id();
                    @endphp
                    <div class="flex {{ $isOwnMessage ? 'justify-end' : 'justify-start' }}">
                        <div class="max-w-[75%]">
                            <div class="relative {{ $isOwnMessage ? 'bg-[#dcf8c6] dark:bg-emerald-800' : 'bg-white dark:bg-gray-700' }} rounded-lg px-4 py-2 shadow-sm">
                                <!-- Bubble Tail -->
                                @if($isOwnMessage)
                                    <div class="absolute -right-2 top-0 w-4 h-4 overflow-hidden">
                                        <div class="absolute bg-[#dcf8c6] dark:bg-emerald-800 w-4 h-4 transform rotate-45 -translate-x-2"></div>
                                    </div>
                                @else
                                    <div class="absolute -left-2 top-0 w-4 h-4 overflow-hidden">
                                        <div class="absolute bg-white dark:bg-gray-700 w-4 h-4 transform rotate-45 translate-x-2"></div>
                                    </div>
                                @endif

                                @if(!$isOwnMessage)
                                    <p class="text-xs font-semibold text-purple-600 dark:text-purple-400 mb-1">
                                        {{ $message->sender->name ?? 'Unknown' }}
                                    </p>
                                @endif

                                <p class="text-gray-800 dark:text-gray-200 whitespace-pre-wrap break-words">{{ $message->body }}</p>

                                <div class="flex items-center justify-end mt-1 space-x-1">
                                    <span class="text-[10px] text-gray-500 dark:text-gray-400">
                                        {{ $message->created_at->format('g:i A') }}
                                    </span>
                                    @if($isOwnMessage)
                                        @if($message->read_at)
                                            <svg class="w-4 h-4 text-blue-500" viewBox="0 0 24 24" fill="currentColor">
                                                <path d="M18 7l-1.41-1.41-6.34 6.34 1.41 1.41L18 7zm4.24-1.41L11.66 16.17 7.48 12l-1.41 1.41L11.66 19l12-12-1.42-1.41zM.41 13.41L6 19l1.41-1.41L1.83 12 .41 13.41z"/>
                                            </svg>
                                        @else
                                            <svg class="w-4 h-4 text-gray-400" viewBox="0 0 24 24" fill="currentColor">
                                                <path d="M18 7l-1.41-1.41-6.34 6.34 1.41 1.41L18 7zm4.24-1.41L11.66 16.17 7.48 12l-1.41 1.41L11.66 19l12-12-1.42-1.41zM.41 13.41L6 19l1.41-1.41L1.83 12 .41 13.41z"/>
                                            </svg>
                                        @endif
                                    @endif
                                </div>
                            </div>
                            <p class="text-center text-xs text-gray-500 dark:text-gray-400 mt-1">
                                {{ $message->created_at->format('M d, Y') }}
                            </p>
                        </div>
                    </div>

                    <!-- Replies -->
                    @if($message->replies->count() > 0)
                        @foreach($message->replies->sortBy('created_at') as $reply)
                            @php
                                $isOwnReply = $reply->sender_id === auth()->id();
                            @endphp
                            <div class="flex {{ $isOwnReply ? 'justify-end' : 'justify-start' }}">
                                <div class="max-w-[75%]">
                                    <div class="relative {{ $isOwnReply ? 'bg-[#dcf8c6] dark:bg-emerald-800' : 'bg-white dark:bg-gray-700' }} rounded-lg px-4 py-2 shadow-sm">
                                        @if($isOwnReply)
                                            <div class="absolute -right-2 top-0 w-4 h-4 overflow-hidden">
                                                <div class="absolute bg-[#dcf8c6] dark:bg-emerald-800 w-4 h-4 transform rotate-45 -translate-x-2"></div>
                                            </div>
                                        @else
                                            <div class="absolute -left-2 top-0 w-4 h-4 overflow-hidden">
                                                <div class="absolute bg-white dark:bg-gray-700 w-4 h-4 transform rotate-45 translate-x-2"></div>
                                            </div>
                                        @endif

                                        @if(!$isOwnReply)
                                            <p class="text-xs font-semibold text-purple-600 dark:text-purple-400 mb-1">
                                                {{ $reply->sender->name ?? 'Unknown' }}
                                            </p>
                                        @endif

                                        <p class="text-gray-800 dark:text-gray-200 whitespace-pre-wrap break-words">{{ $reply->body }}</p>

                                        <div class="flex items-center justify-end mt-1 space-x-1">
                                            <span class="text-[10px] text-gray-500 dark:text-gray-400">
                                                {{ $reply->created_at->format('g:i A') }}
                                            </span>
                                            @if($isOwnReply)
                                                @if($reply->read_at)
                                                    <svg class="w-4 h-4 text-blue-500" viewBox="0 0 24 24" fill="currentColor">
                                                        <path d="M18 7l-1.41-1.41-6.34 6.34 1.41 1.41L18 7zm4.24-1.41L11.66 16.17 7.48 12l-1.41 1.41L11.66 19l12-12-1.42-1.41zM.41 13.41L6 19l1.41-1.41L1.83 12 .41 13.41z"/>
                                                    </svg>
                                                @else
                                                    <svg class="w-4 h-4 text-gray-400" viewBox="0 0 24 24" fill="currentColor">
                                                        <path d="M18 7l-1.41-1.41-6.34 6.34 1.41 1.41L18 7zm4.24-1.41L11.66 16.17 7.48 12l-1.41 1.41L11.66 19l12-12-1.42-1.41zM.41 13.41L6 19l1.41-1.41L1.83 12 .41 13.41z"/>
                                                    </svg>
                                                @endif
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @endif
                </div>

                <!-- Reply Form - Fixed at bottom -->
                <div class="p-3 border-t border-gray-200 dark:border-gray-700 bg-gray-100 dark:bg-gray-800">
                    <form method="POST" action="{{ route('director.messages.reply', $message) }}" class="flex items-end space-x-3">
                        @csrf
                        <div class="flex-1">
                            <textarea name="body" rows="1" required
                                      class="w-full rounded-2xl border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-[#4F46E5] focus:border-[#4F46E5] resize-none py-3 px-4"
                                      placeholder="Type a message..."
                                      style="min-height: 44px; max-height: 120px;"
                                      oninput="this.style.height = ''; this.style.height = Math.min(this.scrollHeight, 120) + 'px'">{{ old('body') }}</textarea>
                            @error('body')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <button type="submit" class="flex-shrink-0 w-12 h-12 rounded-full bg-gradient-to-r from-[#4F46E5] to-[#818CF8] text-white flex items-center justify-center hover:shadow-lg transition-all">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                            </svg>
                        </button>
                    </form>
                </div>
            </div>

        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <x-ui.delete-modal
        title="Delete Message"
        message="Are you sure you want to delete this conversation? All replies will also be deleted."
    />
</x-app-layout>
