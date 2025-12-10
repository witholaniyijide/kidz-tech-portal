<x-tutor-layout title="My Availability">
<div class="space-y-6" x-data="availabilityCalendar()">
    <!-- Page Header -->
    <div class="flex flex-wrap items-center justify-between gap-4">
        <div>
            <h1 class="text-3xl font-bold text-slate-900 dark:text-white">Availability</h1>
            <p class="text-slate-600 dark:text-slate-400 mt-1">Set when you're available for classes</p>
        </div>
        <button @click="showAddModal = true" class="inline-flex items-center gap-2 px-4 py-2.5 bg-gradient-to-r from-[#1D2A6D] to-[#4B51FF] text-white font-semibold rounded-xl hover:opacity-90 transition-all shadow-lg hover:shadow-xl hover:-translate-y-0.5">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            + Hours
        </button>
    </div>

    <!-- Tabs -->
    <div class="glass-card rounded-2xl shadow-lg overflow-hidden">
        <div class="flex border-b border-slate-200 dark:border-slate-700">
            <a href="{{ route('tutor.availability.index', ['tab' => 'weekly']) }}" 
               class="flex-1 px-6 py-4 text-center font-medium transition-colors {{ $tab === 'weekly' ? 'text-[#4B51FF] border-b-2 border-[#4B51FF] bg-[#4B51FF]/5' : 'text-slate-500 hover:text-slate-700 dark:text-slate-400' }}">
                <div class="flex items-center justify-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                    </svg>
                    Weekly hours
                </div>
            </a>
            <a href="{{ route('tutor.availability.index', ['tab' => 'date-specific']) }}" 
               class="flex-1 px-6 py-4 text-center font-medium transition-colors {{ $tab === 'date-specific' ? 'text-[#4B51FF] border-b-2 border-[#4B51FF] bg-[#4B51FF]/5' : 'text-slate-500 hover:text-slate-700 dark:text-slate-400' }}">
                <div class="flex items-center justify-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    Date-specific hours
                </div>
            </a>
        </div>

        <!-- Content -->
        <div class="p-6">
            @if($tab === 'weekly')
                <!-- Weekly Hours Section -->
                <div class="mb-6">
                    <div class="flex items-center gap-2 mb-2">
                        <svg class="w-5 h-5 text-[#4B51FF]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                        </svg>
                        <h2 class="text-lg font-semibold text-slate-900 dark:text-white">Weekly hours</h2>
                    </div>
                    <p class="text-sm text-slate-500 dark:text-slate-400">Set when you are typically available for classes</p>
                </div>

                <!-- Days List -->
                <div class="space-y-4">
                    @php
                        $days = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
                        $dayAbbr = ['S', 'M', 'T', 'W', 'T', 'F', 'S'];
                    @endphp

                    @foreach($days as $index => $day)
                        @php
                            $daySlots = $weeklyAvailability->get($day, collect());
                            $dayClasses = $classesByDay->get($day, collect());
                            $hasAvailable = $daySlots->where('type', 'available')->count() > 0;
                            $isUnavailable = $daySlots->count() === 1 && $daySlots->first()->type === 'unavailable' && $daySlots->first()->start_time->format('H:i') === '00:00';
                        @endphp
                        
                        <div class="flex items-start gap-4 py-4 border-b border-slate-100 dark:border-slate-800 last:border-0">
                            <!-- Day Circle -->
                            <div class="w-10 h-10 rounded-full flex items-center justify-center font-bold text-sm flex-shrink-0
                                {{ $hasAvailable ? 'bg-[#1D2A6D] text-white' : 'bg-slate-200 dark:bg-slate-700 text-slate-500 dark:text-slate-400' }}">
                                {{ $dayAbbr[$index] }}
                            </div>

                            <!-- Time Slots -->
                            <div class="flex-1">
                                @if($isUnavailable)
                                    <div class="flex items-center gap-3">
                                        <span class="text-slate-500 dark:text-slate-400">Unavailable</span>
                                        <button @click="addSlotForDay('{{ $day }}')" class="p-1 text-slate-400 hover:text-[#4B51FF] transition-colors" title="Add available hours">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                                            </svg>
                                        </button>
                                    </div>
                                @elseif($daySlots->isEmpty())
                                    <div class="flex items-center gap-3">
                                        <span class="text-slate-500 dark:text-slate-400">Unavailable</span>
                                        <button @click="addSlotForDay('{{ $day }}')" class="p-1 text-slate-400 hover:text-[#4B51FF] transition-colors" title="Add available hours">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                                            </svg>
                                        </button>
                                    </div>
                                @else
                                    <div class="space-y-2">
                                        @foreach($daySlots as $slot)
                                            <div class="flex items-center gap-3 group">
                                                @if($slot->type === 'available')
                                                    <!-- Time Inputs -->
                                                    <div class="flex items-center gap-2">
                                                        <span class="px-3 py-1.5 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-600 rounded-lg text-sm font-medium text-slate-700 dark:text-slate-300">
                                                            {{ \Carbon\Carbon::parse($slot->start_time)->format('g:ia') }}
                                                        </span>
                                                        <span class="text-slate-400">-</span>
                                                        <span class="px-3 py-1.5 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-600 rounded-lg text-sm font-medium text-slate-700 dark:text-slate-300">
                                                            {{ \Carbon\Carbon::parse($slot->end_time)->format('g:ia') }}
                                                        </span>
                                                    </div>
                                                    
                                                    <!-- Actions -->
                                                    <div class="flex items-center gap-1 opacity-0 group-hover:opacity-100 transition-opacity">
                                                        <!-- Delete -->
                                                        <form action="{{ route('tutor.availability.destroy', $slot) }}" method="POST" class="inline" onsubmit="return confirm('Remove this time slot?')">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="p-1.5 text-slate-400 hover:text-rose-500 transition-colors" title="Remove">
                                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                                                </svg>
                                                            </button>
                                                        </form>
                                                        
                                                        <!-- Add Another -->
                                                        <button @click="addSlotForDay('{{ $day }}')" class="p-1.5 text-slate-400 hover:text-[#4B51FF] transition-colors" title="Add another slot">
                                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                                                            </svg>
                                                        </button>
                                                        
                                                        <!-- Duplicate to another day -->
                                                        <button @click="openDuplicateModal({{ $slot->id }}, '{{ $day }}')" class="p-1.5 text-slate-400 hover:text-[#4B51FF] transition-colors" title="Copy to another day">
                                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                                                            </svg>
                                                        </button>
                                                    </div>
                                                @else
                                                    <span class="text-slate-500 italic">Unavailable: {{ \Carbon\Carbon::parse($slot->start_time)->format('g:ia') }} - {{ \Carbon\Carbon::parse($slot->end_time)->format('g:ia') }}</span>
                                                    <form action="{{ route('tutor.availability.destroy', $slot) }}" method="POST" class="inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="p-1 text-slate-400 hover:text-rose-500 transition-colors opacity-0 group-hover:opacity-100">
                                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                                            </svg>
                                                        </button>
                                                    </form>
                                                @endif
                                            </div>
                                        @endforeach
                                    </div>
                                @endif

                                <!-- Scheduled Classes Indicator -->
                                @if($dayClasses->count() > 0)
                                    <div class="mt-2 flex flex-wrap gap-1">
                                        @foreach($dayClasses->take(3) as $class)
                                            <span class="inline-flex items-center gap-1 px-2 py-0.5 bg-emerald-100 dark:bg-emerald-900/30 text-emerald-700 dark:text-emerald-400 text-xs rounded-full">
                                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                                </svg>
                                                {{ $class['student_name'] ?? 'Student' }} @ {{ isset($class['class_time']) ? \Carbon\Carbon::parse($class['class_time'])->format('g:ia') : 'TBD' }}
                                            </span>
                                        @endforeach
                                        @if($dayClasses->count() > 3)
                                            <span class="text-xs text-slate-500">+{{ $dayClasses->count() - 3 }} more</span>
                                        @endif
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Timezone -->
                <div class="mt-6 pt-4 border-t border-slate-200 dark:border-slate-700">
                    <form action="{{ route('tutor.availability.updateTimezone') }}" method="POST" class="flex items-center gap-3">
                        @csrf
                        <label class="text-sm text-slate-500 dark:text-slate-400">Timezone:</label>
                        <select name="timezone" onchange="this.form.submit()" class="px-3 py-1.5 text-sm bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-600 rounded-lg focus:ring-2 focus:ring-[#4B51FF] focus:border-transparent">
                            <option value="Africa/Lagos" {{ $timezone === 'Africa/Lagos' ? 'selected' : '' }}>West Africa Time (WAT)</option>
                            <option value="Africa/Johannesburg" {{ $timezone === 'Africa/Johannesburg' ? 'selected' : '' }}>South Africa Time (SAST)</option>
                            <option value="Europe/London" {{ $timezone === 'Europe/London' ? 'selected' : '' }}>London (GMT/BST)</option>
                            <option value="America/New_York" {{ $timezone === 'America/New_York' ? 'selected' : '' }}>Eastern Time (ET)</option>
                            <option value="America/Los_Angeles" {{ $timezone === 'America/Los_Angeles' ? 'selected' : '' }}>Pacific Time (PT)</option>
                        </select>
                    </form>
                </div>

            @else
                <!-- Date-Specific Hours Section -->
                <div class="mb-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <div class="flex items-center gap-2 mb-2">
                                <svg class="w-5 h-5 text-[#4B51FF]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                                <h2 class="text-lg font-semibold text-slate-900 dark:text-white">Date-specific hours</h2>
                            </div>
                            <p class="text-sm text-slate-500 dark:text-slate-400">Adjust hours for specific days (overrides weekly schedule)</p>
                        </div>
                        <button @click="showAddModal = true; isDateSpecific = true" class="inline-flex items-center gap-2 px-4 py-2 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-600 rounded-lg text-sm font-medium text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-700 transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                            </svg>
                            + Hours
                        </button>
                    </div>
                </div>

                @if($dateSpecificAvailability->isEmpty())
                    <div class="text-center py-12">
                        <div class="w-16 h-16 bg-slate-100 dark:bg-slate-700 rounded-full flex items-center justify-center mx-auto mb-4">
                            <svg class="w-8 h-8 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                        </div>
                        <h3 class="text-lg font-semibold text-slate-700 dark:text-slate-300 mb-2">No date-specific overrides</h3>
                        <p class="text-slate-500 dark:text-slate-400 mb-4">Add specific dates when your availability differs from your weekly schedule.</p>
                    </div>
                @else
                    <div class="space-y-4">
                        @foreach($dateSpecificAvailability as $date => $slots)
                            @php
                                $dateObj = \Carbon\Carbon::parse($date);
                            @endphp
                            <div class="p-4 bg-slate-50 dark:bg-slate-800/50 rounded-xl">
                                <div class="flex items-center justify-between mb-3">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 bg-[#4B51FF] text-white rounded-lg flex flex-col items-center justify-center">
                                            <span class="text-xs font-medium">{{ $dateObj->format('M') }}</span>
                                            <span class="text-sm font-bold leading-none">{{ $dateObj->format('d') }}</span>
                                        </div>
                                        <div>
                                            <p class="font-semibold text-slate-900 dark:text-white">{{ $dateObj->format('l, F j, Y') }}</p>
                                            <p class="text-sm text-slate-500">{{ $dateObj->diffForHumans() }}</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="space-y-2 pl-13">
                                    @foreach($slots as $slot)
                                        <div class="flex items-center gap-3 group">
                                            <span class="px-2 py-1 text-xs font-medium rounded {{ $slot->type === 'available' ? 'bg-emerald-100 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-400' : 'bg-rose-100 text-rose-700 dark:bg-rose-900/30 dark:text-rose-400' }}">
                                                {{ ucfirst($slot->type) }}
                                            </span>
                                            <span class="text-sm text-slate-700 dark:text-slate-300">
                                                {{ \Carbon\Carbon::parse($slot->start_time)->format('g:ia') }} - {{ \Carbon\Carbon::parse($slot->end_time)->format('g:ia') }}
                                            </span>
                                            <form action="{{ route('tutor.availability.destroy', $slot) }}" method="POST" class="inline opacity-0 group-hover:opacity-100 transition-opacity">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="p-1 text-slate-400 hover:text-rose-500 transition-colors">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                                    </svg>
                                                </button>
                                            </form>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            @endif
        </div>
    </div>

    <!-- Legend -->
    <div class="glass-card rounded-xl p-4">
        <h3 class="text-sm font-semibold text-slate-700 dark:text-slate-300 mb-3">Legend</h3>
        <div class="flex flex-wrap gap-4 text-sm">
            <div class="flex items-center gap-2">
                <span class="w-3 h-3 bg-[#1D2A6D] rounded-full"></span>
                <span class="text-slate-600 dark:text-slate-400">Available day</span>
            </div>
            <div class="flex items-center gap-2">
                <span class="w-3 h-3 bg-slate-300 dark:bg-slate-600 rounded-full"></span>
                <span class="text-slate-600 dark:text-slate-400">Unavailable day</span>
            </div>
            <div class="flex items-center gap-2">
                <span class="px-2 py-0.5 bg-emerald-100 dark:bg-emerald-900/30 text-emerald-700 dark:text-emerald-400 text-xs rounded-full">Student</span>
                <span class="text-slate-600 dark:text-slate-400">Scheduled class</span>
            </div>
        </div>
    </div>

    <!-- Add Availability Modal -->
    <div x-show="showAddModal" x-cloak class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div x-show="showAddModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 bg-slate-900/50 transition-opacity" @click="showAddModal = false"></div>

            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

            <div x-show="showAddModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" class="inline-block align-bottom bg-white dark:bg-slate-800 rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <form action="{{ route('tutor.availability.store') }}" method="POST">
                    @csrf
                    <div class="px-6 py-4 border-b border-slate-200 dark:border-slate-700">
                        <h3 class="text-lg font-semibold text-slate-900 dark:text-white" x-text="isDateSpecific ? 'Add date-specific hours' : 'Add available hours'"></h3>
                    </div>
                    
                    <div class="p-6 space-y-4">
                        <!-- Type -->
                        <div>
                            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Type</label>
                            <div class="flex gap-4">
                                <label class="flex items-center gap-2 cursor-pointer">
                                    <input type="radio" name="type" value="available" checked class="w-4 h-4 text-[#4B51FF]">
                                    <span class="text-sm text-slate-700 dark:text-slate-300">Available</span>
                                </label>
                                <label class="flex items-center gap-2 cursor-pointer">
                                    <input type="radio" name="type" value="unavailable" class="w-4 h-4 text-[#4B51FF]">
                                    <span class="text-sm text-slate-700 dark:text-slate-300">Unavailable</span>
                                </label>
                            </div>
                        </div>

                        <!-- Day / Date -->
                        <div x-show="!isDateSpecific">
                            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Day</label>
                            <select name="day" x-model="selectedDay" class="w-full px-4 py-2.5 bg-white dark:bg-slate-700 border border-slate-300 dark:border-slate-600 rounded-xl focus:ring-2 focus:ring-[#4B51FF] focus:border-transparent">
                                <option value="">Select a day</option>
                                <option value="Sunday">Sunday</option>
                                <option value="Monday">Monday</option>
                                <option value="Tuesday">Tuesday</option>
                                <option value="Wednesday">Wednesday</option>
                                <option value="Thursday">Thursday</option>
                                <option value="Friday">Friday</option>
                                <option value="Saturday">Saturday</option>
                            </select>
                        </div>

                        <div x-show="isDateSpecific">
                            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Specific Date</label>
                            <input type="date" name="specific_date" min="{{ date('Y-m-d') }}" class="w-full px-4 py-2.5 bg-white dark:bg-slate-700 border border-slate-300 dark:border-slate-600 rounded-xl focus:ring-2 focus:ring-[#4B51FF] focus:border-transparent">
                        </div>

                        <!-- Time Range -->
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Start Time</label>
                                <input type="time" name="start_time" required class="w-full px-4 py-2.5 bg-white dark:bg-slate-700 border border-slate-300 dark:border-slate-600 rounded-xl focus:ring-2 focus:ring-[#4B51FF] focus:border-transparent">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">End Time</label>
                                <input type="time" name="end_time" required class="w-full px-4 py-2.5 bg-white dark:bg-slate-700 border border-slate-300 dark:border-slate-600 rounded-xl focus:ring-2 focus:ring-[#4B51FF] focus:border-transparent">
                            </div>
                        </div>

                        <!-- Quick Presets -->
                        <div>
                            <label class="block text-sm font-medium text-slate-500 dark:text-slate-400 mb-2">Quick presets</label>
                            <div class="flex flex-wrap gap-2">
                                <button type="button" @click="setTimePreset('09:00', '12:00')" class="px-3 py-1.5 text-xs font-medium bg-slate-100 dark:bg-slate-700 text-slate-600 dark:text-slate-400 rounded-lg hover:bg-slate-200 dark:hover:bg-slate-600 transition-colors">9am - 12pm</button>
                                <button type="button" @click="setTimePreset('14:00', '17:00')" class="px-3 py-1.5 text-xs font-medium bg-slate-100 dark:bg-slate-700 text-slate-600 dark:text-slate-400 rounded-lg hover:bg-slate-200 dark:hover:bg-slate-600 transition-colors">2pm - 5pm</button>
                                <button type="button" @click="setTimePreset('17:00', '20:00')" class="px-3 py-1.5 text-xs font-medium bg-slate-100 dark:bg-slate-700 text-slate-600 dark:text-slate-400 rounded-lg hover:bg-slate-200 dark:hover:bg-slate-600 transition-colors">5pm - 8pm</button>
                                <button type="button" @click="setTimePreset('09:00', '17:00')" class="px-3 py-1.5 text-xs font-medium bg-slate-100 dark:bg-slate-700 text-slate-600 dark:text-slate-400 rounded-lg hover:bg-slate-200 dark:hover:bg-slate-600 transition-colors">Full day (9-5)</button>
                            </div>
                        </div>

                        <!-- Notes -->
                        <div>
                            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Notes (optional)</label>
                            <input type="text" name="notes" placeholder="e.g., Only for advanced students" class="w-full px-4 py-2.5 bg-white dark:bg-slate-700 border border-slate-300 dark:border-slate-600 rounded-xl focus:ring-2 focus:ring-[#4B51FF] focus:border-transparent">
                        </div>
                    </div>

                    <div class="px-6 py-4 bg-slate-50 dark:bg-slate-800/50 border-t border-slate-200 dark:border-slate-700 flex justify-end gap-3">
                        <button type="button" @click="showAddModal = false; isDateSpecific = false" class="px-4 py-2 text-slate-600 dark:text-slate-400 hover:text-slate-800 dark:hover:text-slate-200 font-medium">
                            Cancel
                        </button>
                        <button type="submit" class="px-6 py-2 bg-gradient-to-r from-[#1D2A6D] to-[#4B51FF] text-white font-semibold rounded-xl hover:opacity-90 transition-all">
                            Add Hours
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Duplicate Modal -->
    <div x-show="showDuplicateModal" x-cloak class="fixed inset-0 z-50 overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen px-4">
            <div class="fixed inset-0 bg-slate-900/50" @click="showDuplicateModal = false"></div>
            <div class="relative bg-white dark:bg-slate-800 rounded-2xl shadow-xl max-w-sm w-full p-6">
                <h3 class="text-lg font-semibold text-slate-900 dark:text-white mb-4">Copy to another day</h3>
                <form :action="'/tutor/availability/' + duplicateSlotId + '/duplicate'" method="POST">
                    @csrf
                    <select name="target_day" class="w-full px-4 py-2.5 mb-4 bg-white dark:bg-slate-700 border border-slate-300 dark:border-slate-600 rounded-xl">
                        <option value="">Select day</option>
                        <template x-for="day in ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday']">
                            <option :value="day" x-text="day" :disabled="day === duplicateFromDay"></option>
                        </template>
                    </select>
                    <div class="flex justify-end gap-3">
                        <button type="button" @click="showDuplicateModal = false" class="px-4 py-2 text-slate-600 dark:text-slate-400">Cancel</button>
                        <button type="submit" class="px-4 py-2 bg-[#4B51FF] text-white rounded-xl">Copy</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function availabilityCalendar() {
    return {
        showAddModal: false,
        showDuplicateModal: false,
        isDateSpecific: false,
        selectedDay: '',
        duplicateSlotId: null,
        duplicateFromDay: '',

        addSlotForDay(day) {
            this.selectedDay = day;
            this.isDateSpecific = false;
            this.showAddModal = true;
        },

        openDuplicateModal(slotId, fromDay) {
            this.duplicateSlotId = slotId;
            this.duplicateFromDay = fromDay;
            this.showDuplicateModal = true;
        },

        setTimePreset(start, end) {
            document.querySelector('input[name="start_time"]').value = start;
            document.querySelector('input[name="end_time"]').value = end;
        }
    }
}
</script>
@endpush
</x-tutor-layout>
