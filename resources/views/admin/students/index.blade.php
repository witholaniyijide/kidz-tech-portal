<x-app-layout>
    <x-slot name="header">{{ __('Student Management') }}</x-slot>
    <x-slot name="title">{{ __('Admin - Students') }}</x-slot>

    <div class="min-h-screen bg-gradient-to-br from-[#423A8E]/5 via-[#00CCCD]/5 to-blue-50 dark:from-slate-900 dark:via-slate-800 dark:to-slate-900 py-8 relative overflow-hidden">
        {{-- Floating Orbs --}}
        <div class="absolute top-0 left-0 w-72 h-72 bg-[#423A8E]/30 dark:bg-[#423A8E]/30 rounded-full mix-blend-multiply filter blur-xl opacity-70 animate-float"></div>
        <div class="absolute top-0 right-0 w-72 h-72 bg-[#00CCCD]/30 dark:bg-[#00CCCD]/40 rounded-full mix-blend-multiply filter blur-xl opacity-70 animate-float" style="animation-delay: 2s;"></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            {{-- Flash Messages --}}
            @if(session('success'))
                <div class="mb-6 bg-emerald-100 dark:bg-emerald-900/30 border border-emerald-400 text-emerald-800 dark:text-emerald-400 px-6 py-4 rounded-xl">
                    {{ session('success') }}
                </div>
            @endif

            {{-- Header --}}
            <div class="flex flex-wrap items-center justify-between gap-4 mb-8">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Student Management</h1>
                    <p class="text-gray-600 dark:text-gray-400 mt-1">Manage all students in the system</p>
                </div>
                <a href="{{ route('admin.students.create') }}" class="inline-flex items-center px-5 py-3 bg-gradient-to-r from-[#423A8E] to-[#00CCCD] text-white font-medium rounded-xl hover:shadow-lg transform hover:-translate-y-0.5 transition-all">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
                    </svg>
                    Add Student
                </a>
            </div>

            {{-- Stat Cards --}}
            <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-5 gap-4 mb-8">
                <div class="bg-white/60 dark:bg-gray-800/60 backdrop-blur-md border border-white/20 rounded-2xl p-5 shadow">
                    <div class="text-3xl font-bold text-gray-900 dark:text-white">{{ $stats['total'] ?? 0 }}</div>
                    <div class="text-sm text-gray-600 dark:text-gray-400">Total Students</div>
                </div>
                <div class="bg-emerald-50 dark:bg-emerald-900/30 border border-emerald-200 dark:border-emerald-800/30 rounded-2xl p-5 shadow">
                    <div class="text-3xl font-bold text-emerald-600">{{ $stats['active'] ?? 0 }}</div>
                    <div class="text-sm text-emerald-700 dark:text-emerald-400">Active</div>
                </div>
                <div class="bg-gray-50 dark:bg-gray-700/30 border border-gray-200 dark:border-gray-600/30 rounded-2xl p-5 shadow">
                    <div class="text-3xl font-bold text-gray-600 dark:text-gray-300">{{ $stats['inactive'] ?? 0 }}</div>
                    <div class="text-sm text-gray-600 dark:text-gray-400">Inactive</div>
                </div>
                <div class="bg-blue-50 dark:bg-blue-900/30 border border-blue-200 dark:border-blue-800/30 rounded-2xl p-5 shadow">
                    <div class="text-3xl font-bold text-blue-600">{{ $stats['graduated'] ?? 0 }}</div>
                    <div class="text-sm text-blue-700 dark:text-blue-400">Graduated</div>
                </div>
                <div class="bg-amber-50 dark:bg-amber-900/30 border border-amber-200 dark:border-amber-800/30 rounded-2xl p-5 shadow">
                    <div class="text-3xl font-bold text-amber-600">{{ $stats['withdrawn'] ?? 0 }}</div>
                    <div class="text-sm text-amber-700 dark:text-amber-400">Withdrawn</div>
                </div>
            </div>

            {{-- Filters --}}
            <div class="bg-white/60 dark:bg-gray-800/60 backdrop-blur-md border border-white/20 rounded-2xl p-5 shadow mb-6">
                <form method="GET" class="space-y-4">
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                        <div class="sm:col-span-2 lg:col-span-1">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Search</label>
                            <input type="text" name="search" value="{{ request('search') }}" placeholder="Name or email..."
                                   class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg focus:ring-2 focus:ring-[#423A8E]">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Status</label>
                            <select name="status" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg focus:ring-2 focus:ring-[#423A8E]">
                                <option value="">All Status</option>
                                <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active</option>
                                <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
                                <option value="graduated" {{ request('status') === 'graduated' ? 'selected' : '' }}>Graduated</option>
                                <option value="withdrawn" {{ request('status') === 'withdrawn' ? 'selected' : '' }}>Withdrawn</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Sort By</label>
                            <select name="sort" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg focus:ring-2 focus:ring-[#423A8E]">
                                <option value="created_at" {{ ($sortBy ?? 'created_at') === 'created_at' ? 'selected' : '' }}>Date Added</option>
                                <option value="first_name" {{ ($sortBy ?? '') === 'first_name' ? 'selected' : '' }}>First Name (A-Z)</option>
                                <option value="last_name" {{ ($sortBy ?? '') === 'last_name' ? 'selected' : '' }}>Last Name (A-Z)</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Order</label>
                            <select name="dir" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg focus:ring-2 focus:ring-[#423A8E]">
                                <option value="asc" {{ ($sortDir ?? 'desc') === 'asc' ? 'selected' : '' }}>A-Z / Oldest</option>
                                <option value="desc" {{ ($sortDir ?? 'desc') === 'desc' ? 'selected' : '' }}>Z-A / Newest</option>
                            </select>
                        </div>
                    </div>
                    <div class="flex flex-wrap gap-2 pt-2">
                        <button type="submit" class="px-5 py-2 bg-[#423A8E] text-white rounded-lg hover:bg-[#423A8E] transition-colors">
                            Filter
                        </button>
                        @if(request()->hasAny(['search', 'status', 'sort', 'dir']))
                            <a href="{{ route('admin.students.index') }}" class="px-5 py-2 bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-300 dark:hover:bg-gray-600 transition-colors">
                                Clear
                            </a>
                        @endif
                    </div>
                </form>
            </div>

            {{-- Students Table --}}
            <div class="bg-white/60 dark:bg-gray-800/60 backdrop-blur-md border border-white/20 rounded-2xl shadow overflow-hidden">
                @if($students->isEmpty())
                    <div class="p-12 text-center">
                        <div class="text-6xl mb-4">👨‍🎓</div>
                        <h3 class="text-xl font-semibold text-gray-700 dark:text-gray-300 mb-2">No Students Found</h3>
                        <p class="text-gray-500 dark:text-gray-400 mb-4">Get started by adding your first student</p>
                        <a href="{{ route('admin.students.create') }}" class="inline-flex items-center px-5 py-2 bg-[#423A8E] text-white rounded-lg hover:bg-[#423A8E]">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                            </svg>
                            Add Student
                        </a>
                    </div>
                @else
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead class="bg-gray-50 dark:bg-gray-700/50">
                                <tr>
                                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">Student</th>
                                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">Tutor Assigned</th>
                                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">Schedule</th>
                                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">Status</th>
                                    <th class="px-6 py-4 text-right text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                                @foreach($students as $student)
                                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/30 transition-colors">
                                        <td class="px-6 py-4">
                                            <div class="flex items-center">
                                                <div class="w-10 h-10 bg-gradient-to-br from-[#00CCCD] to-[#00CCCD] rounded-full flex items-center justify-center text-white font-bold text-sm mr-3">
                                                    {{ strtoupper(substr($student->first_name, 0, 1)) }}{{ strtoupper(substr($student->last_name, 0, 1)) }}
                                                </div>
                                                <div>
                                                    <div class="font-semibold text-gray-900 dark:text-white">
                                                        {{ $student->first_name }} {{ $student->other_name }} {{ $student->last_name }}
                                                    </div>
                                                    @if($student->student_id)
                                                        <div class="text-sm text-gray-500 dark:text-gray-400">{{ $student->student_id }}</div>
                                                    @endif
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4">
                                            @if($student->tutor)
                                                <div class="text-sm text-gray-900 dark:text-white">{{ $student->tutor->first_name }} {{ $student->tutor->last_name }}</div>
                                            @else
                                                <span class="text-sm text-gray-400 italic">Unassigned</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="text-sm text-gray-600 dark:text-gray-400">
                                                {{ $student->classes_per_week ?? 0 }} classes/week
                                            </div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <span class="inline-flex px-3 py-1 text-xs font-semibold rounded-full
                                                @if($student->status === 'active') bg-emerald-100 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-400
                                                @elseif($student->status === 'inactive') bg-gray-100 text-gray-700 dark:bg-gray-700 dark:text-gray-300
                                                @elseif($student->status === 'graduated') bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400
                                                @else bg-amber-100 text-amber-700 dark:bg-amber-900/30 dark:text-amber-400
                                                @endif">
                                                {{ ucfirst($student->status) }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 text-right">
                                            <div class="flex items-center justify-end gap-2">
                                                <a href="{{ route('admin.students.show', $student) }}" class="p-2 text-gray-600 dark:text-gray-400 hover:text-[#423A8E] dark:hover:text-[#00CCCD] hover:bg-[#423A8E]/5 dark:hover:bg-[#423A8E]/40/20 rounded-lg transition-colors" title="View">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                                    </svg>
                                                </a>
                                                <a href="{{ route('admin.students.edit', $student) }}" class="p-2 text-gray-600 dark:text-gray-400 hover:text-blue-600 dark:hover:text-blue-400 hover:bg-blue-50 dark:hover:bg-blue-900/20 rounded-lg transition-colors" title="Edit">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                                    </svg>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    {{-- Pagination --}}
                    @if($students->hasPages())
                        <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700">
                            {{ $students->withQueryString()->links() }}
                        </div>
                    @endif
                @endif
            </div>
        </div>
    </div>

    @push('styles')
    <style>
        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-20px); }
        }
        .animate-float { animation: float 6s ease-in-out infinite; }
    </style>
    @endpush
</x-app-layout>
