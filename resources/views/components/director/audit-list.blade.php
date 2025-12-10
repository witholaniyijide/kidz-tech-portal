@props(['audits', 'title' => 'Audit Trail', 'icon' => true])

@if($audits && $audits->count() > 0)
<div {{ $attributes->merge(['class' => 'backdrop-blur-md bg-white/30 dark:bg-gray-900/30 border border-white/10 rounded-2xl shadow-xl p-6']) }}>
    @if($title)
    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 flex items-center">
        @if($icon)
        <svg class="w-5 h-5 mr-2 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
        </svg>
        @endif
        {{ $title }}
    </h3>
    @endif

    <div class="space-y-3 max-h-96 overflow-y-auto">
        @foreach($audits as $audit)
        <div class="bg-gray-50 dark:bg-gray-800/50 border border-gray-200 dark:border-gray-700 rounded-lg p-4 hover:bg-gray-100 dark:hover:bg-gray-800/70 transition-colors">
            <!-- Action and timestamp -->
            <div class="flex items-center justify-between mb-2">
                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-purple-100 text-purple-800 dark:bg-purple-900/30 dark:text-purple-400">
                    {{ ucfirst(str_replace(['.', '_'], [' → ', ' '], $audit->action)) }}
                </span>
                <span class="text-xs text-gray-500 dark:text-gray-400" title="{{ $audit->created_at->format('l, F j, Y \a\t g:i A') }}">
                    {{ $audit->created_at->diffForHumans() }}
                </span>
            </div>

            <!-- User info -->
            <div class="flex items-center mb-2">
                <svg class="w-4 h-4 mr-2 text-gray-500 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                </svg>
                <span class="text-sm font-medium text-gray-900 dark:text-white">
                    {{ $audit->user->name ?? 'System' }}
                </span>
                @if($audit->user)
                <span class="ml-2 text-xs text-gray-500 dark:text-gray-400">
                    • {{ $audit->user->email }}
                </span>
                @endif
            </div>

            <!-- Metadata -->
            @if(is_array($audit->meta) && (isset($audit->meta['director_comment']) || isset($audit->meta['manager_comment']) || isset($audit->meta['previous_status'])))
            <div class="mt-3 pt-3 border-t border-gray-200 dark:border-gray-700">
                @if(isset($audit->meta['previous_status']))
                <div class="text-xs text-gray-600 dark:text-gray-400 mb-2">
                    <span class="font-medium">Status:</span>
                    <x-ui.status-badge :status="$audit->meta['previous_status']" class="ml-2" />
                    <svg class="w-3 h-3 inline mx-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                    <x-ui.status-badge :status="$audit->meta['new_status'] ?? 'unknown'" />
                </div>
                @endif

                @if(isset($audit->meta['director_comment']) && $audit->meta['director_comment'])
                <div class="mt-2 p-3 bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg">
                    <div class="text-xs font-semibold text-blue-800 dark:text-blue-400 mb-1">Director Comment:</div>
                    <p class="text-xs text-gray-700 dark:text-gray-300 italic">
                        "{{ \Illuminate\Support\Str::limit($audit->meta['director_comment'], 200) }}"
                    </p>
                </div>
                @endif

                @if(isset($audit->meta['manager_comment']) && $audit->meta['manager_comment'])
                <div class="mt-2 p-3 bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-lg">
                    <div class="text-xs font-semibold text-green-800 dark:text-green-400 mb-1">Manager Comment:</div>
                    <p class="text-xs text-gray-700 dark:text-gray-300 italic">
                        "{{ \Illuminate\Support\Str::limit($audit->meta['manager_comment'], 200) }}"
                    </p>
                </div>
                @endif
            </div>
            @endif
        </div>
        @endforeach
    </div>

    @if($audits->hasPages())
    <div class="mt-4 border-t border-gray-200 dark:border-gray-700 pt-4">
        {{ $audits->links() }}
    </div>
    @endif
</div>
@endif
