@props([
    'name',
    'label' => null,
    'accept' => 'image/*',
    'required' => false,
    'helpText' => null,
    'currentFile' => null,
    'previewId' => null,
])

<div x-data="{
    preview: '{{ $currentFile ? asset('storage/' . $currentFile) : '' }}',
    filename: '{{ $currentFile ? basename($currentFile) : '' }}'
}">
    @if($label)
        <label for="{{ $name }}" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
            {{ $label }}
            @if($required)
                <span class="text-red-500">*</span>
            @endif
        </label>
    @endif

    <div class="flex items-start gap-4">
        {{-- Preview --}}
        @if($accept === 'image/*' || str_contains($accept, 'image'))
            <div class="flex-shrink-0">
                <div class="w-24 h-24 rounded-lg overflow-hidden bg-gray-100 dark:bg-gray-700 border-2 border-dashed border-gray-300 dark:border-gray-600 flex items-center justify-center">
                    <img
                        x-show="preview"
                        :src="preview"
                        alt="Preview"
                        class="w-full h-full object-cover"
                        @if($previewId) id="{{ $previewId }}" @endif
                    >
                    <svg x-show="!preview" class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                </div>
            </div>
        @endif

        {{-- File Input --}}
        <div class="flex-1">
            <label class="relative cursor-pointer">
                <div class="flex items-center justify-center px-6 py-4 border-2 border-dashed border-gray-300 dark:border-gray-600 rounded-lg hover:border-sky-500 dark:hover:border-sky-400 transition-colors bg-white dark:bg-gray-800">
                    <div class="text-center">
                        <svg class="mx-auto h-8 w-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                        </svg>
                        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                            <span class="font-semibold text-sky-600 dark:text-sky-400">Click to upload</span>
                            or drag and drop
                        </p>
                        <p class="text-xs text-gray-500 dark:text-gray-500 mt-1" x-show="!filename">
                            @if($helpText)
                                {{ $helpText }}
                            @else
                                PNG, JPG, WEBP up to 2MB
                            @endif
                        </p>
                        <p class="text-xs text-sky-600 dark:text-sky-400 mt-1" x-show="filename" x-text="filename"></p>
                    </div>
                </div>
                <input
                    type="file"
                    name="{{ $name }}"
                    id="{{ $name }}"
                    accept="{{ $accept }}"
                    {{ $required ? 'required' : '' }}
                    class="sr-only"
                    @change="
                        filename = $event.target.files[0]?.name || '';
                        if ($event.target.files[0]) {
                            const reader = new FileReader();
                            reader.onload = (e) => { preview = e.target.result; };
                            reader.readAsDataURL($event.target.files[0]);
                        }
                    "
                    {{ $attributes }}
                >
            </label>

            @error($name)
                <p class="mt-2 text-sm text-red-600 dark:text-red-400" role="alert">{{ $message }}</p>
            @enderror
        </div>
    </div>
</div>
