<x-app-layout>
    <x-slot name="header">
        {{ __('Tutor Assessments') }}
    </x-slot>
    <x-slot name="title">{{ __('Director - Tutor Assessments') }}</x-slot>

    <div x-data="directorAssessmentApp()" x-init="init()" class="min-h-screen bg-slate-50 dark:bg-slate-900">
        {{-- Sub Navigation Tabs --}}
        <nav class="bg-white dark:bg-gray-800 shadow-sm sticky top-0 z-10">
            <div class="max-w-7xl mx-auto px-4">
                <div class="flex gap-1 overflow-x-auto">
                    <button @click="view = 'management'"
                            :class="view === 'management' ? 'text-sky-600 border-b-3 border-sky-500' : 'text-gray-500 hover:text-sky-600'"
                            class="relative px-5 py-4 font-medium transition-all whitespace-nowrap">
                        Management
                        <span x-show="stats.pending > 0" class="ml-2 px-2 py-0.5 text-xs bg-amber-500 text-white rounded-full" x-text="stats.pending"></span>
                    </button>
                    <button @click="view = 'reports'"
                            :class="view === 'reports' ? 'text-sky-600 border-b-3 border-sky-500' : 'text-gray-500 hover:text-sky-600'"
                            class="relative px-5 py-4 font-medium transition-all whitespace-nowrap">
                        Reports
                    </button>
                    <button @click="view = 'analytics'"
                            :class="view === 'analytics' ? 'text-sky-600 border-b-3 border-sky-500' : 'text-gray-500 hover:text-sky-600'"
                            class="relative px-5 py-4 font-medium transition-all whitespace-nowrap">
                        Analytics
                    </button>
                </div>
            </div>
        </nav>

        {{-- Flash Messages --}}
        @if(session('success'))
            <div class="max-w-7xl mx-auto px-4 mt-4">
                <div class="bg-emerald-100 dark:bg-emerald-900/30 border border-emerald-400 text-emerald-800 dark:text-emerald-400 px-6 py-4 rounded-xl">
                    {{ session('success') }}
                </div>
            </div>
        @endif

        @if(session('error'))
            <div class="max-w-7xl mx-auto px-4 mt-4">
                <div class="bg-red-100 dark:bg-red-900/30 border border-red-400 text-red-800 dark:text-red-400 px-6 py-4 rounded-xl">
                    {{ session('error') }}
                </div>
            </div>
        @endif

        {{-- Main Content --}}
        <main class="pb-8">
            {{-- Management Tab --}}
            <div x-show="view === 'management'" x-transition class="w-full max-w-7xl mx-auto p-4 sm:p-6">
                {{-- Sub-tabs: Pending / Completed --}}
                <div class="mb-6">
                    <div class="flex gap-4 border-b border-gray-200 dark:border-gray-700">
                        <button @click="managementTab = 'pending'"
                                :class="managementTab === 'pending' ? 'border-b-2 border-sky-500 text-sky-600' : 'text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white'"
                                class="px-4 py-3 font-medium transition-all">
                            Pending Review (<span x-text="stats.pending"></span>)
                        </button>
                        <button @click="managementTab = 'completed'"
                                :class="managementTab === 'completed' ? 'border-b-2 border-sky-500 text-sky-600' : 'text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white'"
                                class="px-4 py-3 font-medium transition-all">
                            Completed (<span x-text="stats.completed"></span>)
                        </button>
                    </div>
                </div>

                {{-- Header with Filters --}}
                <div class="mb-6">
                    <div class="flex flex-wrap justify-between items-start gap-4 mb-4">
                        <div>
                            <h2 class="text-2xl font-semibold text-gray-800 dark:text-white flex items-center gap-3">
                                <span x-text="managementTab === 'pending' ? 'Pending Reviews' : 'Completed Reviews'"></span>
                                <span x-show="managementTab === 'completed'" class="px-3 py-1 bg-emerald-500 text-white text-sm rounded-full" x-text="filteredCompletedAssessments.length"></span>
                            </h2>
                            <p class="text-gray-500 dark:text-gray-400 mt-1 text-sm" x-text="managementTab === 'pending' ? 'Review and approve manager-completed assessments' : 'View finalized assessments with applied actions'"></p>
                        </div>
                        <button @click="clearFilters()" class="bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 px-4 py-2 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-600 transition-all">
                            Clear Filters
                        </button>
                    </div>

                    {{-- Enhanced Filters for Completed Tab --}}
                    <div x-show="managementTab === 'completed'" class="bg-white dark:bg-gray-800 rounded-2xl shadow p-5">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div>
                                <label class="block text-sm font-medium mb-2 text-gray-700 dark:text-gray-300">Tutor</label>
                                <select x-model="filterTutor" class="w-full border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white px-3 py-2 rounded-lg text-sm">
                                    <option value="">All Tutors</option>
                                    @foreach($tutors as $tutor)
                                        <option value="{{ $tutor->id }}">{{ $tutor->first_name }} {{ $tutor->last_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium mb-2 text-gray-700 dark:text-gray-300">Month</label>
                                <input type="month" x-model="filterMonthInput" class="w-full border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white px-3 py-2 rounded-lg text-sm">
                            </div>
                            <div class="flex items-end">
                                <button @click="exportCSV()" class="w-full bg-emerald-500 hover:bg-emerald-600 text-white px-4 py-2 rounded-lg transition-all text-sm font-medium">
                                    <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                    </svg>
                                    Export
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Pending Assessments --}}
                <div x-show="managementTab === 'pending'" class="space-y-4">
                    @forelse($assessments->filter(fn($a) => in_array($a->status, ['approved-by-manager', 'pending_review'])) as $assessment)
                        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow p-5 border-l-4 border-amber-400">
                            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                                {{-- Left: Assessment Info --}}
                                <div class="lg:col-span-2">
                                    <div class="flex items-start justify-between mb-3">
                                        <div>
                                            <div class="font-semibold text-gray-800 dark:text-white text-lg flex items-center gap-2 flex-wrap">
                                                Tutor: {{ $assessment->tutor->first_name ?? 'Unknown' }} {{ $assessment->tutor->last_name ?? '' }}
                                                @if($assessment->is_stand_in)
                                                    <span class="px-2 py-0.5 text-xs font-semibold bg-purple-500 text-white rounded-full">Stand-in</span>
                                                @endif
                                            </div>
                                            @if($assessment->is_stand_in && $assessment->originalTutor)
                                                <div class="text-purple-600 dark:text-purple-400 text-xs mt-1">
                                                    Standing in for: {{ $assessment->originalTutor->first_name }} {{ $assessment->originalTutor->last_name }}
                                                </div>
                                            @endif
                                            <div class="text-gray-500 dark:text-gray-400 text-sm mt-1">
                                                {{ $assessment->assessment_period }} @if($assessment->assessment_date) · {{ $assessment->assessment_date->format('d M Y') }} @endif
                                            </div>
                                            @if($assessment->total_penalty_deductions > 0)
                                                <div class="mt-1 text-sm font-medium text-red-600 dark:text-red-400">
                                                    Penalty Deductions: ₦{{ number_format($assessment->total_penalty_deductions) }}
                                                    <span class="text-xs text-gray-400 ml-2">(Punctuality: ₦{{ number_format($assessment->punctuality_penalty ?? 0) }} | Video: ₦{{ number_format($assessment->video_penalty ?? 0) }})</span>
                                                </div>
                                            @endif
                                            @if($assessment->hasUnacceptableConduct())
                                                <div class="mt-2 p-2 bg-red-100 dark:bg-red-900/30 border border-red-500 rounded-lg">
                                                    <p class="text-red-700 dark:text-red-400 font-bold text-sm">⚠️ UNACCEPTABLE CONDUCT FLAGGED</p>
                                                </div>
                                            @endif
                                        </div>
                                        <span class="px-3 py-1 text-xs font-semibold bg-amber-500 text-white rounded-full">Pending Review</span>
                                    </div>

                                    {{-- Performance Score --}}
                                    @if($assessment->performance_score)
                                        <div class="mb-4 p-4 bg-gray-50 dark:bg-gray-700/50 rounded-xl">
                                            <div class="text-sm text-gray-500 dark:text-gray-400 mb-1">Performance Score</div>
                                            <div class="text-3xl font-bold {{ $assessment->performance_score >= 70 ? 'text-emerald-600' : ($assessment->performance_score >= 50 ? 'text-amber-600' : 'text-red-600') }}">
                                                {{ $assessment->performance_score }}%
                                            </div>
                                        </div>
                                    @endif

                                    {{-- Ratings --}}
                                    <div class="flex flex-wrap gap-3 mb-4">
                                        @if($assessment->professionalism_rating)
                                            <div class="px-3 py-2 bg-blue-50 dark:bg-blue-900/30 rounded-lg">
                                                <span class="text-xs text-blue-600 dark:text-blue-400">Professionalism</span>
                                                <div class="font-bold text-blue-800 dark:text-blue-200">{{ $assessment->professionalism_rating }}/5</div>
                                            </div>
                                        @endif
                                        @if($assessment->communication_rating)
                                            <div class="px-3 py-2 bg-purple-50 dark:bg-purple-900/30 rounded-lg">
                                                <span class="text-xs text-purple-600 dark:text-purple-400">Communication</span>
                                                <div class="font-bold text-purple-800 dark:text-purple-200">{{ $assessment->communication_rating }}/5</div>
                                            </div>
                                        @endif
                                        @if($assessment->punctuality_rating)
                                            <div class="px-3 py-2 bg-teal-50 dark:bg-teal-900/30 rounded-lg">
                                                <span class="text-xs text-teal-600 dark:text-teal-400">Punctuality</span>
                                                <div class="font-bold text-teal-800 dark:text-teal-200">{{ $assessment->punctuality_rating }}/5</div>
                                            </div>
                                        @endif
                                    </div>

                                    {{-- Strengths & Weaknesses --}}
                                    @if($assessment->strengths)
                                        <div class="mb-3 p-3 bg-emerald-50 dark:bg-emerald-900/20 rounded-lg border border-emerald-200 dark:border-emerald-800/30">
                                            <div class="font-semibold text-emerald-800 dark:text-emerald-300 mb-1 text-sm">Strengths:</div>
                                            <div class="text-emerald-700 dark:text-emerald-200 text-sm">{{ $assessment->strengths }}</div>
                                        </div>
                                    @endif

                                    @if($assessment->weaknesses)
                                        <div class="mb-3 p-3 bg-orange-50 dark:bg-orange-900/20 rounded-lg border border-orange-200 dark:border-orange-800/30">
                                            <div class="font-semibold text-orange-800 dark:text-orange-300 mb-1 text-sm">Areas for Improvement:</div>
                                            <div class="text-orange-700 dark:text-orange-200 text-sm">{{ $assessment->weaknesses }}</div>
                                        </div>
                                    @endif

                                    @if($assessment->recommendations)
                                        <div class="mb-3 p-3 bg-blue-50 dark:bg-blue-900/20 rounded-lg border border-blue-200 dark:border-blue-800/30">
                                            <div class="font-semibold text-blue-800 dark:text-blue-300 mb-1 text-sm">Manager Recommendations:</div>
                                            <div class="text-blue-700 dark:text-blue-200 text-sm">{{ $assessment->recommendations }}</div>
                                        </div>
                                    @endif

                                    @if($assessment->manager_comment)
                                        <div class="p-3 bg-purple-50 dark:bg-purple-900/20 rounded-lg border border-purple-200 dark:border-purple-800/30">
                                            <div class="font-semibold text-purple-800 dark:text-purple-300 mb-1 text-sm">Manager Comment:</div>
                                            <div class="text-purple-700 dark:text-purple-200 text-sm">{{ $assessment->manager_comment }}</div>
                                        </div>
                                    @endif
                                </div>

                                {{-- Right: Director Actions --}}
                                <div class="lg:border-l lg:border-gray-200 dark:border-gray-700 lg:pl-6">
                                    <h4 class="font-semibold text-gray-800 dark:text-white mb-4">Director Action</h4>
                                    
                                    <form action="{{ route('director.assessments.approve', $assessment) }}" method="POST">
                                        @csrf
                                        <div class="mb-4">
                                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Director's Comment</label>
                                            <textarea name="director_comment" rows="4" placeholder="Add your remarks..." 
                                                      class="w-full border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg p-3 text-sm focus:ring-2 focus:ring-[#4F46E5] focus:border-sky-500"></textarea>
                                        </div>

                                        <div class="space-y-2">
                                            <button type="submit" class="w-full py-2.5 px-4 bg-gradient-to-r from-emerald-500 to-emerald-600 text-white rounded-lg hover:shadow-lg transition-all font-medium text-sm flex items-center justify-center gap-2">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                                </svg>
                                                Approve Assessment
                                            </button>
                                            <a href="{{ route('director.assessments.show', $assessment) }}" class="w-full py-2.5 px-4 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-600 transition-all font-medium text-sm flex items-center justify-center gap-2">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                                </svg>
                                                View Details
                                            </a>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow p-8 text-center">
                            <div class="text-6xl mb-4">📋</div>
                            <h3 class="text-xl font-semibold text-gray-700 dark:text-gray-300 mb-2">No Pending Reviews</h3>
                            <p class="text-gray-500 dark:text-gray-400">Manager-completed assessments will appear here for your review</p>
                        </div>
                    @endforelse
                </div>

                {{-- Completed Assessments --}}
                <div x-show="managementTab === 'completed'" class="space-y-4 mt-6">
                    <template x-for="assessment in filteredCompletedAssessments" :key="assessment.id">
                        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow p-5 border-l-4 border-emerald-500">
                            <div class="flex flex-wrap justify-between items-start gap-4">
                                <div class="flex-1">
                                    <div class="flex items-center gap-3 mb-2 flex-wrap">
                                        <div class="font-semibold text-gray-800 dark:text-white text-lg" x-text="assessment.tutor_name"></div>
                                        <span x-show="assessment.is_stand_in" class="px-3 py-1 text-xs font-semibold bg-purple-500 text-white rounded-full">Stand-in</span>
                                        <span class="px-3 py-1 text-xs font-semibold bg-emerald-500 text-white rounded-full">Finalized</span>
                                    </div>
                                    <div x-show="assessment.is_stand_in && assessment.original_tutor_name" class="text-xs text-purple-600 dark:text-purple-400 mb-1">
                                        Standing in for: <span x-text="assessment.original_tutor_name"></span>
                                    </div>
                                    <div class="text-gray-500 dark:text-gray-400 text-sm">
                                        <span x-text="assessment.assessment_month"></span>
                                        <template x-if="assessment.approved_at">
                                            <span> · Approved <span x-text="assessment.approved_at"></span></span>
                                        </template>
                                    </div>
                                    <div class="text-gray-500 dark:text-gray-400 text-xs mt-1" x-show="assessment.student_name">
                                        Student: <span x-text="assessment.student_name"></span>
                                    </div>

                                    <template x-if="assessment.performance_score">
                                        <div class="mt-3">
                                            <span class="text-2xl font-bold text-emerald-600" x-text="assessment.performance_score + '%'"></span>
                                            <span class="text-sm text-gray-500 ml-2">Performance Score</span>
                                        </div>
                                    </template>

                                    <template x-if="assessment.director_comment">
                                        <div class="mt-4 p-3 bg-yellow-50 dark:bg-yellow-900/20 rounded-lg border border-yellow-200 dark:border-yellow-800/30">
                                            <div class="font-semibold text-yellow-800 dark:text-yellow-300 mb-1 text-sm">Director's Remarks:</div>
                                            <div class="text-yellow-700 dark:text-yellow-200 text-sm" x-text="assessment.director_comment"></div>
                                        </div>
                                    </template>
                                </div>

                                <div class="flex gap-2">
                                    <a :href="'/director/assessments/' + assessment.id" class="px-4 py-2 bg-emerald-100 dark:bg-emerald-900/30 text-emerald-700 dark:text-emerald-300 rounded-lg hover:bg-emerald-200 dark:hover:bg-emerald-900/50 transition-colors text-sm font-medium">
                                        View Report
                                    </a>
                                    <button @click="generateReportCard(assessment.id)" class="px-4 py-2 bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-300 rounded-lg hover:bg-blue-200 dark:hover:bg-blue-900/50 transition-colors text-sm font-medium">
                                        Print Report
                                    </button>
                                </div>
                            </div>
                        </div>
                    </template>

                    <template x-if="filteredCompletedAssessments.length === 0">
                        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow p-8 text-center">
                            <div class="text-6xl mb-4">✅</div>
                            <h3 class="text-xl font-semibold text-gray-700 dark:text-gray-300 mb-2">No Completed Reviews Found</h3>
                            <p class="text-gray-500 dark:text-gray-400">Try adjusting your filters or check back later</p>
                        </div>
                    </template>
                </div>
            </div>

            {{-- Reports Tab --}}
            <div x-show="view === 'reports'" x-transition class="w-full max-w-7xl mx-auto p-4 sm:p-6">
                <div class="mb-6">
                    <h2 class="text-2xl font-semibold text-gray-800 dark:text-white">Tutor Performance Reports</h2>
                    <p class="text-gray-500 dark:text-gray-400 mt-1">Generate and view performance report cards for tutors</p>
                </div>

                {{-- Filters & Export --}}
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow p-5 mb-6">
                    <div class="flex flex-wrap justify-between items-center gap-4 mb-4">
                        <h3 class="font-semibold text-gray-800 dark:text-white">Filters & Export</h3>
                        <button @click="exportReportsCSV()" class="px-4 py-2 bg-emerald-500 hover:bg-emerald-600 text-white rounded-lg transition-all text-sm font-medium">
                            Export to CSV
                        </button>
                    </div>

                    {{-- Time Period Buttons --}}
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-600 dark:text-gray-400 mb-2">Time Period</label>
                        <div class="flex flex-wrap gap-2">
                            <button @click="reportPeriod = '7days'" :class="reportPeriod === '7days' ? 'bg-sky-500 text-white' : 'bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300'" class="px-4 py-2 rounded-lg text-sm font-medium transition-all">Last 7 Days</button>
                            <button @click="reportPeriod = '30days'" :class="reportPeriod === '30days' ? 'bg-sky-500 text-white' : 'bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300'" class="px-4 py-2 rounded-lg text-sm font-medium transition-all">Last 30 Days</button>
                            <button @click="reportPeriod = '3months'" :class="reportPeriod === '3months' ? 'bg-sky-500 text-white' : 'bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300'" class="px-4 py-2 rounded-lg text-sm font-medium transition-all">Last 3 Months</button>
                            <button @click="reportPeriod = 'all'" :class="reportPeriod === 'all' ? 'bg-sky-500 text-white' : 'bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300'" class="px-4 py-2 rounded-lg text-sm font-medium transition-all">All Time</button>
                        </div>
                    </div>

                    {{-- Filter Dropdowns --}}
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-600 dark:text-gray-400 mb-1">Year</label>
                            <select x-model="reportYear" class="w-full border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white px-3 py-2 rounded-lg">
                                <option value="{{ date('Y') }}">{{ date('Y') }}</option>
                                <option value="{{ date('Y') - 1 }}">{{ date('Y') - 1 }}</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-600 dark:text-gray-400 mb-1">Month</label>
                            <select x-model="reportMonth" class="w-full border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white px-3 py-2 rounded-lg">
                                <option value="">All Months</option>
                                @for($m = 1; $m <= 12; $m++)
                                    <option value="{{ str_pad($m, 2, '0', STR_PAD_LEFT) }}">{{ date('F', mktime(0, 0, 0, $m, 1)) }}</option>
                                @endfor
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-600 dark:text-gray-400 mb-1">Tutor</label>
                            <select x-model="reportTutor" class="w-full border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white px-3 py-2 rounded-lg">
                                <option value="">All Tutors</option>
                                @foreach($tutors as $tutor)
                                    <option value="{{ $tutor->id }}">{{ $tutor->first_name }} {{ $tutor->last_name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                {{-- Generate Report Card --}}
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow p-5 mb-6">
                    <h3 class="font-semibold text-gray-800 dark:text-white mb-4">Generate Tutor Report Card</h3>
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
                        <div>
                            <label class="block text-sm font-medium text-gray-600 dark:text-gray-400 mb-1">Select Tutor</label>
                            <select x-model="generateTutor" class="w-full border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white px-3 py-2 rounded-lg">
                                <option value="">Select Tutor</option>
                                @foreach($tutors as $tutor)
                                    <option value="{{ $tutor->id }}">{{ $tutor->first_name }} {{ $tutor->last_name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-600 dark:text-gray-400 mb-1">From Date</label>
                            <input type="date" x-model="generateFromDate" class="w-full border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white px-3 py-2 rounded-lg">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-600 dark:text-gray-400 mb-1">To Date</label>
                            <input type="date" x-model="generateToDate" class="w-full border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white px-3 py-2 rounded-lg">
                        </div>
                        <button @click="generatePDF()" class="px-6 py-2.5 bg-sky-500 hover:bg-sky-600 text-white rounded-lg transition-all font-medium">
                            Generate PDF
                        </button>
                    </div>
                </div>

                {{-- Assessment History --}}
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow p-5">
                    <h3 class="font-semibold text-gray-800 dark:text-white mb-4">Assessment History - Finalized Monthly Reports</h3>

                    <div class="text-sm text-gray-500 dark:text-gray-400 mb-4">
                        Showing {{ $assessments->where('status', 'approved-by-director')->count() }} of {{ $stats['completed'] ?? 0 }} monthly assessments
                    </div>

                    <div class="space-y-3">
                        @forelse($assessments->filter(fn($a) => $a->status === 'approved-by-director')->take(10) as $assessment)
                            <div class="flex items-center justify-between p-4 bg-gray-50 dark:bg-gray-700/50 rounded-xl hover:bg-gray-100 dark:hover:bg-gray-700 transition-all">
                                <div class="flex items-center gap-4">
                                    <div class="w-12 h-12 rounded-full bg-gradient-to-r from-sky-500 to-sky-600 flex items-center justify-center text-white font-bold text-lg">
                                        {{ strtoupper(substr($assessment->tutor->first_name ?? 'U', 0, 1)) }}
                                    </div>
                                    <div>
                                        <div class="font-medium text-gray-800 dark:text-white">
                                            {{ $assessment->tutor->first_name ?? 'Unknown' }} {{ $assessment->tutor->last_name ?? '' }}
                                        </div>
                                        <div class="text-sm text-gray-500 dark:text-gray-400">
                                            {{ $assessment->assessment_month }}
                                            @if($assessment->student)
                                                - {{ $assessment->student->first_name }} {{ $assessment->student->last_name }}
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="flex items-center gap-4">
                                    @if($assessment->performance_score)
                                        <div class="text-right">
                                            <div class="text-xl font-bold {{ $assessment->performance_score >= 70 ? 'text-emerald-600' : ($assessment->performance_score >= 50 ? 'text-amber-600' : 'text-red-600') }}">
                                                {{ $assessment->performance_score }}%
                                            </div>
                                            <div class="text-xs text-gray-500">Score</div>
                                        </div>
                                    @endif
                                    <div class="flex gap-2">
                                        <a href="{{ route('director.assessments.show', $assessment) }}" class="p-2 bg-sky-100 dark:bg-sky-900/30 text-sky-600 dark:text-sky-400 rounded-lg hover:bg-sky-200 dark:hover:bg-sky-900/50 transition-all">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                            </svg>
                                        </a>
                                        <button @click="generateReportCard({{ $assessment->id }})" class="p-2 bg-emerald-100 dark:bg-emerald-900/30 text-emerald-600 dark:text-emerald-400 rounded-lg hover:bg-emerald-200 dark:hover:bg-emerald-900/50 transition-all">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-8 text-gray-500 dark:text-gray-400">
                                No finalized assessments match your filters
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>

            {{-- Analytics Tab --}}
            <div x-show="view === 'analytics'" x-transition class="w-full max-w-7xl mx-auto p-4 sm:p-6">
                <h2 class="text-2xl font-semibold text-gray-800 dark:text-white mb-6">Analytics & Insights</h2>

                {{-- Summary Cards --}}
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
                    <div class="bg-gradient-to-br from-[#4F46E5] to-[#818CF8] rounded-2xl p-5 text-white shadow-lg">
                        <div class="text-sm opacity-90 mb-1">Total Assessments</div>
                        <div class="text-3xl font-bold">{{ $stats['total'] ?? 0 }}</div>
                        <div class="text-xs opacity-75 mt-1">all time</div>
                    </div>
                    <div class="bg-gradient-to-br from-emerald-500 to-emerald-600 rounded-2xl p-5 text-white shadow-lg">
                        <div class="text-sm opacity-90 mb-1">Completed</div>
                        <div class="text-3xl font-bold">{{ $stats['completed'] ?? 0 }}</div>
                        <div class="text-xs opacity-75 mt-1">{{ $stats['pending'] ?? 0 }} pending</div>
                    </div>
                    <div class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-2xl p-5 text-white shadow-lg">
                        <div class="text-sm opacity-90 mb-1">Average Score</div>
                        <div class="text-3xl font-bold">{{ number_format($stats['avg_score'] ?? 0, 1) }}%</div>
                        <div class="text-xs opacity-75 mt-1">performance</div>
                    </div>
                    <div class="bg-gradient-to-br from-amber-500 to-amber-600 rounded-2xl p-5 text-white shadow-lg">
                        <div class="text-sm opacity-90 mb-1">Active Tutors</div>
                        <div class="text-3xl font-bold">{{ $tutors->count() }}</div>
                        <div class="text-xs opacity-75 mt-1">being assessed</div>
                    </div>
                </div>

                {{-- Chart Selection --}}
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow p-4 mb-6">
                    <div class="flex flex-wrap gap-2">
                        <button @click="selectedChart = 'overview'"
                                :class="selectedChart === 'overview' ? 'bg-sky-500 text-white' : 'bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-600'"
                                class="px-4 py-2 rounded-lg text-sm font-medium transition-all">
                            Performance Trend
                        </button>
                        <button @click="selectedChart = 'tutors'"
                                :class="selectedChart === 'tutors' ? 'bg-sky-500 text-white' : 'bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-600'"
                                class="px-4 py-2 rounded-lg text-sm font-medium transition-all">
                            Tutor Comparison
                        </button>
                        <button @click="selectedChart = 'criteria'"
                                :class="selectedChart === 'criteria' ? 'bg-sky-500 text-white' : 'bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-600'"
                                class="px-4 py-2 rounded-lg text-sm font-medium transition-all">
                            Criteria Breakdown
                        </button>
                    </div>
                </div>

                {{-- Charts Container --}}
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow p-6 mb-6">
                    <div style="height: 400px;" class="relative">
                        <div x-show="selectedChart === 'overview'" class="h-full relative">
                            <canvas id="performanceTrendChart" class="h-full w-full"></canvas>
                            <div id="noDataOverview" class="hidden absolute inset-0 flex items-center justify-center bg-white dark:bg-gray-800">
                                <div class="text-center">
                                    <svg class="w-16 h-16 mx-auto text-gray-300 dark:text-gray-600 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                                    </svg>
                                    <p class="text-gray-500 dark:text-gray-400 text-sm">No performance data available yet</p>
                                    <p class="text-xs text-gray-400 dark:text-gray-500 mt-1">Complete and approve assessments to see trends</p>
                                </div>
                            </div>
                        </div>
                        <div x-show="selectedChart === 'tutors'" class="h-full relative">
                            <canvas id="tutorComparisonChart" class="h-full w-full"></canvas>
                            <div id="noDataTutors" class="hidden absolute inset-0 flex items-center justify-center bg-white dark:bg-gray-800">
                                <div class="text-center">
                                    <svg class="w-16 h-16 mx-auto text-gray-300 dark:text-gray-600 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                    </svg>
                                    <p class="text-gray-500 dark:text-gray-400 text-sm">No tutor comparison data available</p>
                                    <p class="text-xs text-gray-400 dark:text-gray-500 mt-1">Complete and approve assessments to compare tutors</p>
                                </div>
                            </div>
                        </div>
                        <div x-show="selectedChart === 'criteria'" class="h-full relative">
                            <canvas id="criteriaChart" class="h-full w-full"></canvas>
                            <div id="noDataCriteria" class="hidden absolute inset-0 flex items-center justify-center bg-white dark:bg-gray-800">
                                <div class="text-center">
                                    <svg class="w-16 h-16 mx-auto text-gray-300 dark:text-gray-600 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                                    </svg>
                                    <p class="text-gray-500 dark:text-gray-400 text-sm">No criteria breakdown available</p>
                                    <p class="text-xs text-gray-400 dark:text-gray-500 mt-1">Complete and approve assessments to see criteria scores</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Top & Bottom Performers --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    {{-- Top Performers --}}
                    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow p-5">
                        <h3 class="text-lg font-semibold text-gray-800 dark:text-white mb-4 flex items-center gap-2">
                            <span class="text-2xl">🏆</span>
                            Top Performers
                        </h3>
                        <div class="space-y-3">
                            @php
                                $topPerformers = $assessments->where('status', 'approved-by-director')
                                    ->sortByDesc('performance_score')
                                    ->take(3);
                            @endphp
                            @forelse($topPerformers as $assessment)
                                <div class="flex items-center justify-between p-3 bg-emerald-50 dark:bg-emerald-900/20 border border-emerald-200 dark:border-emerald-800/30 rounded-lg">
                                    <div>
                                        <div class="font-medium text-gray-800 dark:text-white">{{ $assessment->tutor->first_name ?? 'Unknown' }} {{ $assessment->tutor->last_name ?? '' }}</div>
                                        <div class="text-xs text-gray-600 dark:text-gray-400">{{ $assessment->assessment_month }}</div>
                                    </div>
                                    <div class="text-right">
                                        <div class="text-xl font-bold text-emerald-600">{{ $assessment->performance_score ?? 0 }}%</div>
                                    </div>
                                </div>
                            @empty
                                <div class="text-center py-4 text-gray-500">No data yet</div>
                            @endforelse
                        </div>
                    </div>

                    {{-- Needs Improvement --}}
                    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow p-5">
                        <h3 class="text-lg font-semibold text-gray-800 dark:text-white mb-4 flex items-center gap-2">
                            <span class="text-2xl">📈</span>
                            Needs Improvement
                        </h3>
                        <div class="space-y-3">
                            @php
                                $needsImprovement = $assessments->where('status', 'approved-by-director')
                                    ->where('performance_score', '<', 70)
                                    ->sortBy('performance_score')
                                    ->take(3);
                            @endphp
                            @forelse($needsImprovement as $assessment)
                                <div class="flex items-center justify-between p-3 bg-orange-50 dark:bg-orange-900/20 border border-orange-200 dark:border-orange-800/30 rounded-lg">
                                    <div>
                                        <div class="font-medium text-gray-800 dark:text-white">{{ $assessment->tutor->first_name ?? 'Unknown' }} {{ $assessment->tutor->last_name ?? '' }}</div>
                                        <div class="text-xs text-gray-600 dark:text-gray-400">{{ $assessment->assessment_month }}</div>
                                    </div>
                                    <div class="text-right">
                                        <div class="text-xl font-bold text-orange-600">{{ $assessment->performance_score ?? 0 }}%</div>
                                    </div>
                                </div>
                            @empty
                                <div class="text-center py-4 text-gray-500">All tutors performing well!</div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>

        </main>

        {{-- Footer Stats --}}
        <footer class="py-5 bg-white dark:bg-gray-800 border-t dark:border-gray-700 shadow-sm">
            <div class="max-w-7xl mx-auto text-sm text-gray-500 dark:text-gray-400 text-center">
                {{ $stats['pending'] ?? 0 }} pending · {{ $stats['completed'] ?? 0 }} completed · {{ $tutors->count() }} tutors · Live-synced
            </div>
        </footer>

        {{-- Toast Notification --}}
        <div x-show="toast.show" x-transition
             :class="toast.type === 'success' ? 'bg-emerald-500' : 'bg-red-500'"
             class="fixed bottom-6 right-6 text-white px-5 py-3 rounded-xl shadow-lg z-50">
            <span x-text="toast.message"></span>
        </div>
    </div>

    {{-- Chart.js --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>

    @push('scripts')
    <script>
        function directorAssessmentApp() {
            return {
                view: 'management',
                managementTab: 'pending',
                selectedChart: 'overview',
                
                stats: {
                    total: {{ $stats['total'] ?? 0 }},
                    pending: {{ $stats['pending'] ?? 0 }},
                    completed: {{ $stats['completed'] ?? 0 }},
                    avg_score: {{ $stats['avg_score'] ?? 0 }}
                },
                
                filterTutor: '',
                filterMonth: '',
                filterMonthInput: '',

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
                            assessment_month_display: {!! json_encode($assessment->assessment_period) !!},
                            assessment_date: '{{ $assessment->assessment_date ? $assessment->assessment_date->format("d M Y") : "" }}',
                            week: {{ $assessment->week ?? 'null' }},
                            year: {{ $assessment->year ?? 'null' }},
                            class_date: '{{ $assessment->class_date ?? '' }}',
                            performance_score: {{ $assessment->performance_score ?? 'null' }},
                            approved_at: '{{ $assessment->approved_by_director_at ? $assessment->approved_by_director_at->format("M j, Y") : "" }}',
                            director_comment: {!! json_encode($assessment->director_comment ?? '') !!},
                            is_stand_in: {{ $assessment->is_stand_in ? 'true' : 'false' }},
                            original_tutor_name: {!! json_encode($assessment->is_stand_in && $assessment->originalTutor ? ($assessment->originalTutor->first_name . ' ' . $assessment->originalTutor->last_name) : '') !!},
                            total_penalty_deductions: {{ $assessment->total_penalty_deductions ?? 0 }},
                            punctuality_penalty: {{ $assessment->punctuality_penalty ?? 0 }},
                            video_penalty: {{ $assessment->video_penalty ?? 0 }},
                            has_unacceptable_conduct: {{ $assessment->hasUnacceptableConduct() ? 'true' : 'false' }}
                        },
                    @endforeach
                ],

                get filteredCompletedAssessments() {
                    return this.completedAssessments.filter(assessment => {
                        // Filter by tutor
                        if (this.filterTutor && assessment.tutor_id != this.filterTutor) {
                            return false;
                        }

                        // Filter by month (YYYY-MM format from assessment_month)
                        if (this.filterMonthInput && assessment.assessment_month !== this.filterMonthInput) {
                            return false;
                        }

                        return true;
                    });
                },

                // Reports tab
                reportPeriod: 'all',
                reportYear: '{{ date("Y") }}',
                reportMonth: '',
                reportTutor: '',
                generateTutor: '',
                generateFromDate: '',
                generateToDate: '',

                toast: {
                    show: false,
                    message: '',
                    type: 'success'
                },

                charts: {},

                init() {
                    this.$watch('view', (value) => {
                        if (value === 'analytics') {
                            this.$nextTick(() => this.initCharts());
                        }
                    });
                    
                    this.$watch('selectedChart', () => {
                        this.$nextTick(() => this.initCharts());
                    });
                },
                
                showToast(message, type = 'success') {
                    this.toast = { show: true, message, type };
                    setTimeout(() => { this.toast.show = false; }, 3000);
                },
                
                clearFilters() {
                    this.filterTutor = '';
                    this.filterMonth = '';
                    this.filterMonthInput = '';
                },
                
                exportCSV() {
                    // Export via server-side route
                    window.location.href = '{{ route("director.assessments.export") }}';
                    this.showToast('CSV export started');
                },
                
                generateReportCard(assessmentId) {
                    window.open(`{{ url('director/assessments') }}/${assessmentId}/print`, '_blank');
                },

                exportReportsCSV() {
                    window.location.href = '{{ route("director.assessments.export") }}';
                    this.showToast('CSV export started');
                },

                generatePDF() {
                    if (!this.generateTutor) {
                        this.showToast('Please select a tutor', 'error');
                        return;
                    }
                    let url = `{{ url('director/assessments') }}?tutor=${this.generateTutor}&format=pdf`;
                    if (this.generateFromDate) url += `&from=${this.generateFromDate}`;
                    if (this.generateToDate) url += `&to=${this.generateToDate}`;
                    window.open(url, '_blank');
                    this.showToast('Generating PDF...');
                },

                initCharts() {
                    // Destroy existing charts
                    Object.values(this.charts).forEach(chart => {
                        if (chart) chart.destroy();
                    });
                    this.charts = {};

                    const chartData = @json($chartData ?? []);

                    // Performance Trend Chart
                    if (this.selectedChart === 'overview') {
                        const ctx = document.getElementById('performanceTrendChart');
                        if (ctx) {
                            // Check if we have actual data
                            const hasData = chartData.months && chartData.months.length > 0 && chartData.scores && chartData.scores.length > 0;

                            if (!hasData) {
                                document.getElementById('noDataOverview').classList.remove('hidden');
                                ctx.style.display = 'none';
                                return;
                            } else {
                                document.getElementById('noDataOverview').classList.add('hidden');
                                ctx.style.display = 'block';
                            }

                            this.charts.performance = new Chart(ctx, {
                                type: 'line',
                                data: {
                                    labels: chartData.months,
                                    datasets: [{
                                        label: 'Average Score',
                                        data: chartData.scores,
                                        borderColor: '#0ea5e9',
                                        backgroundColor: 'rgba(14, 165, 233, 0.1)',
                                        fill: true,
                                        tension: 0.4
                                    }]
                                },
                                options: {
                                    responsive: true,
                                    maintainAspectRatio: false,
                                    plugins: {
                                        title: {
                                            display: true,
                                            text: 'Performance Trend Over Time',
                                            font: { size: 16, weight: 'bold' }
                                        }
                                    },
                                    scales: {
                                        y: {
                                            beginAtZero: true,
                                            max: 100
                                        }
                                    }
                                }
                            });
                        }
                    }

                    // Tutor Comparison Chart
                    if (this.selectedChart === 'tutors') {
                        const ctx = document.getElementById('tutorComparisonChart');
                        if (ctx) {
                            const hasData = chartData.tutorNames && chartData.tutorNames.length > 0 && chartData.tutorScores && chartData.tutorScores.length > 0;

                            if (!hasData) {
                                document.getElementById('noDataTutors').classList.remove('hidden');
                                ctx.style.display = 'none';
                                return;
                            } else {
                                document.getElementById('noDataTutors').classList.add('hidden');
                                ctx.style.display = 'block';
                            }

                            this.charts.tutors = new Chart(ctx, {
                                type: 'bar',
                                data: {
                                    labels: chartData.tutorNames,
                                    datasets: [{
                                        label: 'Average Score',
                                        data: chartData.tutorScores,
                                        backgroundColor: [
                                            '#10b981', '#0ea5e9', '#8b5cf6', '#f59e0b', '#ef4444',
                                            '#06b6d4', '#ec4899', '#14b8a6', '#f97316', '#6366f1'
                                        ]
                                    }]
                                },
                                options: {
                                    responsive: true,
                                    maintainAspectRatio: false,
                                    plugins: {
                                        title: {
                                            display: true,
                                            text: 'Tutor Performance Comparison',
                                            font: { size: 16, weight: 'bold' }
                                        },
                                        legend: { display: false }
                                    },
                                    scales: {
                                        y: {
                                            beginAtZero: true,
                                            max: 100
                                        }
                                    }
                                }
                            });
                        }
                    }

                    // Criteria Breakdown Chart
                    if (this.selectedChart === 'criteria') {
                        const ctx = document.getElementById('criteriaChart');
                        if (ctx) {
                            const hasData = chartData.criteriaNames && chartData.criteriaNames.length > 0 && chartData.criteriaScores && chartData.criteriaScores.length > 0;

                            if (!hasData) {
                                document.getElementById('noDataCriteria').classList.remove('hidden');
                                ctx.style.display = 'none';
                                return;
                            } else {
                                document.getElementById('noDataCriteria').classList.add('hidden');
                                ctx.style.display = 'block';
                            }

                            const criteriaLabels = chartData.criteriaNames;
                            const criteriaColors = ['#10b981', '#0ea5e9', '#8b5cf6', '#f59e0b', '#ef4444', '#06b6d4', '#ec4899', '#14b8a6'];
                            this.charts.criteria = new Chart(ctx, {
                                type: 'bar',
                                data: {
                                    labels: criteriaLabels,
                                    datasets: [{
                                        label: 'Average Rating (out of 5)',
                                        data: chartData.criteriaScores,
                                        backgroundColor: criteriaColors.slice(0, criteriaLabels.length),
                                        borderWidth: 1,
                                        borderRadius: 4
                                    }]
                                },
                                options: {
                                    responsive: true,
                                    maintainAspectRatio: false,
                                    indexAxis: 'y',
                                    plugins: {
                                        title: {
                                            display: true,
                                            text: 'Average Score by Criterion',
                                            font: { size: 16, weight: 'bold' }
                                        },
                                        legend: {
                                            display: false
                                        }
                                    },
                                    scales: {
                                        x: {
                                            beginAtZero: true,
                                            max: 5,
                                            ticks: {
                                                stepSize: 1
                                            }
                                        }
                                    }
                                }
                            });
                        }
                    }
                }
            };
        }
    </script>
    @endpush
</x-app-layout>
