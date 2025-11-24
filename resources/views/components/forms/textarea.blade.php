@props([
    'name',
    'label' => null,
    'value' => '',
    'required' => false,
    'rows' => 3,
    'placeholder' => '',
    'helpText' => null,
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

    <textarea
        name="{{ $name }}"
        id="{{ $name }}"
        rows="{{ $rows }}"
        {{ $required ? 'required' : '' }}
        @if($placeholder) placeholder="{{ $placeholder }}" @endif
        {{ $attributes->merge(['class' => 'mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 shadow-sm focus:border-sky-500 focus:ring-sky-500 transition-colors']) }}
    >{{ old($name, $value) }}</textarea>

    @error($name)
        <p class="mt-2 text-sm text-red-600 dark:text-red-400" role="alert">{{ $message }}</p>
    @enderror

    @if($helpText)
        <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">{{ $helpText }}</p>
    @endif
</div>
