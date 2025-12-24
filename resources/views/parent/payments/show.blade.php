<x-parent-layout title="Payment Details">
    <div class="space-y-6">
        <!-- Back Button -->
        <div>
            <a href="{{ route('parent.payments.index') }}" class="inline-flex items-center text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white transition-colors">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Back to Payments
            </a>
        </div>

        <!-- Payment Card -->
        <div class="glass-card rounded-2xl p-6">
            <div class="flex items-start justify-between mb-6">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900 dark:text-white">
                        Payment #{{ $payment->id }}
                    </h1>
                    <p class="text-gray-600 dark:text-gray-400 mt-1">
                        {{ $payment->created_at->format('F j, Y') }}
                    </p>
                </div>
                @if($payment->status === 'paid')
                    <span class="badge-paid px-4 py-2 rounded-full text-sm font-medium">Paid</span>
                @elseif($payment->status === 'pending')
                    <span class="badge-pending px-4 py-2 rounded-full text-sm font-medium">Pending</span>
                @elseif($payment->status === 'overdue')
                    <span class="badge-overdue px-4 py-2 rounded-full text-sm font-medium">Overdue</span>
                @else
                    <span class="badge-partial px-4 py-2 rounded-full text-sm font-medium">{{ ucfirst($payment->status) }}</span>
                @endif
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Payment Details -->
                <div class="space-y-4">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Payment Details</h3>

                    <div class="space-y-3">
                        <div class="flex justify-between py-2 border-b border-gray-200 dark:border-gray-700">
                            <span class="text-gray-600 dark:text-gray-400">Description</span>
                            <span class="font-medium text-gray-900 dark:text-white">{{ $payment->description ?? 'Tuition Fee' }}</span>
                        </div>
                        <div class="flex justify-between py-2 border-b border-gray-200 dark:border-gray-700">
                            <span class="text-gray-600 dark:text-gray-400">Amount</span>
                            <span class="font-bold text-xl text-gray-900 dark:text-white">N{{ number_format($payment->amount, 0) }}</span>
                        </div>
                        <div class="flex justify-between py-2 border-b border-gray-200 dark:border-gray-700">
                            <span class="text-gray-600 dark:text-gray-400">Payment Date</span>
                            <span class="font-medium text-gray-900 dark:text-white">
                                {{ $payment->paid_at ? $payment->paid_at->format('M d, Y') : 'Not paid yet' }}
                            </span>
                        </div>
                        @if($payment->due_date)
                            <div class="flex justify-between py-2 border-b border-gray-200 dark:border-gray-700">
                                <span class="text-gray-600 dark:text-gray-400">Due Date</span>
                                <span class="font-medium text-gray-900 dark:text-white">{{ $payment->due_date->format('M d, Y') }}</span>
                            </div>
                        @endif
                        @if($payment->payment_method)
                            <div class="flex justify-between py-2 border-b border-gray-200 dark:border-gray-700">
                                <span class="text-gray-600 dark:text-gray-400">Payment Method</span>
                                <span class="font-medium text-gray-900 dark:text-white">{{ ucfirst($payment->payment_method) }}</span>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Student Info -->
                <div class="space-y-4">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Student</h3>
                    <div class="flex items-center space-x-4 p-4 bg-gray-50 dark:bg-gray-800 rounded-xl">
                        <div class="w-16 h-16 rounded-2xl bg-gradient-parent flex items-center justify-center text-white text-2xl font-bold">
                            {{ substr($payment->student->first_name ?? 'S', 0, 1) }}
                        </div>
                        <div>
                            <p class="font-medium text-gray-900 dark:text-white">
                                {{ $payment->student->first_name ?? '' }} {{ $payment->student->last_name ?? '' }}
                            </p>
                            <p class="text-sm text-gray-500 dark:text-gray-400">
                                {{ $payment->student->email ?? '' }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            @if($payment->notes)
                <div class="mt-6 pt-6 border-t border-gray-200 dark:border-gray-700">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">Notes</h3>
                    <p class="text-gray-600 dark:text-gray-400">{{ $payment->notes }}</p>
                </div>
            @endif

            @if($payment->status === 'paid')
                <div class="mt-6 pt-6 border-t border-gray-200 dark:border-gray-700">
                    <a href="{{ route('parent.payments.receipt', $payment) }}"
                       class="btn-parent-primary inline-flex items-center px-6 py-3 rounded-xl font-medium">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                        </svg>
                        Download Receipt
                    </a>
                </div>
            @endif
        </div>
    </div>
</x-parent-layout>
