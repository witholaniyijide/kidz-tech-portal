@props(['href' => '#', 'gradient'])

<a href="{{ $href }}" class="inline-block px-5 py-3 text-white rounded-xl shadow-md hover:shadow-xl transition-all duration-200 hover:-translate-y-1 {{ $gradient }}">
    {{ $slot }}
</a>
