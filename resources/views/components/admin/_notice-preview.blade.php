@props(['notices' => []])

<x-ui.card
    :empty="empty($notices)"
    emptyMessage="No notices posted yet"
    role="region"
    aria-label="Notice Board"
>
    <x-slot:emptyAction>
        <a
            href="{{ route('noticeboard.create') }}"
            class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-teal-500 to-cyan-500 text-white text-base font-semibold rounded-xl shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-teal-500"
            aria-label="Create new notice"
        >
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>
            Post New Notice
        </a>
    </x-slot:emptyAction>

    @if(!empty($notices))
    <div class="p-6">
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-2xl font-bold text-gray-800 dark:text-white font-inter">📢 Notice Board</h2>
            <div class="flex gap-3">
                <a
                    href="{{ route('noticeboard.index') }}"
                    class="text-teal-600 dark:text-teal-400 hover:text-teal-700 dark:hover:text-teal-300 font-medium text-sm transition-colors focus:outline-none focus-visible:ring-2 focus-visible:ring-teal-500 focus-visible:ring-offset-2 rounded px-2 py-1"
                    aria-label="View all notices"
                >
                    View All Notices →
                </a>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4" role="list" aria-label="Recent notices">
            @foreach($notices as $notice)
            <article
                class="p-4 bg-gradient-to-br from-{{ $notice['color'] ?? 'blue' }}-50 to-{{ $notice['color'] ?? 'cyan' }}-50 dark:from-{{ $notice['color'] ?? 'blue' }}-900/20 dark:to-{{ $notice['color'] ?? 'cyan' }}-900/20 rounded-xl border border-{{ $notice['color'] ?? 'blue' }}-200 dark:border-{{ $notice['color'] ?? 'blue' }}-700 hover:shadow-lg transition-shadow duration-300 focus-within:ring-2 focus-within:ring-{{ $notice['color'] ?? 'blue' }}-500"
                role="listitem"
            >
                <div class="flex items-center justify-between mb-2">
                    <span class="px-3 py-1 bg-{{ $notice['color'] ?? 'blue' }}-500 text-white text-xs font-semibold rounded-full">
                        {{ $notice['type'] ?? 'General' }}
                    </span>
                    <time class="text-xs text-gray-500 dark:text-gray-400 font-inter" datetime="{{ $notice['date'] ?? '' }}">
                        {{ $notice['dateHuman'] ?? '' }}
                    </time>
                </div>
                <h3 class="font-semibold text-gray-800 dark:text-white mb-2 font-inter text-base">
                    {{ $notice['title'] ?? '' }}
                </h3>
                <p class="text-sm text-gray-600 dark:text-gray-300 font-inter">
                    {{ Str::limit($notice['content'] ?? '', 80) }}
                </p>
                @if(isset($notice['url']))
                <a
                    href="{{ $notice['url'] }}"
                    class="inline-flex items-center mt-3 text-sm text-{{ $notice['color'] ?? 'blue' }}-600 hover:text-{{ $notice['color'] ?? 'blue' }}-800 dark:text-{{ $notice['color'] ?? 'blue' }}-400 dark:hover:text-{{ $notice['color'] ?? 'blue' }}-300 font-medium focus:outline-none focus-visible:ring-2 focus-visible:ring-{{ $notice['color'] ?? 'blue' }}-500 focus-visible:ring-offset-2 rounded px-1"
                    aria-label="Read more about {{ $notice['title'] ?? 'notice' }}"
                >
                    Read more →
                </a>
                @endif
            </article>
            @endforeach
        </div>

        <button
            type="button"
            onclick="window.location.href='{{ route('noticeboard.create') }}'"
            class="w-full px-6 py-3 bg-gradient-to-r from-teal-500 to-cyan-500 text-white font-semibold rounded-xl shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-teal-500 focus-visible:ring-2 focus-visible:ring-teal-500"
            aria-label="Post new notice"
        >
            ➕ Post New Notice
        </button>
    </div>
    @endif
</x-ui.card>
