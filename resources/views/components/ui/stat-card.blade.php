@props([
    'title' => '',
    'number' => '0',
    'subtitle' => '',
    'icon' => '',
    'gradient' => 'from-blue-500 to-cyan-500'
])

<div {{ $attributes->merge(['class' => 'group relative overflow-hidden rounded-2xl bg-gradient-to-br ' . $gradient . ' p-6 shadow-xl hover:shadow-2xl transform hover:-translate-y-1 transition-all duration-300']) }}>
    <div class="absolute inset-0 bg-white/10 backdrop-blur-sm"></div>
    <div class="relative z-10">
        @if($icon)
        <div class="flex items-center justify-between mb-4">
            <div class="p-3 bg-white/20 rounded-xl backdrop-blur-sm">
                {!! $icon !!}
            </div>
        </div>
        @endif
        <h3 class="text-white/80 text-sm font-medium mb-1 font-inter">{{ $title }}</h3>
        <p class="text-4xl font-bold text-white mb-1 font-inter">{{ $number }}</p>
        @if($subtitle)
        <p class="text-white/70 text-xs mt-2 font-inter">{{ $subtitle }}</p>
        @endif
        @if(isset($footer))
        <div class="mt-3">
            {{ $footer }}
        </div>
        @endif
    </div>
</div>
