@props(['percentage' => 0, 'size' => 120, 'strokeWidth' => 8])

@php
    $radius = ($size - $strokeWidth) / 2;
    $circumference = 2 * pi() * $radius;
    $offset = $circumference - ($percentage / 100) * $circumference;
@endphp

<div class="relative inline-flex items-center justify-center" style="width: {{ $size }}px; height: {{ $size }}px;">
    <svg class="progress-circle" width="{{ $size }}" height="{{ $size }}">
        <!-- Background circle -->
        <circle
            cx="{{ $size / 2 }}"
            cy="{{ $size / 2 }}"
            r="{{ $radius }}"
            fill="none"
            stroke="currentColor"
            stroke-width="{{ $strokeWidth }}"
            class="text-gray-200 dark:text-gray-700"
        />
        <!-- Progress circle -->
        <circle
            cx="{{ $size / 2 }}"
            cy="{{ $size / 2 }}"
            r="{{ $radius }}"
            fill="none"
            stroke="url(#gradient)"
            stroke-width="{{ $strokeWidth }}"
            stroke-dasharray="{{ $circumference }}"
            stroke-dashoffset="{{ $offset }}"
            stroke-linecap="round"
            class="transition-all duration-1000 ease-out"
        />
        <!-- Gradient definition -->
        <defs>
            <linearGradient id="gradient" x1="0%" y1="0%" x2="100%" y2="100%">
                <stop offset="0%" style="stop-color:#0ea5e9;stop-opacity:1" />
                <stop offset="100%" style="stop-color:#06b6d4;stop-opacity:1" />
            </linearGradient>
        </defs>
    </svg>
    <!-- Percentage text -->
    <div class="absolute inset-0 flex items-center justify-center">
        <span class="text-2xl font-bold text-gray-700 dark:text-gray-300">
            {{ $percentage }}%
        </span>
    </div>
</div>
