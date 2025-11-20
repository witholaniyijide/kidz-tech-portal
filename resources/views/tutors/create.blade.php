<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Add New Tutor
            </h2>
            <a href="{{ route('tutors.index') }}" style="display: inline-block; padding: 10px 20px; background-color: #6B7280; color: white; font-weight: bold; border-radius: 8px; text-decoration: none;">
                ← Back to Tutors
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

                    <form method="POST" action="{{ route('tutors.store') }}">
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
                                    <label for="email" class="block text-sm font-medium text-gray-700">Email *</label>
                                    <input type="email" name="email" id="email" value="{{ old('email') }}" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                </div>

                                <div>
                                    <label for="phone" class="block text-sm font-medium text-gray-700">Phone *</label>
                                    <input type="text" name="phone" id="phone" value="{{ old('phone') }}" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
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
                            <h3 class="text-lg font-semibold text-gray-900 mb-4 border-b pb-2">Professional Information</h3>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                
                                <div>
                                    <label for="hire_date" class="block text-sm font-medium text-gray-700">Hire Date *</label>
                                    <input type="date" name="hire_date" id="hire_date" value="{{ old('hire_date', date('Y-m-d')) }}" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                </div>

                                <div>
                                    <label for="hourly_rate" class="block text-sm font-medium text-gray-700">Hourly Rate (₦)</label>
                                    <input type="number" step="0.01" name="hourly_rate" id="hourly_rate" value="{{ old('hourly_rate') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                </div>

                                <div class="md:col-span-2">
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Specializations *</label>
                                    <div id="specializationsContainer" class="space-y-2">
                                        <div class="flex gap-2">
                                            <input type="text" name="specializations[]" placeholder="e.g., Scratch Programming" required class="flex-1 rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                            <button type="button" onclick="addSpecialization()" style="padding: 10px 20px; background-color: #059669; color: white; font-weight: bold; border-radius: 8px; border: none; cursor: pointer;">
                                                + Add
                                            </button>
                                        </div>
                                    </div>
                                </div>

                                <div class="md:col-span-2">
                                    <label for="qualifications" class="block text-sm font-medium text-gray-700">Qualifications</label>
                                    <textarea name="qualifications" id="qualifications" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">{{ old('qualifications') }}</textarea>
                                </div>

                                <div class="md:col-span-2">
                                    <label for="notes" class="block text-sm font-medium text-gray-700">Notes</label>
                                    <textarea name="notes" id="notes" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">{{ old('notes') }}</textarea>
                                </div>

                            </div>
                        </div>

                        <div class="flex items-center justify-between mt-8 pt-6 border-t border-gray-200">
                            <a href="{{ route('tutors.index') }}" style="display: inline-block; padding: 12px 24px; background-color: #6B7280; color: white; font-weight: bold; border-radius: 8px; text-decoration: none;">
                                Cancel
                            </a>
                            <button type="submit" style="display: inline-block; padding: 12px 32px; background-color: #2563EB; color: white; font-weight: bold; border-radius: 8px; border: none; cursor: pointer; font-size: 16px;">
                                ✓ Add Tutor
                            </button>
                        </div>

                    </form>

                </div>
            </div>
        </div>
    </div>

    <script>
        function addSpecialization() {
            const container = document.getElementById('specializationsContainer');
            const div = document.createElement('div');
            div.className = 'flex gap-2';
            div.innerHTML = `
                <input type="text" name="specializations[]" placeholder="e.g., Python Programming" required class="flex-1 rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                <button type="button" onclick="this.parentElement.remove()" style="padding: 10px 20px; background-color: #DC2626; color: white; font-weight: bold; border-radius: 8px; border: none; cursor: pointer;">
                    Remove
                </button>
            `;
            container.appendChild(div);
        }
    </script>
</x-app-layout>
