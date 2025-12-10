<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-2xl text-white">{{ __('Weekly Schedule') }}</h2>
    </x-slot>
    <x-slot name="title">{{ __('Admin - Weekly Schedule') }}</x-slot>

    <div class="min-h-screen bg-gradient-to-br from-teal-50 via-cyan-50 to-blue-50 dark:from-slate-900 dark:via-slate-800 dark:to-slate-900 py-8 relative overflow-hidden">
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
                    <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Weekly Schedule</h1>
                    <p class="text-gray-600 dark:text-gray-400 mt-1">{{ $weekStart->format('M j') }} - {{ $weekEnd->format('M j, Y') }}</p>
                </div>
                <a href="{{ route('admin.schedules.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-300 dark:hover:bg-gray-600 transition-colors">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    Daily View
                </a>
            </div>

            {{-- Week Navigator --}}
            <div class="bg-white/60 dark:bg-gray-800/60 backdrop-blur-md border border-white/20 rounded-2xl p-4 shadow mb-6">
                <div class="flex items-center justify-between">
                    <a href="{{ route('admin.schedules.weekly', ['week' => $weekOffset - 1]) }}" 
                       class="inline-flex items-center px-4 py-2 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-200 dark:hover:bg-gray-600 transition-colors">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                        </svg>
                        Previous Week
                    </a>
                    
                    <div class="text-center">
                        <span class="text-lg font-semibold text-gray-900 dark:text-white">
                            {{ $weekStart->format('M j') }} - {{ $weekEnd->format('M j, Y') }}
                        </span>
                        @if($weekOffset == 0)
                            <span class="ml-2 px-2 py-0.5 text-xs bg-teal-100 text-teal-700 dark:bg-teal-900/30 dark:text-teal-400 rounded-full">Current Week</span>
                        @endif
                    </div>
                    
                    <a href="{{ route('admin.schedules.weekly', ['week' => $weekOffset + 1]) }}" 
                       class="inline-flex items-center px-4 py-2 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-200 dark:hover:bg-gray-600 transition-colors">
                        Next Week
                        <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </a>
                </div>
            </div>

            {{-- Weekly Grid --}}
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 xl:grid-cols-7 gap-4">
                @php
                    $days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
                @endphp
                @foreach($days as $index => $day)
                    @php
                        $daySchedules = $weeklySchedule[$day] ?? collect();
                        $dayDate = $weekStart->copy()->addDays($index);
                        $isToday = $dayDate->isToday();
                    @endphp
                    <div class="bg-white/60 dark:bg-gray-800/60 backdrop-blur-md border {{ $isToday ? 'border-teal-400 ring-2 ring-teal-400/50' : 'border-white/20' }} rounded-2xl shadow overflow-hidden">
                        <div class="px-4 py-3 {{ $isToday ? 'bg-gradient-to-r from-teal-500 to-cyan-600 text-white' : 'bg-gray-50 dark:bg-gray-700/50' }}">
                            <div class="flex items-center justify-between">
                                <div>
                                    <div class="text-xs font-medium {{ $isToday ? 'text-teal-100' : 'text-gray-500 dark:text-gray-400' }}">{{ $day }}</div>
                                    <div class="text-lg font-bold {{ $isToday ? 'text-white' : 'text-gray-900 dark:text-white' }}">{{ $dayDate->format('M j') }}</div>
                                </div>
                                <span class="inline-flex items-center justify-center w-7 h-7 text-sm font-semibold rounded-full {{ $isToday ? 'bg-white/20 text-white' : 'bg-teal-100 text-teal-700 dark:bg-teal-900/30 dark:text-teal-400' }}">
                                    {{ $daySchedules->count() }}
                                </span>
                            </div>
                        </div>
                        
                        <div class="p-3 min-h-[200px]">
                            @if($daySchedules->isEmpty())
                                <div class="text-center py-8 text-gray-400">
                                    <div class="text-2xl mb-1">📅</div>
                                    <div class="text-xs">No classes</div>
                                </div>
                            @else
                                <div class="space-y-2">
                                    @foreach($daySchedules as $schedule)
                                        <a href="{{ route('admin.schedules.index', ['date' => $dayDate->toDateString()]) }}" 
                                           class="block p-2 bg-gray-50 dark:bg-gray-700/50 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                                            <div class="flex items-center justify-between mb-1">
                                                <span class="text-xs font-medium text-teal-600 dark:text-teal-400">
                                                    {{ \Carbon\Carbon::parse($schedule->class_time)->format('g:i A') }}
                                                </span>
                                                <span class="w-2 h-2 rounded-full
                                                    @if($schedule->status === 'completed') bg-emerald-500
                                                    @elseif($schedule->status === 'in_progress') bg-blue-500
                                                    @elseif($schedule->status === 'cancelled') bg-red-500
                                                    @else bg-gray-400
                                                    @endif">
                                                </span>
                                            </div>
                                            <div class="text-sm font-medium text-gray-900 dark:text-white truncate">
                                                {{ $schedule->student->first_name ?? 'Unknown' }}
                                            </div>
                                            <div class="text-xs text-gray-500 truncate">
                                                {{ $schedule->tutor->first_name ?? 'Unknown' }}
                                            </div>
                                        </a>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- Summary Stats --}}
            <div class="mt-8 bg-white/60 dark:bg-gray-800/60 backdrop-blur-md border border-white/20 rounded-2xl p-6 shadow">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Week Summary</h3>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    @php
                        $totalClasses = collect($weeklySchedule)->flatten()->count();
                        $completedClasses = collect($weeklySchedule)->flatten()->where('status', 'completed')->count();
                        $cancelledClasses = collect($weeklySchedule)->flatten()->where('status', 'cancelled')->count();
                        $scheduledClasses = $totalClasses - $completedClasses - $cancelledClasses;
                    @endphp
                    <div class="text-center">
                        <div class="text-3xl font-bold text-gray-900 dark:text-white">{{ $totalClasses }}</div>
                        <div class="text-sm text-gray-500">Total Classes</div>
                    </div>
                    <div class="text-center">
                        <div class="text-3xl font-bold text-gray-600">{{ $scheduledClasses }}</div>
                        <div class="text-sm text-gray-500">Scheduled</div>
                    </div>
                    <div class="text-center">
                        <div class="text-3xl font-bold text-emerald-600">{{ $completedClasses }}</div>
                        <div class="text-sm text-gray-500">Completed</div>
                    </div>
                    <div class="text-center">
                        <div class="text-3xl font-bold text-red-600">{{ $cancelledClasses }}</div>
                        <div class="text-sm text-gray-500">Cancelled</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('styles')
    <style>
        @keyframes float { 0%, 100% { transform: translateY(0px); } 50% { transform: translateY(-20px); } }
        .animate-float { animation: float 6s ease-in-out infinite; }
    </style>
    @endpush
</x-app-layout>
