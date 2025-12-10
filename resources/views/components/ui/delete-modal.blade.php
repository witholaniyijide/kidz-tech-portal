@props([
    'id' => 'deleteModal',
    'title' => 'Confirm Delete',
    'message' => 'Are you sure you want to delete this item? This action cannot be undone.',
])

<div 
    x-data="{ 
        open: false, 
        formAction: '',
        itemName: '',
        show(action, name = '') {
            this.formAction = action;
            this.itemName = name;
            this.open = true;
        },
        close() {
            this.open = false;
            this.formAction = '';
            this.itemName = '';
        }
    }"
    x-on:open-delete-modal.window="show($event.detail.action, $event.detail.name)"
    x-on:keydown.escape.window="close()"
    id="{{ $id }}"
    class="relative z-50"
>
    <!-- Backdrop -->
    <div 
        x-show="open" 
        x-transition:enter="ease-out duration-300"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="ease-in duration-200"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="fixed inset-0 bg-black/50 backdrop-blur-sm"
        @click="close()"
    ></div>

    <!-- Modal -->
    <div 
        x-show="open"
        x-transition:enter="ease-out duration-300"
        x-transition:enter-start="opacity-0 scale-95"
        x-transition:enter-end="opacity-100 scale-100"
        x-transition:leave="ease-in duration-200"
        x-transition:leave-start="opacity-100 scale-100"
        x-transition:leave-end="opacity-0 scale-95"
        class="fixed inset-0 flex items-center justify-center p-4"
    >
        <div 
            class="bg-white dark:bg-gray-800 rounded-2xl shadow-2xl max-w-md w-full p-6"
            @click.stop
        >
            <!-- Icon -->
            <div class="mx-auto flex items-center justify-center h-14 w-14 rounded-full bg-red-100 dark:bg-red-900/30 mb-4">
                <svg class="h-7 w-7 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                </svg>
            </div>

            <!-- Title -->
            <h3 class="text-lg font-bold text-gray-900 dark:text-white text-center mb-2">
                {{ $title }}
            </h3>

            <!-- Message -->
            <p class="text-sm text-gray-600 dark:text-gray-400 text-center mb-2">
                {{ $message }}
            </p>

            <!-- Item Name (if provided) -->
            <p 
                x-show="itemName" 
                x-text="itemName"
                class="text-sm font-semibold text-gray-900 dark:text-white text-center mb-6 px-4 py-2 bg-gray-100 dark:bg-gray-700 rounded-lg"
            ></p>

            <!-- Actions -->
            <div class="flex space-x-3 mt-6">
                <button 
                    @click="close()" 
                    type="button"
                    class="flex-1 px-4 py-2.5 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 font-semibold rounded-xl hover:bg-gray-200 dark:hover:bg-gray-600 transition-colors"
                >
                    Cancel
                </button>
                <form :action="formAction" method="POST" class="flex-1">
                    @csrf
                    @method('DELETE')
                    <button 
                        type="submit"
                        class="w-full px-4 py-2.5 bg-red-600 text-white font-semibold rounded-xl hover:bg-red-700 transition-colors"
                    >
                        Delete
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
