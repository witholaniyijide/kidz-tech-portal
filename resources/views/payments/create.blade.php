<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Record New Payment
            </h2>
            <a href="{{ route('payments.index') }}" style="display: inline-block; padding: 10px 20px; background-color: #6B7280; color: white; font-weight: bold; border-radius: 8px; text-decoration: none;">
                ← Back to Payments
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    
                    @if($errors->any())
                        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4">
                            <strong class="font-bold">Oops! There were some errors:</strong>
                            <ul class="mt-2 list-disc list-inside">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('payments.store') }}">
                        @csrf

                        <div class="mb-6">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4 border-b pb-2">Payment Information</h3>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                
                                <div class="md:col-span-2">
                                    <label for="student_id" class="block text-sm font-medium text-gray-700">Student *</label>
                                    <select name="student_id" id="student_id" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                        <option value="">Select Student</option>
                                        @foreach($students as $student)
                                            <option value="{{ $student->id }}" {{ old('student_id') == $student->id ? 'selected' : '' }}>
                                                {{ $student->full_name }} ({{ $student->student_id }})
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div>
                                    <label for="amount" class="block text-sm font-medium text-gray-700">Amount (₦) *</label>
                                    <input type="number" step="0.01" name="amount" id="amount" value="{{ old('amount') }}" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                </div>

                                <div>
                                    <label for="payment_date" class="block text-sm font-medium text-gray-700">Payment Date *</label>
                                    <input type="date" name="payment_date" id="payment_date" value="{{ old('payment_date', date('Y-m-d')) }}" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                </div>

                                <div>
                                    <label for="payment_method" class="block text-sm font-medium text-gray-700">Payment Method *</label>
                                    <select name="payment_method" id="payment_method" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                        <option value="">Select Method</option>
                                        <option value="Cash" {{ old('payment_method') == 'Cash' ? 'selected' : '' }}>Cash</option>
                                        <option value="Bank Transfer" {{ old('payment_method') == 'Bank Transfer' ? 'selected' : '' }}>Bank Transfer</option>
                                        <option value="Card" {{ old('payment_method') == 'Card' ? 'selected' : '' }}>Card</option>
                                        <option value="Cheque" {{ old('payment_method') == 'Cheque' ? 'selected' : '' }}>Cheque</option>
                                        <option value="Mobile Money" {{ old('payment_method') == 'Mobile Money' ? 'selected' : '' }}>Mobile Money</option>
                                    </select>
                                </div>

                                <div>
                                    <label for="payment_type" class="block text-sm font-medium text-gray-700">Payment Type *</label>
                                    <select name="payment_type" id="payment_type" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                        <option value="tuition" {{ old('payment_type') == 'tuition' ? 'selected' : '' }}>Tuition</option>
                                        <option value="registration" {{ old('payment_type') == 'registration' ? 'selected' : '' }}>Registration</option>
                                        <option value="materials" {{ old('payment_type') == 'materials' ? 'selected' : '' }}>Materials</option>
                                        <option value="event" {{ old('payment_type') == 'event' ? 'selected' : '' }}>Event</option>
                                        <option value="other" {{ old('payment_type') == 'other' ? 'selected' : '' }}>Other</option>
                                    </select>
                                </div>

                                <div>
                                    <label for="reference_number" class="block text-sm font-medium text-gray-700">Reference Number</label>
                                    <input type="text" name="reference_number" id="reference_number" value="{{ old('reference_number') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                </div>

                                <div>
                                    <label for="month" class="block text-sm font-medium text-gray-700">Month</label>
                                    <select name="month" id="month" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                        <option value="">Select Month</option>
                                        @foreach(['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'] as $month)
                                            <option value="{{ $month }}" {{ old('month') == $month ? 'selected' : '' }}>{{ $month }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div>
                                    <label for="year" class="block text-sm font-medium text-gray-700">Year</label>
                                    <select name="year" id="year" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                        <option value="">Select Year</option>
                                        <option value="2024" {{ old('year') == '2024' ? 'selected' : '' }}>2024</option>
                                        <option value="2025" {{ old('year', '2025') == '2025' ? 'selected' : '' }}>2025</option>
                                        <option value="2026" {{ old('year') == '2026' ? 'selected' : '' }}>2026</option>
                                    </select>
                                </div>

                                <div class="md:col-span-2">
                                    <label for="notes" class="block text-sm font-medium text-gray-700">Notes</label>
                                    <textarea name="notes" id="notes" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">{{ old('notes') }}</textarea>
                                </div>

                            </div>
                        </div>

                        <div class="flex items-center justify-between mt-8 pt-6 border-t border-gray-200">
                            <a href="{{ route('payments.index') }}" style="display: inline-block; padding: 12px 24px; background-color: #6B7280; color: white; font-weight: bold; border-radius: 8px; text-decoration: none;">
                                Cancel
                            </a>
                            <button type="submit" style="display: inline-block; padding: 12px 32px; background-color: #059669; color: white; font-weight: bold; border-radius: 8px; border: none; cursor: pointer; font-size: 16px;">
                                ✓ Record Payment
                            </button>
                        </div>

                    </form>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
