<x-manager-layout>
    <x-slot name="header">{{ __('Manager Dashboard') }}</x-slot>
    <x-slot name="title">{{ __('Manager Dashboard') }}</x-slot>

    <div class="max-w-7xl mx-auto">

        {{-- Welcome Banner --}}
        <div class="bg-white/40 dark:bg-gray-800/40 backdrop-blur-xl border border-white/20 dark:border-gray-700/30 rounded-2xl shadow-lg p-8 mb-8">
            <div class="flex items-center justify-between flex-wrap gap-4">
                <div class="flex items-center gap-4">
                    <div class="bg-gradient-to-r from-[#C15F3C] to-[#DA7756] p-4 rounded-2xl shadow-lg">
                        <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-3xl font-bold text-gray-900 dark:text-white mb-1">
                            Welcome back, <span class="text-transparent bg-clip-text bg-gradient-to-r from-[#C15F3C] to-[#DA7756]">{{ auth()->user()->name ?? 'Manager' }}</span>!
                        </h3>
                        <p class="text-gray-600 dark:text-gray-300 text-lg">Operations & Tutor Performance Coordination Hub</p>
                    </div>
                </div>
                <div class="text-right">
                    <div class="text-gray-700 dark:text-gray-300 font-semibold">{{ now()->format('l, F j, Y') }}</div>
                    <div class="text-gray-500 dark:text-gray-400 text-sm">{{ now()->format('g:i A') }}</div>
                </div>
            </div>
        </div>

        {{-- Section 1: Main Statistics Cards --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            {{-- Active Students Card --}}
            <a href="{{ route('manager.students.index') }}" class="block">
                <div class="bg-white/40 dark:bg-gray-800/40 backdrop-blur-xl border border-white/20 dark:border-gray-700/30 rounded-2xl shadow-lg p-6 hover:-translate-y-1 hover:shadow-xl transition-all duration-300 stat-card">
                    <div class="flex items-center justify-between mb-4">
                        <div class="bg-gradient-to-r from-[#C15F3C] to-[#DA7756] p-3 rounded-xl">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                            </svg>
                        </div>
                        <span class="text-xs px-2 py-1 bg-[#C15F3C]/10 text-[#C15F3C] dark:bg-[#DA7756]/20 dark:text-[#DA7756] rounded-full font-medium">Students</span>
                    </div>
                    <div class="text-4xl font-extrabold text-gray-900 dark:text-white mb-2">{{ $stats['activeStudents'] }}</div>
                    <div class="text-gray-600 dark:text-gray-300 font-medium">Active Students</div>
                    <div class="text-sm text-gray-500 dark:text-gray-400 mt-2">{{ $stats['totalStudents'] }} total • {{ $stats['inactiveStudents'] }} inactive</div>
                </div>
            </a>

            {{-- Active Tutors Card --}}
            <a href="{{ route('manager.tutors.index') }}" class="block">
                <div class="bg-white/40 dark:bg-gray-800/40 backdrop-blur-xl border border-white/20 dark:border-gray-700/30 rounded-2xl shadow-lg p-6 hover:-translate-y-1 hover:shadow-xl transition-all duration-300 stat-card">
                    <div class="flex items-center justify-between mb-4">
                        <div class="bg-gradient-to-r from-[#DA7756] to-[#C15F3C] p-3 rounded-xl">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                        </div>
                        <span class="text-xs px-2 py-1 bg-[#DA7756]/10 text-[#A34E30] dark:bg-[#DA7756]/20 dark:text-[#DA7756] rounded-full font-medium">Tutors</span>
                    </div>
                    <div class="text-4xl font-extrabold text-gray-900 dark:text-white mb-2">{{ $stats['activeTutors'] }}</div>
                    <div class="text-gray-600 dark:text-gray-300 font-medium">Active Tutors</div>
                    <div class="text-sm text-gray-500 dark:text-gray-400 mt-2">{{ $stats['totalTutors'] }} total • {{ $stats['onLeaveTutors'] }} on leave</div>
                </div>
            </a>

            {{-- Today's Classes Card --}}
            <a href="{{ route('manager.attendance.index') }}" class="block">
                <div class="bg-white/40 dark:bg-gray-800/40 backdrop-blur-xl border border-white/20 dark:border-gray-700/30 rounded-2xl shadow-lg p-6 hover:-translate-y-1 hover:shadow-xl transition-all duration-300 stat-card">
                    <div class="flex items-center justify-between mb-4">
                        <div class="bg-gradient-to-r from-amber-500 to-orange-400 p-3 rounded-xl">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                        </div>
                        <span class="text-xs px-2 py-1 bg-amber-100 dark:bg-amber-900/30 text-amber-700 dark:text-amber-300 rounded-full font-medium">Today</span>
                    </div>
                    <div class="text-4xl font-extrabold text-gray-900 dark:text-white mb-2">{{ $stats['todayClasses'] }}</div>
                    <div class="text-gray-600 dark:text-gray-300 font-medium">Today's Classes</div>
                    <div class="text-sm text-gray-500 dark:text-gray-400 mt-2">Scheduled for {{ now()->format('l') }}</div>
                </div>
            </a>

            {{-- Pending Assessments Card --}}
            <a href="{{ route('manager.assessments.index') }}" class="block">
                <div class="bg-white/40 dark:bg-gray-800/40 backdrop-blur-xl border border-white/20 dark:border-gray-700/30 rounded-2xl shadow-lg p-6 hover:-translate-y-1 hover:shadow-xl transition-all duration-300 stat-card">
                    <div class="flex items-center justify-between mb-4">
                        <div class="bg-gradient-to-r from-rose-500 to-pink-500 p-3 rounded-xl">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                            </svg>
                        </div>
                        <span class="text-xs px-2 py-1 bg-rose-100 dark:bg-rose-900/30 text-rose-700 dark:text-rose-300 rounded-full font-medium">Action</span>
                    </div>
                    <div class="text-4xl font-extrabold text-gray-900 dark:text-white mb-2">{{ $stats['pendingAssessments'] }}</div>
                    <div class="text-gray-600 dark:text-gray-300 font-medium">Pending Assessments</div>
                    <div class="text-sm text-gray-500 dark:text-gray-400 mt-2">{{ $stats['awaitingDirectorAssessments'] }} awaiting director</div>
                </div>
            </a>
        </div>

        {{-- Section 2: Today's Class Schedule (Full Width) --}}
        <div class="mb-8" x-data="{
            showModal: false,
            selectedClass: null,
            openModal(classData) {
                this.selectedClass = classData;
                this.showModal = true;
            }
        }">
            <div class="bg-white/40 dark:bg-gray-800/40 backdrop-blur-xl border border-white/20 dark:border-gray-700/30 rounded-2xl shadow-lg p-6">
                <div class="flex items-center justify-between mb-6">
                    <div class="flex items-center gap-3">
                        <div class="bg-gradient-to-r from-[#C15F3C] to-[#DA7756] p-2 rounded-lg">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                        </div>
                        <h3 class="text-2xl font-bold text-gray-900 dark:text-white">Today's Class Schedule</h3>
                    </div>
                    <span class="px-3 py-1 bg-[#C15F3C]/10 dark:bg-[#C15F3C]/20 text-[#C15F3C] dark:text-[#DA7756] rounded-full text-sm font-semibold">
                        {{ now()->format('l, M j') }}
                    </span>
                </div>

                @if($todaySchedule && (($todaySchedule->classes && count($todaySchedule->classes) > 0) || ($todaySchedule->rescheduled_classes && count($todaySchedule->rescheduled_classes) > 0)))
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 max-h-80 overflow-y-auto">
                        {{-- Regular Classes --}}
                        @if($todaySchedule->classes && count($todaySchedule->classes) > 0)
                            @foreach($todaySchedule->classes as $class)
                                <div class="bg-white/50 dark:bg-gray-700/50 backdrop-blur-sm rounded-xl p-4 border border-white/20 dark:border-gray-600/30 cursor-pointer hover:bg-white/70 dark:hover:bg-gray-700/70 transition-colors"
                                     @click="openModal({
                                         time: '{{ $class['time'] ?? 'TBD' }}',
                                         student: '{{ $class['student_name'] ?? 'Student' }}',
                                         tutor: '{{ $class['tutor_name'] ?? 'Assigned' }}',
                                         class_link: '{{ $class['class_link'] ?? '' }}'
                                     })">
                                    <div class="flex items-center gap-3 mb-2">
                                        <div class="bg-gradient-to-r from-[#C15F3C] to-[#DA7756] w-10 h-10 rounded-full flex items-center justify-center text-white font-bold text-sm">
                                            {{ strtoupper(substr($class['student_name'] ?? 'S', 0, 1)) }}
                                        </div>
                                        <div>
                                            <p class="font-semibold text-gray-900 dark:text-white">{{ $class['student_name'] ?? 'Student' }}</p>
                                            @php
                                                try {
                                                    $formattedTime = \Carbon\Carbon::parse($class['time'])->format('g:i A');
                                                } catch (\Exception $e) {
                                                    $formattedTime = $class['time'] ?? 'TBD';
                                                }
                                            @endphp
                                            <p class="text-sm text-gray-500 dark:text-gray-400">{{ $formattedTime }}</p>
                                        </div>
                                    </div>
                                    <p class="text-sm text-gray-600 dark:text-gray-400">
                                        <span class="font-medium">Tutor:</span> {{ $class['tutor_name'] ?? 'Assigned' }}
                                    </p>
                                    @if(isset($class['class_link']) && $class['class_link'])
                                        <span class="inline-flex items-center mt-2 text-xs px-2 py-1 rounded-full bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400">
                                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101"/>
                                            </svg>
                                            Has Link
                                        </span>
                                    @endif
                                </div>
                            @endforeach
                        @endif

                        {{-- Rescheduled Classes --}}
                        @if($todaySchedule->rescheduled_classes && count($todaySchedule->rescheduled_classes) > 0)
                            @foreach($todaySchedule->rescheduled_classes as $class)
                                <div class="bg-amber-50/70 dark:bg-amber-900/20 backdrop-blur-sm rounded-xl p-4 border-2 border-amber-300 dark:border-amber-700/50 cursor-pointer hover:bg-amber-100/70 dark:hover:bg-amber-900/30 transition-colors"
                                     @click="openModal({
                                         time: '{{ $class['time'] ?? 'TBD' }}',
                                         student: '{{ $class['student_name'] ?? 'Student' }}',
                                         tutor: '{{ $class['tutor_name'] ?? 'Assigned' }}',
                                         class_link: '{{ $class['class_link'] ?? '' }}'
                                     })">
                                    <div class="flex items-center gap-3 mb-2">
                                        <div class="bg-gradient-to-r from-amber-500 to-orange-500 w-10 h-10 rounded-full flex items-center justify-center text-white font-bold text-sm">
                                            {{ strtoupper(substr($class['student_name'] ?? 'S', 0, 1)) }}
                                        </div>
                                        <div>
                                            <p class="font-semibold text-gray-900 dark:text-white">{{ $class['student_name'] ?? 'Student' }}</p>
                                            @php
                                                try {
                                                    $formattedTime = \Carbon\Carbon::parse($class['time'])->format('g:i A');
                                                } catch (\Exception $e) {
                                                    $formattedTime = $class['time'] ?? 'TBD';
                                                }
                                            @endphp
                                            <p class="text-sm text-gray-500 dark:text-gray-400">{{ $formattedTime }}</p>
                                        </div>
                                    </div>
                                    <span class="inline-flex items-center text-xs px-2 py-1 rounded-full bg-amber-200 text-amber-800 dark:bg-amber-900/50 dark:text-amber-300 font-medium mb-2">
                                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/>
                                        </svg>
                                        Rescheduled from {{ isset($class['original_date']) ? \Carbon\Carbon::parse($class['original_date'])->format('M j') : 'earlier' }}
                                    </span>
                                    <p class="text-sm text-gray-600 dark:text-gray-400">
                                        <span class="font-medium">Tutor:</span> {{ $class['tutor_name'] ?? 'Assigned' }}
                                    </p>
                                    @if(isset($class['class_link']) && $class['class_link'])
                                        <span class="inline-flex items-center mt-2 text-xs px-2 py-1 rounded-full bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400">
                                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101"/>
                                            </svg>
                                            Has Link
                                        </span>
                                    @endif
                                </div>
                            @endforeach
                        @endif
                    </div>
                @else
                    <div class="text-center py-12">
                        <svg class="w-16 h-16 mx-auto text-gray-300 dark:text-gray-600 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        <p class="text-gray-500 dark:text-gray-400">No classes scheduled for today</p>
                    </div>
                @endif
            </div>

            <!-- Class Details Modal -->
            <div x-show="showModal"
                 x-cloak
                 class="fixed inset-0 z-50 overflow-y-auto"
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100"
                 x-transition:leave="transition ease-in duration-200"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0">
                <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:p-0">
                    <div class="fixed inset-0 bg-black/50 backdrop-blur-sm" @click="showModal = false"></div>

                    <div class="relative inline-block w-full max-w-md p-6 my-8 overflow-hidden text-left align-middle transition-all transform bg-white dark:bg-gray-800 shadow-xl rounded-2xl"
                         x-transition:enter="transition ease-out duration-300"
                         x-transition:enter-start="opacity-0 scale-95"
                         x-transition:enter-end="opacity-100 scale-100"
                         x-transition:leave="transition ease-in duration-200"
                         x-transition:leave-start="opacity-100 scale-100"
                         x-transition:leave-end="opacity-0 scale-95">

                        <!-- Modal Header -->
                        <div class="flex items-center justify-between mb-6">
                            <h3 class="text-xl font-bold text-gray-900 dark:text-white">Class Details</h3>
                            <button @click="showModal = false" class="p-2 text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 rounded-full hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                            </button>
                        </div>

                        <!-- Modal Body -->
                        <div class="space-y-4">
                            <!-- Student -->
                            <div class="flex items-center p-4 bg-orange-50 dark:bg-[#C15F3C]/10 rounded-xl">
                                <div class="w-12 h-12 bg-gradient-to-br from-[#C15F3C] to-[#DA7756] rounded-full flex items-center justify-center text-white font-bold mr-4">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">Student</p>
                                    <p class="font-semibold text-gray-900 dark:text-white" x-text="selectedClass?.student"></p>
                                </div>
                            </div>

                            <!-- Tutor -->
                            <div class="flex items-center p-4 bg-amber-50 dark:bg-amber-900/20 rounded-xl">
                                <div class="w-12 h-12 bg-gradient-to-br from-[#DA7756] to-[#C15F3C] rounded-full flex items-center justify-center text-white font-bold mr-4">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">Tutor</p>
                                    <p class="font-semibold text-gray-900 dark:text-white" x-text="selectedClass?.tutor"></p>
                                </div>
                            </div>

                            <!-- Class Time -->
                            <div class="flex items-center p-4 bg-yellow-50 dark:bg-yellow-900/20 rounded-xl">
                                <div class="w-12 h-12 bg-gradient-to-br from-amber-500 to-orange-600 rounded-full flex items-center justify-center text-white font-bold mr-4">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">Class Time</p>
                                    <p class="font-semibold text-gray-900 dark:text-white" x-text="selectedClass?.time"></p>
                                </div>
                            </div>

                            <!-- Class Link -->
                            <template x-if="selectedClass?.class_link">
                                <a :href="selectedClass?.class_link" target="_blank" class="flex items-center p-4 bg-green-50 dark:bg-green-900/30 rounded-xl hover:bg-green-100 dark:hover:bg-green-900/50 transition-colors">
                                    <div class="w-12 h-12 bg-gradient-to-br from-green-500 to-lime-600 rounded-full flex items-center justify-center text-white font-bold mr-4">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="text-xs text-gray-500 dark:text-gray-400">Class Link</p>
                                        <p class="font-semibold text-green-600 dark:text-green-400">Click to join class &rarr;</p>
                                    </div>
                                </a>
                            </template>
                            <template x-if="!selectedClass?.class_link">
                                <div class="flex items-center p-4 bg-gray-50 dark:bg-gray-700/50 rounded-xl">
                                    <div class="w-12 h-12 bg-gray-300 dark:bg-gray-600 rounded-full flex items-center justify-center text-gray-500 dark:text-gray-400 mr-4">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/>
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="text-xs text-gray-500 dark:text-gray-400">Class Link</p>
                                        <p class="font-semibold text-gray-500 dark:text-gray-400">No link available</p>
                                    </div>
                                </div>
                            </template>
                        </div>

                        <!-- Modal Footer -->
                        <div class="mt-6 pt-4 border-t border-gray-200 dark:border-gray-700">
                            <button @click="showModal = false" class="w-full px-4 py-2 bg-gradient-to-r from-[#C15F3C] to-[#DA7756] text-white font-semibold rounded-lg hover:from-[#A34E30] hover:to-[#C15F3C] transition-all">
                                Close
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Section 3: Recent Reports & Assessments --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
            {{-- Recent Submitted Reports --}}
            <div class="bg-white/40 dark:bg-gray-800/40 backdrop-blur-xl border border-white/20 dark:border-gray-700/30 rounded-2xl shadow-lg p-6">
                <div class="flex items-center justify-between mb-6">
                    <div class="flex items-center gap-3">
                        <div class="bg-gradient-to-r from-blue-500 to-indigo-500 p-2 rounded-lg">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 dark:text-white">Recent Submitted Reports</h3>
                    </div>
                    <a href="{{ route('manager.tutor-reports.index') }}" class="text-[#C15F3C] hover:text-[#A34E30] dark:text-[#DA7756] text-sm font-medium">View All &rarr;</a>
                </div>

                <div class="space-y-3 max-h-64 overflow-y-auto">
                    @forelse($recentReports as $report)
                        <a href="{{ route('manager.tutor-reports.show', $report) }}" class="block bg-white/50 dark:bg-gray-700/50 backdrop-blur-sm rounded-xl p-4 border border-white/20 dark:border-gray-600/30 hover:bg-white/70 dark:hover:bg-gray-700/70 transition-all">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="font-semibold text-gray-900 dark:text-white">{{ $report->student->first_name ?? 'Student' }} {{ $report->student->last_name ?? '' }}</p>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">{{ $report->month ?? 'N/A' }} &bull; by {{ $report->tutor->first_name ?? 'Tutor' }}</p>
                                </div>
                                <span class="px-2 py-1 text-xs rounded-full font-medium
                                    @if($report->status === 'submitted') bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-300
                                    @elseif($report->status === 'approved-by-manager') bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-300
                                    @elseif($report->status === 'approved-by-director') bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-300
                                    @else bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300
                                    @endif">
                                    {{ ucfirst(str_replace('-', ' ', $report->status)) }}
                                </span>
                            </div>
                        </a>
                    @empty
                        <div class="text-center py-8">
                            <p class="text-gray-500 dark:text-gray-400">No recent reports</p>
                        </div>
                    @endforelse
                </div>
            </div>

            {{-- Recent Assessments Awaiting Director --}}
            <div class="bg-white/40 dark:bg-gray-800/40 backdrop-blur-xl border border-white/20 dark:border-gray-700/30 rounded-2xl shadow-lg p-6">
                <div class="flex items-center justify-between mb-6">
                    <div class="flex items-center gap-3">
                        <div class="bg-gradient-to-r from-purple-500 to-pink-500 p-2 rounded-lg">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 dark:text-white">Assessments Awaiting Director</h3>
                    </div>
                    <a href="{{ route('manager.assessments.index') }}" class="text-[#C15F3C] hover:text-[#A34E30] dark:text-[#DA7756] text-sm font-medium">View All &rarr;</a>
                </div>

                <div class="space-y-3 max-h-64 overflow-y-auto">
                    @forelse($recentAssessments as $assessment)
                        <a href="{{ route('manager.assessments.show', $assessment) }}" class="block bg-white/50 dark:bg-gray-700/50 backdrop-blur-sm rounded-xl p-4 border border-white/20 dark:border-gray-600/30 hover:bg-white/70 dark:hover:bg-gray-700/70 transition-all">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="font-semibold text-gray-900 dark:text-white">{{ $assessment->tutor->first_name ?? 'Tutor' }} {{ $assessment->tutor->last_name ?? '' }}</p>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">{{ $assessment->assessment_month ?? 'N/A' }}</p>
                                </div>
                                <span class="px-2 py-1 text-xs rounded-full font-medium
                                    @if($assessment->status === 'draft') bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300
                                    @elseif($assessment->status === 'submitted') bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-300
                                    @elseif($assessment->status === 'approved-by-manager') bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-300
                                    @else bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-300
                                    @endif">
                                    {{ ucfirst(str_replace('-', ' ', $assessment->status)) }}
                                </span>
                            </div>
                        </a>
                    @empty
                        <div class="text-center py-8">
                            <p class="text-gray-500 dark:text-gray-400">No assessments pending</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        {{-- Section 4: Notice Board & To-Do List --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
            {{-- Notice Board --}}
            <div class="bg-white/40 dark:bg-gray-800/40 backdrop-blur-xl border border-white/20 dark:border-gray-700/30 rounded-2xl shadow-lg p-6">
                <div class="flex items-center justify-between mb-6">
                    <div class="flex items-center gap-3">
                        <div class="bg-gradient-to-r from-[#C15F3C] to-[#DA7756] p-2 rounded-lg">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z" />
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 dark:text-white">Notice Board</h3>
                    </div>
                    <a href="{{ route('manager.notices.create') }}" class="inline-flex items-center px-3 py-1.5 bg-gradient-to-r from-[#C15F3C] to-[#DA7756] text-white text-sm font-medium rounded-lg hover:from-[#A34E30] hover:to-[#C15F3C] transition-all">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                        </svg>
                        Create
                    </a>
                </div>

                <div class="space-y-3 max-h-64 overflow-y-auto">
                    {{-- Birthday Notifications --}}
                    @if(!empty($todaysBirthdays))
                        @foreach($todaysBirthdays as $birthday)
                            <div class="bg-gradient-to-r from-pink-50 to-purple-50 dark:from-pink-900/20 dark:to-purple-900/20 rounded-xl p-4 border-l-4 border-pink-500">
                                <div class="flex items-center gap-3">
                                    <span class="text-2xl">🎂</span>
                                    <div>
                                        <p class="font-semibold text-gray-800 dark:text-white">
                                            Today is {{ $birthday['name'] }}'s Birthday!
                                        </p>
                                        <p class="text-sm text-gray-600 dark:text-gray-400">
                                            {{ $birthday['role'] }} • Celebrate them! 🎉
                                        </p>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @endif

                    @forelse($notices as $notice)
                        <div class="bg-white/50 dark:bg-gray-700/50 backdrop-blur-sm rounded-xl p-4 border border-white/20 dark:border-gray-600/30">
                            <div class="flex items-start justify-between">
                                <div class="flex-1">
                                    <p class="font-semibold text-gray-900 dark:text-white">{{ $notice->title }}</p>
                                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1 line-clamp-2">{{ Str::limit(strip_tags($notice->content), 100) }}</p>
                                    <p class="text-xs text-gray-400 dark:text-gray-500 mt-2">{{ $notice->published_at ? $notice->published_at->diffForHumans() : 'Draft' }}</p>
                                </div>
                            </div>
                        </div>
                    @empty
                        @if(empty($todaysBirthdays))
                            <div class="text-center py-8">
                                <p class="text-gray-500 dark:text-gray-400">No notices yet</p>
                            </div>
                        @endif
                    @endforelse
                </div>

                <a href="{{ route('manager.notices.index') }}" class="block mt-4 text-center text-[#C15F3C] hover:text-[#A34E30] dark:text-[#DA7756] text-sm font-medium">
                    View All Notices &rarr;
                </a>
            </div>

            {{-- To-Do List --}}
            <div class="bg-white/40 dark:bg-gray-800/40 backdrop-blur-xl border border-white/20 dark:border-gray-700/30 rounded-2xl shadow-lg p-6">
                <div class="flex items-center justify-between mb-6">
                    <div class="flex items-center gap-3">
                        <div class="bg-gradient-to-r from-[#DA7756] to-[#C15F3C] p-2 rounded-lg">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 dark:text-white">To-Do List</h3>
                    </div>
                </div>

                {{-- Auto-generated Tasks --}}
                <div class="space-y-2 mb-4">
                    @if(isset($todos) && count($todos) > 0)
                        @foreach($todos as $todo)
                            <a href="{{ $todo['link'] }}" class="flex items-center gap-3 p-3 rounded-lg transition-all
                                @if($todo['completed']) bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800/30
                                @elseif($todo['priority'] === 'high') bg-rose-50 dark:bg-rose-900/20 border border-rose-200 dark:border-rose-800/30 hover:bg-rose-100 dark:hover:bg-rose-900/30
                                @elseif($todo['priority'] === 'medium') bg-amber-50 dark:bg-amber-900/20 border border-amber-200 dark:border-amber-800/30 hover:bg-amber-100 dark:hover:bg-amber-900/30
                                @else bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 hover:bg-slate-100 dark:hover:bg-slate-800
                                @endif">
                                <div class="w-6 h-6 rounded-full flex items-center justify-center flex-shrink-0
                                    @if($todo['completed']) bg-green-500 text-white
                                    @elseif($todo['priority'] === 'high') bg-rose-500 text-white
                                    @elseif($todo['priority'] === 'medium') bg-amber-500 text-white
                                    @else bg-slate-400 text-white
                                    @endif">
                                    @if($todo['completed'])
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                        </svg>
                                    @else
                                        <span class="text-xs font-bold">{{ $todo['count'] }}</span>
                                    @endif
                                </div>
                                <span class="flex-1 text-sm font-medium
                                    @if($todo['completed']) text-green-700 dark:text-green-400 line-through
                                    @elseif($todo['priority'] === 'high') text-rose-700 dark:text-rose-400
                                    @elseif($todo['priority'] === 'medium') text-amber-700 dark:text-amber-400
                                    @else text-slate-700 dark:text-slate-300
                                    @endif">{{ $todo['text'] }}</span>
                                <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                </svg>
                            </a>
                        @endforeach
                    @endif
                </div>

                {{-- Divider --}}
                <div class="border-t border-slate-200 dark:border-slate-700 my-4"></div>

                {{-- Add Custom Task Form --}}
                <div class="flex gap-2 mb-4">
                    <input type="text" id="newTaskInput" placeholder="Add a custom task..."
                           class="flex-1 px-4 py-2 bg-white/50 dark:bg-gray-700/50 border border-white/20 dark:border-gray-600 rounded-lg text-gray-900 dark:text-white placeholder-gray-500 focus:ring-2 focus:ring-[#C15F3C] focus:border-transparent">
                    <button id="addTaskBtn" class="px-4 py-2 bg-gradient-to-r from-[#C15F3C] to-[#DA7756] text-white rounded-lg hover:from-[#A34E30] hover:to-[#C15F3C] transition-all">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                        </svg>
                    </button>
                </div>

                {{-- Custom Task List --}}
                <div id="todoList" class="space-y-2 max-h-32 overflow-y-auto">
                    {{-- Tasks will be rendered here by JavaScript --}}
                </div>
            </div>
        </div>

        {{-- Section 5: Quick Actions --}}
        <div class="bg-white/40 dark:bg-gray-800/40 backdrop-blur-xl border border-white/20 dark:border-gray-700/30 rounded-2xl shadow-lg p-6">
            <div class="flex items-center gap-3 mb-6">
                <div class="bg-gradient-to-r from-[#C15F3C] to-[#DA7756] p-2 rounded-lg">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                    </svg>
                </div>
                <h3 class="text-2xl font-bold text-gray-900 dark:text-white">Quick Actions</h3>
            </div>

            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-4">
                {{-- Approve Reports --}}
                <a href="{{ route('manager.tutor-reports.index') }}" class="group bg-white/50 dark:bg-gray-700/50 backdrop-blur-sm rounded-xl p-6 border border-white/20 dark:border-gray-600/30 hover:bg-gradient-to-r hover:from-[#C15F3C] hover:to-[#DA7756] transition-all duration-300 text-center">
                    <div class="bg-gradient-to-r from-blue-500 to-indigo-500 group-hover:from-white/20 group-hover:to-white/20 w-14 h-14 rounded-xl flex items-center justify-center mx-auto mb-4 transition-all">
                        <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <p class="font-semibold text-gray-900 dark:text-white group-hover:text-white transition-colors">Approve Reports</p>
                    <p class="text-sm text-gray-500 dark:text-gray-400 group-hover:text-white/80 mt-1">{{ $stats['pendingReports'] }} pending</p>
                </a>

                {{-- Approve Assessment --}}
                <a href="{{ route('manager.assessments.index') }}" class="group bg-white/50 dark:bg-gray-700/50 backdrop-blur-sm rounded-xl p-6 border border-white/20 dark:border-gray-600/30 hover:bg-gradient-to-r hover:from-[#C15F3C] hover:to-[#DA7756] transition-all duration-300 text-center">
                    <div class="bg-gradient-to-r from-purple-500 to-pink-500 group-hover:from-white/20 group-hover:to-white/20 w-14 h-14 rounded-xl flex items-center justify-center mx-auto mb-4 transition-all">
                        <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                        </svg>
                    </div>
                    <p class="font-semibold text-gray-900 dark:text-white group-hover:text-white transition-colors">Assessments</p>
                    <p class="text-sm text-gray-500 dark:text-gray-400 group-hover:text-white/80 mt-1">{{ $stats['pendingAssessments'] }} pending</p>
                </a>

                {{-- View Students --}}
                <a href="{{ route('manager.students.index') }}" class="group bg-white/50 dark:bg-gray-700/50 backdrop-blur-sm rounded-xl p-6 border border-white/20 dark:border-gray-600/30 hover:bg-gradient-to-r hover:from-[#C15F3C] hover:to-[#DA7756] transition-all duration-300 text-center">
                    <div class="bg-gradient-to-r from-[#C15F3C] to-[#DA7756] group-hover:from-white/20 group-hover:to-white/20 w-14 h-14 rounded-xl flex items-center justify-center mx-auto mb-4 transition-all">
                        <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                        </svg>
                    </div>
                    <p class="font-semibold text-gray-900 dark:text-white group-hover:text-white transition-colors">View Students</p>
                    <p class="text-sm text-gray-500 dark:text-gray-400 group-hover:text-white/80 mt-1">{{ $stats['activeStudents'] }} active</p>
                </a>

                {{-- View Tutors --}}
                <a href="{{ route('manager.tutors.index') }}" class="group bg-white/50 dark:bg-gray-700/50 backdrop-blur-sm rounded-xl p-6 border border-white/20 dark:border-gray-600/30 hover:bg-gradient-to-r hover:from-[#C15F3C] hover:to-[#DA7756] transition-all duration-300 text-center">
                    <div class="bg-gradient-to-r from-[#DA7756] to-[#C15F3C] group-hover:from-white/20 group-hover:to-white/20 w-14 h-14 rounded-xl flex items-center justify-center mx-auto mb-4 transition-all">
                        <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                    </div>
                    <p class="font-semibold text-gray-900 dark:text-white group-hover:text-white transition-colors">View Tutors</p>
                    <p class="text-sm text-gray-500 dark:text-gray-400 group-hover:text-white/80 mt-1">{{ $stats['activeTutors'] }} active</p>
                </a>

                {{-- View Attendance --}}
                <a href="{{ route('manager.attendance.index') }}" class="group bg-white/50 dark:bg-gray-700/50 backdrop-blur-sm rounded-xl p-6 border border-white/20 dark:border-gray-600/30 hover:bg-gradient-to-r hover:from-[#C15F3C] hover:to-[#DA7756] transition-all duration-300 text-center">
                    <div class="bg-gradient-to-r from-amber-500 to-orange-500 group-hover:from-white/20 group-hover:to-white/20 w-14 h-14 rounded-xl flex items-center justify-center mx-auto mb-4 transition-all">
                        <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                        </svg>
                    </div>
                    <p class="font-semibold text-gray-900 dark:text-white group-hover:text-white transition-colors">Attendance</p>
                    <p class="text-sm text-gray-500 dark:text-gray-400 group-hover:text-white/80 mt-1">{{ $stats['pendingAttendance'] }} pending</p>
                </a>
            </div>
        </div>

    </div>

    {{-- To-Do List JavaScript --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const todoList = document.getElementById('todoList');
            const newTaskInput = document.getElementById('newTaskInput');
            const addTaskBtn = document.getElementById('addTaskBtn');

            function loadTasks() {
                const tasks = localStorage.getItem('managerTodos');
                return tasks ? JSON.parse(tasks) : [];
            }

            function saveTasks(tasks) {
                localStorage.setItem('managerTodos', JSON.stringify(tasks));
            }

            function renderTasks() {
                const tasks = loadTasks();
                todoList.innerHTML = '';

                if (tasks.length === 0) {
                    todoList.innerHTML = '<p class="text-center text-gray-500 dark:text-gray-400 py-4">No tasks yet. Add one above!</p>';
                    return;
                }

                tasks.forEach(task => {
                    const taskEl = document.createElement('label');
                    taskEl.className = 'flex items-center gap-3 p-3 bg-white/50 dark:bg-gray-700/50 backdrop-blur-sm rounded-lg border border-white/20 dark:border-gray-600/30 hover:bg-white/70 dark:hover:bg-gray-700/70 transition-all cursor-pointer group';

                    taskEl.innerHTML = `
                        <input type="checkbox" ${task.checked ? 'checked' : ''}
                            data-id="${task.id}"
                            class="task-checkbox w-5 h-5 text-[#C15F3C] border-gray-300 rounded focus:ring-[#C15F3C] focus:ring-2">
                        <span class="text-gray-700 dark:text-gray-300 flex-1 ${task.checked ? 'line-through opacity-60' : ''}">${task.text}</span>
                        <button class="remove-task opacity-0 group-hover:opacity-100 transition-opacity text-red-500 hover:text-red-700 p-1" data-id="${task.id}">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                            </svg>
                        </button>
                    `;

                    todoList.appendChild(taskEl);
                });

                document.querySelectorAll('.task-checkbox').forEach(checkbox => {
                    checkbox.addEventListener('change', toggleTask);
                });

                document.querySelectorAll('.remove-task').forEach(btn => {
                    btn.addEventListener('click', removeTask);
                });
            }

            function toggleTask(e) {
                const taskId = parseInt(e.target.dataset.id);
                const tasks = loadTasks();
                const task = tasks.find(t => t.id === taskId);
                if (task) {
                    task.checked = e.target.checked;
                    saveTasks(tasks);
                    renderTasks();
                }
            }

            function removeTask(e) {
                e.preventDefault();
                const taskId = parseInt(e.currentTarget.dataset.id);
                let tasks = loadTasks();
                tasks = tasks.filter(t => t.id !== taskId);
                saveTasks(tasks);
                renderTasks();
            }

            function addTask() {
                const taskText = newTaskInput.value.trim();
                if (taskText) {
                    const tasks = loadTasks();
                    tasks.push({
                        id: Date.now(),
                        text: taskText,
                        checked: false
                    });
                    saveTasks(tasks);
                    newTaskInput.value = '';
                    renderTasks();
                }
            }

            addTaskBtn.addEventListener('click', addTask);
            newTaskInput.addEventListener('keypress', function(e) {
                if (e.key === 'Enter') {
                    addTask();
                }
            });

            renderTasks();
        });
    </script>
</x-manager-layout>
