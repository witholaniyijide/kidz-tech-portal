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
           class="px-4 py-2 rounded-xl font-medium transition-all {{ !$isStandIn ? 'bg-gradient-to-r from-[#4B49AC] to-[#7978E9] text-white shadow-lg' : 'bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400 hover:bg-slate-200 dark:hover:bg-slate-700' }}">
            My Students
        </a>
        <a href="{{ route('tutor.attendance.create', ['standin' => 1]) }}" 
           class="px-4 py-2 rounded-xl font-medium transition-all {{ $isStandIn ? 'bg-gradient-to-r from-[#7978E9] to-[#98BDFF] text-white shadow-lg' : 'bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400 hover:bg-slate-200 dark:hover:bg-slate-700' }}">
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
            <div class="px-6 py-4 {{ $isStandIn ? 'bg-gradient-to-r from-[#7978E9] to-[#98BDFF]' : 'bg-gradient-to-r from-[#4B49AC] to-[#7978E9]' }}">
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
                                class="w-full px-4 py-3 bg-white dark:bg-slate-700 border border-slate-300 dark:border-slate-600 rounded-xl text-slate-900 dark:text-white focus:ring-2 focus:ring-[#7978E9] focus:border-transparent">
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
                                class="w-full px-4 py-3 bg-white dark:bg-slate-700 border border-slate-300 dark:border-slate-600 rounded-xl text-slate-900 dark:text-white focus:ring-2 focus:ring-[#7978E9] focus:border-transparent">
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
                            class="w-full px-4 py-3 bg-white dark:bg-slate-700 border border-slate-300 dark:border-slate-600 rounded-xl text-slate-900 dark:text-white focus:ring-2 focus:ring-[#7978E9] focus:border-transparent">
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
                               class="w-full px-4 py-3 bg-white dark:bg-slate-700 border border-slate-300 dark:border-slate-600 rounded-xl text-slate-900 dark:text-white focus:ring-2 focus:ring-[#7978E9] focus:border-transparent">
                        @error('class_date')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Class Time (12-hour format) -->
                    <div x-data="{
                        hour: '{{ old('class_time') ? (int)substr(old('class_time'), 0, 2) % 12 ?: 12 : '' }}',
                        minute: '{{ old('class_time') ? substr(old('class_time'), 3, 2) : '' }}',
                        period: '{{ old('class_time') && (int)substr(old('class_time'), 0, 2) >= 12 ? 'PM' : 'AM' }}',
                        get time24() {
                            if (!this.hour || !this.minute) return '';
                            let h = parseInt(this.hour);
                            if (this.period === 'PM' && h !== 12) h += 12;
                            if (this.period === 'AM' && h === 12) h = 0;
                            return String(h).padStart(2, '0') + ':' + this.minute;
                        }
                    }">
                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                            Actual Class Time <span class="text-red-500">*</span>
                        </label>
                        <input type="hidden" name="class_time" :value="time24" required>
                        <div class="flex items-center gap-2">
                            <select x-model="hour" required class="w-20 px-3 py-3 bg-white dark:bg-slate-700 border border-slate-300 dark:border-slate-600 rounded-xl text-slate-900 dark:text-white focus:ring-2 focus:ring-[#7978E9] focus:border-transparent">
                                <option value="">Hr</option>
                                @for($i = 1; $i <= 12; $i++)
                                    <option value="{{ $i }}">{{ $i }}</option>
                                @endfor
                            </select>
                            <span class="text-slate-500 dark:text-slate-400 font-bold text-lg">:</span>
                            <select x-model="minute" required class="w-20 px-3 py-3 bg-white dark:bg-slate-700 border border-slate-300 dark:border-slate-600 rounded-xl text-slate-900 dark:text-white focus:ring-2 focus:ring-[#7978E9] focus:border-transparent">
                                <option value="">Min</option>
                                @for($i = 0; $i < 60; $i += 5)
                                    <option value="{{ str_pad($i, 2, '0', STR_PAD_LEFT) }}">{{ str_pad($i, 2, '0', STR_PAD_LEFT) }}</option>
                                @endfor
                            </select>
                            <select x-model="period" class="w-20 px-3 py-3 bg-white dark:bg-slate-700 border border-slate-300 dark:border-slate-600 rounded-xl text-slate-900 dark:text-white focus:ring-2 focus:ring-[#7978E9] focus:border-transparent">
                                <option value="AM">AM</option>
                                <option value="PM">PM</option>
                            </select>
                        </div>
                        @error('class_time')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Rescheduled Class Toggle -->
                <div x-data="{ isRescheduled: {{ old('is_rescheduled', 'false') }} }">
                    <label class="flex items-center gap-3 cursor-pointer group">
                        <input type="checkbox" name="is_rescheduled" value="1"
                               x-model="isRescheduled"
                               {{ old('is_rescheduled') ? 'checked' : '' }}
                               class="w-5 h-5 rounded text-[#7978E9] focus:ring-[#7978E9] border-slate-300 dark:border-slate-600">
                        <span class="text-sm font-medium text-slate-700 dark:text-slate-300 group-hover:text-slate-900 dark:group-hover:text-white">
                            This class was rescheduled from its originally scheduled time
                        </span>
                    </label>
                    <p class="mt-1 ml-8 text-xs text-slate-500 dark:text-slate-400">
                        Check this if you held the class at a different time than what was scheduled
                    </p>

                    <!-- Rescheduled Details (only shown when toggled) -->
                    <div x-show="isRescheduled" x-collapse class="mt-4 p-4 bg-amber-50 dark:bg-amber-900/20 border border-amber-200 dark:border-amber-800 rounded-xl space-y-4">
                        <div class="flex items-start gap-3">
                            <svg class="w-5 h-5 text-amber-600 dark:text-amber-400 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <div>
                                <h4 class="font-semibold text-amber-800 dark:text-amber-300">Rescheduled Class Details</h4>
                                <p class="text-sm text-amber-700 dark:text-amber-400 mt-1">
                                    Provide the original scheduled time and reason for rescheduling.
                                </p>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <!-- Original Scheduled Time (12-hour format) -->
                            <div x-data="{
                                origHour: '{{ old('original_scheduled_time') ? (int)substr(old('original_scheduled_time'), 0, 2) % 12 ?: 12 : '' }}',
                                origMinute: '{{ old('original_scheduled_time') ? substr(old('original_scheduled_time'), 3, 2) : '' }}',
                                origPeriod: '{{ old('original_scheduled_time') && (int)substr(old('original_scheduled_time'), 0, 2) >= 12 ? 'PM' : 'AM' }}',
                                get origTime24() {
                                    if (!this.origHour || !this.origMinute) return '';
                                    let h = parseInt(this.origHour);
                                    if (this.origPeriod === 'PM' && h !== 12) h += 12;
                                    if (this.origPeriod === 'AM' && h === 12) h = 0;
                                    return String(h).padStart(2, '0') + ':' + this.origMinute;
                                }
                            }">
                                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                                    Original Scheduled Time <span class="text-red-500">*</span>
                                </label>
                                <input type="hidden" name="original_scheduled_time" :value="origTime24">
                                <div class="flex items-center gap-2">
                                    <select x-model="origHour" :required="isRescheduled" class="w-16 px-2 py-3 bg-white dark:bg-slate-700 border border-slate-300 dark:border-slate-600 rounded-xl text-slate-900 dark:text-white focus:ring-2 focus:ring-[#7978E9] text-sm">
                                        <option value="">Hr</option>
                                        @for($i = 1; $i <= 12; $i++)
                                            <option value="{{ $i }}">{{ $i }}</option>
                                        @endfor
                                    </select>
                                    <span class="text-slate-500 dark:text-slate-400 font-bold">:</span>
                                    <select x-model="origMinute" :required="isRescheduled" class="w-16 px-2 py-3 bg-white dark:bg-slate-700 border border-slate-300 dark:border-slate-600 rounded-xl text-slate-900 dark:text-white focus:ring-2 focus:ring-[#7978E9] text-sm">
                                        <option value="">Min</option>
                                        @for($i = 0; $i < 60; $i += 5)
                                            <option value="{{ str_pad($i, 2, '0', STR_PAD_LEFT) }}">{{ str_pad($i, 2, '0', STR_PAD_LEFT) }}</option>
                                        @endfor
                                    </select>
                                    <select x-model="origPeriod" class="w-16 px-2 py-3 bg-white dark:bg-slate-700 border border-slate-300 dark:border-slate-600 rounded-xl text-slate-900 dark:text-white focus:ring-2 focus:ring-[#7978E9] text-sm">
                                        <option value="AM">AM</option>
                                        <option value="PM">PM</option>
                                    </select>
                                </div>
                                @error('original_scheduled_time')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Reschedule Reason -->
                            <div>
                                <label for="reschedule_reason" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                                    Reason for Rescheduling <span class="text-red-500">*</span>
                                </label>
                                <select id="reschedule_reason" name="reschedule_reason"
                                        :required="isRescheduled"
                                        class="w-full px-4 py-3 bg-white dark:bg-slate-700 border border-slate-300 dark:border-slate-600 rounded-xl text-slate-900 dark:text-white focus:ring-2 focus:ring-[#7978E9] focus:border-transparent">
                                    <option value="">Select a reason</option>
                                    <option value="student_request" {{ old('reschedule_reason') === 'student_request' ? 'selected' : '' }}>Student/Parent requested</option>
                                    <option value="tutor_schedule_conflict" {{ old('reschedule_reason') === 'tutor_schedule_conflict' ? 'selected' : '' }}>Tutor schedule conflict</option>
                                    <option value="technical_issues" {{ old('reschedule_reason') === 'technical_issues' ? 'selected' : '' }}>Technical issues earlier</option>
                                    <option value="student_late" {{ old('reschedule_reason') === 'student_late' ? 'selected' : '' }}>Student was late/unavailable</option>
                                    <option value="tutor_emergency" {{ old('reschedule_reason') === 'tutor_emergency' ? 'selected' : '' }}>Tutor emergency</option>
                                    <option value="power_internet_outage" {{ old('reschedule_reason') === 'power_internet_outage' ? 'selected' : '' }}>Power/Internet outage</option>
                                    <option value="manager_approved" {{ old('reschedule_reason') === 'manager_approved' ? 'selected' : '' }}>Pre-approved by Manager</option>
                                    <option value="other" {{ old('reschedule_reason') === 'other' ? 'selected' : '' }}>Other reason</option>
                                </select>
                                @error('reschedule_reason')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Additional Reschedule Notes -->
                        <div>
                            <label for="reschedule_notes" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                                Additional Details (optional)
                            </label>
                            <input type="text" id="reschedule_notes" name="reschedule_notes"
                                   value="{{ old('reschedule_notes') }}"
                                   maxlength="255"
                                   placeholder="Any additional context for the reschedule..."
                                   class="w-full px-4 py-3 bg-white dark:bg-slate-700 border border-slate-300 dark:border-slate-600 rounded-xl text-slate-900 dark:text-white focus:ring-2 focus:ring-[#7978E9] focus:border-transparent">
                            @error('reschedule_notes')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>
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
                               class="w-full px-4 py-3 bg-white dark:bg-slate-700 border border-slate-300 dark:border-slate-600 rounded-xl text-slate-900 dark:text-white focus:ring-2 focus:ring-[#7978E9] focus:border-transparent">
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

                <!-- Course(s) Covered -->
                <div x-data="{ selectedCourses: {{ json_encode(old('courses_covered', [])) }} }">
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                        Course(s) Covered <span class="text-red-500">*</span>
                    </label>
                    <p class="text-xs text-slate-500 mb-2">Select the course(s) you covered in this class. You can select multiple courses if needed.</p>

                    <div class="space-y-2 max-h-64 overflow-y-auto p-3 bg-slate-50 dark:bg-slate-800 rounded-xl border border-slate-200 dark:border-slate-700">
                        @php
                            $courses = [
                                1 => '01 - Introduction to Computer Science',
                                2 => '02 - Coding & Fundamental Concepts',
                                3 => '03 - Scratch Programming',
                                4 => '04 - Artificial Intelligence',
                                5 => '05 - Graphic Design',
                                6 => '06 - Game Development',
                                7 => '07 - Mobile App Development',
                                8 => '08 - Website Development',
                                9 => '09 - Python Programming',
                                10 => '10 - Digital Literacy & Safety/Security',
                                11 => '11 - Machine Learning',
                                12 => '12 - Robotics',
                            ];
                        @endphp
                        @foreach($courses as $id => $courseName)
                            <label class="flex items-center gap-3 p-2 rounded-lg hover:bg-white dark:hover:bg-slate-700 cursor-pointer transition-colors">
                                <input type="checkbox"
                                       name="courses_covered[]"
                                       value="{{ $courseName }}"
                                       x-model="selectedCourses"
                                       {{ in_array($courseName, old('courses_covered', [])) ? 'checked' : '' }}
                                       class="w-4 h-4 rounded text-[#7978E9] focus:ring-[#7978E9] border-slate-300 dark:border-slate-600">
                                <span class="text-sm text-slate-700 dark:text-slate-300">{{ $courseName }}</span>
                            </label>
                        @endforeach
                    </div>

                    <div class="mt-2 flex items-center gap-2" x-show="selectedCourses.length > 0">
                        <span class="text-xs text-slate-500">Selected:</span>
                        <span class="text-xs font-medium text-[#7978E9]" x-text="selectedCourses.length + ' course(s)'"></span>
                    </div>

                    @error('courses_covered')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                    @error('courses_covered.*')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Topic -->
                <div>
                    <label for="topic" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                        Topic Covered (Details)
                    </label>
                    <input type="text" id="topic" name="topic"
                           value="{{ old('topic') }}"
                           maxlength="255"
                           placeholder="e.g., Variables and Data Types, Creating First Animation"
                           class="w-full px-4 py-3 bg-white dark:bg-slate-700 border border-slate-300 dark:border-slate-600 rounded-xl text-slate-900 dark:text-white focus:ring-2 focus:ring-[#7978E9] focus:border-transparent">
                    <p class="mt-1 text-xs text-slate-500">Specific topic or lesson within the selected course(s)</p>
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
                              class="w-full px-4 py-3 bg-white dark:bg-slate-700 border border-slate-300 dark:border-slate-600 rounded-xl text-slate-900 dark:text-white focus:ring-2 focus:ring-[#7978E9] focus:border-transparent resize-none">{{ old('notes') }}</textarea>
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
                            Attendance will be reviewed by an Admin before approval. You'll be notified once it's processed.
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
                        class="px-6 py-2.5 {{ $isStandIn ? 'bg-gradient-to-r from-[#7978E9] to-[#98BDFF]' : 'bg-gradient-to-r from-[#4B49AC] to-[#7978E9]' }} text-white rounded-xl hover:opacity-90 hover:shadow-lg hover:-translate-y-0.5 transition-all font-semibold">
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
