@props(['actions' => []])

<div class="glass-card rounded-2xl p-8 shadow-xl" role="region" aria-label="Quick Actions" style="animation-delay: 0.8s;">
    <h3 class="text-2xl font-bold text-gray-900 dark:text-white mb-6 flex items-center">
        <svg class="w-6 h-6 mr-2 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
        </svg>
        Quick Actions
    </h3>

    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4" role="list" aria-label="Available quick actions">

        {{-- Add Student --}}
        <a href="{{ route('students.create') }}" class="group" role="listitem">
            <div class="glass-card rounded-xl p-6 text-center hover-lift cursor-pointer transform transition-all duration-300">
                <div class="w-16 h-16 mx-auto mb-3 rounded-xl bg-gradient-to-br from-[#14B8A6] to-[#06B6D4] flex items-center justify-center shadow-lg group-hover:scale-110 transition-transform">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
                    </svg>
                </div>
                <span class="text-sm font-semibold text-gray-700 dark:text-gray-300 group-hover:text-teal-600">Add Student</span>
            </div>
        </a>

        {{-- Add Tutor --}}
        <a href="{{ route('tutors.create') }}" class="group" role="listitem">
            <div class="glass-card rounded-xl p-6 text-center hover-lift cursor-pointer transform transition-all duration-300">
                <div class="w-16 h-16 mx-auto mb-3 rounded-xl bg-gradient-to-br from-[#06B6D4] to-[#0EA5E9] flex items-center justify-center shadow-lg group-hover:scale-110 transition-transform">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                    </svg>
                </div>
                <span class="text-sm font-semibold text-gray-700 dark:text-gray-300 group-hover:text-cyan-600">Add Tutor</span>
            </div>
        </a>

        {{-- Post Schedule --}}
        <a href="{{ route('schedule.today') }}" class="group" role="listitem">
            <div class="glass-card rounded-xl p-6 text-center hover-lift cursor-pointer transform transition-all duration-300">
                <div class="w-16 h-16 mx-auto mb-3 rounded-xl bg-gradient-to-br from-[#10B981] to-[#059669] flex items-center justify-center shadow-lg group-hover:scale-110 transition-transform">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                </div>
                <span class="text-sm font-semibold text-gray-700 dark:text-gray-300 group-hover:text-green-600">Post Schedule</span>
            </div>
        </a>

        {{-- Approve Attendance --}}
        <a href="{{ route('attendance.pending') }}" class="group" role="listitem">
            <div class="glass-card rounded-xl p-6 text-center hover-lift cursor-pointer transform transition-all duration-300">
                <div class="w-16 h-16 mx-auto mb-3 rounded-xl bg-gradient-to-br from-[#F59E0B] to-[#EF4444] flex items-center justify-center shadow-lg group-hover:scale-110 transition-transform">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                    </svg>
                </div>
                <span class="text-sm font-semibold text-gray-700 dark:text-gray-300 group-hover:text-orange-600">Approve Attendance</span>
            </div>
        </a>

        {{-- View Reports --}}
        <a href="{{ route('reports.index') }}" class="group" role="listitem">
            <div class="glass-card rounded-xl p-6 text-center hover-lift cursor-pointer transform transition-all duration-300">
                <div class="w-16 h-16 mx-auto mb-3 rounded-xl bg-gradient-to-br from-[#8B5CF6] to-[#7C3AED] flex items-center justify-center shadow-lg group-hover:scale-110 transition-transform">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                </div>
                <span class="text-sm font-semibold text-gray-700 dark:text-gray-300 group-hover:text-purple-600">View Reports</span>
            </div>
        </a>

        {{-- Notice Board --}}
        <a href="{{ route('noticeboard.index') }}" class="group" role="listitem">
            <div class="glass-card rounded-xl p-6 text-center hover-lift cursor-pointer transform transition-all duration-300">
                <div class="w-16 h-16 mx-auto mb-3 rounded-xl bg-gradient-to-br from-[#EC4899] to-[#DB2777] flex items-center justify-center shadow-lg group-hover:scale-110 transition-transform">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"/>
                    </svg>
                </div>
                <span class="text-sm font-semibold text-gray-700 dark:text-gray-300 group-hover:text-pink-600">Notice Board</span>
            </div>
        </a>

    </div>
</div>
