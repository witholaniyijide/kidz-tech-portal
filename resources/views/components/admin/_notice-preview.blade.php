@props(['notices' => []])

@if(empty($notices))
<div class="glass-card rounded-2xl p-6 shadow-xl text-center" role="region" aria-label="Notice Board" style="animation-delay: 0.7s;">
    <div class="py-12">
        <div class="w-20 h-20 mx-auto mb-6 rounded-2xl bg-gradient-to-br from-[#14B8A6] to-[#06B6D4] flex items-center justify-center shadow-lg">
            <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z" />
            </svg>
        </div>
        <h3 class="text-lg font-semibold text-gray-700 dark:text-gray-300 mb-2">No Notices Posted</h3>
        <p class="text-gray-500 dark:text-gray-400 mb-6">There are no notices on the board yet.</p>
        <a
            href="{{ route('noticeboard.create') }}"
            class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-[#14B8A6] to-[#06B6D4] text-white text-base font-semibold rounded-xl shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-teal-500"
            aria-label="Create new notice"
        >
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>
            Post New Notice
        </a>
    </div>
</div>
@else
<div class="glass-card rounded-2xl p-6 shadow-xl" role="region" aria-label="Notice Board" style="animation-delay: 0.7s;">
    <div class="flex items-center justify-between mb-6">
        <h3 class="text-xl font-bold text-gray-900 dark:text-white flex items-center">
            <svg class="w-5 h-5 mr-2 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z" />
            </svg>
            Notice Board
        </h3>
        <a
            href="{{ route('noticeboard.index') }}"
            class="text-teal-600 dark:text-teal-400 hover:text-teal-700 dark:hover:text-teal-300 font-medium text-sm transition-colors focus:outline-none focus-visible:ring-2 focus-visible:ring-teal-500 focus-visible:ring-offset-2 rounded px-2 py-1 flex items-center"
            aria-label="View all notices"
        >
            View All
            <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6" />
            </svg>
        </a>
    </div>

    <div class="space-y-3 mb-6" role="list" aria-label="Recent notices">
        @foreach($notices as $notice)
        <article
            class="p-4 bg-white dark:bg-slate-800/50 rounded-lg border border-gray-200 dark:border-gray-700 hover:shadow-md transition-all duration-300 focus-within:ring-2 focus-within:ring-teal-500"
            role="listitem"
        >
            <div class="flex items-start justify-between mb-2">
                <span class="px-3 py-1 bg-gradient-to-r from-[#14B8A6] to-[#06B6D4] text-white text-xs font-semibold rounded-full">
                    {{ $notice['type'] ?? 'General' }}
                </span>
                <time class="text-xs text-gray-500 dark:text-gray-400" datetime="{{ $notice['date'] ?? '' }}">
                    {{ $notice['dateHuman'] ?? '' }}
                </time>
            </div>
            <h4 class="font-semibold text-gray-900 dark:text-white mb-2 text-sm">
                {{ $notice['title'] ?? '' }}
            </h4>
            <p class="text-sm text-gray-600 dark:text-gray-300 mb-2">
                {{ Str::limit($notice['content'] ?? '', 80) }}
            </p>
            @if(isset($notice['url']))
            <a
                href="{{ $notice['url'] }}"
                class="inline-flex items-center text-sm text-teal-600 hover:text-teal-800 dark:text-teal-400 dark:hover:text-teal-300 font-medium focus:outline-none focus-visible:ring-2 focus-visible:ring-teal-500 focus-visible:ring-offset-2 rounded px-1"
                aria-label="Read more about {{ $notice['title'] ?? 'notice' }}"
            >
                Read more
                <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                </svg>
            </a>
            @endif
        </article>
        @endforeach
    </div>

    <a
        href="{{ route('noticeboard.create') }}"
        class="block w-full text-center px-6 py-3 bg-gradient-to-r from-[#14B8A6] to-[#06B6D4] text-white font-semibold rounded-xl shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-teal-500 focus-visible:ring-2 focus-visible:ring-teal-500"
        aria-label="Post new notice"
    >
        ➕ Post New Notice
    </a>
</div>
@endif
