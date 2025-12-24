<x-student-layout title="My Profile">
    <div class="space-y-6">
        <!-- Page Header -->
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">My Profile</h1>
            <p class="text-gray-600 dark:text-gray-400">View and update your profile information</p>
        </div>

        <!-- Profile Card -->
        <div class="glass-card rounded-2xl p-6">
            <div class="flex flex-col md:flex-row md:items-start gap-6">
                <!-- Avatar -->
                <div class="flex-shrink-0">
                    <div class="w-24 h-24 rounded-2xl bg-gradient-student flex items-center justify-center text-white text-4xl font-bold shadow-xl">
                        {{ strtoupper(substr($user->name ?? 'U', 0, 1)) }}
                    </div>
                </div>

                <!-- Info -->
                <div class="flex-1">
                    <h2 class="text-xl font-bold text-gray-900 dark:text-white">{{ $user->name }}</h2>
                    <p class="text-gray-500 dark:text-gray-400">{{ $user->email }}</p>

                    @if($student)
                        <div class="mt-4 space-y-2">
                            @if($student->tutor)
                                <div class="flex items-center text-sm text-gray-600 dark:text-gray-400">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                    </svg>
                                    Tutor: {{ $student->tutor->first_name }} {{ $student->tutor->last_name }}
                                </div>
                            @endif
                            @if($student->current_course)
                                <div class="flex items-center text-sm text-gray-600 dark:text-gray-400">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                                    </svg>
                                    Current Course: {{ $student->current_course }}
                                </div>
                            @endif
                            @if($student->roadmap_stage)
                                <div class="flex items-center text-sm text-gray-600 dark:text-gray-400">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                                    </svg>
                                    Stage: {{ $student->roadmap_stage }}
                                </div>
                            @endif
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Update Profile Form -->
        <div class="glass-card rounded-2xl p-6">
            <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4">Update Profile</h3>

            <form method="POST" action="{{ route('student.profile.update') }}" class="space-y-6">
                @csrf
                @method('PUT')

                <!-- Name -->
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                        Name <span class="text-red-500">*</span>
                    </label>
                    <input type="text" id="name" name="name" value="{{ old('name', $user->name) }}" required
                           class="w-full rounded-xl border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-[#F5A623] focus:border-[#F5A623]">
                    @error('name')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Email -->
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                        Email <span class="text-red-500">*</span>
                    </label>
                    <input type="email" id="email" name="email" value="{{ old('email', $user->email) }}" required
                           class="w-full rounded-xl border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-[#F5A623] focus:border-[#F5A623]">
                    @error('email')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <hr class="border-gray-200 dark:border-gray-700">

                <p class="text-sm text-gray-500 dark:text-gray-400">Leave password fields empty if you don't want to change your password.</p>

                <!-- Current Password -->
                <div>
                    <label for="current_password" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                        Current Password
                    </label>
                    <input type="password" id="current_password" name="current_password"
                           class="w-full rounded-xl border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-[#F5A623] focus:border-[#F5A623]">
                    @error('current_password')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <!-- New Password -->
                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                        New Password
                    </label>
                    <input type="password" id="password" name="password"
                           class="w-full rounded-xl border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-[#F5A623] focus:border-[#F5A623]">
                    @error('password')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Confirm Password -->
                <div>
                    <label for="password_confirmation" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                        Confirm New Password
                    </label>
                    <input type="password" id="password_confirmation" name="password_confirmation"
                           class="w-full rounded-xl border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-[#F5A623] focus:border-[#F5A623]">
                </div>

                <!-- Submit Button -->
                <div class="flex justify-end">
                    <button type="submit" class="btn-student-primary px-6 py-2.5 rounded-xl font-medium">
                        Save Changes
                    </button>
                </div>
            </form>
        </div>

        @if($student)
            <!-- Progress Summary -->
            <div class="glass-card rounded-2xl p-6">
                <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4">Learning Progress</h3>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <!-- Overall Progress -->
                    <div class="p-4 rounded-xl bg-gray-50 dark:bg-gray-800">
                        <div class="text-3xl font-bold text-[#F5A623]">{{ $student->progressPercentage() }}%</div>
                        <div class="text-sm text-gray-500 dark:text-gray-400">Overall Progress</div>
                    </div>

                    <!-- Milestones -->
                    @if(method_exists($student, 'completedMilestones'))
                        <div class="p-4 rounded-xl bg-gray-50 dark:bg-gray-800">
                            <div class="text-3xl font-bold text-green-600">{{ $student->completedMilestones()->count() }}</div>
                            <div class="text-sm text-gray-500 dark:text-gray-400">Milestones Completed</div>
                        </div>
                    @endif

                    <!-- Joined Date -->
                    <div class="p-4 rounded-xl bg-gray-50 dark:bg-gray-800">
                        <div class="text-xl font-bold text-gray-900 dark:text-white">{{ $student->created_at->format('M Y') }}</div>
                        <div class="text-sm text-gray-500 dark:text-gray-400">Member Since</div>
                    </div>
                </div>
            </div>
        @endif
    </div>
</x-student-layout>
