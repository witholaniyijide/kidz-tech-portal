<x-tutor-layout title="Notifications">
    <div class="max-w-4xl mx-auto">
        {{-- Header --}}
        <div class="flex justify-between items-center mb-6">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Notifications</h1>
                <p class="text-gray-600 dark:text-gray-400">
                    @if($unreadCount > 0)
                        You have {{ $unreadCount }} unread notification{{ $unreadCount > 1 ? 's' : '' }}
                    @else
                        All caught up!
                    @endif
                </p>
            </div>
            @if($unreadCount > 0)
                <form action="{{ route('tutor.notifications.mark-all-read') }}" method="POST">
                    @csrf
                    <button type="submit" class="px-4 py-2 bg-gradient-to-r from-[#4B49AC] to-[#7978E9] text-white rounded-xl hover:shadow-lg transition-all font-medium text-sm">
                        Mark All as Read
                    </button>
                </form>
            @endif
        </div>

        {{-- Notifications List --}}
        <div class="space-y-4">
            @forelse($notifications as $notification)
                <div class="glass-card rounded-xl p-4 {{ !$notification->is_read ? 'border-l-4 border-[#4B49AC]' : '' }}">
                    <div class="flex items-start justify-between">
                        <div class="flex-1">
                            <div class="flex items-center gap-2 mb-1">
                                @if(!$notification->is_read)
                                    <span class="w-2 h-2 bg-[#4B49AC] rounded-full"></span>
                                @endif
                                <h3 class="font-semibold text-gray-900 dark:text-white">
                                    {{ $notification->title }}
                                </h3>
                                <span class="text-xs text-gray-500 dark:text-gray-400">
                                    {{ $notification->created_at->diffForHumans() }}
                                </span>
                            </div>
                            <p class="text-gray-600 dark:text-gray-400 text-sm">
                                {{ $notification->body }}
                            </p>
                            @if($notification->meta && isset($notification->meta['link']))
                                <a href="{{ $notification->meta['link'] }}" class="inline-flex items-center mt-2 text-sm text-[#4B49AC] hover:underline">
                                    View Details
                                    <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                    </svg>
                                </a>
                            @endif
                        </div>
                        <div class="flex items-center gap-2 ml-4">
                            @if(!$notification->is_read)
                                <button onclick="markAsRead({{ $notification->id }})" class="p-2 text-gray-400 hover:text-[#4B49AC] transition-colors" title="Mark as read">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                    </svg>
                                </button>
                            @endif
                            <form action="{{ route('tutor.notifications.destroy', $notification) }}" method="POST" onsubmit="return confirm('Delete this notification?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="p-2 text-gray-400 hover:text-red-500 transition-colors" title="Delete">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                    </svg>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @empty
                <div class="glass-card rounded-xl p-12 text-center">
                    <svg class="w-16 h-16 mx-auto text-gray-400 dark:text-gray-600 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                    </svg>
                    <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-2">No Notifications</h3>
                    <p class="text-gray-600 dark:text-gray-400">You're all caught up! Check back later for updates.</p>
                </div>
            @endforelse
        </div>

        {{-- Pagination --}}
        @if($notifications->hasPages())
            <div class="mt-6">
                {{ $notifications->links() }}
            </div>
        @endif
    </div>

    @push('scripts')
    <script>
        function markAsRead(notificationId) {
            fetch(`/tutor/notifications/${notificationId}/mark-read`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            }).then(() => {
                window.location.reload();
            });
        }
    </script>
    @endpush
</x-tutor-layout>
