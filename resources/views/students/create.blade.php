<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Add New Student
            </h2>
            <a href="{{ route('students.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                ← Back to Students
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

                    <form method="POST" action="{{ route('students.store') }}">
                        @csrf

                        <div class="mb-6">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4 border-b pb-2">Personal Information</h3>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                
                                <div>
                                    <label for="first_name" class="block text-sm font-medium text-gray-700">First Name *</label>
                                    <input type="text" name="first_name" id="first_name" value="{{ old('first_name') }}" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                </div>

                                <div>
                                    <label for="last_name" class="block text-sm font-medium text-gray-700">Last Name *</label>
                                    <input type="text" name="last_name" id="last_name" value="{{ old('last_name') }}" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                </div>

                                <div>
                                    <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                                    <input type="email" name="email" id="email" value="{{ old('email') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                </div>

                                <div>
                                    <label for="phone" class="block text-sm font-medium text-gray-700">Phone</label>
                                    <input type="text" name="phone" id="phone" value="{{ old('phone') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                </div>

                                <div>
                                    <label for="date_of_birth" class="block text-sm font-medium text-gray-700">Date of Birth *</label>
                                    <input type="date" name="date_of_birth" id="date_of_birth" value="{{ old('date_of_birth') }}" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                </div>

                                <div>
                                    <label for="gender" class="block text-sm font-medium text-gray-700">Gender *</label>
                                    <select name="gender" id="gender" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                        <option value="">Select Gender</option>
                                        <option value="male" {{ old('gender') == 'male' ? 'selected' : '' }}>Male</option>
                                        <option value="female" {{ old('gender') == 'female' ? 'selected' : '' }}>Female</option>
                                    </select>
                                </div>

                                <div class="md:col-span-2">
                                    <label for="address" class="block text-sm font-medium text-gray-700">Address</label>
                                    <textarea name="address" id="address" rows="2" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">{{ old('address') }}</textarea>
                                </div>

                                <div>
                                    <label for="state" class="block text-sm font-medium text-gray-700">State</label>
                                    <input type="text" name="state" id="state" value="{{ old('state', 'Lagos') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                </div>

                                <div>
                                    <label for="location" class="block text-sm font-medium text-gray-700">Location</label>
                                    <select name="location" id="location" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                        <option value="">Select Location</option>
                                        <option value="Lekki" {{ old('location') == 'Lekki' ? 'selected' : '' }}>Lekki</option>
                                        <option value="Victoria Island" {{ old('location') == 'Victoria Island' ? 'selected' : '' }}>Victoria Island</option>
                                        <option value="Ikeja" {{ old('location') == 'Ikeja' ? 'selected' : '' }}>Ikeja</option>
                                        <option value="Yaba" {{ old('location') == 'Yaba' ? 'selected' : '' }}>Yaba</option>
                                    </select>
                                </div>

                            </div>
                        </div>

                        <div class="mb-6">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4 border-b pb-2">Parent/Guardian Information</h3>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                
                                <div>
                                    <label for="parent_name" class="block text-sm font-medium text-gray-700">Parent/Guardian Name *</label>
                                    <input type="text" name="parent_name" id="parent_name" value="{{ old('parent_name') }}" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                </div>

                                <div>
                                    <label for="parent_relationship" class="block text-sm font-medium text-gray-700">Relationship *</label>
                                    <select name="parent_relationship" id="parent_relationship" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                        <option value="Parent" {{ old('parent_relationship') == 'Parent' ? 'selected' : '' }}>Parent</option>
                                        <option value="Guardian" {{ old('parent_relationship') == 'Guardian' ? 'selected' : '' }}>Guardian</option>
                                        <option value="Mother" {{ old('parent_relationship') == 'Mother' ? 'selected' : '' }}>Mother</option>
                                        <option value="Father" {{ old('parent_relationship') == 'Father' ? 'selected' : '' }}>Father</option>
                                    </select>
                                </div>

                                <div>
                                    <label for="parent_email" class="block text-sm font-medium text-gray-700">Parent Email</label>
                                    <input type="email" name="parent_email" id="parent_email" value="{{ old('parent_email') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                </div>

                                <div>
                                    <label for="parent_phone" class="block text-sm font-medium text-gray-700">Parent Phone *</label>
                                    <input type="text" name="parent_phone" id="parent_phone" value="{{ old('parent_phone') }}" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                </div>

                            </div>
                        </div>

                        <div class="mb-6">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4 border-b pb-2">Academic Information</h3>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                
                                <div>
                                    <label for="enrollment_date" class="block text-sm font-medium text-gray-700">Enrollment Date *</label>
                                    <input type="date" name="enrollment_date" id="enrollment_date" value="{{ old('enrollment_date', date('Y-m-d')) }}" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                </div>

                                <div class="md:col-span-2">
                                    <label for="notes" class="block text-sm font-medium text-gray-700">Notes</label>
                                    <textarea name="notes" id="notes" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">{{ old('notes') }}</textarea>
                                </div>

                            </div>
                        </div>

                        <div class="flex items-center justify-between mt-8 pt-6 border-t border-gray-200">
                            <a href="{{ route('students.index') }}" style="display: inline-block; padding: 12px 24px; background-color: #6B7280; color: white; font-weight: bold; border-radius: 8px; text-decoration: none;">
                                Cancel
                            </a>
                            <button type="submit" style="display: inline-block; padding: 12px 32px; background-color: #2563EB; color: white; font-weight: bold; border-radius: 8px; border: none; cursor: pointer; font-size: 16px;">
                                ✓ Add Student
                            </button>
                        </div>

                    </form>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
