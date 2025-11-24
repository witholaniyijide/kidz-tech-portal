<x-app-layout>
    {{-- Animated Background --}}
    <div class="min-h-screen bg-gradient-to-br from-teal-50 via-cyan-50 to-blue-50 dark:from-slate-900 dark:via-slate-800 dark:to-slate-900 py-12 relative overflow-hidden">
        {{-- Floating Orbs Background --}}
        <div class="absolute top-0 left-0 w-72 h-72 bg-teal-300 rounded-full mix-blend-multiply filter blur-xl opacity-70 animate-float"></div>
        <div class="absolute top-0 right-0 w-72 h-72 bg-cyan-300 rounded-full mix-blend-multiply filter blur-xl opacity-70 animate-float" style="animation-delay: 2s;"></div>
        <div class="absolute -bottom-8 left-20 w-72 h-72 bg-blue-300 rounded-full mix-blend-multiply filter blur-xl opacity-70 animate-float" style="animation-delay: 4s;"></div>

        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 relative z-10">

            {{-- Welcome Banner --}}
            <x-admin._banner :user="auth()->user()" />

            {{-- Main Statistics --}}
            <x-admin._stat-cards :stats="$stats" />

            {{-- Daily Class Schedule & To-Do List --}}
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
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
            <div class="mb-8">
                <x-admin._notice-preview :notices="$notices" />
            </div>

            {{-- Recent Students & Tutors Tables --}}
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
                <x-admin._recent-students-table :students="$students" />
                <x-admin._recent-tutors-table :tutors="$tutors" />
            </div>

            {{-- Quick Actions Grid --}}
            <x-admin._quick-actions-grid />

        </div>
    </div>

    {{-- Deferred Chart.js and Alpine.js --}}
    @push('scripts')
    <script defer src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.js"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    @endpush

    {{-- Inter Font & Styles --}}
    @push('styles')
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        .font-inter {
            font-family: 'Inter', sans-serif;
        }

        /* Glassmorphism Card */
        .glass-card {
            background: rgba(255, 255, 255, 0.7);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .dark .glass-card {
            background: rgba(30, 41, 59, 0.7);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        /* Hover Lift Effect */
        .hover-lift {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .hover-lift:hover {
            transform: translateY(-4px);
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        }

        /* Icon Bounce Animation */
        .icon-bounce {
            animation: icon-bounce 2s ease-in-out infinite;
        }

        @keyframes icon-bounce {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-5px); }
        }

        /* Floating Animation */
        .animate-float {
            animation: float 20s ease-in-out infinite;
        }

        @keyframes float {
            0%, 100% {
                transform: translate(0, 0) rotate(0deg);
            }
            33% {
                transform: translate(30px, -30px) rotate(120deg);
            }
            66% {
                transform: translate(-20px, 20px) rotate(240deg);
            }
        }

        /* Slide In Animation */
        .animate-slide-in {
            animation: slide-in 0.5s ease-out;
        }

        @keyframes slide-in {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
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
