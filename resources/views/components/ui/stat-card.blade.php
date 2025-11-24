@props(['title', 'value', 'icon', 'gradient', 'subtitle' => null])

<div class="rounded-2xl text-white shadow-xl p-6 {{ $gradient }} hover:-translate-y-1 hover:shadow-2xl transition">
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
