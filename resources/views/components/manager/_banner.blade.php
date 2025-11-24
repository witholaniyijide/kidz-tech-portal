@props([
    'userName' => 'Manager'
])

<x-ui.glass-card padding="p-8" class="mb-8 animate-fadeIn">
    <div class="flex items-center justify-between">
        <div>
            <h3 class="text-3xl font-bold text-gray-900 dark:text-white mb-2">
                Welcome back, <span class="text-transparent bg-clip-text bg-gradient-manager">{{ $userName }}</span>!
            </h3>
            <p class="text-gray-600 dark:text-gray-300 text-lg">{{ now()->format('l, F j, Y') }}</p>
        </div>
        <div class="hidden md:block">
            <div class="w-16 h-16 rounded-2xl bg-gradient-manager shadow-lg flex items-center justify-center">
                <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                </svg>
            </div>
        </div>
    </div>
</x-ui.glass-card>
