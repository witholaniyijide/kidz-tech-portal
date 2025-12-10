@props(['todos' => []])

<div
    class="glass-card rounded-2xl p-6 shadow-xl"
    role="region"
    aria-label="Today's To-Do List"
    style="animation-delay: 0.6s;"
    x-data="{
        todos: {{ json_encode($todos) }},
        newTodo: '',
        init() {
            // Load from localStorage if available
            const stored = localStorage.getItem('admin_todos');
            if (stored) {
                try {
                    this.todos = JSON.parse(stored);
                } catch (e) {
                    console.error('Failed to parse stored todos', e);
                }
            }
        },
        toggleTodo(index) {
            this.todos[index].completed = !this.todos[index].completed;
            this.saveTodos();
        },
        addTodo() {
            if (this.newTodo.trim()) {
                this.todos.push({
                    text: this.newTodo.trim(),
                    completed: false
                });
                this.newTodo = '';
                this.saveTodos();
            }
        },
        removeTodo(index) {
            this.todos.splice(index, 1);
            this.saveTodos();
        },
        saveTodos() {
            localStorage.setItem('admin_todos', JSON.stringify(this.todos));
        },
        handleKeydown(event, index) {
            if (event.key === 'Enter' || event.key === ' ') {
                event.preventDefault();
                this.toggleTodo(index);
            }
        }
    }"
>
    <div class="mb-6">
        <h3 class="text-xl font-bold text-gray-900 dark:text-white flex items-center">
            <svg class="w-5 h-5 mr-2 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
                    class="mt-0.5 w-5 h-5 text-teal-500 border-gray-300 dark:border-gray-600 rounded focus:ring-teal-500 focus:ring-2 cursor-pointer transition-all focus-visible:ring-2 focus-visible:ring-teal-500 focus-visible:ring-offset-2"
                    :aria-label="'Mark task as ' + (todo.completed ? 'incomplete' : 'complete')"
                >
                <label
                    :for="'todo-' + index"
                    class="flex-1 ml-3 text-gray-800 dark:text-gray-200 group-hover:text-teal-600 dark:group-hover:text-teal-400 transition-colors cursor-pointer text-sm"
                    :class="{ 'line-through opacity-50': todo.completed }"
                    x-text="todo.text"
                ></label>
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
            <div class="w-16 h-16 mx-auto mb-4 rounded-xl bg-gradient-to-br from-[#14B8A6] to-[#06B6D4] flex items-center justify-center opacity-50">
                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                </svg>
            </div>
            <p class="text-gray-500 dark:text-gray-400 text-sm">No tasks yet. Add one below!</p>
        </div>
    </div>

    {{-- Add New Todo --}}
    <form @submit.prevent="addTodo" class="flex gap-2 mb-4">
        <input
            type="text"
            x-model="newTodo"
            placeholder="Add a new task..."
            class="flex-1 px-4 py-2.5 text-sm border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500 dark:bg-slate-800 dark:text-white focus-visible:ring-2 focus-visible:ring-teal-500"
            aria-label="New task input"
        >
        <button
            type="submit"
            class="px-5 py-2.5 bg-gradient-to-r from-[#14B8A6] to-[#06B6D4] text-white font-semibold rounded-lg shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-teal-500 focus-visible:ring-2 focus-visible:ring-teal-500"
            aria-label="Add task"
        >
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>
        </button>
    </form>

    <div class="p-3 rounded-xl bg-gradient-to-r from-teal-50 to-cyan-50 dark:from-teal-900/30 dark:to-cyan-900/30 border border-teal-200 dark:border-teal-700">
        <p class="text-xs text-gray-700 dark:text-gray-300 flex items-center">
            <svg class="w-4 h-4 mr-2 text-teal-600 dark:text-teal-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            Tasks are saved automatically in your browser
        </p>
    </div>
</div>
