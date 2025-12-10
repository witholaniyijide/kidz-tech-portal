<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ $title ?? 'Parent Portal' }} - {{ config('app.name', 'Kidz Tech Portal') }}</title>

        <!-- Favicon -->
        <link rel="icon" type="image/png" href="{{ asset('images/favicon.png') }}">

        <!-- Fonts: Plus Jakarta Sans & Inter -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <!-- Parent Portal Theme Styles -->
        <style>
            :root {
                --parent-primary-start: #0ea5e9; /* Sky Blue 500 */
                --parent-primary-end: #22d3ee; /* Cyan 400 */
                --parent-navy: #0f172a;
                --parent-slate: #64748b;
                --parent-emerald: #10b981;
                --parent-amber: #f59e0b;
                --parent-rose: #f43f5e;
            }

            .font-heading {
                font-family: 'Plus Jakarta Sans', system-ui, sans-serif;
            }

            .font-body {
                font-family: 'Inter', system-ui, sans-serif;
            }

            .bg-parent-gradient {
                background: linear-gradient(135deg, var(--parent-primary-start) 0%, var(--parent-primary-end) 100%);
            }

            .text-parent-gradient {
                background: linear-gradient(135deg, var(--parent-primary-start) 0%, var(--parent-primary-end) 100%);
                -webkit-background-clip: text;
                -webkit-text-fill-color: transparent;
                background-clip: text;
            }

            .glass-card {
                background: rgba(255, 255, 255, 0.4);
                backdrop-filter: blur(20px);
                -webkit-backdrop-filter: blur(20px);
                border: 1px solid rgba(255, 255, 255, 0.2);
            }

            .dark .glass-card {
                background: rgba(31, 41, 55, 0.6);
                border: 1px solid rgba(75, 85, 99, 0.3);
            }

            .hover-lift {
                transition: transform 0.3s ease, box-shadow 0.3s ease;
            }

            .hover-lift:hover {
                transform: translateY(-4px);
                box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
            }

            /* Custom scrollbar for parent portal */
            .parent-scrollbar::-webkit-scrollbar {
                width: 6px;
            }

            .parent-scrollbar::-webkit-scrollbar-track {
                background: transparent;
            }

            .parent-scrollbar::-webkit-scrollbar-thumb {
                background: var(--parent-primary-start);
                border-radius: 3px;
            }

            /* Pulse animation for notifications */
            @keyframes notification-pulse {
                0%, 100% { transform: scale(1); }
                50% { transform: scale(1.1); }
            }

            .notification-badge {
                animation: notification-pulse 2s infinite;
            }
        </style>

        <!-- Dark Mode Initialization -->
        <script>
            if (localStorage.getItem('darkMode') === 'true') {
                document.documentElement.classList.add('dark');
            }
        </script>
    </head>
    <body class="min-h-screen bg-gray-50 dark:bg-gray-900 font-body antialiased">
        <!-- Flash Messages -->
        <x-ui.flash-messages />

        <div class="min-h-screen flex flex-col">
            <!-- Parent Navigation -->
            @include('layouts.parent-navigation')

            <!-- Page Header -->
            @if (isset($header) || isset($title))
                <header class="w-full bg-parent-gradient text-white shadow-xl">
                    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
                        <h1 class="text-3xl font-heading font-bold">
                            {{ $title ?? $header ?? 'Dashboard' }}
                        </h1>
                        @if(isset($subtitle))
                            <p class="text-white/80 mt-2">{{ $subtitle }}</p>
                        @else
                            <p class="text-white/80 mt-2">{{ now()->format('l, F j, Y') }}</p>
                        @endif
                    </div>
                </header>
            @endif

            <!-- Page Content -->
            <main class="flex-1 py-6">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    {{ $slot }}
                </div>
            </main>

            <!-- Footer -->
            <footer class="bg-white dark:bg-gray-800 border-t border-gray-200 dark:border-gray-700 py-4">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <p class="text-center text-sm text-gray-500 dark:text-gray-400">
                        &copy; {{ date('Y') }} {{ config('app.name', 'Kidz Tech Coding Club') }}. All rights reserved.
                    </p>
                </div>
            </footer>
        </div>

        <!-- Stack for scripts and styles -->
        @stack('styles')
        @stack('scripts')
    </body>
</html>
