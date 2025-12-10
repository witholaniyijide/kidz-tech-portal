<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-2xl text-white">
            {{ __('Director Final Approval') }}
        </h2>
    </x-slot>

    <x-slot name="title">{{ __('Director Final Approval') }}</x-slot>

    {{-- Animated Background - Director Royal Blue to Purple Gradient --}}
    <div class="min-h-screen bg-gradient-to-br from-blue-50 via-indigo-50 to-purple-50 dark:from-slate-900 dark:via-slate-800 dark:to-slate-900 py-12 relative overflow-hidden">
        {{-- Floating Orbs Background --}}
        <div class="absolute top-0 left-0 w-72 h-72 bg-blue-400 dark:bg-blue-900 rounded-full mix-blend-multiply filter blur-xl opacity-70 animate-float"></div>
        <div class="absolute top-0 right-0 w-72 h-72 bg-purple-400 dark:bg-purple-900 rounded-full mix-blend-multiply filter blur-xl opacity-70 animate-float" style="animation-delay: 2s;"></div>
        <div class="absolute -bottom-8 left-20 w-72 h-72 bg-indigo-400 dark:bg-indigo-900 rounded-full mix-blend-multiply filter blur-xl opacity-70 animate-float" style="animation-delay: 4s;"></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">

            {{-- Page Header --}}
            <div class="mb-8 flex justify-between items-start flex-wrap gap-4">
                <div>
                    <h1 class="text-4xl font-bold text-gray-900 dark:text-white mb-2">
                        Manager Approved Reports — Awaiting Director Review
                    </h1>
                    <p class="text-gray-600 dark:text-gray-400">
                        Final review and approval of tutor reports approved by managers
                    </p>
                </div>
                <div class="flex items-center gap-3">
                    <a href="{{ route('director.activity-logs.index') }}" class="px-4 py-2 bg-white/20 dark:bg-gray-800/30 text-gray-700 dark:text-gray-300 rounded-xl border border-white/10 hover:bg-white/30 dark:hover:bg-gray-800/50 transition-colors flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        Activity Logs
                    </a>
                </div>
            </div>

            {{-- Success/Error Messages --}}
            @if(session('success'))
                <div class="mb-6 bg-green-100 dark:bg-green-900/30 border border-green-400 dark:border-green-700 text-green-800 dark:text-green-400 px-6 py-4 rounded-xl">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="mb-6 bg-red-100 dark:bg-red-900/30 border border-red-400 dark:border-red-700 text-red-800 dark:text-red-400 px-6 py-4 rounded-xl">
                    {{ session('error') }}
                </div>
            @endif

            @if(session('warning'))
                <div class="mb-6 bg-amber-100 dark:bg-amber-900/30 border border-amber-400 dark:border-amber-700 text-amber-800 dark:text-amber-400 px-6 py-4 rounded-xl">
                    {{ session('warning') }}
                </div>
            @endif

            @if(session('info'))
                <div class="mb-6 bg-blue-100 dark:bg-blue-900/30 border border-blue-400 dark:border-blue-700 text-blue-800 dark:text-blue-400 px-6 py-4 rounded-xl">
                    {{ session('info') }}
                </div>
            @endif

            {{-- Filters --}}
            <div class="bg-white/30 dark:bg-gray-900/30 backdrop-blur-md border border-white/10 rounded-2xl p-6 shadow-lg mb-8">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Filters</h3>
                <form method="GET" action="{{ route('director.reports.index') }}" class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    {{-- Month Filter --}}
                    <div>
                        <label for="month" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Month</label>
                        <select name="month" id="month" class="w-full px-4 py-3 bg-white/50 dark:bg-gray-800/50 border border-gray-300 dark:border-gray-600 rounded-xl text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <option value="">All Months</option>
                            @foreach($months as $month)
                                <option value="{{ $month }}" {{ request('month') == $month ? 'selected' : '' }}>
                                    {{ $month }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Tutor Filter --}}
                    <div>
                        <label for="tutor_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Tutor</label>
                        <select name="tutor_id" id="tutor_id" class="w-full px-4 py-3 bg-white/50 dark:bg-gray-800/50 border border-gray-300 dark:border-gray-600 rounded-xl text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <option value="">All Tutors</option>
                            @foreach($tutors as $tutor)
                                <option value="{{ $tutor->id }}" {{ request('tutor_id') == $tutor->id ? 'selected' : '' }}>
                                    {{ $tutor->fullName() }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Student Filter --}}
                    <div>
                        <label for="student_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Student</label>
                        <select name="student_id" id="student_id" class="w-full px-4 py-3 bg-white/50 dark:bg-gray-800/50 border border-gray-300 dark:border-gray-600 rounded-xl text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <option value="">All Students</option>
                            @foreach($students as $student)
                                <option value="{{ $student->id }}" {{ request('student_id') == $student->id ? 'selected' : '' }}>
                                    {{ $student->fullName() }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Filter Buttons --}}
                    <div class="md:col-span-3 flex gap-3">
                        <button type="submit" class="px-6 py-3 bg-gradient-to-r from-blue-500 to-purple-500 text-white rounded-xl hover:shadow-lg transform hover:-translate-y-1 transition-all font-medium flex items-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                            </svg>
                            Apply Filters
                        </button>
                        <a href="{{ route('director.reports.index') }}" class="px-6 py-3 bg-white/20 dark:bg-gray-800/30 text-gray-700 dark:text-gray-300 rounded-xl border border-white/10 hover:bg-white/30 dark:hover:bg-gray-800/50 transition-colors flex items-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                            Clear
                        </a>
                    </div>
                </form>
            </div>

            {{-- Reports List --}}
            @if($reports->isEmpty())
                <div class="bg-white/30 dark:bg-gray-900/30 backdrop-blur-md border border-white/10 rounded-2xl p-12 text-center shadow-lg">
                    <svg class="w-16 h-16 mx-auto text-gray-400 dark:text-gray-600 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-2">No Reports Pending Director Approval</h3>
                    <p class="text-gray-600 dark:text-gray-400">There are currently no manager-approved reports awaiting your final approval</p>
                </div>
            @else
                <div class="bg-white/30 dark:bg-gray-900/30 backdrop-blur-md border border-white/10 rounded-2xl shadow-lg overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead>
                                <tr class="border-b border-white/10">
                                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">Student</th>
                                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">Tutor</th>
                                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">Month</th>
                                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">Manager Comment</th>
                                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">Approved by Manager</th>
                                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-white/10">
                                @foreach($reports as $report)
                                    <tr class="hover:bg-white/10 dark:hover:bg-gray-800/30 transition-colors">
                                        <td class="px-6 py-4">
                                            <div class="flex items-center">
                                                <div>
                                                    <div class="text-sm font-medium text-gray-900 dark:text-white">
                                                        {{ $report->student->fullName() }}
                                                    </div>
                                                    <div class="text-sm text-gray-600 dark:text-gray-400">
                                                        Age {{ $report->student->age }}
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="text-sm text-gray-900 dark:text-white">{{ $report->tutor->fullName() }}</div>
                                            <div class="text-sm text-gray-600 dark:text-gray-400">{{ $report->tutor->email }}</div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="text-sm font-medium text-gray-900 dark:text-white">{{ $report->month }}</div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="text-sm text-gray-700 dark:text-gray-300 max-w-xs truncate">
                                                {{ $report->manager_comment ? \Illuminate\Support\Str::limit($report->manager_comment, 80) : 'No comment' }}
                                            </div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="text-sm text-gray-900 dark:text-white">
                                                {{ $report->approved_by_manager_at ? $report->approved_by_manager_at->format('M d, Y') : 'N/A' }}
                                            </div>
                                            <div class="text-xs text-gray-600 dark:text-gray-400">
                                                {{ $report->approved_by_manager_at ? $report->approved_by_manager_at->format('g:i A') : '' }}
                                            </div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <a href="{{ route('director.reports.show', $report) }}" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-blue-500 to-purple-500 text-white rounded-xl hover:shadow-lg transform hover:-translate-y-1 transition-all font-medium text-sm">
                                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                </svg>
                                                Review & Approve
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                {{-- Pagination --}}
                <div class="mt-6">
                    {{ $reports->links() }}
                </div>
            @endif

        </div>
    </div>
</x-app-layout>
