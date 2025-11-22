<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">Edit Tutor: {{ $tutor->first_name }} {{ $tutor->last_name }}</h2>
            <a href="{{ route('tutors.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-600 dark:bg-gray-700 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-indigo-500 transition ease-in-out duration-150">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                Back to Tutors
            </a>
        </div>
    </x-slot>

    <div class="py-12" x-data="tutorForm()">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if($errors->any())
                <div class="mb-6 bg-red-50 dark:bg-red-900/50 border-l-4 border-red-500 p-4 rounded-lg">
                    <div class="flex items-center mb-2">
                        <svg class="w-5 h-5 text-red-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <strong class="font-bold text-red-800 dark:text-red-200">There were some errors:</strong>
                    </div>
                    <ul class="list-disc list-inside text-red-700 dark:text-red-300 ml-7">
                        @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('tutors.update', $tutor) }}" enctype="multipart/form-data" class="space-y-6">
                @csrf
                @method('PUT')
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 border-b border-gray-200 dark:border-gray-700"><h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 flex items-center"><svg class="w-6 h-6 mr-2 text-teal-600 dark:text-teal-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>Personal Information</h3></div>
                    <div class="p-6"><div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div><label for="first_name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">First Name <span class="text-red-500">*</span></label><input type="text" name="first_name" id="first_name" value="{{ old('first_name', $tutor->first_name) }}" required class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"></div>
                        <div><label for="last_name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Last Name <span class="text-red-500">*</span></label><input type="text" name="last_name" id="last_name" value="{{ old('last_name', $tutor->last_name) }}" required class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"></div>
                        <div><label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Email <span class="text-red-500">*</span></label><input type="email" name="email" id="email" value="{{ old('email', $tutor->email) }}" required class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"></div>
                        <div><label for="phone" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Phone <span class="text-red-500">*</span></label><input type="tel" name="phone" id="phone" value="{{ old('phone', $tutor->phone) }}" required class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"></div>
                        <div><label for="date_of_birth" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Date of Birth</label><input type="date" name="date_of_birth" id="date_of_birth" value="{{ old('date_of_birth', $tutor->date_of_birth ? $tutor->date_of_birth->format('Y-m-d') : '') }}" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"></div>
                        <div><label for="gender" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Gender</label><select name="gender" id="gender" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"><option value="">Select Gender</option><option value="male" {{ old('gender', $tutor->gender) == 'male' ? 'selected' : '' }}>Male</option><option value="female" {{ old('gender', $tutor->gender) == 'female' ? 'selected' : '' }}>Female</option></select></div>
                        <div><label for="location" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Location <span class="text-red-500">*</span></label><input type="text" name="location" id="location" value="{{ old('location', $tutor->location) }}" required class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"></div>
                        <div><label for="occupation" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Occupation</label><input type="text" name="occupation" id="occupation" value="{{ old('occupation', $tutor->occupation) }}" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"></div>
                        <div class="md:col-span-2"><label for="bio" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Bio</label><textarea name="bio" id="bio" rows="3" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old('bio', $tutor->bio) }}</textarea></div>
                        <div><label for="status" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Status <span class="text-red-500">*</span></label><select name="status" id="status" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"><option value="active" {{ old('status', $tutor->status) == 'active' ? 'selected' : '' }}>Active</option><option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Inactive</option><option value="on_leave" {{ old('status') == 'on_leave' ? 'selected' : '' }}>On Leave</option></select></div>
                        <div><label for="profile_photo" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Profile Photo</label><input type="file" name="profile_photo" id="profile_photo" accept="image/*" @change="previewPhoto" class="mt-1 block w-full text-sm text-gray-900 dark:text-gray-300 border border-gray-300 dark:border-gray-600 rounded-md cursor-pointer bg-gray-50 dark:bg-gray-700 focus:outline-none"></div>
                        <div class="md:col-span-2"><label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Current/Preview Photo</label><div class="flex items-center justify-center p-4 bg-gray-100 dark:bg-gray-700 rounded-lg">@if($tutor->profile_photo)
                                    <img :src="photoPreview || '{{ asset('storage/' . $tutor->profile_photo) }}'" alt="Profile Photo" class="h-32 w-32 object-cover rounded-full border-4 border-teal-500">
                                @else
                                    <template x-if="photoPreview"><img :src="photoPreview" alt="Preview" class="h-32 w-32 object-cover rounded-full border-4 border-teal-500"></template>
                                    <template x-if="!photoPreview"><div class="h-32 w-32 bg-gradient-to-br from-teal-400 to-cyan-400 rounded-full flex items-center justify-center text-white text-4xl font-bold">{{ strtoupper(substr($tutor->first_name, 0, 1) . substr($tutor->last_name, 0, 1)) }}</div></template>
                                @endif</div></div>
                    </div></div>
                </div>

                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 border-b border-gray-200 dark:border-gray-700"><h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 flex items-center"><svg class="w-6 h-6 mr-2 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>Emergency Contact</h3></div>
                    <div class="p-6"><div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div><label for="contact_person_name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Contact Person Name</label><input type="text" name="contact_person_name" id="contact_person_name" value="{{ old('contact_person_name', $tutor->contact_person_name) }}" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"></div>
                        <div><label for="contact_person_relationship" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Relationship</label><input type="text" name="contact_person_relationship" id="contact_person_relationship" value="{{ old('contact_person_relationship', $tutor->contact_person_relationship) }}" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"></div>
                        <div><label for="contact_person_phone" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Contact Person Phone</label><input type="tel" name="contact_person_phone" id="contact_person_phone" value="{{ old('contact_person_phone', $tutor->contact_person_phone) }}" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"></div>
                    </div></div>
                </div>

                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 border-b border-gray-200 dark:border-gray-700"><h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 flex items-center"><svg class="w-6 h-6 mr-2 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>Payment Details</h3></div>
                    <div class="p-6"><div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div><label for="bank_name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Bank Name</label><input type="text" name="bank_name" id="bank_name" value="{{ old('bank_name', $tutor->bank_name) }}" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"></div>
                        <div><label for="account_number" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Account Number</label><input type="text" name="account_number" id="account_number" value="{{ old('account_number', $tutor->account_number) }}" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"></div>
                        <div><label for="account_name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Account Name</label><input type="text" name="account_name" id="account_name" value="{{ old('account_name', $tutor->account_name) }}" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"></div>
                    </div></div>
                </div>

                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 border-b border-gray-200 dark:border-gray-700"><h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 flex items-center"><svg class="w-6 h-6 mr-2 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/></svg>User Account (No changes on edit)</h3></div>
                    <div class="p-6"><div class="space-y-4">
                        <label class="flex items-start space-x-3 cursor-pointer">
                            <input type="checkbox" name="create_user_account" id="create_user_account" value="1" x-model="createUserAccount" class="mt-1 w-5 h-5 text-teal-600 border-gray-300 dark:border-gray-600 rounded focus:ring-teal-500">
                            <div><span class="text-gray-900 dark:text-white font-semibold">Create User Account (No changes on edit)</span><p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Create a user account for this tutor to access the portal</p></div>
                        </label>
                        <div x-show="createUserAccount" class="mt-4 p-4 bg-teal-50 dark:bg-teal-900/20 border-l-4 border-teal-500 rounded-lg">
                            <div class="flex items-start"><svg class="w-5 h-5 text-teal-500 mt-0.5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                <div><p class="text-sm font-semibold text-teal-800 dark:text-teal-300">User Account (No changes on edit) Details</p><p class="text-sm text-teal-700 dark:text-teal-400 mt-1"><strong>Email:</strong> Will use tutor's email address<br><strong>Temporary Password:</strong> KidzTech2025<br><strong>Role:</strong> Tutor</p><p class="text-xs text-teal-600 dark:text-teal-500 mt-2 italic">Please share this temporary password with the tutor securely</p></div>
                            </div>
                        </div>
                    </div></div>
                </div>

                <div class="flex items-center justify-between gap-4 pt-4">
                    <a href="{{ route('tutors.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-600 dark:bg-gray-700 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-indigo-500 transition ease-in-out duration-150">Cancel</a>
                    <button type="submit" class="inline-flex items-center px-4 py-2 bg-indigo-600 dark:bg-indigo-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 dark:hover:bg-indigo-600 focus:outline-none focus:ring-2 focus:ring-indigo-500 transition ease-in-out duration-150"><svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg><span x-text="createUserAccount ? 'Update Tutor & Create Account' : 'Update Tutor'">Update Tutor</span></button>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('tutorForm', () => ({
                createUserAccount: {{ old('create_user_account') ? 'true' : 'false' }},
                photoPreview: null,
                previewPhoto(event) {
                    const file = event.target.files[0];
                    if (file) {
                        const reader = new FileReader();
                        reader.onload = (e) => { this.photoPreview = e.target.result; };
                        reader.readAsDataURL(file);
                    }
                }
            }))
        })
    </script>
    @endpush
</x-app-layout>
