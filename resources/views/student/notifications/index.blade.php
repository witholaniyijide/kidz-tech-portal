@extends('layouts.student')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl md:text-3xl font-bold text-gray-900 dark:text-white">Notifications</h1>
            <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Stay updated with your learning progress</p>
        </div>
        @if($notifications->count() > 0)
            <form method="POST" action="{{ route('student.notifications.mark-all-read') }}">
                @csrf
                <button type="submit" class="px-4 py-2 text-sm font-medium text-sky-600 dark:text-sky-400 hover:text-sky-700 dark:hover:text-sky-300 transition-colors">
                    Mark all as read
                </button>
            </form>
        @endif
    </div>

    <!-- Notifications List -->
    @if($notifications->count() > 0)
        <div class="space-y-3">
            @foreach($notifications as $notification)
                <x-ui.glass-card padding="p-0">
                    <div class="flex items-start p-5 {{ $notification->read_at ? '' : 'bg-sky-50/50 dark:bg-sky-900/10' }}">
                        <!-- Notification Icon -->
                        <div class="flex-shrink-0">
                            @php
                                $type = $notification->data['type'] ?? 'default';
                                $iconColor = match($type) {
                                    'report_ready' => 'bg-gradient-to-br from-green-500 to-emerald-500',
                                    'milestone_completed' => 'bg-gradient-to-br from-purple-500 to-pink-500',
                                    'important' => 'bg-gradient-to-br from-orange-500 to-red-500',
                                    default => 'bg-gradient-to-br from-sky-500 to-cyan-400'
                                };
                            @endphp
                            <div class="w-12 h-12 rounded-xl {{ $iconColor }} flex items-center justify-center text-white">
                                @if($type === 'report_ready')
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                    </svg>
                                @elseif($type === 'milestone_completed')
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/>
                                    </svg>
                                @elseif($type === 'important')
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                                    </svg>
                                @else
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                                    </svg>
                                @endif
                            </div>
                        </div>

                        <!-- Notification Content -->
                        <div class="ml-4 flex-1 min-w-0">
                            <div class="flex items-start justify-between">
                                <div class="flex-1">
                                    <h3 class="text-base font-semibold text-gray-900 dark:text-white">
                                        {{ $notification->data['title'] ?? 'Notification' }}
                                        @if(!$notification->read_at)
                                            <span class="ml-2 inline-block w-2 h-2 rounded-full bg-sky-500"></span>
                                        @endif
                                    </h3>
                                    <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                                        {{ $notification->data['message'] ?? '' }}
                                    </p>
                                    <p class="text-xs text-gray-500 dark:text-gray-500 mt-2">
                                        {{ $notification->created_at->diffForHumans() }}
                                    </p>
                                </div>

                                <!-- Action Buttons -->
                                <div class="flex items-center space-x-2 ml-4">
                                    @if(isset($notification->data['action_url']))
                                        <a href="{{ $notification->data['action_url'] }}" class="px-3 py-1 text-xs font-medium text-sky-600 dark:text-sky-400 hover:text-sky-700 dark:hover:text-sky-300 border border-sky-200 dark:border-sky-800 rounded-lg hover:bg-sky-50 dark:hover:bg-sky-900/20 transition-colors">
                                            View
                                        </a>
                                    @endif

                                    @if(!$notification->read_at)
                                        <form method="POST" action="{{ route('student.notifications.mark-read', $notification->id) }}" class="inline">
                                            @csrf
                                            <button type="submit" class="p-2 text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 transition-colors" title="Mark as read">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                                </svg>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </x-ui.glass-card>
            @endforeach
        </div>

        <!-- Pagination -->
        @if($notifications->hasPages())
            <div class="mt-6">
                {{ $notifications->links() }}
            </div>
        @endif
    @else
        <x-ui.glass-card>
            <x-ui.empty-state
                title="No notifications"
                description="You're all caught up! New notifications will appear here"
                icon="bell" />
        </x-ui.glass-card>
    @endif
</div>
@endsection
