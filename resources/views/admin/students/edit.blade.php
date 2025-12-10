<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-2xl text-white">{{ __('Edit Student') }}</h2>
    </x-slot>
    <x-slot name="title">{{ __('Admin - Edit Student') }}</x-slot>

    <div class="min-h-screen bg-gradient-to-br from-teal-50 via-cyan-50 to-blue-50 dark:from-slate-900 dark:via-slate-800 dark:to-slate-900 py-8 relative overflow-hidden">
        <div class="absolute top-0 left-0 w-72 h-72 bg-teal-300 dark:bg-teal-900 rounded-full mix-blend-multiply filter blur-xl opacity-70 animate-float"></div>
        <div class="absolute top-0 right-0 w-72 h-72 bg-cyan-300 dark:bg-cyan-900 rounded-full mix-blend-multiply filter blur-xl opacity-70 animate-float" style="animation-delay: 2s;"></div>

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
                    <div class="px-6 py-4 bg-gradient-to-r from-teal-500 to-cyan-600 text-white">
                        <h3 class="text-lg font-semibold">Section 1: Student Information</h3>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">First Name <span class="text-red-500">*</span></label>
                                <input type="text" name="first_name" value="{{ old('first_name', $student->first_name) }}" required
                                       class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg focus:ring-2 focus:ring-teal-500">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Other Name</label>
                                <input type="text" name="other_name" value="{{ old('other_name', $student->other_name) }}"
                                       class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg focus:ring-2 focus:ring-teal-500">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Last Name <span class="text-red-500">*</span></label>
                                <input type="text" name="last_name" value="{{ old('last_name', $student->last_name) }}" required
                                       class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg focus:ring-2 focus:ring-teal-500">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Email</label>
                                <input type="email" name="email" value="{{ old('email', $student->email) }}"
                                       class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg focus:ring-2 focus:ring-teal-500">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Date of Birth</label>
                                <input type="date" name="date_of_birth" value="{{ old('date_of_birth', $student->date_of_birth?->format('Y-m-d')) }}" x-model="dob" @change="calculateAge()"
                                       class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg focus:ring-2 focus:ring-teal-500">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Age</label>
                                <input type="text" x-model="age" readonly
                                       class="w-full px-4 py-2 bg-gray-100 dark:bg-gray-600 border border-gray-300 dark:border-gray-600 dark:text-white rounded-lg">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Gender</label>
                                <select name="gender" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg focus:ring-2 focus:ring-teal-500">
                                    <option value="">Select Gender</option>
                                    <option value="male" {{ old('gender', $student->gender) === 'male' ? 'selected' : '' }}>Male</option>
                                    <option value="female" {{ old('gender', $student->gender) === 'female' ? 'selected' : '' }}>Female</option>
                                    <option value="other" {{ old('gender', $student->gender) === 'other' ? 'selected' : '' }}>Other</option>
                                </select>
                            </div>
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Coding Experience</label>
                                <textarea name="coding_experience" rows="2" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg focus:ring-2 focus:ring-teal-500">{{ old('coding_experience', $student->coding_experience) }}</textarea>
                            </div>
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Career Interest</label>
                                <textarea name="career_interest" rows="2" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg focus:ring-2 focus:ring-teal-500">{{ old('career_interest', $student->career_interest) }}</textarea>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Status <span class="text-red-500">*</span></label>
                                <select name="status" required class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg focus:ring-2 focus:ring-teal-500">
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
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Total Periods</label>
                                <input type="number" name="total_periods" value="{{ old('total_periods', $student->total_periods) }}" min="0"
                                       class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg focus:ring-2 focus:ring-blue-500">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Completed Periods</label>
                                <input type="text" value="{{ $student->completed_periods ?? 0 }}" readonly
                                       class="w-full px-4 py-2 bg-gray-100 dark:bg-gray-600 border border-gray-300 dark:border-gray-600 dark:text-white rounded-lg">
                            </div>
                        </div>

                        {{-- Dynamic Class Schedule --}}
                        <div class="mt-6">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">Class Schedule</label>
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
                                        <input type="time" x-model="schedule.time" :name="'class_schedule['+index+'][time]'" 
                                               class="px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg">
                                        <button type="button" @click="removeSchedule(index)" class="p-2 text-red-500 hover:bg-red-50 dark:hover:bg-red-900/20 rounded-lg">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                            </svg>
                                        </button>
                                    </div>
                                </template>
                            </div>
                            <button type="button" @click="addSchedule()" class="mt-3 text-sm text-teal-600 dark:text-teal-400 hover:underline">
                                + Add Schedule Slot
                            </button>
                        </div>
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
                    <button type="submit" class="px-8 py-3 bg-gradient-to-r from-teal-500 to-cyan-600 text-white rounded-xl hover:shadow-lg transform hover:-translate-y-0.5 transition-all font-medium">
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
                schedules: @json($student->class_schedule ?? [['day' => '', 'time' => '']]),
                
                calculateAge() {
                    if (!this.dob) { this.age = ''; return; }
                    const today = new Date();
                    const birthDate = new Date(this.dob);
                    let age = today.getFullYear() - birthDate.getFullYear();
                    const m = today.getMonth() - birthDate.getMonth();
                    if (m < 0 || (m === 0 && today.getDate() < birthDate.getDate())) age--;
                    this.age = age + ' years';
                },
                
                addSchedule() { this.schedules.push({ day: '', time: '' }); },
                removeSchedule(index) { if (this.schedules.length > 1) this.schedules.splice(index, 1); },
                init() { this.calculateAge(); if (!this.schedules.length) this.schedules = [{ day: '', time: '' }]; }
            };
        }
    </script>
    @endpush
</x-app-layout>
