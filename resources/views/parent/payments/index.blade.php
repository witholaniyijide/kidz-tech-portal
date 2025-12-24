<x-parent-layout title="Payments">
    <div class="space-y-6">
        <!-- Page Header -->
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Payments</h1>
                <p class="text-gray-600 dark:text-gray-400">View payment history and outstanding balances</p>
            </div>
        </div>

        <!-- Summary Cards -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <!-- Total Paid -->
            <div class="glass-card rounded-2xl p-5 hover-lift">
                <div class="flex items-center justify-between mb-3">
                    <div class="w-12 h-12 rounded-xl bg-green-100 dark:bg-green-900/30 flex items-center justify-center">
                        <svg class="w-6 h-6 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                </div>
                <p class="text-2xl font-bold text-gray-900 dark:text-white">N{{ number_format($totalPaid, 0) }}</p>
                <p class="text-sm text-gray-500 dark:text-gray-400">Total Paid</p>
            </div>

            <!-- Pending -->
            <div class="glass-card rounded-2xl p-5 hover-lift">
                <div class="flex items-center justify-between mb-3">
                    <div class="w-12 h-12 rounded-xl bg-yellow-100 dark:bg-yellow-900/30 flex items-center justify-center">
                        <svg class="w-6 h-6 text-yellow-600 dark:text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                </div>
                <p class="text-2xl font-bold text-gray-900 dark:text-white">N{{ number_format($totalPending, 0) }}</p>
                <p class="text-sm text-gray-500 dark:text-gray-400">Pending</p>
            </div>

            <!-- Overdue -->
            <div class="glass-card rounded-2xl p-5 hover-lift">
                <div class="flex items-center justify-between mb-3">
                    <div class="w-12 h-12 rounded-xl bg-red-100 dark:bg-red-900/30 flex items-center justify-center">
                        <svg class="w-6 h-6 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                </div>
                <p class="text-2xl font-bold text-gray-900 dark:text-white">N{{ number_format($totalOverdue, 0) }}</p>
                <p class="text-sm text-gray-500 dark:text-gray-400">Overdue</p>
            </div>

            <!-- Outstanding Balance -->
            <div class="glass-card rounded-2xl p-5 hover-lift border-2 border-[#F5A623]">
                <div class="flex items-center justify-between mb-3">
                    <div class="w-12 h-12 rounded-xl bg-gradient-parent flex items-center justify-center">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                </div>
                <p class="text-2xl font-bold text-gray-900 dark:text-white">N{{ number_format($outstandingBalance, 0) }}</p>
                <p class="text-sm text-gray-500 dark:text-gray-400">Outstanding Balance</p>
            </div>
        </div>

        <!-- Filters -->
        <div class="glass-card rounded-2xl p-4">
            <form method="GET" action="{{ route('parent.payments.index') }}" class="flex flex-wrap gap-4">
                <!-- Child Filter -->
                <div class="flex-1 min-w-[200px]">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Child</label>
                    <select name="child_id" class="w-full rounded-xl border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-[#F5A623] focus:border-[#F5A623]">
                        <option value="">All Children</option>
                        @foreach($children as $child)
                            <option value="{{ $child->id }}" {{ $selectedChildId == $child->id ? 'selected' : '' }}>
                                {{ $child->first_name }} {{ $child->last_name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Status Filter -->
                <div class="flex-1 min-w-[150px]">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Status</label>
                    <select name="status" class="w-full rounded-xl border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-[#F5A623] focus:border-[#F5A623]">
                        <option value="">All Statuses</option>
                        <option value="paid" {{ $status === 'paid' ? 'selected' : '' }}>Paid</option>
                        <option value="pending" {{ $status === 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="overdue" {{ $status === 'overdue' ? 'selected' : '' }}>Overdue</option>
                        <option value="partial" {{ $status === 'partial' ? 'selected' : '' }}>Partial</option>
                    </select>
                </div>

                <div class="flex items-end">
                    <button type="submit" class="btn-parent-primary px-6 py-2.5 rounded-xl font-medium">
                        Filter
                    </button>
                </div>
            </form>
        </div>

        <!-- Payments List -->
        <div class="glass-card rounded-2xl overflow-hidden">
            <div class="p-4 border-b border-gray-200 dark:border-gray-700">
                <h3 class="font-semibold text-gray-900 dark:text-white">Payment History</h3>
            </div>

            @if($payments->count() > 0)
                <div class="divide-y divide-gray-200 dark:divide-gray-700">
                    @foreach($payments as $payment)
                        <div class="p-4 hover:bg-gray-50 dark:hover:bg-gray-800/50 transition-colors">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center space-x-4">
                                    <div class="w-12 h-12 rounded-xl {{ $payment->status === 'paid' ? 'bg-green-100 dark:bg-green-900/30' : ($payment->status === 'overdue' ? 'bg-red-100 dark:bg-red-900/30' : 'bg-yellow-100 dark:bg-yellow-900/30') }} flex items-center justify-center">
                                        @if($payment->status === 'paid')
                                            <svg class="w-6 h-6 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                            </svg>
                                        @elseif($payment->status === 'overdue')
                                            <svg class="w-6 h-6 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                            </svg>
                                        @else
                                            <svg class="w-6 h-6 text-yellow-600 dark:text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                            </svg>
                                        @endif
                                    </div>
                                    <div>
                                        <p class="font-medium text-gray-900 dark:text-white">
                                            {{ $payment->description ?? 'Tuition Fee' }}
                                        </p>
                                        <p class="text-sm text-gray-500 dark:text-gray-400">
                                            {{ $payment->student->first_name ?? 'Student' }} - {{ $payment->created_at->format('M d, Y') }}
                                        </p>
                                    </div>
                                </div>

                                <div class="flex items-center space-x-4">
                                    <div class="text-right">
                                        <p class="text-lg font-bold text-gray-900 dark:text-white">
                                            N{{ number_format($payment->amount, 0) }}
                                        </p>
                                        @if($payment->status === 'paid')
                                            <span class="badge-paid px-2 py-0.5 rounded-full text-xs font-medium">Paid</span>
                                        @elseif($payment->status === 'pending')
                                            <span class="badge-pending px-2 py-0.5 rounded-full text-xs font-medium">Pending</span>
                                        @elseif($payment->status === 'overdue')
                                            <span class="badge-overdue px-2 py-0.5 rounded-full text-xs font-medium">Overdue</span>
                                        @else
                                            <span class="badge-partial px-2 py-0.5 rounded-full text-xs font-medium">{{ ucfirst($payment->status) }}</span>
                                        @endif
                                    </div>

                                    <div class="flex items-center space-x-2">
                                        <a href="{{ route('parent.payments.show', $payment) }}"
                                           class="p-2 text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                            </svg>
                                        </a>
                                        @if($payment->status === 'paid')
                                            <a href="{{ route('parent.payments.receipt', $payment) }}"
                                               class="p-2 text-[#F5A623] hover:text-[#D4910C] rounded-lg hover:bg-amber-50 dark:hover:bg-amber-900/20 transition-colors">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                                                </svg>
                                            </a>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                <div class="p-4 border-t border-gray-200 dark:border-gray-700">
                    {{ $payments->withQueryString()->links() }}
                </div>
            @else
                <div class="p-12 text-center">
                    <svg class="w-16 h-16 mx-auto text-gray-300 dark:text-gray-600 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-1">No payment records</h3>
                    <p class="text-gray-500 dark:text-gray-400">Payment history will appear here.</p>
                </div>
            @endif
        </div>
    </div>
</x-parent-layout>
