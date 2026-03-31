<x-app-layout>
    <x-slot name="header">{{ __('Daily Schedule') }}</x-slot>
    <x-slot name="title">{{ __('Admin - Schedules') }}</x-slot>

    <div class="min-h-screen bg-gradient-to-br from-[#423A8E]/5 via-[#00CCCD]/5 to-blue-50 dark:from-slate-900 dark:via-slate-800 dark:to-slate-900 py-8 relative overflow-hidden" x-data="scheduleManager()">
        <div class="absolute top-0 left-0 w-72 h-72 bg-[#423A8E]/30 dark:bg-[#423A8E]/30 rounded-full mix-blend-multiply filter blur-xl opacity-70 animate-float"></div>
        <div class="absolute top-0 right-0 w-72 h-72 bg-[#00CCCD]/30 dark:bg-[#00CCCD]/40 rounded-full mix-blend-multiply filter blur-xl opacity-70 animate-float" style="animation-delay: 2s;"></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            @if(session('success'))
                <div class="mb-6 bg-emerald-100 dark:bg-emerald-900/30 border border-emerald-400 text-emerald-800 dark:text-emerald-400 px-6 py-4 rounded-xl">
                    {{ session('success') }}
                </div>
            @endif
            @if(session('error'))
                <div class="mb-6 bg-red-100 dark:bg-red-900/30 border border-red-400 text-red-800 dark:text-red-400 px-6 py-4 rounded-xl">
                    {{ session('error') }}
                </div>
            @endif

            {{-- Header --}}
            <div class="flex flex-wrap items-center justify-between gap-4 mb-8">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Daily Class Schedule</h1>
                    <p class="text-gray-600 dark:text-gray-400 mt-1">{{ $selectedDate->format('l, F j, Y') }}</p>
                </div>
                <div class="flex gap-2">
                    @if($todaySchedule)
                        <a href="{{ route('admin.schedules.edit', $todaySchedule) }}" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-[#423A8E] to-[#00CCCD] text-white font-medium rounded-xl hover:shadow-lg transform hover:-translate-y-0.5 transition-all">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                            </svg>
                            Edit Schedule
                        </a>
                    @else
                        <a href="{{ route('admin.schedules.create', ['date' => $selectedDate->toDateString()]) }}" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-[#423A8E] to-[#00CCCD] text-white font-medium rounded-xl hover:shadow-lg transform hover:-translate-y-0.5 transition-all">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                            </svg>
                            Add Schedule
                        </a>
                    @endif
                    <form action="{{ route('admin.schedules.generate') }}" method="POST" class="inline">
                        @csrf
                        <input type="hidden" name="date" value="{{ $selectedDate->toDateString() }}">
                        <button type="submit" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white font-medium rounded-xl hover:bg-blue-700 transition-colors">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                            </svg>
                            Auto-Generate
                        </button>
                    </form>
                </div>
            </div>

            {{-- Date Navigator --}}
            <div class="bg-white/60 dark:bg-gray-800/60 backdrop-blur-md border border-white/20 rounded-2xl p-4 shadow mb-6">
                <div class="flex items-center justify-between">
                    <a href="{{ route('admin.schedules.index', ['date' => $selectedDate->copy()->subDay()->toDateString()]) }}"
                       class="p-2 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg transition-colors">
                        <svg class="w-5 h-5 text-gray-600 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                        </svg>
                    </a>

                    <div class="flex items-center gap-4">
                        <form method="GET" class="flex items-center gap-2">
                            <input type="date" name="date" value="{{ $selectedDate->toDateString() }}"
                                   onchange="this.form.submit()"
                                   class="px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg focus:ring-2 focus:ring-[#423A8E]">
                        </form>

                        @if(!$selectedDate->isToday())
                            <a href="{{ route('admin.schedules.index') }}" class="px-3 py-1 text-sm bg-[#423A8E]/10 text-[#423A8E] dark:bg-[#423A8E]/30/30 dark:text-[#00CCCD] rounded-full hover:bg-[#423A8E]/20 transition-colors">
                                Today
                            </a>
                        @else
                            <span class="px-3 py-1 text-sm bg-emerald-100 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-400 rounded-full">
                                Today
                            </span>
                        @endif
                    </div>

                    <a href="{{ route('admin.schedules.index', ['date' => $selectedDate->copy()->addDay()->toDateString()]) }}"
                       class="p-2 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg transition-colors">
                        <svg class="w-5 h-5 text-gray-600 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </a>
                </div>
            </div>

            {{-- Post Schedule & WhatsApp --}}
            @if(count($classes) > 0)
                <div class="bg-white/60 dark:bg-gray-800/60 backdrop-blur-md border border-white/20 rounded-2xl p-4 shadow mb-6">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            @if($schedulePosted)
                                <span class="inline-flex items-center px-3 py-1 bg-emerald-100 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-400 rounded-full text-sm">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                    </svg>
                                    Posted
                                </span>
                                <span class="text-sm text-gray-500">{{ $todaySchedule?->posted_at?->format('M j \a\t g:i A') }}</span>
                            @else
                                <span class="inline-flex items-center px-3 py-1 bg-amber-100 text-amber-700 dark:bg-amber-900/30 dark:text-amber-400 rounded-full text-sm">
                                    Not Posted Yet
                                </span>
                            @endif
                        </div>
                        <div class="flex gap-2">
                            <button @click="copyToWhatsApp()" class="inline-flex items-center px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors text-sm">
                                <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
                                </svg>
                                Copy for WhatsApp
                            </button>
                            @if(!$schedulePosted)
                                <form action="{{ route('admin.schedules.post') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="date" value="{{ $selectedDate->toDateString() }}">
                                    <button type="submit" class="inline-flex items-center px-4 py-2 bg-[#423A8E] text-white rounded-lg hover:bg-[#423A8E] transition-colors text-sm">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                        Post Schedule
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                </div>
            @endif

            {{-- Today's Schedule --}}
            <div class="bg-white/60 dark:bg-gray-800/60 backdrop-blur-md border border-white/20 rounded-2xl shadow overflow-hidden mb-8">
                <div class="px-6 py-4 bg-gradient-to-r from-[#423A8E] to-[#00CCCD] text-white flex justify-between items-center">
                    <div>
                        <h3 class="text-lg font-semibold">
                            {{ $selectedDate->format('l, M j') }} Classes
                            ({{ count($classes) }}{{ count($rescheduledClasses) > 0 ? ' + ' . count($rescheduledClasses) . ' rescheduled' : '' }})
                        </h3>
                        @if($inheritedFromWeekly ?? false)
                            <p class="text-xs text-white/70">🔄 Inherited from weekly repeat schedule</p>
                        @endif
                    </div>
                    @if($todaySchedule)
                        <a href="{{ route('admin.schedules.edit', $todaySchedule) }}" class="text-white/80 hover:text-white text-sm">
                            Edit Schedule →
                        </a>
                    @endif
                </div>

                @if(empty($classes))
                    <div class="p-12 text-center">
                        <div class="text-6xl mb-4">📅</div>
                        <h3 class="text-xl font-semibold text-gray-700 dark:text-gray-300 mb-2">No Classes Scheduled</h3>
                        <p class="text-gray-500 dark:text-gray-400 mb-4">Add entries manually or auto-generate from student schedules</p>
                        <div class="flex justify-center gap-3">
                            <a href="{{ route('admin.schedules.create', ['date' => $selectedDate->toDateString()]) }}" class="inline-flex items-center px-4 py-2 bg-[#423A8E] text-white rounded-lg hover:bg-[#423A8E]">
                                Add Schedule
                            </a>
                            <form action="{{ route('admin.schedules.generate') }}" method="POST">
                                @csrf
                                <input type="hidden" name="date" value="{{ $selectedDate->toDateString() }}">
                                <button type="submit" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                                    Auto-Generate
                                </button>
                            </form>
                        </div>
                    </div>
                @else
                    <div class="divide-y divide-gray-200 dark:divide-gray-700">
                        @foreach($classes as $index => $class)
                            <div class="p-4 hover:bg-gray-50 dark:hover:bg-gray-700/30 transition-colors">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center gap-4">
                                        <div class="w-8 h-8 bg-[#423A8E]/10 dark:bg-[#423A8E]/30/30 text-[#423A8E] dark:text-[#00CCCD] rounded-full flex items-center justify-center font-bold text-sm">
                                            {{ $index + 1 }}
                                        </div>
                                        <div>
                                            <div class="font-semibold text-gray-900 dark:text-white">
                                                {{ $class['student_name'] ?? 'Unknown Student' }}
                                            </div>
                                            <div class="text-sm text-gray-500 dark:text-gray-400">
                                                Tutor: {{ $class['tutor_name'] ?? 'Unknown Tutor' }}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="flex items-center gap-4">
                                        <div class="text-right">
                                            <div class="font-medium text-gray-900 dark:text-white">
                                                @php
                                                    try {
                                                        $time = \Carbon\Carbon::parse($class['time'] ?? '00:00')->format('g:i A');
                                                    } catch (\Exception $e) {
                                                        $time = $class['time'] ?? '00:00';
                                                    }
                                                @endphp
                                                {{ $time }}
                                            </div>
                                            @if(!empty($class['class_link']))
                                                <a href="{{ $class['class_link'] }}" target="_blank" class="text-xs text-[#423A8E] hover:underline">Join Class</a>
                                            @endif
                                        </div>
                                        <span class="px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-700 dark:bg-gray-700 dark:text-gray-300">
                                            Scheduled
                                        </span>
                                    </div>
                                </div>
                                @if(!empty($class['notes']))
                                    <div class="mt-2 ml-12 text-sm text-gray-500 dark:text-gray-400">
                                        {{ $class['notes'] }}
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>

                    {{-- Rescheduled Classes Section --}}
                    @if(count($rescheduledClasses) > 0)
                        <div class="px-6 py-4 bg-amber-50/50 dark:bg-amber-900/10 border-t-2 border-amber-500">
                            <h4 class="text-md font-semibold text-amber-800 dark:text-amber-300 mb-3 flex items-center">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                                Rescheduled Classes ({{ count($rescheduledClasses) }})
                            </h4>
                            <div class="space-y-2">
                                @foreach($rescheduledClasses as $index => $class)
                                    <div class="p-3 bg-white dark:bg-gray-800/50 rounded-lg border-l-4 border-amber-500">
                                        <div class="flex items-center justify-between">
                                            <div class="flex items-center gap-3">
                                                <div class="w-7 h-7 bg-amber-100 dark:bg-amber-900/30 text-amber-700 dark:text-amber-400 rounded-full flex items-center justify-center font-bold text-xs">
                                                    {{ $index + 1 }}
                                                </div>
                                                <div>
                                                    <div class="font-semibold text-gray-900 dark:text-white text-sm">
                                                        {{ $class['student_name'] ?? 'Unknown Student' }}
                                                    </div>
                                                    <div class="text-xs text-gray-500 dark:text-gray-400">
                                                        Tutor: {{ $class['tutor_name'] ?? 'Unknown Tutor' }}
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="flex items-center gap-3">
                                                <div class="text-right">
                                                    <div class="font-medium text-gray-900 dark:text-white text-sm">
                                                        @php
                                                            try {
                                                                $time = \Carbon\Carbon::parse($class['time'] ?? '00:00')->format('g:i A');
                                                            } catch (\Exception $e) {
                                                                $time = $class['time'] ?? '00:00';
                                                            }
                                                        @endphp
                                                        {{ $time }}
                                                    </div>
                                                    @if(!empty($class['original_date']))
                                                        <div class="text-xs text-amber-600 dark:text-amber-400">
                                                            @php
                                                                try {
                                                                    $originalDate = \Carbon\Carbon::parse($class['original_date'])->format('M j');
                                                                } catch (\Exception $e) {
                                                                    $originalDate = $class['original_date'];
                                                                }
                                                            @endphp
                                                            Was {{ $originalDate }}
                                                        </div>
                                                    @endif
                                                    @if(!empty($class['class_link']))
                                                        <a href="{{ $class['class_link'] }}" target="_blank" class="text-xs text-[#423A8E] hover:underline">Join Class</a>
                                                    @endif
                                                </div>
                                                <span class="px-2 py-1 text-xs font-semibold rounded-full bg-amber-100 text-amber-700 dark:bg-amber-900/30 dark:text-amber-400">
                                                    Rescheduled
                                                </span>
                                            </div>
                                        </div>
                                        @if(!empty($class['notes']))
                                            <div class="mt-2 ml-10 text-xs text-gray-500 dark:text-gray-400">
                                                {{ $class['notes'] }}
                                            </div>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    {{-- Delete Entire Schedule --}}
                    @if($todaySchedule)
                        <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800/50 flex justify-between items-center">
                            <span class="text-sm text-gray-500">
                                {{ $todaySchedule->footer_note ?? '' }}
                            </span>
                            <form action="{{ route('admin.schedules.destroy', $todaySchedule) }}" method="POST" onsubmit="return confirm('Delete the entire schedule for {{ $selectedDate->format('l, M j') }}?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-sm text-red-600 hover:text-red-700 hover:underline">
                                    Delete Schedule
                                </button>
                            </form>
                        </div>
                    @endif
                @endif
            </div>

            {{-- Weekly Overview --}}
            <div class="bg-white/60 dark:bg-gray-800/60 backdrop-blur-md border border-white/20 rounded-2xl shadow overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 flex justify-between items-center">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Week Overview</h3>
                    <span class="text-sm text-gray-500">{{ $weekStart->format('M j') }} - {{ $weekEnd->format('M j, Y') }}</span>
                </div>
                <div class="grid grid-cols-7 divide-x divide-gray-200 dark:divide-gray-700">
                    @php
                        $days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
                    @endphp
                    @foreach($days as $dayIndex => $day)
                        @php
                            $daySchedule = $weeklySchedules[$day] ?? null;
                            $dayClasses = $daySchedule ? ($daySchedule->classes ?? []) : [];
                            $dayDate = $weekStart->copy()->addDays($dayIndex);
                        @endphp
                        <a href="{{ route('admin.schedules.index', ['date' => $dayDate->toDateString()]) }}"
                           class="p-3 {{ $dayDate->isToday() ? 'bg-[#423A8E]/5 dark:bg-[#423A8E]/30/20' : 'hover:bg-gray-50 dark:hover:bg-gray-700/30' }} transition-colors">
                            <div class="text-center mb-2">
                                <div class="text-xs font-medium text-gray-500 dark:text-gray-400">{{ substr($day, 0, 3) }}</div>
                                <div class="text-lg font-bold {{ $dayDate->isToday() ? 'text-[#423A8E]' : 'text-gray-900 dark:text-white' }}">{{ $dayDate->format('j') }}</div>
                            </div>
                            <div class="text-center">
                                <span class="inline-flex items-center justify-center w-6 h-6 text-xs font-semibold rounded-full {{ count($dayClasses) > 0 ? 'bg-[#423A8E]/10 text-[#423A8E] dark:bg-[#423A8E]/30/30 dark:text-[#00CCCD]' : 'bg-gray-100 text-gray-500 dark:bg-gray-700 dark:text-gray-400' }}">
                                    {{ count($dayClasses) }}
                                </span>
                            </div>
                            @if(count($dayClasses) > 0)
                                <div class="mt-2 space-y-1">
                                    @foreach(array_slice($dayClasses, 0, 3) as $c)
                                        <div class="text-xs text-gray-600 dark:text-gray-400 truncate" title="{{ $c['student_name'] ?? 'Unknown' }}">
                                            @php
                                                try {
                                                    $classTime = \Carbon\Carbon::parse($c['time'] ?? '00:00')->format('g:i');
                                                } catch (\Exception $e) {
                                                    $classTime = $c['time'] ?? '?';
                                                }
                                            @endphp
                                            {{ $classTime }} - {{ explode(' ', $c['student_name'] ?? '?')[0] }}
                                        </div>
                                    @endforeach
                                    @if(count($dayClasses) > 3)
                                        <div class="text-xs text-gray-400">+{{ count($dayClasses) - 3 }} more</div>
                                    @endif
                                </div>
                            @endif
                        </a>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    {{-- Copied Toast --}}
    <div x-show="copied" x-transition class="fixed bottom-4 right-4 bg-emerald-600 text-white px-4 py-2 rounded-lg shadow-lg" style="display: none;">
        ✓ Copied to clipboard!
    </div>

    @push('scripts')
    <script>
        function scheduleManager() {
            return {
                copied: false,
                async copyToWhatsApp() {
                    try {
                        const response = await fetch('{{ route("admin.schedules.whatsapp", ["date" => $selectedDate->toDateString()]) }}');
                        const data = await response.json();
                        const text = data.format;

                        // Try modern Clipboard API first (requires HTTPS)
                        if (navigator.clipboard && window.isSecureContext) {
                            try {
                                await navigator.clipboard.writeText(text);
                                this.copied = true;
                                setTimeout(() => this.copied = false, 2000);
                                return;
                            } catch (error) {
                                console.error('Clipboard API failed:', error);
                            }
                        }

                        // Fallback for HTTP sites or older browsers
                        const textArea = document.createElement('textarea');
                        textArea.value = text;
                        textArea.style.position = 'fixed';
                        textArea.style.left = '-9999px';
                        textArea.style.top = '-9999px';
                        document.body.appendChild(textArea);
                        textArea.focus();
                        textArea.select();

                        const successful = document.execCommand('copy');
                        document.body.removeChild(textArea);

                        if (successful) {
                            this.copied = true;
                            setTimeout(() => this.copied = false, 2000);
                        } else {
                            alert('Failed to copy. Please select the text and copy manually (Ctrl+C).');
                        }
                    } catch (error) {
                        console.error('Failed to copy:', error);
                        alert('Failed to copy to clipboard');
                    }
                }
            };
        }
    </script>
    @endpush

    @push('styles')
    <style>
        @keyframes float { 0%, 100% { transform: translateY(0px); } 50% { transform: translateY(-20px); } }
        .animate-float { animation: float 6s ease-in-out infinite; }
    </style>
    @endpush
</x-app-layout>
