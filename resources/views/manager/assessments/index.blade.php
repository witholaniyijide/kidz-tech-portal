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
                            <p class="text-gray-500 dark:text-gray-400 mt-1 text-sm">Split across 3 sessions - you may save partial progress and complete later</p>
                        </div>
                        <div class="flex gap-3 items-center">
                            <label class="text-gray-500 font-medium text-sm">Session</label>
                            <select x-model="session" class="border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white px-3 py-2 rounded-lg focus:border-[#C15F3C] focus:ring-[#C15F3C]">
                                <option value="1">Session 1</option>
                                <option value="2">Session 2</option>
                                <option value="3">Session 3</option>
                            </select>
                        </div>
                    </div>

                    <form @submit.prevent="saveAssessment()">
                        {{-- Selection Grid --}}
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 mb-6">
                            {{-- Student --}}
                            <div>
                                <label class="block text-sm font-medium mb-2 text-gray-700 dark:text-gray-300">Student *</label>
                                <select x-model="formData.student_id" @change="selectStudent()" required class="w-full border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white px-3 py-2 rounded-lg focus:ring-2 focus:ring-[#C15F3C] focus:border-[#C15F3C]">
                                    <option value="">Select student</option>
                                    @foreach($students ?? [] as $student)
                                        @php
                                            $tutorName = $student->tutor
                                                ? $student->tutor->first_name . ' ' . $student->tutor->last_name
                                                : 'No Tutor Assigned';
                                        @endphp
                                        <option value="{{ $student->id }}"
                                                data-tutor-id="{{ $student->tutor_id }}"
                                                data-tutor-name="{{ $tutorName }}">
                                            {{ $student->first_name }} {{ $student->last_name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- Tutor (Auto-selected) --}}
                            <div>
                                <label class="block text-sm font-medium mb-2 text-gray-700 dark:text-gray-300">Assigned Tutor</label>
                                <div class="w-full border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white px-3 py-2 rounded-lg bg-gray-50 dark:bg-gray-600">
                                    <span x-text="selectedTutorName || 'Select a student first'" :class="selectedTutorName ? 'text-gray-900 dark:text-white' : 'text-gray-400 dark:text-gray-500'"></span>
                                    <input type="hidden" x-model="formData.tutor_id">
                                </div>
                            </div>

                            {{-- Date Class Taken --}}
                            <div>
                                <label class="block text-sm font-medium mb-2 text-gray-700 dark:text-gray-300">Date Class Taken *</label>
                                <input type="date" x-model="formData.class_date" @change="updateWeekFromDate()" required
                                       class="w-full border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white px-3 py-2 rounded-lg focus:ring-2 focus:ring-[#C15F3C] focus:border-[#C15F3C]">
                            </div>
                        </div>

                        {{-- Week Display --}}
                        <div class="mb-6 p-4 bg-[#C15F3C]/10 dark:bg-[#C15F3C]/20 rounded-xl" x-show="formData.class_date">
                            <div class="flex items-center gap-3">
                                <svg class="w-5 h-5 text-[#C15F3C]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                                <span class="font-medium text-[#C15F3C] dark:text-[#DA7756]" x-text="weekDisplay"></span>
                            </div>
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
                                <label class="block text-sm font-medium mb-2 text-gray-700 dark:text-gray-300">Tutor Performance Comments</label>
                                <textarea x-model="formData.strengths" placeholder="Document tutor's performance, notable observations..."
                                          class="w-full border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-xl p-3 h-32 focus:ring-2 focus:ring-[#C15F3C] focus:border-[#C15F3C]"></textarea>
                            </div>
                            <div>
                                <label class="block text-sm font-medium mb-2 text-gray-700 dark:text-gray-300">Areas for Improvement</label>
                                <textarea x-model="formData.weaknesses" placeholder="Notes for improvement areas..."
                                          class="w-full border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-xl p-3 h-32 focus:ring-2 focus:ring-[#C15F3C] focus:border-[#C15F3C]"></textarea>
                            </div>
                        </div>

                        {{-- Recommendations --}}
                        <div class="mb-6">
                            <label class="block text-sm font-medium mb-2 text-gray-700 dark:text-gray-300">Recommendations</label>
                            <textarea x-model="formData.recommendations" placeholder="Any recommendations for the tutor..."
                                      class="w-full border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-xl p-3 h-24 focus:ring-2 focus:ring-[#C15F3C] focus:border-[#C15F3C]"></textarea>
                        </div>

                        {{-- Performance Score --}}
                        <div class="mb-6">
                            <label class="block text-sm font-medium mb-2 text-gray-700 dark:text-gray-300">Performance Score (0-100)</label>
                            <input type="number" x-model="formData.performance_score" min="0" max="100" placeholder="Enter overall score"
                                   class="w-full md:w-48 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white px-3 py-2 rounded-lg focus:ring-2 focus:ring-[#C15F3C] focus:border-[#C15F3C]">
                        </div>

                        {{-- Action Buttons --}}
                        <div class="flex gap-3 flex-wrap">
                            <button type="submit" class="bg-gradient-to-r from-[#C15F3C] to-[#DA7756] text-white px-5 py-2.5 rounded-xl font-medium shadow-lg hover:shadow-xl transition-all hover:-translate-y-0.5">
                                <span x-text="editing ? 'Update Assessment' : 'Save Assessment'"></span>
                            </button>
                            <button type="button" @click="resetForm()" class="bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 px-5 py-2.5 rounded-xl font-medium hover:bg-gray-50 dark:hover:bg-gray-600 transition-all">
                                Reset Form
                            </button>
                            <button type="button" x-show="editing" @click="editing = null; view = 'dashboard'" class="bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 px-5 py-2.5 rounded-xl font-medium hover:bg-gray-50 dark:hover:bg-gray-600 transition-all">
                                Cancel Edit
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
                        <p class="text-gray-500 dark:text-gray-400 mt-1 text-sm" x-text="dashboardWeekDisplay"></p>
                    </div>
                    <div class="flex items-center gap-2">
                        <button @click="changeWeek(-1)" class="p-2 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-600 transition-all">
                            <svg class="w-5 h-5 text-gray-600 dark:text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                            </svg>
                        </button>
                        <div class="px-4 py-2 bg-gradient-to-r from-[#C15F3C] to-[#DA7756] text-white rounded-lg font-medium min-w-[200px] text-center">
                            <span x-text="'Week ' + weekView + ' (' + weekDateRange + ')'"></span>
                        </div>
                        <button @click="changeWeek(1)" class="p-2 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-600 transition-all">
                            <svg class="w-5 h-5 text-gray-600 dark:text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                            </svg>
                        </button>
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
                        <div class="bg-white/70 dark:bg-gray-800/70 backdrop-blur-xl border border-white/20 dark:border-gray-700/50 rounded-2xl shadow-sm p-5 border-l-4 {{ $assessment->status === 'draft' ? 'border-l-amber-400' : 'border-l-[#C15F3C]' }}">
                            <div class="flex flex-wrap justify-between items-start gap-4 mb-4">
                                <div class="flex-1">
                                    <div class="text-lg font-semibold text-gray-800 dark:text-white flex items-center gap-2">
                                        {{ $assessment->tutor->first_name ?? 'Unknown' }} {{ $assessment->tutor->last_name ?? '' }}
                                        @if($assessment->status === 'approved-by-manager')
                                            <span class="px-2 py-0.5 text-xs bg-[#C15F3C] text-white rounded-full">Awaiting Director</span>
                                        @elseif($assessment->status === 'draft')
                                            <span class="px-2 py-0.5 text-xs bg-amber-500 text-white rounded-full">Draft</span>
                                        @endif
                                    </div>
                                    <div class="text-gray-500 dark:text-gray-400 text-sm">
                                        {{ $assessment->assessment_month }} | Session {{ $assessment->session ?? 1 }}
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
                <div class="mb-6">
                    <h2 class="text-2xl font-semibold text-gray-800 dark:text-white">Completed Assessments</h2>
                    <p class="text-gray-500 dark:text-gray-400 mt-1 text-sm">Assessments approved by the Director</p>
                </div>

                <div class="space-y-4">
                    @forelse($assessments->filter(function ($a) {
                        return $a->status === 'approved-by-director';
                    }) as $assessment)
                        <div class="bg-white/70 dark:bg-gray-800/70 backdrop-blur-xl border border-white/20 dark:border-gray-700/50 rounded-2xl shadow-sm p-5 border-l-4 border-l-emerald-500">
                            <div class="flex flex-wrap justify-between items-start gap-4">
                                <div class="flex-1">
                                    <div class="text-lg font-semibold text-gray-800 dark:text-white flex items-center gap-2">
                                        {{ $assessment->tutor->first_name ?? 'Unknown' }} {{ $assessment->tutor->last_name ?? '' }}
                                        <span class="px-2 py-0.5 text-xs bg-emerald-500 text-white rounded-full">Completed</span>
                                    </div>
                                    <div class="text-gray-500 dark:text-gray-400 text-sm">
                                        {{ $assessment->assessment_month }}
                                    </div>
                                    @if($assessment->performance_score)
                                        <div class="mt-2">
                                            <span class="text-2xl font-bold text-emerald-600">{{ $assessment->performance_score }}%</span>
                                        </div>
                                    @endif
                                </div>
                                <div class="text-right">
                                    @if($assessment->approved_by_director_at)
                                        <div class="text-xs text-gray-500">
                                            Approved {{ $assessment->approved_by_director_at->format('M j, Y') }}
                                        </div>
                                    @endif
                                    <a href="{{ route('manager.assessments.show', $assessment) }}" class="mt-2 inline-flex items-center text-xs px-3 py-1.5 bg-emerald-100 dark:bg-emerald-900/30 text-emerald-700 dark:text-emerald-300 rounded-lg hover:bg-emerald-200 dark:hover:bg-emerald-900/50 transition-colors">
                                        View Details
                                    </a>
                                </div>
                            </div>

                            @if($assessment->director_comment)
                                <div class="mt-4 p-3 bg-amber-50 dark:bg-amber-900/20 rounded-lg border border-amber-200 dark:border-amber-800/30">
                                    <div class="font-semibold text-amber-800 dark:text-amber-300 mb-1 text-sm">Director's Remarks:</div>
                                    <div class="text-amber-700 dark:text-amber-200 text-sm">{{ $assessment->director_comment }}</div>
                                </div>
                            @endif
                        </div>
                    @empty
                        <div class="bg-white/70 dark:bg-gray-800/70 backdrop-blur-xl border border-white/20 dark:border-gray-700/50 rounded-2xl shadow-sm p-8 text-center">
                            <svg class="w-16 h-16 mx-auto text-gray-300 dark:text-gray-600 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <h3 class="text-xl font-semibold text-gray-700 dark:text-gray-300 mb-2">No Completed Assessments Yet</h3>
                            <p class="text-gray-500 dark:text-gray-400">Assessments will appear here after Director approval</p>
                        </div>
                    @endforelse
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

    @push('scripts')
    <script>
        function assessmentApp() {
            return {
                view: 'dashboard',
                editing: null,
                session: 1,
                weekView: {{ date('W') }},
                weekYear: {{ date('Y') }},
                weekDateRange: '',
                dashboardWeekDisplay: '',
                selectedTutorName: '',
                weekDisplay: '',

                stats: {
                    total: {{ $stats['total'] }},
                    pending: {{ $stats['pending'] }},
                    awaiting_director: {{ $stats['awaiting_director'] }},
                    completed: {{ $stats['completed'] }}
                },

                formData: {
                    student_id: '',
                    tutor_id: '',
                    class_date: '',
                    week: {{ date('W') }},
                    year: {{ date('Y') }},
                    performance_score: '',
                    strengths: '',
                    weaknesses: '',
                    recommendations: ''
                },

                // 8 Assessment Criteria from database
                criteria: @json($criteria->map(function($c) {
                    $penaltyLabels = [];
                    foreach ($c->penalty_rules ?? [] as $rating => $rule) {
                        if (isset($rule['label'])) {
                            $penaltyLabels[] = $rule['label'];
                        }
                    }
                    return [
                        'id' => $c->code,
                        'name' => $c->name,
                        'penalty' => implode(', ', $penaltyLabels) ?: 'No penalty',
                        'options' => $c->options,
                    ];
                })),

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
                    this.updateWeekDateRange();
                },

                // Get week date range in format "Dec 21 - Dec 27"
                getWeekDateRange(weekNum, year) {
                    const simple = new Date(year, 0, 1 + (weekNum - 1) * 7);
                    const dow = simple.getDay();
                    const startDate = new Date(simple);
                    startDate.setDate(simple.getDate() - dow + 1); // Monday
                    const endDate = new Date(startDate);
                    endDate.setDate(startDate.getDate() + 6); // Sunday

                    const months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
                    return `${months[startDate.getMonth()]} ${startDate.getDate()} - ${months[endDate.getMonth()]} ${endDate.getDate()}`;
                },

                updateWeekDateRange() {
                    this.weekDateRange = this.getWeekDateRange(this.weekView, this.weekYear);
                    this.dashboardWeekDisplay = `Tutor assessments for Week ${this.weekView} (${this.weekDateRange})`;
                },

                changeWeek(delta) {
                    this.weekView += delta;
                    if (this.weekView < 1) {
                        this.weekView = 52;
                        this.weekYear--;
                    } else if (this.weekView > 52) {
                        this.weekView = 1;
                        this.weekYear++;
                    }
                    this.updateWeekDateRange();
                },

                // Select student and auto-fill tutor
                selectStudent() {
                    const select = document.querySelector('select[x-model="formData.student_id"]');
                    const option = select.options[select.selectedIndex];
                    if (option && option.value) {
                        this.formData.tutor_id = option.dataset.tutorId || '';
                        this.selectedTutorName = option.dataset.tutorName || 'No Tutor Assigned';
                    } else {
                        this.formData.tutor_id = '';
                        this.selectedTutorName = '';
                    }
                },

                // Update week from selected date
                updateWeekFromDate() {
                    if (this.formData.class_date) {
                        const date = new Date(this.formData.class_date);
                        const oneJan = new Date(date.getFullYear(), 0, 1);
                        const numberOfDays = Math.floor((date - oneJan) / (24 * 60 * 60 * 1000));
                        const weekNum = Math.ceil((date.getDay() + 1 + numberOfDays) / 7);

                        this.formData.week = weekNum;
                        this.formData.year = date.getFullYear();

                        const weekRange = this.getWeekDateRange(weekNum, date.getFullYear());
                        this.weekDisplay = `Week ${weekNum} (${weekRange})`;
                    }
                },

                showToast(message, type = 'success') {
                    this.toast = { show: true, message, type };
                    setTimeout(() => { this.toast.show = false; }, 3000);
                },

                resetForm() {
                    this.formData = {
                        student_id: '',
                        tutor_id: '',
                        class_date: '',
                        week: {{ date('W') }},
                        year: {{ date('Y') }},
                        performance_score: '',
                        strengths: '',
                        weaknesses: '',
                        recommendations: ''
                    };
                    this.selectedTutorName = '';
                    this.weekDisplay = '';
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

                async saveAssessment() {
                    if (!this.formData.student_id || !this.formData.class_date) {
                        this.showToast('Please select a student and enter class date', 'error');
                        return;
                    }

                    if (!this.formData.tutor_id) {
                        this.showToast('Selected student has no assigned tutor', 'error');
                        return;
                    }

                    try {
                        const response = await fetch('{{ route("manager.assessments.store") }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            body: JSON.stringify({
                                ...this.formData,
                                assessment_month: this.weekDisplay,
                                session: this.session,
                                criteria_assessed: Object.keys(this.checkedCriteria).filter(k => this.checkedCriteria[k]),
                                criteria_ratings: this.ratings
                            })
                        });

                        const data = await response.json();

                        if (response.ok && data.success) {
                            this.showToast('Assessment saved successfully!');
                            this.resetForm();
                            this.view = 'dashboard';
                            setTimeout(() => window.location.reload(), 1000);
                        } else {
                            const errorMsg = data.message || 'Failed to save assessment';
                            console.error('Server error:', data);
                            this.showToast(errorMsg, 'error');
                        }
                    } catch (e) {
                        console.error('Save error:', e);
                        this.showToast('Network error: ' + e.message, 'error');
                    }
                }
            };
        }
    </script>
    @endpush
</x-manager-layout>
