<!DOCTYPE html>
<html lang="en" x-data="tutorForm()">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Tutor - Kidz Tech Portal</title>
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
                    <h1 class="text-3xl font-bold text-gray-800 dark:text-white">Edit Tutor: {{ $tutor->first_name }} {{ $tutor->last_name }}</h1>
                    <p class="text-gray-600 dark:text-gray-400 mt-1">Update tutor profile information</p>
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

            <form method="POST" action="{{ route('tutors.update', $tutor) }}" enctype="multipart/form-data" class="space-y-6">
                @csrf
                @method('PUT')

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
                            <input type="text" name="first_name" id="first_name" value="{{ old('first_name', $tutor->first_name) }}" required
                                class="w-full px-4 py-3 rounded-lg border border-gray-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-teal-500 focus:border-transparent transition-all">
                        </div>

                        <div>
                            <label for="last_name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Last Name <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="last_name" id="last_name" value="{{ old('last_name', $tutor->last_name) }}" required
                                class="w-full px-4 py-3 rounded-lg border border-gray-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-teal-500 focus:border-transparent transition-all">
                        </div>

                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Email <span class="text-red-500">*</span>
                            </label>
                            <input type="email" name="email" id="email" value="{{ old('email', $tutor->email) }}" required
                                class="w-full px-4 py-3 rounded-lg border border-gray-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-teal-500 focus:border-transparent transition-all">
                        </div>

                        <div>
                            <label for="phone" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Phone <span class="text-red-500">*</span>
                            </label>
                            <input type="tel" name="phone" id="phone" value="{{ old('phone', $tutor->phone) }}" required
                                class="w-full px-4 py-3 rounded-lg border border-gray-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-teal-500 focus:border-transparent transition-all">
                        </div>

                        <div>
                            <label for="date_of_birth" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Date of Birth
                            </label>
                            <input type="date" name="date_of_birth" id="date_of_birth" value="{{ old('date_of_birth', $tutor->date_of_birth ? $tutor->date_of_birth->format('Y-m-d') : '') }}"
                                class="w-full px-4 py-3 rounded-lg border border-gray-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-teal-500 focus:border-transparent transition-all">
                        </div>

                        <div>
                            <label for="gender" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Gender
                            </label>
                            <select name="gender" id="gender"
                                class="w-full px-4 py-3 rounded-lg border border-gray-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-teal-500 focus:border-transparent transition-all">
                                <option value="">Select Gender</option>
                                <option value="male" {{ old('gender', $tutor->gender) == 'male' ? 'selected' : '' }}>Male</option>
                                <option value="female" {{ old('gender', $tutor->gender) == 'female' ? 'selected' : '' }}>Female</option>
                            </select>
                        </div>

                        <div>
                            <label for="location" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Location <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="location" id="location" value="{{ old('location', $tutor->location) }}" required
                                class="w-full px-4 py-3 rounded-lg border border-gray-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-teal-500 focus:border-transparent transition-all">
                        </div>

                        <div>
                            <label for="occupation" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Occupation
                            </label>
                            <input type="text" name="occupation" id="occupation" value="{{ old('occupation', $tutor->occupation) }}"
                                class="w-full px-4 py-3 rounded-lg border border-gray-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-teal-500 focus:border-transparent transition-all">
                        </div>

                        <div class="md:col-span-2">
                            <label for="bio" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Bio
                            </label>
                            <textarea name="bio" id="bio" rows="3"
                                class="w-full px-4 py-3 rounded-lg border border-gray-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-teal-500 focus:border-transparent transition-all">{{ old('bio', $tutor->bio) }}</textarea>
                        </div>

                        <div>
                            <label for="status" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Status <span class="text-red-500">*</span>
                            </label>
                            <select name="status" id="status"
                                class="w-full px-4 py-3 rounded-lg border border-gray-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-teal-500 focus:border-transparent transition-all">
                                <option value="active" {{ old('status', $tutor->status) == 'active' ? 'selected' : '' }}>Active</option>
                                <option value="inactive" {{ old('status', $tutor->status) == 'inactive' ? 'selected' : '' }}>Inactive</option>
                                <option value="on_leave" {{ old('status', $tutor->status) == 'on_leave' ? 'selected' : '' }}>On Leave</option>
                            </select>
                        </div>

                        <div>
                            <label for="profile_photo" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Profile Photo
                            </label>
                            <input type="file" name="profile_photo" id="profile_photo" accept="image/*" @change="previewPhoto"
                                class="w-full px-4 py-3 rounded-lg border border-gray-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-teal-500 focus:border-transparent transition-all">
                        </div>

                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Current/Preview Photo
                            </label>
                            <div class="flex items-center justify-center p-4 bg-gray-100 dark:bg-slate-700 rounded-lg">
                                @if($tutor->profile_photo)
                                    <img :src="photoPreview || '{{ asset('storage/' . $tutor->profile_photo) }}'" alt="Profile Photo" class="h-32 w-32 object-cover rounded-full border-4 border-teal-500">
                                @else
                                    <template x-if="photoPreview">
                                        <img :src="photoPreview" alt="Preview" class="h-32 w-32 object-cover rounded-full border-4 border-teal-500">
                                    </template>
                                    <template x-if="!photoPreview">
                                        <div class="h-32 w-32 bg-gradient-to-br from-teal-400 to-cyan-400 rounded-full flex items-center justify-center text-white text-4xl font-bold">
                                            {{ strtoupper(substr($tutor->first_name, 0, 1) . substr($tutor->last_name, 0, 1)) }}
                                        </div>
                                    </template>
                                @endif
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
                            <input type="text" name="contact_person_name" id="contact_person_name" value="{{ old('contact_person_name', $tutor->contact_person_name) }}"
                                class="w-full px-4 py-3 rounded-lg border border-gray-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-teal-500 focus:border-transparent transition-all">
                        </div>

                        <div>
                            <label for="contact_person_relationship" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Relationship
                            </label>
                            <input type="text" name="contact_person_relationship" id="contact_person_relationship" value="{{ old('contact_person_relationship', $tutor->contact_person_relationship) }}"
                                class="w-full px-4 py-3 rounded-lg border border-gray-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-teal-500 focus:border-transparent transition-all">
                        </div>

                        <div>
                            <label for="contact_person_phone" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Contact Person Phone
                            </label>
                            <input type="tel" name="contact_person_phone" id="contact_person_phone" value="{{ old('contact_person_phone', $tutor->contact_person_phone) }}"
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
                            <input type="text" name="bank_name" id="bank_name" value="{{ old('bank_name', $tutor->bank_name) }}"
                                class="w-full px-4 py-3 rounded-lg border border-gray-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-teal-500 focus:border-transparent transition-all">
                        </div>

                        <div>
                            <label for="account_number" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Account Number
                            </label>
                            <input type="text" name="account_number" id="account_number" value="{{ old('account_number', $tutor->account_number) }}"
                                class="w-full px-4 py-3 rounded-lg border border-gray-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-teal-500 focus:border-transparent transition-all">
                        </div>

                        <div>
                            <label for="account_name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Account Name
                            </label>
                            <input type="text" name="account_name" id="account_name" value="{{ old('account_name', $tutor->account_name) }}"
                                class="w-full px-4 py-3 rounded-lg border border-gray-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-teal-500 focus:border-transparent transition-all">
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
                        Update Tutor
                    </button>
                </div>

            </form>

        </div>
    </div>

    <script>
        function tutorForm() {
            return {
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
