<x-tutor-layout title="Create Report">
<div class="max-w-5xl mx-auto" x-data="reportForm()">
    <!-- Page Header -->
    <div class="mb-6">
        <div class="flex items-center gap-2 text-sm text-slate-500 dark:text-slate-400 mb-2">
            <a href="{{ route('tutor.reports.index') }}" class="hover:text-[#4B51FF]">Reports</a>
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            <span>Create</span>
        </div>
        <h1 class="text-3xl font-bold text-slate-900 dark:text-white">Create Student Report</h1>
        @if($importData)
            <div class="mt-3 inline-flex items-center gap-2 px-3 py-1.5 bg-purple-100 dark:bg-purple-900/30 text-purple-700 dark:text-purple-400 rounded-lg text-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/></svg>
                Imported from Claude Artifact
            </div>
        @endif
    </div>

    <form action="{{ route('tutor.reports.store') }}" method="POST" id="reportForm">
        @csrf
        @if($importData)
            <input type="hidden" name="imported_from_artifact" value="1">
        @endif

        <!-- Section 1: Basic Info -->
        <div class="glass-card rounded-2xl p-6 mb-6">
            <h2 class="text-lg font-semibold text-slate-900 dark:text-white mb-4 flex items-center gap-2">
                <span class="w-8 h-8 bg-[#4B51FF] text-white rounded-lg flex items-center justify-center text-sm font-bold">1</span>
                Basic Information
            </h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <!-- Student -->
                <div>
                    <label for="student_id" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Student <span class="text-rose-500">*</span></label>
                    <select id="student_id" name="student_id" required class="w-full px-4 py-2.5 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-600 rounded-xl focus:ring-2 focus:ring-[#4B51FF]">
                        <option value="">Select student</option>
                        @foreach($students as $student)
                            <option value="{{ $student->id }}" {{ old('student_id', $importData['student_id'] ?? request('student_id')) == $student->id ? 'selected' : '' }}>
                                {{ $student->first_name }} {{ $student->last_name }}
                            </option>
                        @endforeach
                    </select>
                    @error('student_id')<p class="mt-1 text-sm text-rose-600">{{ $message }}</p>@enderror
                </div>
                <!-- Month -->
                <div>
                    <label for="month" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Month <span class="text-rose-500">*</span></label>
                    <select id="month" name="month" required class="w-full px-4 py-2.5 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-600 rounded-xl focus:ring-2 focus:ring-[#4B51FF]">
                        @foreach(['January','February','March','April','May','June','July','August','September','October','November','December'] as $m)
                            <option value="{{ $m }}" {{ old('month', $importData['month'] ?? now()->format('F')) == $m ? 'selected' : '' }}>{{ $m }}</option>
                        @endforeach
                    </select>
                </div>
                <!-- Year -->
                <div>
                    <label for="year" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Year <span class="text-rose-500">*</span></label>
                    <select id="year" name="year" required class="w-full px-4 py-2.5 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-600 rounded-xl focus:ring-2 focus:ring-[#4B51FF]">
                        @for($y = now()->year; $y >= now()->year - 2; $y--)
                            <option value="{{ $y }}" {{ old('year', $importData['year'] ?? now()->year) == $y ? 'selected' : '' }}>{{ $y }}</option>
                        @endfor
                    </select>
                </div>
            </div>
        </div>

        <!-- Section 2: Courses -->
        <div class="glass-card rounded-2xl p-6 mb-6">
            <h2 class="text-lg font-semibold text-slate-900 dark:text-white mb-4 flex items-center gap-2">
                <span class="w-8 h-8 bg-[#4B51FF] text-white rounded-lg flex items-center justify-center text-sm font-bold">2</span>
                Courses Taught This Month
            </h2>
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-3">
                @foreach($courses as $course)
                    <label class="flex items-center gap-2 p-3 bg-slate-50 dark:bg-slate-800/50 rounded-xl cursor-pointer hover:bg-slate-100 dark:hover:bg-slate-700/50 transition-colors">
                        <input type="checkbox" name="courses[]" value="{{ $course }}" 
                            {{ in_array($course, old('courses', $importData['courses'] ?? [])) ? 'checked' : '' }}
                            x-model="selectedCourses"
                            class="w-4 h-4 text-[#4B51FF] border-slate-300 rounded focus:ring-[#4B51FF]">
                        <span class="text-sm text-slate-700 dark:text-slate-300">{{ $course }}</span>
                    </label>
                @endforeach
            </div>
        </div>

        <!-- Section 3: Skills -->
        <div class="glass-card rounded-2xl p-6 mb-6">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-lg font-semibold text-slate-900 dark:text-white flex items-center gap-2">
                    <span class="w-8 h-8 bg-[#4B51FF] text-white rounded-lg flex items-center justify-center text-sm font-bold">3</span>
                    Skills Mastered
                </h2>
                <button type="button" @click="showSkillsModal = true" class="inline-flex items-center gap-2 px-4 py-2 bg-[#4B51FF] text-white text-sm font-medium rounded-xl hover:bg-[#3a40cc] transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    Add Skills
                </button>
            </div>
            
            <!-- Selected Skills Display -->
            <div class="min-h-[100px] border-2 border-dashed border-slate-200 dark:border-slate-700 rounded-xl p-4">
                <template x-if="selectedSkills.length === 0">
                    <p class="text-slate-400 text-center py-4">No skills selected. Click "Add Skills" to select from the database.</p>
                </template>
                <div class="flex flex-wrap gap-2">
                    <template x-for="(skill, index) in selectedSkills" :key="index">
                        <span class="inline-flex items-center gap-1 px-3 py-1.5 bg-emerald-100 dark:bg-emerald-900/30 text-emerald-700 dark:text-emerald-400 rounded-full text-sm">
                            <span x-text="skill"></span>
                            <button type="button" @click="removeSkill(index)" class="hover:text-emerald-900 dark:hover:text-emerald-200">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                            </button>
                            <input type="hidden" name="skills_mastered[]" :value="skill">
                        </span>
                    </template>
                </div>
            </div>

            <!-- New Skills (Custom) -->
            <div class="mt-4">
                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">New Skills (not in database)</label>
                <div class="flex gap-2">
                    <input type="text" x-model="newSkillInput" @keydown.enter.prevent="addNewSkill()" placeholder="Type a new skill and press Enter" class="flex-1 px-4 py-2.5 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-600 rounded-xl focus:ring-2 focus:ring-[#4B51FF]">
                    <button type="button" @click="addNewSkill()" class="px-4 py-2.5 bg-slate-100 dark:bg-slate-700 text-slate-700 dark:text-slate-300 rounded-xl hover:bg-slate-200 dark:hover:bg-slate-600">Add</button>
                </div>
                <div class="flex flex-wrap gap-2 mt-2">
                    <template x-for="(skill, index) in newSkills" :key="'new-'+index">
                        <span class="inline-flex items-center gap-1 px-3 py-1.5 bg-cyan-100 dark:bg-cyan-900/30 text-cyan-700 dark:text-cyan-400 rounded-full text-sm">
                            <span x-text="skill"></span>
                            <button type="button" @click="newSkills.splice(index, 1)" class="hover:text-cyan-900">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                            </button>
                            <input type="hidden" name="new_skills[]" :value="skill">
                        </span>
                    </template>
                </div>
            </div>
        </div>

        <!-- Section 4: Projects -->
        <div class="glass-card rounded-2xl p-6 mb-6">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-lg font-semibold text-slate-900 dark:text-white flex items-center gap-2">
                    <span class="w-8 h-8 bg-[#4B51FF] text-white rounded-lg flex items-center justify-center text-sm font-bold">4</span>
                    Projects Completed
                </h2>
                <button type="button" @click="addProject()" class="inline-flex items-center gap-2 px-4 py-2 bg-[#4B51FF] text-white text-sm font-medium rounded-xl hover:bg-[#3a40cc] transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    Add Project
                </button>
            </div>
            
            <div class="space-y-3">
                <template x-if="projects.length === 0">
                    <p class="text-slate-400 text-center py-4 border-2 border-dashed border-slate-200 dark:border-slate-700 rounded-xl">No projects added yet.</p>
                </template>
                <template x-for="(project, index) in projects" :key="index">
                    <div class="flex items-start gap-3 p-4 bg-slate-50 dark:bg-slate-800/50 rounded-xl">
                        <div class="flex-1 grid grid-cols-1 md:grid-cols-2 gap-3">
                            <input type="text" x-model="project.title" :name="'projects['+index+'][title]'" placeholder="Project Title" class="px-4 py-2.5 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-600 rounded-xl focus:ring-2 focus:ring-[#4B51FF]">
                            <input type="url" x-model="project.link" :name="'projects['+index+'][link]'" placeholder="Project Link (optional)" class="px-4 py-2.5 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-600 rounded-xl focus:ring-2 focus:ring-[#4B51FF]">
                        </div>
                        <button type="button" @click="projects.splice(index, 1)" class="p-2 text-slate-400 hover:text-rose-600 hover:bg-rose-50 dark:hover:bg-rose-900/20 rounded-lg transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                        </button>
                    </div>
                </template>
            </div>
        </div>

        <!-- Section 5: Written Sections -->
        <div class="glass-card rounded-2xl p-6 mb-6">
            <h2 class="text-lg font-semibold text-slate-900 dark:text-white mb-4 flex items-center gap-2">
                <span class="w-8 h-8 bg-[#4B51FF] text-white rounded-lg flex items-center justify-center text-sm font-bold">5</span>
                Assessment & Goals
            </h2>
            
            <!-- Areas for Improvement -->
            <div class="mb-5">
                <label for="areas_for_improvement" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                    Areas for Improvement <span class="text-rose-500">*</span>
                </label>
                <textarea id="areas_for_improvement" name="areas_for_improvement" rows="3" required class="w-full px-4 py-3 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-600 rounded-xl focus:ring-2 focus:ring-[#4B51FF]" placeholder="What areas need more focus or practice?">{{ old('areas_for_improvement', $importData['areas_for_improvement'] ?? '') }}</textarea>
            </div>

            <!-- Goals for Next Month -->
            <div class="mb-5">
                <label for="goals_next_month" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                    Goals for Next Month <span class="text-rose-500">*</span>
                </label>
                <textarea id="goals_next_month" name="goals_next_month" rows="3" required class="w-full px-4 py-3 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-600 rounded-xl focus:ring-2 focus:ring-[#4B51FF]" placeholder="What should the student focus on next month?">{{ old('goals_next_month', $importData['goals_next_month'] ?? '') }}</textarea>
            </div>

            <!-- Assignments -->
            <div class="mb-5">
                <label for="assignments" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Assignments Given</label>
                <textarea id="assignments" name="assignments" rows="2" class="w-full px-4 py-3 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-600 rounded-xl focus:ring-2 focus:ring-[#4B51FF]" placeholder="Any homework or assignments given?">{{ old('assignments', $importData['assignments'] ?? '') }}</textarea>
            </div>

            <!-- Comments/Observation -->
            <div>
                <label for="comments_observation" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                    Comments & Observations <span class="text-rose-500">*</span>
                </label>
                <textarea id="comments_observation" name="comments_observation" rows="4" required class="w-full px-4 py-3 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-600 rounded-xl focus:ring-2 focus:ring-[#4B51FF]" placeholder="General observations about the student's learning, behavior, engagement...">{{ old('comments_observation', $importData['comments_observation'] ?? '') }}</textarea>
            </div>
        </div>

        <!-- Info Box -->
        <div class="mb-6 p-4 bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-xl">
            <div class="flex items-start gap-3">
                <svg class="w-5 h-5 text-blue-600 dark:text-blue-400 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <p class="text-sm text-blue-700 dark:text-blue-300">
                    <strong>Save as draft</strong> to continue editing later, or <strong>Submit</strong> for review. Once submitted, reports can only be edited if returned.
                </p>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="flex items-center justify-end gap-3">
            <a href="{{ route('tutor.reports.index') }}" class="px-5 py-2.5 bg-slate-100 dark:bg-slate-700 text-slate-700 dark:text-slate-300 font-medium rounded-xl hover:bg-slate-200 dark:hover:bg-slate-600 transition-colors">Cancel</a>
            <button type="submit" name="status" value="draft" class="px-5 py-2.5 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-600 text-slate-700 dark:text-slate-300 font-medium rounded-xl hover:bg-slate-50 dark:hover:bg-slate-700 transition-colors">
                Save as Draft
            </button>
            <button type="submit" name="status" value="submitted" onclick="return confirm('Submit this report for review?')" class="px-6 py-2.5 bg-gradient-to-r from-[#1D2A6D] to-[#4B51FF] text-white font-semibold rounded-xl hover:opacity-90 transition-all shadow-lg">
                Submit for Review
            </button>
        </div>
    </form>

    <!-- Skills Modal -->
    <div x-show="showSkillsModal" x-cloak class="fixed inset-0 z-50 overflow-y-auto" x-transition:enter="ease-out duration-300" x-transition:leave="ease-in duration-200">
        <div class="flex items-center justify-center min-h-screen px-4">
            <div class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm" @click="showSkillsModal = false"></div>
            <div class="relative bg-white dark:bg-slate-800 rounded-2xl shadow-xl max-w-4xl w-full max-h-[85vh] overflow-hidden">
                <!-- Modal Header -->
                <div class="sticky top-0 bg-white dark:bg-slate-800 border-b border-slate-200 dark:border-slate-700 px-6 py-4 flex items-center justify-between">
                    <div>
                        <h3 class="text-lg font-semibold text-slate-900 dark:text-white">Select Skills</h3>
                        <p class="text-sm text-slate-500">Choose skills the student has mastered</p>
                    </div>
                    <button @click="showSkillsModal = false" class="p-2 text-slate-400 hover:text-slate-600 rounded-lg">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>

                <!-- Search -->
                <div class="px-6 py-3 border-b border-slate-200 dark:border-slate-700">
                    <input type="text" x-model="skillSearch" placeholder="Search skills..." class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-700 border border-slate-200 dark:border-slate-600 rounded-xl focus:ring-2 focus:ring-[#4B51FF]">
                </div>

                <!-- Skills by Course -->
                <div class="overflow-y-auto p-6" style="max-height: calc(85vh - 200px);">
                    @foreach($skillsDatabase as $course => $skills)
                        <div class="mb-6" x-show="filterSkillsByCourse('{{ $course }}').length > 0 || skillSearch === ''">
                            <h4 class="font-semibold text-slate-900 dark:text-white mb-3 flex items-center gap-2">
                                <span class="w-2 h-2 bg-[#4B51FF] rounded-full"></span>
                                {{ $course }}
                            </h4>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-2">
                                @foreach($skills as $skill)
                                    <label class="flex items-center gap-2 p-2 rounded-lg hover:bg-slate-50 dark:hover:bg-slate-700/50 cursor-pointer transition-colors" x-show="'{{ strtolower($skill) }}'.includes(skillSearch.toLowerCase()) || skillSearch === ''">
                                        <input type="checkbox" value="{{ $skill }}" :checked="selectedSkills.includes('{{ $skill }}')" @change="toggleSkill('{{ $skill }}')" class="w-4 h-4 text-[#4B51FF] border-slate-300 rounded focus:ring-[#4B51FF]">
                                        <span class="text-sm text-slate-700 dark:text-slate-300">{{ $skill }}</span>
                                    </label>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Modal Footer -->
                <div class="sticky bottom-0 bg-white dark:bg-slate-800 border-t border-slate-200 dark:border-slate-700 px-6 py-4 flex items-center justify-between">
                    <span class="text-sm text-slate-500" x-text="selectedSkills.length + ' skills selected'"></span>
                    <button @click="showSkillsModal = false" class="px-6 py-2.5 bg-[#4B51FF] text-white font-semibold rounded-xl hover:bg-[#3a40cc] transition-colors">Done</button>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function reportForm() {
    return {
        showSkillsModal: false,
        skillSearch: '',
        selectedCourses: @json(old('courses', $importData['courses'] ?? [])),
        selectedSkills: @json(old('skills_mastered', $importData['skills_mastered'] ?? [])),
        newSkills: @json(old('new_skills', $importData['new_skills'] ?? [])),
        newSkillInput: '',
        projects: @json(old('projects', $importData['projects'] ?? [])),
        
        toggleSkill(skill) {
            const index = this.selectedSkills.indexOf(skill);
            if (index === -1) {
                this.selectedSkills.push(skill);
            } else {
                this.selectedSkills.splice(index, 1);
            }
        },
        
        removeSkill(index) {
            this.selectedSkills.splice(index, 1);
        },
        
        addNewSkill() {
            const skill = this.newSkillInput.trim();
            if (skill && !this.newSkills.includes(skill) && !this.selectedSkills.includes(skill)) {
                this.newSkills.push(skill);
                this.newSkillInput = '';
            }
        },
        
        addProject() {
            this.projects.push({ title: '', link: '' });
        },
        
        filterSkillsByCourse(course) {
            // This is for Alpine.js visibility control
            return [];
        }
    }
}
</script>
@endpush
</x-tutor-layout>
