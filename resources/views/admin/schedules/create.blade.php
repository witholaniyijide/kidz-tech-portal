<x-app-layout>
    <x-slot name="header">{{ $existingSchedule ? __('Edit Daily Schedule') : __('Create Daily Schedule') }}</x-slot>
    <x-slot name="title">{{ $existingSchedule ? __('Admin - Edit Schedule') : __('Admin - Create Schedule') }}</x-slot>

    <div class="min-h-screen bg-gradient-to-br from-[#423A8E]/5 via-[#00CCCD]/5 to-blue-50 dark:from-slate-900 dark:via-slate-800 dark:to-slate-900 py-8 relative overflow-hidden">
        <div class="absolute top-0 left-0 w-72 h-72 bg-[#423A8E]/30 dark:bg-[#423A8E]/30 rounded-full mix-blend-multiply filter blur-xl opacity-70 animate-float"></div>
        <div class="absolute top-0 right-0 w-72 h-72 bg-[#00CCCD]/30 dark:bg-[#00CCCD]/40 rounded-full mix-blend-multiply filter blur-xl opacity-70 animate-float" style="animation-delay: 2s;"></div>

        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10" x-data="scheduleForm()">
            {{-- Header --}}
            <div class="flex items-center justify-between mb-8">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900 dark:text-white">{{ $existingSchedule ? 'Edit Daily Schedule' : 'Create Daily Schedule' }}</h1>
                    <p class="text-gray-600 dark:text-gray-400 mt-1">{{ $existingSchedule ? 'Modify class entries for this day' : 'Add multiple class entries for a single day' }}</p>
                </div>
                <a href="{{ route('admin.schedules.index', ['date' => request('date', date('Y-m-d'))]) }}" class="inline-flex items-center px-4 py-2 bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-300 dark:hover:bg-gray-600 transition-colors">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    Back
                </a>
            </div>

            @if($errors->any())
                <div class="mb-6 bg-red-100 dark:bg-red-900/30 border border-red-400 text-red-800 dark:text-red-400 px-6 py-4 rounded-xl">
                    <ul class="list-disc list-inside">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @if($existingSchedule && ($inheritedFromWeekly ?? false))
                <div class="mb-6 bg-emerald-100 dark:bg-emerald-900/30 border border-emerald-400 text-emerald-800 dark:text-emerald-400 px-6 py-4 rounded-xl flex items-start">
                    <svg class="w-5 h-5 mr-3 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                    </svg>
                    <div>
                        <p class="font-medium">Editing inherited weekly schedule</p>
                        <p class="text-sm mt-1">This schedule is inherited from the weekly repeat template. You can modify classes or add rescheduled classes. Saving will create a new schedule for this specific date.</p>
                    </div>
                </div>
            @elseif($existingSchedule)
                <div class="mb-6 bg-blue-100 dark:bg-blue-900/30 border border-blue-400 text-blue-800 dark:text-blue-400 px-6 py-4 rounded-xl flex items-start">
                    <svg class="w-5 h-5 mr-3 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                    </svg>
                    <div>
                        <p class="font-medium">Editing existing schedule</p>
                        <p class="text-sm mt-1">A schedule already exists for this date. You can modify the classes below. Saving will update the existing schedule.</p>
                    </div>
                </div>
            @endif

            <form action="{{ route('admin.schedules.store') }}" method="POST">
                @csrf

                {{-- Date Selection --}}
                <div class="bg-white/60 dark:bg-gray-800/60 backdrop-blur-md border border-white/20 rounded-2xl shadow overflow-hidden mb-6">
                    <div class="px-6 py-4 bg-gradient-to-r from-[#423A8E] to-[#00CCCD] text-white">
                        <h3 class="text-lg font-semibold">Schedule Date</h3>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Select Date <span class="text-red-500">*</span></label>
                                <input type="date" name="schedule_date" x-model="scheduleDate"
                                       value="{{ old('schedule_date', request('date', date('Y-m-d'))) }}" required
                                       class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg focus:ring-2 focus:ring-[#423A8E]">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Day</label>
                                <input type="text" x-text="getDayName()" readonly
                                       class="w-full px-4 py-2 bg-gray-100 dark:bg-gray-600 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 rounded-lg">
                            </div>
                            <div class="flex items-end">
                                <label class="flex items-center p-3 bg-gray-50 dark:bg-gray-700/50 rounded-lg cursor-pointer hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors w-full">
                                    <input type="checkbox" name="repeat_weekly" value="1" x-model="repeatWeekly"
                                           class="w-4 h-4 text-[#423A8E] border-gray-300 rounded focus:ring-[#423A8E] mr-3">
                                    <div>
                                        <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Repeat Weekly</span>
                                        <p class="text-xs text-gray-500">Create this schedule every week on this day</p>
                                    </div>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Class Entries --}}
                <div class="bg-white/60 dark:bg-gray-800/60 backdrop-blur-md border border-white/20 rounded-2xl shadow overflow-hidden mb-6">
                    <div class="px-6 py-4 bg-gradient-to-r from-blue-500 to-indigo-600 text-white flex justify-between items-center">
                        <h3 class="text-lg font-semibold">Class Entries</h3>
                        <span class="text-sm bg-white/20 px-3 py-1 rounded-full" x-text="entries.length + ' class(es)'"></span>
                    </div>
                    <div class="p-6">
                        {{-- Table Header --}}
                        <div class="hidden md:grid grid-cols-12 gap-3 mb-3 text-sm font-medium text-gray-600 dark:text-gray-400">
                            <div class="col-span-3">Student</div>
                            <div class="col-span-2">Tutor</div>
                            <div class="col-span-2">Start Time</div>
                            <div class="col-span-2">End Time</div>
                            <div class="col-span-2">Class Link</div>
                            <div class="col-span-1"></div>
                        </div>

                        {{-- Entry Rows --}}
                        <template x-for="(entry, index) in entries" :key="index">
                            <div class="grid grid-cols-1 md:grid-cols-12 gap-3 mb-4 p-4 bg-gray-50 dark:bg-gray-700/50 rounded-xl">
                                {{-- Student --}}
                                <div class="md:col-span-3">
                                    <label class="md:hidden text-xs text-gray-500 mb-1 block">Student</label>
                                    <select :name="'entries[' + index + '][student_id]'"
                                            x-model="entry.student_id"
                                            @change="onStudentChange(index)"
                                            required
                                            class="w-full px-3 py-2 text-sm border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg focus:ring-2 focus:ring-[#423A8E]">
                                        <option value="">Select Student</option>
                                        @foreach($students as $student)
                                            <option value="{{ $student->id }}"
                                                    data-tutor="{{ $student->tutor_id }}"
                                                    data-class-link="{{ $student->class_link }}">
                                                {{ $student->first_name }} {{ $student->last_name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                {{-- Tutor (Auto-selected) --}}
                                <div class="md:col-span-2">
                                    <label class="md:hidden text-xs text-gray-500 mb-1 block">Tutor</label>
                                    <select :name="'entries[' + index + '][tutor_id]'"
                                            x-model="entry.tutor_id"
                                            required
                                            class="w-full px-3 py-2 text-sm border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg focus:ring-2 focus:ring-[#423A8E]">
                                        <option value="">Select Tutor</option>
                                        @foreach($tutors as $tutor)
                                            <option value="{{ $tutor->id }}">{{ $tutor->first_name }} {{ $tutor->last_name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                {{-- Start Time --}}
                                <div class="md:col-span-2">
                                    <label class="md:hidden text-xs text-gray-500 mb-1 block">Start Time</label>
                                    <input type="time" :name="'entries[' + index + '][start_time]'"
                                           x-model="entry.start_time"
                                           required
                                           class="w-full px-3 py-2 text-sm border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg focus:ring-2 focus:ring-[#423A8E]">
                                </div>

                                {{-- End Time --}}
                                <div class="md:col-span-2">
                                    <label class="md:hidden text-xs text-gray-500 mb-1 block">End Time</label>
                                    <input type="time" :name="'entries[' + index + '][end_time]'"
                                           x-model="entry.end_time"
                                           required
                                           class="w-full px-3 py-2 text-sm border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg focus:ring-2 focus:ring-[#423A8E]">
                                </div>

                                {{-- Class Link --}}
                                <div class="md:col-span-2">
                                    <label class="md:hidden text-xs text-gray-500 mb-1 block">Class Link</label>
                                    <input type="url" :name="'entries[' + index + '][class_link]'"
                                           x-model="entry.class_link"
                                           placeholder="https://..."
                                           class="w-full px-3 py-2 text-sm border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg focus:ring-2 focus:ring-[#423A8E]">
                                </div>

                                {{-- Delete Button --}}
                                <div class="md:col-span-1 flex items-center justify-end">
                                    <button type="button" @click="removeEntry(index)"
                                            x-show="entries.length > 1"
                                            class="p-2 text-red-500 hover:text-red-700 hover:bg-red-100 dark:hover:bg-red-900/30 rounded-lg transition-colors">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        </template>

                        {{-- Add Entry Button --}}
                        <button type="button" @click="addEntry()"
                                class="w-full py-3 border-2 border-dashed border-gray-300 dark:border-gray-600 rounded-xl text-gray-600 dark:text-gray-400 hover:border-[#423A8E] hover:text-[#423A8E] transition-colors flex items-center justify-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                            </svg>
                            Add Another Class Entry
                        </button>
                    </div>
                </div>

                {{-- Rescheduled Classes --}}
                <div class="bg-white/60 dark:bg-gray-800/60 backdrop-blur-md border border-white/20 rounded-2xl shadow overflow-hidden mb-6">
                    <div class="px-6 py-4 bg-gradient-to-r from-amber-500 to-orange-600 text-white flex justify-between items-center">
                        <div>
                            <h3 class="text-lg font-semibold flex items-center">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                                Rescheduled Classes
                            </h3>
                            <p class="text-xs text-white/80 mt-1">Classes that were moved from another date to this day</p>
                        </div>
                        <span class="text-sm bg-white/20 px-3 py-1 rounded-full" x-text="rescheduledEntries.length + ' rescheduled'"></span>
                    </div>
                    <div class="p-6">
                        <template x-if="rescheduledEntries.length === 0">
                            <div class="text-center py-4 text-gray-500 dark:text-gray-400 text-sm">
                                No rescheduled classes yet
                            </div>
                        </template>

                        {{-- Table Header --}}
                        <div x-show="rescheduledEntries.length > 0" class="hidden md:grid grid-cols-12 gap-3 mb-3 text-sm font-medium text-gray-600 dark:text-gray-400">
                            <div class="col-span-2">Student</div>
                            <div class="col-span-2">Tutor</div>
                            <div class="col-span-2">Start Time</div>
                            <div class="col-span-2">End Time</div>
                            <div class="col-span-2">Original Date</div>
                            <div class="col-span-1">Link</div>
                            <div class="col-span-1"></div>
                        </div>

                        {{-- Rescheduled Entry Rows --}}
                        <template x-for="(entry, index) in rescheduledEntries" :key="'r_'+index">
                            <div class="grid grid-cols-1 md:grid-cols-12 gap-3 mb-4 p-4 bg-amber-50 dark:bg-amber-900/20 rounded-xl border-l-4 border-amber-500">
                                {{-- Student --}}
                                <div class="md:col-span-2">
                                    <label class="md:hidden text-xs text-gray-500 mb-1 block">Student</label>
                                    <select :name="'rescheduled_classes[' + index + '][student_id]'"
                                            x-model="entry.student_id"
                                            @change="onRescheduledStudentChange(index)"
                                            required
                                            class="w-full px-3 py-2 text-sm border border-amber-300 dark:border-amber-600 dark:bg-gray-700 dark:text-white rounded-lg focus:ring-2 focus:ring-amber-500">
                                        <option value="">Select Student</option>
                                        @foreach($students as $student)
                                            <option value="{{ $student->id }}"
                                                    data-tutor="{{ $student->tutor_id }}"
                                                    data-class-link="{{ $student->class_link }}">
                                                {{ $student->first_name }} {{ $student->last_name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                {{-- Tutor --}}
                                <div class="md:col-span-2">
                                    <label class="md:hidden text-xs text-gray-500 mb-1 block">Tutor</label>
                                    <select :name="'rescheduled_classes[' + index + '][tutor_id]'"
                                            x-model="entry.tutor_id"
                                            required
                                            class="w-full px-3 py-2 text-sm border border-amber-300 dark:border-amber-600 dark:bg-gray-700 dark:text-white rounded-lg focus:ring-2 focus:ring-amber-500">
                                        <option value="">Select Tutor</option>
                                        @foreach($tutors as $tutor)
                                            <option value="{{ $tutor->id }}">{{ $tutor->first_name }} {{ $tutor->last_name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                {{-- Start Time --}}
                                <div class="md:col-span-2">
                                    <label class="md:hidden text-xs text-gray-500 mb-1 block">Start Time</label>
                                    <input type="time" :name="'rescheduled_classes[' + index + '][start_time]'"
                                           x-model="entry.start_time"
                                           required
                                           class="w-full px-3 py-2 text-sm border border-amber-300 dark:border-amber-600 dark:bg-gray-700 dark:text-white rounded-lg focus:ring-2 focus:ring-amber-500">
                                </div>

                                {{-- End Time --}}
                                <div class="md:col-span-2">
                                    <label class="md:hidden text-xs text-gray-500 mb-1 block">End Time</label>
                                    <input type="time" :name="'rescheduled_classes[' + index + '][end_time]'"
                                           x-model="entry.end_time"
                                           required
                                           class="w-full px-3 py-2 text-sm border border-amber-300 dark:border-amber-600 dark:bg-gray-700 dark:text-white rounded-lg focus:ring-2 focus:ring-amber-500">
                                </div>

                                {{-- Original Date --}}
                                <div class="md:col-span-2">
                                    <label class="md:hidden text-xs text-gray-500 mb-1 block">Original Date</label>
                                    <input type="date" :name="'rescheduled_classes[' + index + '][original_date]'"
                                           x-model="entry.original_date"
                                           class="w-full px-3 py-2 text-sm border border-amber-300 dark:border-amber-600 dark:bg-gray-700 dark:text-white rounded-lg focus:ring-2 focus:ring-amber-500">
                                </div>

                                {{-- Class Link --}}
                                <div class="md:col-span-1">
                                    <label class="md:hidden text-xs text-gray-500 mb-1 block">Link</label>
                                    <input type="url" :name="'rescheduled_classes[' + index + '][class_link]'"
                                           x-model="entry.class_link"
                                           placeholder="URL"
                                           class="w-full px-3 py-2 text-sm border border-amber-300 dark:border-amber-600 dark:bg-gray-700 dark:text-white rounded-lg focus:ring-2 focus:ring-amber-500">
                                </div>

                                {{-- Delete Button --}}
                                <div class="md:col-span-1 flex items-center justify-end">
                                    <button type="button" @click="removeRescheduledEntry(index)"
                                            class="p-2 text-red-500 hover:text-red-700 hover:bg-red-100 dark:hover:bg-red-900/30 rounded-lg transition-colors">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        </template>

                        {{-- Add Rescheduled Entry Button --}}
                        <button type="button" @click="addRescheduledEntry()"
                                class="w-full py-3 border-2 border-dashed border-amber-300 dark:border-amber-600 rounded-xl text-amber-600 dark:text-amber-400 hover:border-amber-500 hover:text-amber-700 transition-colors flex items-center justify-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                            </svg>
                            Add Rescheduled Class
                        </button>
                    </div>
                </div>

                {{-- Footer Note --}}
                <div class="bg-white/60 dark:bg-gray-800/60 backdrop-blur-md border border-white/20 rounded-2xl shadow overflow-hidden mb-6">
                    <div class="p-6">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Footer Note (Optional)</label>
                        <textarea name="footer_note" rows="2" placeholder="Add any additional notes for this day's schedule..."
                                  class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg focus:ring-2 focus:ring-[#423A8E]">{{ old('footer_note') }}</textarea>
                    </div>
                </div>

                {{-- Submit --}}
                <div class="flex justify-end gap-4">
                    <a href="{{ route('admin.schedules.index', ['date' => request('date', date('Y-m-d'))]) }}"
                       class="px-6 py-3 bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-xl hover:bg-gray-300 dark:hover:bg-gray-600 transition-colors font-medium">
                        Cancel
                    </a>
                    <button type="submit" class="px-8 py-3 bg-gradient-to-r from-[#423A8E] to-[#00CCCD] text-white rounded-xl hover:shadow-lg transform hover:-translate-y-0.5 transition-all font-medium">
                        Save Schedule
                    </button>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
    <script>
        function scheduleForm() {
            @php
                $existingClasses = [];
                $existingRescheduled = [];
                if ($existingSchedule) {
                    $classes = $existingSchedule->classes ?? [];
                    foreach ($classes as $class) {
                        $existingClasses[] = [
                            'student_id' => $class['student_id'] ?? '',
                            'tutor_id' => $class['tutor_id'] ?? '',
                            'start_time' => $class['start_time'] ?? ($class['time'] ?? '09:00'),
                            'end_time' => $class['end_time'] ?? '10:00',
                            'class_link' => $class['class_link'] ?? '',
                        ];
                    }
                    // Only load rescheduled classes if this is an actual schedule for this date (not inherited)
                    if (!($inheritedFromWeekly ?? false)) {
                        $rescheduled = $existingSchedule->rescheduled_classes ?? [];
                        foreach ($rescheduled as $class) {
                            $existingRescheduled[] = [
                                'student_id' => $class['student_id'] ?? '',
                                'tutor_id' => $class['tutor_id'] ?? '',
                                'start_time' => $class['start_time'] ?? ($class['time'] ?? '09:00'),
                                'end_time' => $class['end_time'] ?? '10:00',
                                'class_link' => $class['class_link'] ?? '',
                                'original_date' => $class['original_date'] ?? '',
                            ];
                        }
                    }
                }
            @endphp

            const existingClasses = @json($existingClasses);
            const existingRescheduled = @json($existingRescheduled);

            return {
                scheduleDate: '{{ old("schedule_date", request("date", date("Y-m-d"))) }}',
                repeatWeekly: {{ old('repeat_weekly', ($existingSchedule && !($inheritedFromWeekly ?? false) ? $existingSchedule->repeat_weekly : 0)) ? 'true' : 'false' }},
                entries: existingClasses.length > 0 ? existingClasses : [
                    { student_id: '', tutor_id: '', start_time: '09:00', end_time: '10:00', class_link: '' }
                ],
                rescheduledEntries: existingRescheduled,

                getDayName() {
                    if (!this.scheduleDate) return '';
                    const date = new Date(this.scheduleDate + 'T00:00:00');
                    return date.toLocaleDateString('en-US', { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' });
                },

                addEntry() {
                    const lastEntry = this.entries[this.entries.length - 1];
                    let newStartTime = '09:00';
                    let newEndTime = '10:00';

                    if (lastEntry && lastEntry.end_time) {
                        newStartTime = lastEntry.end_time;
                        // Add 1 hour to end time
                        const [h, m] = lastEntry.end_time.split(':');
                        const newHour = (parseInt(h) + 1).toString().padStart(2, '0');
                        newEndTime = `${newHour}:${m}`;
                    }

                    this.entries.push({
                        student_id: '',
                        tutor_id: '',
                        start_time: newStartTime,
                        end_time: newEndTime,
                        class_link: ''
                    });
                },

                removeEntry(index) {
                    if (this.entries.length > 1) {
                        this.entries.splice(index, 1);
                    }
                },

                onStudentChange(index) {
                    const entry = this.entries[index];
                    const select = document.querySelectorAll('select[name^="entries"][name$="[student_id]"]')[index];
                    const option = select?.options[select.selectedIndex];

                    if (option && option.value) {
                        const tutorId = option.dataset.tutor;
                        const classLink = option.dataset.classLink;

                        if (tutorId) entry.tutor_id = tutorId;
                        if (classLink) entry.class_link = classLink;
                    }
                },

                addRescheduledEntry() {
                    this.rescheduledEntries.push({
                        student_id: '',
                        tutor_id: '',
                        start_time: '09:00',
                        end_time: '10:00',
                        class_link: '',
                        original_date: ''
                    });
                },

                removeRescheduledEntry(index) {
                    this.rescheduledEntries.splice(index, 1);
                },

                onRescheduledStudentChange(index) {
                    const entry = this.rescheduledEntries[index];
                    const select = document.querySelectorAll('select[name^="rescheduled_classes"][name$="[student_id]"]')[index];
                    const option = select?.options[select.selectedIndex];

                    if (option && option.value) {
                        const tutorId = option.dataset.tutor;
                        const classLink = option.dataset.classLink;

                        if (tutorId) entry.tutor_id = tutorId;
                        if (classLink) entry.class_link = classLink;
                    }
                }
            };
        }
    </script>
    @endpush

    @push('styles')
    <style>
        @keyframes float { 0%, 100% { transform: translateY(0px); } 50% { transform: translateY(-20px); } }
        .animate-float { animation: float 6s ease-in-out infinite; }
    </style>
    @endpush
</x-app-layout>
