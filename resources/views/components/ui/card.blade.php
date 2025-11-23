@props([
    'loading' => false,
    'empty' => false,
    'emptyMessage' => 'No data available',
    'emptyAction' => null
])

<div {{ $attributes->merge(['class' => 'relative overflow-hidden rounded-2xl bg-white/70 dark:bg-slate-800/70 backdrop-blur-xl shadow-xl border border-white/20']) }}>
    @if($loading)
        {{-- Skeleton Loader --}}
        <div class="p-6 animate-pulse">
            <div class="h-6 bg-gray-300 dark:bg-gray-600 rounded w-1/3 mb-4"></div>
            <div class="space-y-3">
                <div class="h-4 bg-gray-200 dark:bg-gray-700 rounded"></div>
                <div class="h-4 bg-gray-200 dark:bg-gray-700 rounded w-5/6"></div>
                <div class="h-4 bg-gray-200 dark:bg-gray-700 rounded w-4/6"></div>
            </div>
        </div>
    @elseif($empty)
        {{-- Empty State --}}
        <div class="p-12 text-center">
            <svg class="w-16 h-16 mx-auto mb-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
            </svg>
            <p class="text-gray-600 dark:text-gray-400 text-base font-inter mb-4">{{ $emptyMessage }}</p>
            @if($emptyAction)
                {{ $emptyAction }}
            @endif
        </div>
    @else
        {{ $slot }}
    @endif
</div>
