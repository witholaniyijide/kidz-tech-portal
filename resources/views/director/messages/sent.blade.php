<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-bold text-2xl text-white">{{ __('Sent Messages') }}</h2>
            <a href="{{ route('director.messages.create') }}" 
               class="inline-flex items-center px-4 py-2 bg-white/20 hover:bg-white/30 text-white font-semibold rounded-lg transition-all">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                </svg>
                Compose
            </a>
        </div>
    </x-slot>
    <x-slot name="title">{{ __('Sent') }}</x-slot>

    <div class="min-h-screen bg-gradient-to-br from-blue-50 via-purple-50 to-pink-50 dark:from-gray-900 dark:via-gray-800 dark:to-gray-900 py-8">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">

            <!-- Tabs -->
            <div class="flex space-x-4 mb-6">
                <a href="{{ route('director.messages.index') }}" 
                   class="px-4 py-2 rounded-lg font-medium {{ request()->routeIs('director.messages.index') ? 'bg-gradient-to-r from-blue-600 to-purple-600 text-white' : 'bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-300' }}">
                    <svg class="w-5 h-5 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                    </svg>
                    Inbox
                </a>
                <a href="{{ route('director.messages.sent') }}" 
                   class="px-4 py-2 rounded-lg font-medium {{ request()->routeIs('director.messages.sent') ? 'bg-gradient-to-r from-blue-600 to-purple-600 text-white' : 'bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-300' }}">
                    <svg class="w-5 h-5 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                    </svg>
                    Sent
                </a>
            </div>

            <!-- Messages List -->
            <x-ui.glass-card>
                <div class="divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse($messages as $message)
                        <a href="{{ route('director.messages.show', $message) }}" 
                           class="block p-4 hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                            <div class="flex items-start justify-between">
                                <div class="flex items-start space-x-3">
                                    <!-- Avatar -->
                                    <div class="w-10 h-10 rounded-full bg-gradient-to-br from-blue-500 to-cyan-600 flex items-center justify-center text-white font-semibold flex-shrink-0">
                                        {{ strtoupper(substr($message->recipient->name, 0, 1)) }}
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <div class="flex items-center space-x-2">
                                            <span class="text-sm text-gray-500 dark:text-gray-400">To:</span>
                                            <span class="font-semibold text-gray-900 dark:text-white">
                                                {{ $message->recipient->name }}
                                            </span>
                                        </div>
                                        <p class="text-sm font-medium text-gray-800 dark:text-gray-200 truncate">
                                            {{ $message->subject }}
                                        </p>
                                        <p class="text-sm text-gray-500 dark:text-gray-400 truncate">
                                            {{ Str::limit($message->body, 80) }}
                                        </p>
                                        @if($message->student)
                                            <span class="inline-flex items-center mt-1 px-2 py-0.5 text-xs bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-200 rounded-full">
                                                Re: {{ $message->student->first_name }} {{ $message->student->last_name }}
                                            </span>
                                        @endif
                                    </div>
                                </div>
                                <div class="text-right flex-shrink-0 ml-4">
                                    <span class="text-xs text-gray-500 dark:text-gray-400">
                                        {{ $message->created_at->diffForHumans() }}
                                    </span>
                                    @if($message->is_read)
                                        <div class="mt-1">
                                            <span class="text-xs text-green-500">
                                                <svg class="w-4 h-4 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                                </svg>
                                                Read
                                            </span>
                                        </div>
                                    @else
                                        <div class="mt-1">
                                            <span class="text-xs text-gray-400">Unread</span>
                                        </div>
                                    @endif
                                    @if($message->replies->count() > 0)
                                        <div class="mt-1">
                                            <span class="text-xs text-gray-400">
                                                {{ $message->replies->count() }} {{ Str::plural('reply', $message->replies->count()) }}
                                            </span>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </a>
                    @empty
                        <div class="p-12 text-center">
                            <svg class="w-12 h-12 mx-auto mb-4 text-gray-300 dark:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                            </svg>
                            <p class="text-lg font-medium text-gray-500 dark:text-gray-400">No sent messages</p>
                            <p class="text-sm text-gray-400 dark:text-gray-500">Messages you send will appear here.</p>
                        </div>
                    @endforelse
                </div>

                <!-- Pagination -->
                @if($messages->hasPages())
                    <div class="p-4 border-t border-gray-200 dark:border-gray-700">
                        {{ $messages->links() }}
                    </div>
                @endif
            </x-ui.glass-card>

        </div>
    </div>
</x-app-layout>
