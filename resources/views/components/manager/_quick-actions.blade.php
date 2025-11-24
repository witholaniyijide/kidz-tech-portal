@props([
    'actions' => [
        [
            'title' => 'View Today\'s Schedule',
            'route' => 'schedule.today',
            'icon' => '<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>',
            'description' => 'View and manage today\'s classes'
        ],
        [
            'title' => 'Pending Assessments',
            'route' => 'assessments.pending',
            'icon' => '<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" /></svg>',
            'description' => 'Review pending assessments',
            'badge' => 'pendingAssessmentsCount'
        ],
        [
            'title' => 'Create Assessment Report',
            'route' => 'assessments.create',
            'icon' => '<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>',
            'description' => 'Create new assessment report'
        ],
        [
            'title' => 'View Tutor Reports',
            'route' => 'reports.index',
            'params' => ['filter' => 'tutor'],
            'icon' => '<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" /></svg>',
            'description' => 'View tutor performance reports'
        ],
        [
            'title' => 'Notice Board',
            'route' => 'noticeboard.index',
            'icon' => '<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" /></svg>',
            'description' => 'Manage notices and announcements'
        ]
    ]
])

<x-ui.glass-card>
    <x-ui.section-title class="mb-6">Quick Actions</x-ui.section-title>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-5 gap-4">
        @foreach($actions as $action)
        <a
            href="{{ route($action['route'], $action['params'] ?? []) }}"
            class="group relative p-6 rounded-2xl bg-white/20 dark:bg-gray-900/30 backdrop-blur-xl border border-white/10 dark:border-gray-700/10 hover:border-sky-400/50 hover:bg-white/30 dark:hover:bg-gray-900/40 hover:-translate-y-1 hover:shadow-xl transition-all duration-200"
            aria-label="{{ $action['title'] }}"
        >
            {{-- Badge for counts --}}
            @if(isset($action['badge']) && isset(${$action['badge']}) && ${$action['badge']} > 0)
            <span class="absolute -top-2 -right-2 w-6 h-6 rounded-full bg-amber-500 text-white text-xs font-bold flex items-center justify-center shadow-lg animate-pulse">
                {{ ${$action['badge']} }}
            </span>
            @endif

            {{-- Icon --}}
            <div class="w-12 h-12 rounded-xl bg-gradient-manager shadow-lg flex items-center justify-center text-white mb-4 group-hover:scale-110 transition-transform duration-200">
                {!! $action['icon'] !!}
            </div>

            {{-- Title --}}
            <h4 class="font-semibold text-gray-900 dark:text-white mb-2 group-hover:text-sky-600 dark:group-hover:text-sky-400 transition-colors">
                {{ $action['title'] }}
            </h4>

            {{-- Description --}}
            <p class="text-xs text-gray-600 dark:text-gray-400">
                {{ $action['description'] }}
            </p>

            {{-- Arrow indicator --}}
            <div class="absolute bottom-4 right-4 opacity-0 group-hover:opacity-100 transition-opacity">
                <svg class="w-5 h-5 text-sky-600 dark:text-sky-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                </svg>
            </div>
        </a>
        @endforeach
    </div>
</x-ui.glass-card>
