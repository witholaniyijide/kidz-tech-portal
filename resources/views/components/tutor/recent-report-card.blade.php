@props(['studentName', 'month', 'status', 'link' => '#'])

@php
    $statusStyles = [
        'draft' => 'bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300',
        'submitted' => 'bg-yellow-100 dark:bg-yellow-900/30 text-yellow-700 dark:text-yellow-300',
        'manager_review' => 'bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-300',
        'director_approved' => 'bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-300',
        'approved' => 'bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-300',
    ];

    $statusLabels = [
        'draft' => 'Draft',
        'submitted' => 'Submitted',
        'manager_review' => 'Under Review',
        'director_approved' => 'Approved',
        'approved' => 'Approved',
    ];

    $borderColors = [
        'draft' => 'border-gray-400',
        'submitted' => 'border-yellow-500',
        'manager_review' => 'border-blue-500',
        'director_approved' => 'border-green-500',
        'approved' => 'border-green-500',
    ];
@endphp

<div class="p-4 rounded-xl bg-white/30 dark:bg-gray-800/30 border-l-4 {{ $borderColors[$status] ?? 'border-purple-500' }} hover:bg-white/40 dark:hover:bg-gray-800/40 transition-all">
    <div class="flex justify-between items-start gap-4">
        <div class="flex-1">
            <h4 class="font-semibold text-gray-900 dark:text-white mb-1">{{ $studentName }}</h4>
            <p class="text-sm text-gray-600 dark:text-gray-400">{{ $month }}</p>
        </div>
        <div class="flex flex-col items-end gap-2">
            <span class="px-3 py-1 rounded-full {{ $statusStyles[$status] ?? 'bg-purple-100 text-purple-700' }} text-xs font-semibold whitespace-nowrap">
                {{ $statusLabels[$status] ?? ucfirst($status) }}
            </span>
            <a href="{{ $link }}" class="text-sm font-semibold text-purple-600 dark:text-purple-400 hover:text-pink-600 dark:hover:text-pink-400 transition-colors">
                @if($status === 'draft')
                    Continue Editing →
                @else
                    View Report →
                @endif
            </a>
        </div>
    </div>
</div>
