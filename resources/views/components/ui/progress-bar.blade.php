@props(['value' => 0, 'max' => 100, 'label' => null, 'showPercentage' => true])

@php
$percentage = $max > 0 ? ($value / $max) * 100 : 0;
$colorClass = match(true) {
    $percentage >= 90 => 'bg-gradient-to-r from-green-500 to-emerald-600',
    $percentage >= 75 => 'bg-gradient-to-r from-blue-500 to-cyan-600',
    $percentage >= 60 => 'bg-gradient-to-r from-yellow-500 to-orange-500',
    default => 'bg-gradient-to-r from-red-500 to-pink-600',
};
@endphp

<div {{ $attributes->merge(['class' => 'w-full']) }}>
    @if($label)
    <div class="flex justify-between items-center mb-2">
        <span class="text-sm font-medium text-gray-700 dark:text-gray-300">{{ $label }}</span>
        @if($showPercentage)
        <span class="text-sm font-bold text-gray-900 dark:text-white">{{ number_format($percentage, 0) }}%</span>
        @endif
    </div>
    @endif

    <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-3 overflow-hidden shadow-inner">
        <div
            class="{{ $colorClass }} h-3 rounded-full transition-all duration-500 ease-out flex items-center justify-end pr-1 shadow-md"
            style="width: {{ min($percentage, 100) }}%"
            role="progressbar"
            aria-valuenow="{{ $value }}"
            aria-valuemin="0"
            aria-valuemax="{{ $max }}">
            @if($percentage >= 15 && $showPercentage)
                <span class="text-xs font-bold text-white">{{ number_format($percentage, 0) }}%</span>
            @endif
        </div>
    </div>
</div>
