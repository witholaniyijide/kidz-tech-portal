@props([
    'stats' => []
])

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8" role="region" aria-label="Statistics Overview">
    {{-- Total Students --}}
    <x-ui.stat-card
        title="Total Students"
        :number="$stats['totalStudents'] ?? '0'"
        :subtitle="($stats['activeStudents'] ?? 0) . ' active • ' . (($stats['totalStudents'] ?? 0) - ($stats['activeStudents'] ?? 0)) . ' inactive'"
        gradient="from-[#14B8A6] to-[#06B6D4]"
        textGradient="from-[#0D9488] to-[#0891B2]"
        role="article"
        aria-label="Total Students Statistics"
        style="animation-delay: 0.1s;"
    >
        <x-slot:icon>
            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
            </svg>
        </x-slot:icon>
    </x-ui.stat-card>

    {{-- Total Tutors --}}
    <x-ui.stat-card
        title="Total Tutors"
        :number="$stats['totalTutors'] ?? '0'"
        :subtitle="($stats['activeTutors'] ?? 0) . ' active • ' . ($stats['inactiveTutors'] ?? 0) . ' inactive • ' . ($stats['onLeaveTutors'] ?? 0) . ' on leave'"
        gradient="from-[#06B6D4] to-[#0EA5E9]"
        textGradient="from-[#0891B2] to-[#0284C7]"
        role="article"
        aria-label="Total Tutors Statistics"
        style="animation-delay: 0.2s;"
    >
        <x-slot:icon>
            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
            </svg>
        </x-slot:icon>
    </x-ui.stat-card>

    {{-- Today's Classes --}}
    <x-ui.stat-card
        title="Today's Classes"
        :number="$stats['todayClasses'] ?? '0'"
        :subtitle="($stats['completedClasses'] ?? 0) . ' completed • ' . ($stats['upcomingClasses'] ?? 0) . ' upcoming'"
        gradient="from-[#10B981] to-[#059669]"
        textGradient="from-[#059669] to-[#047857]"
        role="article"
        aria-label="Today's Classes Statistics"
        style="animation-delay: 0.3s;"
    >
        <x-slot:icon>
            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
            </svg>
        </x-slot:icon>
    </x-ui.stat-card>

    {{-- Pending Attendance --}}
    <x-ui.stat-card
        title="Pending Attendance"
        :number="$stats['pendingAttendance'] ?? '0'"
        subtitle="Awaiting approval"
        gradient="from-[#F59E0B] to-[#EF4444]"
        textGradient="from-[#D97706] to-[#DC2626]"
        role="article"
        aria-label="Pending Attendance Statistics"
        style="animation-delay: 0.4s;"
    >
        <x-slot:icon>
            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
            </svg>
        </x-slot:icon>
    </x-ui.stat-card>
</div>
