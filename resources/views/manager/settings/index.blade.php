<x-manager-layout>
    <x-slot name="header">{{ __('Settings') }}</x-slot>
    <x-slot name="title">{{ __('Manager - Settings') }}</x-slot>

    <div class="min-h-screen bg-gradient-to-br from-orange-50 via-amber-50 to-yellow-50 dark:from-slate-900 dark:via-slate-800 dark:to-slate-900 py-8 relative overflow-hidden">
        <div class="absolute top-0 left-0 w-72 h-72 bg-[#DA7756] dark:bg-[#A34E30] rounded-full mix-blend-multiply filter blur-xl opacity-30 animate-float"></div>
        <div class="absolute top-0 right-0 w-72 h-72 bg-[#C15F3C] dark:bg-[#A34E30] rounded-full mix-blend-multiply filter blur-xl opacity-30 animate-float" style="animation-delay: 2s;"></div>

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

            {{-- Profile Photo --}}
            <div class="bg-white/60 dark:bg-gray-800/60 backdrop-blur-md border border-white/20 rounded-2xl shadow overflow-hidden mb-6">
                <div class="px-6 py-4 bg-gradient-to-r from-pink-500 to-rose-500 text-white">
                    <h3 class="text-lg font-semibold">Profile Photo</h3>
                </div>
                <form action="{{ route('manager.settings.updateAvatar') }}" method="POST" enctype="multipart/form-data" class="p-6">
                    @csrf
                    @method('PUT')

                    <div class="flex items-center gap-6">
                        {{-- Current Photo Preview --}}
                        <div class="flex-shrink-0">
                            @if($user->profile_photo)
                                <img src="{{ Storage::url($user->profile_photo) }}" alt="Profile Photo"
                                     class="w-24 h-24 rounded-full object-cover border-4 border-white shadow-lg">
                            @else
                                <div class="w-24 h-24 bg-gradient-to-br from-[#C15F3C] to-[#DA7756] rounded-full flex items-center justify-center text-white font-bold text-3xl border-4 border-white shadow-lg">
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
                                          file:bg-[#C15F3C]/10 file:text-[#C15F3C]
                                          hover:file:bg-[#C15F3C]/20
                                          dark:file:bg-[#C15F3C]/30 dark:file:text-[#DA7756]
                                          cursor-pointer">
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-2">
                                JPG, PNG or GIF. Max size 2MB.
                            </p>
                            @error('avatar')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="mt-6 flex justify-end">
                        <button type="submit" class="px-6 py-2 bg-gradient-to-r from-pink-500 to-rose-500 text-white rounded-lg hover:shadow-lg transition-all font-medium">
                            Update Photo
                        </button>
                    </div>
                </form>
            </div>

            {{-- Profile Information --}}
            <div class="bg-white/60 dark:bg-gray-800/60 backdrop-blur-md border border-white/20 rounded-2xl shadow overflow-hidden mb-6">
                <div class="px-6 py-4 bg-gradient-to-r from-[#C15F3C] to-[#DA7756] text-white">
                    <h3 class="text-lg font-semibold">Profile Information</h3>
                </div>
                <form action="{{ route('manager.settings.updateProfile') }}" method="POST" class="p-6">
                    @csrf
                    @method('PUT')

                    <div class="flex items-center gap-6 mb-6">
                        {{-- Profile Avatar --}}
                        <div class="flex-shrink-0">
                            @if($user->profile_photo)
                                <img src="{{ Storage::url($user->profile_photo) }}" alt="Profile Photo"
                                     class="w-24 h-24 rounded-full object-cover border-4 border-white shadow-lg">
                            @else
                                <div class="w-24 h-24 bg-gradient-to-br from-[#C15F3C] to-[#DA7756] rounded-full flex items-center justify-center text-white font-bold text-3xl border-4 border-white shadow-lg">
                                    {{ strtoupper(substr($user->name, 0, 1)) }}
                                </div>
                            @endif
                        </div>

                        <div class="flex-1">
                            <h4 class="text-lg font-semibold text-gray-900 dark:text-white">{{ $user->name }}</h4>
                            <p class="text-sm text-gray-500 dark:text-gray-400">{{ $user->email }}</p>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-[#C15F3C]/10 text-[#C15F3C] mt-2">
                                Manager
                            </span>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Name</label>
                            <input type="text" name="name" value="{{ old('name', $user->name) }}" required
                                   class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg focus:ring-2 focus:ring-[#C15F3C]">
                            @error('name')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Email</label>
                            <input type="email" name="email" value="{{ old('email', $user->email) }}" required
                                   class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg focus:ring-2 focus:ring-[#C15F3C]">
                            @error('email')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Phone (Optional)</label>
                            <input type="text" name="phone" value="{{ old('phone', $user->phone ?? '') }}"
                                   class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg focus:ring-2 focus:ring-[#C15F3C]">
                            @error('phone')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="mt-6 flex justify-end">
                        <button type="submit" class="px-6 py-2 bg-gradient-to-r from-[#C15F3C] to-[#DA7756] text-white rounded-lg hover:shadow-lg transition-all font-medium">
                            Update Profile
                        </button>
                    </div>
                </form>
            </div>

            {{-- Change Password --}}
            <div class="bg-white/60 dark:bg-gray-800/60 backdrop-blur-md border border-white/20 rounded-2xl shadow overflow-hidden mb-6">
                <div class="px-6 py-4 bg-gradient-to-r from-[#A34E30] to-[#C15F3C] text-white">
                    <h3 class="text-lg font-semibold">Change Password</h3>
                </div>
                <form action="{{ route('manager.settings.updatePassword') }}" method="POST" class="p-6">
                    @csrf
                    @method('PUT')

                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Current Password</label>
                            <input type="password" name="current_password" required
                                   class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg focus:ring-2 focus:ring-[#C15F3C]">
                            @error('current_password')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">New Password</label>
                                <input type="password" name="password" required minlength="8"
                                       class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg focus:ring-2 focus:ring-[#C15F3C]">
                                @error('password')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Confirm New Password</label>
                                <input type="password" name="password_confirmation" required
                                       class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg focus:ring-2 focus:ring-[#C15F3C]">
                            </div>
                        </div>
                    </div>

                    <div class="mt-6 flex justify-end">
                        <button type="submit" class="px-6 py-2 bg-gradient-to-r from-[#A34E30] to-[#C15F3C] text-white rounded-lg hover:shadow-lg transition-all font-medium">
                            Change Password
                        </button>
                    </div>
                </form>
            </div>

            {{-- Notification Preferences --}}
            <div class="bg-white/60 dark:bg-gray-800/60 backdrop-blur-md border border-white/20 rounded-2xl shadow overflow-hidden mb-6">
                <div class="px-6 py-4 bg-gradient-to-r from-[#DA7756] to-[#C15F3C] text-white">
                    <h3 class="text-lg font-semibold">Notification Preferences</h3>
                </div>
                <form action="{{ route('manager.settings.updateNotifications') }}" method="POST" class="p-6">
                    @csrf
                    @method('PUT')

                    <div class="space-y-4">
                        <label class="flex items-center justify-between p-4 bg-gray-50 dark:bg-gray-700/50 rounded-lg cursor-pointer hover:bg-orange-50 dark:hover:bg-[#C15F3C]/10 transition-colors">
                            <div>
                                <p class="font-medium text-gray-900 dark:text-white">Email Notifications</p>
                                <p class="text-sm text-gray-500">Receive email alerts for important updates</p>
                            </div>
                            <input type="checkbox" name="email_notifications" value="1"
                                   {{ ($preferences['email_notifications'] ?? true) ? 'checked' : '' }}
                                   class="w-5 h-5 text-[#C15F3C] border-gray-300 rounded focus:ring-[#C15F3C]">
                        </label>

                        <label class="flex items-center justify-between p-4 bg-gray-50 dark:bg-gray-700/50 rounded-lg cursor-pointer hover:bg-orange-50 dark:hover:bg-[#C15F3C]/10 transition-colors">
                            <div>
                                <p class="font-medium text-gray-900 dark:text-white">Attendance Alerts</p>
                                <p class="text-sm text-gray-500">Get notified when new attendance is submitted</p>
                            </div>
                            <input type="checkbox" name="attendance_alerts" value="1"
                                   {{ ($preferences['attendance_alerts'] ?? true) ? 'checked' : '' }}
                                   class="w-5 h-5 text-[#C15F3C] border-gray-300 rounded focus:ring-[#C15F3C]">
                        </label>

                        <label class="flex items-center justify-between p-4 bg-gray-50 dark:bg-gray-700/50 rounded-lg cursor-pointer hover:bg-orange-50 dark:hover:bg-[#C15F3C]/10 transition-colors">
                            <div>
                                <p class="font-medium text-gray-900 dark:text-white">Report Submission Alerts</p>
                                <p class="text-sm text-gray-500">Get notified when tutors submit reports for review</p>
                            </div>
                            <input type="checkbox" name="report_alerts" value="1"
                                   {{ ($preferences['report_alerts'] ?? true) ? 'checked' : '' }}
                                   class="w-5 h-5 text-[#C15F3C] border-gray-300 rounded focus:ring-[#C15F3C]">
                        </label>

                        <label class="flex items-center justify-between p-4 bg-gray-50 dark:bg-gray-700/50 rounded-lg cursor-pointer hover:bg-orange-50 dark:hover:bg-[#C15F3C]/10 transition-colors">
                            <div>
                                <p class="font-medium text-gray-900 dark:text-white">Assessment Alerts</p>
                                <p class="text-sm text-gray-500">Get notified about assessment deadlines and reviews</p>
                            </div>
                            <input type="checkbox" name="assessment_alerts" value="1"
                                   {{ ($preferences['assessment_alerts'] ?? true) ? 'checked' : '' }}
                                   class="w-5 h-5 text-[#C15F3C] border-gray-300 rounded focus:ring-[#C15F3C]">
                        </label>
                    </div>

                    <div class="mt-6 flex justify-end">
                        <button type="submit" class="px-6 py-2 bg-gradient-to-r from-[#DA7756] to-[#C15F3C] text-white rounded-lg hover:shadow-lg transition-all font-medium">
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
                        <p class="font-semibold text-gray-900 dark:text-white">Manager</p>
                    </div>
                    <div>
                        <p class="text-gray-500">Member Since</p>
                        <p class="font-semibold text-gray-900 dark:text-white">{{ $user->created_at->format('M j, Y') }}</p>
                    </div>
                    <div>
                        <p class="text-gray-500">Last Login</p>
                        <p class="font-semibold text-gray-900 dark:text-white">{{ $user->last_login?->diffForHumans() ?? 'N/A' }}</p>
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
</x-manager-layout>
