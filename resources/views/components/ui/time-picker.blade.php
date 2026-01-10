@props([
    'name' => 'time',
    'value' => '',
    'required' => false,
    'disabled' => false,
])

@php
    // Parse existing 24-hour time value to 12-hour format
    $hour = '';
    $minute = '';
    $period = 'AM';

    if ($value) {
        $timeParts = explode(':', $value);
        if (count($timeParts) >= 2) {
            $h = (int) $timeParts[0];
            $minute = $timeParts[1];

            if ($h === 0) {
                $hour = '12';
                $period = 'AM';
            } elseif ($h === 12) {
                $hour = '12';
                $period = 'PM';
            } elseif ($h > 12) {
                $hour = (string) ($h - 12);
                $period = 'PM';
            } else {
                $hour = (string) $h;
                $period = 'AM';
            }
        }
    }
@endphp

<div x-data="{
    hour: '{{ $hour }}',
    minute: '{{ $minute }}',
    period: '{{ $period }}',
    get time24() {
        if (!this.hour || !this.minute) return '';
        let h = parseInt(this.hour);
        if (this.period === 'PM' && h !== 12) h += 12;
        if (this.period === 'AM' && h === 12) h = 0;
        return String(h).padStart(2, '0') + ':' + this.minute;
    }
}" class="flex items-center gap-1 sm:gap-2">
    {{-- Hidden input for form submission --}}
    <input type="hidden" name="{{ $name }}" :value="time24">

    {{-- Hour --}}
    <select x-model="hour"
            {{ $disabled ? 'disabled' : '' }}
            class="w-16 sm:w-20 px-2 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg text-sm focus:ring-2 focus:ring-blue-500 {{ $disabled ? 'bg-gray-100 cursor-not-allowed' : '' }}">
        <option value="">Hr</option>
        @for($i = 1; $i <= 12; $i++)
            <option value="{{ $i }}">{{ $i }}</option>
        @endfor
    </select>

    <span class="text-gray-500 dark:text-gray-400 font-bold">:</span>

    {{-- Minute --}}
    <select x-model="minute"
            {{ $disabled ? 'disabled' : '' }}
            class="w-16 sm:w-20 px-2 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg text-sm focus:ring-2 focus:ring-blue-500 {{ $disabled ? 'bg-gray-100 cursor-not-allowed' : '' }}">
        <option value="">Min</option>
        @for($i = 0; $i < 60; $i += 5)
            <option value="{{ str_pad($i, 2, '0', STR_PAD_LEFT) }}">{{ str_pad($i, 2, '0', STR_PAD_LEFT) }}</option>
        @endfor
    </select>

    {{-- AM/PM --}}
    <select x-model="period"
            {{ $disabled ? 'disabled' : '' }}
            class="w-16 sm:w-20 px-2 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg text-sm focus:ring-2 focus:ring-blue-500 {{ $disabled ? 'bg-gray-100 cursor-not-allowed' : '' }}">
        <option value="AM">AM</option>
        <option value="PM">PM</option>
    </select>
</div>
