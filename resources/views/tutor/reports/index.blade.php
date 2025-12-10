<x-tutor-layout title="My Reports">
<div class="space-y-6">
    <!-- Page Header -->
    <div class="flex flex-wrap items-center justify-between gap-4">
        <div>
            <h1 class="text-3xl font-bold text-slate-900 dark:text-white">My Reports</h1>
            <p class="text-slate-600 dark:text-slate-400 mt-1">Manage student progress reports</p>
        </div>
        <div class="flex items-center gap-3">
            <!-- Import from Artifact Button -->
            <button onclick="document.getElementById('importModal').classList.remove('hidden')" 
                    class="inline-flex items-center gap-2 px-4 py-2.5 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-slate-700 dark:text-slate-300 font-medium rounded-xl hover:bg-slate-50 dark:hover:bg-slate-700 transition-all">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
                </svg>
                Import from Artifact
            </button>
            <!-- Create Report Button -->
            <a href="{{ route('tutor.reports.create') }}" 
               class="inline-flex items-center gap-2 px-4 py-2.5 bg-gradient-to-r from-[#1D2A6D] to-[#4B51FF] text-white font-semibold rounded-xl hover:opacity-90 transition-all shadow-lg hover:shadow-xl hover:-translate-y-0.5">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Create Report
            </a>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-2 md:grid-cols-5 gap-4">
        <div class="glass-card rounded-xl p-4">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-slate-100 dark:bg-slate-700 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-slate-600 dark:text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                </div>
                <div>
                    <p class="text-2xl font-bold text-slate-900 dark:text-white">{{ $stats['total'] }}</p>
                    <p class="text-xs text-slate-500 dark:text-slate-400">Total</p>
                </div>
            </div>
        </div>

        <div class="glass-card rounded-xl p-4">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-amber-100 dark:bg-amber-900/30 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-amber-600 dark:text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                    </svg>
                </div>
                <div>
                    <p class="text-2xl font-bold text-amber-600 dark:text-amber-400">{{ $stats['draft'] }}</p>
                    <p class="text-xs text-slate-500 dark:text-slate-400">Drafts</p>
                </div>
            </div>
        </div>

        <div class="glass-card rounded-xl p-4">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-blue-100 dark:bg-blue-900/30 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div>
                    <p class="text-2xl font-bold text-blue-600 dark:text-blue-400">{{ $stats['submitted'] }}</p>
                    <p class="text-xs text-slate-500 dark:text-slate-400">Pending</p>
                </div>
            </div>
        </div>

        <div class="glass-card rounded-xl p-4">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-emerald-100 dark:bg-emerald-900/30 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div>
                    <p class="text-2xl font-bold text-emerald-600 dark:text-emerald-400">{{ $stats['approved'] }}</p>
                    <p class="text-xs text-slate-500 dark:text-slate-400">Approved</p>
                </div>
            </div>
        </div>

        <div class="glass-card rounded-xl p-4">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-rose-100 dark:bg-rose-900/30 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-rose-600 dark:text-rose-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6"/>
                    </svg>
                </div>
                <div>
                    <p class="text-2xl font-bold text-rose-600 dark:text-rose-400">{{ $stats['returned'] }}</p>
                    <p class="text-xs text-slate-500 dark:text-slate-400">Returned</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="glass-card rounded-2xl p-6">
        <form method="GET" action="{{ route('tutor.reports.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label for="student_id" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Student</label>
                <select id="student_id" name="student_id" class="w-full px-4 py-2.5 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-600 rounded-xl focus:ring-2 focus:ring-[#4B51FF] focus:border-transparent">
                    <option value="">All Students</option>
                    @foreach($students as $student)
                        <option value="{{ $student->id }}" {{ request('student_id') == $student->id ? 'selected' : '' }}>
                            {{ $student->first_name }} {{ $student->last_name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label for="month" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Month</label>
                <select id="month" name="month" class="w-full px-4 py-2.5 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-600 rounded-xl focus:ring-2 focus:ring-[#4B51FF] focus:border-transparent">
                    <option value="">All Months</option>
                    @foreach($months as $monthOption)
                        <option value="{{ $monthOption }}" {{ request('month') === $monthOption ? 'selected' : '' }}>{{ $monthOption }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label for="status" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Status</label>
                <select id="status" name="status" class="w-full px-4 py-2.5 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-600 rounded-xl focus:ring-2 focus:ring-[#4B51FF] focus:border-transparent">
                    <option value="">All Statuses</option>
                    <option value="draft" {{ request('status') === 'draft' ? 'selected' : '' }}>Draft</option>
                    <option value="submitted" {{ request('status') === 'submitted' ? 'selected' : '' }}>Submitted</option>
                    <option value="approved" {{ request('status') === 'approved' ? 'selected' : '' }}>Approved</option>
                    <option value="director_approved" {{ request('status') === 'director_approved' ? 'selected' : '' }}>Director Approved</option>
                    <option value="returned" {{ request('status') === 'returned' ? 'selected' : '' }}>Returned</option>
                </select>
            </div>

            <div class="flex items-end gap-2">
                <button type="submit" class="flex-1 px-4 py-2.5 bg-[#4B51FF] text-white font-medium rounded-xl hover:bg-[#3a40cc] transition-colors">Filter</button>
                @if(request()->hasAny(['month', 'status', 'student_id']))
                    <a href="{{ route('tutor.reports.index') }}" class="px-4 py-2.5 bg-slate-100 dark:bg-slate-700 text-slate-700 dark:text-slate-300 font-medium rounded-xl hover:bg-slate-200 dark:hover:bg-slate-600 transition-colors">Reset</a>
                @endif
            </div>
        </form>
    </div>

    <!-- Reports List -->
    @if($reports->isEmpty())
        <div class="glass-card rounded-2xl p-12 text-center">
            <div class="w-16 h-16 bg-slate-100 dark:bg-slate-700 rounded-full flex items-center justify-center mx-auto mb-4">
                <svg class="w-8 h-8 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
            </div>
            <h3 class="text-lg font-semibold text-slate-700 dark:text-slate-300 mb-2">
                {{ request()->hasAny(['month', 'status', 'student_id']) ? 'No Reports Found' : 'No Reports Yet' }}
            </h3>
            <p class="text-slate-500 dark:text-slate-400 mb-6">
                {{ request()->hasAny(['month', 'status', 'student_id']) ? 'Try adjusting your filters' : 'Create your first student progress report' }}
            </p>
            @if(!request()->hasAny(['month', 'status', 'student_id']))
                <a href="{{ route('tutor.reports.create') }}" class="inline-flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-[#1D2A6D] to-[#4B51FF] text-white font-semibold rounded-xl hover:opacity-90 transition-all">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    Create Report
                </a>
            @endif
        </div>
    @else
        <div class="space-y-4">
            @foreach($reports as $report)
                <div class="glass-card rounded-xl p-5 hover:shadow-lg transition-all group">
                    <div class="flex items-start justify-between gap-4">
                        <div class="flex-1 min-w-0">
                            <div class="flex flex-wrap items-center gap-3 mb-2">
                                <a href="{{ route('tutor.reports.show', $report) }}" class="text-lg font-semibold text-slate-900 dark:text-white hover:text-[#4B51FF] dark:hover:text-[#22D3EE] transition-colors truncate">
                                    {{ $report->student->first_name }} {{ $report->student->last_name }} - {{ $report->month }} {{ $report->year }}
                                </a>
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold
                                    @if($report->status === 'draft') bg-amber-100 text-amber-700 dark:bg-amber-900/30 dark:text-amber-400
                                    @elseif($report->status === 'submitted') bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400
                                    @elseif($report->status === 'approved') bg-emerald-100 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-400
                                    @elseif($report->status === 'director_approved') bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400
                                    @elseif($report->status === 'returned') bg-rose-100 text-rose-700 dark:bg-rose-900/30 dark:text-rose-400
                                    @else bg-slate-100 text-slate-700 dark:bg-slate-700 dark:text-slate-400 @endif">
                                    {{ ucfirst(str_replace('_', ' ', $report->status)) }}
                                </span>
                                @if($report->imported_from_artifact)
                                    <span class="inline-flex items-center px-2 py-0.5 bg-purple-100 dark:bg-purple-900/30 text-purple-700 dark:text-purple-400 text-xs rounded-full">Imported</span>
                                @endif
                            </div>
                            <div class="flex flex-wrap items-center gap-4 text-sm text-slate-500 dark:text-slate-400">
                                @if($report->courses && count($report->courses) > 0)
                                    <span class="flex items-center gap-1">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                                        {{ count($report->courses) }} course(s)
                                    </span>
                                @endif
                                @if($report->skills_mastered && count($report->skills_mastered) > 0)
                                    <span class="flex items-center gap-1">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/></svg>
                                        {{ count($report->skills_mastered) }} skills
                                    </span>
                                @endif
                                <span class="flex items-center gap-1">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    {{ $report->created_at->diffForHumans() }}
                                </span>
                            </div>
                            @if($report->comments_observation)
                                <p class="mt-2 text-sm text-slate-600 dark:text-slate-400 line-clamp-1">{{ Str::limit($report->comments_observation, 120) }}</p>
                            @endif
                        </div>
                        <div class="flex items-center gap-1 opacity-60 group-hover:opacity-100 transition-opacity">
                            <a href="{{ route('tutor.reports.show', $report) }}" class="p-2 text-slate-500 hover:text-[#4B51FF] hover:bg-[#4B51FF]/10 rounded-lg transition-colors" title="View">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                            </a>
                            @if($report->canEdit())
                                <a href="{{ route('tutor.reports.edit', $report) }}" class="p-2 text-slate-500 hover:text-blue-600 hover:bg-blue-50 dark:hover:bg-blue-900/20 rounded-lg transition-colors" title="Edit">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                </a>
                            @endif
                            @if($report->status === 'draft')
                                <form action="{{ route('tutor.reports.destroy', $report) }}" method="POST" class="inline" onsubmit="return confirm('Delete this draft?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="p-2 text-slate-500 hover:text-rose-600 hover:bg-rose-50 dark:hover:bg-rose-900/20 rounded-lg transition-colors" title="Delete">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        <div class="mt-6">{{ $reports->links() }}</div>
    @endif
</div>

<!-- Import Modal -->
<div id="importModal" class="hidden fixed inset-0 z-50 overflow-y-auto">
    <div class="flex items-center justify-center min-h-screen px-4">
        <div class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm" onclick="document.getElementById('importModal').classList.add('hidden')"></div>
        <div class="relative bg-white dark:bg-slate-800 rounded-2xl shadow-xl max-w-lg w-full p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-slate-900 dark:text-white">Import from Claude Artifact</h3>
                <button onclick="document.getElementById('importModal').classList.add('hidden')" class="p-1 text-slate-400 hover:text-slate-600"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg></button>
            </div>
            <div class="mb-4 p-4 bg-blue-50 dark:bg-blue-900/20 rounded-xl">
                <p class="text-sm text-blue-700 dark:text-blue-400"><strong>How to import:</strong> In the Claude Artifact, click "Copy Report Data" then paste the JSON below.</p>
            </div>
            <form id="importForm" class="space-y-4">
                @csrf
                <div>
                    <label for="json_data" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Paste Report JSON</label>
                    <textarea id="json_data" name="json_data" rows="8" class="w-full px-4 py-3 bg-slate-50 dark:bg-slate-700 border border-slate-200 dark:border-slate-600 rounded-xl focus:ring-2 focus:ring-[#4B51FF] focus:border-transparent font-mono text-sm" placeholder='{"studentName": "...", "month": "...", ...}'></textarea>
                </div>
                <div id="importError" class="hidden p-3 bg-rose-50 dark:bg-rose-900/20 text-rose-700 dark:text-rose-400 rounded-lg text-sm"></div>
                <div id="importSuccess" class="hidden p-3 bg-emerald-50 dark:bg-emerald-900/20 text-emerald-700 dark:text-emerald-400 rounded-lg text-sm"></div>
                <div class="flex justify-end gap-3">
                    <button type="button" onclick="document.getElementById('importModal').classList.add('hidden')" class="px-4 py-2 text-slate-600 dark:text-slate-400 font-medium">Cancel</button>
                    <button type="submit" id="importBtn" class="px-6 py-2 bg-gradient-to-r from-[#1D2A6D] to-[#4B51FF] text-white font-semibold rounded-xl hover:opacity-90">Import Report</button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.getElementById('importForm').addEventListener('submit', async function(e) {
    e.preventDefault();
    const jsonData = document.getElementById('json_data').value.trim();
    const errorDiv = document.getElementById('importError');
    const successDiv = document.getElementById('importSuccess');
    const importBtn = document.getElementById('importBtn');
    errorDiv.classList.add('hidden');
    successDiv.classList.add('hidden');
    if (!jsonData) { errorDiv.textContent = 'Please paste the report JSON data.'; errorDiv.classList.remove('hidden'); return; }
    try { JSON.parse(jsonData); } catch (e) { errorDiv.textContent = 'Invalid JSON format.'; errorDiv.classList.remove('hidden'); return; }
    importBtn.disabled = true; importBtn.textContent = 'Importing...';
    try {
        const response = await fetch('{{ route("tutor.reports.import-artifact") }}', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' },
            body: JSON.stringify({ json_data: jsonData })
        });
        const result = await response.json();
        if (result.success) { successDiv.textContent = 'Redirecting...'; successDiv.classList.remove('hidden'); window.location.href = result.redirect; }
        else { errorDiv.innerHTML = result.message + (result.available_students ? '<br><br><strong>Your students:</strong> ' + result.available_students.join(', ') : ''); errorDiv.classList.remove('hidden'); importBtn.disabled = false; importBtn.textContent = 'Import Report'; }
    } catch (error) { errorDiv.textContent = 'Network error.'; errorDiv.classList.remove('hidden'); importBtn.disabled = false; importBtn.textContent = 'Import Report'; }
});
</script>
@endpush
</x-tutor-layout>
