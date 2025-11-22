<!DOCTYPE html>
<html lang="en" x-data="studentForm()">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Student - Kidz Tech Portal</title>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gradient-to-br from-slate-50 via-cyan-50 to-teal-50 dark:from-slate-900 dark:via-slate-800 dark:to-slate-900 min-h-screen">

    @include('layouts.navigation')

    <div class="py-8 px-4 sm:px-6 lg:px-8">
        <div class="max-w-6xl mx-auto">

            {{-- Header --}}
            <div class="mb-8 flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-800 dark:text-white">Edit Student: {{ $student->first_name }} {{ $student->last_name }}</h1>
                    <p class="text-gray-600 dark:text-gray-400 mt-1">Update student profile information</p>
                </div>
                <a href="{{ route('students.index') }}" class="px-6 py-3 bg-gray-500 hover:bg-gray-600 text-white font-semibold rounded-xl shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all duration-300">
                    <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    Back to Students
                </a>
            </div>

            @if($errors->any())
                <div class="mb-6 bg-red-50 dark:bg-red-900/20 border-l-4 border-red-500 p-4 rounded-lg">
                    <div class="flex items-center mb-2">
                        <svg class="w-6 h-6 text-red-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <strong class="font-bold text-red-800 dark:text-red-300">Oops! There were some errors:</strong>
                    </div>
                    <ul class="list-disc list-inside text-red-700 dark:text-red-400">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('students.update', $student) }}" class="space-y-6">
                @csrf
                @method('PUT')

                {{-- SECTION 1: Student Information --}}
                <div class="relative overflow-hidden rounded-2xl bg-white/70 dark:bg-slate-800/70 backdrop-blur-xl shadow-xl p-6 border border-white/20">
                    <div class="flex items-center mb-6 pb-4 border-b border-gray-200 dark:border-slate-700">
                        <div class="p-3 bg-gradient-to-br from-teal-500 to-cyan-500 rounded-xl mr-4">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                        </div>
                        <h2 class="text-2xl font-bold text-gray-800 dark:text-white">Student Information</h2>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="first_name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                First Name <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="first_name" id="first_name" value="{{ old('first_name', $student->first_name) }}" required
                                class="w-full px-4 py-3 rounded-lg border border-gray-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-teal-500 focus:border-transparent transition-all">
                        </div>

                        <div>
                            <label for="other_name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Other Name
                            </label>
                            <input type="text" name="other_name" id="other_name" value="{{ old('other_name', $student->other_name) }}"
                                class="w-full px-4 py-3 rounded-lg border border-gray-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-teal-500 focus:border-transparent transition-all">
                        </div>

                        <div>
                            <label for="last_name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Last Name <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="last_name" id="last_name" value="{{ old('last_name', $student->last_name) }}" required
                                class="w-full px-4 py-3 rounded-lg border border-gray-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-teal-500 focus:border-transparent transition-all">
                        </div>

                        <div>
                            <label for="date_of_birth" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Date of Birth <span class="text-red-500">*</span>
                            </label>
                            <input type="date" name="date_of_birth" id="date_of_birth" value="{{ old('date_of_birth', $student->date_of_birth ? $student->date_of_birth->format('Y-m-d') : '') }}" required
                                x-model="dateOfBirth" @change="calculateAge"
                                class="w-full px-4 py-3 rounded-lg border border-gray-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-teal-500 focus:border-transparent transition-all">
                        </div>

                        <div>
                            <label for="age" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Age (Auto-calculated)
                            </label>
                            <input type="number" name="age" id="age" x-model="age" readonly
                                class="w-full px-4 py-3 rounded-lg border border-gray-300 dark:border-slate-600 bg-gray-100 dark:bg-slate-600 text-gray-900 dark:text-white cursor-not-allowed">
                        </div>

                        <div>
                            <label for="gender" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Gender <span class="text-red-500">*</span>
                            </label>
                            <select name="gender" id="gender" required
                                class="w-full px-4 py-3 rounded-lg border border-gray-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-teal-500 focus:border-transparent transition-all">
                                <option value="">Select Gender</option>
                                <option value="male" {{ old('gender', $student->gender) == 'male' ? 'selected' : '' }}>Male</option>
                                <option value="female" {{ old('gender', $student->gender) == 'female' ? 'selected' : '' }}>Female</option>
                            </select>
                        </div>

                        <div class="md:col-span-2">
                            <label for="coding_experience" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Coding Experience
                            </label>
                            <textarea name="coding_experience" id="coding_experience" rows="3"
                                class="w-full px-4 py-3 rounded-lg border border-gray-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-teal-500 focus:border-transparent transition-all">{{ old('coding_experience', $student->coding_experience) }}</textarea>
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Brief description of any prior coding experience</p>
                        </div>

                        <div class="md:col-span-2">
                            <label for="career_interest" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Career Interest
                            </label>
                            <textarea name="career_interest" id="career_interest" rows="2"
                                class="w-full px-4 py-3 rounded-lg border border-gray-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-teal-500 focus:border-transparent transition-all">{{ old('career_interest', $student->career_interest) }}</textarea>
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">What area of technology interests the student?</p>
                        </div>

                        <div>
                            <label for="status" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Status <span class="text-red-500">*</span>
                            </label>
                            <select name="status" id="status"
                                class="w-full px-4 py-3 rounded-lg border border-gray-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-teal-500 focus:border-transparent transition-all">
                                <option value="active" {{ old('status', $student->status) == 'active' ? 'selected' : '' }}>Active</option>
                                <option value="inactive" {{ old('status', $student->status) == 'inactive' ? 'selected' : '' }}>Inactive</option>
                            </select>
                        </div>
                    </div>
                </div>

                {{-- SECTION 2: Class Information --}}
                <div class="relative overflow-hidden rounded-2xl bg-white/70 dark:bg-slate-800/70 backdrop-blur-xl shadow-xl p-6 border border-white/20">
                    <div class="flex items-center mb-6 pb-4 border-b border-gray-200 dark:border-slate-700">
                        <div class="p-3 bg-gradient-to-br from-purple-500 to-pink-500 rounded-xl mr-4">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                            </svg>
                        </div>
                        <h2 class="text-2xl font-bold text-gray-800 dark:text-white">Class Information</h2>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="md:col-span-2">
                            <label for="google_classroom_link" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Google Classroom Link <span class="text-red-500">*</span>
                            </label>
                            <input type="url" name="google_classroom_link" id="google_classroom_link" value="{{ old('google_classroom_link', $student->google_classroom_link) }}" required
                                class="w-full px-4 py-3 rounded-lg border border-gray-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-teal-500 focus:border-transparent transition-all">
                            <p class="text-xs text-teal-600 dark:text-teal-400 mt-2 flex items-center">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                Course and level tracked inside Google Classroom
                            </p>
                        </div>

                        <div class="md:col-span-2">
                            <label for="class_link" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Class Link (Zoom/Meet URL)
                            </label>
                            <input type="url" name="class_link" id="class_link" value="{{ old('class_link', $student->class_link) }}"
                                class="w-full px-4 py-3 rounded-lg border border-gray-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-teal-500 focus:border-transparent transition-all">
                        </div>

                        <div class="md:col-span-2">
                            <label for="tutor_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Assigned Tutor <span class="text-red-500">*</span>
                            </label>
                            <select name="tutor_id" id="tutor_id" required
                                class="w-full px-4 py-3 rounded-lg border border-gray-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-teal-500 focus:border-transparent transition-all">
                                <option value="">Select a Tutor</option>
                                @foreach(\App\Models\Tutor::where('status', 'active')->orderBy('first_name')->get() as $tutor)
                                    <option value="{{ $tutor->id }}" {{ old('tutor_id', $student->tutor_id) == $tutor->id ? 'selected' : '' }}>
                                        {{ $tutor->first_name }} {{ $tutor->last_name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">
                                Class Schedule
                            </label>
                            <div class="space-y-3">
                                <template x-for="(schedule, index) in classSchedules" :key="index">
                                    <div class="flex gap-3 items-start">
                                        <select x-model="schedule.day" :name="'class_schedule[' + index + '][day]'"
                                            class="flex-1 px-4 py-3 rounded-lg border border-gray-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-teal-500">
                                            <option value="">Select Day</option>
                                            <option value="Monday">Monday</option>
                                            <option value="Tuesday">Tuesday</option>
                                            <option value="Wednesday">Wednesday</option>
                                            <option value="Thursday">Thursday</option>
                                            <option value="Friday">Friday</option>
                                            <option value="Saturday">Saturday</option>
                                            <option value="Sunday">Sunday</option>
                                        </select>
                                        <input type="time" x-model="schedule.time" :name="'class_schedule[' + index + '][time]'"
                                            class="flex-1 px-4 py-3 rounded-lg border border-gray-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-teal-500">
                                        <button type="button" @click="removeSchedule(index)" x-show="classSchedules.length > 1"
                                            class="px-4 py-3 bg-red-500 hover:bg-red-600 text-white rounded-lg transition-all">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                            </svg>
                                        </button>
                                    </div>
                                </template>
                                <button type="button" @click="addSchedule"
                                    class="w-full px-4 py-3 bg-teal-500 hover:bg-teal-600 text-white font-semibold rounded-lg transition-all flex items-center justify-center">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                                    </svg>
                                    Add Another Day/Time
                                </button>
                            </div>
                        </div>

                        <div>
                            <label for="classes_per_week" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Classes Per Week
                            </label>
                            <select name="classes_per_week" id="classes_per_week"
                                class="w-full px-4 py-3 rounded-lg border border-gray-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-teal-500 focus:border-transparent transition-all">
                                <option value="1" {{ old('classes_per_week', $student->classes_per_week) == '1' ? 'selected' : '' }}>1 Class</option>
                                <option value="2" {{ old('classes_per_week', $student->classes_per_week) == '2' ? 'selected' : '' }}>2 Classes</option>
                                <option value="3" {{ old('classes_per_week', $student->classes_per_week) == '3' ? 'selected' : '' }}>3 Classes</option>
                            </select>
                        </div>

                        <div>
                            <label for="total_periods" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Total Periods
                            </label>
                            <input type="number" name="total_periods" id="total_periods" value="{{ old('total_periods', $student->total_periods) }}"
                                class="w-full px-4 py-3 rounded-lg border border-gray-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-teal-500 focus:border-transparent transition-all">
                        </div>

                        <div>
                            <label for="completed_periods" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Completed Periods (Read-only)
                            </label>
                            <input type="number" name="completed_periods" id="completed_periods" value="{{ old('completed_periods', $student->completed_periods) }}" readonly
                                class="w-full px-4 py-3 rounded-lg border border-gray-300 dark:border-slate-600 bg-gray-100 dark:bg-slate-600 text-gray-900 dark:text-white cursor-not-allowed">
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Auto-updated from attendance records</p>
                        </div>
                    </div>
                </div>

                {{-- SECTION 3: Father's Information --}}
                <div class="relative overflow-hidden rounded-2xl bg-white/70 dark:bg-slate-800/70 backdrop-blur-xl shadow-xl p-6 border border-white/20">
                    <div class="flex items-center mb-6 pb-4 border-b border-gray-200 dark:border-slate-700">
                        <div class="p-3 bg-gradient-to-br from-blue-500 to-indigo-500 rounded-xl mr-4">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                        </div>
                        <h2 class="text-2xl font-bold text-gray-800 dark:text-white">Father's Information</h2>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="father_name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Father's Name
                            </label>
                            <input type="text" name="father_name" id="father_name" value="{{ old('father_name', $student->father_name) }}"
                                class="w-full px-4 py-3 rounded-lg border border-gray-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-teal-500 focus:border-transparent transition-all">
                        </div>

                        <div>
                            <label for="father_phone" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Father's Phone
                            </label>
                            <input type="tel" name="father_phone" id="father_phone" value="{{ old('father_phone', $student->father_phone) }}"
                                class="w-full px-4 py-3 rounded-lg border border-gray-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-teal-500 focus:border-transparent transition-all">
                        </div>

                        <div>
                            <label for="father_email" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Father's Email
                            </label>
                            <input type="email" name="father_email" id="father_email" value="{{ old('father_email', $student->father_email) }}"
                                class="w-full px-4 py-3 rounded-lg border border-gray-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-teal-500 focus:border-transparent transition-all">
                        </div>

                        <div>
                            <label for="father_occupation" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Father's Occupation
                            </label>
                            <input type="text" name="father_occupation" id="father_occupation" value="{{ old('father_occupation', $student->father_occupation) }}"
                                class="w-full px-4 py-3 rounded-lg border border-gray-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-teal-500 focus:border-transparent transition-all">
                        </div>

                        <div class="md:col-span-2">
                            <label for="father_location" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Father's Location
                            </label>
                            <input type="text" name="father_location" id="father_location" value="{{ old('father_location', $student->father_location) }}"
                                class="w-full px-4 py-3 rounded-lg border border-gray-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-teal-500 focus:border-transparent transition-all">
                        </div>
                    </div>
                </div>

                {{-- SECTION 4: Mother's Information --}}
                <div class="relative overflow-hidden rounded-2xl bg-white/70 dark:bg-slate-800/70 backdrop-blur-xl shadow-xl p-6 border border-white/20">
                    <div class="flex items-center mb-6 pb-4 border-b border-gray-200 dark:border-slate-700">
                        <div class="p-3 bg-gradient-to-br from-pink-500 to-rose-500 rounded-xl mr-4">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                        </div>
                        <h2 class="text-2xl font-bold text-gray-800 dark:text-white">Mother's Information</h2>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="mother_name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Mother's Name
                            </label>
                            <input type="text" name="mother_name" id="mother_name" value="{{ old('mother_name', $student->mother_name) }}"
                                class="w-full px-4 py-3 rounded-lg border border-gray-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-teal-500 focus:border-transparent transition-all">
                        </div>

                        <div>
                            <label for="mother_phone" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Mother's Phone
                            </label>
                            <input type="tel" name="mother_phone" id="mother_phone" value="{{ old('mother_phone', $student->mother_phone) }}"
                                class="w-full px-4 py-3 rounded-lg border border-gray-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-teal-500 focus:border-transparent transition-all">
                        </div>

                        <div>
                            <label for="mother_email" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Mother's Email
                            </label>
                            <input type="email" name="mother_email" id="mother_email" value="{{ old('mother_email', $student->mother_email) }}"
                                class="w-full px-4 py-3 rounded-lg border border-gray-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-teal-500 focus:border-transparent transition-all">
                        </div>

                        <div>
                            <label for="mother_occupation" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Mother's Occupation
                            </label>
                            <input type="text" name="mother_occupation" id="mother_occupation" value="{{ old('mother_occupation', $student->mother_occupation) }}"
                                class="w-full px-4 py-3 rounded-lg border border-gray-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-teal-500 focus:border-transparent transition-all">
                        </div>

                        <div class="md:col-span-2">
                            <label for="mother_location" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Mother's Location
                            </label>
                            <input type="text" name="mother_location" id="mother_location" value="{{ old('mother_location', $student->mother_location) }}"
                                class="w-full px-4 py-3 rounded-lg border border-gray-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-teal-500 focus:border-transparent transition-all">
                        </div>
                    </div>
                </div>

                {{-- Action Buttons --}}
                <div class="flex items-center justify-between gap-4 pt-4">
                    <a href="{{ route('students.index') }}"
                        class="px-8 py-4 bg-gray-500 hover:bg-gray-600 text-white font-semibold rounded-xl shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all duration-300">
                        Cancel
                    </a>
                    <button type="submit"
                        class="px-8 py-4 bg-gradient-to-r from-teal-500 to-cyan-500 hover:from-teal-600 hover:to-cyan-600 text-white font-semibold rounded-xl shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all duration-300">
                        <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        Update Student
                    </button>
                </div>

            </form>

        </div>
    </div>

    <script>
        function studentForm() {
            // Parse existing class schedule
            const existingSchedules = @json($student->class_schedule ?? []);
            const schedules = Array.isArray(existingSchedules) && existingSchedules.length > 0
                ? existingSchedules
                : [{ day: '', time: '' }];

            return {
                dateOfBirth: '{{ old('date_of_birth', $student->date_of_birth ? $student->date_of_birth->format('Y-m-d') : '') }}',
                age: {{ old('age', $student->age ?? 0) }},
                classSchedules: schedules,

                calculateAge() {
                    if (!this.dateOfBirth) {
                        this.age = 0;
                        return;
                    }

                    const today = new Date();
                    const birthDate = new Date(this.dateOfBirth);
                    let age = today.getFullYear() - birthDate.getFullYear();
                    const monthDiff = today.getMonth() - birthDate.getMonth();

                    if (monthDiff < 0 || (monthDiff === 0 && today.getDate() < birthDate.getDate())) {
                        age--;
                    }

                    this.age = age;
                },

                addSchedule() {
                    this.classSchedules.push({ day: '', time: '' });
                },

                removeSchedule(index) {
                    if (this.classSchedules.length > 1) {
                        this.classSchedules.splice(index, 1);
                    }
                }
            }
        }

        // Calculate age on page load
        document.addEventListener('DOMContentLoaded', function() {
            window.dispatchEvent(new CustomEvent('calculate-age'));
        });
    </script>

</body>
</html>
