<x-tutor-layout title="View Report">
<div class="max-w-6xl mx-auto">
    <!-- Page Header -->
    <div class="mb-6">
        <div class="flex items-center gap-2 text-sm text-slate-500 dark:text-slate-400 mb-2">
            <a href="{{ route('tutor.reports.index') }}" class="hover:text-[#4B51FF]">Reports</a>
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            <span>{{ $report->student->first_name }} {{ $report->student->last_name }}</span>
        </div>
        
        <div class="flex flex-wrap items-start justify-between gap-4">
            <div>
                <h1 class="text-3xl font-bold text-slate-900 dark:text-white">
                    {{ $report->student->first_name }} {{ $report->student->last_name }}
                </h1>
                <p class="text-slate-600 dark:text-slate-400 mt-1">{{ $report->month }} {{ $report->year }} Progress Report</p>
            </div>
            
            <div class="flex items-center gap-3">
                <!-- Status Badge -->
                <span class="inline-flex items-center px-3 py-1.5 rounded-full text-sm font-semibold
                    @if($report->status === 'draft') bg-amber-100 text-amber-700 dark:bg-amber-900/30 dark:text-amber-400
                    @elseif($report->status === 'submitted') bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400
                    @elseif($report->status === 'approved') bg-emerald-100 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-400
                    @elseif($report->status === 'director_approved') bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400
                    @elseif($report->status === 'returned') bg-rose-100 text-rose-700 dark:bg-rose-900/30 dark:text-rose-400
                    @else bg-slate-100 text-slate-700 dark:bg-slate-700 dark:text-slate-400 @endif">
                    {{ ucfirst(str_replace('_', ' ', $report->status)) }}
                </span>

                @if($report->canEdit())
                    <a href="{{ route('tutor.reports.edit', $report) }}" class="inline-flex items-center gap-2 px-4 py-2 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-slate-700 dark:text-slate-300 font-medium rounded-xl hover:bg-slate-50 dark:hover:bg-slate-700 transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                        Edit
                    </a>
                @endif

                @if($report->status === 'draft')
                    <form action="{{ route('tutor.reports.submit', $report) }}" method="POST" onsubmit="return confirm('Submit for review?')">
                        @csrf
                        <button type="submit" class="inline-flex items-center gap-2 px-4 py-2 bg-gradient-to-r from-[#1D2A6D] to-[#4B51FF] text-white font-semibold rounded-xl hover:opacity-90 transition-all">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            Submit
                        </button>
                    </form>
                @endif
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Courses Section -->
            @if($report->courses && count($report->courses) > 0)
            <div class="glass-card rounded-2xl p-6">
                <h2 class="text-lg font-semibold text-slate-900 dark:text-white mb-4 flex items-center gap-2">
                    <svg class="w-5 h-5 text-[#4B51FF]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                    Courses ({{ count($report->courses) }})
                </h2>
                <div class="flex flex-wrap gap-2">
                    @foreach($report->courses as $course)
                        <span class="px-3 py-1.5 bg-[#4B51FF]/10 text-[#4B51FF] dark:text-[#22D3EE] rounded-full text-sm font-medium">{{ $course }}</span>
                    @endforeach
                </div>
            </div>
            @endif

            <!-- Skills Mastered Section -->
            @if($report->skills_mastered && count($report->skills_mastered) > 0)
            <div class="glass-card rounded-2xl p-6">
                <h2 class="text-lg font-semibold text-slate-900 dark:text-white mb-4 flex items-center gap-2">
                    <svg class="w-5 h-5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/></svg>
                    Skills Mastered ({{ count($report->skills_mastered) }})
                </h2>
                <div class="flex flex-wrap gap-2">
                    @foreach($report->skills_mastered as $skill)
                        <span class="px-3 py-1.5 bg-emerald-100 dark:bg-emerald-900/30 text-emerald-700 dark:text-emerald-400 rounded-full text-sm">{{ $skill }}</span>
                    @endforeach
                </div>
            </div>
            @endif

            <!-- New Skills Section -->
            @if($report->new_skills && count($report->new_skills) > 0)
            <div class="glass-card rounded-2xl p-6">
                <h2 class="text-lg font-semibold text-slate-900 dark:text-white mb-4 flex items-center gap-2">
                    <svg class="w-5 h-5 text-cyan-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    New Skills Learned
                </h2>
                <div class="flex flex-wrap gap-2">
                    @foreach($report->new_skills as $skill)
                        <span class="px-3 py-1.5 bg-cyan-100 dark:bg-cyan-900/30 text-cyan-700 dark:text-cyan-400 rounded-full text-sm">{{ $skill }}</span>
                    @endforeach
                </div>
            </div>
            @endif

            <!-- Projects Section -->
            @if($report->projects && count($report->projects) > 0)
            <div class="glass-card rounded-2xl p-6">
                <h2 class="text-lg font-semibold text-slate-900 dark:text-white mb-4 flex items-center gap-2">
                    <svg class="w-5 h-5 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                    Projects Completed ({{ count($report->projects) }})
                </h2>
                <div class="space-y-3">
                    @foreach($report->projects as $project)
                        @if(!empty($project['title']))
                        <div class="flex items-center justify-between p-3 bg-slate-50 dark:bg-slate-800/50 rounded-xl">
                            <span class="font-medium text-slate-900 dark:text-white">{{ $project['title'] }}</span>
                            @if(!empty($project['link']))
                                <a href="{{ $project['link'] }}" target="_blank" class="inline-flex items-center gap-1 text-sm text-[#4B51FF] hover:underline">
                                    View
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                                </a>
                            @endif
                        </div>
                        @endif
                    @endforeach
                </div>
            </div>
            @endif

            <!-- Areas for Improvement -->
            @if($report->areas_for_improvement)
            <div class="glass-card rounded-2xl p-6">
                <h2 class="text-lg font-semibold text-slate-900 dark:text-white mb-4 flex items-center gap-2">
                    <svg class="w-5 h-5 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                    Areas for Improvement
                </h2>
                <p class="text-slate-700 dark:text-slate-300 whitespace-pre-wrap">{{ $report->areas_for_improvement }}</p>
            </div>
            @endif

            <!-- Goals for Next Month -->
            @if($report->goals_next_month)
            <div class="glass-card rounded-2xl p-6">
                <h2 class="text-lg font-semibold text-slate-900 dark:text-white mb-4 flex items-center gap-2">
                    <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
                    Goals for Next Month
                </h2>
                <p class="text-slate-700 dark:text-slate-300 whitespace-pre-wrap">{{ $report->goals_next_month }}</p>
            </div>
            @endif

            <!-- Assignments -->
            @if($report->assignments)
            <div class="glass-card rounded-2xl p-6">
                <h2 class="text-lg font-semibold text-slate-900 dark:text-white mb-4 flex items-center gap-2">
                    <svg class="w-5 h-5 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                    Assignments Given
                </h2>
                <p class="text-slate-700 dark:text-slate-300 whitespace-pre-wrap">{{ $report->assignments }}</p>
            </div>
            @endif

            <!-- Comments & Observations -->
            @if($report->comments_observation)
            <div class="glass-card rounded-2xl p-6">
                <h2 class="text-lg font-semibold text-slate-900 dark:text-white mb-4 flex items-center gap-2">
                    <svg class="w-5 h-5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"/></svg>
                    Comments & Observations
                </h2>
                <p class="text-slate-700 dark:text-slate-300 whitespace-pre-wrap">{{ $report->comments_observation }}</p>
            </div>
            @endif

            <!-- Director Feedback (only shows director comments, not manager comments) -->
            @if($report->comments && $report->comments->count() > 0)
            <div class="glass-card rounded-2xl p-6">
                <h2 class="text-lg font-semibold text-slate-900 dark:text-white mb-4 flex items-center gap-2">
                    <svg class="w-5 h-5 text-rose-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/></svg>
                    Feedback
                </h2>
                <div class="space-y-4">
                    @foreach($report->comments as $comment)
                        <div class="p-4 bg-slate-50 dark:bg-slate-800/50 rounded-xl border-l-4 
                            @if($comment->user_id === auth()->id()) border-[#4B51FF]
                            @else border-emerald-500 @endif">
                            <div class="flex items-center justify-between mb-2">
                                <div class="flex items-center gap-2">
                                    <span class="font-semibold text-slate-900 dark:text-white">{{ $comment->user->name }}</span>
                                    <span class="px-2 py-0.5 text-xs rounded-full 
                                        @if($comment->user->hasRole('director')) bg-purple-100 text-purple-700 dark:bg-purple-900/30 dark:text-purple-400
                                        @else bg-slate-100 text-slate-600 dark:bg-slate-700 dark:text-slate-400 @endif">
                                        {{ $comment->user->hasRole('director') ? 'Director' : 'Tutor' }}
                                    </span>
                                </div>
                                <span class="text-xs text-slate-500">{{ $comment->created_at->diffForHumans() }}</span>
                            </div>
                            <p class="text-slate-700 dark:text-slate-300">{{ $comment->content }}</p>
                        </div>
                    @endforeach
                </div>
            </div>
            @endif
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Student Info Card -->
            <div class="glass-card rounded-2xl p-6">
                <h3 class="text-lg font-semibold text-slate-900 dark:text-white mb-4">Student</h3>
                <div class="space-y-3">
                    <div>
                        <label class="text-xs text-slate-500 dark:text-slate-400">Name</label>
                        <p class="font-medium text-slate-900 dark:text-white">{{ $report->student->first_name }} {{ $report->student->last_name }}</p>
                    </div>
                    @if($report->student->age)
                    <div>
                        <label class="text-xs text-slate-500 dark:text-slate-400">Age</label>
                        <p class="font-medium text-slate-900 dark:text-white">{{ $report->student->age }} years</p>
                    </div>
                    @endif
                    @if($report->student->location)
                    <div>
                        <label class="text-xs text-slate-500 dark:text-slate-400">Location</label>
                        <p class="font-medium text-slate-900 dark:text-white">{{ $report->student->location }}</p>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Report Stats -->
            <div class="glass-card rounded-2xl p-6">
                <h3 class="text-lg font-semibold text-slate-900 dark:text-white mb-4">Report Stats</h3>
                <div class="grid grid-cols-2 gap-4">
                    <div class="text-center p-3 bg-emerald-50 dark:bg-emerald-900/20 rounded-xl">
                        <p class="text-2xl font-bold text-emerald-600 dark:text-emerald-400">{{ $report->skills_mastered ? count($report->skills_mastered) : 0 }}</p>
                        <p class="text-xs text-slate-500">Skills</p>
                    </div>
                    <div class="text-center p-3 bg-purple-50 dark:bg-purple-900/20 rounded-xl">
                        <p class="text-2xl font-bold text-purple-600 dark:text-purple-400">{{ $report->projects ? count(array_filter($report->projects, fn($p) => !empty($p['title']))) : 0 }}</p>
                        <p class="text-xs text-slate-500">Projects</p>
                    </div>
                    <div class="text-center p-3 bg-blue-50 dark:bg-blue-900/20 rounded-xl">
                        <p class="text-2xl font-bold text-blue-600 dark:text-blue-400">{{ $report->courses ? count($report->courses) : 0 }}</p>
                        <p class="text-xs text-slate-500">Courses</p>
                    </div>
                    <div class="text-center p-3 bg-amber-50 dark:bg-amber-900/20 rounded-xl">
                        <p class="text-2xl font-bold text-amber-600 dark:text-amber-400">{{ $report->new_skills ? count($report->new_skills) : 0 }}</p>
                        <p class="text-xs text-slate-500">New Skills</p>
                    </div>
                </div>
            </div>

            <!-- Timeline -->
            <div class="glass-card rounded-2xl p-6">
                <h3 class="text-lg font-semibold text-slate-900 dark:text-white mb-4">Timeline</h3>
                <div class="space-y-3 text-sm">
                    <div class="flex items-center gap-3">
                        <div class="w-2 h-2 bg-slate-400 rounded-full"></div>
                        <div>
                            <p class="text-slate-500 dark:text-slate-400">Created</p>
                            <p class="font-medium text-slate-900 dark:text-white">{{ $report->created_at->format('M d, Y g:i A') }}</p>
                        </div>
                    </div>
                    @if($report->submitted_at)
                    <div class="flex items-center gap-3">
                        <div class="w-2 h-2 bg-blue-500 rounded-full"></div>
                        <div>
                            <p class="text-slate-500 dark:text-slate-400">Submitted</p>
                            <p class="font-medium text-slate-900 dark:text-white">{{ $report->submitted_at->format('M d, Y g:i A') }}</p>
                        </div>
                    </div>
                    @endif
                    @if($report->imported_from_artifact)
                    <div class="flex items-center gap-3">
                        <div class="w-2 h-2 bg-purple-500 rounded-full"></div>
                        <div>
                            <p class="text-slate-500 dark:text-slate-400">Source</p>
                            <p class="font-medium text-purple-600 dark:text-purple-400">Imported from Artifact</p>
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Export & Share -->
            <div class="glass-card rounded-2xl p-6">
                <h3 class="text-lg font-semibold text-slate-900 dark:text-white mb-4">Export & Share</h3>
                <div class="space-y-3">
                    <a href="{{ route('tutor.reports.pdf', $report) }}" class="flex items-center justify-center gap-2 w-full px-4 py-3 bg-rose-600 text-white rounded-xl hover:bg-rose-700 transition-colors font-medium">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                        Download PDF
                    </a>

                    <button type="button" onclick="exportWhatsApp()" class="flex items-center justify-center gap-2 w-full px-4 py-3 bg-green-600 text-white rounded-xl hover:bg-green-700 transition-colors font-medium">
                        <svg class="w-5 h-5" viewBox="0 0 24 24" fill="currentColor"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                        Copy for WhatsApp
                    </button>
                </div>
            </div>

            <!-- Actions -->
            <div class="glass-card rounded-2xl p-6">
                <h3 class="text-lg font-semibold text-slate-900 dark:text-white mb-4">Actions</h3>
                <div class="space-y-3">
                    <a href="{{ route('tutor.reports.index') }}" class="flex items-center justify-center gap-2 w-full px-4 py-3 bg-slate-100 dark:bg-slate-700 text-slate-700 dark:text-slate-300 rounded-xl hover:bg-slate-200 dark:hover:bg-slate-600 transition-colors font-medium">
                        ← Back to Reports
                    </a>
                    @if($report->canEdit())
                        <a href="{{ route('tutor.reports.edit', $report) }}" class="flex items-center justify-center gap-2 w-full px-4 py-3 bg-blue-600 text-white rounded-xl hover:bg-blue-700 transition-colors font-medium">
                            Edit Report
                        </a>
                    @endif
                    @if($report->status === 'draft')
                        <form action="{{ route('tutor.reports.destroy', $report) }}" method="POST" onsubmit="return confirm('Delete this draft?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="flex items-center justify-center gap-2 w-full px-4 py-3 bg-rose-600 text-white rounded-xl hover:bg-rose-700 transition-colors font-medium">
                                Delete Draft
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- WhatsApp Text Modal -->
<div id="whatsappModal" class="hidden fixed inset-0 z-50 overflow-y-auto">
    <div class="flex items-center justify-center min-h-screen px-4">
        <div class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm" onclick="closeWhatsAppModal()"></div>
        <div class="relative bg-white dark:bg-slate-800 rounded-2xl shadow-xl max-w-2xl w-full p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-slate-900 dark:text-white">WhatsApp Report</h3>
                <button onclick="closeWhatsAppModal()" class="p-1 text-slate-400 hover:text-slate-600">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
            <textarea id="whatsappText" rows="12" readonly class="w-full px-4 py-3 bg-slate-50 dark:bg-slate-700 border border-slate-200 dark:border-slate-600 rounded-xl font-mono text-sm"></textarea>
            <div class="flex justify-end gap-3 mt-4">
                <button onclick="closeWhatsAppModal()" class="px-4 py-2 text-slate-600 dark:text-slate-400 font-medium">Close</button>
                <button onclick="copyWhatsAppText()" class="px-6 py-2 bg-green-600 text-white font-semibold rounded-xl hover:bg-green-700">Copy to Clipboard</button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
async function exportWhatsApp() {
    try {
        const response = await fetch('{{ route("tutor.reports.whatsapp", $report) }}');
        const data = await response.json();
        if (data.success) {
            document.getElementById('whatsappText').value = data.text;
            document.getElementById('whatsappModal').classList.remove('hidden');
        }
    } catch (error) {
        alert('Failed to generate WhatsApp text');
    }
}

function closeWhatsAppModal() {
    document.getElementById('whatsappModal').classList.add('hidden');
}

function copyWhatsAppText() {
    const text = document.getElementById('whatsappText');
    text.select();
    document.execCommand('copy');
    alert('Copied to clipboard!');
}
</script>
@endpush
</x-tutor-layout>
