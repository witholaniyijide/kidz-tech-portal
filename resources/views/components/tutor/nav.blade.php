@props(['currentRoute' => null])

<nav class="bg-[#1D2A6D] dark:bg-slate-900 h-full overflow-y-auto">
    <div class="p-6">
        <!-- Logo/Brand -->
        <div class="flex items-center gap-3 mb-8">
            <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-[#4B51FF] to-[#22D3EE] flex items-center justify-center shadow-lg">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                </svg>
            </div>
            <div>
                <h2 class="font-bold text-white">Tutor Portal</h2>
                <p class="text-xs text-slate-400">Kidz Tech</p>
            </div>
        </div>

        <!-- Navigation Items -->
        <div class="space-y-1">
            <!-- Dashboard -->
            <a href="{{ route('tutor.dashboard') }}" 
               class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all
               {{ request()->routeIs('tutor.dashboard') 
                   ? 'bg-gradient-to-r from-[#4B51FF]/20 to-[#22D3EE]/10 border-l-4 border-[#22D3EE] text-white font-semibold' 
                   : 'text-slate-300 hover:text-white hover:bg-white/5' }}">
                <svg class="w-5 h-5 {{ request()->routeIs('tutor.dashboard') ? 'text-[#22D3EE]' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                </svg>
                <span>Dashboard</span>
            </a>

            <!-- Section: Students -->
            <div class="pt-4 pb-2">
                <p class="px-4 text-xs font-semibold text-slate-500 uppercase tracking-wider">Students</p>
            </div>

            <!-- My Students -->
            <a href="{{ route('tutor.students.index') }}" 
               class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all
               {{ request()->routeIs('tutor.students.*') 
                   ? 'bg-gradient-to-r from-[#4B51FF]/20 to-[#22D3EE]/10 border-l-4 border-[#22D3EE] text-white font-semibold' 
                   : 'text-slate-300 hover:text-white hover:bg-white/5' }}">
                <svg class="w-5 h-5 {{ request()->routeIs('tutor.students.*') ? 'text-[#22D3EE]' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                </svg>
                <span>My Students</span>
            </a>

            <!-- Section: Attendance -->
            <div class="pt-4 pb-2">
                <p class="px-4 text-xs font-semibold text-slate-500 uppercase tracking-wider">Attendance</p>
            </div>

            <!-- Submit Attendance -->
            <a href="{{ route('tutor.attendance.create') }}" 
               class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all
               {{ request()->routeIs('tutor.attendance.create') 
                   ? 'bg-gradient-to-r from-[#4B51FF]/20 to-[#22D3EE]/10 border-l-4 border-[#22D3EE] text-white font-semibold' 
                   : 'text-slate-300 hover:text-white hover:bg-white/5' }}">
                <svg class="w-5 h-5 {{ request()->routeIs('tutor.attendance.create') ? 'text-[#22D3EE]' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                <span>Submit Attendance</span>
            </a>

            <!-- View Attendance -->
            <a href="{{ route('tutor.attendance.index') }}" 
               class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all
               {{ request()->routeIs('tutor.attendance.index') || request()->routeIs('tutor.attendance.show') 
                   ? 'bg-gradient-to-r from-[#4B51FF]/20 to-[#22D3EE]/10 border-l-4 border-[#22D3EE] text-white font-semibold' 
                   : 'text-slate-300 hover:text-white hover:bg-white/5' }}">
                <svg class="w-5 h-5 {{ request()->routeIs('tutor.attendance.index') ? 'text-[#22D3EE]' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                </svg>
                <span>View Attendance</span>
            </a>

            <!-- Section: Reports -->
            <div class="pt-4 pb-2">
                <p class="px-4 text-xs font-semibold text-slate-500 uppercase tracking-wider">Reports</p>
            </div>

            <!-- Submit New Report -->
            <a href="{{ route('tutor.reports.create') }}" 
               class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all
               {{ request()->routeIs('tutor.reports.create') 
                   ? 'bg-gradient-to-r from-[#4B51FF]/20 to-[#22D3EE]/10 border-l-4 border-[#22D3EE] text-white font-semibold' 
                   : 'text-slate-300 hover:text-white hover:bg-white/5' }}">
                <svg class="w-5 h-5 {{ request()->routeIs('tutor.reports.create') ? 'text-[#22D3EE]' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <span>Submit New Report</span>
            </a>

            <!-- My Reports -->
            <a href="{{ route('tutor.reports.index') }}" 
               class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all
               {{ request()->routeIs('tutor.reports.index') || request()->routeIs('tutor.reports.show') || request()->routeIs('tutor.reports.edit')
                   ? 'bg-gradient-to-r from-[#4B51FF]/20 to-[#22D3EE]/10 border-l-4 border-[#22D3EE] text-white font-semibold' 
                   : 'text-slate-300 hover:text-white hover:bg-white/5' }}">
                <svg class="w-5 h-5 {{ request()->routeIs('tutor.reports.index') ? 'text-[#22D3EE]' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                <span>My Reports</span>
            </a>

            <!-- Section: Performance -->
            <div class="pt-4 pb-2">
                <p class="px-4 text-xs font-semibold text-slate-500 uppercase tracking-wider">Performance</p>
            </div>

            <!-- My Assessments -->
            <a href="{{ route('tutor.performance.index') }}" 
               class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all
               {{ request()->routeIs('tutor.performance.*') 
                   ? 'bg-gradient-to-r from-[#4B51FF]/20 to-[#22D3EE]/10 border-l-4 border-[#22D3EE] text-white font-semibold' 
                   : 'text-slate-300 hover:text-white hover:bg-white/5' }}">
                <svg class="w-5 h-5 {{ request()->routeIs('tutor.performance.*') ? 'text-[#22D3EE]' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                </svg>
                <span>My Assessments</span>
            </a>

            <!-- Section: Schedule -->
            <div class="pt-4 pb-2">
                <p class="px-4 text-xs font-semibold text-slate-500 uppercase tracking-wider">Schedule</p>
            </div>

            <!-- Today's Schedule -->
            <a href="{{ route('tutor.schedule.today') }}" 
               class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all
               {{ request()->routeIs('tutor.schedule.today') 
                   ? 'bg-gradient-to-r from-[#4B51FF]/20 to-[#22D3EE]/10 border-l-4 border-[#22D3EE] text-white font-semibold' 
                   : 'text-slate-300 hover:text-white hover:bg-white/5' }}">
                <svg class="w-5 h-5 {{ request()->routeIs('tutor.schedule.today') ? 'text-[#22D3EE]' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
                <span>Today's Schedule</span>
            </a>

            <!-- My Availability -->
            <a href="{{ route('tutor.availability.index') }}" 
               class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all
               {{ request()->routeIs('tutor.availability.*') 
                   ? 'bg-gradient-to-r from-[#4B51FF]/20 to-[#22D3EE]/10 border-l-4 border-[#22D3EE] text-white font-semibold' 
                   : 'text-slate-300 hover:text-white hover:bg-white/5' }}">
                <svg class="w-5 h-5 {{ request()->routeIs('tutor.availability.*') ? 'text-[#22D3EE]' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <span>My Availability</span>
            </a>

            <!-- Section: Notices -->
            <div class="pt-4 pb-2">
                <p class="px-4 text-xs font-semibold text-slate-500 uppercase tracking-wider">Notices</p>
            </div>

            <!-- Notice Board -->
            <a href="{{ route('tutor.notices.index') }}" 
               class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all
               {{ request()->routeIs('tutor.notices.*') 
                   ? 'bg-gradient-to-r from-[#4B51FF]/20 to-[#22D3EE]/10 border-l-4 border-[#22D3EE] text-white font-semibold' 
                   : 'text-slate-300 hover:text-white hover:bg-white/5' }}">
                <svg class="w-5 h-5 {{ request()->routeIs('tutor.notices.*') ? 'text-[#22D3EE]' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                </svg>
                <span>Notice Board</span>
            </a>

            <div class="border-t border-white/10 my-4"></div>

            <!-- Profile Settings -->
            <a href="{{ route('tutor.profile.edit') }}" 
               class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all
               {{ request()->routeIs('tutor.profile.*') 
                   ? 'bg-gradient-to-r from-[#4B51FF]/20 to-[#22D3EE]/10 border-l-4 border-[#22D3EE] text-white font-semibold' 
                   : 'text-slate-300 hover:text-white hover:bg-white/5' }}">
                <svg class="w-5 h-5 {{ request()->routeIs('tutor.profile.*') ? 'text-[#22D3EE]' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                </svg>
                <span>My Profile</span>
            </a>

            <!-- Logout -->
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="w-full flex items-center gap-3 px-4 py-3 rounded-xl text-rose-400 hover:text-rose-300 hover:bg-rose-500/10 transition-all">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                    </svg>
                    <span>Logout</span>
                </button>
            </form>
        </div>
    </div>
</nav>
