@props(['todos' => []])

<x-ui.card
    role="region"
    aria-label="Today's To-Do List"
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
    <div class="p-6">
        <h2 class="text-2xl font-bold text-gray-800 dark:text-white mb-6 font-inter">Today's To-Do List</h2>

        <div class="space-y-4 mb-6" role="list" aria-label="Task list">
            <template x-for="(todo, index) in todos" :key="index">
                <div class="flex items-start space-x-3 group" role="listitem">
                    <input
                        type="checkbox"
                        :id="'todo-' + index"
                        x-model="todo.completed"
                        @change="saveTodos()"
                        @keydown="handleKeydown($event, index)"
                        class="mt-1 w-5 h-5 text-teal-500 border-gray-300 rounded focus:ring-teal-500 focus:ring-2 cursor-pointer transition-all focus-visible:ring-2 focus-visible:ring-teal-500 focus-visible:ring-offset-2"
                        :aria-label="'Mark task as ' + (todo.completed ? 'incomplete' : 'complete')"
                    >
                    <label
                        :for="'todo-' + index"
                        class="flex-1 text-gray-700 dark:text-gray-300 group-hover:text-teal-600 dark:group-hover:text-teal-400 transition-colors cursor-pointer font-inter text-base"
                        :class="{ 'line-through opacity-60': todo.completed }"
                        x-text="todo.text"
                    ></label>
                    <button
                        type="button"
                        @click="removeTodo(index)"
                        class="opacity-0 group-hover:opacity-100 text-red-500 hover:text-red-700 transition-opacity focus:opacity-100 focus:outline-none focus-visible:ring-2 focus-visible:ring-red-500 focus-visible:ring-offset-2 rounded p-1"
                        :aria-label="'Remove task: ' + todo.text"
                    >
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </template>

            <div x-show="todos.length === 0" class="text-center py-8">
                <p class="text-gray-500 dark:text-gray-400 font-inter text-base">No tasks yet. Add one below!</p>
            </div>
        </div>

        {{-- Add New Todo --}}
        <form @submit.prevent="addTodo" class="flex gap-2">
            <input
                type="text"
                x-model="newTodo"
                placeholder="Add a new task..."
                class="flex-1 px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500 dark:bg-slate-700 dark:text-white font-inter text-base focus-visible:ring-2 focus-visible:ring-teal-500"
                aria-label="New task input"
            >
            <button
                type="submit"
                class="px-6 py-2 bg-gradient-to-r from-teal-500 to-cyan-500 text-white font-semibold rounded-lg shadow-md hover:shadow-lg transform hover:-translate-y-0.5 transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-teal-500 focus-visible:ring-2 focus-visible:ring-teal-500"
                aria-label="Add task"
            >
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
            </button>
        </form>

        <div class="mt-6 p-4 bg-gradient-to-r from-teal-50 to-cyan-50 dark:from-teal-900/20 dark:to-cyan-900/20 rounded-xl border border-teal-200 dark:border-teal-700">
            <p class="text-sm text-gray-600 dark:text-gray-400 flex items-center font-inter">
                <svg class="w-5 h-5 mr-2 text-teal-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                Complete tasks to keep operations smooth
            </p>
        </div>
    </div>
</x-ui.card>
