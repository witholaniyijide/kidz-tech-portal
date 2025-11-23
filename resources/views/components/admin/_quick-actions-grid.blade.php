@props(['actions' => []])

<x-ui.card role="region" aria-label="Quick Actions">
    <div class="p-6">
        <h2 class="text-2xl font-bold text-gray-800 dark:text-white mb-6 font-inter">⚡ Quick Actions</h2>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4" role="list" aria-label="Available quick actions">
            {{-- Add Student --}}
            <a
                href="{{ route('students.create') }}"
                class="group relative overflow-hidden p-6 bg-gradient-to-br from-blue-500 to-cyan-500 rounded-xl shadow-lg hover:shadow-2xl transform hover:-translate-y-1 transition-all duration-300 focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-500 focus-visible:ring-offset-2"
                role="listitem"
                aria-label="Add new student"
            >
                <div class="absolute inset-0 bg-white/10 backdrop-blur-sm"></div>
                <div class="relative z-10 text-center">
                    <svg class="w-12 h-12 mx-auto mb-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                    </svg>
                    <h3 class="text-white font-semibold text-lg font-inter">Add Student</h3>
                </div>
            </a>

            {{-- Add Tutor --}}
            <a
                href="{{ route('tutors.create') }}"
                class="group relative overflow-hidden p-6 bg-gradient-to-br from-purple-500 to-pink-500 rounded-xl shadow-lg hover:shadow-2xl transform hover:-translate-y-1 transition-all duration-300 focus:outline-none focus-visible:ring-2 focus-visible:ring-purple-500 focus-visible:ring-offset-2"
                role="listitem"
                aria-label="Add new tutor"
            >
                <div class="absolute inset-0 bg-white/10 backdrop-blur-sm"></div>
                <div class="relative z-10 text-center">
                    <svg class="w-12 h-12 mx-auto mb-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                    </svg>
                    <h3 class="text-white font-semibold text-lg font-inter">Add Tutor</h3>
                </div>
            </a>

            {{-- Post Schedule --}}
            <a
                href="{{ route('schedule.today') }}"
                class="group relative overflow-hidden p-6 bg-gradient-to-br from-green-500 to-emerald-500 rounded-xl shadow-lg hover:shadow-2xl transform hover:-translate-y-1 transition-all duration-300 focus:outline-none focus-visible:ring-2 focus-visible:ring-green-500 focus-visible:ring-offset-2"
                role="listitem"
                aria-label="Post today's schedule"
            >
                <div class="absolute inset-0 bg-white/10 backdrop-blur-sm"></div>
                <div class="relative z-10 text-center">
                    <svg class="w-12 h-12 mx-auto mb-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                    <h3 class="text-white font-semibold text-lg font-inter">Post Schedule</h3>
                </div>
            </a>

            {{-- Approve Attendance --}}
            <a
                href="{{ route('attendance.pending') }}"
                class="group relative overflow-hidden p-6 bg-gradient-to-br from-orange-500 to-red-500 rounded-xl shadow-lg hover:shadow-2xl transform hover:-translate-y-1 transition-all duration-300 focus:outline-none focus-visible:ring-2 focus-visible:ring-orange-500 focus-visible:ring-offset-2"
                role="listitem"
                aria-label="Approve pending attendance"
            >
                <div class="absolute inset-0 bg-white/10 backdrop-blur-sm"></div>
                <div class="relative z-10 text-center">
                    <svg class="w-12 h-12 mx-auto mb-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                    </svg>
                    <h3 class="text-white font-semibold text-lg font-inter">Approve Attendance</h3>
                </div>
            </a>

            {{-- View Reports --}}
            <a
                href="{{ route('reports.index') }}"
                class="group relative overflow-hidden p-6 bg-gradient-to-br from-indigo-500 to-purple-500 rounded-xl shadow-lg hover:shadow-2xl transform hover:-translate-y-1 transition-all duration-300 focus:outline-none focus-visible:ring-2 focus-visible:ring-indigo-500 focus-visible:ring-offset-2"
                role="listitem"
                aria-label="View all reports"
            >
                <div class="absolute inset-0 bg-white/10 backdrop-blur-sm"></div>
                <div class="relative z-10 text-center">
                    <svg class="w-12 h-12 mx-auto mb-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    <h3 class="text-white font-semibold text-lg font-inter">View Reports</h3>
                </div>
            </a>

            {{-- Notice Board --}}
            <a
                href="{{ route('noticeboard.index') }}"
                class="group relative overflow-hidden p-6 bg-gradient-to-br from-teal-500 to-cyan-500 rounded-xl shadow-lg hover:shadow-2xl transform hover:-translate-y-1 transition-all duration-300 focus:outline-none focus-visible:ring-2 focus-visible:ring-teal-500 focus-visible:ring-offset-2"
                role="listitem"
                aria-label="Access notice board"
            >
                <div class="absolute inset-0 bg-white/10 backdrop-blur-sm"></div>
                <div class="relative z-10 text-center">
                    <svg class="w-12 h-12 mx-auto mb-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z" />
                    </svg>
                    <h3 class="text-white font-semibold text-lg font-inter">Notice Board</h3>
                </div>
            </a>
        </div>
    </div>
</x-ui.card>
