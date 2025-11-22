<x-app-layout>
    <div class="min-h-screen bg-gradient-to-br from-slate-50 via-cyan-50 to-teal-50 dark:from-slate-900 dark:via-slate-800 dark:to-slate-900 py-8 px-4 sm:px-6 lg:px-8">
    <div class="max-w-7xl mx-auto space-y-8">

        {{-- Welcome Banner --}}
        <div class="relative overflow-hidden rounded-3xl bg-gradient-to-r from-teal-500 via-cyan-500 to-teal-400 p-8 shadow-2xl">
            <div class="absolute inset-0 bg-white/10 backdrop-blur-sm"></div>
            <div class="relative z-10">
                <h1 class="text-4xl font-bold text-white mb-2">
                    Welcome back, {{ auth()->user()->name }}! 🛠️
                </h1>
                <p class="text-xl text-white/90 mb-3">Student & Tutor Coordination Hub</p>
                <p class="text-white/80 text-sm">
                    {{ now()->format('l, F j, Y') }}
                </p>
            </div>
            <div class="absolute top-0 right-0 -mt-4 -mr-4 h-32 w-32 rounded-full bg-white/10 blur-2xl"></div>
            <div class="absolute bottom-0 left-0 -mb-4 -ml-4 h-32 w-32 rounded-full bg-cyan-300/20 blur-2xl"></div>
        </div>

        {{-- Main Statistics --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            {{-- Total Students --}}
            <div class="group relative overflow-hidden rounded-2xl bg-gradient-to-br from-blue-500 to-cyan-500 p-6 shadow-xl hover:shadow-2xl transform hover:-translate-y-1 transition-all duration-300">
                <div class="absolute inset-0 bg-white/10 backdrop-blur-sm"></div>
                <div class="relative z-10">
                    <div class="flex items-center justify-between mb-4">
                        <div class="p-3 bg-white/20 rounded-xl backdrop-blur-sm">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                            </svg>
                        </div>
                    </div>
                    <h3 class="text-white/80 text-sm font-medium mb-1">Total Students</h3>
                    <p class="text-4xl font-bold text-white">127</p>
                    <p class="text-white/70 text-xs mt-2">112 active • 15 inactive</p>
                </div>
            </div>

            {{-- Total Tutors --}}
            <div class="group relative overflow-hidden rounded-2xl bg-gradient-to-br from-purple-500 to-pink-500 p-6 shadow-xl hover:shadow-2xl transform hover:-translate-y-1 transition-all duration-300">
                <div class="absolute inset-0 bg-white/10 backdrop-blur-sm"></div>
                <div class="relative z-10">
                    <div class="flex items-center justify-between mb-4">
                        <div class="p-3 bg-white/20 rounded-xl backdrop-blur-sm">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                            </svg>
                        </div>
                    </div>
                    <h3 class="text-white/80 text-sm font-medium mb-1">Total Tutors</h3>
                    <p class="text-4xl font-bold text-white">24</p>
                    <p class="text-white/70 text-xs mt-2">20 active • 3 inactive • 1 on leave</p>
                </div>
            </div>

            {{-- Today's Classes --}}
            <div class="group relative overflow-hidden rounded-2xl bg-gradient-to-br from-green-500 to-emerald-500 p-6 shadow-xl hover:shadow-2xl transform hover:-translate-y-1 transition-all duration-300">
                <div class="absolute inset-0 bg-white/10 backdrop-blur-sm"></div>
                <div class="relative z-10">
                    <div class="flex items-center justify-between mb-4">
                        <div class="p-3 bg-white/20 rounded-xl backdrop-blur-sm">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                        </div>
                    </div>
                    <h3 class="text-white/80 text-sm font-medium mb-1">Today's Classes</h3>
                    <p class="text-4xl font-bold text-white">18</p>
                    <p class="text-white/70 text-xs mt-2">6 completed • 12 upcoming</p>
                </div>
            </div>

            {{-- Pending Approvals --}}
            <div class="group relative overflow-hidden rounded-2xl bg-gradient-to-br from-orange-500 to-red-500 p-6 shadow-xl hover:shadow-2xl transform hover:-translate-y-1 transition-all duration-300">
                <div class="absolute inset-0 bg-white/10 backdrop-blur-sm"></div>
                <div class="relative z-10">
                    <div class="flex items-center justify-between mb-4">
                        <div class="p-3 bg-white/20 rounded-xl backdrop-blur-sm">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                            </svg>
                        </div>
                    </div>
                    <h3 class="text-white/80 text-sm font-medium mb-1">Pending Attendance</h3>
                    <p class="text-4xl font-bold text-white">12</p>
                    <p class="text-white/70 text-xs mt-2">Awaiting approval</p>
                </div>
            </div>
        </div>

        {{-- Daily Class Schedule & To-Do List --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            {{-- Daily Class Schedule (60% - 2 columns) --}}
            <div class="lg:col-span-2">
                <div class="relative overflow-hidden rounded-2xl bg-white/70 dark:bg-slate-800/70 backdrop-blur-xl shadow-xl p-6 border border-white/20">
                    <h2 class="text-2xl font-bold text-gray-800 dark:text-white mb-2">
                        Coding Classes Scheduled for Today – {{ now()->format('l, F j, Y') }}
                    </h2>

                    <div class="mt-6 space-y-3">
                        {{-- Placeholder Classes --}}
                        <div class="bg-teal-50 dark:bg-teal-900/20 border-l-4 border-teal-500 p-4 rounded-lg">
                            <p class="text-gray-800 dark:text-gray-200">
                                <span class="font-bold text-teal-600 dark:text-teal-400">1.</span>
                                4:00 PM - 5:00 PM: <span class="font-semibold">Python Basics</span> with Tutor Johnson (5 students)
                            </p>
                        </div>
                        <div class="bg-cyan-50 dark:bg-cyan-900/20 border-l-4 border-cyan-500 p-4 rounded-lg">
                            <p class="text-gray-800 dark:text-gray-200">
                                <span class="font-bold text-cyan-600 dark:text-cyan-400">2.</span>
                                5:00 PM - 6:00 PM: <span class="font-semibold">Web Development</span> with Tutor Sarah (8 students)
                            </p>
                        </div>
                        <div class="bg-teal-50 dark:bg-teal-900/20 border-l-4 border-teal-500 p-4 rounded-lg">
                            <p class="text-gray-800 dark:text-gray-200">
                                <span class="font-bold text-teal-600 dark:text-teal-400">3.</span>
                                6:00 PM - 7:00 PM: <span class="font-semibold">JavaScript Advanced</span> with Tutor Michael (6 students)
                            </p>
                        </div>
                        <div class="bg-cyan-50 dark:bg-cyan-900/20 border-l-4 border-cyan-500 p-4 rounded-lg">
                            <p class="text-gray-800 dark:text-gray-200">
                                <span class="font-bold text-cyan-600 dark:text-cyan-400">4.</span>
                                7:00 PM - 8:00 PM: <span class="font-semibold">React Fundamentals</span> with Tutor Emma (7 students)
                            </p>
                        </div>
                    </div>

                    {{-- Footer Notes --}}
                    <div class="mt-6 p-4 bg-gradient-to-r from-teal-100 to-cyan-100 dark:from-teal-900/30 dark:to-cyan-900/30 rounded-xl border border-teal-200 dark:border-teal-700">
                        <p class="text-sm text-gray-700 dark:text-gray-300 space-y-1">
                            <span class="block">📌 All classes are in Nigerian Time.</span>
                            <span class="block">📌 Be punctual to classes.</span>
                            <span class="block">📌 Stay professional in your delivery.</span>
                            <span class="block">📌 Give at least 12-hours notice if you won't be making it to your class.</span>
                            <span class="block mt-2 font-semibold italic text-teal-700 dark:text-teal-300">
                                Let's go raise the next generation of coders! 💪
                            </span>
                        </p>
                    </div>

                    {{-- Action Buttons --}}
                    <div class="mt-6 flex flex-wrap gap-3">
                        <button class="px-6 py-3 bg-gradient-to-r from-teal-500 to-cyan-500 text-white font-semibold rounded-xl shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all duration-300">
                            📝 Post Schedule
                        </button>
                        <button class="px-6 py-3 bg-white dark:bg-slate-700 text-gray-800 dark:text-white font-semibold rounded-xl shadow-md hover:shadow-lg transform hover:-translate-y-0.5 transition-all duration-300 border border-gray-200 dark:border-slate-600">
                            📋 Copy for WhatsApp
                        </button>
                        <button class="px-6 py-3 text-teal-600 dark:text-teal-400 font-semibold rounded-xl hover:bg-teal-50 dark:hover:bg-teal-900/20 transition-all duration-300">
                            ⏭️ Generate Tomorrow's Schedule
                        </button>
                    </div>
                </div>
            </div>

            {{-- To-Do List (40% - 1 column) --}}
            <div class="lg:col-span-1">
                <div class="relative overflow-hidden rounded-2xl bg-white/70 dark:bg-slate-800/70 backdrop-blur-xl shadow-xl p-6 border border-white/20">
                    <h2 class="text-2xl font-bold text-gray-800 dark:text-white mb-6">Today's To-Do List</h2>

                    <div class="space-y-4">
                        <label class="flex items-start space-x-3 group cursor-pointer">
                            <input type="checkbox" class="mt-1 w-5 h-5 text-teal-500 border-gray-300 rounded focus:ring-teal-500 focus:ring-2 cursor-pointer transition-all">
                            <span class="text-gray-700 dark:text-gray-300 group-hover:text-teal-600 dark:group-hover:text-teal-400 transition-colors">
                                Post today's schedule
                            </span>
                        </label>

                        <label class="flex items-start space-x-3 group cursor-pointer">
                            <input type="checkbox" class="mt-1 w-5 h-5 text-teal-500 border-gray-300 rounded focus:ring-teal-500 focus:ring-2 cursor-pointer transition-all">
                            <span class="text-gray-700 dark:text-gray-300 group-hover:text-teal-600 dark:group-hover:text-teal-400 transition-colors">
                                Review pending attendance
                            </span>
                        </label>

                        <label class="flex items-start space-x-3 group cursor-pointer">
                            <input type="checkbox" class="mt-1 w-5 h-5 text-teal-500 border-gray-300 rounded focus:ring-teal-500 focus:ring-2 cursor-pointer transition-all">
                            <span class="text-gray-700 dark:text-gray-300 group-hover:text-teal-600 dark:group-hover:text-teal-400 transition-colors">
                                Follow up inactive students
                            </span>
                        </label>

                        <label class="flex items-start space-x-3 group cursor-pointer">
                            <input type="checkbox" class="mt-1 w-5 h-5 text-teal-500 border-gray-300 rounded focus:ring-teal-500 focus:ring-2 cursor-pointer transition-all">
                            <span class="text-gray-700 dark:text-gray-300 group-hover:text-teal-600 dark:group-hover:text-teal-400 transition-colors">
                                Approve tutor submissions
                            </span>
                        </label>
                    </div>

                    <div class="mt-6 p-4 bg-gradient-to-r from-teal-50 to-cyan-50 dark:from-teal-900/20 dark:to-cyan-900/20 rounded-xl border border-teal-200 dark:border-teal-700">
                        <p class="text-sm text-gray-600 dark:text-gray-400 flex items-center">
                            <svg class="w-5 h-5 mr-2 text-teal-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            Complete tasks to keep operations smooth
                        </p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Notice Board Preview --}}
        <div class="relative overflow-hidden rounded-2xl bg-white/70 dark:bg-slate-800/70 backdrop-blur-xl shadow-xl p-6 border border-white/20">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-2xl font-bold text-gray-800 dark:text-white">📢 Notice Board</h2>
                <div class="flex gap-3">
                    <a href="{{ route('noticeboard.index') }}" class="text-teal-600 dark:text-teal-400 hover:text-teal-700 dark:hover:text-teal-300 font-medium text-sm transition-colors">
                        View All Notices →
                    </a>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                <div class="p-4 bg-gradient-to-br from-blue-50 to-cyan-50 dark:from-blue-900/20 dark:to-cyan-900/20 rounded-xl border border-blue-200 dark:border-blue-700">
                    <div class="flex items-center justify-between mb-2">
                        <span class="px-3 py-1 bg-blue-500 text-white text-xs font-semibold rounded-full">Important</span>
                        <span class="text-xs text-gray-500 dark:text-gray-400">2 days ago</span>
                    </div>
                    <h3 class="font-semibold text-gray-800 dark:text-white mb-2">New Class Time Updates</h3>
                    <p class="text-sm text-gray-600 dark:text-gray-300">Please note the revised schedule for Wednesday classes...</p>
                </div>

                <div class="p-4 bg-gradient-to-br from-purple-50 to-pink-50 dark:from-purple-900/20 dark:to-pink-900/20 rounded-xl border border-purple-200 dark:border-purple-700">
                    <div class="flex items-center justify-between mb-2">
                        <span class="px-3 py-1 bg-purple-500 text-white text-xs font-semibold rounded-full">General</span>
                        <span class="text-xs text-gray-500 dark:text-gray-400">5 days ago</span>
                    </div>
                    <h3 class="font-semibold text-gray-800 dark:text-white mb-2">Tutor Training Session</h3>
                    <p class="text-sm text-gray-600 dark:text-gray-300">Join us for the monthly tutor development workshop...</p>
                </div>

                <div class="p-4 bg-gradient-to-br from-green-50 to-emerald-50 dark:from-green-900/20 dark:to-emerald-900/20 rounded-xl border border-green-200 dark:border-green-700">
                    <div class="flex items-center justify-between mb-2">
                        <span class="px-3 py-1 bg-green-500 text-white text-xs font-semibold rounded-full">Reminder</span>
                        <span class="text-xs text-gray-500 dark:text-gray-400">1 week ago</span>
                    </div>
                    <h3 class="font-semibold text-gray-800 dark:text-white mb-2">Monthly Reports Due</h3>
                    <p class="text-sm text-gray-600 dark:text-gray-300">Submit your monthly progress reports by the 25th...</p>
                </div>
            </div>

            <button class="w-full px-6 py-3 bg-gradient-to-r from-teal-500 to-cyan-500 text-white font-semibold rounded-xl shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all duration-300">
                ➕ Post New Notice
            </button>
        </div>

        {{-- Recent Students Table --}}
        <div class="relative overflow-hidden rounded-2xl bg-white/70 dark:bg-slate-800/70 backdrop-blur-xl shadow-xl border border-white/20">
            <div class="p-6 border-b border-gray-200 dark:border-slate-700">
                <h2 class="text-2xl font-bold text-gray-800 dark:text-white">👨‍🎓 Recent Students</h2>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gradient-to-r from-teal-50 to-cyan-50 dark:from-teal-900/20 dark:to-cyan-900/20">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider">Name</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider">Tutor</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider">Last Class</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-slate-700">
                        <tr class="hover:bg-teal-50/50 dark:hover:bg-teal-900/10 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="h-10 w-10 rounded-full bg-gradient-to-br from-blue-400 to-cyan-400 flex items-center justify-center text-white font-semibold">
                                        AO
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900 dark:text-white">Adewale Ogunleye</div>
                                        <div class="text-sm text-gray-500 dark:text-gray-400">adewale@example.com</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700 dark:text-gray-300">Tutor Johnson</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700 dark:text-gray-300">2 days ago</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400">
                                    Active
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm space-x-2">
                                <button class="text-teal-600 hover:text-teal-900 dark:text-teal-400 dark:hover:text-teal-300">View</button>
                                <button class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300">Edit</button>
                                <button class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300">Deactivate</button>
                            </td>
                        </tr>
                        <tr class="hover:bg-teal-50/50 dark:hover:bg-teal-900/10 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="h-10 w-10 rounded-full bg-gradient-to-br from-purple-400 to-pink-400 flex items-center justify-center text-white font-semibold">
                                        CI
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900 dark:text-white">Chioma Ikechukwu</div>
                                        <div class="text-sm text-gray-500 dark:text-gray-400">chioma@example.com</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700 dark:text-gray-300">Tutor Sarah</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700 dark:text-gray-300">Yesterday</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400">
                                    Active
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm space-x-2">
                                <button class="text-teal-600 hover:text-teal-900 dark:text-teal-400 dark:hover:text-teal-300">View</button>
                                <button class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300">Edit</button>
                                <button class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300">Deactivate</button>
                            </td>
                        </tr>
                        <tr class="hover:bg-teal-50/50 dark:hover:bg-teal-900/10 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="h-10 w-10 rounded-full bg-gradient-to-br from-green-400 to-emerald-400 flex items-center justify-center text-white font-semibold">
                                        EA
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900 dark:text-white">Emeka Adekunle</div>
                                        <div class="text-sm text-gray-500 dark:text-gray-400">emeka@example.com</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700 dark:text-gray-300">Tutor Michael</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700 dark:text-gray-300">5 days ago</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-400">
                                    Inactive
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm space-x-2">
                                <button class="text-teal-600 hover:text-teal-900 dark:text-teal-400 dark:hover:text-teal-300">View</button>
                                <button class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300">Edit</button>
                                <button class="text-green-600 hover:text-green-900 dark:text-green-400 dark:hover:text-green-300">Activate</button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="px-6 py-4 border-t border-gray-200 dark:border-slate-700">
                <a href="{{ route('students.index') }}" class="text-teal-600 dark:text-teal-400 hover:text-teal-700 dark:hover:text-teal-300 font-medium text-sm">
                    View All Students →
                </a>
            </div>
        </div>

        {{-- Recent Tutors Table --}}
        <div class="relative overflow-hidden rounded-2xl bg-white/70 dark:bg-slate-800/70 backdrop-blur-xl shadow-xl border border-white/20">
            <div class="p-6 border-b border-gray-200 dark:border-slate-700">
                <h2 class="text-2xl font-bold text-gray-800 dark:text-white">👨‍🏫 Recent Tutors</h2>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gradient-to-r from-teal-50 to-cyan-50 dark:from-teal-900/20 dark:to-cyan-900/20">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider">Name</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider">Students Assigned</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider">Last Active</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-slate-700">
                        <tr class="hover:bg-teal-50/50 dark:hover:bg-teal-900/10 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="h-10 w-10 rounded-full bg-gradient-to-br from-indigo-400 to-purple-400 flex items-center justify-center text-white font-semibold">
                                        TJ
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900 dark:text-white">Tutor Johnson</div>
                                        <div class="text-sm text-gray-500 dark:text-gray-400">johnson@example.com</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700 dark:text-gray-300">12 students</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700 dark:text-gray-300">Today</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400">
                                    Active
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm space-x-2">
                                <button class="text-teal-600 hover:text-teal-900 dark:text-teal-400 dark:hover:text-teal-300">View</button>
                                <button class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300">Edit</button>
                                <button class="text-purple-600 hover:text-purple-900 dark:text-purple-400 dark:hover:text-purple-300">Assign</button>
                            </td>
                        </tr>
                        <tr class="hover:bg-teal-50/50 dark:hover:bg-teal-900/10 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="h-10 w-10 rounded-full bg-gradient-to-br from-pink-400 to-rose-400 flex items-center justify-center text-white font-semibold">
                                        TS
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900 dark:text-white">Tutor Sarah</div>
                                        <div class="text-sm text-gray-500 dark:text-gray-400">sarah@example.com</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700 dark:text-gray-300">15 students</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700 dark:text-gray-300">Yesterday</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400">
                                    Active
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm space-x-2">
                                <button class="text-teal-600 hover:text-teal-900 dark:text-teal-400 dark:hover:text-teal-300">View</button>
                                <button class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300">Edit</button>
                                <button class="text-purple-600 hover:text-purple-900 dark:text-purple-400 dark:hover:text-purple-300">Assign</button>
                            </td>
                        </tr>
                        <tr class="hover:bg-teal-50/50 dark:hover:bg-teal-900/10 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="h-10 w-10 rounded-full bg-gradient-to-br from-orange-400 to-red-400 flex items-center justify-center text-white font-semibold">
                                        TM
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900 dark:text-white">Tutor Michael</div>
                                        <div class="text-sm text-gray-500 dark:text-gray-400">michael@example.com</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700 dark:text-gray-300">8 students</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700 dark:text-gray-300">3 days ago</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-orange-100 text-orange-800 dark:bg-orange-900/30 dark:text-orange-400">
                                    On Leave
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm space-x-2">
                                <button class="text-teal-600 hover:text-teal-900 dark:text-teal-400 dark:hover:text-teal-300">View</button>
                                <button class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300">Edit</button>
                                <button class="text-purple-600 hover:text-purple-900 dark:text-purple-400 dark:hover:text-purple-300">Assign</button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="px-6 py-4 border-t border-gray-200 dark:border-slate-700">
                <a href="{{ route('tutors.index') }}" class="text-teal-600 dark:text-teal-400 hover:text-teal-700 dark:hover:text-teal-300 font-medium text-sm">
                    View All Tutors →
                </a>
            </div>
        </div>

        {{-- Quick Actions Grid --}}
        <div class="relative overflow-hidden rounded-2xl bg-white/70 dark:bg-slate-800/70 backdrop-blur-xl shadow-xl p-6 border border-white/20">
            <h2 class="text-2xl font-bold text-gray-800 dark:text-white mb-6">⚡ Quick Actions</h2>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                <a href="{{ route('students.create') }}" class="group relative overflow-hidden p-6 bg-gradient-to-br from-blue-500 to-cyan-500 rounded-xl shadow-lg hover:shadow-2xl transform hover:-translate-y-1 transition-all duration-300">
                    <div class="absolute inset-0 bg-white/10 backdrop-blur-sm"></div>
                    <div class="relative z-10 text-center">
                        <svg class="w-12 h-12 mx-auto mb-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                        </svg>
                        <h3 class="text-white font-semibold text-lg">Add Student</h3>
                    </div>
                </a>

                <a href="{{ route('tutors.create') }}" class="group relative overflow-hidden p-6 bg-gradient-to-br from-purple-500 to-pink-500 rounded-xl shadow-lg hover:shadow-2xl transform hover:-translate-y-1 transition-all duration-300">
                    <div class="absolute inset-0 bg-white/10 backdrop-blur-sm"></div>
                    <div class="relative z-10 text-center">
                        <svg class="w-12 h-12 mx-auto mb-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                        </svg>
                        <h3 class="text-white font-semibold text-lg">Add Tutor</h3>
                    </div>
                </a>

                <a href="{{ route('schedule.today') }}" class="group relative overflow-hidden p-6 bg-gradient-to-br from-green-500 to-emerald-500 rounded-xl shadow-lg hover:shadow-2xl transform hover:-translate-y-1 transition-all duration-300">
                    <div class="absolute inset-0 bg-white/10 backdrop-blur-sm"></div>
                    <div class="relative z-10 text-center">
                        <svg class="w-12 h-12 mx-auto mb-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        <h3 class="text-white font-semibold text-lg">Post Schedule</h3>
                    </div>
                </a>

                <a href="{{ route('attendance.pending') }}" class="group relative overflow-hidden p-6 bg-gradient-to-br from-orange-500 to-red-500 rounded-xl shadow-lg hover:shadow-2xl transform hover:-translate-y-1 transition-all duration-300">
                    <div class="absolute inset-0 bg-white/10 backdrop-blur-sm"></div>
                    <div class="relative z-10 text-center">
                        <svg class="w-12 h-12 mx-auto mb-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                        </svg>
                        <h3 class="text-white font-semibold text-lg">Approve Attendance</h3>
                    </div>
                </a>

                <a href="{{ route('reports.index') }}" class="group relative overflow-hidden p-6 bg-gradient-to-br from-indigo-500 to-purple-500 rounded-xl shadow-lg hover:shadow-2xl transform hover:-translate-y-1 transition-all duration-300">
                    <div class="absolute inset-0 bg-white/10 backdrop-blur-sm"></div>
                    <div class="relative z-10 text-center">
                        <svg class="w-12 h-12 mx-auto mb-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        <h3 class="text-white font-semibold text-lg">View Reports</h3>
                    </div>
                </a>

                <a href="{{ route('noticeboard.index') }}" class="group relative overflow-hidden p-6 bg-gradient-to-br from-teal-500 to-cyan-500 rounded-xl shadow-lg hover:shadow-2xl transform hover:-translate-y-1 transition-all duration-300">
                    <div class="absolute inset-0 bg-white/10 backdrop-blur-sm"></div>
                    <div class="relative z-10 text-center">
                        <svg class="w-12 h-12 mx-auto mb-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z" />
                        </svg>
                        <h3 class="text-white font-semibold text-lg">Notice Board</h3>
                    </div>
                </a>
            </div>
        </div>

    </div>
    </div>
</x-app-layout>
