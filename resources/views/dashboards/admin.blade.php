<x-app-layout>
    <div class="min-h-screen bg-gradient-to-br from-slate-50 via-cyan-50 to-teal-50 dark:from-slate-900 dark:via-slate-800 dark:to-slate-900 py-8 px-4 sm:px-6 lg:px-8">
        <div class="max-w-7xl mx-auto space-y-8">

            {{-- Welcome Banner --}}
            <x-admin._banner :user="auth()->user()" />

            {{-- Main Statistics --}}
            <x-admin._stat-cards :stats="$stats" />

            {{-- Daily Class Schedule & To-Do List --}}
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                {{-- Daily Class Schedule (60% - 2 columns) --}}
                <div class="lg:col-span-2">
                    <x-admin._schedule-card :classes="$classes" />
                </div>

                {{-- To-Do List (40% - 1 column) --}}
                <div class="lg:col-span-1">
                    <x-admin._todo-card :todos="$todos" />
                </div>
            </div>

            {{-- Notice Board Preview --}}
            <x-admin._notice-preview :notices="$notices" />

            {{-- Recent Students Table --}}
            <x-admin._recent-students-table :students="$students" />

            {{-- Recent Tutors Table --}}
            <x-admin._recent-tutors-table :tutors="$tutors" />

            {{-- Quick Actions Grid --}}
            <x-admin._quick-actions-grid />

        </div>
    </div>

    {{-- Deferred Chart.js and Alpine.js --}}
    @push('scripts')
    <script defer src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.js"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    @endpush

    {{-- Inter Font --}}
    @push('styles')
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        .font-inter {
            font-family: 'Inter', sans-serif;
        }

        /* Smooth transitions */
        * {
            transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
        }

        /* Focus visible styles for accessibility */
        .focus-visible\:ring-2:focus-visible {
            outline: 2px solid transparent;
            outline-offset: 2px;
        }

        /* Image lazy loading blur effect */
        img[loading="lazy"] {
            filter: blur(10px);
            transition: filter 0.3s;
        }

        img[loading="lazy"].loaded {
            filter: blur(0);
        }
    </style>
    @endpush

    {{-- Lazy loading images script --}}
    @push('scripts')
    <script defer>
        document.addEventListener('DOMContentLoaded', function() {
            const images = document.querySelectorAll('img[loading="lazy"]');
            images.forEach(img => {
                if (img.complete) {
                    img.classList.add('loaded');
                } else {
                    img.addEventListener('load', function() {
                        img.classList.add('loaded');
                    });
                }
            });
        });
    </script>
    @endpush
</x-app-layout>
