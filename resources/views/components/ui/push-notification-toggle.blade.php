{{-- 
    Push Notification Toggle Component
    Include this in settings pages or navigation dropdown
    
    Usage: <x-ui.push-notification-toggle />
--}}

<div 
    x-data="{
        enabled: false,
        loading: false,
        supported: true,
        status: 'default',
        
        async init() {
            // Check if browser supports notifications
            if (!('Notification' in window) || !('serviceWorker' in navigator)) {
                this.supported = false;
                return;
            }
            
            this.status = Notification.permission;
            this.enabled = this.status === 'granted';
            
            // Initialize push service
            if (window.KidzTechPush) {
                await KidzTechPush.init();
            }
        },
        
        async toggle() {
            if (!this.supported) return;
            
            this.loading = true;
            
            try {
                if (this.enabled) {
                    // Disable notifications
                    if (window.KidzTechPush) {
                        await KidzTechPush.unsubscribe();
                    }
                    this.enabled = false;
                } else {
                    // Enable notifications
                    if (window.KidzTechPush) {
                        const result = await KidzTechPush.requestPermission();
                        this.enabled = result.success;
                        
                        if (!result.success && result.error === 'Permission denied') {
                            this.status = 'denied';
                            alert('Notifications were blocked. Please enable them in your browser settings.');
                        }
                    }
                }
            } catch (error) {
                console.error('Toggle error:', error);
            }
            
            this.loading = false;
        }
    }"
    class="flex items-center justify-between p-4 bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700"
>
    <div class="flex items-center space-x-3">
        <div class="p-2 rounded-full" :class="enabled ? 'bg-green-100 dark:bg-green-900/30' : 'bg-gray-100 dark:bg-gray-700'">
            <svg class="w-5 h-5" :class="enabled ? 'text-green-600' : 'text-gray-400'" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
            </svg>
        </div>
        <div>
            <p class="font-medium text-gray-900 dark:text-white">Push Notifications</p>
            <p class="text-sm text-gray-500 dark:text-gray-400" x-show="supported">
                <span x-show="enabled">Enabled - You'll receive browser notifications</span>
                <span x-show="!enabled && status !== 'denied'">Click to enable browser notifications</span>
                <span x-show="status === 'denied'" class="text-red-500">Blocked - Enable in browser settings</span>
            </p>
            <p class="text-sm text-red-500" x-show="!supported">
                Your browser doesn't support push notifications
            </p>
        </div>
    </div>
    
    <button 
        @click="toggle()"
        :disabled="loading || !supported || status === 'denied'"
        class="relative inline-flex h-6 w-11 items-center rounded-full transition-colors focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 disabled:opacity-50 disabled:cursor-not-allowed"
        :class="enabled ? 'bg-blue-600' : 'bg-gray-200 dark:bg-gray-600'"
    >
        <span 
            class="inline-block h-4 w-4 transform rounded-full bg-white transition-transform"
            :class="enabled ? 'translate-x-6' : 'translate-x-1'"
        ></span>
        <span x-show="loading" class="absolute inset-0 flex items-center justify-center">
            <svg class="animate-spin h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
        </span>
    </button>
</div>

{{-- Include the push notification script --}}
@once
    @push('scripts')
        <script src="{{ asset('js/push-notifications.js') }}"></script>
    @endpush
@endonce
