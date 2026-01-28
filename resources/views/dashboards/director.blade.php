<x-app-layout>
    <x-slot name="header">
        {{ __('Director Dashboard') }}
    </x-slot>

    <x-slot name="title">{{ __('Director Dashboard') }}</x-slot>

    <!-- Animated Background -->
    <div class="min-h-screen bg-gradient-to-br from-blue-50 via-purple-50 to-pink-50 dark:from-gray-900 dark:via-gray-800 dark:to-gray-900 py-12 relative overflow-hidden">
        <!-- Floating Orbs Background -->
        <div class="absolute top-0 left-0 w-72 h-72 bg-purple-300 dark:bg-purple-900 rounded-full mix-blend-multiply filter blur-xl opacity-70 animate-float"></div>
        <div class="absolute top-0 right-0 w-72 h-72 bg-yellow-300 dark:bg-yellow-900 rounded-full mix-blend-multiply filter blur-xl opacity-70 animate-float" style="animation-delay: 2s;"></div>
        <div class="absolute -bottom-8 left-20 w-72 h-72 bg-pink-300 dark:bg-pink-900 rounded-full mix-blend-multiply filter blur-xl opacity-70 animate-float" style="animation-delay: 4s;"></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">

            <!-- Welcome Section -->
            <x-ui.glass-card padding="p-8" class="mb-8">
                <div class="flex items-center justify-between flex-wrap">
                    <div>
                        <h3 class="text-3xl font-bold text-gray-900 dark:text-white mb-2">
                            Welcome back, <span class="text-transparent bg-clip-text bg-gradient-to-r from-[#4F46E5] to-[#818CF8]">{{ Auth::user()->name }}</span>!
                        </h3>
                        <p class="text-gray-600 dark:text-gray-300 text-lg">Here's what's happening with your organization today.</p>
                    </div>
                    <div class="text-right mt-4 md:mt-0">
                        <div class="text-gray-600 dark:text-gray-300">{{ now()->format('l, F j, Y') }}</div>
                        <div class="text-gray-500 dark:text-gray-400 text-sm">{{ now()->format('g:i A') }}</div>
                    </div>
                </div>
            </x-ui.glass-card>

            <!-- Main Stats Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <x-ui.stat-card
                    title="Total Students"
                    value="{{ $totalStudents }}"
                    icon='<svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>'
                    gradient="bg-gradient-to-br from-blue-500 to-cyan-600"
                />

                <x-ui.stat-card
                    title="Total Tutors"
                    value="{{ $totalTutors }}"
                    icon='<svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>'
                    gradient="bg-gradient-to-br from-purple-500 to-pink-600"
                />

                <x-ui.stat-card
                    title="Attendance Rate"
                    value="{{ $attendanceRate }}%"
                    icon='<svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>'
                    gradient="bg-gradient-to-br from-green-500 to-emerald-600"
                />

                <x-ui.stat-card
                    title="Monthly Reports"
                    value="{{ $monthlyReports }}"
                    icon='<svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>'
                    gradient="bg-gradient-to-br from-orange-500 to-red-600"
                />
            </div>

            <!-- Class Schedule & To-Do Section -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8" x-data="{
                showModal: false,
                selectedClass: null,
                openModal(classData) {
                    this.selectedClass = classData;
                    this.showModal = true;
                }
            }">
                <!-- Today's Class Schedule -->
                <x-ui.glass-card>
                    <div class="flex items-center justify-between mb-4">
                        <div>
                            <x-ui.section-title>Today's Classes</x-ui.section-title>
                            @if($schedulePosted ?? false)
                                <p class="text-xs text-emerald-600 dark:text-emerald-400 mt-1">
                                    ✅ Posted by Admin {{ $schedulePostedAt ? \Carbon\Carbon::parse($schedulePostedAt)->format('g:i A') : '' }}
                                </p>
                            @else
                                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">{{ now()->format('l, M j') }}</p>
                            @endif
                        </div>
                        <span class="text-sm text-gray-500 dark:text-gray-400">{{ count($todayClasses) }} classes</span>
                    </div>

                    @if(count($todayClasses) > 0)
                        <div class="space-y-3 max-h-80 overflow-y-auto">
                            @foreach($todayClasses as $index => $class)
                                @php
                                    $isRescheduled = isset($class['is_rescheduled']) && $class['is_rescheduled'];
                                @endphp
                                <div class="flex items-center p-3 rounded-lg transition-colors cursor-pointer {{ $isRescheduled ? 'bg-amber-50 dark:bg-amber-900/20 border-2 border-amber-300 dark:border-amber-700/50 hover:bg-amber-100 dark:hover:bg-amber-900/30' : 'bg-gray-50 dark:bg-gray-700/50 hover:bg-gray-100 dark:hover:bg-gray-700' }}"
                                     @click="openModal({
                                         time: '{{ $class['time'] }}',
                                         student: '{{ $class['student'] }}',
                                         tutor: '{{ $class['tutor'] }}',
                                         level: '{{ $class['level'] ?? 'Not specified' }}',
                                         class_link: '{{ $class['class_link'] ?? '' }}',
                                         is_rescheduled: {{ $isRescheduled ? 'true' : 'false' }},
                                         original_date: '{{ $class['original_date'] ?? '' }}'
                                     })">
                                    <div class="w-16 text-center">
                                        @php
                                            try {
                                                $formattedTime = \Carbon\Carbon::parse($class['time'])->format('g:i A');
                                            } catch (\Exception $e) {
                                                $formattedTime = $class['time'];
                                            }
                                        @endphp
                                        <span class="text-sm font-bold {{ $isRescheduled ? 'text-amber-600 dark:text-amber-400' : 'text-[#4F46E5] dark:text-[#818CF8]' }}">{{ $formattedTime }}</span>
                                    </div>
                                    <div class="flex-1 ml-4">
                                        <div class="flex items-center gap-2">
                                            <p class="font-medium text-gray-900 dark:text-white">{{ $class['student'] }}</p>
                                            @if($isRescheduled)
                                                <span class="inline-flex items-center text-xs px-2 py-0.5 rounded-full bg-amber-200 text-amber-800 dark:bg-amber-900/50 dark:text-amber-300 font-medium">
                                                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/>
                                                    </svg>
                                                    Rescheduled
                                                </span>
                                            @endif
                                        </div>
                                        <p class="text-sm {{ $isRescheduled ? 'text-amber-700 dark:text-amber-400' : 'text-gray-500 dark:text-gray-400' }}">
                                            <span class="inline-flex items-center">
                                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                                </svg>
                                                {{ $class['tutor'] }}
                                                @if($isRescheduled && isset($class['original_date']))
                                                    • From {{ \Carbon\Carbon::parse($class['original_date'])->format('M j') }}
                                                @endif
                                            </span>
                                        </p>
                                    </div>
                                    <div class="text-right">
                                        @if(isset($class['class_link']) && $class['class_link'])
                                            <span class="text-xs px-2 py-1 rounded-full {{ $isRescheduled ? 'bg-amber-200 text-amber-800 dark:bg-amber-900/50 dark:text-amber-300' : 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' }}">
                                                Has Link
                                            </span>
                                        @else
                                            <span class="text-xs px-2 py-1 rounded-full bg-gray-100 text-gray-600 dark:bg-gray-700 dark:text-gray-400">
                                                Click for details
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-8">
                            <svg class="w-12 h-12 mx-auto text-gray-300 dark:text-gray-600 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                            <p class="text-gray-500 dark:text-gray-400">No classes scheduled for today</p>
                        </div>
                    @endif
                </x-ui.glass-card>

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
                                <div class="flex items-center p-4 bg-blue-50 dark:bg-blue-900/30 rounded-xl">
                                    <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-cyan-600 rounded-full flex items-center justify-center text-white font-bold mr-4">
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
                                <div class="flex items-center p-4 bg-purple-50 dark:bg-purple-900/30 rounded-xl">
                                    <div class="w-12 h-12 bg-gradient-to-br from-purple-500 to-pink-600 rounded-full flex items-center justify-center text-white font-bold mr-4">
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
                                <div class="flex items-center p-4 bg-emerald-50 dark:bg-emerald-900/30 rounded-xl">
                                    <div class="w-12 h-12 bg-gradient-to-br from-emerald-500 to-teal-600 rounded-full flex items-center justify-center text-white font-bold mr-4">
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
                                            <p class="font-semibold text-green-600 dark:text-green-400">Click to join class →</p>
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
                                <button @click="showModal = false" class="w-full px-4 py-2 bg-gradient-to-r from-[#4F46E5] to-[#818CF8] text-white font-semibold rounded-lg hover:from-[#3730A3] hover:to-[#4F46E5] transition-all">
                                    Close
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Today's To-Do List -->
                <div
                    class="bg-white/60 dark:bg-gray-800/60 backdrop-blur-xl rounded-2xl p-6 shadow-xl border border-white/20 dark:border-gray-700/30"
                    role="region"
                    aria-label="Today's To-Do List"
                    x-data="{
                        todos: [],
                        newTodo: '',
                        newDate: '',
                        newTime: '',
                        init() {
                            const stored = localStorage.getItem('director_todos');
                            if (stored) {
                                try {
                                    this.todos = JSON.parse(stored);
                                } catch (e) {
                                    console.error('Failed to parse stored todos', e);
                                    this.todos = this.getDefaultTodos();
                                }
                            } else {
                                this.todos = this.getDefaultTodos();
                                this.saveTodos();
                            }
                        },
                        getDefaultTodos() {
                            return [
                                { text: 'Post today\'s schedule', completed: false },
                                { text: 'Review pending attendance', completed: false },
                                { text: 'Follow up inactive students', completed: false },
                                { text: 'Approve tutor submissions', completed: false }
                            ];
                        },
                        toggleTodo(index) {
                            this.todos[index].completed = !this.todos[index].completed;
                            this.saveTodos();
                        },
                        addTodo() {
                            if (this.newTodo.trim()) {
                                this.todos.push({
                                    text: this.newTodo.trim(),
                                    date: this.newDate || null,
                                    time: this.newTime || null,
                                    completed: false
                                });
                                this.newTodo = '';
                                this.newDate = '';
                                this.newTime = '';
                                this.saveTodos();
                            }
                        },
                        removeTodo(index) {
                            this.todos.splice(index, 1);
                            this.saveTodos();
                        },
                        saveTodos() {
                            localStorage.setItem('director_todos', JSON.stringify(this.todos));
                        },
                        handleKeydown(event, index) {
                            if (event.key === 'Enter' || event.key === ' ') {
                                event.preventDefault();
                                this.toggleTodo(index);
                            }
                        },
                        formatDateTime(date, time) {
                            if (!date) return '';
                            const d = new Date(date);
                            const dateStr = d.toLocaleDateString('en-US', { month: 'short', day: 'numeric' });
                            return time ? `${dateStr} at ${time}` : dateStr;
                        }
                    }"
                >
                    <div class="mb-6">
                        <h3 class="text-xl font-bold text-gray-900 dark:text-white flex items-center">
                            <svg class="w-5 h-5 mr-2 text-[#4F46E5]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                            </svg>
                            Today's To-Do List
                        </h3>
                        <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Keep track of your daily tasks</p>
                    </div>

                    <div class="space-y-3 mb-6 max-h-64 overflow-y-auto" role="list" aria-label="Task list">
                        <template x-for="(todo, index) in todos" :key="index">
                            <div class="flex items-start p-3 rounded-lg bg-white dark:bg-slate-800/50 border border-gray-200 dark:border-gray-700 hover:shadow-md transition-all duration-200 group" role="listitem">
                                <input
                                    type="checkbox"
                                    :id="'todo-' + index"
                                    x-model="todo.completed"
                                    @change="saveTodos()"
                                    @keydown="handleKeydown($event, index)"
                                    class="mt-0.5 w-5 h-5 text-[#4F46E5] border-gray-300 dark:border-gray-600 rounded focus:ring-[#4F46E5] focus:ring-2 cursor-pointer transition-all focus-visible:ring-2 focus-visible:ring-[#4F46E5] focus-visible:ring-offset-2"
                                    :aria-label="'Mark task as ' + (todo.completed ? 'incomplete' : 'complete')"
                                >
                                <label
                                    :for="'todo-' + index"
                                    class="flex-1 ml-3 cursor-pointer"
                                >
                                    <div class="text-gray-800 dark:text-gray-200 group-hover:text-[#4F46E5] dark:group-hover:text-[#818CF8] transition-colors text-sm" :class="{ 'line-through opacity-50': todo.completed }" x-text="todo.text"></div>
                                    <div class="text-xs text-gray-500 dark:text-gray-400 mt-0.5" x-show="todo.date" x-text="formatDateTime(todo.date, todo.time)"></div>
                                </label>
                                <button
                                    type="button"
                                    @click="removeTodo(index)"
                                    class="opacity-0 group-hover:opacity-100 text-red-500 hover:text-red-700 dark:text-red-400 dark:hover:text-red-600 transition-opacity focus:opacity-100 focus:outline-none focus-visible:ring-2 focus-visible:ring-red-500 focus-visible:ring-offset-2 rounded p-1"
                                    :aria-label="'Remove task: ' + todo.text"
                                >
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                </button>
                            </div>
                        </template>

                        <div x-show="todos.length === 0" class="text-center py-8">
                            <div class="w-16 h-16 mx-auto mb-4 rounded-xl bg-gradient-to-br from-[#4F46E5] to-[#818CF8] flex items-center justify-center opacity-50">
                                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                </svg>
                            </div>
                            <p class="text-gray-500 dark:text-gray-400 text-sm">No tasks yet. Add one below!</p>
                        </div>
                    </div>

                    {{-- Add New Todo --}}
                    <form @submit.prevent="addTodo" class="space-y-3 mb-4">
                        <input
                            type="text"
                            x-model="newTodo"
                            placeholder="Add a new task..."
                            class="w-full px-4 py-2.5 text-sm border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-[#4F46E5] focus:border-[#4F46E5] dark:bg-slate-800 dark:text-white focus-visible:ring-2 focus-visible:ring-[#4F46E5]"
                            aria-label="New task input"
                        >
                        <input
                            type="date"
                            x-model="newDate"
                            class="w-full px-4 py-2.5 text-sm text-gray-900 bg-white border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-slate-800 dark:text-white focus:ring-2 focus:ring-[#4F46E5] focus:border-[#4F46E5] focus-visible:ring-2 focus-visible:ring-[#4F46E5]"
                            aria-label="Task date"
                        >
                        <input
                            type="time"
                            x-model="newTime"
                            class="w-full px-4 py-2.5 text-sm text-gray-900 bg-white border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-slate-800 dark:text-white focus:ring-2 focus:ring-[#4F46E5] focus:border-[#4F46E5] focus-visible:ring-2 focus-visible:ring-[#4F46E5]"
                            aria-label="Task time"
                        >
                        <button
                            type="submit"
                            class="w-full px-5 py-2.5 bg-gradient-to-r from-[#4F46E5] to-[#818CF8] text-white font-semibold rounded-lg shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#4F46E5] focus-visible:ring-2 focus-visible:ring-[#4F46E5]"
                            aria-label="Add task"
                        >
                            <svg class="w-5 h-5 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                            </svg>
                        </button>
                    </form>

                    <div class="p-3 rounded-xl bg-gradient-to-r from-indigo-50 to-purple-50 dark:from-indigo-900/30 dark:to-purple-900/30 border border-indigo-200 dark:border-indigo-700">
                        <p class="text-xs text-gray-700 dark:text-gray-300 flex items-center">
                            <svg class="w-4 h-4 mr-2 text-[#4F46E5] dark:text-[#818CF8]" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            Tasks are saved automatically in your browser
                        </p>
                    </div>
                </div>
            </div>

            <!-- Charts and Analytics Section -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">

                <!-- Notice Board -->
                <div class="lg:col-span-2">
                    <x-ui.glass-card class="h-full flex flex-col" style="height: 420px;">
                        <div class="flex items-center justify-between mb-4 flex-shrink-0">
                            <x-ui.section-title>Notice Board</x-ui.section-title>
                            <a href="{{ route('director.notices.create') }}" class="text-sm text-[#4F46E5] dark:text-[#818CF8] hover:underline font-medium">+ Create Notice</a>
                        </div>
                        <div class="space-y-4 flex-1 overflow-y-auto min-h-0">
                            @if(($notices ?? collect())->isEmpty())
                                <div class="text-center py-8 text-gray-500 dark:text-gray-400">
                                    <svg class="w-12 h-12 mx-auto text-gray-300 dark:text-gray-600 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"/>
                                    </svg>
                                    <p>No notices published yet</p>
                                </div>
                            @else
                                @foreach($notices as $notice)
                                    <div class="p-4 bg-gray-50 dark:bg-gray-700/50 rounded-xl border-l-4
                                        @if($notice->priority === 'urgent') border-red-500
                                        @elseif($notice->priority === 'high') border-amber-500
                                        @else border-blue-500
                                        @endif">
                                        <div class="flex items-start justify-between gap-2">
                                            <h4 class="font-semibold text-gray-800 dark:text-white">{{ $notice->title }}</h4>
                                            @if($notice->priority === 'urgent')
                                                <span class="px-2 py-0.5 text-xs font-semibold bg-red-100 text-red-700 dark:bg-red-900/50 dark:text-red-300 rounded-full">Urgent</span>
                                            @elseif($notice->priority === 'high')
                                                <span class="px-2 py-0.5 text-xs font-semibold bg-amber-100 text-amber-700 dark:bg-amber-900/50 dark:text-amber-300 rounded-full">High</span>
                                            @endif
                                        </div>
                                        <p class="text-sm text-gray-600 dark:text-gray-400 mt-1 line-clamp-2">{{ Str::limit($notice->content, 100) }}</p>
                                        <p class="text-xs text-gray-500 mt-2">{{ $notice->created_at->diffForHumans() }}</p>
                                    </div>
                                @endforeach
                                <a href="{{ route('director.notices.index') }}" class="block text-center mt-4 text-[#4F46E5] dark:text-[#818CF8] hover:underline text-sm">View All Notices →</a>
                            @endif
                        </div>
                    </x-ui.glass-card>
                </div>

                <!-- Student Distribution Chart -->
                <x-ui.glass-card class="h-full" style="max-height: 420px;">
                    <x-ui.section-title>Student Distribution</x-ui.section-title>
                    <p class="text-sm text-gray-600 dark:text-gray-300 mb-4">By status</p>
                    <div class="h-72">
                        <canvas id="studentDistributionChart"></canvas>
                    </div>
                </x-ui.glass-card>

            </div>

            <!-- Attendance & Activity Section -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">

                <!-- Weekly Attendance Chart -->
                <x-ui.glass-card>
                    <x-ui.section-title>Weekly Attendance</x-ui.section-title>
                    <p class="text-sm text-gray-600 dark:text-gray-300 mb-4">Last 7 days attendance tracking</p>
                    <div class="h-64">
                        <canvas id="attendanceChart"></canvas>
                    </div>
                </x-ui.glass-card>

                <!-- Recent Activity Feed -->
                <x-ui.glass-card>
                    <div class="flex items-center justify-between mb-4">
                        <x-ui.section-title>Recent Activity</x-ui.section-title>
                        <a href="{{ route('director.activity-logs.index') }}" class="text-sm text-[#4F46E5] hover:text-[#3730A3] dark:text-[#818CF8]">View All</a>
                    </div>
                    <p class="text-sm text-gray-600 dark:text-gray-300 mb-4">Latest updates and actions</p>
                    <div class="space-y-4 max-h-64 overflow-y-auto">
                        @foreach($recentActivities as $activity)
                            <div class="flex items-start space-x-3 pb-4 border-b border-gray-200 dark:border-gray-700 last:border-0">
                                <div class="w-10 h-10 rounded-full bg-gradient-to-br {{ $activity['gradient'] }} flex items-center justify-center flex-shrink-0">
                                    @switch($activity['icon'])
                                        @case('document-check')
                                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                            </svg>
                                            @break
                                        @case('x-circle')
                                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                            </svg>
                                            @break
                                        @case('academic-cap')
                                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path d="M12 14l9-5-9-5-9 5 9 5z"/><path d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"/>
                                            </svg>
                                            @break
                                        @case('clipboard-check')
                                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                                            </svg>
                                            @break
                                        @case('currency-dollar')
                                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                            </svg>
                                            @break
                                        @case('speakerphone')
                                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"/>
                                            </svg>
                                            @break
                                        @default
                                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/>
                                            </svg>
                                    @endswitch
                                </div>
                                <div class="flex-1">
                                    <p class="text-sm font-semibold text-gray-900 dark:text-gray-100">{{ $activity['title'] }}</p>
                                    <p class="text-xs text-gray-600 dark:text-gray-400">{{ $activity['description'] }}</p>
                                    <p class="text-xs text-gray-400 mt-1">{{ $activity['time'] }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </x-ui.glass-card>

            </div>

            <!-- Quick Actions Grid -->
            <x-ui.glass-card>
                <x-ui.section-title>Quick Actions</x-ui.section-title>
                <p class="text-sm text-gray-600 dark:text-gray-300 mb-6">Frequently used actions</p>

                <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-4">
                    <a href="{{ route('director.students.index') }}" class="group">
                        <div class="rounded-xl bg-white/10 dark:bg-gray-900/10 p-6 text-center hover:-translate-y-1 hover:shadow-xl transition-all duration-300">
                            <div class="w-16 h-16 mx-auto mb-3 rounded-xl bg-gradient-to-br from-[#4F46E5] to-[#818CF8] flex items-center justify-center shadow-lg group-hover:scale-110 transition-transform">
                                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                                </svg>
                            </div>
                            <span class="text-sm font-semibold text-gray-700 dark:text-gray-300 group-hover:text-[#4F46E5]">Students</span>
                        </div>
                    </a>

                    <a href="{{ route('director.tutors.index') }}" class="group">
                        <div class="rounded-xl bg-white/10 dark:bg-gray-900/10 p-6 text-center hover:-translate-y-1 hover:shadow-xl transition-all duration-300">
                            <div class="w-16 h-16 mx-auto mb-3 rounded-xl bg-gradient-to-br from-purple-500 to-pink-600 flex items-center justify-center shadow-lg group-hover:scale-110 transition-transform">
                                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                                </svg>
                            </div>
                            <span class="text-sm font-semibold text-gray-700 dark:text-gray-300 group-hover:text-purple-600">Tutors</span>
                        </div>
                    </a>

                    <a href="{{ route('director.reports.index') }}" class="group">
                        <div class="rounded-xl bg-white/10 dark:bg-gray-900/10 p-6 text-center hover:-translate-y-1 hover:shadow-xl transition-all duration-300">
                            <div class="w-16 h-16 mx-auto mb-3 rounded-xl bg-gradient-to-br from-green-500 to-emerald-600 flex items-center justify-center shadow-lg group-hover:scale-110 transition-transform">
                                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                            </div>
                            <span class="text-sm font-semibold text-gray-700 dark:text-gray-300 group-hover:text-green-600">Reports</span>
                        </div>
                    </a>

                    <a href="{{ route('director.assessments.index') }}" class="group">
                        <div class="rounded-xl bg-white/10 dark:bg-gray-900/10 p-6 text-center hover:-translate-y-1 hover:shadow-xl transition-all duration-300">
                            <div class="w-16 h-16 mx-auto mb-3 rounded-xl bg-gradient-to-br from-orange-500 to-red-600 flex items-center justify-center shadow-lg group-hover:scale-110 transition-transform">
                                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                                </svg>
                            </div>
                            <span class="text-sm font-semibold text-gray-700 dark:text-gray-300 group-hover:text-orange-600">Assessments</span>
                        </div>
                    </a>

                    <a href="{{ route('director.analytics.index') }}" class="group">
                        <div class="rounded-xl bg-white/10 dark:bg-gray-900/10 p-6 text-center hover:-translate-y-1 hover:shadow-xl transition-all duration-300">
                            <div class="w-16 h-16 mx-auto mb-3 rounded-xl bg-gradient-to-br from-indigo-500 to-violet-600 flex items-center justify-center shadow-lg group-hover:scale-110 transition-transform">
                                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z"/>
                                </svg>
                            </div>
                            <span class="text-sm font-semibold text-gray-700 dark:text-gray-300 group-hover:text-indigo-600">Analytics</span>
                        </div>
                    </a>

                    <a href="{{ route('director.settings.index') }}" class="group">
                        <div class="rounded-xl bg-white/10 dark:bg-gray-900/10 p-6 text-center hover:-translate-y-1 hover:shadow-xl transition-all duration-300">
                            <div class="w-16 h-16 mx-auto mb-3 rounded-xl bg-gradient-to-br from-gray-500 to-gray-700 flex items-center justify-center shadow-lg group-hover:scale-110 transition-transform">
                                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                </svg>
                            </div>
                            <span class="text-sm font-semibold text-gray-700 dark:text-gray-300 group-hover:text-gray-600">Settings</span>
                        </div>
                    </a>
                </div>
            </x-ui.glass-card>

        </div>
    </div>

    <!-- Chart.js Initialization Scripts with Dynamic Data -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Student Distribution Chart - Dynamic Data
            const distributionCtx = document.getElementById('studentDistributionChart').getContext('2d');
            const studentData = @json($studentDistribution);
            new Chart(distributionCtx, {
                type: 'doughnut',
                data: {
                    labels: Object.keys(studentData),
                    datasets: [{
                        data: Object.values(studentData),
                        backgroundColor: [
                            'rgba(34, 197, 94, 0.8)',
                            'rgba(249, 115, 22, 0.8)',
                            'rgba(59, 130, 246, 0.8)',
                            'rgba(168, 85, 247, 0.8)'
                        ],
                        borderWidth: 2,
                        hoverOffset: 15
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { 
                            display: true,
                            position: 'bottom',
                            labels: {
                                padding: 15,
                                usePointStyle: true
                            }
                        }
                    },
                    cutout: '60%'
                }
            });

            // Weekly Attendance Chart - Dynamic Data
            const attendanceCtx = document.getElementById('attendanceChart').getContext('2d');
            const attendanceData = @json($attendanceData);
            new Chart(attendanceCtx, {
                type: 'bar',
                data: {
                    labels: @json($attendanceLabels),
                    datasets: [{
                        label: 'Present',
                        data: attendanceData.present || [],
                        backgroundColor: 'rgba(34, 197, 94, 0.8)',
                        borderRadius: 8,
                    }, {
                        label: 'Absent',
                        data: attendanceData.absent || [],
                        backgroundColor: 'rgba(239, 68, 68, 0.8)',
                        borderRadius: 8,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: true, position: 'top' }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            max: 100,
                            ticks: {
                                callback: function(value) {
                                    return value + '%';
                                }
                            }
                        }
                    }
                }
            });
        });
    </script>
</x-app-layout>
