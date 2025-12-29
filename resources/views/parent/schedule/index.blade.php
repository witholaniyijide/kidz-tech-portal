<x-parent-layout title="Class Schedule">
    <div class="space-y-6">
        <!-- Page Header -->
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Class Schedule</h1>
                <p class="text-gray-600 dark:text-gray-400">View your children's weekly class schedules</p>
            </div>

            <!-- Child Filter -->
            @if($children->count() > 1)
                <form method="GET" action="{{ route('parent.schedule.index') }}">
                    <select name="child_id" onchange="this.form.submit()"
                            class="rounded-xl border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-[#F5A623] focus:border-[#F5A623]">
                        <option value="">All Children</option>
                        @foreach($children as $child)
                            <option value="{{ $child->id }}" {{ $selectedChildId == $child->id ? 'selected' : '' }}>
                                {{ $child->first_name }} {{ $child->last_name }}
                            </option>
                        @endforeach
                    </select>
                </form>
            @endif
        </div>

        <!-- Today's Classes -->
        @if(count($todayClasses) > 0)
            <div class="glass-card rounded-2xl p-6 border-l-4 border-[#F5A623]">
                <div class="flex items-center mb-4">
                    <div class="w-10 h-10 rounded-xl bg-gradient-parent flex items-center justify-center text-white mr-3">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-lg font-bold text-gray-900 dark:text-white">Today's Classes</h2>
                        <p class="text-sm text-gray-500 dark:text-gray-400">{{ $today }}, {{ now()->format('F j, Y') }}</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @foreach($todayClasses as $class)
                        <div class="p-4 rounded-xl bg-amber-50 dark:bg-amber-900/20 border border-amber-200 dark:border-amber-800">
                            <div class="flex items-start justify-between">
                                <div>
                                    <p class="font-semibold text-gray-900 dark:text-white">{{ $class['student']->first_name }}</p>
                                    <p class="text-sm text-gray-600 dark:text-gray-400">{{ $class['course'] }}</p>
                                </div>
                                <div class="text-right">
                                    <span class="text-sm font-medium text-amber-600 dark:text-amber-400">{{ $class['time'] }}</span>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">{{ $class['duration'] ?? '1 hour' }}</p>
                                </div>
                            </div>
                            @if($class['tutor'])
                                <p class="text-sm text-gray-500 dark:text-gray-400 mt-2">
                                    Tutor: {{ $class['tutor']->first_name ?? 'TBD' }} {{ $class['tutor']->last_name ?? '' }}
                                </p>
                            @endif
                            @if($class['class_link'])
                                <a href="{{ $class['class_link'] }}" target="_blank" rel="noopener noreferrer"
                                   class="mt-3 inline-flex items-center px-3 py-1.5 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                                    </svg>
                                    Join Class
                                </a>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        <!-- Weekly Schedule -->
        <div class="glass-card rounded-2xl overflow-hidden">
            <div class="p-4 border-b border-gray-200 dark:border-gray-700">
                <h2 class="text-lg font-bold text-gray-900 dark:text-white">Weekly Schedule</h2>
            </div>

            <div class="divide-y divide-gray-200 dark:divide-gray-700">
                @php
                    $days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
                @endphp

                @foreach($days as $day)
                    <div class="p-4 {{ $day === $today ? 'bg-amber-50 dark:bg-amber-900/10' : '' }}">
                        <div class="flex items-center justify-between mb-3">
                            <div class="flex items-center">
                                <span class="font-semibold text-gray-900 dark:text-white">{{ $day }}</span>
                                @if($day === $today)
                                    <span class="ml-2 px-2 py-0.5 text-xs font-medium bg-amber-200 dark:bg-amber-800 text-amber-800 dark:text-amber-200 rounded-full">
                                        Today
                                    </span>
                                @endif
                            </div>
                        </div>

                        @if(isset($weeklySchedule[$day]) && count($weeklySchedule[$day]) > 0)
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3">
                                @foreach($weeklySchedule[$day] as $class)
                                    <div class="p-4 rounded-xl bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 hover:shadow-md transition-shadow">
                                        <div class="flex items-start justify-between mb-2">
                                            <div class="flex items-center">
                                                <div class="w-8 h-8 rounded-full bg-gray-200 dark:bg-gray-600 flex items-center justify-center text-gray-900 dark:text-white text-sm font-semibold mr-2">
                                                    {{ substr($class['student']->first_name, 0, 1) }}
                                                </div>
                                                <span class="font-medium text-gray-900 dark:text-white">{{ $class['student']->first_name }}</span>
                                            </div>
                                            <div class="text-right">
                                                <span class="text-sm font-semibold text-[#F5A623]">{{ $class['time'] }}</span>
                                                <p class="text-xs text-gray-500 dark:text-gray-400">{{ $class['duration'] ?? '1 hour' }}</p>
                                            </div>
                                        </div>
                                        <p class="text-sm text-gray-600 dark:text-gray-400">{{ $class['course'] }}</p>
                                        @if($class['tutor'])
                                            <p class="text-xs text-gray-500 dark:text-gray-500 mt-2">
                                                <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                                </svg>
                                                {{ $class['tutor']->first_name ?? 'TBD' }} {{ $class['tutor']->last_name ?? '' }}
                                            </p>
                                        @endif
                                        @if($class['class_link'])
                                            <a href="{{ $class['class_link'] }}" target="_blank" rel="noopener noreferrer"
                                               class="mt-2 inline-flex items-center text-xs text-blue-600 dark:text-blue-400 hover:underline">
                                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                                                </svg>
                                                Class Link
                                            </a>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <p class="text-sm text-gray-500 dark:text-gray-400 italic">No classes scheduled</p>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Children Summary with Action Buttons -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            @foreach($children as $child)
                <div class="glass-card rounded-2xl p-5 hover-lift">
                    <div class="flex items-center space-x-4 mb-4">
                        <div class="w-14 h-14 rounded-2xl bg-gray-200 dark:bg-gray-600 flex items-center justify-center text-gray-900 dark:text-white text-xl font-bold">
                            {{ substr($child->first_name, 0, 1) }}
                        </div>
                        <div>
                            <h3 class="font-semibold text-gray-900 dark:text-white">{{ $child->first_name }} {{ $child->last_name }}</h3>
                            <p class="text-sm text-gray-500 dark:text-gray-400">{{ $child->current_course ?? 'Coding Class' }}</p>
                        </div>
                    </div>

                    @if($child->tutor)
                        <div class="flex items-center text-sm text-gray-600 dark:text-gray-400 mb-2">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                            Tutor: {{ $child->tutor->first_name }} {{ $child->tutor->last_name }}
                        </div>
                    @endif

                    @if($child->class_schedule && is_array($child->class_schedule) && count($child->class_schedule) > 0)
                        <div class="flex items-center text-sm text-gray-600 dark:text-gray-400 mb-4">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                            {{ count($child->class_schedule) }} class{{ count($child->class_schedule) > 1 ? 'es' : '' }}/week
                        </div>
                    @else
                        <p class="text-sm text-gray-500 dark:text-gray-400 mb-4">No schedule set</p>
                    @endif

                    <!-- Action Buttons -->
                    <div class="flex flex-wrap gap-2 pt-3 border-t border-gray-200 dark:border-gray-700">
                        <!-- Request Schedule Change Button -->
                        <button onclick="openScheduleChangeModal({{ $child->id }}, '{{ $child->first_name }} {{ $child->last_name }}')"
                                class="inline-flex items-center px-3 py-1.5 text-xs font-medium text-amber-700 dark:text-amber-400 bg-amber-100 dark:bg-amber-900/30 rounded-lg hover:bg-amber-200 dark:hover:bg-amber-900/50 transition-colors">
                            <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                            Request Change
                        </button>

                        <!-- Set Reminder Button -->
                        <button onclick="openReminderModal({{ $child->id }}, '{{ $child->first_name }}', {{ $child->class_reminder_enabled ? 'true' : 'false' }}, {{ $child->class_reminder_minutes ?? 30 }})"
                                class="inline-flex items-center px-3 py-1.5 text-xs font-medium {{ $child->class_reminder_enabled ? 'text-emerald-700 dark:text-emerald-400 bg-emerald-100 dark:bg-emerald-900/30' : 'text-gray-700 dark:text-gray-400 bg-gray-100 dark:bg-gray-700' }} rounded-lg hover:opacity-80 transition-colors">
                            <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                            </svg>
                            {{ $child->class_reminder_enabled ? 'Reminder On' : 'Set Reminder' }}
                        </button>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <!-- Schedule Change Request Modal -->
    <div id="scheduleChangeModal" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" onclick="closeScheduleChangeModal()"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>
            <div class="inline-block align-bottom bg-white dark:bg-gray-800 rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <div class="bg-white dark:bg-gray-800 px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-amber-100 dark:bg-amber-900/30 sm:mx-0 sm:h-10 sm:w-10">
                            <svg class="h-6 w-6 text-amber-600 dark:text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                        </div>
                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                            <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white" id="modal-title">
                                Request Schedule Change
                            </h3>
                            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                                For: <span id="scheduleChangeStudentName" class="font-medium"></span>
                            </p>
                            <div class="mt-4">
                                <label for="scheduleChangeMessage" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Your Message to Director
                                </label>
                                <textarea id="scheduleChangeMessage" rows="4"
                                          class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-amber-500 focus:border-amber-500"
                                          placeholder="Please describe your preferred schedule or the changes you'd like to request..."></textarea>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 dark:bg-gray-900/50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse gap-2">
                    <button type="button" onclick="submitScheduleChangeRequest()"
                            class="w-full inline-flex justify-center rounded-lg border border-transparent shadow-sm px-4 py-2 bg-amber-600 text-base font-medium text-white hover:bg-amber-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-amber-500 sm:ml-3 sm:w-auto sm:text-sm">
                        Send Request
                    </button>
                    <button type="button" onclick="closeScheduleChangeModal()"
                            class="mt-3 w-full inline-flex justify-center rounded-lg border border-gray-300 dark:border-gray-600 shadow-sm px-4 py-2 bg-white dark:bg-gray-700 text-base font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-amber-500 sm:mt-0 sm:w-auto sm:text-sm">
                        Cancel
                    </button>
                </div>
            </div>
        </div>
    </div>
    <input type="hidden" id="scheduleChangeStudentId" value="">

    <!-- Class Reminder Modal -->
    <div id="reminderModal" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="reminder-modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" onclick="closeReminderModal()"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>
            <div class="inline-block align-bottom bg-white dark:bg-gray-800 rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-md sm:w-full">
                <div class="bg-white dark:bg-gray-800 px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-emerald-100 dark:bg-emerald-900/30 sm:mx-0 sm:h-10 sm:w-10">
                            <svg class="h-6 w-6 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                            </svg>
                        </div>
                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                            <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white" id="reminder-modal-title">
                                Class Reminder Settings
                            </h3>
                            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                                For: <span id="reminderStudentName" class="font-medium"></span>
                            </p>
                            <div class="mt-4 space-y-4">
                                <div class="flex items-center justify-between">
                                    <label for="reminderEnabled" class="text-sm font-medium text-gray-700 dark:text-gray-300">
                                        Enable Reminders
                                    </label>
                                    <label class="relative inline-flex items-center cursor-pointer">
                                        <input type="checkbox" id="reminderEnabled" class="sr-only peer">
                                        <div class="w-11 h-6 bg-gray-300 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-emerald-300 dark:peer-focus:ring-emerald-800 rounded-full peer dark:bg-gray-600 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-emerald-600"></div>
                                    </label>
                                </div>
                                <div>
                                    <label for="reminderMinutes" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        Remind me before class
                                    </label>
                                    <select id="reminderMinutes"
                                            class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-emerald-500 focus:border-emerald-500">
                                        <option value="15">15 minutes</option>
                                        <option value="30">30 minutes</option>
                                        <option value="45">45 minutes</option>
                                        <option value="60">1 hour</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 dark:bg-gray-900/50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse gap-2">
                    <button type="button" onclick="saveReminderSettings()"
                            class="w-full inline-flex justify-center rounded-lg border border-transparent shadow-sm px-4 py-2 bg-emerald-600 text-base font-medium text-white hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500 sm:ml-3 sm:w-auto sm:text-sm">
                        Save Settings
                    </button>
                    <button type="button" onclick="closeReminderModal()"
                            class="mt-3 w-full inline-flex justify-center rounded-lg border border-gray-300 dark:border-gray-600 shadow-sm px-4 py-2 bg-white dark:bg-gray-700 text-base font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500 sm:mt-0 sm:w-auto sm:text-sm">
                        Cancel
                    </button>
                </div>
            </div>
        </div>
    </div>
    <input type="hidden" id="reminderStudentId" value="">

    @push('scripts')
    <script>
        // Schedule Change Modal Functions
        function openScheduleChangeModal(studentId, studentName) {
            document.getElementById('scheduleChangeStudentId').value = studentId;
            document.getElementById('scheduleChangeStudentName').textContent = studentName;
            document.getElementById('scheduleChangeMessage').value = '';
            document.getElementById('scheduleChangeModal').classList.remove('hidden');
        }

        function closeScheduleChangeModal() {
            document.getElementById('scheduleChangeModal').classList.add('hidden');
        }

        function submitScheduleChangeRequest() {
            const studentId = document.getElementById('scheduleChangeStudentId').value;
            const message = document.getElementById('scheduleChangeMessage').value.trim();

            if (!message) {
                alert('Please enter a message for your request.');
                return;
            }

            fetch('{{ route("parent.schedule.request-change") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    student_id: studentId,
                    message: message
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert(data.message);
                    closeScheduleChangeModal();
                } else {
                    alert(data.error || 'An error occurred. Please try again.');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred. Please try again.');
            });
        }

        // Reminder Modal Functions
        function openReminderModal(studentId, studentName, enabled, minutes) {
            document.getElementById('reminderStudentId').value = studentId;
            document.getElementById('reminderStudentName').textContent = studentName;
            document.getElementById('reminderEnabled').checked = enabled;
            document.getElementById('reminderMinutes').value = minutes;
            document.getElementById('reminderModal').classList.remove('hidden');
        }

        function closeReminderModal() {
            document.getElementById('reminderModal').classList.add('hidden');
        }

        function saveReminderSettings() {
            const studentId = document.getElementById('reminderStudentId').value;
            const enabled = document.getElementById('reminderEnabled').checked;
            const minutes = document.getElementById('reminderMinutes').value;

            fetch('{{ route("parent.schedule.toggle-reminder") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    student_id: studentId,
                    enabled: enabled,
                    minutes_before: parseInt(minutes)
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert(data.message);
                    closeReminderModal();
                    window.location.reload();
                } else {
                    alert(data.error || 'An error occurred. Please try again.');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred. Please try again.');
            });
        }
    </script>
    @endpush
</x-parent-layout>
