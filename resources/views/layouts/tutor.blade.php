<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Kidz Tech') }} - Tutor Portal</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800,900&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        [x-cloak] { display: none !important; }

        /* Custom Tutor Gradient */
        .bg-gradient-tutor {
            background: linear-gradient(to right, #8B5CF6, #EC4899);
        }

        /* Floating Animation */
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

        /* Custom Scrollbar */
        ::-webkit-scrollbar {
            width: 8px;
            height: 8px;
        }

        ::-webkit-scrollbar-track {
            background: rgba(255, 255, 255, 0.1);
            border-radius: 10px;
        }

        ::-webkit-scrollbar-thumb {
            background: rgba(139, 92, 246, 0.5);
            border-radius: 10px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: rgba(139, 92, 246, 0.7);
        }

        /* Dark mode check on load */
        @media (prefers-color-scheme: dark) {
            html:not(.light) {
                color-scheme: dark;
            }
        }
    </style>

    <script>
        // Dark mode initialization
        if (localStorage.getItem('theme') === 'dark' || (!localStorage.getItem('theme') && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
    </script>
</head>
<body class="h-full font-sans antialiased">
    <div class="flex h-screen overflow-hidden bg-gradient-to-br from-purple-50 via-pink-50 to-rose-50 dark:from-gray-900 dark:via-gray-800 dark:to-gray-900">

        <!-- Sidebar Navigation -->
        <aside class="hidden lg:flex lg:flex-shrink-0 w-64">
            <x-tutor.nav />
        </aside>

        <!-- Main Content Area -->
        <div class="flex-1 flex flex-col overflow-hidden">

            <!-- Top Bar -->
            <x-tutor.top-bar :userName="auth()->user()->name ?? 'Tutor'" />

            <!-- Main Content -->
            <main class="flex-1 overflow-y-auto relative">
                <!-- Floating Orbs Background -->
                <div class="absolute top-0 left-0 w-72 h-72 bg-purple-300 dark:bg-purple-900 rounded-full mix-blend-multiply filter blur-xl opacity-70 animate-float pointer-events-none"></div>
                <div class="absolute top-0 right-0 w-72 h-72 bg-pink-300 dark:bg-pink-900 rounded-full mix-blend-multiply filter blur-xl opacity-70 animate-float pointer-events-none" style="animation-delay: 2s;"></div>
                <div class="absolute -bottom-8 left-20 w-72 h-72 bg-rose-300 dark:bg-rose-900 rounded-full mix-blend-multiply filter blur-xl opacity-70 animate-float pointer-events-none" style="animation-delay: 4s;"></div>

                <!-- Page Content -->
                <div class="relative z-10 p-6">
                    {{ $slot }}
                </div>
            </main>
        </div>
    </div>

    <!-- Mobile Menu Toggle (Optional) -->
    <script>
        // Add mobile menu functionality here if needed
    </script>
</body>
</html>
