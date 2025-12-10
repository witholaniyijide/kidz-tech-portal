@props(['position' => 'top-right'])

@php
    $positionClasses = [
        'top-right' => 'top-4 right-4',
        'top-left' => 'top-4 left-4',
        'top-center' => 'top-4 left-1/2 -translate-x-1/2',
        'bottom-right' => 'bottom-4 right-4',
        'bottom-left' => 'bottom-4 left-4',
        'bottom-center' => 'bottom-4 left-1/2 -translate-x-1/2',
    ];
    $position = $positionClasses[$position] ?? $positionClasses['top-right'];
@endphp

<div 
    x-data="{ 
        messages: [],
        init() {
            // Add messages from session
            @if(session('success'))
                this.addMessage('success', '{{ session('success') }}');
            @endif
            @if(session('error'))
                this.addMessage('error', '{{ session('error') }}');
            @endif
            @if(session('warning'))
                this.addMessage('warning', '{{ session('warning') }}');
            @endif
            @if(session('info'))
                this.addMessage('info', '{{ session('info') }}');
            @endif
        },
        addMessage(type, text) {
            const id = Date.now();
            this.messages.push({ id, type, text });
            // Auto remove after 5 seconds
            setTimeout(() => this.removeMessage(id), 5000);
        },
        removeMessage(id) {
            this.messages = this.messages.filter(m => m.id !== id);
        },
        getIcon(type) {
            const icons = {
                success: `<svg class='w-5 h-5' fill='none' stroke='currentColor' viewBox='0 0 24 24'><path stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z'/></svg>`,
                error: `<svg class='w-5 h-5' fill='none' stroke='currentColor' viewBox='0 0 24 24'><path stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z'/></svg>`,
                warning: `<svg class='w-5 h-5' fill='none' stroke='currentColor' viewBox='0 0 24 24'><path stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z'/></svg>`,
                info: `<svg class='w-5 h-5' fill='none' stroke='currentColor' viewBox='0 0 24 24'><path stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z'/></svg>`
            };
            return icons[type] || icons.info;
        },
        getClasses(type) {
            const classes = {
                success: 'bg-green-50 dark:bg-green-900/50 border-green-500 text-green-800 dark:text-green-200',
                error: 'bg-red-50 dark:bg-red-900/50 border-red-500 text-red-800 dark:text-red-200',
                warning: 'bg-yellow-50 dark:bg-yellow-900/50 border-yellow-500 text-yellow-800 dark:text-yellow-200',
                info: 'bg-blue-50 dark:bg-blue-900/50 border-blue-500 text-blue-800 dark:text-blue-200'
            };
            return classes[type] || classes.info;
        },
        getIconClasses(type) {
            const classes = {
                success: 'text-green-500',
                error: 'text-red-500',
                warning: 'text-yellow-500',
                info: 'text-blue-500'
            };
            return classes[type] || classes.info;
        }
    }"
    class="fixed {{ $position }} z-50 space-y-3 w-full max-w-sm pointer-events-none"
>
    <template x-for="message in messages" :key="message.id">
        <div 
            x-show="true"
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 transform translate-x-8"
            x-transition:enter-end="opacity-100 transform translate-x-0"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100 transform translate-x-0"
            x-transition:leave-end="opacity-0 transform translate-x-8"
            :class="getClasses(message.type)"
            class="pointer-events-auto flex items-start p-4 rounded-lg border-l-4 shadow-lg backdrop-blur-sm"
        >
            <!-- Icon -->
            <div :class="getIconClasses(message.type)" class="flex-shrink-0" x-html="getIcon(message.type)"></div>
            
            <!-- Message -->
            <div class="ml-3 flex-1">
                <p class="text-sm font-medium" x-text="message.text"></p>
            </div>
            
            <!-- Close Button -->
            <button 
                @click="removeMessage(message.id)" 
                class="ml-4 flex-shrink-0 inline-flex text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 focus:outline-none transition-colors"
            >
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
    </template>
</div>
