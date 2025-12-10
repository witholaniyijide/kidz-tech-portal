<x-parent-layout>
    <x-slot name="title">Notifications</x-slot>
    <x-slot name="subtitle">Stay updated on your children's progress</x-slot>

    <div class="space-y-6">
        <!-- Header Actions -->
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-2">
                <span class="px-3 py-1 bg-sky-100 dark:bg-sky-900/30 text-sky-700 dark:text-sky-400 rounded-full text-sm font-medium">
                    {{ $unreadCount }} Unread
                </span>
            </div>
            @if($unreadCount > 0)
                <form action="{{ route('parent.notifications.mark-all-read') }}" method="POST">
                    @csrf
                    <button type="submit"
                            class="flex items-center space-x-2 px-4 py-2 text-sm font-medium text-gray-600 dark:text-gray-400 hover:text-sky-600 dark:hover:text-sky-400 transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        <span>Mark all as read</span>
                    </button>
                </form>
            @endif
        </div>

        <!-- Notifications List -->
        @if($notifications->count() > 0)
            <div class="space-y-4">
                @foreach($notifications as $notification)
                    <div class="glass-card rounded-xl p-4 {{ $notification->isUnread() ? 'border-l-4 border-sky-500' : '' }}">
                        <div class="flex items-start space-x-4">
                            <!-- Icon -->
                            <div class="w-10 h-10 rounded-full flex items-center justify-center flex-shrink-0
                                        @switch($notification->type)
                                            @case('report_approved')
                                                bg-emerald-100 dark:bg-emerald-900/30 text-emerald-600 dark:text-emerald-400
                                                @break
                                            @case('certificate_issued')
                                                bg-amber-100 dark:bg-amber-900/30 text-amber-600 dark:text-amber-400
                                                @break
                                            @case('milestone_achieved')
                                                bg-purple-100 dark:bg-purple-900/30 text-purple-600 dark:text-purple-400
                                                @break
                                            @case('progress_update')
                                                bg-sky-100 dark:bg-sky-900/30 text-sky-600 dark:text-sky-400
                                                @break
                                            @default
                                                bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-400
                                        @endswitch">
                                @switch($notification->type)
                                    @case('report_approved')
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                        </svg>
                                        @break
                                    @case('certificate_issued')
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/>
                                        </svg>
                                        @break
                                    @case('milestone_achieved')
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                                        </svg>
                                        @break
                                    @default
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                                        </svg>
                                @endswitch
                            </div>

                            <!-- Content -->
                            <div class="flex-1 min-w-0">
                                <div class="flex items-start justify-between">
                                    <div>
                                        <h4 class="font-medium text-gray-800 dark:text-white {{ $notification->isUnread() ? 'font-semibold' : '' }}">
                                            {{ $notification->title ?? ucfirst(str_replace('_', ' ', $notification->type)) }}
                                        </h4>
                                        @if($notification->student)
                                            <p class="text-xs text-sky-600 dark:text-sky-400 mt-0.5">
                                                {{ $notification->student->first_name }}
                                            </p>
                                        @endif
                                    </div>
                                    <span class="text-xs text-gray-400 whitespace-nowrap ml-2">
                                        {{ $notification->created_at->diffForHumans() }}
                                    </span>
                                </div>
                                @if($notification->message)
                                    <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                                        {{ $notification->message }}
                                    </p>
                                @endif

                                <div class="flex items-center space-x-4 mt-3">
                                    @if($notification->link)
                                        <a href="{{ $notification->link }}"
                                           class="text-sm text-sky-600 dark:text-sky-400 hover:underline">
                                            View Details
                                        </a>
                                    @endif
                                    @if($notification->isUnread())
                                        <form action="{{ route('parent.notifications.mark-read', $notification) }}" method="POST" class="inline">
                                            @csrf
                                            <button type="submit" class="text-sm text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300">
                                                Mark as read
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </div>

                            <!-- Unread Indicator -->
                            @if($notification->isUnread())
                                <div class="w-2 h-2 bg-sky-500 rounded-full flex-shrink-0"></div>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="mt-6">
                {{ $notifications->links() }}
            </div>
        @else
            <div class="glass-card rounded-2xl p-12 text-center">
                <svg class="w-16 h-16 mx-auto text-gray-300 dark:text-gray-600 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                </svg>
                <h3 class="text-xl font-heading font-bold text-gray-800 dark:text-white mb-2">No Notifications</h3>
                <p class="text-gray-500 dark:text-gray-400">
                    You'll receive notifications about your children's progress here.
                </p>
            </div>
        @endif
    </div>
</x-parent-layout>
