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

        <!-- Message Thread - WhatsApp Style -->
        <div class="glass-card rounded-2xl overflow-hidden flex flex-col" style="min-height: 70vh;">
            <!-- Header with recipient info -->
            <div class="p-4 border-b border-gray-200 dark:border-gray-700 bg-gradient-to-r from-sky-500 to-cyan-400">
                <div class="flex items-center">
                    @php
                        $otherParty = $message->sender_id === auth()->id() ? $message->recipient : $message->sender;
                    @endphp
                    <div class="w-10 h-10 rounded-full bg-white/20 flex items-center justify-center text-white font-bold mr-3">
                        {{ strtoupper(substr($otherParty->name ?? 'U', 0, 1)) }}
                    </div>
                    <div>
                        <h1 class="text-lg font-bold text-white">{{ $otherParty->name ?? 'Unknown' }}</h1>
                        <p class="text-sm text-white/80">{{ $message->subject }}</p>
                    </div>
                </div>
            </div>

            <!-- Chat Messages Container -->
            <div class="flex-1 overflow-y-auto p-4 space-y-4 bg-[#e5ddd5] dark:bg-gray-800" style="background-image: url('data:image/svg+xml,%3Csvg width=&quot;60&quot; height=&quot;60&quot; viewBox=&quot;0 0 60 60&quot; xmlns=&quot;http://www.w3.org/2000/svg&quot;%3E%3Cg fill=&quot;none&quot; fill-rule=&quot;evenodd&quot;%3E%3Cg fill=&quot;%239C92AC&quot; fill-opacity=&quot;0.05&quot;%3E%3Cpath d=&quot;M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z&quot;/%3E%3C/g%3E%3C/g%3E%3C/svg%3E');">
                @foreach($thread as $msg)
                    @php
                        $isOwnMessage = $msg->sender_id === auth()->id();
                    @endphp
                    <div class="flex {{ $isOwnMessage ? 'justify-end' : 'justify-start' }}">
                        <div class="max-w-[75%] {{ $isOwnMessage ? 'order-2' : '' }}">
                            <!-- Chat Bubble -->
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

                                <!-- Sender Name (for received messages) -->
                                @if(!$isOwnMessage)
                                    <p class="text-xs font-semibold text-sky-600 dark:text-sky-400 mb-1">
                                        {{ $msg->sender->name ?? 'Unknown' }}
                                    </p>
                                @endif

                                <!-- Message Content -->
                                <p class="text-gray-800 dark:text-gray-200 whitespace-pre-wrap break-words">{{ $msg->body }}</p>

                                <!-- Timestamp & Read Status -->
                                <div class="flex items-center justify-end mt-1 space-x-1">
                                    <span class="text-[10px] text-gray-500 dark:text-gray-400">
                                        {{ $msg->created_at->format('g:i A') }}
                                    </span>
                                    @if($isOwnMessage)
                                        @if($msg->read_at)
                                            <!-- Double blue tick (read) -->
                                            <svg class="w-4 h-4 text-blue-500" viewBox="0 0 24 24" fill="currentColor">
                                                <path d="M18 7l-1.41-1.41-6.34 6.34 1.41 1.41L18 7zm4.24-1.41L11.66 16.17 7.48 12l-1.41 1.41L11.66 19l12-12-1.42-1.41zM.41 13.41L6 19l1.41-1.41L1.83 12 .41 13.41z"/>
                                            </svg>
                                        @else
                                            <!-- Double gray tick (delivered) -->
                                            <svg class="w-4 h-4 text-gray-400" viewBox="0 0 24 24" fill="currentColor">
                                                <path d="M18 7l-1.41-1.41-6.34 6.34 1.41 1.41L18 7zm4.24-1.41L11.66 16.17 7.48 12l-1.41 1.41L11.66 19l12-12-1.42-1.41zM.41 13.41L6 19l1.41-1.41L1.83 12 .41 13.41z"/>
                                            </svg>
                                        @endif
                                    @endif
                                </div>
                            </div>

                            <!-- Date label (for first message or date changes) -->
                            @if($loop->first)
                                <p class="text-center text-xs text-gray-500 dark:text-gray-400 mt-1">
                                    {{ $msg->created_at->format('M d, Y') }}
                                </p>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Reply Form - Fixed at bottom -->
            <div class="p-3 border-t border-gray-200 dark:border-gray-700 bg-gray-100 dark:bg-gray-800">
                <form method="POST" action="{{ route('parent.messages.reply', $message) }}" class="flex items-end space-x-3">
                    @csrf
                    <div class="flex-1">
                        <textarea id="body" name="body" rows="1" required
                                  class="w-full rounded-2xl border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-sky-500 focus:border-sky-500 resize-none py-3 px-4"
                                  placeholder="Type a message..."
                                  style="min-height: 44px; max-height: 120px;"
                                  oninput="this.style.height = ''; this.style.height = Math.min(this.scrollHeight, 120) + 'px'"></textarea>
                        @error('body')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>
                    <button type="submit" class="flex-shrink-0 w-12 h-12 rounded-full bg-gradient-to-r from-sky-500 to-cyan-400 text-white flex items-center justify-center hover:shadow-lg transition-all">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                        </svg>
                    </button>
                </form>
            </div>
        </div>
    </div>
</x-parent-layout>
