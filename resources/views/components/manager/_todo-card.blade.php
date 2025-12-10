@props([
    'defaultTasks' => [
        'Join today\'s classes',
        'Assess tutor performance',
        'Create assessment reports',
        'Follow-up with inactive students'
    ]
])

<x-ui.glass-card class="h-full">
    <div class="flex items-center justify-between mb-6">
        <x-ui.section-title>To-Do List</x-ui.section-title>
        <span class="text-xs text-gray-500 dark:text-gray-400" id="todo-count">4 tasks</span>
    </div>

    <div class="space-y-2 mb-6 max-h-96 overflow-y-auto pr-2 custom-scrollbar" id="todo-list">
        {{-- Tasks will be rendered by JavaScript --}}
    </div>

    <div class="mt-4 pt-4 border-t border-white/10 dark:border-gray-700/10">
        <div class="flex gap-2">
            <input
                type="text"
                id="new-todo-input"
                placeholder="Add a new task..."
                class="flex-1 px-4 py-2 rounded-xl bg-white/20 dark:bg-gray-900/30 border border-white/10 dark:border-gray-700/10 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-sky-500 focus:border-transparent transition-all"
                aria-label="New task input"
            />
            <button
                id="add-todo-btn"
                class="px-4 py-2 rounded-xl bg-gradient-manager text-white font-medium hover:shadow-lg hover:-translate-y-0.5 transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-sky-500 focus:ring-offset-2"
                aria-label="Add task"
            >
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
            </button>
        </div>
    </div>
</x-ui.glass-card>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const defaultTasks = @json($defaultTasks);
    const todoList = document.getElementById('todo-list');
    const todoCount = document.getElementById('todo-count');
    const newTodoInput = document.getElementById('new-todo-input');
    const addTodoBtn = document.getElementById('add-todo-btn');

    // Load tasks from localStorage or use defaults
    function loadTasks() {
        const savedTasks = localStorage.getItem('manager_todos');
        if (savedTasks) {
            return JSON.parse(savedTasks);
        } else {
            // Initialize with default tasks
            const tasks = defaultTasks.map(task => ({ text: task, completed: false, id: Date.now() + Math.random() }));
            saveTasks(tasks);
            return tasks;
        }
    }

    // Save tasks to localStorage
    function saveTasks(tasks) {
        localStorage.setItem('manager_todos', JSON.stringify(tasks));
    }

    // Render tasks
    function renderTasks() {
        const tasks = loadTasks();
        const activeTasks = tasks.filter(task => !task.completed);
        const completedTasks = tasks.filter(task => task.completed);

        todoList.innerHTML = '';

        // Render active tasks first
        activeTasks.forEach(task => {
            todoList.appendChild(createTaskElement(task));
        });

        // Render completed tasks
        completedTasks.forEach(task => {
            todoList.appendChild(createTaskElement(task));
        });

        // Update count
        const totalTasks = tasks.length;
        const completedCount = completedTasks.length;
        todoCount.textContent = `${totalTasks} ${totalTasks === 1 ? 'task' : 'tasks'} (${completedCount} done)`;
    }

    // Create task element
    function createTaskElement(task) {
        const div = document.createElement('div');
        div.className = `flex items-center gap-3 p-3 rounded-lg bg-white/20 dark:bg-gray-900/20 border border-white/10 dark:border-gray-700/10 hover:border-sky-400/30 transition-all group ${task.completed ? 'opacity-60' : ''}`;

        const checkbox = document.createElement('input');
        checkbox.type = 'checkbox';
        checkbox.checked = task.completed;
        checkbox.className = 'w-4 h-4 rounded text-sky-600 focus:ring-sky-500 focus:ring-offset-0 cursor-pointer';
        checkbox.setAttribute('aria-label', `Mark ${task.text} as ${task.completed ? 'incomplete' : 'complete'}`);
        checkbox.addEventListener('change', () => toggleTask(task.id));

        const span = document.createElement('span');
        span.className = `flex-1 text-sm text-gray-700 dark:text-gray-300 ${task.completed ? 'line-through' : ''}`;
        span.textContent = task.text;

        const deleteBtn = document.createElement('button');
        deleteBtn.className = 'opacity-0 group-hover:opacity-100 p-1 rounded hover:bg-red-500/20 text-red-500 transition-all';
        deleteBtn.innerHTML = '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>';
        deleteBtn.setAttribute('aria-label', `Delete task: ${task.text}`);
        deleteBtn.addEventListener('click', () => deleteTask(task.id));

        div.appendChild(checkbox);
        div.appendChild(span);
        div.appendChild(deleteBtn);

        return div;
    }

    // Add new task
    function addTask() {
        const text = newTodoInput.value.trim();
        if (!text) return;

        const tasks = loadTasks();
        tasks.push({ text, completed: false, id: Date.now() });
        saveTasks(tasks);
        renderTasks();
        newTodoInput.value = '';
    }

    // Toggle task completion
    function toggleTask(id) {
        const tasks = loadTasks();
        const task = tasks.find(t => t.id === id);
        if (task) {
            task.completed = !task.completed;
            saveTasks(tasks);
            renderTasks();
        }
    }

    // Delete task
    function deleteTask(id) {
        const tasks = loadTasks();
        const filteredTasks = tasks.filter(t => t.id !== id);
        saveTasks(filteredTasks);
        renderTasks();
    }

    // Event listeners
    addTodoBtn.addEventListener('click', addTask);
    newTodoInput.addEventListener('keypress', (e) => {
        if (e.key === 'Enter') addTask();
    });

    // Initial render
    renderTasks();
});
</script>

<style>
.custom-scrollbar::-webkit-scrollbar {
    width: 6px;
}

.custom-scrollbar::-webkit-scrollbar-track {
    background: rgba(255, 255, 255, 0.1);
    border-radius: 3px;
}

.custom-scrollbar::-webkit-scrollbar-thumb {
    background: linear-gradient(to bottom, #0ea5e9, #38bdf8);
    border-radius: 3px;
}

.custom-scrollbar::-webkit-scrollbar-thumb:hover {
    background: linear-gradient(to bottom, #0284c7, #0ea5e9);
}
</style>
