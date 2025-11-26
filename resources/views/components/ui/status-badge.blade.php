@props(['status'])

@php
$classes = match(strtolower($status)) {
    'draft' => 'bg-gray-100 text-gray-800 dark:bg-gray-900/30 dark:text-gray-400 border border-gray-300 dark:border-gray-700',
    'submitted' => 'bg-indigo-100 text-indigo-800 dark:bg-indigo-900/30 dark:text-indigo-400 border border-indigo-300 dark:border-indigo-700',
    'approved-by-manager', 'approved by manager' => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-400 border border-yellow-300 dark:border-yellow-700',
    'approved-by-director', 'approved by director' => 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400 border border-green-300 dark:border-green-700',
    'rejected' => 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400 border border-red-300 dark:border-red-700',
    default => 'bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-400 border border-blue-300 dark:border-blue-700',
};

$displayText = ucfirst(str_replace('-', ' ', $status));
@endphp

<span {{ $attributes->merge(['class' => "inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold $classes"]) }}>
    {{ $displayText }}
</span>
