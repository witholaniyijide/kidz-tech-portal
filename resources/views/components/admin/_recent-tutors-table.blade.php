@props(['tutors' => [], 'loading' => false])

<x-ui.card
    :loading="$loading"
    :empty="empty($tutors)"
    emptyMessage="No tutors found"
    role="region"
    aria-label="Recent Tutors"
    x-data="{
        showModal: false,
        modalAction: '',
        selectedTutor: null,
        confirmAction(action, tutor) {
            this.modalAction = action;
            this.selectedTutor = tutor;
            this.showModal = true;
        },
        executeAction() {
            // Handle the action (would typically make an API call)
            console.log('Executing', this.modalAction, 'on tutor', this.selectedTutor);
            this.showModal = false;
        }
    }"
>
    <x-slot:emptyAction>
        <a
            href="{{ route('tutors.create') }}"
            class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-teal-500 to-cyan-500 text-white text-base font-semibold rounded-xl shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-teal-500"
            aria-label="Add new tutor"
        >
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>
            Add Tutor
        </a>
    </x-slot:emptyAction>

    @if(!empty($tutors))
    <div class="p-6 border-b border-gray-200 dark:border-slate-700">
        <h2 class="text-2xl font-bold text-gray-800 dark:text-white font-inter">👨‍🏫 Recent Tutors</h2>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full" role="table" aria-label="Recent tutors list">
            <thead class="bg-gradient-to-r from-teal-50 to-cyan-50 dark:from-teal-900/20 dark:to-cyan-900/20">
                <tr role="row">
                    <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider font-inter">Name</th>
                    <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider font-inter">Students Assigned</th>
                    <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider font-inter">Last Active</th>
                    <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider font-inter">Status</th>
                    <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider font-inter">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200 dark:divide-slate-700" role="rowgroup">
                @foreach($tutors as $tutor)
                <tr class="hover:bg-teal-50/50 dark:hover:bg-teal-900/10 transition-colors" role="row">
                    <td class="px-6 py-4 whitespace-nowrap" role="cell">
                        <div class="flex items-center">
                            @if(isset($tutor['avatar']))
                            <img
                                src="{{ $tutor['avatar'] }}"
                                alt="{{ $tutor['name'] ?? '' }}"
                                class="h-10 w-10 rounded-full object-cover"
                                loading="lazy"
                            >
                            @else
                            <div class="h-10 w-10 rounded-full bg-gradient-to-br {{ $tutor['avatarGradient'] ?? 'from-indigo-400 to-purple-400' }} flex items-center justify-center text-white font-semibold" aria-label="Avatar for {{ $tutor['name'] ?? '' }}">
                                {{ $tutor['initials'] ?? 'NA' }}
                            </div>
                            @endif
                            <div class="ml-4">
                                <div class="text-sm font-medium text-gray-900 dark:text-white font-inter">{{ $tutor['name'] ?? '' }}</div>
                                <div class="text-sm text-gray-500 dark:text-gray-400 font-inter">{{ $tutor['email'] ?? '' }}</div>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700 dark:text-gray-300 font-inter" role="cell">
                        {{ $tutor['studentsCount'] ?? 0 }} students
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700 dark:text-gray-300 font-inter" role="cell">
                        <time datetime="{{ $tutor['lastActiveDate'] ?? '' }}">{{ $tutor['lastActive'] ?? 'N/A' }}</time>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap" role="cell">
                        <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full
                            @if($tutor['status'] === 'active')
                                bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400
                            @elseif($tutor['status'] === 'on_leave')
                                bg-orange-100 text-orange-800 dark:bg-orange-900/30 dark:text-orange-400
                            @else
                                bg-gray-100 text-gray-800 dark:bg-gray-900/30 dark:text-gray-400
                            @endif">
                            {{ ucfirst(str_replace('_', ' ', $tutor['status'] ?? 'inactive')) }}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm space-x-2" role="cell">
                        <a
                            href="{{ route('tutors.show', $tutor['id'] ?? '#') }}"
                            class="text-teal-600 hover:text-teal-900 dark:text-teal-400 dark:hover:text-teal-300 focus:outline-none focus-visible:ring-2 focus-visible:ring-teal-500 focus-visible:ring-offset-2 rounded px-1 py-0.5"
                            aria-label="View details for {{ $tutor['name'] ?? 'tutor' }}"
                        >
                            View
                        </a>
                        <a
                            href="{{ route('tutors.edit', $tutor['id'] ?? '#') }}"
                            class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300 focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-500 focus-visible:ring-offset-2 rounded px-1 py-0.5"
                            aria-label="Edit {{ $tutor['name'] ?? 'tutor' }}"
                        >
                            Edit
                        </a>
                        <a
                            href="{{ route('tutors.assign', $tutor['id'] ?? '#') }}"
                            class="text-purple-600 hover:text-purple-900 dark:text-purple-400 dark:hover:text-purple-300 focus:outline-none focus-visible:ring-2 focus-visible:ring-purple-500 focus-visible:ring-offset-2 rounded px-1 py-0.5"
                            aria-label="Assign students to {{ $tutor['name'] ?? 'tutor' }}"
                        >
                            Assign
                        </a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="px-6 py-4 border-t border-gray-200 dark:border-slate-700">
        <a
            href="{{ route('tutors.index') }}"
            class="text-teal-600 dark:text-teal-400 hover:text-teal-700 dark:hover:text-teal-300 font-medium text-sm focus:outline-none focus-visible:ring-2 focus-visible:ring-teal-500 focus-visible:ring-offset-2 rounded px-2 py-1"
            aria-label="View all tutors"
        >
            View All Tutors →
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
                        <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-yellow-100 dark:bg-yellow-900/30 sm:mx-0 sm:h-10 sm:w-10">
                            <svg class="h-6 w-6 text-yellow-600 dark:text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                            </svg>
                        </div>
                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                            <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white font-inter" id="modal-title">
                                Confirm Action
                            </h3>
                            <div class="mt-2">
                                <p class="text-sm text-gray-500 dark:text-gray-400 font-inter">
                                    Are you sure you want to <span x-text="modalAction"></span> this tutor? This action can be reversed later.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 dark:bg-slate-700 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse gap-3">
                    <button
                        type="button"
                        @click="executeAction"
                        class="w-full inline-flex justify-center rounded-lg border border-transparent shadow-sm px-4 py-2 bg-yellow-600 text-base font-medium text-white hover:bg-yellow-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-500 sm:ml-3 sm:w-auto sm:text-sm transition-colors"
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
