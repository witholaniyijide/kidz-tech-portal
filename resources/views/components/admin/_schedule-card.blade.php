@props(['schedule' => null, 'classes' => []])

<x-ui.card
    :empty="empty($classes)"
    emptyMessage="No classes scheduled for today"
    role="region"
    aria-label="Daily Class Schedule"
    x-data="{
        showToast: false,
        toastMessage: '',
        copyToWhatsApp() {
            const scheduleText = this.getScheduleText();
            navigator.clipboard.writeText(scheduleText).then(() => {
                this.toastMessage = 'Schedule copied to clipboard!';
                this.showToast = true;
                setTimeout(() => { this.showToast = false; }, 3000);
            }).catch(() => {
                this.toastMessage = 'Failed to copy. Please try again.';
                this.showToast = true;
                setTimeout(() => { this.showToast = false; }, 3000);
            });
        },
        getScheduleText() {
            let text = '*Coding Classes Scheduled for Today – {{ now()->format('l, F j, Y') }}*\n\n';
            const items = document.querySelectorAll('#schedule-list li');
            items.forEach((item, index) => {
                text += `${index + 1}. ${item.textContent.trim()}\n`;
            });
            text += '\n📌 All classes are in Nigerian Time.\n';
            text += '📌 Be punctual to classes.\n';
            text += '📌 Stay professional in your delivery.\n';
            text += '📌 Give at least 12-hours notice if you won\\'t be making it to your class.\n\n';
            text += '*Let\\'s go raise the next generation of coders! 💪*';
            return text;
        }
    }"
>
    <x-slot:emptyAction>
        <a
            href="{{ route('schedule.create') }}"
            class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-teal-500 to-cyan-500 text-white text-base font-semibold rounded-xl shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-teal-500"
            aria-label="Create new schedule"
        >
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>
            Create Schedule
        </a>
    </x-slot:emptyAction>

    @if(!empty($classes))
    <div class="p-6">
        <h2 class="text-2xl font-bold text-gray-800 dark:text-white mb-2 font-inter">
            Coding Classes Scheduled for Today – {{ now()->format('l, F j, Y') }}
        </h2>

        <ol id="schedule-list" class="mt-6 space-y-3 list-decimal" role="list" aria-label="Today's class schedule">
            @foreach($classes as $index => $class)
            <li class="bg-{{ $index % 2 == 0 ? 'teal' : 'cyan' }}-50 dark:bg-{{ $index % 2 == 0 ? 'teal' : 'cyan' }}-900/20 border-l-4 border-{{ $index % 2 == 0 ? 'teal' : 'cyan' }}-500 p-4 rounded-lg ml-6">
                <span class="text-gray-800 dark:text-gray-200 font-inter text-base">
                    <span class="font-bold text-{{ $index % 2 == 0 ? 'teal' : 'cyan' }}-600 dark:text-{{ $index % 2 == 0 ? 'teal' : 'cyan' }}-400">{{ $index + 1 }}.</span>
                    {{ $class['time'] ?? '' }}: <span class="font-semibold">{{ $class['name'] ?? '' }}</span> with {{ $class['tutor'] ?? '' }} ({{ $class['students'] ?? 0 }} students)
                </span>
            </li>
            @endforeach
        </ol>

        {{-- Footer Notes --}}
        <div class="mt-6 p-4 bg-gradient-to-r from-teal-100 to-cyan-100 dark:from-teal-900/30 dark:to-cyan-900/30 rounded-xl border border-teal-200 dark:border-teal-700" role="note" aria-label="Important reminders">
            <p class="text-sm text-gray-700 dark:text-gray-300 space-y-1 font-inter">
                <span class="block">📌 All classes are in Nigerian Time.</span>
                <span class="block">📌 Be punctual to classes.</span>
                <span class="block">📌 Stay professional in your delivery.</span>
                <span class="block">📌 Give at least 12-hours notice if you won't be making it to your class.</span>
                <span class="block mt-2 font-semibold italic text-teal-700 dark:text-teal-300">
                    Let's go raise the next generation of coders! 💪
                </span>
            </p>
        </div>

        {{-- Action Buttons --}}
        <div class="mt-6 flex flex-wrap gap-3" role="group" aria-label="Schedule actions">
            <button
                type="button"
                class="px-6 py-3 bg-gradient-to-r from-teal-500 to-cyan-500 text-white font-semibold rounded-xl shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-teal-500 focus-visible:ring-2 focus-visible:ring-teal-500"
                aria-label="Post schedule"
            >
                📝 Post Schedule
            </button>
            <button
                type="button"
                @click="copyToWhatsApp"
                class="px-6 py-3 bg-white dark:bg-slate-700 text-gray-800 dark:text-white font-semibold rounded-xl shadow-md hover:shadow-lg transform hover:-translate-y-0.5 transition-all duration-300 border border-gray-200 dark:border-slate-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-cyan-500 focus-visible:ring-2 focus-visible:ring-cyan-500"
                aria-label="Copy schedule for WhatsApp"
            >
                📋 Copy for WhatsApp
            </button>
            <button
                type="button"
                class="px-6 py-3 text-teal-600 dark:text-teal-400 font-semibold rounded-xl hover:bg-teal-50 dark:hover:bg-teal-900/20 transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-teal-500 focus-visible:ring-2 focus-visible:ring-teal-500"
                aria-label="Generate tomorrow's schedule"
            >
                ⏭️ Generate Tomorrow's Schedule
            </button>
        </div>
    </div>

    {{-- Toast Notification --}}
    <div
        x-show="showToast"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 transform translate-y-2"
        x-transition:enter-end="opacity-100 transform translate-y-0"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100 transform translate-y-0"
        x-transition:leave-end="opacity-0 transform translate-y-2"
        class="fixed bottom-4 right-4 z-50 px-6 py-4 bg-gray-900 text-white rounded-xl shadow-2xl"
        role="alert"
        aria-live="polite"
        aria-atomic="true"
        style="display: none;"
    >
        <p class="font-inter text-sm" x-text="toastMessage"></p>
    </div>
    @endif
</x-ui.card>
