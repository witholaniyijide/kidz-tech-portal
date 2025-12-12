<x-app-layout>
    <x-slot name="header">
        {{ $replyTo ? __('Reply to Message') : __('Compose Message') }}
    </x-slot>
    <x-slot name="title">{{ __('Compose') }}</x-slot>

    <div class="min-h-screen bg-gradient-to-br from-blue-50 via-purple-50 to-pink-50 dark:from-gray-900 dark:via-gray-800 dark:to-gray-900 py-8">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">

            <!-- Action Button -->
            <div class="flex justify-end mb-4">
                <a href="{{ route('director.messages.index') }}"
                   class="inline-flex items-center px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white font-semibold rounded-lg transition-all">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    Back to Inbox
                </a>
            </div>

            @if($replyTo)
                <!-- Original Message -->
                <x-ui.glass-card class="mb-6">
                    <h3 class="text-sm font-semibold text-gray-500 dark:text-gray-400 mb-3">Replying to:</h3>
                    <div class="p-4 bg-gray-50 dark:bg-gray-700/50 rounded-lg">
                        <div class="flex items-center justify-between mb-2">
                            <span class="font-semibold text-gray-900 dark:text-white">{{ $replyTo->sender->name }}</span>
                            <span class="text-xs text-gray-500">{{ $replyTo->created_at->format('M d, Y h:i A') }}</span>
                        </div>
                        <p class="font-medium text-gray-800 dark:text-gray-200 mb-2">{{ $replyTo->subject }}</p>
                        <p class="text-sm text-gray-600 dark:text-gray-400">{{ $replyTo->body }}</p>
                    </div>
                </x-ui.glass-card>
            @endif

            <x-ui.glass-card>
                <form method="POST" action="{{ route('director.messages.store') }}" class="space-y-6">
                    @csrf

                    @if($replyTo)
                        <input type="hidden" name="parent_id" value="{{ $replyTo->id }}">
                        <input type="hidden" name="recipient_id" value="{{ $replyTo->sender_id }}">
                        <input type="hidden" name="student_id" value="{{ $replyTo->student_id }}">
                        <input type="hidden" name="subject" value="Re: {{ $replyTo->subject }}">
                    @else
                        <!-- Recipient -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">To (Parent) *</label>
                            <select name="recipient_id" required 
                                    class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                <option value="">Select a parent...</option>
                                @foreach($parents as $parent)
                                    <option value="{{ $parent->id }}" {{ old('recipient_id') == $parent->id ? 'selected' : '' }}>
                                        {{ $parent->name }} ({{ $parent->email }})
                                    </option>
                                @endforeach
                            </select>
                            @error('recipient_id')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Related Student (Optional) -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Regarding Student (Optional)</label>
                            <select name="student_id" 
                                    class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                <option value="">Select a student...</option>
                                @foreach($students as $student)
                                    <option value="{{ $student->id }}" {{ old('student_id') == $student->id ? 'selected' : '' }}>
                                        {{ $student->first_name }} {{ $student->last_name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Subject -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Subject *</label>
                            <input type="text" name="subject" value="{{ old('subject') }}" required
                                   placeholder="Message subject..."
                                   class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            @error('subject')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    @endif

                    <!-- Message Body -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Message *</label>
                        <textarea name="body" rows="8" required
                                  placeholder="Type your message here..."
                                  class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 focus:ring-blue-500">{{ old('body') }}</textarea>
                        @error('body')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        <p class="mt-1 text-xs text-gray-500">Maximum 5000 characters</p>
                    </div>

                    <!-- Actions -->
                    <div class="flex justify-end space-x-3">
                        <a href="{{ route('director.messages.index') }}" 
                           class="px-4 py-2 bg-gray-200 dark:bg-gray-600 text-gray-700 dark:text-gray-200 rounded-lg hover:bg-gray-300 dark:hover:bg-gray-500 transition-colors">
                            Cancel
                        </a>
                        <button type="submit" 
                                class="px-6 py-2 bg-gradient-to-r from-blue-600 to-purple-600 text-white font-semibold rounded-lg hover:from-blue-700 hover:to-purple-700 transition-all">
                            <svg class="w-5 h-5 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                            </svg>
                            Send Message
                        </button>
                    </div>
                </form>
            </x-ui.glass-card>

        </div>
    </div>
</x-app-layout>
