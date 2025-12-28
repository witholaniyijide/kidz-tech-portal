<x-app-layout>
    <x-slot name="header">
        {{ __('Settings') }}
    </x-slot>

    <x-slot name="title">Director Settings</x-slot>

    <div class="min-h-screen bg-gradient-to-br from-indigo-50 via-purple-50 to-pink-50 dark:from-slate-900 dark:via-slate-800 dark:to-slate-900 py-12 relative overflow-hidden">
        {{-- Floating Orbs - Director Indigo Theme --}}
        <div class="absolute top-0 left-0 w-72 h-72 bg-indigo-400 dark:bg-indigo-900 rounded-full mix-blend-multiply filter blur-xl opacity-70 animate-float"></div>
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

                {{-- Appearance Settings --}}
                <div class="backdrop-blur-md bg-white/30 dark:bg-gray-900/30 border border-white/10 rounded-2xl p-8 shadow-xl" x-data="{
                    darkMode: localStorage.getItem('darkMode') !== null ? localStorage.getItem('darkMode') === 'true' : window.matchMedia('(prefers-color-scheme: dark)').matches,
                    themeColor: localStorage.getItem('themeColor') || 'blue',
                    toggleDarkMode() {
                        this.darkMode = !this.darkMode;
                        localStorage.setItem('darkMode', this.darkMode);
                        if (this.darkMode) {
                            document.documentElement.classList.add('dark');
                            document.body.classList.add('dark');
                        } else {
                            document.documentElement.classList.remove('dark');
                            document.body.classList.remove('dark');
                        }
                    },
                    setThemeColor(color) {
                        this.themeColor = color;
                        localStorage.setItem('themeColor', color);
                    }
                }">
                    <div class="flex items-center mb-6">
                        <div class="w-12 h-12 rounded-xl bg-gradient-to-r from-indigo-500 to-purple-500 flex items-center justify-center mr-4">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01" />
                            </svg>
                        </div>
                        <div>
                            <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Appearance</h2>
                            <p class="text-sm text-gray-600 dark:text-gray-400">Customize how the dashboard looks</p>
                        </div>
                    </div>

                    {{-- Dark Mode Toggle --}}
                    <div class="flex items-center justify-between p-4 bg-white/50 dark:bg-gray-800/50 rounded-xl mb-4">
                        <div class="flex items-start">
                            <div class="flex items-center h-5 mt-1">
                                <button @click="toggleDarkMode()" class="relative inline-flex h-6 w-11 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2" :class="darkMode ? 'bg-indigo-600' : 'bg-gray-200'">
                                    <span class="sr-only">Toggle dark mode</span>
                                    <span class="pointer-events-none inline-block h-5 w-5 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out" :class="darkMode ? 'translate-x-5' : 'translate-x-0'"></span>
                                </button>
                            </div>
                            <div class="ml-4">
                                <label class="font-medium text-gray-900 dark:text-white cursor-pointer">Dark Mode</label>
                                <p class="text-sm text-gray-600 dark:text-gray-400">Switch between light and dark themes</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-2">
                            <svg x-show="!darkMode" class="w-6 h-6 text-yellow-500" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/>
                            </svg>
                            <svg x-show="darkMode" class="w-6 h-6 text-indigo-400" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/>
                            </svg>
                        </div>
                    </div>

                    {{-- Theme Color Selection --}}
                    <div class="p-4 bg-white/50 dark:bg-gray-800/50 rounded-xl">
                        <label class="font-medium text-gray-900 dark:text-white block mb-2">Accent Color</label>
                        <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">Choose your preferred accent color for the dashboard</p>
                        <div class="flex flex-wrap gap-3">
                            <button @click="setThemeColor('blue')" :class="themeColor === 'blue' ? 'ring-2 ring-offset-2 ring-blue-500' : ''" class="w-10 h-10 rounded-full bg-gradient-to-br from-blue-500 to-blue-600 hover:scale-110 transition-transform"></button>
                            <button @click="setThemeColor('purple')" :class="themeColor === 'purple' ? 'ring-2 ring-offset-2 ring-purple-500' : ''" class="w-10 h-10 rounded-full bg-gradient-to-br from-purple-500 to-purple-600 hover:scale-110 transition-transform"></button>
                            <button @click="setThemeColor('green')" :class="themeColor === 'green' ? 'ring-2 ring-offset-2 ring-green-500' : ''" class="w-10 h-10 rounded-full bg-gradient-to-br from-green-500 to-green-600 hover:scale-110 transition-transform"></button>
                            <button @click="setThemeColor('orange')" :class="themeColor === 'orange' ? 'ring-2 ring-offset-2 ring-orange-500' : ''" class="w-10 h-10 rounded-full bg-gradient-to-br from-orange-500 to-orange-600 hover:scale-110 transition-transform"></button>
                            <button @click="setThemeColor('pink')" :class="themeColor === 'pink' ? 'ring-2 ring-offset-2 ring-pink-500' : ''" class="w-10 h-10 rounded-full bg-gradient-to-br from-pink-500 to-pink-600 hover:scale-110 transition-transform"></button>
                            <button @click="setThemeColor('teal')" :class="themeColor === 'teal' ? 'ring-2 ring-offset-2 ring-teal-500' : ''" class="w-10 h-10 rounded-full bg-gradient-to-br from-teal-500 to-teal-600 hover:scale-110 transition-transform"></button>
                        </div>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-3">Selected: <span class="capitalize font-medium" x-text="themeColor"></span></p>
                    </div>
                </div>

                {{-- Profile Picture --}}
                <div class="backdrop-blur-md bg-white/30 dark:bg-gray-900/30 border border-white/10 rounded-2xl p-8 shadow-xl">
                    <div class="flex items-center mb-6">
                        <div class="w-12 h-12 rounded-xl bg-gradient-to-r from-pink-500 to-rose-500 flex items-center justify-center mr-4">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                        </div>
                        <div>
                            <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Profile Picture</h2>
                            <p class="text-sm text-gray-600 dark:text-gray-400">Upload a profile photo</p>
                        </div>
                    </div>

                    <form action="{{ route('director.settings.avatar.update') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                        @csrf
                        @method('PUT')

                        <div class="flex items-center gap-6">
                            {{-- Current Avatar Preview --}}
                            <div class="flex-shrink-0">
                                @if($user->avatar)
                                    <img src="{{ Storage::url($user->avatar) }}" alt="Profile Picture" class="w-24 h-24 rounded-full object-cover border-4 border-white dark:border-gray-700 shadow-lg">
                                @else
                                    <div class="w-24 h-24 rounded-full bg-gradient-to-r from-[#4F46E5] to-[#818CF8] flex items-center justify-center text-white text-3xl font-bold border-4 border-white dark:border-gray-700 shadow-lg">
                                        {{ strtoupper(substr($user->name, 0, 1)) }}
                                    </div>
                                @endif
                            </div>

                            {{-- Upload Section --}}
                            <div class="flex-1">
                                <label for="avatar" class="block">
                                    <div class="flex items-center justify-center w-full px-4 py-6 border-2 border-dashed border-gray-300 dark:border-gray-600 rounded-xl cursor-pointer hover:border-pink-500 dark:hover:border-pink-400 transition-colors bg-white/50 dark:bg-gray-800/50">
                                        <div class="text-center">
                                            <svg class="w-8 h-8 mx-auto text-gray-400 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                                            </svg>
                                            <p class="text-sm text-gray-600 dark:text-gray-400">Click to upload or drag and drop</p>
                                            <p class="text-xs text-gray-500 mt-1">PNG, JPG up to 2MB</p>
                                        </div>
                                    </div>
                                    <input type="file" id="avatar" name="avatar" class="hidden" accept="image/png,image/jpeg,image/jpg">
                                </label>
                                @error('avatar')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="flex justify-end">
                            <button type="submit" class="px-6 py-3 bg-gradient-to-r from-pink-500 to-rose-500 text-white rounded-xl hover:shadow-lg transform hover:-translate-y-1 transition-all">
                                Upload Photo
                            </button>
                        </div>
                    </form>
                </div>

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
                            <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}" required class="w-full px-4 py-2 rounded-xl border border-gray-300 dark:border-gray-600 bg-white/50 dark:bg-gray-800/50 text-gray-900 dark:text-white focus:ring-2 focus:ring-[#4F46E5] focus:border-transparent">
                            @error('name')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Email</label>
                            <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}" required class="w-full px-4 py-2 rounded-xl border border-gray-300 dark:border-gray-600 bg-white/50 dark:bg-gray-800/50 text-gray-900 dark:text-white focus:ring-2 focus:ring-[#4F46E5] focus:border-transparent">
                            @error('email')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="phone" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Phone</label>
                            <input type="text" name="phone" id="phone" value="{{ old('phone', $user->phone) }}" class="w-full px-4 py-2 rounded-xl border border-gray-300 dark:border-gray-600 bg-white/50 dark:bg-gray-800/50 text-gray-900 dark:text-white focus:ring-2 focus:ring-[#4F46E5] focus:border-transparent">
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
