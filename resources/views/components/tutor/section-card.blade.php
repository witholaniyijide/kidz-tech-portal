@props(['title' => null, 'padding' => 'p-6'])

<div class="rounded-2xl bg-white/20 dark:bg-gray-900/20 border border-white/10 dark:border-gray-700/10 shadow-xl backdrop-blur-xl {{ $padding }} hover:-translate-y-1 hover:shadow-2xl transition-all duration-300">
    @if($title)
        <div class="flex items-center gap-3 mb-6">
            <div class="bg-gradient-to-r from-purple-500 to-pink-500 p-2 rounded-lg">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
            </div>
            <h3 class="text-2xl font-bold text-gray-900 dark:text-white">{{ $title }}</h3>
        </div>
    @endif

    {{ $slot }}
</div>
