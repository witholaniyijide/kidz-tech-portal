<!DOCTYPE html>
<html lang="en" x-data="tutorForm()">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New Tutor - Kidz Tech Portal</title>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gradient-to-br from-slate-50 via-cyan-50 to-teal-50 dark:from-slate-900 dark:via-slate-800 dark:to-slate-900 min-h-screen">

    @include('layouts.navigation')

    <div class="py-8 px-4 sm:px-6 lg:px-8">
        <div class="max-w-6xl mx-auto">

            {{-- Header --}}
            <div class="mb-8 flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-800 dark:text-white">Add New Tutor</h1>
                    <p class="text-gray-600 dark:text-gray-400 mt-1">Create a new tutor profile</p>
                </div>
                <a href="{{ route('tutors.index') }}" class="px-6 py-3 bg-gray-500 hover:bg-gray-600 text-white font-semibold rounded-xl shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all duration-300">
                    <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    Back to Tutors
                </a>
            </div>

            @if($errors->any())
                <div class="mb-6 bg-red-50 dark:bg-red-900/20 border-l-4 border-red-500 p-4 rounded-lg">
                    <div class="flex items-center mb-2">
                        <svg class="w-6 h-6 text-red-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <strong class="font-bold text-red-800 dark:text-red-300">Oops! There were some errors:</strong>
                    </div>
                    <ul class="list-disc list-inside text-red-700 dark:text-red-400">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('tutors.store') }}" enctype="multipart/form-data" class="space-y-6">
                @csrf

                {{-- SECTION 1: Personal Information --}}
                <div class="relative overflow-hidden rounded-2xl bg-white/70 dark:bg-slate-800/70 backdrop-blur-xl shadow-xl p-6 border border-white/20">
                    <div class="flex items-center mb-6 pb-4 border-b border-gray-200 dark:border-slate-700">
                        <div class="p-3 bg-gradient-to-br from-teal-500 to-cyan-500 rounded-xl mr-4">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                        </div>
                        <h2 class="text-2xl font-bold text-gray-800 dark:text-white">Personal Information</h2>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="first_name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                First Name <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="first_name" id="first_name" value="{{ old('first_name') }}" required
                                class="w-full px-4 py-3 rounded-lg border border-gray-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-teal-500 focus:border-transparent transition-all">
                        </div>

                        <div>
                            <label for="last_name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Last Name <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="last_name" id="last_name" value="{{ old('last_name') }}" required
                                class="w-full px-4 py-3 rounded-lg border border-gray-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-teal-500 focus:border-transparent transition-all">
                        </div>

                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Email <span class="text-red-500">*</span>
                            </label>
                            <input type="email" name="email" id="email" value="{{ old('email') }}" required
                                class="w-full px-4 py-3 rounded-lg border border-gray-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-teal-500 focus:border-transparent transition-all">
                        </div>

                        <div>
                            <label for="phone" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Phone <span class="text-red-500">*</span>
                            </label>
                            <input type="tel" name="phone" id="phone" value="{{ old('phone') }}" required
                                class="w-full px-4 py-3 rounded-lg border border-gray-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-teal-500 focus:border-transparent transition-all">
                        </div>

                        <div>
                            <label for="date_of_birth" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Date of Birth
                            </label>
                            <input type="date" name="date_of_birth" id="date_of_birth" value="{{ old('date_of_birth') }}"
                                class="w-full px-4 py-3 rounded-lg border border-gray-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-teal-500 focus:border-transparent transition-all">
                        </div>

                        <div>
                            <label for="gender" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Gender
                            </label>
                            <select name="gender" id="gender"
                                class="w-full px-4 py-3 rounded-lg border border-gray-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-teal-500 focus:border-transparent transition-all">
                                <option value="">Select Gender</option>
                                <option value="male" {{ old('gender') == 'male' ? 'selected' : '' }}>Male</option>
                                <option value="female" {{ old('gender') == 'female' ? 'selected' : '' }}>Female</option>
                            </select>
                        </div>

                        <div>
                            <label for="location" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Location <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="location" id="location" value="{{ old('location') }}" required
                                class="w-full px-4 py-3 rounded-lg border border-gray-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-teal-500 focus:border-transparent transition-all">
                        </div>

                        <div>
                            <label for="occupation" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Occupation
                            </label>
                            <input type="text" name="occupation" id="occupation" value="{{ old('occupation') }}"
                                class="w-full px-4 py-3 rounded-lg border border-gray-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-teal-500 focus:border-transparent transition-all">
                        </div>

                        <div class="md:col-span-2">
                            <label for="bio" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Bio
                            </label>
                            <textarea name="bio" id="bio" rows="3"
                                class="w-full px-4 py-3 rounded-lg border border-gray-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-teal-500 focus:border-transparent transition-all">{{ old('bio') }}</textarea>
                        </div>

                        <div>
                            <label for="status" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Status <span class="text-red-500">*</span>
                            </label>
                            <select name="status" id="status"
                                class="w-full px-4 py-3 rounded-lg border border-gray-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-teal-500 focus:border-transparent transition-all">
                                <option value="active" {{ old('status', 'active') == 'active' ? 'selected' : '' }}>Active</option>
                                <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                                <option value="on_leave" {{ old('status') == 'on_leave' ? 'selected' : '' }}>On Leave</option>
                            </select>
                        </div>

                        <div>
                            <label for="profile_photo" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Profile Photo
                            </label>
                            <input type="file" name="profile_photo" id="profile_photo" accept="image/*" @change="previewPhoto"
                                class="w-full px-4 py-3 rounded-lg border border-gray-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-teal-500 focus:border-transparent transition-all">
                        </div>

                        <div class="md:col-span-2" x-show="photoPreview">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Photo Preview
                            </label>
                            <div class="flex items-center justify-center p-4 bg-gray-100 dark:bg-slate-700 rounded-lg">
                                <img :src="photoPreview" alt="Preview" class="h-32 w-32 object-cover rounded-full border-4 border-teal-500">
                            </div>
                        </div>
                    </div>
                </div>

                {{-- SECTION 2: Emergency Contact --}}
                <div class="relative overflow-hidden rounded-2xl bg-white/70 dark:bg-slate-800/70 backdrop-blur-xl shadow-xl p-6 border border-white/20">
                    <div class="flex items-center mb-6 pb-4 border-b border-gray-200 dark:border-slate-700">
                        <div class="p-3 bg-gradient-to-br from-red-500 to-orange-500 rounded-xl mr-4">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                            </svg>
                        </div>
                        <h2 class="text-2xl font-bold text-gray-800 dark:text-white">Emergency Contact</h2>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div>
                            <label for="contact_person_name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Contact Person Name
                            </label>
                            <input type="text" name="contact_person_name" id="contact_person_name" value="{{ old('contact_person_name') }}"
                                class="w-full px-4 py-3 rounded-lg border border-gray-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-teal-500 focus:border-transparent transition-all">
                        </div>

                        <div>
                            <label for="contact_person_relationship" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Relationship
                            </label>
                            <input type="text" name="contact_person_relationship" id="contact_person_relationship" value="{{ old('contact_person_relationship') }}"
                                class="w-full px-4 py-3 rounded-lg border border-gray-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-teal-500 focus:border-transparent transition-all">
                        </div>

                        <div>
                            <label for="contact_person_phone" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Contact Person Phone
                            </label>
                            <input type="tel" name="contact_person_phone" id="contact_person_phone" value="{{ old('contact_person_phone') }}"
                                class="w-full px-4 py-3 rounded-lg border border-gray-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-teal-500 focus:border-transparent transition-all">
                        </div>
                    </div>
                </div>

                {{-- SECTION 3: Payment Details --}}
                <div class="relative overflow-hidden rounded-2xl bg-white/70 dark:bg-slate-800/70 backdrop-blur-xl shadow-xl p-6 border border-white/20">
                    <div class="flex items-center mb-6 pb-4 border-b border-gray-200 dark:border-slate-700">
                        <div class="p-3 bg-gradient-to-br from-green-500 to-emerald-500 rounded-xl mr-4">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                            </svg>
                        </div>
                        <h2 class="text-2xl font-bold text-gray-800 dark:text-white">Payment Details</h2>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div>
                            <label for="bank_name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Bank Name
                            </label>
                            <input type="text" name="bank_name" id="bank_name" value="{{ old('bank_name') }}"
                                class="w-full px-4 py-3 rounded-lg border border-gray-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-teal-500 focus:border-transparent transition-all">
                        </div>

                        <div>
                            <label for="account_number" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Account Number
                            </label>
                            <input type="text" name="account_number" id="account_number" value="{{ old('account_number') }}"
                                class="w-full px-4 py-3 rounded-lg border border-gray-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-teal-500 focus:border-transparent transition-all">
                        </div>

                        <div>
                            <label for="account_name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Account Name
                            </label>
                            <input type="text" name="account_name" id="account_name" value="{{ old('account_name') }}"
                                class="w-full px-4 py-3 rounded-lg border border-gray-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-teal-500 focus:border-transparent transition-all">
                        </div>
                    </div>
                </div>

                {{-- SECTION 4: User Account Option --}}
                <div class="relative overflow-hidden rounded-2xl bg-white/70 dark:bg-slate-800/70 backdrop-blur-xl shadow-xl p-6 border border-white/20">
                    <div class="flex items-center mb-6 pb-4 border-b border-gray-200 dark:border-slate-700">
                        <div class="p-3 bg-gradient-to-br from-purple-500 to-pink-500 rounded-xl mr-4">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/>
                            </svg>
                        </div>
                        <h2 class="text-2xl font-bold text-gray-800 dark:text-white">User Account</h2>
                    </div>

                    <div class="space-y-4">
                        <label class="flex items-start space-x-3 cursor-pointer">
                            <input type="checkbox" name="create_user_account" id="create_user_account" value="1" x-model="createUserAccount"
                                class="mt-1 w-6 h-6 text-teal-500 border-gray-300 rounded focus:ring-teal-500 focus:ring-2 cursor-pointer transition-all">
                            <div>
                                <span class="text-gray-900 dark:text-white font-semibold">Create User Account</span>
                                <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                                    Create a user account for this tutor to access the portal
                                </p>
                            </div>
                        </label>

                        <div x-show="createUserAccount" class="mt-4 p-4 bg-teal-50 dark:bg-teal-900/20 border-l-4 border-teal-500 rounded-lg">
                            <div class="flex items-start">
                                <svg class="w-5 h-5 text-teal-500 mt-0.5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                <div>
                                    <p class="text-sm font-semibold text-teal-800 dark:text-teal-300">
                                        User Account Details
                                    </p>
                                    <p class="text-sm text-teal-700 dark:text-teal-400 mt-1">
                                        <strong>Email:</strong> Will use tutor's email address<br>
                                        <strong>Temporary Password:</strong> KidzTech2025<br>
                                        <strong>Role:</strong> Tutor
                                    </p>
                                    <p class="text-xs text-teal-600 dark:text-teal-500 mt-2 italic">
                                        Please share this temporary password with the tutor securely
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Action Buttons --}}
                <div class="flex items-center justify-between gap-4 pt-4">
                    <a href="{{ route('tutors.index') }}"
                        class="px-8 py-4 bg-gray-500 hover:bg-gray-600 text-white font-semibold rounded-xl shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all duration-300">
                        Cancel
                    </a>
                    <button type="submit"
                        class="px-8 py-4 bg-gradient-to-r from-teal-500 to-cyan-500 hover:from-teal-600 hover:to-cyan-600 text-white font-semibold rounded-xl shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all duration-300">
                        <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        <span x-text="createUserAccount ? 'Save Tutor & Create Account' : 'Save Tutor'"></span>
                    </button>
                </div>

            </form>

        </div>
    </div>

    <script>
        function tutorForm() {
            return {
                createUserAccount: {{ old('create_user_account') ? 'true' : 'false' }},
                photoPreview: null,

                previewPhoto(event) {
                    const file = event.target.files[0];
                    if (file) {
                        const reader = new FileReader();
                        reader.onload = (e) => {
                            this.photoPreview = e.target.result;
                        };
                        reader.readAsDataURL(file);
                    }
                }
            }
        }
    </script>

</body>
</html>
