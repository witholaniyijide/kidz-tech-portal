<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Create Monthly Progress Report
            </h2>
            <a href="{{ route('reports.index') }}" style="display: inline-block; padding: 10px 20px; background-color: #6B7280; color: white; font-weight: bold; border-radius: 8px; text-decoration: none;">
                ← Back to Reports
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

                    <form method="POST" action="{{ route('reports.store') }}" id="reportForm">
                        @csrf

                        <div class="mb-6 pb-6 border-b">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">Basic Information</h3>
                            
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                
                                <div>
                                    <label for="student_id" class="block text-sm font-medium text-gray-700">Student *</label>
                                    <select name="student_id" id="student_id" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                        <option value="">Select Student</option>
                                        @foreach($students as $student)
                                            <option value="{{ $student->id }}" {{ old('student_id') == $student->id ? 'selected' : '' }}>
                                                {{ $student->full_name }} ({{ $student->student_id }})
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div>
                                    <label for="month" class="block text-sm font-medium text-gray-700">Month *</label>
                                    <select name="month" id="month" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                        <option value="">Select Month</option>
                                        <option value="January" {{ old('month') == 'January' ? 'selected' : '' }}>January</option>
                                        <option value="February" {{ old('month') == 'February' ? 'selected' : '' }}>February</option>
                                        <option value="March" {{ old('month') == 'March' ? 'selected' : '' }}>March</option>
                                        <option value="April" {{ old('month') == 'April' ? 'selected' : '' }}>April</option>
                                        <option value="May" {{ old('month') == 'May' ? 'selected' : '' }}>May</option>
                                        <option value="June" {{ old('month') == 'June' ? 'selected' : '' }}>June</option>
                                        <option value="July" {{ old('month') == 'July' ? 'selected' : '' }}>July</option>
                                        <option value="August" {{ old('month') == 'August' ? 'selected' : '' }}>August</option>
                                        <option value="September" {{ old('month') == 'September' ? 'selected' : '' }}>September</option>
                                        <option value="October" {{ old('month') == 'October' ? 'selected' : '' }}>October</option>
                                        <option value="November" {{ old('month') == 'November' ? 'selected' : '' }}>November</option>
                                        <option value="December" {{ old('month') == 'December' ? 'selected' : '' }}>December</option>
                                    </select>
                                </div>

                                <div>
                                    <label for="year" class="block text-sm font-medium text-gray-700">Year *</label>
                                    <select name="year" id="year" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                        <option value="">Select Year</option>
                                        <option value="2024" {{ old('year') == '2024' ? 'selected' : '' }}>2024</option>
                                        <option value="2025" {{ old('year', '2025') == '2025' ? 'selected' : '' }}>2025</option>
                                        <option value="2026" {{ old('year') == '2026' ? 'selected' : '' }}>2026</option>
                                    </select>
                                </div>

                            </div>
                        </div>

                        <div class="mb-6 pb-6 border-b">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">Course(s) Taught *</h3>
                            
                            <div id="coursesContainer" class="space-y-2">
                                <div class="flex gap-2">
                                    <input type="text" name="courses[]" placeholder="Enter course name" required class="flex-1 rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                    <button type="button" onclick="addCourse()" style="padding: 10px 20px; background-color: #059669; color: white; font-weight: bold; border-radius: 8px; border: none; cursor: pointer;">
                                        + Add
                                    </button>
                                </div>
                            </div>
                        </div>

                        <div class="mb-6 pb-6 border-b" style="background-color: #F0F9FF; padding: 20px; border-radius: 12px;">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">1. Progress Overview *</h3>
                            
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Skills Mastered * (Add at least one)</label>
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
                                <label class="block text-sm font-medium text-gray-700 mb-2">New Skills (Optional)</label>
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
                                <div class="project-item bg-white p-4 rounded-lg border">
                                    <div class="font-semibold mb-2">Project 1</div>
                                    <input type="text" name="projects[0][title]" placeholder="Project title" required class="w-full mb-2 rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                    <input type="text" name="projects[0][link]" placeholder="Project link or details" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                </div>
                            </div>
                            
                            <button type="button" onclick="addProject()" style="margin-top: 12px; padding: 8px 16px; background-color: #059669; color: white; font-weight: bold; border-radius: 8px; border: none; cursor: pointer;">
                                + Add Another Project
                            </button>
                        </div>

                        <div class="mb-6 pb-6 border-b" style="background-color: #FFF2F2; padding: 20px; border-radius: 12px;">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">3. Areas for Improvement *</h3>
                            <textarea name="improvement" id="improvement" rows="4" required placeholder="e.g., Proper coordination of the use of mouse, practical identification and application of coding concepts" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">{{ old('improvement') }}</textarea>
                        </div>

                        <div class="mb-6 pb-6 border-b" style="background-color: #FFFBEB; padding: 20px; border-radius: 12px;">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">4. Goals for Next Month *</h3>
                            <textarea name="goals" id="goals" rows="4" required placeholder="e.g., Our goal for the next month is - New Course: Scratch Programming, More Animation Projects using Scratch App" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">{{ old('goals') }}</textarea>
                        </div>

                        <div class="mb-6 pb-6 border-b" style="background-color: #F0F9FF; padding: 20px; border-radius: 12px;">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">5. Assignment/Projects during the month *</h3>
                            <textarea name="assignments" id="assignments" rows="4" required placeholder="List assignments given during the month. You can provide project/assignment names and note that links are in Google Classroom." class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">{{ old('assignments') }}</textarea>
                        </div>

                        <div class="mb-6" style="background-color: #ECFDF5; padding: 20px; border-radius: 12px;">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">6. Comments/Observation *</h3>
                            <textarea name="comments" id="comments" rows="4" required placeholder="Short personalized feedback, e.g., [Child's Name] is progressing well and showing enthusiasm!" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">{{ old('comments') }}</textarea>
                        </div>

                        <div class="flex items-center justify-between mt-8 pt-6 border-t border-gray-200">
                            <a href="{{ route('reports.index') }}" style="display: inline-block; padding: 12px 24px; background-color: #6B7280; color: white; font-weight: bold; border-radius: 8px; text-decoration: none;">
                                Cancel
                            </a>
                            <div class="flex gap-3">
                                <button type="submit" name="draft" style="display: inline-block; padding: 12px 24px; background-color: #9CA3AF; color: white; font-weight: bold; border-radius: 8px; border: none; cursor: pointer;">
                                    Save as Draft
                                </button>
                                <button type="submit" name="submit" style="display: inline-block; padding: 12px 32px; background-color: #2563EB; color: white; font-weight: bold; border-radius: 8px; border: none; cursor: pointer; font-size: 16px;">
                                    ✓ Submit Report
                                </button>
                            </div>
                        </div>

                    </form>

                </div>
            </div>
        </div>
    </div>

    <script>
        let projectCount = 1;
        let skillsMastered = [];
        let skillsNew = [];

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

