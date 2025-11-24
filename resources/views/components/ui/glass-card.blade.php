@props(['padding' => 'p-6'])

<div class="rounded-2xl bg-white/20 dark:bg-gray-900/20 border border-white/10 dark:border-gray-700/10 shadow-xl backdrop-blur-xl {{ $padding }} hover:-translate-y-1 hover:shadow-2xl transition-all duration-300">
    {{ $slot }}
</div>
