@props(['schedule' => null, 'classes' => []])

@if(empty($classes))
<div class="glass-card rounded-2xl p-6 shadow-xl text-center" role="region" aria-label="Daily Class Schedule" style="animation-delay: 0.5s;">
    <div class="py-12">
        <div class="w-20 h-20 mx-auto mb-6 rounded-2xl bg-gradient-to-br from-[#423A8E] to-[#00CCCD] flex items-center justify-center shadow-lg">
            <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
            </svg>
        </div>
        <h3 class="text-lg font-semibold text-gray-700 dark:text-gray-300 mb-2">No Classes Scheduled</h3>
        <p class="text-gray-500 dark:text-gray-400 mb-6">There are no classes scheduled for today.</p>
        <a
            href="{{ route('schedule.generate') }}"
            class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-[#423A8E] to-[#00CCCD] text-white text-base font-semibold rounded-xl shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#423A8E]"
            aria-label="Generate today's schedule"
        >
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>
            Generate Today's Schedule
        </a>
    </div>
</div>
@else
<div
    class="glass-card rounded-2xl p-6 shadow-xl"
    role="region"
    aria-label="Daily Class Schedule"
    style="animation-delay: 0.5s;"
    x-data="{
        showToast: false,
        toastMessage: '',
        async copyToWhatsApp() {
            const scheduleText = this.getScheduleText();

            // Try modern Clipboard API first (requires HTTPS)
            if (navigator.clipboard && window.isSecureContext) {
                try {
                    await navigator.clipboard.writeText(scheduleText);
                    this.toastMessage = 'Schedule copied to clipboard!';
                    this.showToast = true;
                    setTimeout(() => { this.showToast = false; }, 3000);
                    return;
                } catch (error) {
                    console.error('Clipboard API failed:', error);
                }
            }

            // Fallback for HTTP sites or older browsers
            try {
                const textArea = document.createElement('textarea');
                textArea.value = scheduleText;
                textArea.style.position = 'fixed';
                textArea.style.left = '-9999px';
                textArea.style.top = '-9999px';
                document.body.appendChild(textArea);
                textArea.focus();
                textArea.select();

                const successful = document.execCommand('copy');
                document.body.removeChild(textArea);

                if (successful) {
                    this.toastMessage = 'Schedule copied to clipboard!';
                } else {
                    this.toastMessage = 'Failed to copy. Please try again.';
                }
                this.showToast = true;
                setTimeout(() => { this.showToast = false; }, 3000);
            } catch (error) {
                console.error('Fallback copy failed:', error);
                this.toastMessage = 'Failed to copy. Please try again.';
                this.showToast = true;
                setTimeout(() => { this.showToast = false; }, 3000);
            }
        },
        getScheduleText() {
            let text = '*Coding Classes Scheduled for Today – {{ now()->format('l, F j, Y') }}*\\n\\n';
            const items = document.querySelectorAll('#schedule-list li');
            items.forEach((item, index) => {
                text += `${index + 1}. ${item.textContent.trim()}\\n`;
            });
            text += '\\n📌 All classes are in Nigerian Time.\\n';
            text += '📌 Be punctual to classes.\\n';
            text += '📌 Stay professional in your delivery.\\n';
            text += '📌 Give at least 12-hours notice if you won\\\\'t be making it to your class.\\n\\n';
            text += '*Let\\\\'s go raise the next generation of coders! 💪*';
            return text;
        }
    }"
>
    <div class="mb-6">
        <h3 class="text-xl font-bold text-gray-900 dark:text-white flex items-center">
            <svg class="w-5 h-5 mr-2 text-[#423A8E]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
            </svg>
            Coding Classes Scheduled for Today
        </h3>
        <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">{{ now()->format('l, F j, Y') }}</p>
    </div>

    <ol id="schedule-list" class="space-y-3 mb-6" role="list" aria-label="Today's class schedule">
        @foreach($classes as $index => $class)
        <li class="flex items-start p-4 rounded-lg bg-gradient-to-r from-[#423A8E]/5 to-[#00CCCD]/5 dark:from-[#423A8E]/20 dark:to-[#00CCCD]/20 border-l-4 border-[#423A8E]">
            <div class="flex-shrink-0 w-8 h-8 rounded-full bg-gradient-to-br from-[#423A8E] to-[#00CCCD] flex items-center justify-center text-white font-bold text-sm">
                {{ $index + 1 }}
            </div>
            <div class="ml-4 flex-1">
                <div class="text-gray-900 dark:text-gray-100 font-semibold">
                    @php
                        try {
                            $formattedTime = \Carbon\Carbon::parse($class['time'])->format('g:i A');
                        } catch (\Exception $e) {
                            $formattedTime = $class['time'] ?? '';
                        }
                    @endphp
                    {{ $formattedTime }}
                </div>
                <div class="text-gray-700 dark:text-gray-300 mt-1">
                    <span class="font-medium">{{ $class['name'] ?? '' }}</span>
                </div>
                <div class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                    with {{ $class['tutor'] ?? '' }} • {{ $class['students'] ?? 0 }} students
                </div>
            </div>
        </li>
        @endforeach
    </ol>

    {{-- Footer Notes --}}
    <div class="p-4 rounded-xl bg-gradient-to-r from-[#423A8E]/5 to-[#00CCCD]/5 dark:from-[#423A8E]/30 dark:to-[#00CCCD]/30 border border-[#423A8E]/20 dark:border-[#423A8E] mb-6" role="note" aria-label="Important reminders">
        <div class="space-y-2 text-sm text-gray-700 dark:text-gray-300">
            <p class="flex items-start">
                <span class="mr-2">📌</span>
                <span>All classes are in Nigerian Time.</span>
            </p>
            <p class="flex items-start">
                <span class="mr-2">📌</span>
                <span>Be punctual to classes.</span>
            </p>
            <p class="flex items-start">
                <span class="mr-2">📌</span>
                <span>Stay professional in your delivery.</span>
            </p>
            <p class="flex items-start">
                <span class="mr-2">📌</span>
                <span>Give at least 12-hours notice if you won't be making it to your class.</span>
            </p>
        </div>
        <p class="mt-3 font-semibold italic text-transparent bg-clip-text bg-gradient-to-r from-[#423A8E] to-[#00CCCD]">
            Let's go raise the next generation of coders! 💪
        </p>
    </div>

    {{-- Action Buttons --}}
    <div class="flex flex-wrap gap-3" role="group" aria-label="Schedule actions">
        <form method="POST" action="{{ route('schedule.post') }}" class="inline">
            @csrf
            <button
                type="submit"
                class="px-6 py-3 bg-gradient-to-r from-[#423A8E] to-[#00CCCD] text-white font-semibold rounded-xl shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#423A8E] focus-visible:ring-2 focus-visible:ring-[#423A8E]"
                aria-label="Post schedule to WhatsApp groups"
            >
                📝 Post Schedule
            </button>
        </form>
        <button
            type="button"
            @click="copyToWhatsApp"
            class="px-6 py-3 bg-white dark:bg-slate-700 text-gray-800 dark:text-white font-semibold rounded-xl shadow-md hover:shadow-lg transform hover:-translate-y-0.5 transition-all duration-300 border border-gray-200 dark:border-slate-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#00CCCD] focus-visible:ring-2 focus-visible:ring-[#00CCCD]"
            aria-label="Copy schedule for WhatsApp"
        >
            📋 Copy for WhatsApp
        </button>
        <a
            href="{{ route('schedule.generate') }}"
            class="px-6 py-3 text-[#423A8E] dark:text-[#00CCCD] font-semibold rounded-xl hover:bg-[#423A8E]/5 dark:hover:bg-[#423A8E]/20 transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#423A8E] focus-visible:ring-2 focus-visible:ring-[#423A8E] inline-flex items-center"
            aria-label="Generate tomorrow's schedule"
        >
            ⏭️ Generate Tomorrow's Schedule
        </a>
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
        <p class="text-sm" x-text="toastMessage"></p>
    </div>
</div>
@endif
