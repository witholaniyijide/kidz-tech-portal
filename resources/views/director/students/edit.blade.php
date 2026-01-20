<x-app-layout>
    <x-slot name="header">
        {{ __('Edit Student') }}
    </x-slot>

    <x-slot name="title">{{ __('Edit Student') }}</x-slot>

    <div class="min-h-screen bg-gradient-to-br from-indigo-50 via-purple-50 to-pink-50 dark:from-gray-900 dark:via-gray-800 dark:to-gray-900 py-8">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <!-- Action Button -->
            <div class="flex justify-end mb-4">
                <a href="{{ route('director.students.index') }}"
                   class="inline-flex items-center px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white font-semibold rounded-lg transition-all duration-200">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    Back to List
                </a>
            </div>
            <x-ui.glass-card>
                @php
                    $existingSchedules = is_array($student->class_schedule) ? $student->class_schedule : (json_decode($student->class_schedule ?? '[]', true) ?: []);
                @endphp
                <form method="POST" action="{{ route('director.students.update', $student) }}" x-data="studentForm()">
                    @csrf
                    @method('PUT')

                    <!-- Personal Information -->
                    <div class="mb-8">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 pb-2 border-b border-gray-200 dark:border-gray-700">
                            Personal Information
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">First Name *</label>
                                <input type="text" name="first_name" value="{{ old('first_name', $student->first_name) }}" required
                                       class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-[#4F46E5] focus:ring-[#4F46E5]">
                                @error('first_name') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Last Name *</label>
                                <input type="text" name="last_name" value="{{ old('last_name', $student->last_name) }}" required
                                       class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-[#4F46E5] focus:ring-[#4F46E5]">
                                @error('last_name') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Email</label>
                                <input type="email" name="email" value="{{ old('email', $student->email) }}"
                                       class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-[#4F46E5] focus:ring-[#4F46E5]">
                                @error('email') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Phone</label>
                                <input type="text" name="phone" value="{{ old('phone', $student->phone) }}"
                                       class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-[#4F46E5] focus:ring-[#4F46E5]">
                                @error('phone') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Date of Birth</label>
                                <input type="date" name="date_of_birth" value="{{ old('date_of_birth', $student->date_of_birth?->format('Y-m-d')) }}"
                                       class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-[#4F46E5] focus:ring-[#4F46E5]">
                                @error('date_of_birth') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Gender</label>
                                <select name="gender" class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-[#4F46E5] focus:ring-[#4F46E5]">
                                    <option value="">Select Gender</option>
                                    <option value="male" {{ old('gender', $student->gender) == 'male' ? 'selected' : '' }}>Male</option>
                                    <option value="female" {{ old('gender', $student->gender) == 'female' ? 'selected' : '' }}>Female</option>
                                    <option value="other" {{ old('gender', $student->gender) == 'other' ? 'selected' : '' }}>Other</option>
                                </select>
                                @error('gender') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                            </div>
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Address</label>
                                <textarea name="address" rows="2"
                                          class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-[#4F46E5] focus:ring-[#4F46E5]">{{ old('address', $student->address) }}</textarea>
                                @error('address') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Academic Information -->
                    <div class="mb-8">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 pb-2 border-b border-gray-200 dark:border-gray-700">
                            Academic Information
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Assigned Tutor</label>
                                <select name="tutor_id" class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-[#4F46E5] focus:ring-[#4F46E5]">
                                    <option value="">Select Tutor</option>
                                    @foreach($tutors as $tutor)
                                        <option value="{{ $tutor->id }}" {{ old('tutor_id', $student->tutor_id) == $tutor->id ? 'selected' : '' }}>
                                            {{ $tutor->first_name }} {{ $tutor->last_name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('tutor_id') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Parent/Guardian</label>
                                <select name="parent_id" class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-[#4F46E5] focus:ring-[#4F46E5]">
                                    <option value="">Select Parent</option>
                                    @foreach($parents as $parent)
                                        <option value="{{ $parent->id }}" {{ old('parent_id', $student->guardians->first()?->id) == $parent->id ? 'selected' : '' }}>
                                            {{ $parent->name }} ({{ $parent->email }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('parent_id') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Starting Course</label>
                                <select name="starting_course_id" class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-[#4F46E5] focus:ring-[#4F46E5]">
                                    <option value="">Select Starting Course</option>
                                    @if(isset($courses))
                                        @foreach($courses as $course)
                                            <option value="{{ $course->id }}" {{ old('starting_course_id', $student->starting_course_id) == $course->id ? 'selected' : '' }}>
                                                Level {{ $course->level }} - {{ $course->name }}
                                            </option>
                                        @endforeach
                                    @endif
                                </select>
                                @error('starting_course_id') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Current Course</label>
                                <select name="current_course_id" class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-[#4F46E5] focus:ring-[#4F46E5]">
                                    <option value="">Select Current Course</option>
                                    @if(isset($courses))
                                        @foreach($courses as $course)
                                            <option value="{{ $course->id }}" {{ old('current_course_id', $student->current_course_id) == $course->id ? 'selected' : '' }}>
                                                Level {{ $course->level }} - {{ $course->name }}
                                            </option>
                                        @endforeach
                                    @endif
                                </select>
                                @error('current_course_id') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Status *</label>
                                <select name="status" required class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-[#4F46E5] focus:ring-[#4F46E5]">
                                    <option value="active" {{ old('status', $student->status) == 'active' ? 'selected' : '' }}>Active</option>
                                    <option value="inactive" {{ old('status', $student->status) == 'inactive' ? 'selected' : '' }}>Inactive</option>
                                    <option value="graduated" {{ old('status', $student->status) == 'graduated' ? 'selected' : '' }}>Graduated</option>
                                    <option value="withdrawn" {{ old('status', $student->status) == 'withdrawn' ? 'selected' : '' }}>Withdrawn</option>
                                </select>
                                @error('status') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Enrollment Date</label>
                                <input type="date" name="enrollment_date" value="{{ old('enrollment_date', $student->enrollment_date?->format('Y-m-d')) }}"
                                       class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-[#4F46E5] focus:ring-[#4F46E5]">
                                @error('enrollment_date') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Classes Per Week</label>
                                <select name="classes_per_week" x-model="classesPerWeek" @change="updateScheduleSlots()"
                                        class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-[#4F46E5] focus:ring-[#4F46E5]">
                                    <option value="">Select</option>
                                    @for($i = 1; $i <= 7; $i++)
                                        <option value="{{ $i }}" {{ old('classes_per_week', $student->classes_per_week) == $i ? 'selected' : '' }}>{{ $i }} {{ $i == 1 ? 'class' : 'classes' }}</option>
                                    @endfor
                                </select>
                                @error('classes_per_week') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Google Classroom Link</label>
                                <input type="url" name="google_classroom_link" value="{{ old('google_classroom_link', $student->google_classroom_link) }}" placeholder="https://classroom.google.com/..."
                                       class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-[#4F46E5] focus:ring-[#4F46E5]">
                                @error('google_classroom_link') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Live Classroom Link</label>
                                <input type="url" name="live_classroom_link" value="{{ old('live_classroom_link', $student->live_classroom_link) }}" placeholder="https://meet.google.com/... or Zoom link"
                                       class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-[#4F46E5] focus:ring-[#4F46E5]">
                                @error('live_classroom_link') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Class Schedule - Moved before Course Progression and Parent Info -->
                    <div class="mb-8" x-show="classesPerWeek > 0">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 pb-2 border-b border-gray-200 dark:border-gray-700">
                            Class Schedule <span class="text-sm font-normal text-amber-600 dark:text-amber-400">(NG Time)</span>
                        </h3>
                        <div class="space-y-4">
                            <template x-for="(slot, index) in scheduleSlots" :key="index">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 p-4 bg-gray-50 dark:bg-gray-700/50 rounded-lg">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                            Day <span x-text="index + 1"></span>
                                        </label>
                                        <select :name="'class_schedule[' + index + '][day]'" x-model="slot.day"
                                                class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-[#4F46E5] focus:ring-[#4F46E5]">
                                            <option value="">Select Day</option>
                                            <option value="Monday">Monday</option>
                                            <option value="Tuesday">Tuesday</option>
                                            <option value="Wednesday">Wednesday</option>
                                            <option value="Thursday">Thursday</option>
                                            <option value="Friday">Friday</option>
                                            <option value="Saturday">Saturday</option>
                                            <option value="Sunday">Sunday</option>
                                        </select>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Time</label>
                                        <div class="flex items-center gap-1">
                                            <input type="hidden" :name="'class_schedule[' + index + '][time]'" :value="slot.time">
                                            <select x-model="slot.hour" @change="updateSlotTime(index)" class="w-16 px-2 py-2 rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-[#4F46E5] focus:ring-[#4F46E5] text-sm">
                                                <option value="">Hr</option>
                                                <template x-for="h in 12" :key="h">
                                                    <option :value="h" x-text="h"></option>
                                                </template>
                                            </select>
                                            <span class="text-gray-500 dark:text-gray-400 font-bold">:</span>
                                            <select x-model="slot.minute" @change="updateSlotTime(index)" class="w-16 px-2 py-2 rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-[#4F46E5] focus:ring-[#4F46E5] text-sm">
                                                <option value="">Min</option>
                                                @for($i = 0; $i < 60; $i += 5)
                                                    <option value="{{ str_pad($i, 2, '0', STR_PAD_LEFT) }}">{{ str_pad($i, 2, '0', STR_PAD_LEFT) }}</option>
                                                @endfor
                                            </select>
                                            <select x-model="slot.period" @change="updateSlotTime(index)" class="w-16 px-2 py-2 rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-[#4F46E5] focus:ring-[#4F46E5] text-sm">
                                                <option value="AM">AM</option>
                                                <option value="PM">PM</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </template>
                        </div>
                    </div>

                    <!-- Parent Information -->
                    <div class="mb-8">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 pb-2 border-b border-gray-200 dark:border-gray-700">
                            Parent/Guardian Information
                        </h3>
                        <p class="text-sm text-gray-500 dark:text-gray-400 mb-4">
                            If a new email is added, a parent account will be created and login credentials sent via email.
                        </p>
                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                            <!-- Father's Information -->
                            <div class="p-4 bg-blue-50 dark:bg-blue-900/20 rounded-lg border border-blue-100 dark:border-blue-800">
                                <h4 class="font-semibold text-gray-800 dark:text-white mb-4 flex items-center">
                                    <span class="w-8 h-8 bg-blue-100 dark:bg-blue-900/30 rounded-full flex items-center justify-center mr-2 text-blue-600 text-lg">F</span>
                                    Father's Information
                                </h4>
                                <div class="space-y-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Name</label>
                                        <input type="text" name="father_name" value="{{ old('father_name', $student->father_name) }}"
                                               class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-[#4F46E5] focus:ring-[#4F46E5]">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Phone</label>
                                        <input type="tel" name="father_phone" value="{{ old('father_phone', $student->father_phone) }}"
                                               class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-[#4F46E5] focus:ring-[#4F46E5]">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Email</label>
                                        <input type="email" name="father_email" value="{{ old('father_email', $student->father_email) }}"
                                               class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-[#4F46E5] focus:ring-[#4F46E5]">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Occupation</label>
                                        <input type="text" name="father_occupation" value="{{ old('father_occupation', $student->father_occupation) }}"
                                               class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-[#4F46E5] focus:ring-[#4F46E5]">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Location</label>
                                        <input type="text" name="father_location" value="{{ old('father_location', $student->father_location) }}"
                                               class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-[#4F46E5] focus:ring-[#4F46E5]">
                                    </div>
                                </div>
                            </div>

                            <!-- Mother's Information -->
                            <div class="p-4 bg-pink-50 dark:bg-pink-900/20 rounded-lg border border-pink-100 dark:border-pink-800">
                                <h4 class="font-semibold text-gray-800 dark:text-white mb-4 flex items-center">
                                    <span class="w-8 h-8 bg-pink-100 dark:bg-pink-900/30 rounded-full flex items-center justify-center mr-2 text-pink-600 text-lg">M</span>
                                    Mother's Information
                                </h4>
                                <div class="space-y-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Name</label>
                                        <input type="text" name="mother_name" value="{{ old('mother_name', $student->mother_name) }}"
                                               class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-[#4F46E5] focus:ring-[#4F46E5]">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Phone</label>
                                        <input type="tel" name="mother_phone" value="{{ old('mother_phone', $student->mother_phone) }}"
                                               class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-[#4F46E5] focus:ring-[#4F46E5]">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Email</label>
                                        <input type="email" name="mother_email" value="{{ old('mother_email', $student->mother_email) }}"
                                               class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-[#4F46E5] focus:ring-[#4F46E5]">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Occupation</label>
                                        <input type="text" name="mother_occupation" value="{{ old('mother_occupation', $student->mother_occupation) }}"
                                               class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-[#4F46E5] focus:ring-[#4F46E5]">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Location</label>
                                        <input type="text" name="mother_location" value="{{ old('mother_location', $student->mother_location) }}"
                                               class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-[#4F46E5] focus:ring-[#4F46E5]">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Course Progression - Moved after Parent Info -->
                    @if(isset($courses) && $courses->count() > 0)
                    <div class="mb-8">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 pb-2 border-b border-gray-200 dark:border-gray-700">
                            Course Progression
                        </h3>
                        <p class="text-sm text-gray-500 dark:text-gray-400 mb-4">Select courses the student has completed.</p>
                        @php
                            $completedCourseIds = old('completed_course_ids', $student->completedCourses->pluck('id')->toArray());
                        @endphp
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3">
                            @foreach($courses as $course)
                                <label class="flex items-center p-3 bg-gray-50 dark:bg-gray-700/50 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 cursor-pointer transition-colors">
                                    <input type="checkbox"
                                           name="completed_course_ids[]"
                                           value="{{ $course->id }}"
                                           {{ in_array($course->id, $completedCourseIds) ? 'checked' : '' }}
                                           class="h-4 w-4 text-green-600 focus:ring-green-500 border-gray-300 dark:border-gray-600 rounded">
                                    <span class="ml-3 text-sm text-gray-700 dark:text-gray-300">
                                        Level {{ $course->level }} - {{ $course->name }}
                                    </span>
                                </label>
                            @endforeach
                        </div>
                    </div>
                    @endif

                    <!-- Notes -->
                    <div class="mb-8">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 pb-2 border-b border-gray-200 dark:border-gray-700">
                            Additional Notes
                        </h3>
                        <textarea name="notes" rows="3" placeholder="Any additional notes about the student..."
                                  class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-[#4F46E5] focus:ring-[#4F46E5]">{{ old('notes', $student->notes) }}</textarea>
                        @error('notes') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                    </div>

                    <!-- Submit -->
                    <div class="flex justify-end space-x-4">
                        <a href="{{ route('director.students.index') }}" 
                           class="px-6 py-2 bg-gray-200 dark:bg-gray-600 text-gray-700 dark:text-gray-200 rounded-lg hover:bg-gray-300 dark:hover:bg-gray-500 transition-all">
                            Cancel
                        </a>
                        <button type="submit" 
                                class="px-6 py-2 bg-gradient-to-r from-[#4F46E5] to-[#818CF8] text-white rounded-lg hover:from-[#3730A3] hover:to-[#4F46E5] transition-all">
                            Update Student
                        </button>
                    </div>
                </form>
            </x-ui.glass-card>
        </div>
    </div>

    <script>
        function studentForm() {
            return {
                classesPerWeek: {{ old('classes_per_week', $student->classes_per_week ?? 0) }},
                scheduleSlots: [],

                parseTimeTo12Hour(time24) {
                    if (!time24) return { hour: '', minute: '', period: 'AM' };
                    const parts = time24.split(':');
                    if (parts.length < 2) return { hour: '', minute: '', period: 'AM' };

                    let h = parseInt(parts[0]);
                    const m = parts[1];
                    let period = 'AM';

                    if (h === 0) {
                        h = 12;
                        period = 'AM';
                    } else if (h === 12) {
                        period = 'PM';
                    } else if (h > 12) {
                        h = h - 12;
                        period = 'PM';
                    }

                    return { hour: String(h), minute: m, period: period };
                },

                updateSlotTime(index) {
                    const slot = this.scheduleSlots[index];
                    if (slot.hour && slot.minute) {
                        let h = parseInt(slot.hour);
                        if (slot.period === 'PM' && h !== 12) h += 12;
                        if (slot.period === 'AM' && h === 12) h = 0;
                        slot.time = String(h).padStart(2, '0') + ':' + slot.minute;
                    } else {
                        slot.time = '';
                    }
                },

                updateScheduleSlots() {
                    const count = parseInt(this.classesPerWeek) || 0;
                    const currentLength = this.scheduleSlots.length;

                    if (count > currentLength) {
                        for (let i = currentLength; i < count; i++) {
                            this.scheduleSlots.push({ day: '', time: '', hour: '', minute: '', period: 'AM' });
                        }
                    } else if (count < currentLength) {
                        this.scheduleSlots = this.scheduleSlots.slice(0, count);
                    }
                },

                init() {
                    // Load existing schedules, filtering out empty entries
                    let rawSchedules = @json($existingSchedules);

                    // Filter to only include schedules that have a day set
                    let validSchedules = Array.isArray(rawSchedules)
                        ? rawSchedules.filter(s => s && s.day && s.day.trim() !== '')
                        : [];

                    // Parse existing times to 12-hour format
                    this.scheduleSlots = validSchedules.map(slot => {
                        const parsed = this.parseTimeTo12Hour(slot.time);
                        return {
                            ...slot,
                            hour: parsed.hour,
                            minute: parsed.minute,
                            period: parsed.period
                        };
                    });

                    // If no valid schedules but classes per week is set, create empty slots
                    if (this.scheduleSlots.length === 0 && this.classesPerWeek > 0) {
                        this.updateScheduleSlots();
                    }
                }
            }
        }
    </script>
</x-app-layout>
