{{--
    Tutor Portal Layout
    This is a wrapper that uses the tutor-layout component.
    For new views, prefer using <x-tutor-layout> directly.
--}}
@props(['title' => 'Tutor Portal'])

<x-tutor-layout :title="$title">
    {{ $slot }}
</x-tutor-layout>
