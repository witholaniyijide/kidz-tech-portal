<x-parent-layout>
    <x-slot name="title">Settings</x-slot>
    <x-slot name="subtitle">Manage your account preferences</x-slot>

    <div class="max-w-4xl mx-auto space-y-6">

        {{-- Success/Error Messages --}}
        @if (session('success'))
            <div class="glass-card rounded-xl p-4 bg-emerald-50/90 dark:bg-emerald-900/20 border border-emerald-200 dark:border-emerald-800" role="alert">
                <div class="flex items-center">
                    <svg class="w-5 h-5 text-emerald-600 dark:text-emerald-400 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <p class="text-emerald-800 dark:text-emerald-200">{{ session('success') }}</p>
                </div>
            </div>
        @endif

        @if ($errors->any())
            <div class="glass-card rounded-xl p-4 bg-rose-50/90 dark:bg-rose-900/20 border border-rose-200 dark:border-rose-800" role="alert">
                <div class="flex items-start">
                    <svg class="w-5 h-5 text-rose-600 dark:text-rose-400 mr-3 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <div class="flex-1">
                        <p class="text-rose-800 dark:text-rose-200 font-medium mb-2">Please correct the following errors:</p>
                        <ul class="list-disc list-inside text-sm text-rose-700 dark:text-rose-300">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        @endif

        {{-- Profile Photo Section --}}
        <div class="glass-card rounded-2xl overflow-hidden">
            <div class="p-6 bg-gradient-to-r from-pink-500 to-rose-500">
                <h3 class="text-xl font-heading font-bold text-white flex items-center">
                    <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    Profile Photo
                </h3>
                <p class="text-pink-100 text-sm mt-1">Upload your profile picture</p>
            </div>

            <div class="p-6">
                <form method="POST" action="{{ route('parent.settings.update-avatar') }}" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="flex items-center gap-6">
                        {{-- Current Photo Preview --}}
                        <div class="flex-shrink-0">
                            @if($user->profile_photo)
                                <img src="{{ Storage::url($user->profile_photo) }}" alt="Profile Photo"
                                     class="w-24 h-24 rounded-full object-cover border-4 border-white shadow-lg">
                            @else
                                <div class="w-24 h-24 bg-gradient-to-br from-sky-500 to-cyan-400 rounded-full flex items-center justify-center text-white font-bold text-3xl border-4 border-white shadow-lg">
                                    {{ strtoupper(substr($user->name, 0, 1)) }}
                                </div>
                            @endif
                        </div>

                        {{-- Upload Section --}}
                        <div class="flex-1">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Upload New Photo
                            </label>
                            <input type="file" name="avatar" accept="image/jpeg,image/png,image/jpg,image/gif"
                                   class="block w-full text-sm text-gray-500 dark:text-gray-400
                                          file:mr-4 file:py-2 file:px-4
                                          file:rounded-lg file:border-0
                                          file:text-sm file:font-medium
                                          file:bg-pink-100 file:text-pink-600
                                          hover:file:bg-pink-200
                                          dark:file:bg-pink-900/30 dark:file:text-pink-400
                                          cursor-pointer">
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-2">
                                JPG, PNG or GIF. Max size 2MB.
                            </p>
                            @error('avatar')
                                <p class="text-rose-600 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="mt-6 flex justify-end">
                        <button type="submit"
                                class="px-6 py-2.5 bg-gradient-to-r from-pink-500 to-rose-500 hover:from-pink-600 hover:to-rose-600 text-white font-medium rounded-lg shadow-lg hover:shadow-xl transition-all duration-200">
                            Update Photo
                        </button>
                    </div>
                </form>
            </div>
        </div>

        {{-- Profile Information Section --}}
        <div class="glass-card rounded-2xl overflow-hidden">
            <div class="p-6 bg-gradient-to-r from-sky-500 to-cyan-400">
                <h3 class="text-xl font-heading font-bold text-white flex items-center">
                    <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                    Profile Information
                </h3>
                <p class="text-sky-100 text-sm mt-1">Update your personal information</p>
            </div>

            <div class="p-6">
                <form method="POST" action="{{ route('parent.settings.update-profile') }}">
                    @csrf
                    @method('PUT')

                    <div class="space-y-4">
                        {{-- Name --}}
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Full Name <span class="text-rose-500">*</span>
                            </label>
                            <input type="text"
                                   id="name"
                                   name="name"
                                   value="{{ old('name', $user->name) }}"
                                   required
                                   class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-2 focus:ring-sky-500 focus:border-sky-500 transition-colors"
                                   aria-required="true">
                            @error('name')
                                <p class="mt-1 text-sm text-rose-600 dark:text-rose-400" role="alert">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Email --}}
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Email Address <span class="text-rose-500">*</span>
                            </label>
                            <input type="email"
                                   id="email"
                                   name="email"
                                   value="{{ old('email', $user->email) }}"
                                   required
                                   class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-2 focus:ring-sky-500 focus:border-sky-500 transition-colors"
                                   aria-required="true">
                            @error('email')
                                <p class="mt-1 text-sm text-rose-600 dark:text-rose-400" role="alert">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Phone with Country Code --}}
                        <div>
                            <label for="phone" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Phone Number
                            </label>
                            <div class="flex">
                                <select name="phone_country_code"
                                        id="phone_country_code"
                                        class="rounded-l-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-2 focus:ring-sky-500 focus:border-sky-500 transition-colors w-36 text-sm">
                                    <option value="+234" {{ old('phone_country_code', $user->phone_country_code ?? '+234') == '+234' ? 'selected' : '' }}>🇳🇬 +234 (NG)</option>
                                    <option value="+44" {{ old('phone_country_code', $user->phone_country_code ?? '+234') == '+44' ? 'selected' : '' }}>🇬🇧 +44 (UK)</option>
                                    <option value="+1" {{ old('phone_country_code', $user->phone_country_code ?? '+234') == '+1' ? 'selected' : '' }}>🇺🇸 +1 (US)</option>
                                    <option value="+233" {{ old('phone_country_code', $user->phone_country_code ?? '+234') == '+233' ? 'selected' : '' }}>🇬🇭 +233 (GH)</option>
                                    <option value="+27" {{ old('phone_country_code', $user->phone_country_code ?? '+234') == '+27' ? 'selected' : '' }}>🇿🇦 +27 (ZA)</option>
                                    <option value="+254" {{ old('phone_country_code', $user->phone_country_code ?? '+234') == '+254' ? 'selected' : '' }}>🇰🇪 +254 (KE)</option>
                                    <option value="+256" {{ old('phone_country_code', $user->phone_country_code ?? '+234') == '+256' ? 'selected' : '' }}>🇺🇬 +256 (UG)</option>
                                    <option value="+91" {{ old('phone_country_code', $user->phone_country_code ?? '+234') == '+91' ? 'selected' : '' }}>🇮🇳 +91 (IN)</option>
                                    <option value="+971" {{ old('phone_country_code', $user->phone_country_code ?? '+234') == '+971' ? 'selected' : '' }}>🇦🇪 +971 (UAE)</option>
                                    <option value="+49" {{ old('phone_country_code', $user->phone_country_code ?? '+234') == '+49' ? 'selected' : '' }}>🇩🇪 +49 (DE)</option>
                                    <option value="+33" {{ old('phone_country_code', $user->phone_country_code ?? '+234') == '+33' ? 'selected' : '' }}>🇫🇷 +33 (FR)</option>
                                    <option value="+61" {{ old('phone_country_code', $user->phone_country_code ?? '+234') == '+61' ? 'selected' : '' }}>🇦🇺 +61 (AU)</option>
                                </select>
                                <input type="text"
                                       id="phone"
                                       name="phone"
                                       value="{{ old('phone', $user->phone) }}"
                                       placeholder="e.g., 8012345678"
                                       class="flex-1 rounded-r-lg border-l-0 border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-2 focus:ring-sky-500 focus:border-sky-500 transition-colors">
                            </div>
                            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Select your country code and enter your phone number</p>
                            @error('phone')
                                <p class="mt-1 text-sm text-rose-600 dark:text-rose-400" role="alert">{{ $message }}</p>
                            @enderror
                            @error('phone_country_code')
                                <p class="mt-1 text-sm text-rose-600 dark:text-rose-400" role="alert">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="mt-6 flex justify-end">
                        <button type="submit"
                                class="px-6 py-2.5 bg-gradient-to-r from-sky-500 to-cyan-400 hover:from-sky-600 hover:to-cyan-500 text-white font-medium rounded-lg shadow-lg hover:shadow-xl transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-sky-500 focus:ring-offset-2">
                            Save Profile
                        </button>
                    </div>
                </form>
            </div>
        </div>

        {{-- Change Password Section --}}
        <div class="glass-card rounded-2xl overflow-hidden">
            <div class="p-6 bg-gradient-to-r from-purple-500 to-pink-500">
                <h3 class="text-xl font-heading font-bold text-white flex items-center">
                    <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                    </svg>
                    Change Password
                </h3>
                <p class="text-purple-100 text-sm mt-1">Update your account password</p>
            </div>

            <div class="p-6">
                <form method="POST" action="{{ route('parent.settings.update-password') }}">
                    @csrf
                    @method('PUT')

                    <div class="space-y-4">
                        {{-- Current Password --}}
                        <div>
                            <label for="current_password" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Current Password <span class="text-rose-500">*</span>
                            </label>
                            <input type="password"
                                   id="current_password"
                                   name="current_password"
                                   required
                                   class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-colors"
                                   aria-required="true">
                            @error('current_password')
                                <p class="mt-1 text-sm text-rose-600 dark:text-rose-400" role="alert">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- New Password --}}
                        <div>
                            <label for="new_password" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                New Password <span class="text-rose-500">*</span>
                            </label>
                            <input type="password"
                                   id="new_password"
                                   name="new_password"
                                   required
                                   class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-colors"
                                   aria-required="true">
                            @error('new_password')
                                <p class="mt-1 text-sm text-rose-600 dark:text-rose-400" role="alert">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Confirm New Password --}}
                        <div>
                            <label for="new_password_confirmation" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Confirm New Password <span class="text-rose-500">*</span>
                            </label>
                            <input type="password"
                                   id="new_password_confirmation"
                                   name="new_password_confirmation"
                                   required
                                   class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-colors"
                                   aria-required="true">
                        </div>

                        <div class="text-sm text-gray-600 dark:text-gray-400 bg-gray-50 dark:bg-gray-900/50 rounded-lg p-3">
                            <p class="font-medium mb-1">Password requirements:</p>
                            <ul class="list-disc list-inside space-y-1 text-xs">
                                <li>At least 8 characters long</li>
                                <li>Mix of uppercase and lowercase letters recommended</li>
                                <li>Include numbers and special characters for security</li>
                            </ul>
                        </div>
                    </div>

                    <div class="mt-6 flex justify-end">
                        <button type="submit"
                                class="px-6 py-2.5 bg-gradient-to-r from-purple-500 to-pink-500 hover:from-purple-600 hover:to-pink-600 text-white font-medium rounded-lg shadow-lg hover:shadow-xl transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:ring-offset-2">
                            Change Password
                        </button>
                    </div>
                </form>
            </div>
        </div>

        {{-- Notification Preferences Section --}}
        <div class="glass-card rounded-2xl overflow-hidden">
            <div class="p-6 bg-gradient-to-r from-emerald-500 to-teal-500">
                <h3 class="text-xl font-heading font-bold text-white flex items-center">
                    <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                    </svg>
                    Notification Preferences
                </h3>
                <p class="text-emerald-100 text-sm mt-1">Manage how you receive notifications</p>
            </div>

            <div class="p-6">
                <form method="POST" action="{{ route('parent.settings.update-notifications') }}">
                    @csrf
                    @method('PUT')

                    <div class="space-y-5">
                        {{-- Email Notifications Toggle --}}
                        <div class="flex items-center justify-between p-4 rounded-lg bg-gray-50 dark:bg-gray-900/50 hover:bg-gray-100 dark:hover:bg-gray-900 transition-colors">
                            <div class="flex-1 pr-4">
                                <label for="notify_email" class="block font-medium text-gray-900 dark:text-white cursor-pointer">
                                    Email Notifications
                                </label>
                                <p class="text-sm text-gray-600 dark:text-gray-400 mt-0.5">
                                    Receive notifications via email
                                </p>
                            </div>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox"
                                       id="notify_email"
                                       name="notify_email"
                                       value="1"
                                       {{ old('notify_email', $user->notify_email ?? true) ? 'checked' : '' }}
                                       class="sr-only peer"
                                       role="switch"
                                       aria-checked="{{ old('notify_email', $user->notify_email ?? true) ? 'true' : 'false' }}">
                                <div class="w-14 h-7 bg-gray-300 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-emerald-300 dark:peer-focus:ring-emerald-800 rounded-full peer dark:bg-gray-600 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-0.5 after:left-[4px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-6 after:w-6 after:transition-all dark:border-gray-600 peer-checked:bg-emerald-600"></div>
                            </label>
                        </div>

                        {{-- In-App Notifications Toggle --}}
                        <div class="flex items-center justify-between p-4 rounded-lg bg-gray-50 dark:bg-gray-900/50 hover:bg-gray-100 dark:hover:bg-gray-900 transition-colors">
                            <div class="flex-1 pr-4">
                                <label for="notify_in_app" class="block font-medium text-gray-900 dark:text-white cursor-pointer">
                                    In-App Notifications
                                </label>
                                <p class="text-sm text-gray-600 dark:text-gray-400 mt-0.5">
                                    Show notifications when using the portal
                                </p>
                            </div>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox"
                                       id="notify_in_app"
                                       name="notify_in_app"
                                       value="1"
                                       {{ old('notify_in_app', $user->notify_in_app ?? true) ? 'checked' : '' }}
                                       class="sr-only peer"
                                       role="switch"
                                       aria-checked="{{ old('notify_in_app', $user->notify_in_app ?? true) ? 'true' : 'false' }}">
                                <div class="w-14 h-7 bg-gray-300 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-emerald-300 dark:peer-focus:ring-emerald-800 rounded-full peer dark:bg-gray-600 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-0.5 after:left-[4px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-6 after:w-6 after:transition-all dark:border-gray-600 peer-checked:bg-emerald-600"></div>
                            </label>
                        </div>

                        {{-- Daily Summary Toggle --}}
                        <div class="flex items-center justify-between p-4 rounded-lg bg-gray-50 dark:bg-gray-900/50 hover:bg-gray-100 dark:hover:bg-gray-900 transition-colors">
                            <div class="flex-1 pr-4">
                                <label for="notify_daily_summary" class="block font-medium text-gray-900 dark:text-white cursor-pointer">
                                    Daily Summary Notifications
                                </label>
                                <p class="text-sm text-gray-600 dark:text-gray-400 mt-0.5">
                                    Receive daily summary of your children's activities
                                </p>
                            </div>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox"
                                       id="notify_daily_summary"
                                       name="notify_daily_summary"
                                       value="1"
                                       {{ old('notify_daily_summary', $user->notify_daily_summary ?? false) ? 'checked' : '' }}
                                       class="sr-only peer"
                                       role="switch"
                                       aria-checked="{{ old('notify_daily_summary', $user->notify_daily_summary ?? false) ? 'true' : 'false' }}">
                                <div class="w-14 h-7 bg-gray-300 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-emerald-300 dark:peer-focus:ring-emerald-800 rounded-full peer dark:bg-gray-600 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-0.5 after:left-[4px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-6 after:w-6 after:transition-all dark:border-gray-600 peer-checked:bg-emerald-600"></div>
                            </label>
                        </div>
                    </div>

                    <div class="mt-6 flex justify-end">
                        <button type="submit"
                                class="px-6 py-2.5 bg-gradient-to-r from-emerald-500 to-teal-500 hover:from-emerald-600 hover:to-teal-600 text-white font-medium rounded-lg shadow-lg hover:shadow-xl transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2">
                            Save Preferences
                        </button>
                    </div>
                </form>
            </div>
        </div>

    </div>
</x-parent-layout>
