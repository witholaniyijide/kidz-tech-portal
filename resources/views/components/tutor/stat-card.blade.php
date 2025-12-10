@props(['title', 'value', 'icon', 'subtitle' => null])

<div class="rounded-2xl bg-gradient-to-r from-purple-500 to-pink-500 text-white shadow-xl p-6 hover:-translate-y-1 hover:scale-105 hover:shadow-2xl transition-all duration-300">
    <div class="flex items-center justify-between">
        <div class="flex-1">
            <div class="text-xl font-semibold opacity-90">{{ $title }}</div>
            <div class="text-4xl font-extrabold mt-2">{{ $value }}</div>
            @if($subtitle)
                <div class="text-sm opacity-80 mt-2">{{ $subtitle }}</div>
            @endif
        </div>
        <div class="text-5xl opacity-80">{!! $icon !!}</div>
    </div>
</div>
