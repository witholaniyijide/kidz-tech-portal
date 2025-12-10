<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-2xl text-white">{{ __('Tutor Assessments') }}</h2>
    </x-slot>
    <x-slot name="title">{{ __('Tutor Assessments') }}</x-slot>

    <div x-data="assessmentApp()" x-init="init()" class="min-h-screen bg-slate-50 dark:bg-slate-900">
        {{-- Sub Navigation Tabs --}}
        <nav class="bg-white dark:bg-gray-800 shadow-sm sticky top-0 z-10">
            <div class="max-w-7xl mx-auto px-4">
                <div class="flex gap-1 overflow-x-auto">
                    <button @click="view = 'new'" 
                            :class="view === 'new' ? 'text-sky-600 border-b-3 border-sky-500' : 'text-gray-500 hover:text-sky-600'"
                            class="relative px-5 py-4 font-medium transition-all whitespace-nowrap">
                        New Assessment
                    </button>
                    <button @click="view = 'dashboard'" 
                            :class="view === 'dashboard' ? 'text-sky-600 border-b-3 border-sky-500' : 'text-gray-500 hover:text-sky-600'"
                            class="relative px-5 py-4 font-medium transition-all whitespace-nowrap">
                        Dashboard
                        <span x-show="stats.pending > 0" class="ml-2 px-2 py-0.5 text-xs bg-amber-500 text-white rounded-full" x-text="stats.pending"></span>
                    </button>
                    <button @click="view = 'management'" 
                            :class="view === 'management' ? 'text-sky-600 border-b-3 border-sky-500' : 'text-gray-500 hover:text-sky-600'"
                            class="relative px-5 py-4 font-medium transition-all whitespace-nowrap">
                        Management
                    </button>
                    <button @click="view = 'completed'" 
                            :class="view === 'completed' ? 'text-sky-600 border-b-3 border-sky-500' : 'text-gray-500 hover:text-sky-600'"
                            class="relative px-5 py-4 font-medium transition-all whitespace-nowrap">
                        Completed
                        <span x-show="stats.completed > 0" class="ml-2 px-2 py-0.5 text-xs bg-emerald-500 text-white rounded-full" x-text="stats.completed"></span>
                    </button>
                </div>
            </div>
        </nav>

        {{-- Main Content --}}
        <main class="pb-8">
            {{-- New Assessment Tab --}}
            <div x-show="view === 'new'" x-transition class="w-full max-w-6xl mx-auto p-4 sm:p-6">
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-6">
                    <div class="flex flex-wrap justify-between items-center gap-4 mb-6">
                        <div>
                            <h2 class="text-2xl font-semibold text-gray-800 dark:text-white" x-text="editing ? 'Edit Assessment' : 'New Assessment'"></h2>
                            <p class="text-gray-500 dark:text-gray-400 mt-1 text-sm">Split across 3 sessions – you may save partial progress and complete later</p>
                        </div>
                        <div class="flex gap-3 items-center">
                            <label class="text-gray-500 font-medium text-sm">Session</label>
                            <select x-model="session" class="border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white px-3 py-2 rounded-lg">
                                <option value="1">Session 1</option>
                                <option value="2">Session 2</option>
                                <option value="3">Session 3</option>
                            </select>
                        </div>
                    </div>

                    <form @submit.prevent="saveAssessment()">
                        {{-- Selection Grid --}}
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
                            {{-- Tutor --}}
                            <div>
                                <label class="block text-sm font-medium mb-2 text-gray-700 dark:text-gray-300">Tutor *</label>
                                <select x-model="formData.tutor_id" required class="w-full border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white px-3 py-2 rounded-lg focus:ring-2 focus:ring-sky-500 focus:border-sky-500">
                                    <option value="">Select tutor</option>
                                    @foreach($tutors as $tutor)
                                        <option value="{{ $tutor->id }}">{{ $tutor->first_name }} {{ $tutor->last_name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- Assessment Period --}}
                            <div>
                                <label class="block text-sm font-medium mb-2 text-gray-700 dark:text-gray-300">Assessment Period *</label>
                                <input type="text" x-model="formData.assessment_month" required placeholder="e.g., November 2024" 
                                       class="w-full border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white px-3 py-2 rounded-lg focus:ring-2 focus:ring-sky-500 focus:border-sky-500">
                            </div>

                            {{-- Year --}}
                            <div>
                                <label class="block text-sm font-medium mb-2 text-gray-700 dark:text-gray-300">Year</label>
                                <select x-model="formData.year" class="w-full border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white px-3 py-2 rounded-lg">
                                    <option value="{{ date('Y') - 1 }}">{{ date('Y') - 1 }}</option>
                                    <option value="{{ date('Y') }}" selected>{{ date('Y') }}</option>
                                    <option value="{{ date('Y') + 1 }}">{{ date('Y') + 1 }}</option>
                                </select>
                            </div>

                            {{-- Week --}}
                            <div>
                                <label class="block text-sm font-medium mb-2 text-gray-700 dark:text-gray-300">Week</label>
                                <input type="number" x-model="formData.week" min="1" max="53" placeholder="Week number"
                                       class="w-full border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white px-3 py-2 rounded-lg focus:ring-2 focus:ring-sky-500 focus:border-sky-500">
                            </div>
                        </div>

                        {{-- Criteria Selection --}}
                        <div class="mb-6">
                            <h3 class="font-semibold mb-3 text-gray-800 dark:text-white">Select Criteria to Assess</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                <template x-for="criterion in criteria" :key="criterion.id">
                                    <label :class="checkedCriteria[criterion.id] ? 'border-sky-500 bg-sky-50 dark:bg-sky-900/30' : 'border-gray-200 dark:border-gray-600 hover:border-sky-300'"
                                           class="flex items-center gap-3 p-3 border rounded-lg transition-all cursor-pointer">
                                        <input type="checkbox" 
                                               :value="criterion.id" 
                                               x-model="checkedCriteria[criterion.id]"
                                               class="w-4 h-4 text-sky-600 rounded">
                                        <span class="text-sm font-medium text-gray-700 dark:text-gray-300" x-text="criterion.name"></span>
                                    </label>
                                </template>
                            </div>
                        </div>

                        {{-- Ratings Section --}}
                        <div class="mb-6">
                            <h3 class="font-semibold mb-3 text-gray-800 dark:text-white">Provide Ratings</h3>
                            <div class="space-y-4">
                                <template x-for="criterion in criteria.filter(c => checkedCriteria[c.id])" :key="criterion.id">
                                    <div class="border border-gray-200 dark:border-gray-600 rounded-lg p-4 bg-gray-50 dark:bg-gray-700/50">
                                        <div class="font-medium mb-3 text-gray-800 dark:text-white" x-text="criterion.name"></div>
                                        <div class="flex gap-2 flex-wrap">
                                            <template x-for="opt in criterion.options" :key="opt">
                                                <button type="button"
                                                        @click="ratings[criterion.id] = opt"
                                                        :class="ratings[criterion.id] === opt ? 'bg-sky-600 text-white shadow-md' : 'bg-white dark:bg-gray-600 border border-gray-300 dark:border-gray-500 text-gray-700 dark:text-gray-200 hover:border-sky-400'"
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
                                          class="w-full border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-xl p-3 h-32 focus:ring-2 focus:ring-sky-500 focus:border-sky-500"></textarea>
                            </div>
                            <div>
                                <label class="block text-sm font-medium mb-2 text-gray-700 dark:text-gray-300">Areas for Improvement</label>
                                <textarea x-model="formData.weaknesses" placeholder="Notes for improvement areas..." 
                                          class="w-full border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-xl p-3 h-32 focus:ring-2 focus:ring-sky-500 focus:border-sky-500"></textarea>
                            </div>
                        </div>

                        {{-- Recommendations --}}
                        <div class="mb-6">
                            <label class="block text-sm font-medium mb-2 text-gray-700 dark:text-gray-300">Recommendations</label>
                            <textarea x-model="formData.recommendations" placeholder="Any recommendations for the tutor..." 
                                      class="w-full border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-xl p-3 h-24 focus:ring-2 focus:ring-sky-500 focus:border-sky-500"></textarea>
                        </div>

                        {{-- Performance Score --}}
                        <div class="mb-6">
                            <label class="block text-sm font-medium mb-2 text-gray-700 dark:text-gray-300">Performance Score (0-100)</label>
                            <input type="number" x-model="formData.performance_score" min="0" max="100" placeholder="Enter overall score"
                                   class="w-full md:w-48 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white px-3 py-2 rounded-lg focus:ring-2 focus:ring-sky-500 focus:border-sky-500">
                        </div>

                        {{-- Ratings Grid --}}
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                            <div>
                                <label class="block text-sm font-medium mb-2 text-gray-700 dark:text-gray-300">Professionalism (1-5)</label>
                                <select x-model="formData.professionalism_rating" class="w-full border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white px-3 py-2 rounded-lg">
                                    <option value="">Select rating</option>
                                    <option value="1">1 - Poor</option>
                                    <option value="2">2 - Below Average</option>
                                    <option value="3">3 - Average</option>
                                    <option value="4">4 - Good</option>
                                    <option value="5">5 - Excellent</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium mb-2 text-gray-700 dark:text-gray-300">Communication (1-5)</label>
                                <select x-model="formData.communication_rating" class="w-full border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white px-3 py-2 rounded-lg">
                                    <option value="">Select rating</option>
                                    <option value="1">1 - Poor</option>
                                    <option value="2">2 - Below Average</option>
                                    <option value="3">3 - Average</option>
                                    <option value="4">4 - Good</option>
                                    <option value="5">5 - Excellent</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium mb-2 text-gray-700 dark:text-gray-300">Punctuality (1-5)</label>
                                <select x-model="formData.punctuality_rating" class="w-full border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white px-3 py-2 rounded-lg">
                                    <option value="">Select rating</option>
                                    <option value="1">1 - Poor</option>
                                    <option value="2">2 - Below Average</option>
                                    <option value="3">3 - Average</option>
                                    <option value="4">4 - Good</option>
                                    <option value="5">5 - Excellent</option>
                                </select>
                            </div>
                        </div>

                        {{-- Action Buttons --}}
                        <div class="flex gap-3 flex-wrap">
                            <button type="submit" class="bg-gradient-to-r from-sky-500 to-sky-600 text-white px-5 py-2.5 rounded-xl font-medium shadow-lg hover:shadow-xl transition-all hover:-translate-y-0.5">
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
            <div x-show="view === 'dashboard'" x-transition class="w-full max-w-7xl mx-auto p-4 sm:p-6">
                {{-- Header with Week Navigator --}}
                <div class="flex flex-wrap justify-between items-center gap-4 mb-6">
                    <div>
                        <h2 class="text-2xl font-semibold text-gray-800 dark:text-white">Dashboard – Week <span x-text="weekView"></span></h2>
                        <p class="text-gray-500 dark:text-gray-400 mt-1 text-sm">Tutor assessments grouped by week</p>
                    </div>
                    <div class="flex items-center gap-3">
                        <label class="text-gray-500 font-medium text-sm">Week</label>
                        <input type="number" x-model="weekView" min="1" max="52" class="border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white px-3 py-2 rounded-lg w-24">
                    </div>
                </div>

                {{-- Filters --}}
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow p-4 mb-6">
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-3">
                        <div>
                            <label class="block text-sm font-medium mb-2 text-gray-700 dark:text-gray-300">Filter by Tutor</label>
                            <select x-model="dashFilterTutor" class="w-full border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white px-3 py-2 rounded-lg">
                                <option value="">All Tutors</option>
                                @foreach($tutors as $tutor)
                                    <option value="{{ $tutor->id }}">{{ $tutor->first_name }} {{ $tutor->last_name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium mb-2 text-gray-700 dark:text-gray-300">Filter by Status</label>
                            <select x-model="dashFilterStatus" class="w-full border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white px-3 py-2 rounded-lg">
                                <option value="">All Status</option>
                                <option value="draft">Draft</option>
                                <option value="approved-by-manager">Awaiting Director</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium mb-2 text-gray-700 dark:text-gray-300">Filter by Period</label>
                            <input type="text" x-model="dashFilterMonth" placeholder="e.g., November 2024" class="w-full border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white px-3 py-2 rounded-lg">
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
                    @forelse($assessments->filter(fn($a) => in_array($a->status, ['draft', 'submitted', 'approved-by-manager'])) as $assessment)
                        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow p-5 border-l-4 {{ $assessment->status === 'draft' ? 'border-amber-400' : 'border-blue-500' }}">
                            <div class="flex flex-wrap justify-between items-start gap-4 mb-4">
                                <div class="flex-1">
                                    <div class="text-lg font-semibold text-gray-800 dark:text-white flex items-center gap-2">
                                        {{ $assessment->tutor->first_name ?? 'Unknown' }} {{ $assessment->tutor->last_name ?? '' }}
                                        @if($assessment->status === 'approved-by-manager')
                                            <span class="px-2 py-0.5 text-xs bg-blue-500 text-white rounded-full">Awaiting Director</span>
                                        @elseif($assessment->status === 'draft')
                                            <span class="px-2 py-0.5 text-xs bg-amber-500 text-white rounded-full">Draft</span>
                                        @endif
                                    </div>
                                    <div class="text-gray-500 dark:text-gray-400 text-sm">
                                        {{ $assessment->assessment_month }} · Session {{ $assessment->session ?? 1 }}
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
                                        <a href="{{ route('manager.assessments.edit', $assessment) }}" class="text-xs px-3 py-1.5 border border-sky-500 text-sky-600 rounded-lg hover:bg-sky-50 dark:hover:bg-sky-900/30 transition-colors">
                                            Edit
                                        </a>
                                        <form action="{{ route('manager.assessments.submit', $assessment) }}" method="POST" class="inline">
                                            @csrf
                                            <button type="submit" class="text-xs px-3 py-1.5 bg-emerald-500 text-white rounded-lg hover:bg-emerald-600 transition-colors">
                                                Submit to Director
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </div>

                            {{-- Ratings Display --}}
                            <div class="flex flex-wrap gap-4 text-sm">
                                @if($assessment->professionalism_rating)
                                    <div class="flex items-center gap-1">
                                        <span class="text-gray-500">Professionalism:</span>
                                        <span class="font-medium">{{ $assessment->professionalism_rating }}/5</span>
                                    </div>
                                @endif
                                @if($assessment->communication_rating)
                                    <div class="flex items-center gap-1">
                                        <span class="text-gray-500">Communication:</span>
                                        <span class="font-medium">{{ $assessment->communication_rating }}/5</span>
                                    </div>
                                @endif
                                @if($assessment->punctuality_rating)
                                    <div class="flex items-center gap-1">
                                        <span class="text-gray-500">Punctuality:</span>
                                        <span class="font-medium">{{ $assessment->punctuality_rating }}/5</span>
                                    </div>
                                @endif
                            </div>

                            @if($assessment->strengths)
                                <div class="mt-3 p-3 bg-blue-50 dark:bg-blue-900/20 rounded-lg">
                                    <div class="font-semibold text-blue-900 dark:text-blue-300 mb-1 text-sm">Strengths:</div>
                                    <div class="text-blue-800 dark:text-blue-200 text-sm">{{ Str::limit($assessment->strengths, 200) }}</div>
                                </div>
                            @endif
                        </div>
                    @empty
                        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow p-8 text-center">
                            <div class="text-6xl mb-4">📋</div>
                            <h3 class="text-xl font-semibold text-gray-700 dark:text-gray-300 mb-2">No Assessments Found</h3>
                            <p class="text-gray-500 dark:text-gray-400">Create a new assessment to get started</p>
                        </div>
                    @endforelse
                </div>
            </div>

            {{-- Management Tab --}}
            <div x-show="view === 'management'" x-transition class="w-full max-w-6xl mx-auto p-4 sm:p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    {{-- Quick Stats --}}
                    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow p-5">
                        <h3 class="font-semibold mb-4 text-gray-800 dark:text-white text-lg">Assessment Statistics</h3>
                        <div class="grid grid-cols-2 gap-4">
                            <div class="p-4 bg-gray-50 dark:bg-gray-700/50 rounded-xl text-center">
                                <div class="text-3xl font-bold text-gray-800 dark:text-white">{{ $stats['total'] }}</div>
                                <div class="text-sm text-gray-500 dark:text-gray-400">Total</div>
                            </div>
                            <div class="p-4 bg-amber-50 dark:bg-amber-900/20 rounded-xl text-center">
                                <div class="text-3xl font-bold text-amber-600">{{ $stats['pending'] }}</div>
                                <div class="text-sm text-amber-600">Pending</div>
                            </div>
                            <div class="p-4 bg-blue-50 dark:bg-blue-900/20 rounded-xl text-center">
                                <div class="text-3xl font-bold text-blue-600">{{ $stats['awaiting_director'] }}</div>
                                <div class="text-sm text-blue-600">Awaiting Director</div>
                            </div>
                            <div class="p-4 bg-emerald-50 dark:bg-emerald-900/20 rounded-xl text-center">
                                <div class="text-3xl font-bold text-emerald-600">{{ $stats['completed'] }}</div>
                                <div class="text-sm text-emerald-600">Completed</div>
                            </div>
                        </div>
                    </div>

                    {{-- Tutors List --}}
                    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow p-5">
                        <h3 class="font-semibold mb-4 text-gray-800 dark:text-white text-lg">Active Tutors ({{ $tutors->count() }})</h3>
                        <div class="space-y-2 max-h-64 overflow-y-auto">
                            @foreach($tutors as $tutor)
                                <div class="flex justify-between items-center p-3 bg-gray-50 dark:bg-gray-700/50 rounded-lg">
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 rounded-full bg-gradient-to-r from-sky-500 to-blue-500 flex items-center justify-center text-white font-bold text-sm">
                                            {{ strtoupper(substr($tutor->first_name, 0, 1)) }}
                                        </div>
                                        <span class="font-medium text-gray-800 dark:text-white">{{ $tutor->first_name }} {{ $tutor->last_name }}</span>
                                    </div>
                                    <span class="px-2 py-1 text-xs rounded-full {{ $tutor->status === 'active' ? 'bg-emerald-100 text-emerald-800' : 'bg-gray-100 text-gray-600' }}">
                                        {{ ucfirst($tutor->status) }}
                                    </span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            {{-- Completed Tab --}}
            <div x-show="view === 'completed'" x-transition class="w-full max-w-6xl mx-auto p-4 sm:p-6">
                <div class="mb-6">
                    <h2 class="text-2xl font-semibold text-gray-800 dark:text-white">Completed Assessments</h2>
                    <p class="text-gray-500 dark:text-gray-400 mt-1 text-sm">Assessments approved by the Director</p>
                </div>

                <div class="space-y-4">
                    @forelse($assessments->filter(fn($a) => $a->status === 'approved-by-director') as $assessment)
                        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow p-5 border-l-4 border-emerald-500">
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
                                <div class="mt-4 p-3 bg-yellow-50 dark:bg-yellow-900/20 rounded-lg border border-yellow-200 dark:border-yellow-800/30">
                                    <div class="font-semibold text-yellow-800 dark:text-yellow-300 mb-1 text-sm">Director's Remarks:</div>
                                    <div class="text-yellow-700 dark:text-yellow-200 text-sm">{{ $assessment->director_comment }}</div>
                                </div>
                            @endif
                        </div>
                    @empty
                        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow p-8 text-center">
                            <div class="text-6xl mb-4">✅</div>
                            <h3 class="text-xl font-semibold text-gray-700 dark:text-gray-300 mb-2">No Completed Assessments Yet</h3>
                            <p class="text-gray-500 dark:text-gray-400">Assessments will appear here after Director approval</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </main>

        {{-- Footer Stats --}}
        <footer class="py-5 bg-white dark:bg-gray-800 border-t dark:border-gray-700 shadow-sm">
            <div class="max-w-7xl mx-auto text-sm text-gray-500 dark:text-gray-400 text-center">
                {{ $stats['pending'] }} pending · {{ $stats['awaiting_director'] }} awaiting director · {{ $stats['completed'] }} completed · {{ $tutors->count() }} tutors
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
                
                stats: {
                    total: {{ $stats['total'] }},
                    pending: {{ $stats['pending'] }},
                    awaiting_director: {{ $stats['awaiting_director'] }},
                    completed: {{ $stats['completed'] }}
                },
                
                formData: {
                    tutor_id: '',
                    assessment_month: '',
                    year: {{ date('Y') }},
                    week: {{ date('W') }},
                    performance_score: '',
                    professionalism_rating: '',
                    communication_rating: '',
                    punctuality_rating: '',
                    strengths: '',
                    weaknesses: '',
                    recommendations: ''
                },
                
                criteria: [
                    { id: 'preparation', name: 'Lesson Preparation', options: ['Excellent', 'Good', 'Acceptable', 'Needs Improvement'] },
                    { id: 'delivery', name: 'Content Delivery', options: ['Excellent', 'Good', 'Acceptable', 'Needs Improvement'] },
                    { id: 'engagement', name: 'Student Engagement', options: ['Excellent', 'Good', 'Acceptable', 'Needs Improvement'] },
                    { id: 'punctuality', name: 'Punctuality', options: ['On Time', 'Late'] },
                    { id: 'communication', name: 'Communication Skills', options: ['Excellent', 'Good', 'Acceptable', 'Needs Improvement'] },
                    { id: 'professionalism', name: 'Professionalism', options: ['Excellent', 'Good', 'Acceptable', 'Needs Improvement'] },
                    { id: 'adaptability', name: 'Adaptability', options: ['Excellent', 'Good', 'Acceptable', 'Needs Improvement'] },
                    { id: 'feedback', name: 'Feedback Quality', options: ['Excellent', 'Good', 'Acceptable', 'Needs Improvement'] }
                ],
                
                checkedCriteria: {},
                ratings: {},
                
                dashFilterTutor: '',
                dashFilterStatus: '',
                dashFilterMonth: '',
                
                toast: {
                    show: false,
                    message: '',
                    type: 'success'
                },
                
                init() {
                    // Initialize checked criteria
                    this.criteria.forEach(c => {
                        this.checkedCriteria[c.id] = false;
                    });
                },
                
                showToast(message, type = 'success') {
                    this.toast = { show: true, message, type };
                    setTimeout(() => { this.toast.show = false; }, 3000);
                },
                
                resetForm() {
                    this.formData = {
                        tutor_id: '',
                        assessment_month: '',
                        year: new Date().getFullYear(),
                        week: parseInt('{{ date("W") }}'),
                        performance_score: '',
                        professionalism_rating: '',
                        communication_rating: '',
                        punctuality_rating: '',
                        strengths: '',
                        weaknesses: '',
                        recommendations: ''
                    };
                    this.criteria.forEach(c => {
                        this.checkedCriteria[c.id] = false;
                    });
                    this.ratings = {};
                    this.editing = null;
                },
                
                clearDashFilters() {
                    this.dashFilterTutor = '';
                    this.dashFilterStatus = '';
                    this.dashFilterMonth = '';
                },
                
                async saveAssessment() {
                    if (!this.formData.tutor_id || !this.formData.assessment_month) {
                        this.showToast('Please select a tutor and enter assessment period', 'error');
                        return;
                    }
                    
                    // Submit form via fetch
                    try {
                        const response = await fetch('{{ route("manager.assessments.store") }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            body: JSON.stringify({
                                ...this.formData,
                                session: this.session,
                                criteria_assessed: Object.keys(this.checkedCriteria).filter(k => this.checkedCriteria[k]),
                                criteria_ratings: this.ratings
                            })
                        });
                        
                        if (response.ok) {
                            this.showToast('Assessment saved successfully!');
                            this.resetForm();
                            this.view = 'dashboard';
                            // Reload page to get updated data
                            setTimeout(() => window.location.reload(), 1000);
                        } else {
                            this.showToast('Failed to save assessment', 'error');
                        }
                    } catch (e) {
                        console.error('Save error:', e);
                        this.showToast('Failed to save assessment', 'error');
                    }
                }
            };
        }
    </script>
    @endpush
</x-app-layout>
