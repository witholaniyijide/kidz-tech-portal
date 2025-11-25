<x-app-layout>
    <x-slot name="title">{{ __('Attendance') }}</x-slot>

    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Take Attendance
            </h2>
            <a href="{{ route('attendance.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                ← Back to List
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    
                    @if($errors->any())
                        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4">
                            <strong class="font-bold">Oops! There were some errors:</strong>
                            <ul class="mt-2 list-disc list-inside">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('attendance.store') }}" id="attendanceForm">
                        @csrf

                        <div class="mb-6 pb-6 border-b">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                
                                <div>
                                    <label for="class_date" class="block text-sm font-medium text-gray-700">Date *</label>
                                    <input type="date"
                                           name="class_date"
                                           id="class_date"
                                           value="{{ $selectedDate }}"
                                           required
                                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                </div>

                                <div>
                                    <label for="session" class="block text-sm font-medium text-gray-700">Session</label>
                                    <input type="text" 
                                           name="session" 
                                           id="session" 
                                           placeholder="e.g., Morning, Afternoon"
                                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                </div>

                            </div>
                        </div>

                        <div class="mb-4 flex justify-between items-center">
                            <h3 class="text-lg font-semibold text-gray-900">Mark Attendance</h3>
                            <div class="flex gap-2">
                                <button type="button" onclick="markAll('present')" class="text-sm bg-green-100 hover:bg-green-200 text-green-800 px-3 py-1 rounded">Mark All Present</button>
                                <button type="button" onclick="markAll('absent')" class="text-sm bg-red-100 hover:bg-red-200 text-red-800 px-3 py-1 rounded">Mark All Absent</button>
                            </div>
                        </div>

                        @if($students->count() > 0)
                            <div class="space-y-3">
                                @foreach($students as $index => $student)
                                    <div class="border rounded-lg p-4 {{ in_array($student->id, $existingAttendance) ? 'bg-gray-50' : '' }}">
                                        <div class="grid grid-cols-1 md:grid-cols-12 gap-4 items-center">
                                            
                                            <div class="md:col-span-4">
                                                <div class="font-medium text-gray-900">{{ $student->full_name }}</div>
                                                <div class="text-sm text-gray-500">{{ $student->student_id }}</div>
                                            </div>

                                            <div class="md:col-span-4">
                                                <input type="hidden" name="attendance[{{ $index }}][student_id]" value="{{ $student->id }}">
                                                <select name="attendance[{{ $index }}][status]" 
                                                        class="attendance-status w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                                        {{ in_array($student->id, $existingAttendance) ? 'disabled' : '' }}>
                                                    <option value="present">Present</option>
                                                    <option value="absent">Absent</option>
                                                    <option value="late">Late</option>
                                                    <option value="excused">Excused</option>
                                                </select>
                                            </div>

                                            <div class="md:col-span-4">
                                                <input type="text" 
                                                       name="attendance[{{ $index }}][notes]" 
                                                       placeholder="Notes (optional)"
                                                       {{ in_array($student->id, $existingAttendance) ? 'disabled' : '' }}
                                                       class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                            </div>

                                        </div>
                                        @if(in_array($student->id, $existingAttendance))
                                            <div class="mt-2 text-xs text-yellow-600">Attendance already recorded for this date</div>
                                        @endif
                                    </div>
                                @endforeach
                            </div>

                            <div class="flex items-center justify-between mt-8 pt-6 border-t border-gray-200">
                                <a href="{{ route('attendance.index') }}" style="display: inline-block; padding: 12px 24px; background-color: #6B7280; color: white; font-weight: bold; border-radius: 8px; text-decoration: none;">
                                    Cancel
                                </a>
                                <button type="submit" style="display: inline-block; padding: 12px 32px; background-color: #2563EB; color: white; font-weight: bold; border-radius: 8px; border: none; cursor: pointer; font-size: 16px;">
                                    ✓ Submit Attendance
                                </button>
                            </div>

                        @else
                            <div class="text-center py-12">
                                <p class="text-gray-500">No active students found.</p>
                                <a href="{{ route('students.create') }}" class="mt-4 inline-block text-blue-600 hover:text-blue-800">Add students first</a>
                            </div>
                        @endif

                    </form>

                </div>
            </div>
        </div>
    </div>

    <script>
        function markAll(status) {
            const selects = document.querySelectorAll('.attendance-status');
            selects.forEach(select => {
                if (!select.disabled) {
                    select.value = status;
                }
            });
        }
    </script>
</x-app-layout>
