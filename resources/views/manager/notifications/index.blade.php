<x-manager-layout title="Notifications">
    <div class="p-6">
        <div class="max-w-4xl mx-auto">
            <!-- Header -->
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Notifications</h1>
                    <p class="text-gray-500 dark:text-gray-400 mt-1">Stay updated on assessments and reports</p>
                </div>
            <div class="flex items-center space-x-4">
                <span class="px-3 py-1 bg-orange-100 dark:bg-orange-900/30 text-orange-700 dark:text-orange-400 rounded-full text-sm font-medium">
                    {{ $unreadCount }} Unread
                </span>
                @if($unreadCount > 0)
                    <form action="{{ route('manager.notifications.mark-all-read') }}" method="POST">
                        @csrf
                        <button type="submit" class="flex items-center space-x-2 px-4 py-2 text-sm font-medium text-gray-600 dark:text-gray-400 hover:text-orange-600 dark:hover:text-orange-400 transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            <span>Mark all as read</span>
                        </button>
                    </form>
                @endif
            </div>
        </div>

        <!-- Notifications List -->
        @if($notifications->count() > 0)
            <div class="space-y-4">
                @foreach($notifications as $notification)
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-4 {{ !$notification->is_read ? 'border-l-4 border-l-orange-500' : '' }}">
                        <div class="flex items-start justify-between">
                            <div class="flex items-start space-x-4">
                                <!-- Icon -->
                                <div class="w-10 h-10 rounded-full flex items-center justify-center flex-shrink-0
                                    @switch($notification->type)
                                        @case('report_approved')
                                            bg-emerald-100 dark:bg-emerald-900/30 text-emerald-600 dark:text-emerald-400
                                            @break
                                        @case('report_rejected')
                                            bg-red-100 dark:bg-red-900/30 text-red-600 dark:text-red-400
                                            @break
                                        @case('assessment_approved')
                                            bg-blue-100 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400
                                            @break
                                        @case('notice')
                                            bg-purple-100 dark:bg-purple-900/30 text-purple-600 dark:text-purple-400
                                            @break
                                        @default
                                            bg-orange-100 dark:bg-orange-900/30 text-orange-600 dark:text-orange-400
                                    @endswitch">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                                    </svg>
                                </div>

                                <!-- Content -->
                                <div class="flex-1 min-w-0">
                                    <h4 class="font-medium text-gray-900 dark:text-white {{ !$notification->is_read ? 'font-semibold' : '' }}">
                                        {{ $notification->title }}
                                    </h4>
                                    @if($notification->body)
                                        <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                                            {{ $notification->body }}
                                        </p>
                                    @endif
                                    <div class="flex items-center space-x-4 mt-3">
                                        @if(isset($notification->meta['link']))
                                            <a href="{{ $notification->meta['link'] }}" class="text-sm text-orange-600 dark:text-orange-400 hover:underline">
                                                View Details
                                            </a>
                                        @endif
                                        @if(!$notification->is_read)
                                            <form action="{{ route('manager.notifications.mark-read', $notification) }}" method="POST" class="inline">
                                                @csrf
                                                <button type="submit" class="text-sm text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300">
                                                    Mark as read
                                                </button>
                                            </form>
                                        @endif
                                        <form action="{{ route('manager.notifications.destroy', $notification) }}" method="POST" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-sm text-red-500 hover:text-red-700" onclick="return confirm('Delete this notification?')">
                                                Delete
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>

                            <div class="flex items-center space-x-2">
                                <span class="text-xs text-gray-400 whitespace-nowrap">
                                    {{ $notification->created_at->diffForHumans() }}
                                </span>
                                @if(!$notification->is_read)
                                    <div class="w-2 h-2 bg-orange-500 rounded-full"></div>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="mt-6">
                {{ $notifications->links() }}
            </div>
        @else
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm p-12 text-center">
                <svg class="w-16 h-16 mx-auto text-gray-300 dark:text-gray-600 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                </svg>
                <h3 class="text-xl font-bold text-gray-800 dark:text-white mb-2">No Notifications</h3>
                <p class="text-gray-500 dark:text-gray-400">
                    You'll receive notifications about assessments and reports here.
                </p>
            </div>
        @endif
        </div>
    </div>
</x-manager-layout>
