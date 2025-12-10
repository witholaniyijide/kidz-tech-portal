<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-2xl text-white">{{ __('Add Schedule Entry') }}</h2>
    </x-slot>
    <x-slot name="title">{{ __('Admin - Add Schedule') }}</x-slot>

    <div class="min-h-screen bg-gradient-to-br from-teal-50 via-cyan-50 to-blue-50 dark:from-slate-900 dark:via-slate-800 dark:to-slate-900 py-8 relative overflow-hidden">
        <div class="absolute top-0 left-0 w-72 h-72 bg-teal-300 dark:bg-teal-900 rounded-full mix-blend-multiply filter blur-xl opacity-70 animate-float"></div>
        <div class="absolute top-0 right-0 w-72 h-72 bg-cyan-300 dark:bg-cyan-900 rounded-full mix-blend-multiply filter blur-xl opacity-70 animate-float" style="animation-delay: 2s;"></div>

        <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            {{-- Header --}}
            <div class="flex items-center justify-between mb-8">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Add Schedule Entry</h1>
                    <p class="text-gray-600 dark:text-gray-400 mt-1">Create a new class schedule entry</p>
                </div>
                <a href="{{ route('admin.schedules.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-300 dark:hover:bg-gray-600 transition-colors">
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

            <form action="{{ route('admin.schedules.store') }}" method="POST" x-data="scheduleForm()">
                @csrf

                <div class="bg-white/60 dark:bg-gray-800/60 backdrop-blur-md border border-white/20 rounded-2xl shadow overflow-hidden">
                    <div class="px-6 py-4 bg-gradient-to-r from-teal-500 to-cyan-600 text-white">
                        <h3 class="text-lg font-semibold">Schedule Details</h3>
                    </div>
                    <div class="p-6 space-y-6">
                        {{-- Student --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Student <span class="text-red-500">*</span></label>
                            <select name="student_id" x-model="studentId" @change="onStudentChange()" required
                                    class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg focus:ring-2 focus:ring-teal-500">
                                <option value="">Select Student</option>
                                @foreach($students as $student)
                                    <option value="{{ $student->id }}" 
                                            data-tutor="{{ $student->tutor_id }}"
                                            data-class-link="{{ $student->class_link }}"
                                            {{ old('student_id') == $student->id ? 'selected' : '' }}>
                                        {{ $student->first_name }} {{ $student->last_name }}
                                        @if($student->tutor) (Tutor: {{ $student->tutor->first_name }}) @endif
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Tutor --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Tutor <span class="text-red-500">*</span></label>
                            <select name="tutor_id" x-model="tutorId" required
                                    class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg focus:ring-2 focus:ring-teal-500">
                                <option value="">Select Tutor</option>
                                @foreach($tutors as $tutor)
                                    <option value="{{ $tutor->id }}" {{ old('tutor_id') == $tutor->id ? 'selected' : '' }}>
                                        {{ $tutor->first_name }} {{ $tutor->last_name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Schedule Date --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Schedule Date <span class="text-red-500">*</span></label>
                            <input type="date" name="schedule_date" value="{{ old('schedule_date', date('Y-m-d')) }}" required
                                   class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg focus:ring-2 focus:ring-teal-500">
                        </div>

                        {{-- Class Time --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Class Time <span class="text-red-500">*</span></label>
                            <input type="time" name="class_time" value="{{ old('class_time', '09:00') }}" required
                                   class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg focus:ring-2 focus:ring-teal-500">
                        </div>

                        {{-- Class Link --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Class Link (Zoom/Meet)</label>
                            <input type="url" name="class_link" x-model="classLink" value="{{ old('class_link') }}" placeholder="https://..."
                                   class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg focus:ring-2 focus:ring-teal-500">
                        </div>

                        {{-- Notes --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Notes</label>
                            <textarea name="notes" rows="2" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg focus:ring-2 focus:ring-teal-500">{{ old('notes') }}</textarea>
                        </div>
                    </div>
                </div>

                {{-- Submit --}}
                <div class="mt-6 flex justify-end gap-4">
                    <a href="{{ route('admin.schedules.index') }}" class="px-6 py-3 bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-xl hover:bg-gray-300 dark:hover:bg-gray-600 transition-colors font-medium">
                        Cancel
                    </a>
                    <button type="submit" class="px-8 py-3 bg-gradient-to-r from-teal-500 to-cyan-600 text-white rounded-xl hover:shadow-lg transform hover:-translate-y-0.5 transition-all font-medium">
                        Create Schedule
                    </button>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
    <script>
        function scheduleForm() {
            return {
                studentId: '{{ old("student_id") }}',
                tutorId: '{{ old("tutor_id") }}',
                classLink: '{{ old("class_link") }}',
                
                onStudentChange() {
                    const select = document.querySelector('select[name="student_id"]');
                    const option = select.options[select.selectedIndex];
                    
                    if (option && option.value) {
                        const tutorId = option.dataset.tutor;
                        const classLink = option.dataset.classLink;
                        
                        if (tutorId) this.tutorId = tutorId;
                        if (classLink) this.classLink = classLink;
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
