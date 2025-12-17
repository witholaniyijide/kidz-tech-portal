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

        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 relative z-10">

            <!-- Welcome Section -->
            <x-ui.glass-card padding="p-8" class="mb-8">
                <div class="flex items-center justify-between flex-wrap">
                    <div>
                        <h3 class="text-3xl font-bold text-gray-900 dark:text-white mb-2">
                            Welcome back, <span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-600 to-purple-600">{{ Auth::user()->name }}</span>!
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
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
                <!-- Today's Class Schedule -->
                <x-ui.glass-card>
                    <div class="flex items-center justify-between mb-4">
                        <x-ui.section-title>Today's Classes</x-ui.section-title>
                        <span class="text-sm text-gray-500 dark:text-gray-400">{{ now()->format('l') }}</span>
                    </div>
                    
                    @if(count($todayClasses) > 0)
                        <div class="space-y-3 max-h-80 overflow-y-auto">
                            @foreach($todayClasses as $class)
                                <div class="flex items-center p-3 bg-gray-50 dark:bg-gray-700/50 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                                    <div class="w-16 text-center">
                                        <span class="text-sm font-bold text-blue-600 dark:text-blue-400">{{ $class['time'] }}</span>
                                    </div>
                                    <div class="flex-1 ml-4">
                                        <p class="font-medium text-gray-900 dark:text-white">{{ $class['student'] }}</p>
                                        <p class="text-sm text-gray-500 dark:text-gray-400">
                                            <span class="inline-flex items-center">
                                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                                </svg>
                                                {{ $class['tutor'] }}
                                            </span>
                                        </p>
                                    </div>
                                    <div class="text-right">
                                        <span class="text-xs px-2 py-1 rounded-full bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200">
                                            {{ Str::limit($class['level'], 20) }}
                                        </span>
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

                <!-- Today's To-Do List -->
                <x-ui.glass-card x-data="{
                    todos: [],
                    newTodo: '',
                    editingId: null,
                    editText: '',
                    init() {
                        const saved = localStorage.getItem('directorTodos');
                        if (saved) {
                            this.todos = JSON.parse(saved);
                        } else {
                            // Initialize with default tasks
                            this.todos = [
                                { id: 1, text: "Post today's schedule", completed: false },
                                { id: 2, text: "Review pending attendance", completed: false },
                                { id: 3, text: "Follow up inactive students", completed: false },
                                { id: 4, text: "Approve tutor submissions", completed: false }
                            ];
                            this.saveTodos();
                        }
                    },
                    saveTodos() {
                        localStorage.setItem('directorTodos', JSON.stringify(this.todos));
                    },
                    addTodo() {
                        if (this.newTodo.trim()) {
                            this.todos.push({
                                id: Date.now(),
                                text: this.newTodo.trim(),
                                completed: false
                            });
                            this.newTodo = '';
                            this.saveTodos();
                        }
                    },
                    toggleTodo(id) {
                        const todo = this.todos.find(t => t.id === id);
                        if (todo) {
                            todo.completed = !todo.completed;
                            this.saveTodos();
                        }
                    },
                    startEdit(todo) {
                        this.editingId = todo.id;
                        this.editText = todo.text;
                        this.$nextTick(() => {
                            const input = this.$refs['editInput' + todo.id];
                            if (input) input.focus();
                        });
                    },
                    saveEdit(id) {
                        if (this.editText.trim()) {
                            const todo = this.todos.find(t => t.id === id);
                            if (todo) {
                                todo.text = this.editText.trim();
                                this.saveTodos();
                            }
                        }
                        this.editingId = null;
                        this.editText = '';
                    },
                    cancelEdit() {
                        this.editingId = null;
                        this.editText = '';
                    },
                    deleteTodo(id) {
                        this.todos = this.todos.filter(t => t.id !== id);
                        this.saveTodos();
                    }
                }">
                    <!-- Header -->
                    <div class="flex items-center gap-2 mb-2">
                        <div class="w-6 h-6 bg-teal-500 rounded flex items-center justify-center">
                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                        </div>
                        <h3 class="text-lg font-bold text-gray-900 dark:text-white">Today's To-Do List</h3>
                    </div>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mb-4">Keep track of your daily tasks</p>

                    <!-- To-Do Items -->
                    <div class="space-y-3 mb-4 max-h-64 overflow-y-auto">
                        <template x-for="todo in todos" :key="todo.id">
                            <div class="flex items-center gap-3 p-3 bg-gray-50 dark:bg-gray-700/50 rounded-xl border border-gray-100 dark:border-gray-600 hover:border-teal-300 dark:hover:border-teal-500 transition-all group">
                                <!-- Checkbox -->
                                <button @click="toggleTodo(todo.id)"
                                        class="w-6 h-6 rounded-md border-2 flex-shrink-0 flex items-center justify-center transition-colors"
                                        :class="todo.completed ? 'bg-teal-500 border-teal-500' : 'border-gray-300 dark:border-gray-500 hover:border-teal-400'">
                                    <svg x-show="todo.completed" class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                    </svg>
                                </button>

                                <!-- Text/Edit -->
                                <div class="flex-1 min-w-0">
                                    <template x-if="editingId !== todo.id">
                                        <p class="text-sm font-medium truncate cursor-pointer"
                                           :class="todo.completed ? 'text-gray-400 dark:text-gray-500 line-through' : 'text-gray-700 dark:text-gray-200'"
                                           @dblclick="startEdit(todo)"
                                           x-text="todo.text"></p>
                                    </template>
                                    <template x-if="editingId === todo.id">
                                        <input type="text"
                                               x-model="editText"
                                               @keydown.enter="saveEdit(todo.id)"
                                               @keydown.escape="cancelEdit()"
                                               @blur="saveEdit(todo.id)"
                                               x-ref="editInput"
                                               class="w-full px-2 py-1 text-sm border border-teal-400 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-teal-500 focus:outline-none">
                                    </template>
                                </div>

                                <!-- Actions -->
                                <div class="flex items-center gap-1 opacity-0 group-hover:opacity-100 transition-opacity">
                                    <template x-if="editingId !== todo.id">
                                        <button @click="startEdit(todo)"
                                                class="p-1.5 text-gray-400 hover:text-teal-500 hover:bg-teal-50 dark:hover:bg-teal-900/30 rounded-lg transition-colors"
                                                title="Edit">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                            </svg>
                                        </button>
                                    </template>
                                    <button @click="deleteTodo(todo.id)"
                                            class="p-1.5 text-gray-400 hover:text-red-500 hover:bg-red-50 dark:hover:bg-red-900/30 rounded-lg transition-colors"
                                            title="Delete">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        </template>

                        <template x-if="todos.length === 0">
                            <div class="text-center py-4 text-gray-500 dark:text-gray-400">
                                <p class="text-sm">No tasks yet. Add one below!</p>
                            </div>
                        </template>
                    </div>

                    <!-- Add New Task -->
                    <div class="flex gap-2 mb-3">
                        <input type="text"
                               x-model="newTodo"
                               @keydown.enter="addTodo()"
                               placeholder="Add a new task..."
                               class="flex-1 px-4 py-2.5 text-sm border border-gray-200 dark:border-gray-600 rounded-xl bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-gray-500 focus:ring-2 focus:ring-teal-500 focus:border-transparent">
                        <button @click="addTodo()"
                                class="px-4 py-2.5 bg-teal-500 hover:bg-teal-600 text-white font-medium rounded-xl transition-colors shadow-sm">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                            </svg>
                        </button>
                    </div>

                    <!-- Info Banner -->
                    <div class="flex items-center gap-2 p-3 bg-teal-50 dark:bg-teal-900/20 rounded-xl border border-teal-100 dark:border-teal-800">
                        <svg class="w-5 h-5 text-teal-600 dark:text-teal-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <span class="text-sm text-teal-700 dark:text-teal-300">Tasks are saved automatically in your browser</span>
                    </div>
                </x-ui.glass-card>
            </div>

            <!-- Charts and Analytics Section -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">

                <!-- Notice Board -->
                <div class="lg:col-span-2">
                    <x-ui.glass-card>
                        <div class="flex items-center justify-between mb-4">
                            <x-ui.section-title>Notice Board</x-ui.section-title>
                            <a href="{{ route('director.notices.create') }}" class="text-sm text-blue-600 dark:text-blue-400 hover:underline font-medium">+ Create Notice</a>
                        </div>
                        <div class="space-y-4 max-h-80 overflow-y-auto">
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
                                <a href="{{ route('director.notices.index') }}" class="block text-center mt-4 text-blue-600 dark:text-blue-400 hover:underline text-sm">View All Notices →</a>
                            @endif
                        </div>
                    </x-ui.glass-card>
                </div>

                <!-- Student Distribution Chart -->
                <x-ui.glass-card>
                    <x-ui.section-title>Student Distribution</x-ui.section-title>
                    <p class="text-sm text-gray-600 dark:text-gray-300 mb-4">By status</p>
                    <div class="h-80">
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
                        <a href="{{ route('director.activity-logs.index') }}" class="text-sm text-blue-600 hover:text-blue-800 dark:text-blue-400">View All</a>
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
                            <div class="w-16 h-16 mx-auto mb-3 rounded-xl bg-gradient-to-br from-blue-500 to-cyan-600 flex items-center justify-center shadow-lg group-hover:scale-110 transition-transform">
                                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                                </svg>
                            </div>
                            <span class="text-sm font-semibold text-gray-700 dark:text-gray-300 group-hover:text-blue-600">Students</span>
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
