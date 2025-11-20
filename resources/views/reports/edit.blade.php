<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Edit Report: {{ $report->student->full_name }}
            </h2>
            <a href="{{ route('reports.show', $report) }}" style="display: inline-block; padding: 10px 20px; background-color: #6B7280; color: white; font-weight: bold; border-radius: 8px; text-decoration: none;">
                ← Back to Report
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
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

                    <form method="POST" action="{{ route('reports.update', $report) }}" id="reportForm">
                        @csrf
                        @method('PUT')

                        <div class="mb-6 pb-6 border-b">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">Basic Information</h3>
                            
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                
                                <div>
                                    <label for="student_id" class="block text-sm font-medium text-gray-700">Student *</label>
                                    <select name="student_id" id="student_id" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                        <option value="">Select Student</option>
                                        @foreach($students as $student)
                                            <option value="{{ $student->id }}" {{ old('student_id', $report->student_id) == $student->id ? 'selected' : '' }}>
                                                {{ $student->full_name }} ({{ $student->student_id }})
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div>
                                    <label for="month" class="block text-sm font-medium text-gray-700">Month *</label>
                                    <select name="month" id="month" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                        <option value="">Select Month</option>
                                        @foreach(['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'] as $month)
                                            <option value="{{ $month }}" {{ old('month', $report->month) == $month ? 'selected' : '' }}>{{ $month }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div>
                                    <label for="year" class="block text-sm font-medium text-gray-700">Year *</label>
                                    <select name="year" id="year" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                        <option value="">Select Year</option>
                                        @foreach(['2024', '2025', '2026'] as $year)
                                            <option value="{{ $year }}" {{ old('year', $report->year) == $year ? 'selected' : '' }}>{{ $year }}</option>
                                        @endforeach
                                    </select>
                                </div>

                            </div>
                        </div>

                        <div class="mb-6 pb-6 border-b">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">Course(s) Taught *</h3>
                            
                            <div id="coursesContainer" class="space-y-2">
                                @foreach(old('courses', $report->courses) as $course)
                                    <div class="flex gap-2">
                                        <input type="text" name="courses[]" value="{{ $course }}" placeholder="Enter course name" required class="flex-1 rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                        @if($loop->first)
                                            <button type="button" onclick="addCourse()" style="padding: 10px 20px; background-color: #059669; color: white; font-weight: bold; border-radius: 8px; border: none; cursor: pointer;">
                                                + Add
                                            </button>
                                        @else
                                            <button type="button" onclick="this.parentElement.remove()" style="padding: 10px 20px; background-color: #DC2626; color: white; font-weight: bold; border-radius: 8px; border: none; cursor: pointer;">
                                                Remove
                                            </button>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <div class="mb-6 pb-6 border-b" style="background-color: #F0F9FF; padding: 20px; border-radius: 12px;">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">1. Progress Overview *</h3>
                            
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Skills Mastered *</label>
                                <div id="skillsMasteredContainer" class="space-y-2 mb-2">
                                    <div class="flex gap-2">
                                        <input type="text" id="masteredSkillInput" placeholder="Enter skill mastered" class="flex-1 rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                        <button type="button" onclick="addSkillMastered()" style="padding: 10px 20px; background-color: #2563EB; color: white; font-weight: bold; border-radius: 8px; border: none; cursor: pointer;">
                                            + Add
                                        </button>
                                    </div>
                                </div>
                                <div id="masteredSkillsList" class="flex flex-wrap gap-2 mt-2"></div>
                                <input type="hidden" name="skills_mastered" id="skills_mastered_hidden">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">New Skills</label>
                                <div id="skillsNewContainer" class="space-y-2 mb-2">
                                    <div class="flex gap-2">
                                        <input type="text" id="newSkillInput" placeholder="Enter new skill" class="flex-1 rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                        <button type="button" onclick="addSkillNew()" style="padding: 10px 20px; background-color: #2563EB; color: white; font-weight: bold; border-radius: 8px; border: none; cursor: pointer;">
                                            + Add
                                        </button>
                                    </div>
                                </div>
                                <div id="newSkillsList" class="flex flex-wrap gap-2 mt-2"></div>
                                <input type="hidden" name="skills_new" id="skills_new_hidden">
                            </div>
                        </div>

                        <div class="mb-6 pb-6 border-b" style="background-color: #ECFDF5; padding: 20px; border-radius: 12px;">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">2. Projects/Activities Completed *</h3>
                            
                            <div id="projectsContainer" class="space-y-3">
                                @foreach(old('projects', $report->projects) as $index => $project)
                                    <div class="project-item bg-white p-4 rounded-lg border">
                                        <div class="flex justify-between items-center mb-2">
                                            <div class="font-semibold">Project {{ $index + 1 }}</div>
                                            @if($index > 0)
                                                <button type="button" onclick="this.closest('.project-item').remove()" style="padding: 6px 12px; background-color: #DC2626; color: white; font-weight: bold; border-radius: 8px; border: none; cursor: pointer; font-size: 12px;">
                                                    Remove
                                                </button>
                                            @endif
                                        </div>
                                        <input type="text" name="projects[{{ $index }}][title]" value="{{ $project['title'] }}" placeholder="Project title" required class="w-full mb-2 rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                        <input type="text" name="projects[{{ $index }}][link]" value="{{ $project['link'] ?? '' }}" placeholder="Project link or details" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                    </div>
                                @endforeach
                            </div>
                            
                            <button type="button" onclick="addProject()" style="margin-top: 12px; padding: 8px 16px; background-color: #059669; color: white; font-weight: bold; border-radius: 8px; border: none; cursor: pointer;">
                                + Add Another Project
                            </button>
                        </div>

                        <div class="mb-6 pb-6 border-b" style="background-color: #FFF2F2; padding: 20px; border-radius: 12px;">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">3. Areas for Improvement *</h3>
                            <textarea name="improvement" id="improvement" rows="4" required class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">{{ old('improvement', $report->improvement) }}</textarea>
                        </div>

                        <div class="mb-6 pb-6 border-b" style="background-color: #FFFBEB; padding: 20px; border-radius: 12px;">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">4. Goals for Next Month *</h3>
                            <textarea name="goals" id="goals" rows="4" required class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">{{ old('goals', $report->goals) }}</textarea>
                        </div>

                        <div class="mb-6 pb-6 border-b" style="background-color: #F0F9FF; padding: 20px; border-radius: 12px;">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">5. Assignment/Projects during the month *</h3>
                            <textarea name="assignments" id="assignments" rows="4" required class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">{{ old('assignments', $report->assignments) }}</textarea>
                        </div>

<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Edit Report: {{ $report->student->full_name }}
            </h2>
            <a href="{{ route('reports.show', $report) }}" style="display: inline-block; padding: 10px 20px; background-color: #6B7280; color: white; font-weight: bold; border-radius: 8px; text-decoration: none;">
                ← Back to Report
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
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

                    <form method="POST" action="{{ route('reports.update', $report) }}" id="reportForm">
                        @csrf
                        @method('PUT')

                        <div class="mb-6 pb-6 border-b">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">Basic Information</h3>
                            
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                
                                <div>
                                    <label for="student_id" class="block text-sm font-medium text-gray-700">Student *</label>
                                    <select name="student_id" id="student_id" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                        <option value="">Select Student</option>
                                        @foreach($students as $student)
                                            <option value="{{ $student->id }}" {{ old('student_id', $report->student_id) == $student->id ? 'selected' : '' }}>
                                                {{ $student->full_name }} ({{ $student->student_id }})
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div>
                                    <label for="month" class="block text-sm font-medium text-gray-700">Month *</label>
                                    <select name="month" id="month" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                        <option value="">Select Month</option>
                                        @foreach(['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'] as $month)
                                            <option value="{{ $month }}" {{ old('month', $report->month) == $month ? 'selected' : '' }}>{{ $month }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div>
                                    <label for="year" class="block text-sm font-medium text-gray-700">Year *</label>
                                    <select name="year" id="year" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                        <option value="">Select Year</option>
                                        @foreach(['2024', '2025', '2026'] as $year)
                                            <option value="{{ $year }}" {{ old('year', $report->year) == $year ? 'selected' : '' }}>{{ $year }}</option>
                                        @endforeach
                                    </select>
                                </div>

                            </div>
                        </div>

                        <div class="mb-6 pb-6 border-b">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">Course(s) Taught *</h3>
                            
                            <div id="coursesContainer" class="space-y-2">
                                @foreach(old('courses', $report->courses) as $course)
                                    <div class="flex gap-2">
                                        <input type="text" name="courses[]" value="{{ $course }}" placeholder="Enter course name" required class="flex-1 rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                        @if($loop->first)
                                            <button type="button" onclick="addCourse()" style="padding: 10px 20px; background-color: #059669; color: white; font-weight: bold; border-radius: 8px; border: none; cursor: pointer;">
                                                + Add
                                            </button>
                                        @else
                                            <button type="button" onclick="this.parentElement.remove()" style="padding: 10px 20px; background-color: #DC2626; color: white; font-weight: bold; border-radius: 8px; border: none; cursor: pointer;">
                                                Remove
                                            </button>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <div class="mb-6 pb-6 border-b" style="background-color: #F0F9FF; padding: 20px; border-radius: 12px;">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">1. Progress Overview *</h3>
                            
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Skills Mastered *</label>
                                <div id="skillsMasteredContainer" class="space-y-2 mb-2">
                                    <div class="flex gap-2">
                                        <input type="text" id="masteredSkillInput" placeholder="Enter skill mastered" class="flex-1 rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                        <button type="button" onclick="addSkillMastered()" style="padding: 10px 20px; background-color: #2563EB; color: white; font-weight: bold; border-radius: 8px; border: none; cursor: pointer;">
                                            + Add
                                        </button>
                                    </div>
                                </div>
                                <div id="masteredSkillsList" class="flex flex-wrap gap-2 mt-2"></div>
                                <input type="hidden" name="skills_mastered" id="skills_mastered_hidden">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">New Skills</label>
                                <div id="skillsNewContainer" class="space-y-2 mb-2">
                                    <div class="flex gap-2">
                                        <input type="text" id="newSkillInput" placeholder="Enter new skill" class="flex-1 rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                        <button type="button" onclick="addSkillNew()" style="padding: 10px 20px; background-color: #2563EB; color: white; font-weight: bold; border-radius: 8px; border: none; cursor: pointer;">
                                            + Add
                                        </button>
                                    </div>
                                </div>
                                <div id="newSkillsList" class="flex flex-wrap gap-2 mt-2"></div>
                                <input type="hidden" name="skills_new" id="skills_new_hidden">
                            </div>
                        </div>

                        <div class="mb-6 pb-6 border-b" style="background-color: #ECFDF5; padding: 20px; border-radius: 12px;">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">2. Projects/Activities Completed *</h3>
                            
                            <div id="projectsContainer" class="space-y-3">
                                @foreach(old('projects', $report->projects) as $index => $project)
                                    <div class="project-item bg-white p-4 rounded-lg border">
                                        <div class="flex justify-between items-center mb-2">
                                            <div class="font-semibold">Project {{ $index + 1 }}</div>
                                            @if($index > 0)
                                                <button type="button" onclick="this.closest('.project-item').remove()" style="padding: 6px 12px; background-color: #DC2626; color: white; font-weight: bold; border-radius: 8px; border: none; cursor: pointer; font-size: 12px;">
                                                    Remove
                                                </button>
                                            @endif
                                        </div>
                                        <input type="text" name="projects[{{ $index }}][title]" value="{{ $project['title'] }}" placeholder="Project title" required class="w-full mb-2 rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                        <input type="text" name="projects[{{ $index }}][link]" value="{{ $project['link'] ?? '' }}" placeholder="Project link or details" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                    </div>
                                @endforeach
                            </div>
                            
                            <button type="button" onclick="addProject()" style="margin-top: 12px; padding: 8px 16px; background-color: #059669; color: white; font-weight: bold; border-radius: 8px; border: none; cursor: pointer;">
                                + Add Another Project
                            </button>
                        </div>

                        <div class="mb-6 pb-6 border-b" style="background-color: #FFF2F2; padding: 20px; border-radius: 12px;">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">3. Areas for Improvement *</h3>
                            <textarea name="improvement" id="improvement" rows="4" required class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">{{ old('improvement', $report->improvement) }}</textarea>
                        </div>

                        <div class="mb-6 pb-6 border-b" style="background-color: #FFFBEB; padding: 20px; border-radius: 12px;">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">4. Goals for Next Month *</h3>
                            <textarea name="goals" id="goals" rows="4" required class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">{{ old('goals', $report->goals) }}</textarea>
                        </div>

                        <div class="mb-6 pb-6 border-b" style="background-color: #F0F9FF; padding: 20px; border-radius: 12px;">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">5. Assignment/Projects during the month *</h3>
                            <textarea name="assignments" id="assignments" rows="4" required class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">{{ old('assignments', $report->assignments) }}</textarea>
                        </div>

                        <div class="mb-6" style="background-color: #ECFDF5; padding: 20px; border-radius: 12px;">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">6. Comments/Observation *</h3>
                            <textarea name="comments" id="comments" rows="4" required class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">{{ old('comments', $report->comments) }}</textarea>
                        </div>

                        <div class="flex items-center justify-between mt-8 pt-6 border-t border-gray-200">
                            <a href="{{ route('reports.show', $report) }}" style="display: inline-block; padding: 12px 24px; background-color: #6B7280; color: white; font-weight: bold; border-radius: 8px; text-decoration: none;">
                                Cancel
                            </a>
                            <div class="flex gap-3">
                                <button type="submit" name="draft" style="display: inline-block; padding: 12px 24px; background-color: #9CA3AF; color: white; font-weight: bold; border-radius: 8px; border: none; cursor: pointer;">
                                    Save as Draft
                                </button>
                                <button type="submit" name="submit" style="display: inline-block; padding: 12px 32px; background-color: #2563EB; color: white; font-weight: bold; border-radius: 8px; border: none; cursor: pointer; font-size: 16px;">
                                    ✓ Update Report
                                </button>
                            </div>
                        </div>

                    </form>

                </div>
            </div>
        </div>
    </div>

    <script>
        let projectCount = {{ count($report->projects) }};
        let skillsMastered = @json($report->skills_mastered);
        let skillsNew = @json($report->skills_new ?? []);

        document.addEventListener('DOMContentLoaded', function() {
            updateSkillsList('mastered');
            updateSkillsList('new');
        });

        function addCourse() {
            const container = document.getElementById('coursesContainer');
            const div = document.createElement('div');
            div.className = 'flex gap-2';
            div.innerHTML = `
                <input type="text" name="courses[]" placeholder="Enter course name" required class="flex-1 rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                <button type="button" onclick="this.parentElement.remove()" style="padding: 10px 20px; background-color: #DC2626; color: white; font-weight: bold; border-radius: 8px; border: none; cursor: pointer;">
                    Remove
                </button>
            `;
            container.appendChild(div);
        }

        function addProject() {
            projectCount++;
            const container = document.getElementById('projectsContainer');
            const div = document.createElement('div');
            div.className = 'project-item bg-white p-4 rounded-lg border';
            div.innerHTML = `
                <div class="flex justify-between items-center mb-2">
                    <div class="font-semibold">Project ${projectCount}</div>
                    <button type="button" onclick="this.closest('.project-item').remove()" style="padding: 6px 12px; background-color: #DC2626; color: white; font-weight: bold; border-radius: 8px; border: none; cursor: pointer; font-size: 12px;">
                        Remove
                    </button>
                </div>
                <input type="text" name="projects[${projectCount-1}][title]" placeholder="Project title" required class="w-full mb-2 rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                <input type="text" name="projects[${projectCount-1}][link]" placeholder="Project link or details" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
            `;
            container.appendChild(div);
        }

        function addSkillMastered() {
            const input = document.getElementById('masteredSkillInput');
            const skill = input.value.trim();
            if (skill) {
                skillsMastered.push(skill);
                updateSkillsList('mastered');
                input.value = '';
            }
        }

        function addSkillNew() {
            const input = document.getElementById('newSkillInput');
            const skill = input.value.trim();
            if (skill) {
                skillsNew.push(skill);
                updateSkillsList('new');
                input.value = '';
            }
        }

        function removeSkill(type, index) {
            if (type === 'mastered') {
                skillsMastered.splice(index, 1);
                updateSkillsList('mastered');
            } else {
                skillsNew.splice(index, 1);
                updateSkillsList('new');
            }
        }

        function updateSkillsList(type) {
            const skills = type === 'mastered' ? skillsMastered : skillsNew;
            const listId = type === 'mastered' ? 'masteredSkillsList' : 'newSkillsList';
            const hiddenId = type === 'mastered' ? 'skills_mastered_hidden' : 'skills_new_hidden';
            
            const list = document.getElementById(listId);
            list.innerHTML = '';
            
            skills.forEach((skill, index) => {
                const span = document.createElement('span');
                span.className = 'inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800';
                span.innerHTML = `
                    ${skill}
                    <button type="button" onclick="removeSkill('${type}', ${index})" class="ml-2 text-blue-600 hover:text-blue-800">×</button>
                `;
                list.appendChild(span);
            });
            
            document.getElementById(hiddenId).value = JSON.stringify(skills);
        }

        document.getElementById('masteredSkillInput').addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                addSkillMastered();
            }
        });

        document.getElementById('newSkillInput').addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                addSkillNew();
            }
        });
    </script>
</x-app-layout>

