<x-tutor-layout title="Create Report">
<div class="max-w-5xl mx-auto" x-data="reportForm()">
    <!-- Page Header -->
    <div class="mb-6">
        <div class="flex items-center gap-2 text-sm text-slate-500 dark:text-slate-400 mb-2">
            <a href="{{ route('tutor.reports.index') }}" class="hover:text-[#7978E9]">Reports</a>
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
                <span class="w-8 h-8 bg-[#7978E9] text-white rounded-lg flex items-center justify-center text-sm font-bold">1</span>
                Basic Information
            </h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <!-- Student -->
                <div>
                    <label for="student_id" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Student <span class="text-rose-500">*</span></label>
                    <select id="student_id" name="student_id" required class="w-full px-4 py-2.5 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-600 rounded-xl focus:ring-2 focus:ring-[#7978E9]">
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
                    <select id="month" name="month" required class="w-full px-4 py-2.5 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-600 rounded-xl focus:ring-2 focus:ring-[#7978E9]">
                        @foreach(['January','February','March','April','May','June','July','August','September','October','November','December'] as $m)
                            <option value="{{ $m }}" {{ old('month', $importData['month'] ?? now()->format('F')) == $m ? 'selected' : '' }}>{{ $m }}</option>
                        @endforeach
                    </select>
                </div>
                <!-- Year -->
                <div>
                    <label for="year" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Year <span class="text-rose-500">*</span></label>
                    <select id="year" name="year" required class="w-full px-4 py-2.5 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-600 rounded-xl focus:ring-2 focus:ring-[#7978E9]">
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
                <span class="w-8 h-8 bg-[#7978E9] text-white rounded-lg flex items-center justify-center text-sm font-bold">2</span>
                Courses Taught This Month
            </h2>
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-3">
                @foreach($courses as $course)
                    <label class="flex items-center gap-2 p-3 bg-slate-50 dark:bg-slate-800/50 rounded-xl cursor-pointer hover:bg-slate-100 dark:hover:bg-slate-700/50 transition-colors">
                        <input type="checkbox" name="courses[]" value="{{ $course }}" 
                            {{ in_array($course, old('courses', $importData['courses'] ?? [])) ? 'checked' : '' }}
                            x-model="selectedCourses"
                            class="w-4 h-4 text-[#7978E9] border-slate-300 rounded focus:ring-[#7978E9]">
                        <span class="text-sm text-slate-700 dark:text-slate-300">{{ $course }}</span>
                    </label>
                @endforeach
            </div>
        </div>

        <!-- Section 3: Skills Mastered -->
        <div class="glass-card rounded-2xl p-6 mb-6">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-lg font-semibold text-slate-900 dark:text-white flex items-center gap-2">
                    <span class="w-8 h-8 bg-[#7978E9] text-white rounded-lg flex items-center justify-center text-sm font-bold">3</span>
                    Skills Mastered
                </h2>
                <button type="button" @click="openSkillsModal('mastered')" class="inline-flex items-center gap-2 px-4 py-2 bg-[#7978E9] text-white text-sm font-medium rounded-xl hover:bg-[#3a40cc] transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    Select Skills
                </button>
            </div>

            <!-- Selected Skills Display -->
            <div class="min-h-[100px] border-2 border-dashed border-slate-200 dark:border-slate-700 rounded-xl p-4">
                <template x-if="selectedSkills.length === 0">
                    <p class="text-slate-400 text-center py-4">No skills selected. Click "Select Skills" to choose from available skills.</p>
                </template>
                <div class="flex flex-wrap gap-2">
                    <template x-for="(skill, index) in selectedSkills" :key="'mastered-'+index">
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
        </div>

        <!-- Section 3b: New Skills -->
        <div class="glass-card rounded-2xl p-6 mb-6">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-lg font-semibold text-slate-900 dark:text-white flex items-center gap-2">
                    <span class="w-8 h-8 bg-cyan-500 text-white rounded-lg flex items-center justify-center text-sm font-bold">+</span>
                    New Skills
                </h2>
                <button type="button" @click="openSkillsModal('new')" class="inline-flex items-center gap-2 px-4 py-2 bg-cyan-500 text-white text-sm font-medium rounded-xl hover:bg-cyan-600 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    Select New Skills
                </button>
            </div>
            <p class="text-sm text-slate-500 dark:text-slate-400 mb-4">Skills the student learned this month (not previously mastered)</p>

            <!-- Selected New Skills Display -->
            <div class="min-h-[80px] border-2 border-dashed border-slate-200 dark:border-slate-700 rounded-xl p-4">
                <template x-if="newSkills.length === 0">
                    <p class="text-slate-400 text-center py-2">No new skills selected.</p>
                </template>
                <div class="flex flex-wrap gap-2">
                    <template x-for="(skill, index) in newSkills" :key="'new-'+index">
                        <span class="inline-flex items-center gap-1 px-3 py-1.5 bg-cyan-100 dark:bg-cyan-900/30 text-cyan-700 dark:text-cyan-400 rounded-full text-sm">
                            <span x-text="skill"></span>
                            <button type="button" @click="removeNewSkill(index)" class="hover:text-cyan-900 dark:hover:text-cyan-200">
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
                    <span class="w-8 h-8 bg-[#7978E9] text-white rounded-lg flex items-center justify-center text-sm font-bold">4</span>
                    Projects Completed
                </h2>
                <button type="button" @click="addProject()" class="inline-flex items-center gap-2 px-4 py-2 bg-[#7978E9] text-white text-sm font-medium rounded-xl hover:bg-[#3a40cc] transition-colors">
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
                            <input type="text" x-model="project.title" :name="'projects['+index+'][title]'" placeholder="Project Title" class="px-4 py-2.5 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-600 rounded-xl focus:ring-2 focus:ring-[#7978E9]">
                            <input type="text" x-model="project.link" :name="'projects['+index+'][link]'" placeholder="Project Link or Description (optional)" class="px-4 py-2.5 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-600 rounded-xl focus:ring-2 focus:ring-[#7978E9]">
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
                <span class="w-8 h-8 bg-[#7978E9] text-white rounded-lg flex items-center justify-center text-sm font-bold">5</span>
                Assessment & Goals
            </h2>
            
            <!-- Areas for Improvement -->
            <div class="mb-5">
                <label for="areas_for_improvement" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                    Areas for Improvement <span class="text-rose-500">*</span>
                </label>
                <textarea id="areas_for_improvement" name="areas_for_improvement" rows="3" required class="w-full px-4 py-3 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-600 rounded-xl focus:ring-2 focus:ring-[#7978E9]" placeholder="What areas need more focus or practice?">{{ old('areas_for_improvement', $importData['areas_for_improvement'] ?? '') }}</textarea>
            </div>

            <!-- Goals for Next Month -->
            <div class="mb-5">
                <label for="goals_next_month" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                    Goals for Next Month <span class="text-rose-500">*</span>
                </label>
                <textarea id="goals_next_month" name="goals_next_month" rows="3" required class="w-full px-4 py-3 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-600 rounded-xl focus:ring-2 focus:ring-[#7978E9]" placeholder="What should the student focus on next month?">{{ old('goals_next_month', $importData['goals_next_month'] ?? '') }}</textarea>
            </div>

            <!-- Assignments -->
            <div class="mb-5">
                <label for="assignments" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Assignments Given</label>
                <textarea id="assignments" name="assignments" rows="2" class="w-full px-4 py-3 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-600 rounded-xl focus:ring-2 focus:ring-[#7978E9]" placeholder="Any homework or assignments given?">{{ old('assignments', $importData['assignments'] ?? '') }}</textarea>
            </div>

            <!-- Comments/Observation -->
            <div>
                <label for="comments_observation" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                    Comments & Observations <span class="text-rose-500">*</span>
                </label>
                <textarea id="comments_observation" name="comments_observation" rows="4" required class="w-full px-4 py-3 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-600 rounded-xl focus:ring-2 focus:ring-[#7978E9]" placeholder="General observations about the student's learning, behavior, engagement...">{{ old('comments_observation', $importData['comments_observation'] ?? '') }}</textarea>
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
            <button type="submit" name="status" value="submitted" onclick="return confirm('Submit this report for review?')" class="px-6 py-2.5 bg-gradient-to-r from-[#4B49AC] to-[#7978E9] text-white font-semibold rounded-xl hover:opacity-90 transition-all shadow-lg">
                Submit for Review
            </button>
        </div>
    </form>

    <!-- Unified Skills Modal -->
    <div x-show="showSkillsModal" x-cloak class="fixed inset-0 z-50 overflow-y-auto" x-transition:enter="ease-out duration-300" x-transition:leave="ease-in duration-200">
        <div class="flex items-center justify-center min-h-screen px-4">
            <div class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm" @click="closeSkillsModal()"></div>
            <div class="relative bg-white dark:bg-slate-800 rounded-2xl shadow-xl max-w-4xl w-full max-h-[85vh] overflow-hidden">
                <!-- Modal Header -->
                <div class="sticky top-0 bg-white dark:bg-slate-800 border-b border-slate-200 dark:border-slate-700 px-6 py-4 flex items-center justify-between">
                    <div>
                        <h3 class="text-lg font-semibold text-slate-900 dark:text-white" x-text="skillModalType === 'mastered' ? 'Select Skills Mastered' : 'Select New Skills'"></h3>
                        <p class="text-sm text-slate-500" x-text="skillModalType === 'mastered' ? 'Choose skills the student has already mastered' : 'Choose new skills the student learned this month'"></p>
                    </div>
                    <button @click="closeSkillsModal()" class="p-2 text-slate-400 hover:text-slate-600 rounded-lg">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>

                <!-- Search -->
                <div class="px-6 py-3 border-b border-slate-200 dark:border-slate-700">
                    <input type="text" x-model="skillSearch" placeholder="Search skills..." class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-700 border border-slate-200 dark:border-slate-600 rounded-xl focus:ring-2 focus:ring-[#7978E9]">
                </div>

                <!-- Skills by Course (filtered by selected courses) -->
                <div class="overflow-y-auto p-6" style="max-height: calc(85vh - 280px);">
                    <!-- Notice when no courses selected -->
                    <template x-if="selectedCourses.length === 0">
                        <div class="text-center py-8">
                            <svg class="w-12 h-12 mx-auto text-slate-300 dark:text-slate-600 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                            </svg>
                            <p class="text-slate-500 dark:text-slate-400 font-medium">No courses selected</p>
                            <p class="text-sm text-slate-400 dark:text-slate-500 mt-1">Please select courses first to see available skills</p>
                        </div>
                    </template>

                    @foreach($skillsDatabase as $course => $skills)
                        <div class="mb-6" x-show="selectedCourses.includes('{{ $course }}')">
                            <h4 class="font-semibold text-slate-900 dark:text-white mb-3 flex items-center gap-2">
                                <span class="w-2 h-2 rounded-full" :class="skillModalType === 'mastered' ? 'bg-[#7978E9]' : 'bg-cyan-500'"></span>
                                {{ $course }}
                            </h4>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-2">
                                @foreach($skills as $skill)
                                    <label
                                        class="flex items-center gap-2 p-2 rounded-lg hover:bg-slate-50 dark:hover:bg-slate-700/50 cursor-pointer transition-colors"
                                        x-show="isSkillVisible('{{ addslashes($skill) }}')"
                                        :class="{ 'opacity-50 cursor-not-allowed': isSkillDisabled('{{ addslashes($skill) }}') }"
                                    >
                                        <input
                                            type="checkbox"
                                            value="{{ $skill }}"
                                            :checked="isSkillChecked('{{ addslashes($skill) }}')"
                                            @change="toggleModalSkill('{{ addslashes($skill) }}')"
                                            :disabled="isSkillDisabled('{{ addslashes($skill) }}')"
                                            class="w-4 h-4 border-slate-300 rounded focus:ring-[#7978E9]"
                                            :class="skillModalType === 'mastered' ? 'text-[#7978E9]' : 'text-cyan-500'"
                                        >
                                        <span class="text-sm text-slate-700 dark:text-slate-300">{{ $skill }}</span>
                                        <span x-show="isSkillDisabled('{{ addslashes($skill) }}')" class="text-xs text-slate-400 ml-auto">(already selected)</span>
                                    </label>
                                @endforeach
                            </div>
                        </div>
                    @endforeach

                    <!-- Add New Skill Section -->
                    <div class="mt-6 pt-6 border-t border-slate-200 dark:border-slate-700">
                        <h4 class="font-semibold text-slate-900 dark:text-white mb-3 flex items-center gap-2">
                            <svg class="w-5 h-5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                            </svg>
                            Add a New Skill
                        </h4>
                        <p class="text-sm text-slate-500 dark:text-slate-400 mb-3">Can't find the skill? Add a new one below. It will be saved for future use.</p>
                        <div class="flex gap-2">
                            <select x-model="newSkillCourse" class="px-4 py-2.5 bg-white dark:bg-slate-700 border border-slate-200 dark:border-slate-600 rounded-xl focus:ring-2 focus:ring-[#7978E9]">
                                <option value="">Select Course (Optional)</option>
                                @foreach($courses as $course)
                                    <option value="{{ $course }}">{{ $course }}</option>
                                @endforeach
                            </select>
                            <input
                                type="text"
                                x-model="customSkillInput"
                                @keydown.enter.prevent="addCustomSkill()"
                                placeholder="Type new skill name..."
                                class="flex-1 px-4 py-2.5 bg-white dark:bg-slate-700 border border-slate-200 dark:border-slate-600 rounded-xl focus:ring-2 focus:ring-[#7978E9]"
                            >
                            <button
                                type="button"
                                @click="addCustomSkill()"
                                :disabled="!customSkillInput.trim() || savingSkill"
                                class="px-4 py-2.5 bg-emerald-500 text-white rounded-xl hover:bg-emerald-600 transition-colors disabled:opacity-50 disabled:cursor-not-allowed"
                            >
                                <span x-show="!savingSkill">Add & Select</span>
                                <span x-show="savingSkill">Saving...</span>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Modal Footer -->
                <div class="sticky bottom-0 bg-white dark:bg-slate-800 border-t border-slate-200 dark:border-slate-700 px-6 py-4 flex items-center justify-between">
                    <span class="text-sm text-slate-500" x-text="getModalSkillCount() + ' skills selected'"></span>
                    <button @click="closeSkillsModal()" class="px-6 py-2.5 text-white font-semibold rounded-xl transition-colors" :class="skillModalType === 'mastered' ? 'bg-[#7978E9] hover:bg-[#3a40cc]' : 'bg-cyan-500 hover:bg-cyan-600'">Done</button>
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
        skillModalType: 'mastered', // 'mastered' or 'new'
        skillSearch: '',
        selectedCourses: @json(old('courses', $importData['courses'] ?? [])),
        selectedSkills: @json(old('skills_mastered', $importData['skills_mastered'] ?? [])),
        newSkills: @json(old('new_skills', $importData['new_skills'] ?? [])),
        projects: @json(old('projects', $importData['projects'] ?? [])),
        customSkillInput: '',
        newSkillCourse: '',
        savingSkill: false,

        openSkillsModal(type) {
            this.skillModalType = type;
            this.skillSearch = '';
            this.customSkillInput = '';
            this.newSkillCourse = '';
            this.showSkillsModal = true;
        },

        closeSkillsModal() {
            this.showSkillsModal = false;
        },

        isSkillVisible(skill) {
            if (!this.skillSearch) return true;
            return skill.toLowerCase().includes(this.skillSearch.toLowerCase());
        },

        isSkillDisabled(skill) {
            // In 'new' modal, disable skills already in selectedSkills
            // In 'mastered' modal, disable skills already in newSkills
            if (this.skillModalType === 'new') {
                return this.selectedSkills.includes(skill);
            } else {
                return this.newSkills.includes(skill);
            }
        },

        isSkillChecked(skill) {
            if (this.skillModalType === 'mastered') {
                return this.selectedSkills.includes(skill);
            } else {
                return this.newSkills.includes(skill);
            }
        },

        toggleModalSkill(skill) {
            if (this.isSkillDisabled(skill)) return;

            const targetArray = this.skillModalType === 'mastered' ? this.selectedSkills : this.newSkills;
            const index = targetArray.indexOf(skill);

            if (index === -1) {
                targetArray.push(skill);
            } else {
                targetArray.splice(index, 1);
            }
        },

        removeSkill(index) {
            this.selectedSkills.splice(index, 1);
        },

        removeNewSkill(index) {
            this.newSkills.splice(index, 1);
        },

        getModalSkillCount() {
            return this.skillModalType === 'mastered' ? this.selectedSkills.length : this.newSkills.length;
        },

        async addCustomSkill() {
            const skillName = this.customSkillInput.trim();
            if (!skillName) return;

            // Check if already exists
            if (this.selectedSkills.includes(skillName) || this.newSkills.includes(skillName)) {
                alert('This skill is already selected.');
                return;
            }

            this.savingSkill = true;

            try {
                // Save to database
                const response = await fetch('{{ route("tutor.reports.custom-skill") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        name: skillName,
                        course: this.newSkillCourse || null
                    })
                });

                const data = await response.json();

                if (data.success) {
                    // Add to appropriate array
                    if (this.skillModalType === 'mastered') {
                        this.selectedSkills.push(skillName);
                    } else {
                        this.newSkills.push(skillName);
                    }
                    this.customSkillInput = '';
                    this.newSkillCourse = '';
                }
            } catch (error) {
                console.error('Error saving skill:', error);
                // Still add to array even if save fails (will work for this session)
                if (this.skillModalType === 'mastered') {
                    this.selectedSkills.push(skillName);
                } else {
                    this.newSkills.push(skillName);
                }
                this.customSkillInput = '';
            }

            this.savingSkill = false;
        },

        addProject() {
            this.projects.push({ title: '', link: '' });
        },

        getFilteredSkillsCount(course, skills) {
            if (!this.skillSearch) return skills.length;
            return skills.filter(skill => skill.toLowerCase().includes(this.skillSearch.toLowerCase())).length;
        }
    }
}
</script>
@endpush
</x-tutor-layout>
