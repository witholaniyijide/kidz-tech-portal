<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-2xl text-white">{{ __('Daily Schedule') }}</h2>
    </x-slot>
    <x-slot name="title">{{ __('Admin - Schedules') }}</x-slot>

    <div class="min-h-screen bg-gradient-to-br from-teal-50 via-cyan-50 to-blue-50 dark:from-slate-900 dark:via-slate-800 dark:to-slate-900 py-8 relative overflow-hidden" x-data="scheduleManager()">
        <div class="absolute top-0 left-0 w-72 h-72 bg-teal-300 dark:bg-teal-900 rounded-full mix-blend-multiply filter blur-xl opacity-70 animate-float"></div>
        <div class="absolute top-0 right-0 w-72 h-72 bg-cyan-300 dark:bg-cyan-900 rounded-full mix-blend-multiply filter blur-xl opacity-70 animate-float" style="animation-delay: 2s;"></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            @if(session('success'))
                <div class="mb-6 bg-emerald-100 dark:bg-emerald-900/30 border border-emerald-400 text-emerald-800 dark:text-emerald-400 px-6 py-4 rounded-xl">
                    {{ session('success') }}
                </div>
            @endif

            {{-- Header --}}
            <div class="flex flex-wrap items-center justify-between gap-4 mb-8">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Daily Class Schedule</h1>
                    <p class="text-gray-600 dark:text-gray-400 mt-1">{{ $selectedDate->format('l, F j, Y') }}</p>
                </div>
                <div class="flex gap-2">
                    <a href="{{ route('admin.schedules.create') }}" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-teal-500 to-cyan-600 text-white font-medium rounded-xl hover:shadow-lg transform hover:-translate-y-0.5 transition-all">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                        </svg>
                        Add Entry
                    </a>
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
                                   class="px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg focus:ring-2 focus:ring-teal-500">
                        </form>
                        
                        @if(!$selectedDate->isToday())
                            <a href="{{ route('admin.schedules.index') }}" class="px-3 py-1 text-sm bg-teal-100 text-teal-700 dark:bg-teal-900/30 dark:text-teal-400 rounded-full hover:bg-teal-200 transition-colors">
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
            @if($todaySchedule->count() > 0)
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
                                <span class="text-sm text-gray-500">{{ $todaySchedule->first()->posted_at?->format('M j \a\t g:i A') }}</span>
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
                                    <button type="submit" class="inline-flex items-center px-4 py-2 bg-teal-600 text-white rounded-lg hover:bg-teal-700 transition-colors text-sm">
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
                <div class="px-6 py-4 bg-gradient-to-r from-teal-500 to-cyan-600 text-white flex justify-between items-center">
                    <h3 class="text-lg font-semibold">{{ $selectedDate->format('l\'s') }} Classes ({{ $todaySchedule->count() }})</h3>
                </div>
                
                @if($todaySchedule->isEmpty())
                    <div class="p-12 text-center">
                        <div class="text-6xl mb-4">📅</div>
                        <h3 class="text-xl font-semibold text-gray-700 dark:text-gray-300 mb-2">No Classes Scheduled</h3>
                        <p class="text-gray-500 dark:text-gray-400 mb-4">Add entries manually or auto-generate from student schedules</p>
                        <div class="flex justify-center gap-3">
                            <a href="{{ route('admin.schedules.create') }}" class="inline-flex items-center px-4 py-2 bg-teal-600 text-white rounded-lg hover:bg-teal-700">
                                Add Entry
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
                        @foreach($todaySchedule as $index => $schedule)
                            <div class="p-4 hover:bg-gray-50 dark:hover:bg-gray-700/30 transition-colors">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center gap-4">
                                        <div class="w-8 h-8 bg-teal-100 dark:bg-teal-900/30 text-teal-700 dark:text-teal-400 rounded-full flex items-center justify-center font-bold text-sm">
                                            {{ $index + 1 }}
                                        </div>
                                        <div>
                                            <div class="font-semibold text-gray-900 dark:text-white">
                                                {{ $schedule->student->first_name ?? 'Unknown' }} {{ $schedule->student->last_name ?? '' }}
                                            </div>
                                            <div class="text-sm text-gray-500 dark:text-gray-400">
                                                Tutor: {{ $schedule->tutor->first_name ?? 'Unknown' }} {{ $schedule->tutor->last_name ?? '' }}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="flex items-center gap-4">
                                        <div class="text-right">
                                            <div class="font-medium text-gray-900 dark:text-white">
                                                {{ \Carbon\Carbon::parse($schedule->class_time)->format('g:i A') }}
                                            </div>
                                            @if($schedule->class_link)
                                                <a href="{{ $schedule->class_link }}" target="_blank" class="text-xs text-teal-600 hover:underline">Join Class</a>
                                            @endif
                                        </div>
                                        <span class="px-2 py-1 text-xs font-semibold rounded-full
                                            @if($schedule->status === 'completed') bg-emerald-100 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-400
                                            @elseif($schedule->status === 'in_progress') bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400
                                            @elseif($schedule->status === 'cancelled') bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400
                                            @else bg-gray-100 text-gray-700 dark:bg-gray-700 dark:text-gray-300
                                            @endif">
                                            {{ ucfirst(str_replace('_', ' ', $schedule->status ?? 'scheduled')) }}
                                        </span>
                                        <div class="flex gap-1">
                                            <a href="{{ route('admin.schedules.edit', $schedule) }}" class="p-1.5 text-gray-500 hover:text-blue-600 hover:bg-blue-50 dark:hover:bg-blue-900/20 rounded transition-colors">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                                </svg>
                                            </a>
                                            <form action="{{ route('admin.schedules.destroy', $schedule) }}" method="POST" onsubmit="return confirm('Delete this schedule entry?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="p-1.5 text-gray-500 hover:text-red-600 hover:bg-red-50 dark:hover:bg-red-900/20 rounded transition-colors">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                    </svg>
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
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
                    @foreach($days as $day)
                        @php
                            $daySchedules = $weeklySchedule[$day] ?? collect();
                            $dayDate = $weekStart->copy()->next($day)->subWeek();
                            if ($day === 'Monday') $dayDate = $weekStart->copy();
                            else $dayDate = $weekStart->copy()->next($day)->subWeek();
                            // Recalculate correctly
                            $dayIndex = array_search($day, $days);
                            $dayDate = $weekStart->copy()->addDays($dayIndex);
                        @endphp
                        <div class="p-3 {{ $dayDate->isToday() ? 'bg-teal-50 dark:bg-teal-900/20' : '' }}">
                            <div class="text-center mb-2">
                                <div class="text-xs font-medium text-gray-500 dark:text-gray-400">{{ substr($day, 0, 3) }}</div>
                                <div class="text-lg font-bold {{ $dayDate->isToday() ? 'text-teal-600' : 'text-gray-900 dark:text-white' }}">{{ $dayDate->format('j') }}</div>
                            </div>
                            <div class="text-center">
                                <span class="inline-flex items-center justify-center w-6 h-6 text-xs font-semibold rounded-full {{ $daySchedules->count() > 0 ? 'bg-teal-100 text-teal-700 dark:bg-teal-900/30 dark:text-teal-400' : 'bg-gray-100 text-gray-500 dark:bg-gray-700 dark:text-gray-400' }}">
                                    {{ $daySchedules->count() }}
                                </span>
                            </div>
                            @if($daySchedules->count() > 0)
                                <div class="mt-2 space-y-1">
                                    @foreach($daySchedules->take(3) as $s)
                                        <div class="text-xs text-gray-600 dark:text-gray-400 truncate" title="{{ $s->student->first_name ?? 'Unknown' }}">
                                            {{ \Carbon\Carbon::parse($s->class_time)->format('g:i') }} - {{ $s->student->first_name ?? '?' }}
                                        </div>
                                    @endforeach
                                    @if($daySchedules->count() > 3)
                                        <div class="text-xs text-gray-400">+{{ $daySchedules->count() - 3 }} more</div>
                                    @endif
                                </div>
                            @endif
                        </div>
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
                        await navigator.clipboard.writeText(data.format);
                        this.copied = true;
                        setTimeout(() => this.copied = false, 2000);
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
