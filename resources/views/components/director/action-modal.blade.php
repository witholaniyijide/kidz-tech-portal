@props([
    'name',
    'title' => 'Confirm Action',
    'confirmText' => 'Confirm',
    'cancelText' => 'Cancel',
    'confirmClass' => 'bg-gradient-to-r from-blue-500 to-purple-500',
    'showCommentField' => false,
    'commentRequired' => false,
    'commentLabel' => 'Comment',
    'commentPlaceholder' => 'Add your comment...',
    'showSignature' => false,
    'signatureLabel' => 'Signature',
])

<div
    x-data="{ show: false }"
    x-on:open-modal.window="$event.detail === '{{ $name }}' ? show = true : null"
    x-on:close-modal.window="$event.detail === '{{ $name }}' ? show = false : null"
    x-on:keydown.escape.window="show = false"
    x-show="show"
    class="fixed inset-0 overflow-y-auto px-4 py-6 sm:px-0 z-50"
    style="display: none;"
>
    <!-- Background overlay -->
    <div
        x-show="show"
        x-transition:enter="ease-out duration-300"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="ease-in duration-200"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="fixed inset-0 transform transition-all"
        x-on:click="show = false"
    >
        <div class="absolute inset-0 bg-gray-900 opacity-75"></div>
    </div>

    <!-- Modal container -->
    <div
        x-show="show"
        x-transition:enter="ease-out duration-300"
        x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
        x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
        x-transition:leave="ease-in duration-200"
        x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
        x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
        class="mb-6 backdrop-blur-md bg-white/90 dark:bg-gray-900/90 border border-white/20 rounded-2xl shadow-2xl transform transition-all sm:w-full sm:max-w-2xl sm:mx-auto"
        x-trap.noscroll.inert="show"
    >
        <!-- Modal header -->
        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
            <div class="flex items-center justify-between">
                <h3 class="text-lg font-bold text-gray-900 dark:text-white">
                    {{ $title }}
                </h3>
                <button
                    type="button"
                    x-on:click="show = false"
                    class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 transition-colors"
                >
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>

        <!-- Modal body -->
        <div class="px-6 py-4">
            {{ $slot }}

            @if($showCommentField)
            <div class="mt-4">
                <label for="{{ $name }}_comment" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    {{ $commentLabel }} @if($commentRequired)<span class="text-red-500">*</span>@endif
                </label>
                <textarea
                    id="{{ $name }}_comment"
                    name="director_comment"
                    rows="4"
                    maxlength="2000"
                    @if($commentRequired) required @endif
                    placeholder="{{ $commentPlaceholder }}"
                    class="w-full px-4 py-3 bg-white/50 dark:bg-gray-800/50 border border-gray-300 dark:border-gray-600 rounded-xl text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent resize-none"
                ></textarea>
                <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                    @if($commentRequired) Required @else Optional @endif
                </p>
            </div>
            @endif

            @if($showSignature)
            <div class="mt-4">
                <label for="{{ $name }}_signature" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    {{ $signatureLabel }} <span class="text-gray-500">(Optional)</span>
                </label>
                <input
                    type="file"
                    id="{{ $name }}_signature"
                    name="director_signature"
                    accept=".png,.jpg,.jpeg,.webp"
                    class="w-full px-4 py-3 bg-white/50 dark:bg-gray-800/50 border border-gray-300 dark:border-gray-600 rounded-xl text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                >
                <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                    Accepts PNG, JPG, or WebP format
                </p>
            </div>
            @endif
        </div>

        <!-- Modal footer -->
        <div class="px-6 py-4 bg-gray-50 dark:bg-gray-800/50 border-t border-gray-200 dark:border-gray-700 rounded-b-2xl flex items-center justify-end gap-3">
            <button
                type="button"
                x-on:click="show = false"
                class="px-6 py-3 bg-white/20 dark:bg-gray-700/30 text-gray-700 dark:text-gray-300 rounded-xl border border-white/10 hover:bg-white/30 dark:hover:bg-gray-700/50 transition-colors font-medium"
            >
                {{ $cancelText }}
            </button>
            <button
                type="submit"
                class="px-6 py-3 {{ $confirmClass }} text-white rounded-xl hover:shadow-lg transform hover:-translate-y-1 transition-all font-bold flex items-center"
            >
                {{ $confirmText }}
            </button>
        </div>
    </div>
</div>

@push('scripts')
<script src="//unpkg.com/alpinejs" defer></script>
@endpush
