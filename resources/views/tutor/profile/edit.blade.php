<x-tutor-layout>
    <!-- Page Header -->
    <div class="mb-8">
        <h1 class="text-4xl font-bold text-gray-900 dark:text-white mb-2">
            Edit Profile
        </h1>
        <p class="text-gray-600 dark:text-gray-400">
            Update your personal information and profile photo
        </p>
    </div>

    <!-- Profile Form -->
    <div class="max-w-4xl">
        <form action="{{ route('tutor.profile.update') }}" method="POST" enctype="multipart/form-data"
            class="bg-white/20 dark:bg-gray-900/30 backdrop-blur-xl border border-white/10 rounded-2xl p-8 shadow-lg">
            @csrf
            @method('PUT')

            <!-- Profile Photo -->
            <div class="mb-8">
                <label class="block text-sm font-medium text-gray-900 dark:text-white mb-4">Profile Photo</label>
                <div class="flex items-center space-x-6">
                    <div class="flex-shrink-0">
                        <img id="profile_photo_preview"
                            src="{{ $tutor->profile_photo ? asset('storage/' . $tutor->profile_photo) : 'https://ui-avatars.com/api/?name=' . urlencode($tutor->fullName()) . '&size=128&background=8B5CF6&color=fff' }}"
                            alt="Profile photo"
                            class="w-32 h-32 rounded-full object-cover border-4 border-purple-500">
                    </div>
                    <div class="flex-1">
                        <input type="file" id="profile_photo" name="profile_photo" accept="image/*"
                            class="block w-full text-sm text-gray-900 dark:text-white bg-white/50 dark:bg-gray-800/50 border border-gray-300 dark:border-gray-600 rounded-xl cursor-pointer focus:outline-none focus:ring-2 focus:ring-purple-500">
                        <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">JPG, JPEG, PNG or WEBP. Max 2MB.</p>
                        @error('profile_photo')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Name Fields -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div>
                    <label for="first_name" class="block text-sm font-medium text-gray-900 dark:text-white mb-2">
                        First Name <span class="text-red-500">*</span>
                    </label>
                    <input type="text" id="first_name" name="first_name" value="{{ old('first_name', $tutor->first_name) }}" required
                        class="w-full px-4 py-3 bg-white/50 dark:bg-gray-800/50 border border-gray-300 dark:border-gray-600 rounded-xl text-gray-900 dark:text-white focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                    @error('first_name')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="last_name" class="block text-sm font-medium text-gray-900 dark:text-white mb-2">
                        Last Name <span class="text-red-500">*</span>
                    </label>
                    <input type="text" id="last_name" name="last_name" value="{{ old('last_name', $tutor->last_name) }}" required
                        class="w-full px-4 py-3 bg-white/50 dark:bg-gray-800/50 border border-gray-300 dark:border-gray-600 rounded-xl text-gray-900 dark:text-white focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                    @error('last_name')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Contact Fields -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-900 dark:text-white mb-2">
                        Email <span class="text-red-500">*</span>
                    </label>
                    <input type="email" id="email" name="email" value="{{ old('email', $tutor->email) }}" required
                        class="w-full px-4 py-3 bg-white/50 dark:bg-gray-800/50 border border-gray-300 dark:border-gray-600 rounded-xl text-gray-900 dark:text-white focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                    @error('email')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="phone" class="block text-sm font-medium text-gray-900 dark:text-white mb-2">
                        Phone <span class="text-red-500">*</span>
                    </label>
                    <input type="text" id="phone" name="phone" value="{{ old('phone', $tutor->phone) }}" required
                        class="w-full px-4 py-3 bg-white/50 dark:bg-gray-800/50 border border-gray-300 dark:border-gray-600 rounded-xl text-gray-900 dark:text-white focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                    @error('phone')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Personal Details -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div>
                    <label for="date_of_birth" class="block text-sm font-medium text-gray-900 dark:text-white mb-2">
                        Date of Birth
                    </label>
                    <input type="date" id="date_of_birth" name="date_of_birth" value="{{ old('date_of_birth', $tutor->date_of_birth?->format('Y-m-d')) }}"
                        class="w-full px-4 py-3 bg-white/50 dark:bg-gray-800/50 border border-gray-300 dark:border-gray-600 rounded-xl text-gray-900 dark:text-white focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                    @error('date_of_birth')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="gender" class="block text-sm font-medium text-gray-900 dark:text-white mb-2">
                        Gender
                    </label>
                    <select id="gender" name="gender"
                        class="w-full px-4 py-3 bg-white/50 dark:bg-gray-800/50 border border-gray-300 dark:border-gray-600 rounded-xl text-gray-900 dark:text-white focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                        <option value="">Select gender</option>
                        <option value="male" {{ old('gender', $tutor->gender) === 'male' ? 'selected' : '' }}>Male</option>
                        <option value="female" {{ old('gender', $tutor->gender) === 'female' ? 'selected' : '' }}>Female</option>
                        <option value="other" {{ old('gender', $tutor->gender) === 'other' ? 'selected' : '' }}>Other</option>
                    </select>
                    @error('gender')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Location Fields -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div>
                    <label for="state" class="block text-sm font-medium text-gray-900 dark:text-white mb-2">
                        State
                    </label>
                    <input type="text" id="state" name="state" value="{{ old('state', $tutor->state) }}"
                        class="w-full px-4 py-3 bg-white/50 dark:bg-gray-800/50 border border-gray-300 dark:border-gray-600 rounded-xl text-gray-900 dark:text-white focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                    @error('state')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="location" class="block text-sm font-medium text-gray-900 dark:text-white mb-2">
                        Location
                    </label>
                    <input type="text" id="location" name="location" value="{{ old('location', $tutor->location) }}"
                        class="w-full px-4 py-3 bg-white/50 dark:bg-gray-800/50 border border-gray-300 dark:border-gray-600 rounded-xl text-gray-900 dark:text-white focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                    @error('location')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Address -->
            <div class="mb-6">
                <label for="address" class="block text-sm font-medium text-gray-900 dark:text-white mb-2">
                    Address
                </label>
                <textarea id="address" name="address" rows="2"
                    class="w-full px-4 py-3 bg-white/50 dark:bg-gray-800/50 border border-gray-300 dark:border-gray-600 rounded-xl text-gray-900 dark:text-white focus:ring-2 focus:ring-purple-500 focus:border-transparent">{{ old('address', $tutor->address) }}</textarea>
                @error('address')
                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                @enderror
            </div>

            <!-- Occupation -->
            <div class="mb-6">
                <label for="occupation" class="block text-sm font-medium text-gray-900 dark:text-white mb-2">
                    Occupation
                </label>
                <input type="text" id="occupation" name="occupation" value="{{ old('occupation', $tutor->occupation) }}"
                    class="w-full px-4 py-3 bg-white/50 dark:bg-gray-800/50 border border-gray-300 dark:border-gray-600 rounded-xl text-gray-900 dark:text-white focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                @error('occupation')
                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                @enderror
            </div>

            <!-- Bio -->
            <div class="mb-6">
                <label for="bio" class="block text-sm font-medium text-gray-900 dark:text-white mb-2">
                    Bio
                </label>
                <textarea id="bio" name="bio" rows="4" maxlength="2000"
                    class="w-full px-4 py-3 bg-white/50 dark:bg-gray-800/50 border border-gray-300 dark:border-gray-600 rounded-xl text-gray-900 dark:text-white focus:ring-2 focus:ring-purple-500 focus:border-transparent"
                    placeholder="Tell us about yourself...">{{ old('bio', $tutor->bio) }}</textarea>
                @error('bio')
                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                @enderror
            </div>

            <!-- Action Buttons -->
            <div class="flex items-center justify-end space-x-4">
                <a href="{{ route('tutor.dashboard') }}"
                    class="px-6 py-3 bg-white/20 dark:bg-gray-800/30 text-gray-700 dark:text-gray-300 rounded-xl border border-white/10 hover:bg-white/30 dark:hover:bg-gray-800/50 transition-colors">
                    Cancel
                </a>
                <button type="submit"
                    class="px-6 py-3 bg-gradient-to-r from-purple-500 to-pink-500 text-white rounded-xl hover:shadow-lg hover:-translate-y-0.5 transition-all font-medium">
                    Save Changes
                </button>
            </div>
        </form>
    </div>

    @push('scripts')
    <script>
        // Profile photo preview
        document.getElementById('profile_photo').addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(event) {
                    document.getElementById('profile_photo_preview').src = event.target.result;
                };
                reader.readAsDataURL(file);
            }
        });
    </script>
    @endpush
</x-tutor-layout>
