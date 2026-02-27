{{-- CACHE-CLEAR-REQUIRED: {{ now()->format('Y-m-d-H-i-s') }} --}}
<x-manager-layout title="Tutor Assessments">
    <div x-data="assessmentApp()" x-init="init()">
        {{-- Sub Navigation Tabs --}}
        <nav class="bg-white/70 dark:bg-gray-800/70 backdrop-blur-xl border border-white/20 dark:border-gray-700/50 rounded-2xl shadow-sm sticky top-0 z-10 mb-6">
            <div class="px-4">
                <div class="flex gap-1 overflow-x-auto">
                    <button @click="view = 'new'"
                            :class="view === 'new' ? 'text-[#C15F3C] border-b-3 border-[#C15F3C]' : 'text-gray-500 hover:text-[#C15F3C]'"
                            class="relative px-5 py-4 font-medium transition-all whitespace-nowrap">
                        New Assessment
                    </button>
                    <button @click="view = 'dashboard'"
                            :class="view === 'dashboard' ? 'text-[#C15F3C] border-b-3 border-[#C15F3C]' : 'text-gray-500 hover:text-[#C15F3C]'"
                            class="relative px-5 py-4 font-medium transition-all whitespace-nowrap">
                        Dashboard
                        <span x-show="stats.pending > 0" class="ml-2 px-2 py-0.5 text-xs bg-amber-500 text-white rounded-full" x-text="stats.pending"></span>
                    </button>
                    <button @click="view = 'management'"
                            :class="view === 'management' ? 'text-[#C15F3C] border-b-3 border-[#C15F3C]' : 'text-gray-500 hover:text-[#C15F3C]'"
                            class="relative px-5 py-4 font-medium transition-all whitespace-nowrap">
                        Management
                    </button>
                    <button @click="view = 'completed'"
                            :class="view === 'completed' ? 'text-[#C15F3C] border-b-3 border-[#C15F3C]' : 'text-gray-500 hover:text-[#C15F3C]'"
                            class="relative px-5 py-4 font-medium transition-all whitespace-nowrap">
                        Completed
                        <span x-show="stats.completed > 0" class="ml-2 px-2 py-0.5 text-xs bg-emerald-500 text-white rounded-full" x-text="stats.completed"></span>
                    </button>
                </div>
            </div>
        </nav>

        {{-- Main Content --}}
        <main>
            {{-- New Assessment Tab --}}
            <div x-show="view === 'new'" x-transition>
                <div class="bg-white/70 dark:bg-gray-800/70 backdrop-blur-xl border border-white/20 dark:border-gray-700/50 rounded-2xl shadow-sm p-6">
                    <div class="flex flex-wrap justify-between items-center gap-4 mb-6">
                        <div>
                            <h2 class="text-2xl font-semibold text-gray-800 dark:text-white" x-text="editing ? 'Edit Assessment' : 'New Assessment'"></h2>
                            <p class="text-gray-500 dark:text-gray-400 mt-1 text-sm">Monthly tutor performance assessment</p>
                        </div>
                    </div>

                    <form @submit.prevent="saveAssessment()">
                        {{-- Selection Grid --}}
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                            {{-- Tutor Selection --}}
                            <div>
                                <label class="block text-sm font-medium mb-2 text-gray-700 dark:text-gray-300">Tutor *</label>
                                <select x-model="formData.tutor_id" @change="loadTutorStudents()" required class="w-full border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white px-3 py-2 rounded-lg focus:ring-2 focus:ring-[#C15F3C] focus:border-[#C15F3C]">
                                    <option value="">Select tutor</option>
                                    @foreach($tutors as $tutor)
                                        <option value="{{ $tutor->id }}">{{ $tutor->first_name }} {{ $tutor->last_name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- Assessment Month --}}
                            <div>
                                <label class="block text-sm font-medium mb-2 text-gray-700 dark:text-gray-300">Assessment Month *</label>
                                <input type="month" x-model="formData.assessment_month" required
                                       class="w-full border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white px-3 py-2 rounded-lg focus:ring-2 focus:ring-[#C15F3C] focus:border-[#C15F3C]">
                            </div>

                            {{-- Assessment Date --}}
                            <div>
                                <label class="block text-sm font-medium mb-2 text-gray-700 dark:text-gray-300">Assessment Date *</label>
                                <input type="date" x-model="formData.assessment_date" required
                                       class="w-full border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white px-3 py-2 rounded-lg focus:ring-2 focus:ring-[#C15F3C] focus:border-[#C15F3C]">
                            </div>
                        </div>

                        {{-- Student Chips --}}
                        <div x-show="formData.tutor_id" x-transition class="mb-6">
                            <h3 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">Students assigned to this tutor</h3>
                            <div x-show="loadingStudents" class="text-sm text-gray-400 italic py-2">Loading attendance data...</div>
                            <div x-show="!loadingStudents && tutorStudents.length > 0" class="flex flex-wrap gap-2">
                                <template x-for="student in tutorStudents" :key="student.id">
                                    <div class="flex items-center gap-2 bg-[#C15F3C]/10 dark:bg-[#C15F3C]/20 border border-[#C15F3C]/30 rounded-full px-3 py-1.5">
                                        <span class="text-sm font-medium text-gray-800 dark:text-white" x-text="student.name"></span>
                                        <span class="text-xs text-gray-500 dark:text-gray-400" x-text="'(' + student.classes_attended + '/' + student.total_classes + ')'"></span>
                                    </div>
                                </template>
                            </div>
                            <div x-show="!loadingStudents && formData.tutor_id && tutorStudents.length === 0" class="text-sm text-gray-400 italic py-2">No active students assigned to this tutor</div>
                            <p class="text-xs text-gray-400 mt-2">Format: Name (approved classes attended / scheduled classes). Attendance data is as of the assessment date.</p>
                        </div>

                        {{-- Criteria Selection - 8 criteria --}}
                        <div class="mb-6">
                            <h3 class="font-semibold mb-3 text-gray-800 dark:text-white">Select Criteria to Assess</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                <template x-for="criterion in criteria" :key="criterion.id">
                                    <label :class="checkedCriteria[criterion.id] ? 'border-[#C15F3C] bg-[#C15F3C]/10 dark:bg-[#C15F3C]/20' : 'border-gray-200 dark:border-gray-600 hover:border-[#C15F3C]/50'"
                                           class="flex items-center gap-3 p-3 border rounded-lg transition-all cursor-pointer">
                                        <input type="checkbox"
                                               :value="criterion.id"
                                               x-model="checkedCriteria[criterion.id]"
                                               class="w-4 h-4 text-[#C15F3C] rounded focus:ring-[#C15F3C]">
                                        <div>
                                            <span class="text-sm font-medium text-gray-700 dark:text-gray-300" x-text="criterion.name"></span>
                                            <p class="text-xs text-gray-500 dark:text-gray-400" x-text="criterion.penalty"></p>
                                        </div>
                                    </label>
                                </template>
                            </div>
                        </div>

                        {{-- Ratings Section --}}
                        <div class="mb-6">
                            <h3 class="font-semibold mb-3 text-gray-800 dark:text-white">Provide Ratings</h3>
                            <div class="space-y-4">
                                <template x-for="criterion in criteria.filter(c => checkedCriteria[c.id])" :key="criterion.id">
                                    <div class="border border-gray-200 dark:border-gray-600 rounded-lg p-4 bg-gray-50/50 dark:bg-gray-700/50">
                                        <div class="font-medium mb-3 text-gray-800 dark:text-white" x-text="criterion.name"></div>
                                        <div class="flex gap-2 flex-wrap">
                                            <template x-for="opt in criterion.options" :key="opt">
                                                <button type="button"
                                                        @click="ratings[criterion.id] = opt"
                                                        :class="ratings[criterion.id] === opt ? 'bg-gradient-to-r from-[#C15F3C] to-[#DA7756] text-white shadow-md' : 'bg-white dark:bg-gray-600 border border-gray-300 dark:border-gray-500 text-gray-700 dark:text-gray-200 hover:border-[#C15F3C]'"
                                                        class="px-4 py-2 rounded-lg font-medium transition-all"
                                                        x-text="opt">
                                                </button>
                                            </template>
                                        </div>

                                        {{-- Punctuality incident count --}}
                                        <div x-show="criterion.id === 'punctuality'" x-transition class="mt-3 flex items-center gap-3 p-3 bg-amber-50 dark:bg-amber-900/20 rounded-lg border border-amber-200 dark:border-amber-800/30">
                                            <label class="text-sm font-medium text-amber-800 dark:text-amber-300 whitespace-nowrap">Times late this month</label>
                                            <input type="number" x-model.number="incidentCounts.punctualityLate" min="0"
                                                   class="w-16 text-center border border-amber-300 dark:border-amber-700 dark:bg-gray-700 dark:text-white px-2 py-1 rounded-lg text-sm focus:ring-2 focus:ring-amber-500 focus:border-amber-500">
                                            <span class="text-xs text-amber-600 dark:text-amber-400" x-show="incidentCounts.punctualityLate > 0"
                                                  x-text="'Penalty: ' + incidentCounts.punctualityLate + ' x &#8358;500 = &#8358;' + (incidentCounts.punctualityLate * 500).toLocaleString()"></span>
                                        </div>

                                        {{-- Video-off incident count --}}
                                        <div x-show="criterion.id === 'video'" x-transition class="mt-3 flex items-center gap-3 p-3 bg-amber-50 dark:bg-amber-900/20 rounded-lg border border-amber-200 dark:border-amber-800/30">
                                            <label class="text-sm font-medium text-amber-800 dark:text-amber-300 whitespace-nowrap">Video-off incidents</label>
                                            <input type="number" x-model.number="incidentCounts.videoOff" min="0"
                                                   class="w-16 text-center border border-amber-300 dark:border-amber-700 dark:bg-gray-700 dark:text-white px-2 py-1 rounded-lg text-sm focus:ring-2 focus:ring-amber-500 focus:border-amber-500">
                                            <span class="text-xs text-amber-600 dark:text-amber-400" x-show="incidentCounts.videoOff > 0"
                                                  x-text="'Penalty: ' + incidentCounts.videoOff + ' x &#8358;1,000 = &#8358;' + (incidentCounts.videoOff * 1000).toLocaleString()"></span>
                                        </div>

                                        {{-- Professional conduct alert --}}
                                        <div x-show="criterion.id === 'professional' && ratings['professional'] === 'Unacceptable'" x-transition class="mt-3 p-3 bg-red-50 dark:bg-red-900/20 rounded-lg border border-red-200 dark:border-red-800/30">
                                            <div class="flex items-center gap-2 text-red-700 dark:text-red-300">
                                                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z" />
                                                </svg>
                                                <span class="text-sm font-medium">Unacceptable professional conduct flagged. This will be escalated to the Director for review.</span>
                                            </div>
                                        </div>
                                    </div>
                                </template>
                                <div x-show="Object.keys(checkedCriteria).filter(k => checkedCriteria[k]).length === 0" class="text-center py-8 text-gray-500 italic">
                                    Select criteria above to start rating
                                </div>
                            </div>
                        </div>

                        {{-- Comments Section --}}
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                            <div>
                                <label class="block text-sm font-medium mb-2 text-gray-700 dark:text-gray-300">Strengths</label>
                                <textarea x-model="formData.strengths" placeholder="Document tutor's strengths, notable observations..."
                                          class="w-full border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-xl p-3 h-32 focus:ring-2 focus:ring-[#C15F3C] focus:border-[#C15F3C]"></textarea>
                            </div>
                            <div>
                                <label class="block text-sm font-medium mb-2 text-gray-700 dark:text-gray-300">Areas of Concern</label>
                                <textarea x-model="formData.weaknesses" placeholder="Notes for areas of concern..."
                                          class="w-full border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-xl p-3 h-32 focus:ring-2 focus:ring-[#C15F3C] focus:border-[#C15F3C]"></textarea>
                            </div>
                        </div>

                        {{-- Auto-calculated Performance Score --}}
                        <div class="mb-6" x-show="Object.keys(ratings).length > 0">
                            <label class="block text-sm font-medium mb-2 text-gray-700 dark:text-gray-300">Performance Score (auto-calculated)</label>
                            <div class="flex items-center gap-3">
                                <span class="text-3xl font-bold" :class="{
                                    'text-green-600': calculatedScore >= 70,
                                    'text-amber-600': calculatedScore >= 50 && calculatedScore < 70,
                                    'text-red-600': calculatedScore < 50
                                }" x-text="calculatedScore + '%'"></span>
                                <span class="text-sm text-gray-500 dark:text-gray-400" x-text="'(' + calculatedScoreLabel + ')'"></span>
                            </div>
                        </div>

                        {{-- Action Buttons --}}
                        <div class="flex gap-3 flex-wrap">
                            <button type="button" @click="saveAssessment('draft')" class="bg-gradient-to-r from-blue-500 to-blue-600 text-white px-5 py-2.5 rounded-xl font-medium shadow-lg hover:shadow-xl transition-all hover:-translate-y-0.5">
                                Save Assessment
                            </button>
                            <button type="button" @click="saveAssessment('send')" class="bg-gradient-to-r from-emerald-500 to-emerald-600 text-white px-5 py-2.5 rounded-xl font-medium shadow-lg hover:shadow-xl transition-all hover:-translate-y-0.5">
                                Send to Director
                            </button>
                            <button type="button" @click="cancelAssessment()" class="bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 px-5 py-2.5 rounded-xl font-medium hover:bg-gray-50 dark:hover:bg-gray-600 transition-all">
                                Cancel
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            {{-- Dashboard Tab --}}
            <div x-show="view === 'dashboard'" x-transition>
                {{-- Header with Week Navigator --}}
                <div class="flex flex-wrap justify-between items-center gap-4 mb-6">
                    <div>
                        <h2 class="text-2xl font-semibold text-gray-800 dark:text-white">Dashboard</h2>
                        <p class="text-gray-500 dark:text-gray-400 mt-1 text-sm">Monthly tutor assessments overview</p>
                    </div>
                </div>

                {{-- Filters --}}
                <div class="bg-white/70 dark:bg-gray-800/70 backdrop-blur-xl border border-white/20 dark:border-gray-700/50 rounded-2xl shadow-sm p-4 mb-6">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                        <div>
                            <label class="block text-sm font-medium mb-2 text-gray-700 dark:text-gray-300">Filter by Tutor</label>
                            <select x-model="dashFilterTutor" class="w-full border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white px-3 py-2 rounded-lg focus:ring-[#C15F3C] focus:border-[#C15F3C]">
                                <option value="">All Tutors</option>
                                @foreach($tutors as $tutor)
                                    <option value="{{ $tutor->id }}">{{ $tutor->first_name }} {{ $tutor->last_name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium mb-2 text-gray-700 dark:text-gray-300">Filter by Status</label>
                            <select x-model="dashFilterStatus" class="w-full border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white px-3 py-2 rounded-lg focus:ring-[#C15F3C] focus:border-[#C15F3C]">
                                <option value="">All Status</option>
                                <option value="draft">Draft</option>
                                <option value="submitted">Submitted</option>
                                <option value="approved-by-manager">Awaiting Director</option>
                            </select>
                        </div>
                        <div class="flex items-end">
                            <button @click="clearDashFilters()" class="w-full bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 px-4 py-2 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-600 transition-all">
                                Clear Filters
                            </button>
                        </div>
                    </div>
                </div>

                {{-- Assessment Cards --}}
                <div class="space-y-4">
                    @forelse($assessments->filter(function ($a) {
                        return in_array($a->status, ['draft', 'submitted', 'approved-by-manager']);
                    }) as $assessment)
                        <div x-show="(!dashFilterTutor || dashFilterTutor == '{{ $assessment->tutor_id }}') && (!dashFilterStatus || dashFilterStatus === '{{ $assessment->status }}')"
                             class="bg-white/70 dark:bg-gray-800/70 backdrop-blur-xl border border-white/20 dark:border-gray-700/50 rounded-2xl shadow-sm p-5 border-l-4 {{ $assessment->status === 'draft' ? 'border-l-amber-400' : 'border-l-[#C15F3C]' }}">
                            <div class="flex flex-wrap justify-between items-start gap-4 mb-4">
                                <div class="flex-1">
                                    <div class="text-lg font-semibold text-gray-800 dark:text-white flex items-center gap-2 flex-wrap">
                                        {{ $assessment->tutor->first_name ?? 'Unknown' }} {{ $assessment->tutor->last_name ?? '' }}
                                        @if($assessment->is_stand_in)
                                            <span class="px-2 py-0.5 text-xs bg-purple-500 text-white rounded-full">Stand-in</span>
                                        @endif
                                        @if($assessment->status === 'approved-by-manager')
                                            <span class="px-2 py-0.5 text-xs bg-[#C15F3C] text-white rounded-full">Awaiting Director</span>
                                        @elseif($assessment->status === 'draft')
                                            <span class="px-2 py-0.5 text-xs bg-amber-500 text-white rounded-full">Draft</span>
                                        @endif
                                    </div>
                                    @if($assessment->is_stand_in && $assessment->originalTutor)
                                        <div class="text-xs text-purple-600 dark:text-purple-400 mt-1">
                                            Standing in for: {{ $assessment->originalTutor->first_name }} {{ $assessment->originalTutor->last_name }}
                                        </div>
                                    @endif
                                    <div class="text-gray-500 dark:text-gray-400 text-sm">
                                        {{ $assessment->assessment_period }}
                                        @if($assessment->assessment_date)
                                            | {{ $assessment->assessment_date->format('d M Y') }}
                                        @endif
                                    </div>
                                    @if($assessment->performance_score)
                                        <div class="mt-2 flex items-center gap-2">
                                            <span class="text-2xl font-bold text-gray-800 dark:text-white">{{ $assessment->performance_score }}%</span>
                                            <span class="text-sm text-gray-500">Performance Score</span>
                                        </div>
                                    @endif
                                </div>
                                <div class="flex gap-2">
                                    @if($assessment->status === 'draft')
                                        <a href="{{ route('manager.assessments.edit', $assessment) }}" class="text-xs px-3 py-1.5 border border-[#C15F3C] text-[#C15F3C] rounded-lg hover:bg-[#C15F3C]/10 dark:hover:bg-[#C15F3C]/20 transition-colors">
                                            Edit
                                        </a>
                                        <form action="{{ route('manager.assessments.destroy', $assessment) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this draft assessment?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-xs px-3 py-1.5 border border-red-500 text-red-500 rounded-lg hover:bg-red-50 dark:hover:bg-red-900/20 transition-colors">
                                                Delete
                                            </button>
                                        </form>
                                        <form action="{{ route('manager.assessments.submit', $assessment) }}" method="POST" class="inline">
                                            @csrf
                                            <button type="submit" class="text-xs px-3 py-1.5 bg-gradient-to-r from-[#C15F3C] to-[#DA7756] text-white rounded-lg hover:from-[#A34E30] hover:to-[#C15F3C] transition-colors">
                                                Submit to Director
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </div>

                            @if($assessment->strengths)
                                <div class="mt-3 p-3 bg-[#C15F3C]/10 dark:bg-[#C15F3C]/20 rounded-lg">
                                    <div class="font-semibold text-[#C15F3C] dark:text-[#DA7756] mb-1 text-sm">Strengths:</div>
                                    <div class="text-gray-700 dark:text-gray-300 text-sm">{{ Str::limit($assessment->strengths, 200) }}</div>
                                </div>
                            @endif
                        </div>
                    @empty
                        <div class="bg-white/70 dark:bg-gray-800/70 backdrop-blur-xl border border-white/20 dark:border-gray-700/50 rounded-2xl shadow-sm p-8 text-center">
                            <svg class="w-16 h-16 mx-auto text-gray-300 dark:text-gray-600 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                            </svg>
                            <h3 class="text-xl font-semibold text-gray-700 dark:text-gray-300 mb-2">No Assessments Found</h3>
                            <p class="text-gray-500 dark:text-gray-400">Create a new assessment to get started</p>
                        </div>
                    @endforelse
                </div>
            </div>

            {{-- Management Tab --}}
            <div x-show="view === 'management'" x-transition>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    {{-- Quick Stats --}}
                    <div class="bg-white/70 dark:bg-gray-800/70 backdrop-blur-xl border border-white/20 dark:border-gray-700/50 rounded-2xl shadow-sm p-5">
                        <h3 class="font-semibold mb-4 text-gray-800 dark:text-white text-lg">Assessment Statistics</h3>
                        <div class="grid grid-cols-2 gap-4">
                            <div class="p-4 bg-gray-50/50 dark:bg-gray-700/50 rounded-xl text-center">
                                <div class="text-3xl font-bold text-gray-800 dark:text-white">{{ $stats['total'] }}</div>
                                <div class="text-sm text-gray-500 dark:text-gray-400">Total</div>
                            </div>
                            <div class="p-4 bg-amber-50 dark:bg-amber-900/20 rounded-xl text-center">
                                <div class="text-3xl font-bold text-amber-600">{{ $stats['pending'] }}</div>
                                <div class="text-sm text-amber-600">Pending</div>
                            </div>
                            <div class="p-4 bg-[#C15F3C]/10 dark:bg-[#C15F3C]/20 rounded-xl text-center">
                                <div class="text-3xl font-bold text-[#C15F3C]">{{ $stats['awaiting_director'] }}</div>
                                <div class="text-sm text-[#C15F3C]">Awaiting Director</div>
                            </div>
                            <div class="p-4 bg-emerald-50 dark:bg-emerald-900/20 rounded-xl text-center">
                                <div class="text-3xl font-bold text-emerald-600">{{ $stats['completed'] }}</div>
                                <div class="text-sm text-emerald-600">Completed</div>
                            </div>
                        </div>
                    </div>

                    {{-- Tutors List --}}
                    <div class="bg-white/70 dark:bg-gray-800/70 backdrop-blur-xl border border-white/20 dark:border-gray-700/50 rounded-2xl shadow-sm p-5">
                        <h3 class="font-semibold mb-4 text-gray-800 dark:text-white text-lg">Active Tutors ({{ $tutors->count() }})</h3>
                        <div class="space-y-3 max-h-96 overflow-y-auto">
                            @foreach($tutors as $tutor)
                                <div class="p-3 bg-gray-50/50 dark:bg-gray-700/50 rounded-lg">
                                    <div class="flex justify-between items-center mb-2">
                                        <div class="flex items-center gap-3">
                                            <div class="w-8 h-8 rounded-full bg-gradient-to-r from-[#C15F3C] to-[#DA7756] flex items-center justify-center text-white font-bold text-sm">
                                                {{ strtoupper(substr($tutor->first_name, 0, 1)) }}
                                            </div>
                                            <span class="font-medium text-gray-800 dark:text-white">{{ $tutor->first_name }} {{ $tutor->last_name }}</span>
                                        </div>
                                        <span class="px-2 py-1 text-xs rounded-full {{ $tutor->status === 'active' ? 'bg-emerald-100 text-emerald-800 dark:bg-emerald-900/30 dark:text-emerald-300' : 'bg-gray-100 text-gray-600 dark:bg-gray-700 dark:text-gray-400' }}">
                                            {{ ucfirst($tutor->status) }}
                                        </span>
                                    </div>
                                    @php
                                        $tutorStudents = $students->where('tutor_id', $tutor->id);
                                    @endphp
                                    @if($tutorStudents->count() > 0)
                                        <div class="flex flex-wrap gap-1 mt-2">
                                            @foreach($tutorStudents as $student)
                                                <span class="px-2 py-0.5 text-xs bg-white dark:bg-gray-600 text-gray-700 dark:text-gray-300 border border-gray-200 dark:border-gray-500 rounded-full">
                                                    {{ $student->first_name }} {{ $student->last_name }}
                                                </span>
                                            @endforeach
                                        </div>
                                    @else
                                        <div class="text-xs text-gray-400 dark:text-gray-500 italic mt-2">No students assigned</div>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            {{-- Completed Tab --}}
            <div x-show="view === 'completed'" x-transition>
                <div class="mb-6 flex justify-between items-start">
                    <div>
                        <h2 class="text-2xl font-semibold text-gray-800 dark:text-white flex items-center gap-3">
                            Completed Assessments
                            <span class="px-3 py-1 bg-emerald-500 text-white text-sm rounded-full" x-text="filteredCompletedAssessments.length"></span>
                        </h2>
                        <p class="text-gray-500 dark:text-gray-400 mt-1 text-sm">Assessments approved by the Director</p>
                    </div>
                </div>

                {{-- Filters --}}
                <div class="bg-white/70 dark:bg-gray-800/70 backdrop-blur-xl border border-white/20 dark:border-gray-700/50 rounded-2xl shadow-sm p-5 mb-6">
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                        <div>
                            <label class="block text-sm font-medium mb-2 text-gray-700 dark:text-gray-300">Tutor</label>
                            <select x-model="completedFilters.tutor" class="w-full border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white px-3 py-2 rounded-lg focus:ring-[#C15F3C] focus:border-[#C15F3C] text-sm">
                                <option value="">All Tutors</option>
                                @foreach($tutors as $tutor)
                                    <option value="{{ $tutor->id }}">{{ $tutor->first_name }} {{ $tutor->last_name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium mb-2 text-gray-700 dark:text-gray-300">Month</label>
                            <input type="month" x-model="completedFilters.month" class="w-full border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white px-3 py-2 rounded-lg focus:ring-[#C15F3C] focus:border-[#C15F3C] text-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-medium mb-2 text-gray-700 dark:text-gray-300">Year</label>
                            <select x-model="completedFilters.year" class="w-full border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white px-3 py-2 rounded-lg focus:ring-[#C15F3C] focus:border-[#C15F3C] text-sm">
                                <option value="">All Years</option>
                                @for($y = date('Y'); $y >= date('Y') - 2; $y--)
                                    <option value="{{ $y }}">{{ $y }}</option>
                                @endfor
                            </select>
                        </div>
                        <div class="flex items-end">
                            <button @click="clearCompletedFilters()" class="w-full bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 px-4 py-2 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-600 transition-all text-sm">
                                Clear Filters
                            </button>
                        </div>
                    </div>
                </div>

                <div class="space-y-4">
                    <template x-for="assessment in filteredCompletedAssessments" :key="assessment.id">
                        <div class="bg-white/70 dark:bg-gray-800/70 backdrop-blur-xl border border-white/20 dark:border-gray-700/50 rounded-2xl shadow-sm p-5 border-l-4 border-l-emerald-500">
                            <div class="flex flex-wrap justify-between items-start gap-4">
                                <div class="flex-1">
                                    <div class="text-lg font-semibold text-gray-800 dark:text-white flex items-center gap-2 flex-wrap">
                                        <span x-text="assessment.tutor_name"></span>
                                        <span x-show="assessment.is_stand_in" class="px-2 py-0.5 text-xs bg-purple-500 text-white rounded-full">Stand-in</span>
                                        <span class="px-2 py-0.5 text-xs bg-emerald-500 text-white rounded-full">Completed</span>
                                    </div>
                                    <div x-show="assessment.is_stand_in && assessment.original_tutor_name" class="text-xs text-purple-600 dark:text-purple-400 mt-1">
                                        Standing in for: <span x-text="assessment.original_tutor_name"></span>
                                    </div>
                                    <div class="text-gray-500 dark:text-gray-400 text-sm" x-text="assessment.assessment_month"></div>
                                    <div class="text-gray-500 dark:text-gray-400 text-xs mt-1" x-show="assessment.student_name">
                                        Student: <span x-text="assessment.student_name"></span>
                                    </div>
                                    <template x-if="assessment.performance_score">
                                        <div class="mt-2">
                                            <span class="text-2xl font-bold text-emerald-600" x-text="assessment.performance_score + '%'"></span>
                                        </div>
                                    </template>
                                </div>
                                <div class="text-right">
                                    <template x-if="assessment.approved_at">
                                        <div class="text-xs text-gray-500" x-text="'Approved ' + assessment.approved_at"></div>
                                    </template>
                                    <a :href="'/manager/assessments/' + assessment.id" class="mt-2 inline-flex items-center text-xs px-3 py-1.5 bg-emerald-100 dark:bg-emerald-900/30 text-emerald-700 dark:text-emerald-300 rounded-lg hover:bg-emerald-200 dark:hover:bg-emerald-900/50 transition-colors">
                                        View Details
                                    </a>
                                </div>
                            </div>

                            <template x-if="assessment.director_comment">
                                <div class="mt-4 p-3 bg-amber-50 dark:bg-amber-900/20 rounded-lg border border-amber-200 dark:border-amber-800/30">
                                    <div class="font-semibold text-amber-800 dark:text-amber-300 mb-1 text-sm">Director's Remarks:</div>
                                    <div class="text-amber-700 dark:text-amber-200 text-sm" x-text="assessment.director_comment"></div>
                                </div>
                            </template>
                        </div>
                    </template>

                    <template x-if="filteredCompletedAssessments.length === 0">
                        <div class="bg-white/70 dark:bg-gray-800/70 backdrop-blur-xl border border-white/20 dark:border-gray-700/50 rounded-2xl shadow-sm p-8 text-center">
                            <svg class="w-16 h-16 mx-auto text-gray-300 dark:text-gray-600 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <h3 class="text-xl font-semibold text-gray-700 dark:text-gray-300 mb-2">No Completed Assessments Found</h3>
                            <p class="text-gray-500 dark:text-gray-400">Try adjusting your filters or check back later</p>
                        </div>
                    </template>
                </div>
            </div>
        </main>

        {{-- Footer Stats --}}
        <footer class="mt-8 py-4 bg-white/70 dark:bg-gray-800/70 backdrop-blur-xl border border-white/20 dark:border-gray-700/50 rounded-2xl shadow-sm">
            <div class="text-sm text-gray-500 dark:text-gray-400 text-center">
                {{ $stats['pending'] }} pending | {{ $stats['awaiting_director'] }} awaiting director | {{ $stats['completed'] }} completed | {{ $tutors->count() }} tutors
            </div>
        </footer>

        {{-- Toast Notification --}}
        <div x-show="toast.show" x-transition
             :class="toast.type === 'success' ? 'bg-emerald-500' : 'bg-red-500'"
             class="fixed bottom-6 right-6 text-white px-5 py-3 rounded-xl shadow-lg z-50">
            <span x-text="toast.message"></span>
        </div>
    </div>

    @php
        // Standard rating options (ensures DB is not stale)
        $standardOptions = ['Excellent', 'Good', 'Acceptable', 'Needs Improvement'];
        $professionalOptions = ['Excellent', 'Good', 'Acceptable', 'Needs Improvement', 'Unacceptable'];

        // Prepare criteria data for JavaScript
        $criteriaForJs = $criteria->map(function($c) use ($standardOptions, $professionalOptions) {
            // Override options to ensure correct scale for all criteria
            $options = $c->code === 'professional' ? $professionalOptions : $standardOptions;

            // Build penalty label from penalty_rules
            $penaltyLabel = 'No penalty';
            $rules = $c->penalty_rules;
            if (!empty($rules) && is_array($rules)) {
                $labels = [];
                foreach ($rules as $rating => $rule) {
                    if (isset($rule['label'])) {
                        $labels[] = "Penalty: {$rule['label']}";
                    } elseif (isset($rule['amount'])) {
                        $labels[] = "Penalty: ₦" . number_format($rule['amount']);
                    } elseif (isset($rule['halfPay']) && $rule['halfPay']) {
                        $labels[] = "Penalty: Half pay deduction";
                    } elseif (isset($rule['action'])) {
                        $labels[] = "Penalty: {$rule['action']}";
                    } elseif (isset($rule['countThreshold'])) {
                        $labels[] = "Penalty: Flagged after {$rule['countThreshold']}x";
                    }
                }
                if (!empty($labels)) {
                    $penaltyLabel = implode(' | ', $labels);
                }
            }

            return [
                'id' => $c->code,
                'name' => $c->name,
                'penalty' => $penaltyLabel,
                'options' => $options,
            ];
        })->values()->toArray();
    @endphp
    @push('scripts')
    <script>
        function assessmentApp() {
            return {
                view: 'dashboard',
                editing: null,

                stats: {
                    total: {{ $stats['total'] }},
                    pending: {{ $stats['pending'] }},
                    awaiting_director: {{ $stats['awaiting_director'] }},
                    completed: {{ $stats['completed'] }}
                },

                formData: {
                    tutor_id: '',
                    assessment_month: new Date().toISOString().slice(0, 7),
                    assessment_date: new Date().toISOString().split('T')[0],
                    year: {{ date('Y') }},
                    strengths: '',
                    weaknesses: '',
                },

                tutorStudents: [],
                loadingStudents: false,
                incidentCounts: { punctualityLate: 0, videoOff: 0 },

                // Rating-to-percentage mapping (must match AssessmentRating model)
                ratingPercentages: {
                    'Excellent': 90,
                    'Good': 70,
                    'Acceptable': 55,
                    'Needs Improvement': 20,
                    'Unacceptable': 0
                },

                get calculatedScore() {
                    const ratedCriteria = Object.keys(this.ratings).filter(k => this.ratings[k]);
                    if (ratedCriteria.length === 0) return 0;
                    let total = 0;
                    ratedCriteria.forEach(k => {
                        total += this.ratingPercentages[this.ratings[k]] ?? 0;
                    });
                    return Math.round((total / ratedCriteria.length) * 10) / 10;
                },

                get calculatedScoreLabel() {
                    const s = this.calculatedScore;
                    if (s >= 90) return 'Excellent';
                    if (s >= 70) return 'Good';
                    if (s >= 50) return 'Acceptable';
                    return 'Needs Improvement';
                },

                // 8 Assessment Criteria from database
                criteria: @json($criteriaForJs),

                checkedCriteria: {},
                ratings: {},

                dashFilterTutor: '',
                dashFilterStatus: '',

                toast: {
                    show: false,
                    message: '',
                    type: 'success'
                },

                init() {
                    this.criteria.forEach(c => {
                        this.checkedCriteria[c.id] = false;
                    });
                },

                async loadTutorStudents() {
                    const tutorId = parseInt(this.formData.tutor_id);
                    if (!tutorId) { this.tutorStudents = []; return; }

                    this.loadingStudents = true;
                    try {
                        const month = this.formData.assessment_month || new Date().toISOString().slice(0, 7);
                        const response = await fetch(`{{ url('manager/assessments/tutor-students') }}/${tutorId}?month=${month}`, {
                            headers: {
                                'Accept': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            }
                        });
                        if (response.ok) {
                            const data = await response.json();
                            this.tutorStudents = data.students || [];
                        } else {
                            // Fallback to local student data
                            const students = @json($students ?? []);
                            this.tutorStudents = students
                                .filter(s => s.tutor_id == tutorId && s.status === 'active')
                                .map(s => ({
                                    id: s.id,
                                    name: s.first_name + ' ' + s.last_name,
                                    classes_attended: 0,
                                    total_classes: 0
                                }));
                        }
                    } catch (e) {
                        console.error('Error loading students:', e);
                        const students = @json($students ?? []);
                        this.tutorStudents = students
                            .filter(s => s.tutor_id == tutorId && s.status === 'active')
                            .map(s => ({
                                id: s.id,
                                name: s.first_name + ' ' + s.last_name,
                                classes_attended: 0,
                                total_classes: 0
                            }));
                    } finally {
                        this.loadingStudents = false;
                    }
                },

                showToast(message, type = 'success') {
                    this.toast = { show: true, message, type };
                    setTimeout(() => { this.toast.show = false; }, 3000);
                },

                resetForm() {
                    this.formData = {
                        tutor_id: '',
                        assessment_month: new Date().toISOString().slice(0, 7),
                        assessment_date: new Date().toISOString().split('T')[0],
                        year: {{ date('Y') }},
                        strengths: '',
                        weaknesses: '',
                    };
                    this.tutorStudents = [];
                    this.incidentCounts = { punctualityLate: 0, videoOff: 0 };
                    this.criteria.forEach(c => {
                        this.checkedCriteria[c.id] = false;
                    });
                    this.ratings = {};
                    this.editing = null;
                },

                clearDashFilters() {
                    this.dashFilterTutor = '';
                    this.dashFilterStatus = '';
                },

                // Completed assessments data
                completedAssessments: [
                    @foreach($assessments->filter(fn($a) => $a->status === 'approved-by-director') as $assessment)
                        {
                            id: {{ $assessment->id }},
                            tutor_id: {{ $assessment->tutor_id }},
                            tutor_name: {!! json_encode(($assessment->tutor->first_name ?? '') . ' ' . ($assessment->tutor->last_name ?? '')) !!},
                            student_id: {{ $assessment->student_id ?? 'null' }},
                            student_name: {!! json_encode($assessment->student ? ($assessment->student->first_name . ' ' . $assessment->student->last_name) : '') !!},
                            assessment_month: '{{ $assessment->assessment_month }}',
                            week: {{ $assessment->week ?? 'null' }},
                            year: {{ $assessment->year ?? 'null' }},
                            class_date: '{{ $assessment->class_date ?? '' }}',
                            performance_score: {{ $assessment->performance_score ?? 'null' }},
                            approved_at: '{{ $assessment->approved_by_director_at ? $assessment->approved_by_director_at->format("M j, Y") : "" }}',
                            director_comment: {!! json_encode($assessment->director_comment ?? '') !!},
                            is_stand_in: {{ $assessment->is_stand_in ? 'true' : 'false' }},
                            original_tutor_name: {!! json_encode($assessment->is_stand_in && $assessment->originalTutor ? ($assessment->originalTutor->first_name . ' ' . $assessment->originalTutor->last_name) : '') !!}
                        },
                    @endforeach
                ],

                completedFilters: {
                    tutor: '',
                    month: '',
                    year: '',
                },

                get filteredCompletedAssessments() {
                    return this.completedAssessments.filter(assessment => {
                        if (this.completedFilters.tutor && assessment.tutor_id != this.completedFilters.tutor) {
                            return false;
                        }
                        if (this.completedFilters.month && assessment.assessment_month !== this.completedFilters.month) {
                            return false;
                        }
                        if (this.completedFilters.year && assessment.year != this.completedFilters.year) {
                            return false;
                        }
                        return true;
                    });
                },

                clearCompletedFilters() {
                    this.completedFilters = {
                        tutor: '',
                        month: '',
                        year: '',
                    };
                },

                async saveAssessment(action = 'draft') {
                    if (!this.formData.tutor_id) {
                        this.showToast('Please select a tutor', 'error');
                        return;
                    }

                    if (!this.formData.assessment_month || !this.formData.assessment_date) {
                        this.showToast('Please enter assessment month and date', 'error');
                        return;
                    }

                    // Validation for sending to director
                    if (action === 'send') {
                        const checkedCount = Object.keys(this.checkedCriteria).filter(k => this.checkedCriteria[k]).length;
                        if (checkedCount === 0) {
                            this.showToast('Please select at least one criteria to assess', 'error');
                            return;
                        }
                    }

                    try {
                        const payload = {
                            ...this.formData,
                            performance_score: this.calculatedScore,
                            criteria_assessed: Object.keys(this.checkedCriteria).filter(k => this.checkedCriteria[k]),
                            criteria_ratings: this.ratings,
                            action: action, // 'draft' or 'send'
                            punctuality_late_count: this.incidentCounts.punctualityLate,
                            video_off_count: this.incidentCounts.videoOff,
                            student_chips: this.tutorStudents.map(s => ({
                                name: s.name,
                                classes_attended: s.classes_attended,
                                total_classes: s.total_classes
                            })),
                        };

                        const response = await fetch('{{ route("manager.assessments.store") }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Accept': 'application/json'
                            },
                            body: JSON.stringify(payload)
                        });

                        // Check if response is JSON
                        const contentType = response.headers.get('content-type');
                        if (!contentType || !contentType.includes('application/json')) {
                            const text = await response.text();
                            console.error('Non-JSON response:', text.substring(0, 500));
                            this.showToast('Server error: Invalid response format. Please check the logs.', 'error');
                            return;
                        }

                        const data = await response.json();

                        if (response.ok && data.success) {
                            const message = action === 'send'
                                ? 'Assessment sent to Director successfully!'
                                : 'Assessment saved as draft!';
                            this.showToast(message);
                            this.resetForm();
                            this.view = 'dashboard';
                            setTimeout(() => window.location.reload(), 1000);
                        } else {
                            const errorMsg = data.message || 'Failed to save assessment';
                            if (data.errors) {
                                const errorList = Object.values(data.errors).flat().join(', ');
                                this.showToast(errorMsg + ': ' + errorList, 'error');
                            } else {
                                this.showToast(errorMsg, 'error');
                            }
                        }
                    } catch (e) {
                        console.error('Save error:', e);
                        this.showToast('Error: ' + e.message, 'error');
                    }
                },

                cancelAssessment() {
                    if (confirm('Are you sure you want to cancel? All unsaved changes will be lost.')) {
                        this.resetForm();
                        this.view = 'dashboard';
                    }
                }
            };
        }
    </script>
    @endpush
</x-manager-layout>
