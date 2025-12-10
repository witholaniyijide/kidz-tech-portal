<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-2xl text-white">Settings</h2>
    </x-slot>

    <x-slot name="title">Director Settings</x-slot>

    <div class="min-h-screen bg-gradient-to-br from-blue-50 via-indigo-50 to-purple-50 dark:from-slate-900 dark:via-slate-800 dark:to-slate-900 py-12 relative overflow-hidden">
        {{-- Floating Orbs --}}
        <div class="absolute top-0 left-0 w-72 h-72 bg-blue-400 dark:bg-blue-900 rounded-full mix-blend-multiply filter blur-xl opacity-70 animate-float"></div>
        <div class="absolute top-0 right-0 w-72 h-72 bg-purple-400 dark:bg-purple-900 rounded-full mix-blend-multiply filter blur-xl opacity-70 animate-float" style="animation-delay: 2s;"></div>

        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">

            {{-- Header --}}
            <div class="mb-8">
                <h1 class="text-4xl font-bold text-gray-900 dark:text-white mb-2">Settings</h1>
                <p class="text-gray-600 dark:text-gray-400">Manage your account preferences and notification settings</p>
            </div>

            {{-- Success Message --}}
            @if(session('success'))
            <div class="mb-6 backdrop-blur-md bg-green-500/20 border border-green-500/30 rounded-2xl p-4 shadow-xl">
                <div class="flex items-center">
                    <svg class="w-5 h-5 text-green-600 dark:text-green-400 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <p class="text-green-800 dark:text-green-200">{{ session('success') }}</p>
                </div>
            </div>
            @endif

            {{-- Settings Sections --}}
            <div class="space-y-6">

                {{-- Notification Preferences --}}
                <div class="backdrop-blur-md bg-white/30 dark:bg-gray-900/30 border border-white/10 rounded-2xl p-8 shadow-xl">
                    <div class="flex items-center mb-6">
                        <div class="w-12 h-12 rounded-xl bg-gradient-to-r from-purple-500 to-pink-500 flex items-center justify-center mr-4">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                            </svg>
                        </div>
                        <div>
                            <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Notification Preferences</h2>
                            <p class="text-sm text-gray-600 dark:text-gray-400">Choose how you want to receive notifications</p>
                        </div>
                    </div>

                    <form action="{{ route('director.settings.notifications.update') }}" method="POST" class="space-y-4">
                        @csrf
                        @method('PUT')

                        {{-- Email Notifications --}}
                        <div class="flex items-center justify-between p-4 bg-white/50 dark:bg-gray-800/50 rounded-xl">
                            <div class="flex items-start">
                                <div class="flex items-center h-5 mt-1">
                                    <input type="checkbox" name="notify_email" id="notify_email" value="1" {{ $user->notify_email ? 'checked' : '' }} class="w-5 h-5 text-purple-600 border-gray-300 rounded focus:ring-purple-500">
                                </div>
                                <div class="ml-4">
                                    <label for="notify_email" class="font-medium text-gray-900 dark:text-white cursor-pointer">Email Notifications</label>
                                    <p class="text-sm text-gray-600 dark:text-gray-400">Receive notifications via email</p>
                                </div>
                            </div>
                        </div>

                        {{-- In-App Notifications --}}
                        <div class="flex items-center justify-between p-4 bg-white/50 dark:bg-gray-800/50 rounded-xl">
                            <div class="flex items-start">
                                <div class="flex items-center h-5 mt-1">
                                    <input type="checkbox" name="notify_in_app" id="notify_in_app" value="1" {{ $user->notify_in_app ? 'checked' : '' }} class="w-5 h-5 text-purple-600 border-gray-300 rounded focus:ring-purple-500">
                                </div>
                                <div class="ml-4">
                                    <label for="notify_in_app" class="font-medium text-gray-900 dark:text-white cursor-pointer">In-App Notifications</label>
                                    <p class="text-sm text-gray-600 dark:text-gray-400">See notifications in the application</p>
                                </div>
                            </div>
                        </div>

                        {{-- Daily Summary --}}
                        <div class="flex items-center justify-between p-4 bg-white/50 dark:bg-gray-800/50 rounded-xl">
                            <div class="flex items-start">
                                <div class="flex items-center h-5 mt-1">
                                    <input type="checkbox" name="notify_daily_summary" id="notify_daily_summary" value="1" {{ $user->notify_daily_summary ? 'checked' : '' }} class="w-5 h-5 text-purple-600 border-gray-300 rounded focus:ring-purple-500">
                                </div>
                                <div class="ml-4">
                                    <label for="notify_daily_summary" class="font-medium text-gray-900 dark:text-white cursor-pointer">Daily Summary Email</label>
                                    <p class="text-sm text-gray-600 dark:text-gray-400">Receive a daily summary of your activities at 7:00 PM</p>
                                </div>
                            </div>
                        </div>

                        <div class="flex justify-end mt-6">
                            <button type="submit" class="px-6 py-3 bg-gradient-to-r from-purple-500 to-pink-500 text-white rounded-xl hover:shadow-lg transform hover:-translate-y-1 transition-all">
                                Save Preferences
                            </button>
                        </div>
                    </form>

                    {{-- Push Notifications (Browser) --}}
                    <div class="mt-6 pt-6 border-t border-gray-200 dark:border-gray-700">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Browser Push Notifications</h3>
                        <x-ui.push-notification-toggle />
                    </div>
                </div>

                {{-- Profile Settings --}}
                <div class="backdrop-blur-md bg-white/30 dark:bg-gray-900/30 border border-white/10 rounded-2xl p-8 shadow-xl">
                    <div class="flex items-center mb-6">
                        <div class="w-12 h-12 rounded-xl bg-gradient-to-r from-blue-500 to-cyan-500 flex items-center justify-center mr-4">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                        </div>
                        <div>
                            <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Profile Information</h2>
                            <p class="text-sm text-gray-600 dark:text-gray-400">Update your account details</p>
                        </div>
                    </div>

                    <form action="{{ route('director.settings.profile.update') }}" method="POST" class="space-y-4">
                        @csrf
                        @method('PUT')

                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Name</label>
                            <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}" required class="w-full px-4 py-2 rounded-xl border border-gray-300 dark:border-gray-600 bg-white/50 dark:bg-gray-800/50 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            @error('name')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Email</label>
                            <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}" required class="w-full px-4 py-2 rounded-xl border border-gray-300 dark:border-gray-600 bg-white/50 dark:bg-gray-800/50 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            @error('email')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="phone" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Phone</label>
                            <input type="text" name="phone" id="phone" value="{{ old('phone', $user->phone) }}" class="w-full px-4 py-2 rounded-xl border border-gray-300 dark:border-gray-600 bg-white/50 dark:bg-gray-800/50 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            @error('phone')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="flex justify-end mt-6">
                            <button type="submit" class="px-6 py-3 bg-gradient-to-r from-blue-500 to-cyan-500 text-white rounded-xl hover:shadow-lg transform hover:-translate-y-1 transition-all">
                                Update Profile
                            </button>
                        </div>
                    </form>
                </div>

                {{-- Password Change --}}
                <div class="backdrop-blur-md bg-white/30 dark:bg-gray-900/30 border border-white/10 rounded-2xl p-8 shadow-xl">
                    <div class="flex items-center mb-6">
                        <div class="w-12 h-12 rounded-xl bg-gradient-to-r from-red-500 to-orange-500 flex items-center justify-center mr-4">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                            </svg>
                        </div>
                        <div>
                            <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Change Password</h2>
                            <p class="text-sm text-gray-600 dark:text-gray-400">Update your password to keep your account secure</p>
                        </div>
                    </div>

                    <form action="{{ route('director.settings.password.update') }}" method="POST" class="space-y-4">
                        @csrf
                        @method('PUT')

                        <div>
                            <label for="current_password" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Current Password</label>
                            <input type="password" name="current_password" id="current_password" required class="w-full px-4 py-2 rounded-xl border border-gray-300 dark:border-gray-600 bg-white/50 dark:bg-gray-800/50 text-gray-900 dark:text-white focus:ring-2 focus:ring-red-500 focus:border-transparent">
                            @error('current_password')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="password" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">New Password</label>
                            <input type="password" name="password" id="password" required class="w-full px-4 py-2 rounded-xl border border-gray-300 dark:border-gray-600 bg-white/50 dark:bg-gray-800/50 text-gray-900 dark:text-white focus:ring-2 focus:ring-red-500 focus:border-transparent">
                            @error('password')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="password_confirmation" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Confirm New Password</label>
                            <input type="password" name="password_confirmation" id="password_confirmation" required class="w-full px-4 py-2 rounded-xl border border-gray-300 dark:border-gray-600 bg-white/50 dark:bg-gray-800/50 text-gray-900 dark:text-white focus:ring-2 focus:ring-red-500 focus:border-transparent">
                        </div>

                        <div class="flex justify-end mt-6">
                            <button type="submit" class="px-6 py-3 bg-gradient-to-r from-red-500 to-orange-500 text-white rounded-xl hover:shadow-lg transform hover:-translate-y-1 transition-all">
                                Change Password
                            </button>
                        </div>
                    </form>
                </div>

            </div>

        </div>
    </div>
</x-app-layout>
