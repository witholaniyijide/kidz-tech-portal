@props(['items' => []])

@if(count($items) > 0)
<nav class="mb-6">
    <ol class="flex items-center space-x-2 text-sm">
        <li>
            <a href="{{ route('tutor.dashboard') }}" class="text-gray-600 dark:text-gray-400 hover:text-purple-600 dark:hover:text-purple-400 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                </svg>
            </a>
        </li>

        @foreach($items as $index => $item)
            <li class="flex items-center">
                <svg class="w-4 h-4 text-gray-400 dark:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>

                @if($index < count($items) - 1 && isset($item['url']))
                    <a href="{{ $item['url'] }}" class="ml-2 text-gray-600 dark:text-gray-400 hover:text-purple-600 dark:hover:text-purple-400 transition-colors">
                        {{ $item['label'] }}
                    </a>
                @else
                    <span class="ml-2 text-gray-900 dark:text-white font-medium">
                        {{ $item['label'] }}
                    </span>
                @endif
            </li>
        @endforeach
    </ol>
</nav>
@endif
