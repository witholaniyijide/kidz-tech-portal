<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-2xl text-white">Director Activity Logs</h2>
    </x-slot>

    <x-slot name="title">Activity Logs</x-slot>

    <div class="min-h-screen bg-gradient-to-br from-blue-50 via-indigo-50 to-purple-50 dark:from-slate-900 dark:via-slate-800 dark:to-slate-900 py-12 relative overflow-hidden">
        <div class="absolute top-0 left-0 w-72 h-72 bg-blue-400 dark:bg-blue-900 rounded-full mix-blend-multiply filter blur-xl opacity-70 animate-float"></div>
        <div class="absolute top-0 right-0 w-72 h-72 bg-purple-400 dark:bg-purple-900 rounded-full mix-blend-multiply filter blur-xl opacity-70 animate-float" style="animation-delay: 2s;"></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="mb-8">
                <h1 class="text-4xl font-bold text-gray-900 dark:text-white mb-2">Activity Logs</h1>
                <p class="text-gray-600 dark:text-gray-400">Track all director actions and system activities</p>
            </div>

            {{-- Filters --}}
            <div class="bg-white/30 dark:bg-gray-900/30 backdrop-blur-md border border-white/10 rounded-2xl p-6 shadow-lg mb-8">
                <form method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <div>
                        <label for="action_type" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Action Type</label>
                        <select name="action_type" id="action_type" class="w-full px-4 py-3 bg-white/50 dark:bg-gray-800/50 border border-gray-300 dark:border-gray-600 rounded-xl text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500">
                            <option value="">All Actions</option>
                            @foreach($actionTypes as $type)
                                <option value="{{ $type }}" {{ request('action_type') == $type ? 'selected' : '' }}>{{ ucfirst(str_replace('_', ' ', $type)) }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label for="model_type" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Model Type</label>
                        <select name="model_type" id="model_type" class="w-full px-4 py-3 bg-white/50 dark:bg-gray-800/50 border border-gray-300 dark:border-gray-600 rounded-xl text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500">
                            <option value="">All Types</option>
                            @foreach($modelTypes as $type)
                                <option value="{{ $type }}" {{ request('model_type') == $type ? 'selected' : '' }}>{{ class_basename($type) }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label for="from_date" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">From Date</label>
                        <input type="date" name="from_date" id="from_date" value="{{ request('from_date') }}" class="w-full px-4 py-3 bg-white/50 dark:bg-gray-800/50 border border-gray-300 dark:border-gray-600 rounded-xl text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500">
                    </div>

                    <div>
                        <label for="to_date" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">To Date</label>
                        <input type="date" name="to_date" id="to_date" value="{{ request('to_date') }}" class="w-full px-4 py-3 bg-white/50 dark:bg-gray-800/50 border border-gray-300 dark:border-gray-600 rounded-xl text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500">
                    </div>

                    <div class="md:col-span-4 flex gap-3">
                        <button type="submit" class="px-6 py-3 bg-gradient-to-r from-blue-500 to-purple-500 text-white rounded-xl hover:shadow-lg transform hover:-translate-y-1 transition-all font-medium flex items-center">
                            Apply Filters
                        </button>
                        <a href="{{ route('director.activity-logs.index') }}" class="px-6 py-3 bg-white/20 dark:bg-gray-800/30 text-gray-700 dark:text-gray-300 rounded-xl border border-white/10 hover:bg-white/30 transition-colors">
                            Clear
                        </a>
                    </div>
                </form>
            </div>

            {{-- Activity Logs Table --}}
            @if($logs->isEmpty())
                <div class="bg-white/30 dark:bg-gray-900/30 backdrop-blur-md border border-white/10 rounded-2xl p-12 text-center shadow-lg">
                    <svg class="w-16 h-16 mx-auto text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-2">No Activity Logs</h3>
                    <p class="text-gray-600 dark:text-gray-400">No activities match your filters</p>
                </div>
            @else
                <div class="bg-white/30 dark:bg-gray-900/30 backdrop-blur-md border border-white/10 rounded-2xl shadow-lg overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead>
                                <tr class="border-b border-white/10 bg-white/10">
                                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase">Time</th>
                                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase">Director</th>
                                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase">Action</th>
                                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase">Model</th>
                                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase">IP Address</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-white/10">
                                @foreach($logs as $log)
                                    <tr class="hover:bg-white/10 dark:hover:bg-gray-800/30 transition-colors">
                                        <td class="px-6 py-4">
                                            <div class="text-sm text-gray-900 dark:text-white">{{ $log->created_at->format('M d, Y') }}</div>
                                            <div class="text-xs text-gray-600 dark:text-gray-400">{{ $log->created_at->format('g:i A') }}</div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="text-sm font-medium text-gray-900 dark:text-white">{{ $log->director->name ?? 'Unknown' }}</div>
                                            <div class="text-xs text-gray-600 dark:text-gray-400">{{ $log->director->email ?? '' }}</div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-purple-100 text-purple-800 dark:bg-purple-900/30 dark:text-purple-400">
                                                {{ ucfirst(str_replace('_', ' ', $log->action_type)) }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4">
                                            @if($log->model_type)
                                                <div class="text-sm text-gray-900 dark:text-white">{{ class_basename($log->model_type) }}</div>
                                                <div class="text-xs text-gray-600 dark:text-gray-400">ID: {{ $log->model_id }}</div>
                                            @else
                                                <span class="text-sm text-gray-500 dark:text-gray-400">N/A</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="text-xs text-gray-700 dark:text-gray-300">{{ $log->ip_address ?? 'N/A' }}</div>
                                            <div class="text-xs text-gray-500 dark:text-gray-500 truncate max-w-xs" title="{{ $log->user_agent }}">
                                                {{ \Illuminate\Support\Str::limit($log->user_agent ?? '', 40) }}
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="mt-6">
                    {{ $logs->links() }}
                </div>
            @endif
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
