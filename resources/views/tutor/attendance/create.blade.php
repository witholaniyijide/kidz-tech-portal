<x-tutor-layout title="Submit Attendance">
<div class="space-y-6">
    <!-- Page Header -->
    <div class="flex flex-wrap items-center justify-between gap-4">
        <div>
            <h1 class="text-3xl font-bold text-slate-900 dark:text-white">
                @if($isStandIn)
                    Submit Stand-in Attendance
                @else
                    Submit Attendance
                @endif
            </h1>
            <p class="text-slate-600 dark:text-slate-400 mt-1">
                @if($isStandIn)
                    Record attendance for a student you're covering for
                @else
                    Record class attendance for your assigned students
                @endif
            </p>
        </div>
        <a href="{{ route('tutor.attendance.index') }}" class="inline-flex items-center gap-2 px-4 py-2 text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white transition-colors">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            Back to History
        </a>
    </div>

    <!-- Mode Toggle -->
    <div class="flex gap-2">
        <a href="{{ route('tutor.attendance.create') }}" 
           class="px-4 py-2 rounded-xl font-medium transition-all {{ !$isStandIn ? 'bg-gradient-to-r from-[#1D2A6D] to-[#4B51FF] text-white shadow-lg' : 'bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400 hover:bg-slate-200 dark:hover:bg-slate-700' }}">
            My Students
        </a>
        <a href="{{ route('tutor.attendance.create', ['standin' => 1]) }}" 
           class="px-4 py-2 rounded-xl font-medium transition-all {{ $isStandIn ? 'bg-gradient-to-r from-[#4B51FF] to-[#22D3EE] text-white shadow-lg' : 'bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400 hover:bg-slate-200 dark:hover:bg-slate-700' }}">
            Stand-in
        </a>
    </div>

    <!-- Stand-in Info Banner -->
    @if($isStandIn)
        <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-xl p-4">
            <div class="flex items-start gap-3">
                <svg class="w-5 h-5 text-blue-600 dark:text-blue-400 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <div>
                    <h4 class="font-semibold text-blue-800 dark:text-blue-300">Stand-in Attendance</h4>
                    <p class="text-sm text-blue-700 dark:text-blue-400 mt-1">
                        Use this form when covering for another tutor. You must provide a reason for the stand-in.
                        The student's assigned tutor will be notified.
                    </p>
                </div>
            </div>
        </div>
    @endif

    <!-- Attendance Form -->
    <div class="max-w-3xl">
        <form action="{{ route('tutor.attendance.store') }}" method="POST" class="glass-card rounded-2xl shadow-lg overflow-hidden">
            @csrf
            <input type="hidden" name="is_stand_in" value="{{ $isStandIn ? '1' : '0' }}">

            <!-- Header -->
            <div class="px-6 py-4 {{ $isStandIn ? 'bg-gradient-to-r from-[#4B51FF] to-[#22D3EE]' : 'bg-gradient-to-r from-[#1D2A6D] to-[#4B51FF]' }}">
                <h2 class="text-lg font-semibold text-white flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                    </svg>
                    {{ $isStandIn ? 'Stand-in Details' : 'Attendance Details' }}
                </h2>
            </div>

            <div class="p-6 space-y-6">
                <!-- Student Selection -->
                <div>
                    <label for="student_id" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                        Student <span class="text-red-500">*</span>
                    </label>
                    @if($isStandIn)
                        @if($standInStudents->isEmpty())
                            <div class="p-4 bg-amber-50 dark:bg-amber-900/20 border border-amber-200 dark:border-amber-800 rounded-xl">
                                <p class="text-amber-700 dark:text-amber-400 text-sm">No other students available for stand-in.</p>
                            </div>
                        @else
                            <select id="student_id" name="student_id" required
                                class="w-full px-4 py-3 bg-white dark:bg-slate-700 border border-slate-300 dark:border-slate-600 rounded-xl text-slate-900 dark:text-white focus:ring-2 focus:ring-[#4B51FF] focus:border-transparent">
                                <option value="">Select a student to cover</option>
                                @foreach($standInStudents as $student)
                                    <option value="{{ $student->id }}" {{ old('student_id') == $student->id ? 'selected' : '' }}>
                                        {{ $student->first_name }} {{ $student->last_name }}
                                        @if($student->tutor)
                                            (Assigned to: {{ $student->tutor->first_name }} {{ $student->tutor->last_name }})
                                        @else
                                            (No assigned tutor)
                                        @endif
                                    </option>
                                @endforeach
                            </select>
                        @endif
                    @else
                        @if($myStudents->isEmpty())
                            <div class="p-4 bg-amber-50 dark:bg-amber-900/20 border border-amber-200 dark:border-amber-800 rounded-xl">
                                <p class="text-amber-700 dark:text-amber-400 text-sm">You have no students assigned yet.</p>
                            </div>
                        @else
                            <select id="student_id" name="student_id" required
                                class="w-full px-4 py-3 bg-white dark:bg-slate-700 border border-slate-300 dark:border-slate-600 rounded-xl text-slate-900 dark:text-white focus:ring-2 focus:ring-[#4B51FF] focus:border-transparent">
                                <option value="">Select your student</option>
                                @foreach($myStudents as $student)
                                    <option value="{{ $student->id }}" {{ old('student_id', request('student_id')) == $student->id ? 'selected' : '' }}>
                                        {{ $student->first_name }} {{ $student->last_name }}
                                    </option>
                                @endforeach
                            </select>
                        @endif
                    @endif
                    @error('student_id')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Stand-in Reason (only for stand-in mode) -->
                @if($isStandIn)
                    <div>
                        <label for="stand_in_reason" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                            Reason for Stand-in <span class="text-red-500">*</span>
                        </label>
                        <select id="stand_in_reason" name="stand_in_reason" required
                            class="w-full px-4 py-3 bg-white dark:bg-slate-700 border border-slate-300 dark:border-slate-600 rounded-xl text-slate-900 dark:text-white focus:ring-2 focus:ring-[#4B51FF] focus:border-transparent">
                            <option value="">Select a reason</option>
                            <option value="tutor_sick" {{ old('stand_in_reason') === 'tutor_sick' ? 'selected' : '' }}>Assigned tutor is sick</option>
                            <option value="tutor_leave" {{ old('stand_in_reason') === 'tutor_leave' ? 'selected' : '' }}>Assigned tutor on leave</option>
                            <option value="tutor_emergency" {{ old('stand_in_reason') === 'tutor_emergency' ? 'selected' : '' }}>Assigned tutor has emergency</option>
                            <option value="schedule_conflict" {{ old('stand_in_reason') === 'schedule_conflict' ? 'selected' : '' }}>Schedule conflict</option>
                            <option value="manager_request" {{ old('stand_in_reason') === 'manager_request' ? 'selected' : '' }}>Manager requested cover</option>
                            <option value="other" {{ old('stand_in_reason') === 'other' ? 'selected' : '' }}>Other reason</option>
                        </select>
                        @error('stand_in_reason')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>
                @endif

                <!-- Date & Time Row -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <!-- Class Date -->
                    <div>
                        <label for="class_date" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                            Class Date <span class="text-red-500">*</span>
                        </label>
                        <input type="date" id="class_date" name="class_date" 
                               value="{{ old('class_date', date('Y-m-d')) }}" 
                               required 
                               max="{{ date('Y-m-d') }}"
                               class="w-full px-4 py-3 bg-white dark:bg-slate-700 border border-slate-300 dark:border-slate-600 rounded-xl text-slate-900 dark:text-white focus:ring-2 focus:ring-[#4B51FF] focus:border-transparent">
                        @error('class_date')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Class Time -->
                    <div>
                        <label for="class_time" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                            Class Time <span class="text-red-500">*</span>
                        </label>
                        <input type="time" id="class_time" name="class_time" 
                               value="{{ old('class_time') }}" 
                               required
                               class="w-full px-4 py-3 bg-white dark:bg-slate-700 border border-slate-300 dark:border-slate-600 rounded-xl text-slate-900 dark:text-white focus:ring-2 focus:ring-[#4B51FF] focus:border-transparent">
                        @error('class_time')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Duration -->
                <div>
                    <label for="duration_minutes" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                        Duration (minutes) <span class="text-red-500">*</span>
                    </label>
                    <div class="flex items-center gap-2">
                        <input type="number" id="duration_minutes" name="duration_minutes" 
                               value="{{ old('duration_minutes', 60) }}" 
                               required 
                               min="15" 
                               max="240"
                               class="w-full px-4 py-3 bg-white dark:bg-slate-700 border border-slate-300 dark:border-slate-600 rounded-xl text-slate-900 dark:text-white focus:ring-2 focus:ring-[#4B51FF] focus:border-transparent">
                        <div class="flex gap-1">
                            <button type="button" onclick="setDuration(30)" class="px-3 py-2 bg-slate-100 dark:bg-slate-700 text-slate-600 dark:text-slate-400 rounded-lg hover:bg-slate-200 dark:hover:bg-slate-600 text-sm font-medium">30</button>
                            <button type="button" onclick="setDuration(45)" class="px-3 py-2 bg-slate-100 dark:bg-slate-700 text-slate-600 dark:text-slate-400 rounded-lg hover:bg-slate-200 dark:hover:bg-slate-600 text-sm font-medium">45</button>
                            <button type="button" onclick="setDuration(60)" class="px-3 py-2 bg-slate-100 dark:bg-slate-700 text-slate-600 dark:text-slate-400 rounded-lg hover:bg-slate-200 dark:hover:bg-slate-600 text-sm font-medium">60</button>
                            <button type="button" onclick="setDuration(90)" class="px-3 py-2 bg-slate-100 dark:bg-slate-700 text-slate-600 dark:text-slate-400 rounded-lg hover:bg-slate-200 dark:hover:bg-slate-600 text-sm font-medium">90</button>
                        </div>
                    </div>
                    @error('duration_minutes')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                    <p class="mt-1 text-xs text-slate-500">Enter duration between 15 and 240 minutes</p>
                </div>

                <!-- Topic -->
                <div>
                    <label for="topic" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                        Topic Covered
                    </label>
                    <input type="text" id="topic" name="topic" 
                           value="{{ old('topic') }}" 
                           maxlength="255"
                           placeholder="e.g., Introduction to Python, Scratch Animation"
                           class="w-full px-4 py-3 bg-white dark:bg-slate-700 border border-slate-300 dark:border-slate-600 rounded-xl text-slate-900 dark:text-white focus:ring-2 focus:ring-[#4B51FF] focus:border-transparent">
                    @error('topic')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Notes -->
                <div>
                    <label for="notes" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                        Notes
                    </label>
                    <textarea id="notes" name="notes" rows="4" maxlength="2000"
                              placeholder="Any additional notes about the class, student progress, homework assigned..."
                              class="w-full px-4 py-3 bg-white dark:bg-slate-700 border border-slate-300 dark:border-slate-600 rounded-xl text-slate-900 dark:text-white focus:ring-2 focus:ring-[#4B51FF] focus:border-transparent resize-none">{{ old('notes') }}</textarea>
                    @error('notes')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Late Submission Warning -->
                <div id="late-warning" class="hidden bg-amber-50 dark:bg-amber-900/20 border border-amber-200 dark:border-amber-800 rounded-xl p-4">
                    <div class="flex items-start gap-3">
                        <svg class="w-5 h-5 text-amber-600 dark:text-amber-400 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                        </svg>
                        <div>
                            <h4 class="font-semibold text-amber-800 dark:text-amber-300">Late Submission</h4>
                            <p class="text-sm text-amber-700 dark:text-amber-400 mt-1">
                                This attendance will be marked as late because the class date has passed.
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Info Box -->
                <div class="bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 rounded-xl p-4">
                    <div class="flex items-start gap-3">
                        <svg class="w-5 h-5 text-slate-500 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <p class="text-sm text-slate-600 dark:text-slate-400">
                            Attendance will be reviewed by your Manager before approval. You'll be notified once it's processed.
                        </p>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="px-6 py-4 bg-slate-50 dark:bg-slate-800/50 border-t border-slate-200 dark:border-slate-700 flex items-center justify-end gap-4">
                <a href="{{ route('tutor.attendance.index') }}"
                   class="px-6 py-2.5 text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white font-medium transition-colors">
                    Cancel
                </a>
                <button type="submit"
                        class="px-6 py-2.5 {{ $isStandIn ? 'bg-gradient-to-r from-[#4B51FF] to-[#22D3EE]' : 'bg-gradient-to-r from-[#1D2A6D] to-[#4B51FF]' }} text-white rounded-xl hover:opacity-90 hover:shadow-lg hover:-translate-y-0.5 transition-all font-semibold">
                    Submit {{ $isStandIn ? 'Stand-in ' : '' }}Attendance
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    function setDuration(minutes) {
        document.getElementById('duration_minutes').value = minutes;
    }

    // Check for late submission
    document.getElementById('class_date').addEventListener('change', function() {
        const selectedDate = new Date(this.value);
        const today = new Date();
        today.setHours(0, 0, 0, 0);
        
        const warningEl = document.getElementById('late-warning');
        if (selectedDate < today) {
            warningEl.classList.remove('hidden');
        } else {
            warningEl.classList.add('hidden');
        }
    });

    // Trigger on page load
    document.addEventListener('DOMContentLoaded', function() {
        const classDateInput = document.getElementById('class_date');
        if (classDateInput.value) {
            classDateInput.dispatchEvent(new Event('change'));
        }
    });
</script>
@endpush
</x-tutor-layout>
