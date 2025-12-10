<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-2xl text-white">{{ __('Tutor Management') }}</h2>
    </x-slot>
    <x-slot name="title">{{ __('Admin - Tutors') }}</x-slot>

    <div class="min-h-screen bg-gradient-to-br from-teal-50 via-cyan-50 to-blue-50 dark:from-slate-900 dark:via-slate-800 dark:to-slate-900 py-8 relative overflow-hidden">
        <div class="absolute top-0 left-0 w-72 h-72 bg-teal-300 dark:bg-teal-900 rounded-full mix-blend-multiply filter blur-xl opacity-70 animate-float"></div>
        <div class="absolute top-0 right-0 w-72 h-72 bg-cyan-300 dark:bg-cyan-900 rounded-full mix-blend-multiply filter blur-xl opacity-70 animate-float" style="animation-delay: 2s;"></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            @if(session('success'))
                <div class="mb-6 bg-emerald-100 dark:bg-emerald-900/30 border border-emerald-400 text-emerald-800 dark:text-emerald-400 px-6 py-4 rounded-xl">
                    {{ session('success') }}
                </div>
            @endif

            {{-- Header --}}
            <div class="flex flex-wrap items-center justify-between gap-4 mb-8">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Tutor Management</h1>
                    <p class="text-gray-600 dark:text-gray-400 mt-1">Manage all tutors in the system</p>
                </div>
                <a href="{{ route('admin.tutors.create') }}" class="inline-flex items-center px-5 py-3 bg-gradient-to-r from-teal-500 to-cyan-600 text-white font-medium rounded-xl hover:shadow-lg transform hover:-translate-y-0.5 transition-all">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
                    </svg>
                    Add Tutor
                </a>
            </div>

            {{-- Stat Cards --}}
            <div class="grid grid-cols-2 md:grid-cols-5 gap-4 mb-8">
                <div class="bg-white/60 dark:bg-gray-800/60 backdrop-blur-md border border-white/20 rounded-2xl p-5 shadow">
                    <div class="text-3xl font-bold text-gray-900 dark:text-white">{{ $stats['total'] ?? 0 }}</div>
                    <div class="text-sm text-gray-600 dark:text-gray-400">Total Tutors</div>
                </div>
                <div class="bg-emerald-50 dark:bg-emerald-900/30 border border-emerald-200 dark:border-emerald-800/30 rounded-2xl p-5 shadow">
                    <div class="text-3xl font-bold text-emerald-600">{{ $stats['active'] ?? 0 }}</div>
                    <div class="text-sm text-emerald-700 dark:text-emerald-400">Active</div>
                </div>
                <div class="bg-gray-50 dark:bg-gray-700/30 border border-gray-200 dark:border-gray-600/30 rounded-2xl p-5 shadow">
                    <div class="text-3xl font-bold text-gray-600 dark:text-gray-300">{{ $stats['inactive'] ?? 0 }}</div>
                    <div class="text-sm text-gray-600 dark:text-gray-400">Inactive</div>
                </div>
                <div class="bg-amber-50 dark:bg-amber-900/30 border border-amber-200 dark:border-amber-800/30 rounded-2xl p-5 shadow">
                    <div class="text-3xl font-bold text-amber-600">{{ $stats['on_leave'] ?? 0 }}</div>
                    <div class="text-sm text-amber-700 dark:text-amber-400">On Leave</div>
                </div>
                <div class="bg-red-50 dark:bg-red-900/30 border border-red-200 dark:border-red-800/30 rounded-2xl p-5 shadow">
                    <div class="text-3xl font-bold text-red-600">{{ $stats['resigned'] ?? 0 }}</div>
                    <div class="text-sm text-red-700 dark:text-red-400">Resigned</div>
                </div>
            </div>

            {{-- Filters --}}
            <div class="bg-white/60 dark:bg-gray-800/60 backdrop-blur-md border border-white/20 rounded-2xl p-5 shadow mb-6">
                <form method="GET" class="flex flex-wrap items-end gap-4">
                    <div class="flex-1 min-w-[200px]">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Search</label>
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Name or email..."
                               class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg focus:ring-2 focus:ring-teal-500">
                    </div>
                    <div class="w-40">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Status</label>
                        <select name="status" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg focus:ring-2 focus:ring-teal-500">
                            <option value="">All Status</option>
                            <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active</option>
                            <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
                            <option value="on_leave" {{ request('status') === 'on_leave' ? 'selected' : '' }}>On Leave</option>
                            <option value="resigned" {{ request('status') === 'resigned' ? 'selected' : '' }}>Resigned</option>
                        </select>
                    </div>
                    <button type="submit" class="px-5 py-2 bg-teal-600 text-white rounded-lg hover:bg-teal-700 transition-colors">
                        Filter
                    </button>
                    @if(request()->hasAny(['search', 'status']))
                        <a href="{{ route('admin.tutors.index') }}" class="px-5 py-2 bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-300 dark:hover:bg-gray-600 transition-colors">
                            Clear
                        </a>
                    @endif
                </form>
            </div>

            {{-- Tutors Table --}}
            <div class="bg-white/60 dark:bg-gray-800/60 backdrop-blur-md border border-white/20 rounded-2xl shadow overflow-hidden">
                @if($tutors->isEmpty())
                    <div class="p-12 text-center">
                        <div class="text-6xl mb-4">👨‍🏫</div>
                        <h3 class="text-xl font-semibold text-gray-700 dark:text-gray-300 mb-2">No Tutors Found</h3>
                        <p class="text-gray-500 dark:text-gray-400 mb-4">Get started by adding your first tutor</p>
                        <a href="{{ route('admin.tutors.create') }}" class="inline-flex items-center px-5 py-2 bg-teal-600 text-white rounded-lg hover:bg-teal-700">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                            </svg>
                            Add Tutor
                        </a>
                    </div>
                @else
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead class="bg-gray-50 dark:bg-gray-700/50">
                                <tr>
                                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">Tutor</th>
                                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">Contact</th>
                                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">Students</th>
                                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">Status</th>
                                    <th class="px-6 py-4 text-right text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                                @foreach($tutors as $tutor)
                                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/30 transition-colors">
                                        <td class="px-6 py-4">
                                            <div class="flex items-center">
                                                @if($tutor->profile_photo)
                                                    <img src="{{ Storage::url($tutor->profile_photo) }}" alt="" class="w-10 h-10 rounded-full object-cover mr-3">
                                                @else
                                                    <div class="w-10 h-10 bg-gradient-to-br from-cyan-400 to-blue-500 rounded-full flex items-center justify-center text-white font-bold text-sm mr-3">
                                                        {{ strtoupper(substr($tutor->first_name, 0, 1)) }}{{ strtoupper(substr($tutor->last_name, 0, 1)) }}
                                                    </div>
                                                @endif
                                                <div>
                                                    <div class="font-semibold text-gray-900 dark:text-white">
                                                        {{ $tutor->first_name }} {{ $tutor->last_name }}
                                                    </div>
                                                    <div class="text-sm text-gray-500 dark:text-gray-400">{{ $tutor->occupation ?? 'Tutor' }}</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="text-sm text-gray-900 dark:text-white">{{ $tutor->email }}</div>
                                            <div class="text-sm text-gray-500 dark:text-gray-400">{{ $tutor->phone ?? '-' }}</div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="flex items-center">
                                                <span class="text-lg font-semibold text-gray-900 dark:text-white">{{ $tutor->students_count ?? 0 }}</span>
                                                <span class="text-sm text-gray-500 dark:text-gray-400 ml-1">assigned</span>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <span class="inline-flex px-3 py-1 text-xs font-semibold rounded-full
                                                @if($tutor->status === 'active') bg-emerald-100 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-400
                                                @elseif($tutor->status === 'inactive') bg-gray-100 text-gray-700 dark:bg-gray-700 dark:text-gray-300
                                                @elseif($tutor->status === 'on_leave') bg-amber-100 text-amber-700 dark:bg-amber-900/30 dark:text-amber-400
                                                @else bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400
                                                @endif">
                                                {{ ucfirst(str_replace('_', ' ', $tutor->status)) }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 text-right">
                                            <div class="flex items-center justify-end gap-2">
                                                <a href="{{ route('admin.tutors.show', $tutor) }}" class="p-2 text-gray-600 dark:text-gray-400 hover:text-teal-600 dark:hover:text-teal-400 hover:bg-teal-50 dark:hover:bg-teal-900/20 rounded-lg transition-colors" title="View">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                                    </svg>
                                                </a>
                                                <a href="{{ route('admin.tutors.edit', $tutor) }}" class="p-2 text-gray-600 dark:text-gray-400 hover:text-blue-600 dark:hover:text-blue-400 hover:bg-blue-50 dark:hover:bg-blue-900/20 rounded-lg transition-colors" title="Edit">
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

                    @if($tutors->hasPages())
                        <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700">
                            {{ $tutors->withQueryString()->links() }}
                        </div>
                    @endif
                @endif
            </div>
        </div>
    </div>

    @push('styles')
    <style>
        @keyframes float { 0%, 100% { transform: translateY(0px); } 50% { transform: translateY(-20px); } }
        .animate-float { animation: float 6s ease-in-out infinite; }
    </style>
    @endpush
</x-app-layout>
