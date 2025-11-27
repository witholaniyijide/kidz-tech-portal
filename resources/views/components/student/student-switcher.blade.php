@props(['students', 'currentStudent'])

@if(count($students) > 1)
    <div x-data="{ open: false }" class="relative mb-6">
        <x-ui.glass-card>
            <button @click="open = !open" class="w-full flex items-center justify-between">
                <div class="flex items-center space-x-4">
                    <!-- Current Student Avatar -->
                    <div class="flex-shrink-0">
                        @if($currentStudent->profile_photo)
                            <img src="{{ $currentStudent->profile_photo }}" alt="{{ $currentStudent->full_name }}" class="w-12 h-12 rounded-full object-cover border-2 border-sky-500">
                        @else
                            <div class="w-12 h-12 rounded-full bg-gradient-to-br from-sky-500 to-cyan-400 flex items-center justify-center text-white font-bold">
                                {{ strtoupper(substr($currentStudent->first_name, 0, 1)) }}{{ strtoupper(substr($currentStudent->last_name, 0, 1)) }}
                            </div>
                        @endif
                    </div>

                    <!-- Current Student Info -->
                    <div class="text-left">
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Viewing Dashboard For</p>
                        <h3 class="text-lg font-bold text-gray-900 dark:text-white">
                            {{ $currentStudent->full_name }}
                        </h3>
                        <div class="flex items-center space-x-2 mt-1">
                            <span class="text-xs text-gray-500 dark:text-gray-400">
                                {{ $currentStudent->roadmap_stage ?? 'Getting Started' }}
                            </span>
                            <span class="text-gray-400">•</span>
                            <span class="text-xs font-medium text-sky-600 dark:text-sky-400">
                                {{ $currentStudent->progressPercentage() }}% Progress
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Toggle Icon -->
                <div class="flex-shrink-0">
                    <svg class="w-5 h-5 text-gray-500 dark:text-gray-400 transition-transform" :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                    </svg>
                </div>
            </button>
        </x-ui.glass-card>

        <!-- Student Dropdown -->
        <div x-show="open"
             x-cloak
             @click.away="open = false"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 transform scale-95"
             x-transition:enter-end="opacity-100 transform scale-100"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100 transform scale-100"
             x-transition:leave-end="opacity-0 transform scale-95"
             class="absolute z-50 w-full mt-2 rounded-2xl bg-white/95 dark:bg-gray-900/95 border border-white/10 dark:border-gray-700/10 shadow-2xl backdrop-blur-xl overflow-hidden">
            <div class="p-2 max-h-96 overflow-y-auto">
                <p class="px-4 py-2 text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                    Switch Student
                </p>
                @foreach($students as $student)
                    <a href="{{ route('student.dashboard', ['student_id' => $student->id]) }}"
                       class="block px-4 py-3 rounded-xl hover:bg-sky-50 dark:hover:bg-sky-900/30 transition-colors {{ $student->id === $currentStudent->id ? 'bg-sky-100 dark:bg-sky-900/50' : '' }}">
                        <div class="flex items-center space-x-3">
                            <!-- Student Avatar -->
                            <div class="flex-shrink-0">
                                @if($student->profile_photo)
                                    <img src="{{ $student->profile_photo }}" alt="{{ $student->full_name }}" class="w-10 h-10 rounded-full object-cover {{ $student->id === $currentStudent->id ? 'border-2 border-sky-500' : '' }}">
                                @else
                                    <div class="w-10 h-10 rounded-full bg-gradient-to-br from-sky-500 to-cyan-400 flex items-center justify-center text-white font-semibold text-sm">
                                        {{ strtoupper(substr($student->first_name, 0, 1)) }}{{ strtoupper(substr($student->last_name, 0, 1)) }}
                                    </div>
                                @endif
                            </div>

                            <!-- Student Info -->
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-semibold text-gray-900 dark:text-white truncate">
                                    {{ $student->full_name }}
                                    @if($student->id === $currentStudent->id)
                                        <span class="ml-2 text-xs font-normal text-sky-600 dark:text-sky-400">(Current)</span>
                                    @endif
                                </p>
                                <div class="flex items-center space-x-2">
                                    <span class="text-xs text-gray-500 dark:text-gray-400">
                                        Grade {{ $student->age ?? 'N/A' }}
                                    </span>
                                    <span class="text-gray-400">•</span>
                                    <span class="text-xs font-medium text-sky-600 dark:text-sky-400">
                                        {{ $student->progressPercentage() }}%
                                    </span>
                                </div>
                            </div>

                            <!-- Checkmark for current student -->
                            @if($student->id === $currentStudent->id)
                                <div class="flex-shrink-0">
                                    <svg class="w-5 h-5 text-sky-500" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                    </svg>
                                </div>
                            @endif
                        </div>
                    </a>
                @endforeach
            </div>
        </div>
    </div>
@endif
