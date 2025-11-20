<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Payment Details
            </h2>
            <div class="flex gap-2">
                <a href="{{ route('payments.edit', $payment) }}" style="display: inline-block; padding: 10px 20px; background-color: #2563EB; color: white; font-weight: bold; border-radius: 8px; text-decoration: none;">
                    Edit Payment
                </a>
                <a href="{{ route('payments.index') }}" style="display: inline-block; padding: 10px 20px; background-color: #6B7280; color: white; font-weight: bold; border-radius: 8px; text-decoration: none;">
                    ← Back to List
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            
            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    
                    <div class="text-center mb-6 pb-6 border-b">
                        <h3 class="text-2xl font-bold text-green-600">₦{{ number_format($payment->amount, 2) }}</h3>
                        <p class="text-sm text-gray-500 mt-1">Payment ID: {{ $payment->payment_id }}</p>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        
                        <div>
                            <h3 class="text-sm font-medium text-gray-500">Student</h3>
                            <p class="mt-1 text-lg font-semibold text-gray-900">{{ $payment->student->full_name }}</p>
                            <p class="text-sm text-gray-500">{{ $payment->student->student_id }}</p>
                        </div>

                        <div>
                            <h3 class="text-sm font-medium text-gray-500">Payment Date</h3>
                            <p class="mt-1 text-gray-900">{{ $payment->payment_date->format('M d, Y') }}</p>
                        </div>

                        <div>
                            <h3 class="text-sm font-medium text-gray-500">Payment Method</h3>
                            <p class="mt-1 text-gray-900">{{ $payment->payment_method }}</p>
                        </div>

                        <div>
                            <h3 class="text-sm font-medium text-gray-500">Payment Type</h3>
                            <p class="mt-1 text-gray-900">{{ ucfirst($payment->payment_type) }}</p>
                        </div>

                        @if($payment->reference_number)
                        <div>
                            <h3 class="text-sm font-medium text-gray-500">Reference Number</h3>
                            <p class="mt-1 text-gray-900">{{ $payment->reference_number }}</p>
                        </div>
                        @endif

                        <div>
                            <h3 class="text-sm font-medium text-gray-500">Status</h3>
                            <p class="mt-1">
                                @if($payment->status == 'completed')
                                    <span class="px-3 py-1 inline-flex text-sm leading-5 font-semibold rounded-full bg-green-100 text-green-800">Completed</span>
                                @elseif($payment->status == 'pending')
                                    <span class="px-3 py-1 inline-flex text-sm leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">Pending</span>
                                @elseif($payment->status == 'failed')
                                    <span class="px-3 py-1 inline-flex text-sm leading-5 font-semibold rounded-full bg-red-100 text-red-800">Failed</span>
                                @else
                                    <span class="px-3 py-1 inline-flex text-sm leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">Refunded</span>
                                @endif
                            </p>
                        </div>

                        @if($payment->month && $payment->year)
                        <div>
                            <h3 class="text-sm font-medium text-gray-500">Period</h3>
                            <p class="mt-1 text-gray-900">{{ $payment->month }} {{ $payment->year }}</p>
                        </div>
                        @endif

                        <div>
                            <h3 class="text-sm font-medium text-gray-500">Recorded By</h3>
                            <p class="mt-1 text-gray-900">{{ $payment->recordedBy->name }}</p>
                            <p class="text-xs text-gray-500">{{ $payment->created_at->format('M d, Y H:i A') }}</p>
                        </div>

                    </div>

                    @if($payment->notes)
                    <div class="mb-6 pb-6 border-t pt-6">
                        <h3 class="text-sm font-medium text-gray-500 mb-2">Notes</h3>
                        <p class="text-gray-900">{{ $payment->notes }}</p>
                    </div>
                    @endif

                    <div class="flex gap-3 pt-6 border-t">
                        <a href="{{ route('payments.edit', $payment) }}" style="display: inline-block; padding: 10px 20px; background-color: #2563EB; color: white; font-weight: bold; border-radius: 8px; text-decoration: none;">
                            Edit Payment
                        </a>
                        
                        <form action="{{ route('payments.destroy', $payment) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this payment?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" style="display: inline-block; padding: 10px 20px; background-color: #DC2626; color: white; font-weight: bold; border-radius: 8px; border: none; cursor: pointer;">
                                Delete Payment
                            </button>
                        </form>
                    </div>

                </div>
            </div>

        </div>
    </div>
</x-app-layout>
