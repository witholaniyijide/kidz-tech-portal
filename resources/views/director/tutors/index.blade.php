<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-2xl text-white">{{ __('Tutors Management') }}</h2>
    </x-slot>

    <div class="min-h-screen bg-gradient-to-br from-blue-50 via-indigo-50 to-purple-50 dark:from-slate-900 dark:via-slate-800 dark:to-slate-900 py-12 relative overflow-hidden">
        <div class="absolute top-0 left-0 w-72 h-72 bg-blue-400 dark:bg-blue-900 rounded-full mix-blend-multiply filter blur-xl opacity-70 animate-float"></div>
        <div class="absolute top-0 right-0 w-72 h-72 bg-purple-400 dark:bg-purple-900 rounded-full mix-blend-multiply filter blur-xl opacity-70 animate-float" style="animation-delay: 2s;"></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="mb-8 flex justify-between items-start">
                <h1 class="text-4xl font-bold text-gray-900 dark:text-white">Tutors Management</h1>
                <a href="{{ route('director.tutors.create') }}" class="px-4 py-2 bg-gradient-to-r from-blue-600 to-purple-600 text-white rounded-xl hover:from-blue-700 hover:to-purple-700 transition-all shadow-lg">
                    Add Tutor
                </a>
            </div>

            @if(session('success'))
                <div class="mb-6 bg-green-100 dark:bg-green-900/30 border border-green-400 dark:border-green-700 text-green-800 dark:text-green-400 px-6 py-4 rounded-xl">
                    {{ session('success') }}
                </div>
            @endif

            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
                <div class="bg-white/30 dark:bg-gray-900/30 backdrop-blur-md border border-white/10 rounded-2xl p-6 shadow-lg">
                    <div class="text-sm text-gray-600 dark:text-gray-400 mb-1">Total Tutors</div>
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
                    <div class="text-sm text-gray-600 dark:text-gray-400 mb-1">On Leave</div>
                    <div class="text-3xl font-bold text-amber-600 dark:text-amber-400">{{ $stats['on_leave'] }}</div>
                </div>
            </div>

            <div class="bg-white/30 dark:bg-gray-900/30 backdrop-blur-md border border-white/10 rounded-2xl shadow-lg overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-100/50 dark:bg-gray-800/50">
                            <tr>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase">Tutor ID</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase">Name</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase">Email</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase">Status</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                            @forelse($tutors as $tutor)
                                <tr class="hover:bg-white/10 dark:hover:bg-gray-800/30 transition">
                                    <td class="px-6 py-4 text-sm text-gray-900 dark:text-white">{{ $tutor->tutor_id }}</td>
                                    <td class="px-6 py-4 text-sm text-gray-900 dark:text-white">{{ $tutor->first_name }} {{ $tutor->last_name }}</td>
                                    <td class="px-6 py-4 text-sm text-gray-900 dark:text-white">{{ $tutor->email }}</td>
                                    <td class="px-6 py-4 text-sm">
                                        <span class="px-3 py-1 rounded-full text-xs font-semibold
                                            {{ $tutor->status === 'active' ? 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400' : 'bg-gray-100 text-gray-800 dark:bg-gray-900/30 dark:text-gray-400' }}">
                                            {{ ucfirst($tutor->status) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-sm">
                                        <a href="{{ route('director.tutors.show', $tutor) }}" class="text-blue-600 dark:text-blue-400 hover:underline mr-3">View</a>
                                        <a href="{{ route('director.tutors.edit', $tutor) }}" class="text-purple-600 dark:text-purple-400 hover:underline">Edit</a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-8 text-center text-gray-500 dark:text-gray-400">No tutors found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="px-6 py-4 bg-gray-100/50 dark:bg-gray-800/50">
                    {{ $tutors->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
