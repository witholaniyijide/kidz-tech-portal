@props(['title' => 'Tutor Portal'])

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Kidz Tech') }} - {{ $title }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        [x-cloak] { display: none !important; }

        /* ========================================
           TUTOR PORTAL - MATURE BRAND THEME
           Primary: Deep Navy #1D2A6D
           Secondary: Royal Indigo #4B51FF
           Accent: Cyan #22D3EE
        ======================================== */

        /* Primary Gradient - Deep Navy to Royal Indigo */
        .bg-gradient-tutor {
            background: linear-gradient(135deg, #1D2A6D 0%, #4B51FF 100%);
        }

        /* Accent Gradient - Indigo to Cyan */
        .bg-gradient-tutor-accent {
            background: linear-gradient(135deg, #4B51FF 0%, #22D3EE 100%);
        }

        /* Header Gradient */
        .bg-gradient-tutor-header {
            background: linear-gradient(135deg, #1D2A6D 0%, #2D3A8C 50%, #4B51FF 100%);
        }

        /* Button Gradient */
        .btn-tutor-primary {
            background: linear-gradient(135deg, #1D2A6D 0%, #4B51FF 100%);
            color: white;
            transition: all 0.3s ease;
        }
        .btn-tutor-primary:hover {
            opacity: 0.9;
            transform: translateY(-1px);
            box-shadow: 0 10px 25px -5px rgba(75, 81, 255, 0.4);
        }

        /* Cyan Accent Button */
        .btn-tutor-accent {
            background: linear-gradient(135deg, #4B51FF 0%, #22D3EE 100%);
            color: white;
            transition: all 0.3s ease;
        }
        .btn-tutor-accent:hover {
            opacity: 0.9;
            transform: translateY(-1px);
            box-shadow: 0 10px 25px -5px rgba(34, 211, 238, 0.4);
        }

        /* Text Gradient */
        .text-gradient-tutor {
            background: linear-gradient(135deg, #4B51FF 0%, #22D3EE 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        /* Floating Animation */
        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-20px); }
        }
        .animate-float {
            animation: float 6s ease-in-out infinite;
        }

        /* Pulse Glow Animation */
        @keyframes pulse-glow {
            0%, 100% { box-shadow: 0 0 20px rgba(75, 81, 255, 0.3); }
            50% { box-shadow: 0 0 40px rgba(75, 81, 255, 0.5); }
        }
        .animate-pulse-glow {
            animation: pulse-glow 3s ease-in-out infinite;
        }

        /* Custom Scrollbar - Indigo Theme */
        ::-webkit-scrollbar {
            width: 8px;
            height: 8px;
        }
        ::-webkit-scrollbar-track {
            background: rgba(29, 42, 109, 0.1);
            border-radius: 10px;
        }
        ::-webkit-scrollbar-thumb {
            background: linear-gradient(180deg, #4B51FF 0%, #22D3EE 100%);
            border-radius: 10px;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: linear-gradient(180deg, #5B61FF 0%, #32E3FE 100%);
        }

        /* Glass Card - Subtle glassmorphism */
        .glass-card {
            background: rgba(255, 255, 255, 0.7);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border: 1px solid rgba(255, 255, 255, 0.3);
        }
        .dark .glass-card {
            background: rgba(30, 41, 59, 0.7);
            border: 1px solid rgba(71, 85, 105, 0.3);
        }

        /* Stat Card Hover */
        .stat-card {
            transition: all 0.3s ease;
        }
        .stat-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 20px 40px -15px rgba(75, 81, 255, 0.2);
        }

        /* Icon Container */
        .icon-container {
            background: linear-gradient(135deg, #1D2A6D 0%, #4B51FF 100%);
        }
        .icon-container-accent {
            background: linear-gradient(135deg, #4B51FF 0%, #22D3EE 100%);
        }

        /* Status Badge Colors */
        .badge-draft { background: #E2E8F0; color: #475569; }
        .badge-submitted { background: #DBEAFE; color: #1E40AF; }
        .badge-returned { background: #FEF3C7; color: #92400E; }
        .badge-approved { background: #D1FAE5; color: #065F46; }
        .badge-pending { background: #FEF3C7; color: #92400E; }
        .badge-late { background: #FEE2E2; color: #991B1B; }

        .dark .badge-draft { background: rgba(71, 85, 105, 0.3); color: #94A3B8; }
        .dark .badge-submitted { background: rgba(59, 130, 246, 0.2); color: #60A5FA; }
        .dark .badge-returned { background: rgba(245, 158, 11, 0.2); color: #FBBF24; }
        .dark .badge-approved { background: rgba(16, 185, 129, 0.2); color: #34D399; }
        .dark .badge-pending { background: rgba(245, 158, 11, 0.2); color: #FBBF24; }
        .dark .badge-late { background: rgba(239, 68, 68, 0.2); color: #F87171; }

        /* Form Input Focus */
        input:focus, select:focus, textarea:focus {
            border-color: #4B51FF !important;
            box-shadow: 0 0 0 3px rgba(75, 81, 255, 0.1) !important;
        }

        /* Checkbox/Radio Accent */
        input[type="checkbox"]:checked,
        input[type="radio"]:checked {
            background-color: #4B51FF;
            border-color: #4B51FF;
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

    @stack('styles')
</head>
<body class="h-full font-sans antialiased" x-data="{ sidebarOpen: false }">
    <div class="flex h-screen overflow-hidden bg-gradient-to-br from-slate-50 via-blue-50/30 to-indigo-50/50 dark:from-slate-900 dark:via-slate-800 dark:to-slate-900">

        <!-- Mobile Sidebar Backdrop -->
        <div 
            x-show="sidebarOpen" 
            x-transition:enter="transition-opacity ease-linear duration-300"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="transition-opacity ease-linear duration-300"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            @click="sidebarOpen = false"
            class="fixed inset-0 bg-slate-900/50 z-40 lg:hidden"
        ></div>

        <!-- Mobile Sidebar -->
        <aside 
            x-show="sidebarOpen"
            x-transition:enter="transition ease-in-out duration-300 transform"
            x-transition:enter-start="-translate-x-full"
            x-transition:enter-end="translate-x-0"
            x-transition:leave="transition ease-in-out duration-300 transform"
            x-transition:leave-start="translate-x-0"
            x-transition:leave-end="-translate-x-full"
            class="fixed inset-y-0 left-0 z-50 w-64 lg:hidden"
        >
            <x-tutor.nav />
        </aside>

        <!-- Desktop Sidebar Navigation -->
        <aside class="hidden lg:flex lg:flex-shrink-0 w-64">
            <x-tutor.nav />
        </aside>

        <!-- Main Content Area -->
        <div class="flex-1 flex flex-col overflow-hidden">

            <!-- Top Bar -->
            <x-tutor.top-bar :userName="auth()->user()->name ?? 'Tutor'" />

            <!-- Main Content -->
            <main class="flex-1 overflow-y-auto relative">
                <!-- Floating Orbs Background - Updated Colors -->
                <div class="absolute top-0 left-0 w-72 h-72 bg-indigo-200 dark:bg-indigo-900/50 rounded-full mix-blend-multiply dark:mix-blend-soft-light filter blur-xl opacity-60 animate-float pointer-events-none"></div>
                <div class="absolute top-0 right-0 w-72 h-72 bg-blue-200 dark:bg-blue-900/50 rounded-full mix-blend-multiply dark:mix-blend-soft-light filter blur-xl opacity-60 animate-float pointer-events-none" style="animation-delay: 2s;"></div>
                <div class="absolute -bottom-8 left-20 w-72 h-72 bg-cyan-200 dark:bg-cyan-900/50 rounded-full mix-blend-multiply dark:mix-blend-soft-light filter blur-xl opacity-60 animate-float pointer-events-none" style="animation-delay: 4s;"></div>

                <!-- Page Content -->
                <div class="relative z-10 p-6">
                    <!-- Flash Messages -->
                    @if(session('success'))
                        <div x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 5000)" class="mb-6 bg-emerald-100 dark:bg-emerald-900/30 border border-emerald-400 dark:border-emerald-700 text-emerald-700 dark:text-emerald-400 px-4 py-3 rounded-xl relative" role="alert">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    <span class="font-medium">{{ session('success') }}</span>
                                </div>
                                <button @click="show = false" class="text-emerald-700 dark:text-emerald-400 hover:text-emerald-900 dark:hover:text-emerald-300">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                    </svg>
                                </button>
                            </div>
                        </div>
                    @endif

                    @if(session('error'))
                        <div x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 5000)" class="mb-6 bg-rose-100 dark:bg-rose-900/30 border border-rose-400 dark:border-rose-700 text-rose-700 dark:text-rose-400 px-4 py-3 rounded-xl relative" role="alert">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    <span class="font-medium">{{ session('error') }}</span>
                                </div>
                                <button @click="show = false" class="text-rose-700 dark:text-rose-400 hover:text-rose-900 dark:hover:text-rose-300">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                    </svg>
                                </button>
                            </div>
                        </div>
                    @endif

                    @if(session('info'))
                        <div x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 5000)" class="mb-6 bg-blue-100 dark:bg-blue-900/30 border border-blue-400 dark:border-blue-700 text-blue-700 dark:text-blue-400 px-4 py-3 rounded-xl relative" role="alert">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    <span class="font-medium">{{ session('info') }}</span>
                                </div>
                                <button @click="show = false" class="text-blue-700 dark:text-blue-400 hover:text-blue-900 dark:hover:text-blue-300">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                    </svg>
                                </button>
                            </div>
                        </div>
                    @endif

                    @if(session('warning'))
                        <div x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 5000)" class="mb-6 bg-amber-100 dark:bg-amber-900/30 border border-amber-400 dark:border-amber-700 text-amber-700 dark:text-amber-400 px-4 py-3 rounded-xl relative" role="alert">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                                    </svg>
                                    <span class="font-medium">{{ session('warning') }}</span>
                                </div>
                                <button @click="show = false" class="text-amber-700 dark:text-amber-400 hover:text-amber-900 dark:hover:text-amber-300">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                    </svg>
                                </button>
                            </div>
                        </div>
                    @endif

                    {{ $slot }}
                </div>
            </main>
        </div>
    </div>

    @stack('scripts')
</body>
</html>
