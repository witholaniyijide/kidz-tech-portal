<x-app-layout>
    <x-slot name="header">{{ __('Edit Daily Schedule') }}</x-slot>
    <x-slot name="title">{{ __('Admin - Edit Schedule') }}</x-slot>

    <div class="min-h-screen bg-gradient-to-br from-[#423A8E]/5 via-[#00CCCD]/5 to-blue-50 dark:from-slate-900 dark:via-slate-800 dark:to-slate-900 py-8 relative overflow-hidden">
        <div class="absolute top-0 left-0 w-72 h-72 bg-[#423A8E]/30 dark:bg-[#423A8E]/30 rounded-full mix-blend-multiply filter blur-xl opacity-70 animate-float"></div>
        <div class="absolute top-0 right-0 w-72 h-72 bg-[#00CCCD]/30 dark:bg-[#00CCCD]/40 rounded-full mix-blend-multiply filter blur-xl opacity-70 animate-float" style="animation-delay: 2s;"></div>

        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10" x-data="scheduleEditForm()">
            {{-- Header --}}
            <div class="flex items-center justify-between mb-8">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Edit Daily Schedule</h1>
                    <p class="text-gray-600 dark:text-gray-400 mt-1">{{ $schedule->schedule_date?->format('l, M j, Y') }}</p>
                </div>
                <a href="{{ route('admin.schedules.index', ['date' => $schedule->schedule_date?->toDateString()]) }}" class="inline-flex items-center px-4 py-2 bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-300 dark:hover:bg-gray-600 transition-colors">
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

            <form action="{{ route('admin.schedules.update', $schedule) }}" method="POST">
                @csrf
                @method('PUT')

                {{-- Date Info (Read-only) --}}
                <div class="bg-white/60 dark:bg-gray-800/60 backdrop-blur-md border border-white/20 rounded-2xl shadow overflow-hidden mb-6">
                    <div class="px-6 py-4 bg-gradient-to-r from-[#423A8E] to-[#00CCCD] text-white">
                        <h3 class="text-lg font-semibold">Schedule Date</h3>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Date</label>
                                <input type="text" value="{{ $schedule->schedule_date?->format('M j, Y') }}" readonly
                                       class="w-full px-4 py-2 bg-gray-100 dark:bg-gray-600 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 rounded-lg">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Day</label>
                                <input type="text" value="{{ $schedule->schedule_date?->format('l') }}" readonly
                                       class="w-full px-4 py-2 bg-gray-100 dark:bg-gray-600 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 rounded-lg">
                            </div>
                            <div class="flex items-end">
                                <div class="p-3 bg-gray-50 dark:bg-gray-700/50 rounded-lg w-full">
                                    <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Status: </span>
                                    @if($schedule->posted_at)
                                        <span class="text-emerald-600 font-medium">Posted</span>
                                    @else
                                        <span class="text-amber-600 font-medium">Draft</span>
                                    @endif
                                </div>
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
                                    <select :name="'classes[' + index + '][student_id]'"
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
                                    <select :name="'classes[' + index + '][tutor_id]'"
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
                                    <input type="time" :name="'classes[' + index + '][time]'"
                                           x-model="entry.time"
                                           required
                                           class="w-full px-3 py-2 text-sm border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg focus:ring-2 focus:ring-[#423A8E]">
                                </div>

                                {{-- End Time --}}
                                <div class="md:col-span-2">
                                    <label class="md:hidden text-xs text-gray-500 mb-1 block">End Time</label>
                                    <input type="time" :name="'classes[' + index + '][end_time]'"
                                           x-model="entry.end_time"
                                           class="w-full px-3 py-2 text-sm border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg focus:ring-2 focus:ring-[#423A8E]">
                                </div>

                                {{-- Class Link --}}
                                <div class="md:col-span-2">
                                    <label class="md:hidden text-xs text-gray-500 mb-1 block">Class Link</label>
                                    <input type="url" :name="'classes[' + index + '][class_link]'"
                                           x-model="entry.class_link"
                                           placeholder="https://..."
                                           class="w-full px-3 py-2 text-sm border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg focus:ring-2 focus:ring-[#423A8E]">
                                </div>

                                {{-- Delete Button --}}
                                <div class="md:col-span-1 flex items-center justify-end">
                                    <button type="button" @click="removeEntry(index)"
                                            class="p-2 text-red-500 hover:text-red-700 hover:bg-red-100 dark:hover:bg-red-900/30 rounded-lg transition-colors">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        </template>

                        {{-- Empty State --}}
                        <div x-show="entries.length === 0" class="text-center py-8 text-gray-500 dark:text-gray-400">
                            <svg class="w-12 h-12 mx-auto mb-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                            </svg>
                            <p>No class entries. Add a new entry below.</p>
                        </div>

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

                {{-- Footer Note --}}
                <div class="bg-white/60 dark:bg-gray-800/60 backdrop-blur-md border border-white/20 rounded-2xl shadow overflow-hidden mb-6">
                    <div class="p-6">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Footer Note (Optional)</label>
                        <textarea name="footer_note" rows="2" placeholder="Add any additional notes for this day's schedule..."
                                  class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg focus:ring-2 focus:ring-[#423A8E]">{{ old('footer_note', $schedule->footer_note) }}</textarea>
                    </div>
                </div>

                {{-- Submit --}}
                <div class="flex justify-between gap-4">
                    <button type="button" @click="if(confirm('Delete this entire schedule?')) { document.getElementById('delete-form').submit(); }"
                            class="px-4 py-2 bg-red-100 text-red-700 rounded-lg hover:bg-red-200 transition-colors">
                        Delete Schedule
                    </button>

                    <div class="flex gap-4">
                        <a href="{{ route('admin.schedules.index', ['date' => $schedule->schedule_date?->toDateString()]) }}"
                           class="px-6 py-3 bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-xl hover:bg-gray-300 dark:hover:bg-gray-600 transition-colors font-medium">
                            Cancel
                        </a>
                        <button type="submit" class="px-8 py-3 bg-gradient-to-r from-[#423A8E] to-[#00CCCD] text-white rounded-xl hover:shadow-lg transform hover:-translate-y-0.5 transition-all font-medium">
                            Save Schedule
                        </button>
                    </div>
                </div>
            </form>

            {{-- Hidden Delete Form --}}
            <form id="delete-form" action="{{ route('admin.schedules.destroy', $schedule) }}" method="POST" class="hidden">
                @csrf
                @method('DELETE')
            </form>
        </div>
    </div>

    @push('scripts')
    <script>
        function scheduleEditForm() {
            return {
                entries: @json($schedule->classes ?? []).map(c => ({
                    student_id: String(c.student_id || ''),
                    tutor_id: String(c.tutor_id || ''),
                    time: c.time || '09:00',
                    end_time: c.end_time || '',
                    class_link: c.class_link || '',
                    notes: c.notes || ''
                })),

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
                    } else if (lastEntry && lastEntry.time) {
                        newStartTime = lastEntry.time;
                        const [h, m] = lastEntry.time.split(':');
                        const newHour = (parseInt(h) + 1).toString().padStart(2, '0');
                        newEndTime = `${newHour}:${m}`;
                    }

                    this.entries.push({
                        student_id: '',
                        tutor_id: '',
                        time: newStartTime,
                        end_time: newEndTime,
                        class_link: '',
                        notes: ''
                    });
                },

                removeEntry(index) {
                    this.entries.splice(index, 1);
                },

                onStudentChange(index) {
                    const entry = this.entries[index];
                    const selects = document.querySelectorAll('select[name^="classes"][name$="[student_id]"]');
                    const select = selects[index];
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
