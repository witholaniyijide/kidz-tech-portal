@props([
    'title' => '',
    'number' => '0',
    'subtitle' => '',
    'icon' => '',
    'gradient' => 'from-blue-500 to-cyan-600',
    'textGradient' => 'from-blue-600 to-cyan-600'
])

<div {{ $attributes->merge(['class' => 'glass-card rounded-2xl p-6 shadow-xl hover-lift cursor-pointer transform transition-all duration-300']) }}>
    <div class="flex items-center justify-between mb-4">
        @if($icon || isset($icon))
        <div class="w-14 h-14 rounded-xl bg-gradient-to-br {{ $gradient }} flex items-center justify-center shadow-lg icon-bounce">
            {{ $icon }}
        </div>
        @endif
    </div>
    <h4 class="text-sm font-semibold text-gray-600 dark:text-gray-400 mb-2">{{ $title }}</h4>
    <p class="text-4xl font-bold text-transparent bg-clip-text bg-gradient-to-r {{ $textGradient }} mb-3">
        {{ $number }}
    </p>
    @if($subtitle)
    <div class="flex items-center text-sm">
        <span class="text-gray-600 dark:text-gray-400">{{ $subtitle }}</span>
    </div>
    @endif
    @if(isset($footer))
    <div class="mt-3">
        {{ $footer }}
    </div>
    @endif
</div>
