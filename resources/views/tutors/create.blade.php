<x-app-layout>
    <x-slot name="title">{{ __('Tutors') }}</x-slot>

    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                Add New Tutor
            </h2>
            <a href="{{ route('tutors.index') }}" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-sky-600 to-cyan-600 hover:from-sky-700 hover:to-cyan-700 text-white rounded-xl font-semibold text-sm shadow-lg hover:-translate-y-0.5 transform transition-all duration-200">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Back to Tutors
            </a>
        </div>
    </x-slot>

    <div class="py-12" x-data="tutorForm()">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            @if($errors->any())
                <div class="mb-6 bg-red-50 dark:bg-red-900/50 border-l-4 border-red-500 p-4 rounded-2xl backdrop-blur-xl">
                    <div class="flex items-center mb-2">
                        <svg class="w-5 h-5 text-red-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <strong class="font-bold text-red-800 dark:text-red-200">Please fix the following errors:</strong>
                    </div>
                    <ul class="list-disc list-inside text-red-700 dark:text-red-300 ml-7">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @if(session('temp_password'))
                <div class="mb-6 bg-amber-50 dark:bg-amber-900/30 border-l-4 border-amber-500 p-4 rounded-2xl backdrop-blur-xl">
                    <div class="flex items-start">
                        <svg class="w-5 h-5 text-amber-500 mr-3 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/>
                        </svg>
                        <div>
                            <p class="font-semibold text-amber-800 dark:text-amber-200">User Account Created Successfully!</p>
                            <p class="mt-2 text-sm text-amber-700 dark:text-amber-300">
                                <strong>Email:</strong> {{ session('temp_password_email') }}<br>
                                <strong>Temporary Password:</strong> <code class="px-2 py-1 bg-amber-100 dark:bg-amber-800 rounded">{{ session('temp_password') }}</code>
                            </p>
                            <p class="mt-2 text-xs text-amber-600 dark:text-amber-400 italic">
                                Please share this password with the tutor securely. This message will only be shown once.
                            </p>
                        </div>
                    </div>
                </div>
            @endif

            <form method="POST" action="{{ route('tutors.store') }}" enctype="multipart/form-data" class="space-y-6">
                @csrf

                {{-- SECTION 1: Personal Information --}}
                <div class="bg-white/20 dark:bg-gray-900/30 backdrop-blur-xl border border-white/10 overflow-hidden shadow-card rounded-2xl">
                    <div class="p-6 border-b border-gray-200/50 dark:border-gray-700/50 bg-gradient-to-r from-sky-500/10 to-cyan-500/10">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 flex items-center">
                            <svg class="w-6 h-6 mr-2 text-sky-600 dark:text-sky-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                            Personal Information
                        </h3>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <x-forms.input
                                name="first_name"
                                label="First Name"
                                :required="true"
                                :value="old('first_name')"
                            />

                            <x-forms.input
                                name="last_name"
                                label="Last Name"
                                :required="true"
                                :value="old('last_name')"
                            />

                            <x-forms.input
                                name="email"
                                type="email"
                                label="Email"
                                :required="true"
                                :value="old('email')"
                            />

                            <x-forms.input
                                name="phone"
                                type="tel"
                                label="Phone"
                                :required="true"
                                :value="old('phone')"
                                placeholder="e.g., 08012345678"
                                helpText="Nigerian phone number format"
                            />

                            <x-forms.input
                                name="date_of_birth"
                                type="date"
                                label="Date of Birth"
                                :value="old('date_of_birth')"
                            />

                            <x-forms.select
                                name="gender"
                                label="Gender"
                                :options="['male' => 'Male', 'female' => 'Female', 'other' => 'Other']"
                                :value="old('gender')"
                            />

                            <x-forms.input
                                name="location"
                                label="Location"
                                :value="old('location')"
                            />

                            <x-forms.input
                                name="occupation"
                                label="Occupation"
                                :value="old('occupation')"
                            />

                            <div class="md:col-span-2">
                                <x-forms.textarea
                                    name="bio"
                                    label="Bio"
                                    :value="old('bio')"
                                    rows="3"
                                    helpText="Brief professional biography"
                                />
                            </div>

                            <div class="md:col-span-2">
                                <x-forms.file-upload
                                    name="profile_photo"
                                    label="Profile Photo"
                                    accept="image/*"
                                    helpText="PNG, JPG, WEBP up to 2MB"
                                />
                            </div>

                            <x-forms.select
                                name="status"
                                label="Status"
                                :options="['active' => 'Active', 'inactive' => 'Inactive', 'on_leave' => 'On Leave']"
                                :value="old('status', 'active')"
                            />
                        </div>
                    </div>
                </div>

                {{-- SECTION 2: Emergency Contact --}}
                <div class="bg-white/20 dark:bg-gray-900/30 backdrop-blur-xl border border-white/10 overflow-hidden shadow-card rounded-2xl">
                    <div class="p-6 border-b border-gray-200/50 dark:border-gray-700/50 bg-gradient-to-r from-red-500/10 to-pink-500/10">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 flex items-center">
                            <svg class="w-6 h-6 mr-2 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                            </svg>
                            Emergency Contact
                        </h3>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <x-forms.input
                                name="contact_person_name"
                                label="Contact Person Name"
                                :value="old('contact_person_name')"
                            />

                            <x-forms.input
                                name="contact_person_relationship"
                                label="Relationship"
                                :value="old('contact_person_relationship')"
                            />

                            <x-forms.input
                                name="contact_person_phone"
                                type="tel"
                                label="Contact Phone"
                                :value="old('contact_person_phone')"
                                placeholder="e.g., 08012345678"
                            />
                        </div>
                    </div>
                </div>

                {{-- SECTION 3: Payment Information --}}
                <div class="bg-white/20 dark:bg-gray-900/30 backdrop-blur-xl border border-white/10 overflow-hidden shadow-card rounded-2xl">
                    <div class="p-6 border-b border-gray-200/50 dark:border-gray-700/50 bg-gradient-to-r from-green-500/10 to-emerald-500/10">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 flex items-center">
                            <svg class="w-6 h-6 mr-2 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                            </svg>
                            Payment Information
                        </h3>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <x-forms.input
                                name="bank_name"
                                label="Bank Name"
                                :value="old('bank_name')"
                            />

                            <x-forms.input
                                name="account_number"
                                label="Account Number"
                                :value="old('account_number')"
                            />

                            <x-forms.input
                                name="account_name"
                                label="Account Name"
                                :value="old('account_name')"
                            />
                        </div>
                    </div>
                </div>

                {{-- SECTION 4: User Account --}}
                <div class="bg-white/20 dark:bg-gray-900/30 backdrop-blur-xl border border-white/10 overflow-hidden shadow-card rounded-2xl">
                    <div class="p-6 border-b border-gray-200/50 dark:border-gray-700/50 bg-gradient-to-r from-amber-500/10 to-yellow-500/10">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 flex items-center">
                            <svg class="w-6 h-6 mr-2 text-amber-600 dark:text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/>
                            </svg>
                            User Account
                        </h3>
                    </div>
                    <div class="p-6">
                        <label class="flex items-start space-x-3 cursor-pointer group">
                            <input
                                type="checkbox"
                                name="create_user_account"
                                id="create_user_account"
                                value="1"
                                x-model="createUserAccount"
                                class="mt-1 w-5 h-5 text-sky-600 border-gray-300 dark:border-gray-600 rounded focus:ring-sky-500 focus:ring-offset-2 transition-colors"
                            >
                            <div>
                                <span class="text-gray-900 dark:text-white font-semibold group-hover:text-sky-600 dark:group-hover:text-sky-400 transition-colors">
                                    Create User Account
                                </span>
                                <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                                    Create a portal login for this tutor (email: {{ old('email', 'tutor@example.com') }})
                                </p>
                            </div>
                        </label>

                        <div x-show="createUserAccount" x-transition class="mt-4 p-4 bg-sky-50 dark:bg-sky-900/20 border-l-4 border-sky-500 rounded-xl">
                            <div class="flex items-start">
                                <svg class="w-5 h-5 text-sky-500 mt-0.5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                <div>
                                    <p class="text-sm font-semibold text-sky-800 dark:text-sky-300">User Account Details</p>
                                    <p class="text-sm text-sky-700 dark:text-sky-400 mt-2">
                                        <strong>Email:</strong> Will use tutor's email address<br>
                                        <strong>Temporary Password:</strong> <code class="px-2 py-0.5 bg-sky-100 dark:bg-sky-800 rounded">KidzTech2025</code><br>
                                        <strong>Role:</strong> Tutor
                                    </p>
                                    <p class="text-xs text-sky-600 dark:text-sky-500 mt-2 italic">
                                        Please share this temporary password with the tutor securely. They should change it on first login.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Action Buttons --}}
                <div class="flex items-center justify-between gap-4 pt-4">
                    <a href="{{ route('tutors.index') }}"
                        class="inline-flex items-center px-5 py-3 bg-white/20 dark:bg-gray-700/50 backdrop-blur-xl border border-white/10 rounded-xl font-semibold text-sm text-gray-700 dark:text-gray-300 hover:bg-white/30 dark:hover:bg-gray-700/70 focus:outline-none focus:ring-2 focus:ring-sky-500 focus:ring-offset-2 transition-all duration-200">
                        Cancel
                    </a>
                    <button
                        type="submit"
                        class="inline-flex items-center px-5 py-3 bg-gradient-to-r from-sky-600 to-cyan-600 hover:from-sky-700 hover:to-cyan-700 text-white rounded-xl font-semibold text-sm shadow-lg hover:-translate-y-0.5 transform transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-sky-500 focus:ring-offset-2">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        <span x-text="createUserAccount ? 'Save Tutor & Create Account' : 'Save Tutor'">Save Tutor</span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('tutorForm', () => ({
                createUserAccount: {{ old('create_user_account') ? 'true' : 'false' }}
            }))
        })
    </script>
    @endpush
</x-app-layout>
