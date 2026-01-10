{{-- Alpine.js Time Picker Component --}}
{{-- Usage: Include in x-for loops where you need dynamic time binding --}}
{{-- Pass: :name, :model-prefix (e.g., 'schedule.time') --}}

@props([
    'namePrefix' => 'time',
    'modelPrefix' => 'time',
    'index' => 0,
])

<div class="flex items-center gap-1 sm:gap-2">
    {{-- Hour --}}
    <select x-model="{{ $modelPrefix }}_hour"
            @change="updateTimeFromParts({{ $index }})"
            class="w-14 sm:w-16 px-1 sm:px-2 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg text-sm focus:ring-2 focus:ring-blue-500">
        <option value="">Hr</option>
        <template x-for="h in 12" :key="h">
            <option :value="h" x-text="h"></option>
        </template>
    </select>

    <span class="text-gray-500 dark:text-gray-400 font-bold">:</span>

    {{-- Minute --}}
    <select x-model="{{ $modelPrefix }}_min"
            @change="updateTimeFromParts({{ $index }})"
            class="w-14 sm:w-16 px-1 sm:px-2 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg text-sm focus:ring-2 focus:ring-blue-500">
        <option value="">Min</option>
        @for($i = 0; $i < 60; $i += 5)
            <option value="{{ str_pad($i, 2, '0', STR_PAD_LEFT) }}">{{ str_pad($i, 2, '0', STR_PAD_LEFT) }}</option>
        @endfor
    </select>

    {{-- AM/PM --}}
    <select x-model="{{ $modelPrefix }}_period"
            @change="updateTimeFromParts({{ $index }})"
            class="w-14 sm:w-16 px-1 sm:px-2 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg text-sm focus:ring-2 focus:ring-blue-500">
        <option value="AM">AM</option>
        <option value="PM">PM</option>
    </select>
</div>
