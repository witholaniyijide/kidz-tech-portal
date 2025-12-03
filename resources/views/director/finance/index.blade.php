<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-2xl text-white">{{ __('Finance Management') }}</h2>
    </x-slot>

    <div class="min-h-screen bg-gradient-to-br from-blue-50 via-indigo-50 to-purple-50 dark:from-slate-900 dark:via-slate-800 dark:to-slate-900 py-12 relative overflow-hidden">
        <div class="absolute top-0 left-0 w-72 h-72 bg-blue-400 dark:bg-blue-900 rounded-full mix-blend-multiply filter blur-xl opacity-70 animate-float"></div>
        <div class="absolute top-0 right-0 w-72 h-72 bg-purple-400 dark:bg-purple-900 rounded-full mix-blend-multiply filter blur-xl opacity-70 animate-float" style="animation-delay: 2s;"></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <h1 class="text-4xl font-bold text-gray-900 dark:text-white mb-8">Finance Management</h1>

            @if(session('success'))
                <div class="mb-6 bg-green-100 dark:bg-green-900/30 border border-green-400 dark:border-green-700 text-green-800 dark:text-green-400 px-6 py-4 rounded-xl">
                    {{ session('success') }}
                </div>
            @endif

            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
                <div class="bg-white/30 dark:bg-gray-900/30 backdrop-blur-md border border-white/10 rounded-2xl p-6 shadow-lg">
                    <div class="text-sm text-gray-600 dark:text-gray-400 mb-1">Total Revenue</div>
                    <div class="text-3xl font-bold text-green-600 dark:text-green-400">£{{ number_format($stats['total_revenue'], 2) }}</div>
                </div>
                <div class="bg-white/30 dark:bg-gray-900/30 backdrop-blur-md border border-white/10 rounded-2xl p-6 shadow-lg">
                    <div class="text-sm text-gray-600 dark:text-gray-400 mb-1">Monthly Revenue</div>
                    <div class="text-3xl font-bold text-blue-600 dark:text-blue-400">£{{ number_format($stats['monthly_revenue'], 2) }}</div>
                </div>
                <div class="bg-white/30 dark:bg-gray-900/30 backdrop-blur-md border border-white/10 rounded-2xl p-6 shadow-lg">
                    <div class="text-sm text-gray-600 dark:text-gray-400 mb-1">Yearly Revenue</div>
                    <div class="text-3xl font-bold text-purple-600 dark:text-purple-400">£{{ number_format($stats['yearly_revenue'], 2) }}</div>
                </div>
                <div class="bg-white/30 dark:bg-gray-900/30 backdrop-blur-md border border-white/10 rounded-2xl p-6 shadow-lg">
                    <div class="text-sm text-gray-600 dark:text-gray-400 mb-1">Pending Payments</div>
                    <div class="text-3xl font-bold text-amber-600 dark:text-amber-400">{{ $stats['pending_payments'] }}</div>
                </div>
            </div>

            <div class="bg-white/30 dark:bg-gray-900/30 backdrop-blur-md border border-white/10 rounded-2xl shadow-lg overflow-hidden">
                <div class="px-6 py-4 bg-gray-100/50 dark:bg-gray-800/50 border-b border-white/10">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Recent Income</h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-100/50 dark:bg-gray-800/50">
                            <tr>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase">Payment ID</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase">Date</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase">Student</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase">Amount</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase">Type</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                            @forelse($incomeRecords as $payment)
                                <tr class="hover:bg-white/10 dark:hover:bg-gray-800/30 transition">
                                    <td class="px-6 py-4 text-sm text-gray-900 dark:text-white">{{ $payment->payment_id }}</td>
                                    <td class="px-6 py-4 text-sm text-gray-900 dark:text-white">{{ $payment->payment_date }}</td>
                                    <td class="px-6 py-4 text-sm text-gray-900 dark:text-white">{{ $payment->student->first_name ?? 'N/A' }} {{ $payment->student->last_name ?? '' }}</td>
                                    <td class="px-6 py-4 text-sm text-gray-900 dark:text-white">£{{ number_format($payment->amount, 2) }}</td>
                                    <td class="px-6 py-4 text-sm text-gray-900 dark:text-white">{{ ucfirst($payment->payment_type) }}</td>
                                    <td class="px-6 py-4 text-sm">
                                        <span class="px-3 py-1 rounded-full text-xs font-semibold
                                            {{ $payment->status === 'completed' ? 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400' : 'bg-amber-100 text-amber-800 dark:bg-amber-900/30 dark:text-amber-400' }}">
                                            {{ ucfirst($payment->status) }}
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-8 text-center text-gray-500 dark:text-gray-400">No payment records found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="px-6 py-4 bg-gray-100/50 dark:bg-gray-800/50">
                    {{ $incomeRecords->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
