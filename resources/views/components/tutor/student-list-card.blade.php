@props(['studentName', 'lastClass', 'avatarUrl' => null, 'createReportLink' => '#'])

<div class="p-4 rounded-xl bg-white/30 dark:bg-gray-800/30 border border-white/20 dark:border-gray-700/20 hover:bg-white/40 dark:hover:bg-gray-800/40 transition-all">
    <div class="flex items-center justify-between gap-4">
        <div class="flex items-center gap-3 flex-1">
            <div class="w-12 h-12 rounded-full bg-gradient-to-r from-purple-500 to-pink-500 flex items-center justify-center text-white font-bold text-lg flex-shrink-0">
                @if($avatarUrl)
                    <img src="{{ $avatarUrl }}" alt="{{ $studentName }}" class="w-full h-full rounded-full object-cover">
                @else
                    {{ substr($studentName, 0, 1) }}
                @endif
            </div>
            <div class="flex-1 min-w-0">
                <h4 class="font-semibold text-gray-900 dark:text-white truncate">{{ $studentName }}</h4>
                <p class="text-sm text-gray-600 dark:text-gray-400">Last class: {{ $lastClass }}</p>
            </div>
        </div>
        <a href="{{ $createReportLink }}" class="px-4 py-2 bg-gradient-to-r from-purple-500 to-pink-500 text-white text-sm font-semibold rounded-lg hover:-translate-y-0.5 hover:shadow-lg transition-all whitespace-nowrap">
            Create Report
        </a>
    </div>
</div>
