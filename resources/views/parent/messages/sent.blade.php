<x-parent-layout title="Sent Messages">
    <div class="space-y-6">
        <!-- Page Header -->
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Messages</h1>
                <p class="text-gray-600 dark:text-gray-400">Communicate with the Director</p>
            </div>
            <a href="{{ route('parent.messages.create') }}"
               class="btn-parent-primary inline-flex items-center px-4 py-2.5 rounded-xl font-medium">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Compose Message
            </a>
        </div>

        <!-- Tabs -->
        <div class="glass-card rounded-2xl p-1 inline-flex">
            <a href="{{ route('parent.messages.index') }}"
               class="px-4 py-2 rounded-xl text-sm font-medium transition-colors {{ request()->routeIs('parent.messages.index') ? 'bg-gradient-parent text-white' : 'text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white' }}">
                Inbox
            </a>
            <a href="{{ route('parent.messages.sent') }}"
               class="px-4 py-2 rounded-xl text-sm font-medium transition-colors {{ request()->routeIs('parent.messages.sent') ? 'bg-gradient-parent text-white' : 'text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white' }}">
                Sent
            </a>
        </div>

        <!-- Messages List -->
        <div class="glass-card rounded-2xl overflow-hidden">
            @if($messages->count() > 0)
                <div class="divide-y divide-gray-200 dark:divide-gray-700">
                    @foreach($messages as $message)
                        <a href="{{ route('parent.messages.show', $message) }}"
                           class="block p-4 hover:bg-gray-50 dark:hover:bg-gray-800/50 transition-colors">
                            <div class="flex items-start justify-between">
                                <div class="flex items-start space-x-4">
                                    <div class="w-10 h-10 rounded-full bg-gradient-parent flex items-center justify-center text-white font-semibold flex-shrink-0">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18"/>
                                        </svg>
                                    </div>
                                    <div class="min-w-0">
                                        <p class="font-medium text-gray-600 dark:text-gray-400">
                                            To: {{ $message->recipient->name ?? 'Director' }}
                                        </p>
                                        <p class="text-sm font-medium text-gray-800 dark:text-gray-200 mt-0.5">
                                            {{ $message->subject }}
                                        </p>
                                        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1 line-clamp-1">
                                            {{ Str::limit($message->body, 100) }}
                                        </p>
                                    </div>
                                </div>
                                <div class="text-right flex-shrink-0 ml-4">
                                    <p class="text-xs text-gray-500 dark:text-gray-400">
                                        {{ $message->created_at->diffForHumans() }}
                                    </p>
                                    @if($message->read_at)
                                        <p class="text-xs text-green-600 dark:text-green-400 mt-1">Read</p>
                                    @endif
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>

                <!-- Pagination -->
                <div class="p-4 border-t border-gray-200 dark:border-gray-700">
                    {{ $messages->links() }}
                </div>
            @else
                <div class="p-12 text-center">
                    <svg class="w-16 h-16 mx-auto text-gray-300 dark:text-gray-600 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                    </svg>
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-1">No sent messages</h3>
                    <p class="text-gray-500 dark:text-gray-400 mb-4">You haven't sent any messages yet.</p>
                    <a href="{{ route('parent.messages.create') }}"
                       class="btn-parent-primary inline-flex items-center px-4 py-2 rounded-xl font-medium">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                        </svg>
                        Send a Message
                    </a>
                </div>
            @endif
        </div>
    </div>
</x-parent-layout>
