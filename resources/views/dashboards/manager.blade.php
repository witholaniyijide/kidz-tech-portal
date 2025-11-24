<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-2xl text-white">
            {{ __('Manager Dashboard') }}
        </h2>
    </x-slot>

    <x-slot name="title">{{ __('Manager Dashboard') }}</x-slot>

    {{-- Animated Background --}}
    <div class="min-h-screen bg-gradient-to-br from-sky-50 via-blue-50 to-cyan-50 dark:from-slate-900 dark:via-slate-800 dark:to-slate-900 py-12 relative overflow-hidden">
        {{-- Floating Orbs Background --}}
        <div class="absolute top-0 left-0 w-72 h-72 bg-sky-300 dark:bg-sky-900 rounded-full mix-blend-multiply filter blur-xl opacity-70 animate-float"></div>
        <div class="absolute top-0 right-0 w-72 h-72 bg-blue-300 dark:bg-blue-900 rounded-full mix-blend-multiply filter blur-xl opacity-70 animate-float" style="animation-delay: 2s;"></div>
        <div class="absolute -bottom-8 left-20 w-72 h-72 bg-cyan-300 dark:bg-cyan-900 rounded-full mix-blend-multiply filter blur-xl opacity-70 animate-float" style="animation-delay: 4s;"></div>

        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 relative z-10">

            {{-- Welcome Banner --}}
            <x-ui.glass-card padding="p-8" class="mb-16">
                <div class="flex items-center justify-between flex-wrap">
                    <div>
                        <h3 class="text-3xl font-bold text-gray-900 dark:text-white mb-2">
                            Welcome back, <span class="text-transparent bg-clip-text bg-gradient-manager">{{ auth()->user()->name ?? 'Manager' }}</span>!
                        </h3>
                        <p class="text-gray-600 dark:text-gray-300 text-lg">Here's what's happening with your organization today.</p>
                    </div>
                    <div class="text-right mt-4 md:mt-0">
                        <div class="text-gray-600 dark:text-gray-300">{{ now()->format('l, F j, Y') }}</div>
                        <div class="text-gray-500 dark:text-gray-400 text-sm">{{ now()->format('g:i A') }}</div>
                    </div>
                </div>
            </x-ui.glass-card>

            {{-- Main Statistics --}}
            @include('components.manager._stat-cards', [
                'studentCount' => $stats['totalStudents'] ?? 0,
                'tutorCount' => $stats['totalTutors'] ?? 0,
                'todayClassesCount' => $stats['todayClasses'] ?? 0,
                'pendingAssessmentsCount' => $stats['pendingAssessments'] ?? 0
            ])

            {{-- Schedule View & To-Do List --}}
            <div class="grid grid-cols-1 lg:grid-cols-5 gap-6 mb-12">
                {{-- Schedule View (60% / 3 columns) --}}
                <div class="lg:col-span-3">
                    @include('components.manager._schedule-view-card', [
                        'schedule' => $todaySchedule ?? []
                    ])
                </div>

                {{-- To-Do List (40% / 2 columns) --}}
                <div class="lg:col-span-2">
                    @include('components.manager._todo-card', [
                        'defaultTasks' => [
                            'Join today\'s classes',
                            'Assess tutor performance',
                            'Create assessment reports',
                            'Follow-up with inactive students'
                        ]
                    ])
                </div>
            </div>

            {{-- Notice Board Preview --}}
            <div class="mb-12">
                @include('components.manager._notice-board-preview', [
                    'notices' => $notices ?? []
                ])
            </div>

            {{-- Recent Students & Tutors --}}
            <div class="grid grid-cols-1 xl:grid-cols-2 gap-6 mb-12">
                {{-- Recent Students --}}
                <div>
                    @include('components.manager._recent-students', [
                        'students' => $recentStudents ?? []
                    ])
                </div>

                {{-- Recent Tutors --}}
                <div>
                    @include('components.manager._recent-tutors', [
                        'tutors' => $recentTutors ?? []
                    ])
                </div>
            </div>

            {{-- Quick Actions --}}
            @include('components.manager._quick-actions', [
                'pendingAssessmentsCount' => $stats['pendingAssessments'] ?? 0
            ])

            {{-- Footer Note & CTA Links --}}
            <div class="mt-12">
                <x-ui.glass-card padding="p-6">
                    <div class="text-center">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">
                            Need Help Managing Your Team?
                        </h3>
                        <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">
                            Access our comprehensive guides and resources to help you manage classes, tutors, and students effectively.
                        </p>
                        <div class="flex flex-wrap justify-center gap-4">
                            <x-ui.gradient-button
                                href="{{ route('help.index') }}"
                                gradient="bg-gradient-manager"
                                aria-label="View Help Center"
                            >
                                <svg class="w-5 h-5 mr-2 inline-block" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                Help Center
                            </x-ui.gradient-button>

                            <x-ui.gradient-button
                                href="{{ route('reports.index') }}"
                                gradient="bg-gradient-to-r from-sky-600 to-blue-600"
                                aria-label="View All Reports"
                            >
                                <svg class="w-5 h-5 mr-2 inline-block" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                                </svg>
                                View All Reports
                            </x-ui.gradient-button>

                            <x-ui.gradient-button
                                href="{{ route('settings.index') }}"
                                gradient="bg-gradient-to-r from-blue-600 to-cyan-600"
                                aria-label="Dashboard Settings"
                            >
                                <svg class="w-5 h-5 mr-2 inline-block" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                                Settings
                            </x-ui.gradient-button>
                        </div>
                    </div>
                </x-ui.glass-card>
            </div>

            {{-- Footer Info --}}
            <div class="mt-8 text-center text-sm text-gray-500 dark:text-gray-400">
                <p>Last updated: {{ now()->format('F j, Y g:i A') }}</p>
                <p class="mt-1">Kidz Tech Portal &copy; {{ date('Y') }} - Manager Dashboard</p>
            </div>

        </div>
    </div>

    {{-- Custom Animations --}}
    <style>
        @keyframes float {
            0%, 100% {
                transform: translateY(0px);
            }
            50% {
                transform: translateY(-20px);
            }
        }

        .animate-float {
            animation: float 6s ease-in-out infinite;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .animate-fadeIn {
            animation: fadeIn 0.6s ease-out;
        }

        /* Focus visible styles for accessibility */
        *:focus-visible {
            outline: 2px solid #0ea5e9;
            outline-offset: 2px;
            border-radius: 0.375rem;
        }
    </style>
</x-app-layout>
