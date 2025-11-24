@props([
    'studentCount' => 0,
    'tutorCount' => 0,
    'todayClassesCount' => 0,
    'pendingAssessmentsCount' => 0
])

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-12" role="region" aria-label="Statistics Overview">
    {{-- Total Students --}}
    <x-ui.stat-card
        title="Total Students"
        :value="$studentCount"
        icon='<svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
        </svg>'
        gradient="bg-gradient-manager"
        role="article"
        aria-label="Total Students Statistics"
    />

    {{-- Total Tutors --}}
    <x-ui.stat-card
        title="Total Tutors"
        :value="$tutorCount"
        icon='<svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
        </svg>'
        gradient="bg-gradient-manager"
        role="article"
        aria-label="Total Tutors Statistics"
    />

    {{-- Today's Classes --}}
    <x-ui.stat-card
        title="Today's Classes"
        :value="$todayClassesCount"
        icon='<svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
        </svg>'
        gradient="bg-gradient-manager"
        role="article"
        aria-label="Today's Classes Statistics"
    />

    {{-- Pending Assessments --}}
    <x-ui.stat-card
        title="Pending Assessments"
        :value="$pendingAssessmentsCount"
        icon='<svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
        </svg>'
        gradient="bg-gradient-manager"
        role="article"
        aria-label="Pending Assessments Statistics"
    />
</div>
