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
                <form method="POST" action="{{ route('parent.settings.profile') }}">
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

                        {{-- Phone --}}
                        <div>
                            <label for="phone" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Phone Number
                            </label>
                            <input type="text"
                                   id="phone"
                                   name="phone"
                                   value="{{ old('phone', $user->phone) }}"
                                   placeholder="e.g., 08012345678"
                                   class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-2 focus:ring-sky-500 focus:border-sky-500 transition-colors">
                            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Nigerian phone number format (e.g., 08012345678)</p>
                            @error('phone')
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
                <form method="POST" action="{{ route('parent.settings.password') }}">
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
                <form method="POST" action="{{ route('parent.settings.notifications') }}">
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
