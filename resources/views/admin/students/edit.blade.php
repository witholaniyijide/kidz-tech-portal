<x-app-layout>
    <x-slot name="header">{{ __('Edit Student') }}</x-slot>
    <x-slot name="title">{{ __('Admin - Edit Student') }}</x-slot>

    <div class="min-h-screen bg-gradient-to-br from-[#423A8E]/5 via-[#00CCCD]/5 to-blue-50 dark:from-slate-900 dark:via-slate-800 dark:to-slate-900 py-8 relative overflow-hidden">
        <div class="absolute top-0 left-0 w-72 h-72 bg-[#423A8E]/30 dark:bg-[#423A8E]/30 rounded-full mix-blend-multiply filter blur-xl opacity-70 animate-float"></div>
        <div class="absolute top-0 right-0 w-72 h-72 bg-[#00CCCD]/30 dark:bg-[#00CCCD]/40 rounded-full mix-blend-multiply filter blur-xl opacity-70 animate-float" style="animation-delay: 2s;"></div>

        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            {{-- Header --}}
            <div class="flex items-center justify-between mb-8">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Edit Student</h1>
                    <p class="text-gray-600 dark:text-gray-400 mt-1">{{ $student->first_name }} {{ $student->last_name }}</p>
                </div>
                <a href="{{ route('admin.students.show', $student) }}" class="inline-flex items-center px-4 py-2 bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-300 dark:hover:bg-gray-600 transition-colors">
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

            <form action="{{ route('admin.students.update', $student) }}" method="POST" x-data="studentForm()">
                @csrf
                @method('PUT')

                {{-- SECTION 1: Student Info --}}
                <div class="bg-white/60 dark:bg-gray-800/60 backdrop-blur-md border border-white/20 rounded-2xl shadow mb-6 overflow-hidden">
                    <div class="px-6 py-4 bg-gradient-to-r from-[#423A8E] to-[#00CCCD] text-white">
                        <h3 class="text-lg font-semibold">Section 1: Student Information</h3>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">First Name <span class="text-red-500">*</span></label>
                                <input type="text" name="first_name" value="{{ old('first_name', $student->first_name) }}" required
                                       class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg focus:ring-2 focus:ring-[#423A8E]">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Other Name</label>
                                <input type="text" name="other_name" value="{{ old('other_name', $student->other_name) }}"
                                       class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg focus:ring-2 focus:ring-[#423A8E]">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Last Name <span class="text-red-500">*</span></label>
                                <input type="text" name="last_name" value="{{ old('last_name', $student->last_name) }}" required
                                       class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg focus:ring-2 focus:ring-[#423A8E]">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Email</label>
                                <input type="email" name="email" value="{{ old('email', $student->email) }}"
                                       class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg focus:ring-2 focus:ring-[#423A8E]">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Date of Birth</label>
                                <input type="date" name="date_of_birth" value="{{ old('date_of_birth', $student->date_of_birth?->format('Y-m-d')) }}" x-model="dob" @change="calculateAge()"
                                       class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg focus:ring-2 focus:ring-[#423A8E]">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Age</label>
                                <input type="text" x-model="age" readonly
                                       class="w-full px-4 py-2 bg-gray-100 dark:bg-gray-600 border border-gray-300 dark:border-gray-600 dark:text-white rounded-lg">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Gender</label>
                                <select name="gender" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg focus:ring-2 focus:ring-[#423A8E]">
                                    <option value="">Select Gender</option>
                                    <option value="male" {{ old('gender', $student->gender) === 'male' ? 'selected' : '' }}>Male</option>
                                    <option value="female" {{ old('gender', $student->gender) === 'female' ? 'selected' : '' }}>Female</option>
                                    <option value="other" {{ old('gender', $student->gender) === 'other' ? 'selected' : '' }}>Other</option>
                                </select>
                            </div>
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Coding Experience</label>
                                <textarea name="coding_experience" rows="2" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg focus:ring-2 focus:ring-[#423A8E]">{{ old('coding_experience', $student->coding_experience) }}</textarea>
                            </div>
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Career Interest</label>
                                <textarea name="career_interest" rows="2" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg focus:ring-2 focus:ring-[#423A8E]">{{ old('career_interest', $student->career_interest) }}</textarea>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Status <span class="text-red-500">*</span></label>
                                <select name="status" required class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg focus:ring-2 focus:ring-[#423A8E]">
                                    <option value="active" {{ old('status', $student->status) === 'active' ? 'selected' : '' }}>Active</option>
                                    <option value="inactive" {{ old('status', $student->status) === 'inactive' ? 'selected' : '' }}>Inactive</option>
                                    <option value="graduated" {{ old('status', $student->status) === 'graduated' ? 'selected' : '' }}>Graduated</option>
                                    <option value="withdrawn" {{ old('status', $student->status) === 'withdrawn' ? 'selected' : '' }}>Withdrawn</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- SECTION 2: Class Information --}}
                <div class="bg-white/60 dark:bg-gray-800/60 backdrop-blur-md border border-white/20 rounded-2xl shadow mb-6 overflow-hidden">
                    <div class="px-6 py-4 bg-gradient-to-r from-blue-500 to-indigo-600 text-white">
                        <h3 class="text-lg font-semibold">Section 2: Class Information</h3>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Class Link</label>
                                <input type="url" name="class_link" value="{{ old('class_link', $student->class_link) }}"
                                       class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg focus:ring-2 focus:ring-blue-500">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Google Classroom Link</label>
                                <input type="url" name="google_classroom_link" value="{{ old('google_classroom_link', $student->google_classroom_link) }}"
                                       class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg focus:ring-2 focus:ring-blue-500">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Tutor In Charge</label>
                                <select name="tutor_id" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg focus:ring-2 focus:ring-blue-500">
                                    <option value="">Select Tutor</option>
                                    @foreach($tutors as $tutor)
                                        <option value="{{ $tutor->id }}" {{ old('tutor_id', $student->tutor_id) == $tutor->id ? 'selected' : '' }}>
                                            {{ $tutor->first_name }} {{ $tutor->last_name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Classes Per Week</label>
                                <select name="classes_per_week" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg focus:ring-2 focus:ring-blue-500">
                                    @for($i = 1; $i <= 7; $i++)
                                        <option value="{{ $i }}" {{ old('classes_per_week', $student->classes_per_week) == $i ? 'selected' : '' }}>{{ $i }}</option>
                                    @endfor
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Enrollment Date</label>
                                <input type="date" name="enrollment_date" value="{{ old('enrollment_date', $student->enrollment_date?->format('Y-m-d')) }}"
                                       class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg focus:ring-2 focus:ring-blue-500">
                            </div>
                        </div>

                        {{-- Dynamic Class Schedule --}}
                        <div class="mt-6 pt-6 border-t border-gray-200 dark:border-gray-600">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">
                                Class Schedule
                                <span class="text-xs text-amber-600 dark:text-amber-400 ml-2">(NG Time)</span>
                            </label>
                            <div class="space-y-3">
                                <template x-for="(schedule, index) in schedules" :key="index">
                                    <div class="flex items-center gap-3 p-3 bg-gray-50 dark:bg-gray-700/50 rounded-lg">
                                        <select x-model="schedule.day" :name="'class_schedule['+index+'][day]'" class="px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg">
                                            <option value="">Day</option>
                                            <option value="monday">Monday</option>
                                            <option value="tuesday">Tuesday</option>
                                            <option value="wednesday">Wednesday</option>
                                            <option value="thursday">Thursday</option>
                                            <option value="friday">Friday</option>
                                            <option value="saturday">Saturday</option>
                                            <option value="sunday">Sunday</option>
                                        </select>
                                        {{-- 12-Hour Time Picker --}}
                                        <div class="flex items-center gap-1">
                                            <input type="hidden" :name="'class_schedule['+index+'][time]'" :value="schedule.time">
                                            <select x-model="schedule.hour" @change="updateScheduleTime(index)" class="w-16 px-2 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg text-sm">
                                                <option value="">Hr</option>
                                                <template x-for="h in 12" :key="h">
                                                    <option :value="h" x-text="h"></option>
                                                </template>
                                            </select>
                                            <span class="text-gray-500 dark:text-gray-400 font-bold">:</span>
                                            <select x-model="schedule.minute" @change="updateScheduleTime(index)" class="w-16 px-2 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg text-sm">
                                                <option value="">Min</option>
                                                @for($i = 0; $i < 60; $i += 5)
                                                    <option value="{{ str_pad($i, 2, '0', STR_PAD_LEFT) }}">{{ str_pad($i, 2, '0', STR_PAD_LEFT) }}</option>
                                                @endfor
                                            </select>
                                            <select x-model="schedule.period" @change="updateScheduleTime(index)" class="w-16 px-2 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg text-sm">
                                                <option value="AM">AM</option>
                                                <option value="PM">PM</option>
                                            </select>
                                        </div>
                                        <button type="button" @click="removeSchedule(index)" class="p-2 text-red-500 hover:bg-red-50 dark:hover:bg-red-900/20 rounded-lg">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                            </svg>
                                        </button>
                                    </div>
                                </template>
                            </div>
                            <button type="button" @click="addSchedule()" class="mt-3 text-sm text-[#423A8E] dark:text-[#00CCCD] hover:underline">
                                + Add Schedule Slot
                            </button>
                        </div>

                        {{-- Course Progression Section --}}
                        @if(isset($courses) && $courses->count() > 0)
                        <div class="mt-8 pt-6 border-t border-gray-200 dark:border-gray-600">
                            <h4 class="text-md font-semibold text-gray-800 dark:text-white mb-4 flex items-center">
                                <svg class="w-5 h-5 mr-2 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                                </svg>
                                Course Progression
                            </h4>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                                {{-- Starting Course (Immutable after set) --}}
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                        Starting Course
                                        @if($student->starting_course_id)
                                            <span class="text-xs text-amber-600 dark:text-amber-400 ml-2">(Locked)</span>
                                        @endif
                                    </label>
                                    <select name="starting_course_id"
                                            {{ $student->starting_course_id ? 'disabled' : '' }}
                                            class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg focus:ring-2 focus:ring-blue-500 {{ $student->starting_course_id ? 'bg-gray-100 dark:bg-gray-600 cursor-not-allowed' : '' }}">
                                        <option value="">Select Starting Course</option>
                                        @foreach($courses as $course)
                                            <option value="{{ $course->id }}" {{ old('starting_course_id', $student->starting_course_id) == $course->id ? 'selected' : '' }}>
                                                Level {{ $course->level }} - {{ $course->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @if($student->starting_course_id)
                                        <input type="hidden" name="starting_course_id" value="{{ $student->starting_course_id }}">
                                    @endif
                                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Once set, cannot be changed</p>
                                </div>

                                {{-- Current Course --}}
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Current Course</label>
                                    <select name="current_course_id" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg focus:ring-2 focus:ring-blue-500">
                                        <option value="">No Active Course</option>
                                        @foreach($courses as $course)
                                            <option value="{{ $course->id }}" {{ old('current_course_id', $student->current_course_id) == $course->id ? 'selected' : '' }}>
                                                Level {{ $course->level }} - {{ $course->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Course student is currently working on</p>
                                </div>
                            </div>

                            {{-- Completed Courses (Multi-select) --}}
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Completed Courses</label>
                                <p class="text-xs text-gray-500 dark:text-gray-400 mb-3">Select all courses the student has completed. Non-sequential completion is allowed.</p>

                                @php
                                    $completedIds = old('completed_course_ids', $student->completedCourses->pluck('id')->toArray());
                                @endphp

                                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3">
                                    @foreach($courses as $course)
                                        <label class="flex items-center p-3 bg-gray-50 dark:bg-gray-700/50 rounded-lg cursor-pointer hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                                            <input type="checkbox"
                                                   name="completed_course_ids[]"
                                                   value="{{ $course->id }}"
                                                   {{ in_array($course->id, $completedIds) ? 'checked' : '' }}
                                                   class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                                            <span class="ml-3 text-sm text-gray-700 dark:text-gray-300">
                                                <span class="font-medium">Level {{ $course->level }}</span>
                                                <span class="block text-xs text-gray-500 dark:text-gray-400">{{ $course->name }}</span>
                                            </span>
                                            @if($course->certificate_eligible)
                                                <span class="ml-auto text-xs bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-400 px-2 py-0.5 rounded">Certificate</span>
                                            @endif
                                        </label>
                                    @endforeach
                                </div>

                                {{-- Warning for inconsistencies --}}
                                <div x-data="{ showWarning: false }" x-init="
                                    $watch('showWarning', value => {});
                                    const checkConsistency = () => {
                                        const completed = document.querySelectorAll('input[name=\'completed_course_ids[]\']:checked');
                                        const current = document.querySelector('select[name=current_course_id]').value;
                                        // Show warning logic could be added here
                                    };
                                ">
                                    <p class="text-xs text-amber-600 dark:text-amber-400 mt-2" x-show="showWarning">
                                        Warning: Some completed courses have higher levels than the current course.
                                    </p>
                                </div>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>

                {{-- SECTION 3: Parent Information --}}
                <div class="bg-white/60 dark:bg-gray-800/60 backdrop-blur-md border border-white/20 rounded-2xl shadow mb-6 overflow-hidden">
                    <div class="px-6 py-4 bg-gradient-to-r from-purple-500 to-pink-600 text-white">
                        <h3 class="text-lg font-semibold">Section 3: Parent Information</h3>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                            {{-- Father --}}
                            <div>
                                <h4 class="text-md font-semibold text-gray-800 dark:text-white mb-4 flex items-center">
                                    <span class="w-8 h-8 bg-blue-100 dark:bg-blue-900/30 rounded-full flex items-center justify-center mr-2 text-blue-600">👨</span>
                                    Father's Information
                                </h4>
                                <div class="space-y-4">
                                    <input type="text" name="father_name" value="{{ old('father_name', $student->father_name) }}" placeholder="Name"
                                           class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg">
                                    <input type="tel" name="father_phone" value="{{ old('father_phone', $student->father_phone) }}" placeholder="Phone"
                                           class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg">
                                    <input type="email" name="father_email" value="{{ old('father_email', $student->father_email) }}" placeholder="Email"
                                           class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg">
                                    <input type="text" name="father_occupation" value="{{ old('father_occupation', $student->father_occupation) }}" placeholder="Occupation"
                                           class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg">
                                    <input type="text" name="father_location" value="{{ old('father_location', $student->father_location) }}" placeholder="Location"
                                           class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg">
                                </div>
                            </div>
                            {{-- Mother --}}
                            <div>
                                <h4 class="text-md font-semibold text-gray-800 dark:text-white mb-4 flex items-center">
                                    <span class="w-8 h-8 bg-pink-100 dark:bg-pink-900/30 rounded-full flex items-center justify-center mr-2 text-pink-600">👩</span>
                                    Mother's Information
                                </h4>
                                <div class="space-y-4">
                                    <input type="text" name="mother_name" value="{{ old('mother_name', $student->mother_name) }}" placeholder="Name"
                                           class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg">
                                    <input type="tel" name="mother_phone" value="{{ old('mother_phone', $student->mother_phone) }}" placeholder="Phone"
                                           class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg">
                                    <input type="email" name="mother_email" value="{{ old('mother_email', $student->mother_email) }}" placeholder="Email"
                                           class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg">
                                    <input type="text" name="mother_occupation" value="{{ old('mother_occupation', $student->mother_occupation) }}" placeholder="Occupation"
                                           class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg">
                                    <input type="text" name="mother_location" value="{{ old('mother_location', $student->mother_location) }}" placeholder="Location"
                                           class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Submit --}}
                <div class="flex justify-end gap-4">
                    <a href="{{ route('admin.students.show', $student) }}" class="px-6 py-3 bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-xl hover:bg-gray-300 dark:hover:bg-gray-600 transition-colors font-medium">
                        Cancel
                    </a>
                    <button type="submit" class="px-8 py-3 bg-gradient-to-r from-[#423A8E] to-[#00CCCD] text-white rounded-xl hover:shadow-lg transform hover:-translate-y-0.5 transition-all font-medium">
                        Update Student
                    </button>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
    <script>
        function studentForm() {
            return {
                dob: '{{ old('date_of_birth', $student->date_of_birth?->format('Y-m-d')) }}',
                age: '',
                schedules: [],

                parseTimeTo12Hour(time24) {
                    if (!time24) return { hour: '', minute: '', period: 'AM' };
                    const parts = time24.split(':');
                    if (parts.length < 2) return { hour: '', minute: '', period: 'AM' };

                    let h = parseInt(parts[0]);
                    const m = parts[1];
                    let period = 'AM';

                    if (h === 0) { h = 12; period = 'AM'; }
                    else if (h === 12) { period = 'PM'; }
                    else if (h > 12) { h = h - 12; period = 'PM'; }

                    return { hour: String(h), minute: m, period: period };
                },

                updateScheduleTime(index) {
                    const schedule = this.schedules[index];
                    if (schedule.hour && schedule.minute) {
                        let h = parseInt(schedule.hour);
                        if (schedule.period === 'PM' && h !== 12) h += 12;
                        if (schedule.period === 'AM' && h === 12) h = 0;
                        schedule.time = String(h).padStart(2, '0') + ':' + schedule.minute;
                    } else {
                        schedule.time = '';
                    }
                },

                calculateAge() {
                    if (!this.dob) { this.age = ''; return; }
                    const today = new Date();
                    const birthDate = new Date(this.dob);
                    let age = today.getFullYear() - birthDate.getFullYear();
                    const m = today.getMonth() - birthDate.getMonth();
                    if (m < 0 || (m === 0 && today.getDate() < birthDate.getDate())) age--;
                    this.age = age + ' years';
                },

                addSchedule() { this.schedules.push({ day: '', time: '', hour: '', minute: '', period: 'AM' }); },
                removeSchedule(index) { if (this.schedules.length > 1) this.schedules.splice(index, 1); },
                init() {
                    this.calculateAge();

                    // Load existing schedules from server, filtering out empty entries
                    let rawSchedules = @json($student->class_schedule ?? []);

                    // Filter to only include schedules that have a day set
                    let validSchedules = Array.isArray(rawSchedules)
                        ? rawSchedules.filter(s => s && s.day && s.day.trim() !== '')
                        : [];

                    // If no valid schedules, start with one empty slot
                    if (!validSchedules.length) {
                        this.schedules = [{ day: '', time: '', hour: '', minute: '', period: 'AM' }];
                    } else {
                        // Parse existing times to 12-hour format
                        this.schedules = validSchedules.map(schedule => {
                            const parsed = this.parseTimeTo12Hour(schedule.time);
                            return { ...schedule, hour: parsed.hour, minute: parsed.minute, period: parsed.period };
                        });
                    }
                }
            };
        }
    </script>
    @endpush
</x-app-layout>
