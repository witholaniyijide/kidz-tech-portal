<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-2xl text-white">
            {{ __('Students Management') }}
        </h2>
    </x-slot>

    <x-slot name="title">{{ __('Students Management') }}</x-slot>

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
                        Students Management
                    </h1>
                    <p class="text-gray-600 dark:text-gray-400">
                        Manage all student records and information
                    </p>
                </div>
                <div class="flex items-center gap-3">
                    <a href="{{ route('director.students.create') }}" class="px-4 py-2 bg-gradient-to-r from-blue-600 to-purple-600 text-white rounded-xl hover:from-blue-700 hover:to-purple-700 transition-all flex items-center shadow-lg">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        Add Student
                    </a>
                </div>
            </div>

            {{-- Success Message --}}
            @if(session('success'))
                <div class="mb-6 bg-green-100 dark:bg-green-900/30 border border-green-400 dark:border-green-700 text-green-800 dark:text-green-400 px-6 py-4 rounded-xl">
                    {{ session('success') }}
                </div>
            @endif

            {{-- Statistics Cards --}}
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
                <div class="bg-white/30 dark:bg-gray-900/30 backdrop-blur-md border border-white/10 rounded-2xl p-6 shadow-lg">
                    <div class="text-sm text-gray-600 dark:text-gray-400 mb-1">Total Students</div>
                    <div class="text-3xl font-bold text-gray-900 dark:text-white">{{ $stats['total'] }}</div>
                </div>
                <div class="bg-white/30 dark:bg-gray-900/30 backdrop-blur-md border border-white/10 rounded-2xl p-6 shadow-lg">
                    <div class="text-sm text-gray-600 dark:text-gray-400 mb-1">Active</div>
                    <div class="text-3xl font-bold text-green-600 dark:text-green-400">{{ $stats['active'] }}</div>
                </div>
                <div class="bg-white/30 dark:bg-gray-900/30 backdrop-blur-md border border-white/10 rounded-2xl p-6 shadow-lg">
                    <div class="text-sm text-gray-600 dark:text-gray-400 mb-1">Inactive</div>
                    <div class="text-3xl font-bold text-gray-600 dark:text-gray-400">{{ $stats['inactive'] }}</div>
                </div>
                <div class="bg-white/30 dark:bg-gray-900/30 backdrop-blur-md border border-white/10 rounded-2xl p-6 shadow-lg">
                    <div class="text-sm text-gray-600 dark:text-gray-400 mb-1">On Hold</div>
                    <div class="text-3xl font-bold text-amber-600 dark:text-amber-400">{{ $stats['on_hold'] }}</div>
                </div>
            </div>

            {{-- Filters --}}
            <div class="bg-white/30 dark:bg-gray-900/30 backdrop-blur-md border border-white/10 rounded-2xl p-6 shadow-lg mb-8">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Filters</h3>
                <form method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <input type="text" name="search" placeholder="Search students..." value="{{ request('search') }}" class="px-4 py-3 bg-white/50 dark:bg-gray-800/50 border border-gray-300 dark:border-gray-600 rounded-xl text-gray-900 dark:text-white">
                    <select name="status" class="px-4 py-3 bg-white/50 dark:bg-gray-800/50 border border-gray-300 dark:border-gray-600 rounded-xl text-gray-900 dark:text-white">
                        <option value="">All Status</option>
                        <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                        <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                        <option value="on_hold" {{ request('status') == 'on_hold' ? 'selected' : '' }}>On Hold</option>
                    </select>
                    <select name="location" class="px-4 py-3 bg-white/50 dark:bg-gray-800/50 border border-gray-300 dark:border-gray-600 rounded-xl text-gray-900 dark:text-white">
                        <option value="">All Locations</option>
                        @foreach($locations as $location)
                            <option value="{{ $location }}" {{ request('location') == $location ? 'selected' : '' }}>{{ $location }}</option>
                        @endforeach
                    </select>
                    <button type="submit" class="px-6 py-3 bg-gradient-to-r from-blue-600 to-purple-600 text-white rounded-xl hover:from-blue-700 hover:to-purple-700 transition-all">
                        Filter
                    </button>
                </form>
            </div>

            {{-- Students Table --}}
            <div class="bg-white/30 dark:bg-gray-900/30 backdrop-blur-md border border-white/10 rounded-2xl shadow-lg overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-100/50 dark:bg-gray-800/50">
                            <tr>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase">Student ID</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase">Name</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase">Email</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase">Status</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                            @forelse($students as $student)
                                <tr class="hover:bg-white/10 dark:hover:bg-gray-800/30 transition">
                                    <td class="px-6 py-4 text-sm text-gray-900 dark:text-white">{{ $student->student_id }}</td>
                                    <td class="px-6 py-4 text-sm text-gray-900 dark:text-white">{{ $student->first_name }} {{ $student->last_name }}</td>
                                    <td class="px-6 py-4 text-sm text-gray-900 dark:text-white">{{ $student->email }}</td>
                                    <td class="px-6 py-4 text-sm">
                                        <span class="px-3 py-1 rounded-full text-xs font-semibold
                                            {{ $student->status === 'active' ? 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400' : '' }}
                                            {{ $student->status === 'inactive' ? 'bg-gray-100 text-gray-800 dark:bg-gray-900/30 dark:text-gray-400' : '' }}
                                            {{ $student->status === 'on_hold' ? 'bg-amber-100 text-amber-800 dark:bg-amber-900/30 dark:text-amber-400' : '' }}">
                                            {{ ucfirst($student->status) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-sm">
                                        <a href="{{ route('director.students.show', $student) }}" class="text-blue-600 dark:text-blue-400 hover:underline mr-3">View</a>
                                        <a href="{{ route('director.students.edit', $student) }}" class="text-purple-600 dark:text-purple-400 hover:underline">Edit</a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-8 text-center text-gray-500 dark:text-gray-400">
                                        No students found.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="px-6 py-4 bg-gray-100/50 dark:bg-gray-800/50">
                    {{ $students->links() }}
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
