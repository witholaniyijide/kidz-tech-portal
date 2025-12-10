<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-2xl text-white">{{ __('Settings') }}</h2>
    </x-slot>
    <x-slot name="title">{{ __('Admin - Settings') }}</x-slot>

    <div class="min-h-screen bg-gradient-to-br from-teal-50 via-cyan-50 to-blue-50 dark:from-slate-900 dark:via-slate-800 dark:to-slate-900 py-8 relative overflow-hidden">
        <div class="absolute top-0 left-0 w-72 h-72 bg-teal-300 dark:bg-teal-900 rounded-full mix-blend-multiply filter blur-xl opacity-70 animate-float"></div>
        <div class="absolute top-0 right-0 w-72 h-72 bg-cyan-300 dark:bg-cyan-900 rounded-full mix-blend-multiply filter blur-xl opacity-70 animate-float" style="animation-delay: 2s;"></div>

        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            @if(session('success'))
                <div class="mb-6 bg-emerald-100 dark:bg-emerald-900/30 border border-emerald-400 text-emerald-800 dark:text-emerald-400 px-6 py-4 rounded-xl">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="mb-6 bg-red-100 dark:bg-red-900/30 border border-red-400 text-red-800 dark:text-red-400 px-6 py-4 rounded-xl">
                    {{ session('error') }}
                </div>
            @endif

            {{-- Header --}}
            <div class="mb-8">
                <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Settings</h1>
                <p class="text-gray-600 dark:text-gray-400 mt-1">Manage your profile and preferences</p>
            </div>

            {{-- Profile Information --}}
            <div class="bg-white/60 dark:bg-gray-800/60 backdrop-blur-md border border-white/20 rounded-2xl shadow overflow-hidden mb-6">
                <div class="px-6 py-4 bg-gradient-to-r from-teal-500 to-cyan-600 text-white">
                    <h3 class="text-lg font-semibold">Profile Information</h3>
                </div>
                <form action="{{ route('admin.settings.profile') }}" method="POST" class="p-6">
                    @csrf
                    @method('PUT')

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Name</label>
                            <input type="text" name="name" value="{{ old('name', $user->name) }}" required
                                   class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg focus:ring-2 focus:ring-teal-500">
                            @error('name')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Email</label>
                            <input type="email" name="email" value="{{ old('email', $user->email) }}" required
                                   class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg focus:ring-2 focus:ring-teal-500">
                            @error('email')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="mt-6 flex justify-end">
                        <button type="submit" class="px-6 py-2 bg-gradient-to-r from-teal-500 to-cyan-600 text-white rounded-lg hover:shadow-lg transition-all font-medium">
                            Update Profile
                        </button>
                    </div>
                </form>
            </div>

            {{-- Change Password --}}
            <div class="bg-white/60 dark:bg-gray-800/60 backdrop-blur-md border border-white/20 rounded-2xl shadow overflow-hidden mb-6">
                <div class="px-6 py-4 bg-gradient-to-r from-blue-500 to-indigo-600 text-white">
                    <h3 class="text-lg font-semibold">Change Password</h3>
                </div>
                <form action="{{ route('admin.settings.password') }}" method="POST" class="p-6">
                    @csrf
                    @method('PUT')

                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Current Password</label>
                            <input type="password" name="current_password" required
                                   class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg focus:ring-2 focus:ring-blue-500">
                            @error('current_password')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">New Password</label>
                                <input type="password" name="password" required minlength="8"
                                       class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg focus:ring-2 focus:ring-blue-500">
                                @error('password')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Confirm New Password</label>
                                <input type="password" name="password_confirmation" required
                                       class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg focus:ring-2 focus:ring-blue-500">
                            </div>
                        </div>
                    </div>

                    <div class="mt-6 flex justify-end">
                        <button type="submit" class="px-6 py-2 bg-gradient-to-r from-blue-500 to-indigo-600 text-white rounded-lg hover:shadow-lg transition-all font-medium">
                            Change Password
                        </button>
                    </div>
                </form>
            </div>

            {{-- Notification Preferences --}}
            <div class="bg-white/60 dark:bg-gray-800/60 backdrop-blur-md border border-white/20 rounded-2xl shadow overflow-hidden mb-6">
                <div class="px-6 py-4 bg-gradient-to-r from-purple-500 to-pink-600 text-white">
                    <h3 class="text-lg font-semibold">Notification Preferences</h3>
                </div>
                <form action="{{ route('admin.settings.notifications') }}" method="POST" class="p-6">
                    @csrf
                    @method('PUT')

                    <div class="space-y-4">
                        <label class="flex items-center justify-between p-4 bg-gray-50 dark:bg-gray-700/50 rounded-lg cursor-pointer hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                            <div>
                                <p class="font-medium text-gray-900 dark:text-white">Email Notifications</p>
                                <p class="text-sm text-gray-500">Receive email alerts for important updates</p>
                            </div>
                            <input type="checkbox" name="email_notifications" value="1" 
                                   {{ ($preferences['email_notifications'] ?? true) ? 'checked' : '' }}
                                   class="w-5 h-5 text-teal-600 border-gray-300 rounded focus:ring-teal-500">
                        </label>

                        <label class="flex items-center justify-between p-4 bg-gray-50 dark:bg-gray-700/50 rounded-lg cursor-pointer hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                            <div>
                                <p class="font-medium text-gray-900 dark:text-white">Attendance Alerts</p>
                                <p class="text-sm text-gray-500">Get notified when new attendance is submitted</p>
                            </div>
                            <input type="checkbox" name="attendance_alerts" value="1"
                                   {{ ($preferences['attendance_alerts'] ?? true) ? 'checked' : '' }}
                                   class="w-5 h-5 text-teal-600 border-gray-300 rounded focus:ring-teal-500">
                        </label>

                        <label class="flex items-center justify-between p-4 bg-gray-50 dark:bg-gray-700/50 rounded-lg cursor-pointer hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                            <div>
                                <p class="font-medium text-gray-900 dark:text-white">Daily Schedule Reminder</p>
                                <p class="text-sm text-gray-500">Receive daily summary of scheduled classes</p>
                            </div>
                            <input type="checkbox" name="schedule_reminder" value="1"
                                   {{ ($preferences['schedule_reminder'] ?? false) ? 'checked' : '' }}
                                   class="w-5 h-5 text-teal-600 border-gray-300 rounded focus:ring-teal-500">
                        </label>

                        <label class="flex items-center justify-between p-4 bg-gray-50 dark:bg-gray-700/50 rounded-lg cursor-pointer hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                            <div>
                                <p class="font-medium text-gray-900 dark:text-white">Report Submission Alerts</p>
                                <p class="text-sm text-gray-500">Get notified when tutors submit reports</p>
                            </div>
                            <input type="checkbox" name="report_alerts" value="1"
                                   {{ ($preferences['report_alerts'] ?? true) ? 'checked' : '' }}
                                   class="w-5 h-5 text-teal-600 border-gray-300 rounded focus:ring-teal-500">
                        </label>
                    </div>

                    <div class="mt-6 flex justify-end">
                        <button type="submit" class="px-6 py-2 bg-gradient-to-r from-purple-500 to-pink-600 text-white rounded-lg hover:shadow-lg transition-all font-medium">
                            Save Preferences
                        </button>
                    </div>
                </form>
            </div>

            {{-- Account Info --}}
            <div class="bg-white/60 dark:bg-gray-800/60 backdrop-blur-md border border-white/20 rounded-2xl shadow p-6">
                <h4 class="text-sm font-semibold text-gray-500 dark:text-gray-400 uppercase mb-4">Account Information</h4>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 text-sm">
                    <div>
                        <p class="text-gray-500">Role</p>
                        <p class="font-semibold text-gray-900 dark:text-white">{{ ucfirst($user->role ?? 'Admin') }}</p>
                    </div>
                    <div>
                        <p class="text-gray-500">Member Since</p>
                        <p class="font-semibold text-gray-900 dark:text-white">{{ $user->created_at->format('M j, Y') }}</p>
                    </div>
                    <div>
                        <p class="text-gray-500">Last Login</p>
                        <p class="font-semibold text-gray-900 dark:text-white">{{ $user->last_login_at?->diffForHumans() ?? 'N/A' }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('styles')
    <style>
        @keyframes float { 0%, 100% { transform: translateY(0px); } 50% { transform: translateY(-20px); } }
        .animate-float { animation: float 6s ease-in-out infinite; }
    </style>
    @endpush
</x-app-layout>
