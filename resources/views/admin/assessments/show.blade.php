<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-2xl text-white">{{ __('View Assessment') }}</h2>
    </x-slot>
    <x-slot name="title">{{ __('Admin - View Assessment') }}</x-slot>

    <div class="min-h-screen bg-gradient-to-br from-teal-50 via-cyan-50 to-blue-50 dark:from-slate-900 dark:via-slate-800 dark:to-slate-900 py-8 relative overflow-hidden">
        <div class="absolute top-0 left-0 w-72 h-72 bg-teal-300 dark:bg-teal-900 rounded-full mix-blend-multiply filter blur-xl opacity-70 animate-float"></div>
        <div class="absolute top-0 right-0 w-72 h-72 bg-cyan-300 dark:bg-cyan-900 rounded-full mix-blend-multiply filter blur-xl opacity-70 animate-float" style="animation-delay: 2s;"></div>

        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            {{-- Header --}}
            <div class="flex flex-wrap items-center justify-between gap-4 mb-8">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Performance Assessment</h1>
                    <p class="text-gray-600 dark:text-gray-400 mt-1">{{ $assessment->assessment_month }}</p>
                </div>
                <div class="flex gap-2">
                    <a href="{{ route('admin.assessments.print', $assessment) }}" target="_blank" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
                        </svg>
                        Print
                    </a>
                    <a href="{{ route('admin.assessments.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-300 dark:hover:bg-gray-600 transition-colors">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                        </svg>
                        Back
                    </a>
                </div>
            </div>

            {{-- Approval Badge --}}
            <div class="mb-6 p-4 bg-emerald-100 dark:bg-emerald-900/30 border border-emerald-300 dark:border-emerald-700 rounded-xl">
                <div class="flex items-center gap-3">
                    <span class="text-2xl">✅</span>
                    <div>
                        <p class="font-semibold text-emerald-800 dark:text-emerald-400">Director Approved</p>
                        <p class="text-sm text-emerald-700 dark:text-emerald-500">
                            Approved by {{ $assessment->director->name ?? 'Director' }} on {{ $assessment->director_approved_at?->format('M j, Y \a\t g:i A') }}
                        </p>
                    </div>
                </div>
            </div>

            {{-- Tutor Info & Score --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                <div class="md:col-span-2 bg-white/60 dark:bg-gray-800/60 backdrop-blur-md border border-white/20 rounded-2xl shadow p-6">
                    <h4 class="text-sm font-semibold text-gray-500 dark:text-gray-400 uppercase mb-4">Tutor</h4>
                    <div class="flex items-center gap-4">
                        <div class="w-16 h-16 bg-gradient-to-br from-blue-400 to-indigo-500 rounded-full flex items-center justify-center text-white font-bold text-xl">
                            {{ strtoupper(substr($assessment->tutor->first_name ?? 'U', 0, 1)) }}{{ strtoupper(substr($assessment->tutor->last_name ?? '', 0, 1)) }}
                        </div>
                        <div>
                            <p class="font-semibold text-gray-900 dark:text-white text-xl">
                                {{ $assessment->tutor->first_name ?? 'Unknown' }} {{ $assessment->tutor->last_name ?? '' }}
                            </p>
                            <p class="text-sm text-gray-500">{{ $assessment->tutor->email ?? '-' }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white/60 dark:bg-gray-800/60 backdrop-blur-md border border-white/20 rounded-2xl shadow p-6 text-center">
                    <h4 class="text-sm font-semibold text-gray-500 dark:text-gray-400 uppercase mb-2">Overall Score</h4>
                    @php
                        $score = $assessment->performance_score ?? 0;
                        $colorClass = $score >= 80 ? 'text-emerald-600' : ($score >= 60 ? 'text-amber-600' : 'text-red-600');
                    @endphp
                    <div class="text-5xl font-bold {{ $colorClass }}">{{ number_format($score, 0) }}%</div>
                    <div class="text-sm text-gray-500 mt-1">
                        @if($score >= 80) Excellent
                        @elseif($score >= 60) Good
                        @else Needs Improvement
                        @endif
                    </div>
                </div>
            </div>

            {{-- Assessment Criteria --}}
            <div class="bg-white/60 dark:bg-gray-800/60 backdrop-blur-md border border-white/20 rounded-2xl shadow mb-6 overflow-hidden">
                <div class="px-6 py-4 bg-gradient-to-r from-teal-500 to-cyan-600 text-white">
                    <h3 class="text-lg font-semibold">Assessment Criteria</h3>
                </div>
                <div class="p-6">
                    @php
                        $criteria = [
                            'punctuality' => 'Punctuality',
                            'class_preparation' => 'Class Preparation',
                            'teaching_quality' => 'Teaching Quality',
                            'communication' => 'Communication',
                            'student_engagement' => 'Student Engagement',
                            'report_submission' => 'Report Submission',
                            'professionalism' => 'Professionalism',
                            'adaptability' => 'Adaptability',
                        ];
                    @endphp
                    <div class="space-y-4">
                        @foreach($criteria as $key => $label)
                            @php
                                $rating = $assessment->{$key} ?? 0;
                                $barColor = $rating >= 4 ? 'bg-emerald-500' : ($rating >= 3 ? 'bg-amber-500' : 'bg-red-500');
                            @endphp
                            <div>
                                <div class="flex justify-between mb-1">
                                    <span class="text-sm font-medium text-gray-700 dark:text-gray-300">{{ $label }}</span>
                                    <span class="text-sm font-semibold text-gray-900 dark:text-white">{{ $rating }}/5</span>
                                </div>
                                <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2">
                                    <div class="{{ $barColor }} h-2 rounded-full transition-all" style="width: {{ ($rating / 5) * 100 }}%"></div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            {{-- Comments --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                @if($assessment->manager_comment)
                    <div class="bg-white/60 dark:bg-gray-800/60 backdrop-blur-md border border-white/20 rounded-2xl shadow p-6">
                        <h4 class="text-sm font-semibold text-gray-500 dark:text-gray-400 uppercase mb-3">Manager's Comment</h4>
                        <p class="text-gray-700 dark:text-gray-300">{{ $assessment->manager_comment }}</p>
                        <p class="text-xs text-gray-500 mt-3">— {{ $assessment->manager->name ?? 'Manager' }}</p>
                    </div>
                @endif

                @if($assessment->director_comment)
                    <div class="bg-white/60 dark:bg-gray-800/60 backdrop-blur-md border border-white/20 rounded-2xl shadow p-6">
                        <h4 class="text-sm font-semibold text-gray-500 dark:text-gray-400 uppercase mb-3">Director's Comment</h4>
                        <p class="text-gray-700 dark:text-gray-300">{{ $assessment->director_comment }}</p>
                        <p class="text-xs text-gray-500 mt-3">— {{ $assessment->director->name ?? 'Director' }}</p>
                    </div>
                @endif
            </div>

            {{-- Strengths & Improvements --}}
            @if($assessment->strengths || $assessment->areas_for_improvement)
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
                    @if($assessment->strengths)
                        <div class="bg-emerald-50 dark:bg-emerald-900/20 border border-emerald-200 dark:border-emerald-800/30 rounded-2xl p-6">
                            <h4 class="text-sm font-semibold text-emerald-700 dark:text-emerald-400 uppercase mb-3 flex items-center">
                                <span class="mr-2">💪</span> Strengths
                            </h4>
                            <p class="text-gray-700 dark:text-gray-300">{{ $assessment->strengths }}</p>
                        </div>
                    @endif

                    @if($assessment->areas_for_improvement)
                        <div class="bg-amber-50 dark:bg-amber-900/20 border border-amber-200 dark:border-amber-800/30 rounded-2xl p-6">
                            <h4 class="text-sm font-semibold text-amber-700 dark:text-amber-400 uppercase mb-3 flex items-center">
                                <span class="mr-2">📈</span> Areas for Improvement
                            </h4>
                            <p class="text-gray-700 dark:text-gray-300">{{ $assessment->areas_for_improvement }}</p>
                        </div>
                    @endif
                </div>
            @endif
        </div>
    </div>

    @push('styles')
    <style>
        @keyframes float { 0%, 100% { transform: translateY(0px); } 50% { transform: translateY(-20px); } }
        .animate-float { animation: float 6s ease-in-out infinite; }
    </style>
    @endpush
</x-app-layout>
