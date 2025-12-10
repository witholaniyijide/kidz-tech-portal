@props(['students' => [], 'loading' => false])

<x-ui.card
    :loading="$loading"
    :empty="empty($students)"
    emptyMessage="No students found"
    role="region"
    aria-label="Recent Students"
    x-data="{
        showModal: false,
        modalAction: '',
        selectedStudent: null,
        confirmAction(action, student) {
            this.modalAction = action;
            this.selectedStudent = student;
            this.showModal = true;
        },
        executeAction() {
            // Handle the action (would typically make an API call)
            console.log('Executing', this.modalAction, 'on student', this.selectedStudent);
            this.showModal = false;
        }
    }"
>
    <x-slot:emptyAction>
        <a
            href="{{ route('students.create') }}"
            class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-teal-500 to-cyan-500 text-white text-base font-semibold rounded-xl shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-teal-500"
            aria-label="Add new student"
        >
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>
            Add Student
        </a>
    </x-slot:emptyAction>

    @if(!empty($students))
    <div class="p-6 border-b border-gray-200 dark:border-slate-700">
        <h2 class="text-2xl font-bold text-gray-800 dark:text-white font-inter">👨‍🎓 Recent Students</h2>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full" role="table" aria-label="Recent students list">
            <thead class="bg-gradient-to-r from-teal-50 to-cyan-50 dark:from-teal-900/20 dark:to-cyan-900/20">
                <tr role="row">
                    <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider font-inter">Name</th>
                    <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider font-inter">Tutor</th>
                    <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider font-inter">Last Class</th>
                    <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider font-inter">Status</th>
                    <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider font-inter">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200 dark:divide-slate-700" role="rowgroup">
                @foreach($students as $student)
                <tr class="hover:bg-teal-50/50 dark:hover:bg-teal-900/10 transition-colors" role="row">
                    <td class="px-6 py-4 whitespace-nowrap" role="cell">
                        <div class="flex items-center">
                            @if(isset($student['avatar']))
                            <img
                                src="{{ $student['avatar'] }}"
                                alt="{{ $student['name'] ?? '' }}"
                                class="h-10 w-10 rounded-full object-cover"
                                loading="lazy"
                            >
                            @else
                            <div class="h-10 w-10 rounded-full {{ $student['avatarGradient'] ?? 'bg-gradient-to-br from-blue-500 to-cyan-600' }} flex items-center justify-center text-white font-semibold text-sm shadow-md" aria-label="Avatar for {{ $student['name'] ?? '' }}">
                                {{ $student['initials'] ?? 'NA' }}
                            </div>
                            @endif
                            <div class="ml-4">
                                <div class="text-sm font-medium text-gray-900 dark:text-white font-inter">{{ $student['name'] ?? '' }}</div>
                                <div class="text-sm text-gray-500 dark:text-gray-400 font-inter">{{ $student['email'] ?? '' }}</div>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700 dark:text-gray-300 font-inter" role="cell">
                        {{ $student['tutor'] ?? 'N/A' }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700 dark:text-gray-300 font-inter" role="cell">
                        <time datetime="{{ $student['lastClassDate'] ?? '' }}">{{ $student['lastClass'] ?? 'N/A' }}</time>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap" role="cell">
                        <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full
                            {{ $student['status'] === 'active' ? 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400' : 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-400' }}">
                            {{ ucfirst($student['status'] ?? 'inactive') }}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm space-x-2" role="cell">
                        <a
                            href="{{ route('students.show', $student['id'] ?? '#') }}"
                            class="text-teal-600 hover:text-teal-900 dark:text-teal-400 dark:hover:text-teal-300 focus:outline-none focus-visible:ring-2 focus-visible:ring-teal-500 focus-visible:ring-offset-2 rounded px-1 py-0.5"
                            aria-label="View details for {{ $student['name'] ?? 'student' }}"
                        >
                            View
                        </a>
                        <a
                            href="{{ route('students.edit', $student['id'] ?? '#') }}"
                            class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300 focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-500 focus-visible:ring-offset-2 rounded px-1 py-0.5"
                            aria-label="Edit {{ $student['name'] ?? 'student' }}"
                        >
                            Edit
                        </a>
                        <button
                            type="button"
                            @click="confirmAction('{{ $student['status'] === 'active' ? 'deactivate' : 'activate' }}', {{ json_encode($student) }})"
                            class="{{ $student['status'] === 'active' ? 'text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300' : 'text-green-600 hover:text-green-900 dark:text-green-400 dark:hover:text-green-300' }} focus:outline-none focus-visible:ring-2 focus-visible:ring-{{ $student['status'] === 'active' ? 'red' : 'green' }}-500 focus-visible:ring-offset-2 rounded px-1 py-0.5"
                            aria-label="{{ $student['status'] === 'active' ? 'Deactivate' : 'Activate' }} {{ $student['name'] ?? 'student' }}"
                        >
                            {{ $student['status'] === 'active' ? 'Deactivate' : 'Activate' }}
                        </button>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="px-6 py-4 border-t border-gray-200 dark:border-slate-700">
        <a
            href="{{ route('students.index') }}"
            class="text-teal-600 dark:text-teal-400 hover:text-teal-700 dark:hover:text-teal-300 font-medium text-sm focus:outline-none focus-visible:ring-2 focus-visible:ring-teal-500 focus-visible:ring-offset-2 rounded px-2 py-1"
            aria-label="View all students"
        >
            View All Students →
        </a>
    </div>

    {{-- Confirmation Modal --}}
    <div
        x-show="showModal"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="fixed inset-0 z-50 overflow-y-auto"
        aria-labelledby="modal-title"
        role="dialog"
        aria-modal="true"
        style="display: none;"
        @keydown.escape.window="showModal = false"
    >
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 transition-opacity bg-gray-500 bg-opacity-75" aria-hidden="true" @click="showModal = false"></div>

            <div class="inline-block align-bottom bg-white dark:bg-slate-800 rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <div class="bg-white dark:bg-slate-800 px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 dark:bg-red-900/30 sm:mx-0 sm:h-10 sm:w-10">
                            <svg class="h-6 w-6 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                            </svg>
                        </div>
                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                            <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white font-inter" id="modal-title">
                                Confirm Action
                            </h3>
                            <div class="mt-2">
                                <p class="text-sm text-gray-500 dark:text-gray-400 font-inter">
                                    Are you sure you want to <span x-text="modalAction"></span> this student? This action can be reversed later.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 dark:bg-slate-700 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse gap-3">
                    <button
                        type="button"
                        @click="executeAction"
                        class="w-full inline-flex justify-center rounded-lg border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm transition-colors"
                    >
                        Confirm
                    </button>
                    <button
                        type="button"
                        @click="showModal = false"
                        class="mt-3 w-full inline-flex justify-center rounded-lg border border-gray-300 dark:border-gray-600 shadow-sm px-4 py-2 bg-white dark:bg-slate-800 text-base font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-slate-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-teal-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm transition-colors"
                    >
                        Cancel
                    </button>
                </div>
            </div>
        </div>
    </div>
    @endif
</x-ui.card>
