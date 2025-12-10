@props([
    'name',
    'label' => null,
    'type' => 'text',
    'value' => '',
    'required' => false,
    'readonly' => false,
    'placeholder' => '',
    'helpText' => null,
    'dataDob' => false,
    'dataAge' => false,
])

<div>
    @if($label)
        <label for="{{ $name }}" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
            {{ $label }}
            @if($required)
                <span class="text-red-500">*</span>
            @endif
        </label>
    @endif

    <input
        type="{{ $type }}"
        name="{{ $name }}"
        id="{{ $name }}"
        value="{{ old($name, $value) }}"
        {{ $required ? 'required' : '' }}
        {{ $readonly ? 'readonly' : '' }}
        @if($placeholder) placeholder="{{ $placeholder }}" @endif
        @if($dataDob) data-dob @endif
        @if($dataAge) data-age @endif
        {{ $attributes->merge(['class' => 'mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 shadow-sm focus:border-sky-500 focus:ring-sky-500 transition-colors ' . ($readonly ? 'bg-gray-100 dark:bg-gray-600 cursor-not-allowed' : '')]) }}
    >

    @error($name)
        <p class="mt-2 text-sm text-red-600 dark:text-red-400" role="alert">{{ $message }}</p>
    @enderror

    @if($helpText)
        <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">{{ $helpText }}</p>
    @endif
</div>
