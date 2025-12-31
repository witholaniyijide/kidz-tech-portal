<x-parent-layout title="Compose Message">
    <div class="space-y-6">
        <!-- Back Button -->
        <div>
            <a href="{{ route('parent.messages.index') }}" class="inline-flex items-center text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white transition-colors">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Back to Messages
            </a>
        </div>

        <!-- Compose Form -->
        <div class="glass-card rounded-2xl p-6">
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white mb-6">Compose Message</h1>

            <form method="POST" action="{{ route('parent.messages.store') }}" class="space-y-6">
                @csrf

                <!-- Recipient (Fixed to Director) -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                        To
                    </label>
                    <div class="w-full px-4 py-3 rounded-xl bg-gray-100 dark:bg-gray-700 border border-gray-200 dark:border-gray-600">
                        <div class="flex items-center">
                            <div class="w-10 h-10 rounded-full bg-gradient-to-br from-sky-500 to-cyan-400 flex items-center justify-center text-white font-bold mr-3">
                                {{ strtoupper(substr($director->name, 0, 1)) }}
                            </div>
                            <div>
                                <p class="font-medium text-gray-900 dark:text-white">{{ $director->name }}</p>
                                <p class="text-sm text-gray-500 dark:text-gray-400">Director</p>
                            </div>
                        </div>
                    </div>
                    <input type="hidden" name="recipient_id" value="{{ $director->id }}">
                    @error('recipient_id')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Subject -->
                <div>
                    <label for="subject" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                        Subject <span class="text-red-500">*</span>
                    </label>
                    <input type="text" id="subject" name="subject" value="{{ old('subject') }}" required
                           class="w-full rounded-xl border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-[#F5A623] focus:border-[#F5A623]"
                           placeholder="Enter subject">
                    @error('subject')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Message Body -->
                <div>
                    <label for="body" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                        Message <span class="text-red-500">*</span>
                    </label>
                    <textarea id="body" name="body" rows="8" required
                              class="w-full rounded-xl border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-[#F5A623] focus:border-[#F5A623]"
                              placeholder="Write your message...">{{ old('body') }}</textarea>
                    @error('body')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Submit Button -->
                <div class="flex justify-end gap-4">
                    <a href="{{ route('parent.messages.index') }}"
                       class="px-6 py-2.5 rounded-xl font-medium text-gray-700 dark:text-gray-300 bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 transition-colors">
                        Cancel
                    </a>
                    <button type="submit" class="btn-parent-primary px-6 py-2.5 rounded-xl font-medium inline-flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                        </svg>
                        Send Message
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-parent-layout>
