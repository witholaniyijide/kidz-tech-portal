<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-2xl text-white">
            {{ __('Tutor Dashboard') }}
        </h2>
    </x-slot>

    <x-slot name="title">{{ __('Tutor Dashboard') }}</x-slot>

    {{-- Main Dashboard Container --}}
    <div class="min-h-screen bg-gradient-to-br from-purple-50 via-pink-50 to-rose-50 dark:from-gray-900 dark:via-gray-800 dark:to-gray-900 py-12 relative overflow-hidden">

        {{-- Floating Orbs Background --}}
        <div class="absolute top-0 left-0 w-72 h-72 bg-purple-300 dark:bg-purple-900 rounded-full mix-blend-multiply filter blur-xl opacity-70 animate-float"></div>
        <div class="absolute top-0 right-0 w-72 h-72 bg-pink-300 dark:bg-pink-900 rounded-full mix-blend-multiply filter blur-xl opacity-70 animate-float" style="animation-delay: 2s;"></div>
        <div class="absolute -bottom-8 left-20 w-72 h-72 bg-rose-300 dark:bg-rose-900 rounded-full mix-blend-multiply filter blur-xl opacity-70 animate-float" style="animation-delay: 4s;"></div>

        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 relative z-10">

            {{-- Welcome Banner with Gradient --}}
            <x-ui.glass-card padding="p-8" class="mb-8">
                <div class="flex items-center justify-between flex-wrap gap-4">
                    <div class="flex items-center gap-4">
                        <div class="bg-gradient-to-r from-purple-500 to-pink-500 p-4 rounded-2xl shadow-lg">
                            <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-3xl font-bold text-gray-900 dark:text-white mb-2">
                                Welcome back, <span class="text-transparent bg-clip-text bg-gradient-to-r from-purple-500 to-pink-500">John Tutor</span>! 📚
                            </h3>
                            <p class="text-gray-600 dark:text-gray-300 text-lg">Ready to inspire young minds today?</p>
                        </div>
                    </div>
                    <div class="text-right">
                        <div class="text-gray-700 dark:text-gray-300 font-semibold">{{ now()->format('l, F j, Y') }}</div>
                        <div class="text-gray-500 dark:text-gray-400 text-sm">{{ now()->format('g:i A') }}</div>
                    </div>
                </div>
            </x-ui.glass-card>

            {{-- Stats Grid (4 Cards) --}}
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">

                {{-- My Students --}}
                <x-tutor.stat-card
                    title="My Students"
                    value="12"
                    subtitle="Students under your guidance"
                    icon='<svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>'
                />

                {{-- Reports This Month --}}
                <x-tutor.stat-card
                    title="Reports This Month"
                    value="8"
                    subtitle="Monthly reports submitted"
                    icon='<svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>'
                />

                {{-- Pending Reports --}}
                <x-tutor.stat-card
                    title="Pending Reports"
                    value="3"
                    subtitle="Reports to complete"
                    icon='<svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>'
                />

                {{-- My Schedule --}}
                <x-tutor.stat-card
                    title="My Schedule"
                    value="5"
                    subtitle="Classes today • 18 this week"
                    icon='<svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>'
                />
            </div>

            {{-- Quick Actions --}}
            <x-ui.glass-card padding="p-6" class="mb-8">
                <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-4">Quick Actions</h3>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    <a href="#" class="flex flex-col items-center justify-center p-6 bg-gradient-to-r from-purple-500 to-pink-500 text-white rounded-xl shadow-lg hover:-translate-y-1 hover:shadow-2xl transition-all">
                        <svg class="w-10 h-10 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <span class="font-semibold text-center">Create Report</span>
                    </a>

                    <a href="#" class="flex flex-col items-center justify-center p-6 bg-gradient-to-r from-pink-500 to-rose-500 text-white rounded-xl shadow-lg hover:-translate-y-1 hover:shadow-2xl transition-all">
                        <svg class="w-10 h-10 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        <span class="font-semibold text-center">View My Reports</span>
                    </a>

                    <a href="#" class="flex flex-col items-center justify-center p-6 bg-gradient-to-r from-rose-500 to-red-500 text-white rounded-xl shadow-lg hover:-translate-y-1 hover:shadow-2xl transition-all">
                        <svg class="w-10 h-10 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                        </svg>
                        <span class="font-semibold text-center">View My Students</span>
                    </a>

                    <a href="#" class="flex flex-col items-center justify-center p-6 bg-gradient-to-r from-purple-600 to-purple-800 text-white rounded-xl shadow-lg hover:-translate-y-1 hover:shadow-2xl transition-all">
                        <svg class="w-10 h-10 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        <span class="font-semibold text-center">View Attendance</span>
                    </a>
                </div>
            </x-ui.glass-card>

            {{-- To-Do List --}}
            <x-ui.glass-card padding="p-6" class="mb-8">
                <div class="mb-6">
                    <h3 class="text-xl font-bold text-gray-900 dark:text-white flex items-center">
                        <svg class="w-5 h-5 mr-2 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                        </svg>
                        Today's To-Do List
                    </h3>
                    <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Keep track of your daily tasks</p>
                </div>

                <div id="tutorTodoList" class="space-y-3 mb-6 max-h-64 overflow-y-auto"></div>

                <form id="tutorTodoForm" class="space-y-3 mb-4">
                    <input
                        type="text"
                        id="tutorNewTodo"
                        placeholder="Add a new task..."
                        class="w-full px-4 py-2.5 text-sm border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-purple-600 focus:border-purple-600 dark:bg-slate-800 dark:text-white"
                        aria-label="New task input"
                    >
                    <div class="flex flex-col sm:flex-row gap-2">
                        <input
                            type="date"
                            id="tutorNewDate"
                            class="flex-1 px-4 py-2.5 text-sm border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-purple-600 focus:border-purple-600 dark:bg-slate-800 dark:text-white"
                            aria-label="Task date"
                        >
                        <input
                            type="time"
                            id="tutorNewTime"
                            class="flex-1 px-4 py-2.5 text-sm border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-purple-600 focus:border-purple-600 dark:bg-slate-800 dark:text-white"
                            aria-label="Task time"
                        >
                        <button
                            type="submit"
                            class="w-full sm:w-auto px-5 py-2.5 bg-gradient-to-r from-purple-500 to-pink-500 text-white font-semibold rounded-lg shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all duration-300"
                            aria-label="Add task"
                        >
                            <svg class="w-5 h-5 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                            </svg>
                        </button>
                    </div>
                </form>

                <div class="p-3 rounded-xl bg-gradient-to-r from-purple-50 to-pink-50 dark:from-purple-900/30 dark:to-pink-900/30 border border-purple-200 dark:border-purple-700">
                    <p class="text-xs text-gray-700 dark:text-gray-300 flex items-center">
                        <svg class="w-4 h-4 mr-2 text-purple-600 dark:text-pink-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        Tasks are saved automatically in your browser
                    </p>
                </div>
            </x-ui.glass-card>

            {{-- Two-Column Grid: Recent Reports & My Students --}}
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">

                {{-- LEFT: My Recent Reports --}}
                <x-tutor.section-card title="My Recent Reports">
                    <div class="space-y-3">
                        @php
                            $dummyReports = [
                                ['student' => 'Samuel Johnson', 'month' => 'November 2025', 'status' => 'draft'],
                                ['student' => 'Mary Okoro', 'month' => 'November 2025', 'status' => 'approved'],
                                ['student' => 'David Akinwale', 'month' => 'November 2025', 'status' => 'submitted'],
                                ['student' => 'Grace Adeyemi', 'month' => 'October 2025', 'status' => 'manager_review'],
                                ['student' => 'Emmanuel Nwosu', 'month' => 'October 2025', 'status' => 'director_approved'],
                            ];
                        @endphp

                        @foreach($dummyReports as $report)
                            <x-tutor.recent-report-card
                                :studentName="$report['student']"
                                :month="$report['month']"
                                :status="$report['status']"
                                link="#"
                            />
                        @endforeach
                    </div>
                </x-tutor.section-card>

                {{-- RIGHT: My Students List --}}
                <x-tutor.section-card title="My Students">
                    <div class="space-y-3">
                        @php
                            $dummyStudents = [
                                ['name' => 'Samuel Johnson', 'last_class' => 'Yesterday'],
                                ['name' => 'Mary Okoro', 'last_class' => '2 days ago'],
                                ['name' => 'David Akinwale', 'last_class' => '3 days ago'],
                                ['name' => 'Grace Adeyemi', 'last_class' => '1 week ago'],
                                ['name' => 'Emmanuel Nwosu', 'last_class' => '1 week ago'],
                                ['name' => 'Blessing Okonkwo', 'last_class' => '2 weeks ago'],
                            ];
                        @endphp

                        @foreach($dummyStudents as $student)
                            <x-tutor.student-list-card
                                :studentName="$student['name']"
                                :lastClass="$student['last_class']"
                                createReportLink="#"
                            />
                        @endforeach
                    </div>

                    <div class="mt-4 text-center">
                        <a href="#" class="text-purple-600 dark:text-purple-400 hover:text-pink-600 dark:hover:text-pink-400 font-semibold text-sm transition-colors">
                            View All Students →
                        </a>
                    </div>
                </x-tutor.section-card>
            </div>

        </div>
    </div>

    {{-- Tutor To-Do JavaScript --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const todoList = document.getElementById('tutorTodoList');
            const todoForm = document.getElementById('tutorTodoForm');
            const newTodoInput = document.getElementById('tutorNewTodo');
            const newDateInput = document.getElementById('tutorNewDate');
            const newTimeInput = document.getElementById('tutorNewTime');

            const defaultTasks = [
                'Prepare today\'s class materials',
                'Review student progress reports',
                'Submit attendance records',
                'Follow up with struggling students'
            ];

            function formatDateTime(date, time) {
                if (!date) return '';
                const d = new Date(date);
                const dateStr = d.toLocaleDateString('en-US', { month: 'short', day: 'numeric' });
                return time ? `${dateStr} at ${time}` : dateStr;
            }

            function loadTasks() {
                const saved = localStorage.getItem('tutor_todos');
                if (saved) {
                    try { return JSON.parse(saved); } catch(e) {}
                }
                const tasks = defaultTasks.map((text, i) => ({
                    id: Date.now() + i,
                    text: text,
                    date: null,
                    time: null,
                    completed: false
                }));
                saveTasks(tasks);
                return tasks;
            }

            function saveTasks(tasks) {
                localStorage.setItem('tutor_todos', JSON.stringify(tasks));
            }

            function renderTasks() {
                const tasks = loadTasks();
                todoList.innerHTML = '';

                if (tasks.length === 0) {
                    todoList.innerHTML = `
                        <div class="text-center py-8">
                            <div class="w-16 h-16 mx-auto mb-4 rounded-xl bg-gradient-to-br from-purple-500 to-pink-500 flex items-center justify-center opacity-50">
                                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                </svg>
                            </div>
                            <p class="text-gray-500 dark:text-gray-400 text-sm">No tasks yet. Add one below!</p>
                        </div>`;
                    return;
                }

                tasks.forEach(task => {
                    const div = document.createElement('div');
                    div.className = 'flex items-start p-3 rounded-lg bg-white dark:bg-slate-800/50 border border-gray-200 dark:border-gray-700 hover:shadow-md transition-all duration-200 group';

                    const dateDisplay = task.date
                        ? `<div class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">${formatDateTime(task.date, task.time)}</div>`
                        : '';

                    div.innerHTML = `
                        <input type="checkbox" ${task.completed ? 'checked' : ''}
                            data-id="${task.id}"
                            class="task-cb mt-0.5 w-5 h-5 text-purple-600 border-gray-300 dark:border-gray-600 rounded focus:ring-purple-600 focus:ring-2 cursor-pointer">
                        <div class="flex-1 ml-3 cursor-pointer" onclick="this.previousElementSibling.click()">
                            <div class="text-gray-800 dark:text-gray-200 text-sm ${task.completed ? 'line-through opacity-50' : ''}">${task.text}</div>
                            ${dateDisplay}
                        </div>
                        <button class="task-remove opacity-0 group-hover:opacity-100 text-red-500 hover:text-red-700 transition-opacity p-1" data-id="${task.id}">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>`;

                    todoList.appendChild(div);
                });

                document.querySelectorAll('.task-cb').forEach(cb => {
                    cb.addEventListener('change', function() {
                        const id = parseInt(this.dataset.id);
                        const tasks = loadTasks();
                        const task = tasks.find(t => t.id === id);
                        if (task) { task.completed = this.checked; saveTasks(tasks); renderTasks(); }
                    });
                });

                document.querySelectorAll('.task-remove').forEach(btn => {
                    btn.addEventListener('click', function(e) {
                        e.preventDefault();
                        const id = parseInt(this.dataset.id);
                        saveTasks(loadTasks().filter(t => t.id !== id));
                        renderTasks();
                    });
                });
            }

            todoForm.addEventListener('submit', function(e) {
                e.preventDefault();
                const text = newTodoInput.value.trim();
                if (text) {
                    const tasks = loadTasks();
                    tasks.push({
                        id: Date.now(),
                        text: text,
                        date: newDateInput.value || null,
                        time: newTimeInput.value || null,
                        completed: false
                    });
                    saveTasks(tasks);
                    newTodoInput.value = '';
                    newDateInput.value = '';
                    newTimeInput.value = '';
                    renderTasks();
                }
            });

            renderTasks();
        });
    </script>

    {{-- Custom Animations --}}
    <style>
        @keyframes float {
            0%, 100% {
                transform: translateY(0px);
            }
            50% {
                transform: translateY(-20px);
            }
        }

        .animate-float {
            animation: float 6s ease-in-out infinite;
        }

        /* Tutor Gradient Utility */
        .bg-gradient-tutor {
            background: linear-gradient(to right, #8B5CF6, #EC4899);
        }

        /* Focus visible styles for accessibility */
        *:focus-visible {
            outline: 2px solid #8B5CF6;
            outline-offset: 2px;
            border-radius: 0.375rem;
        }
    </style>
</x-app-layout>
