<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Favicon -->
        <link rel="icon" type="image/png" href="{{ asset('images/favicon.png') }}"
              onerror="this.href='data:image/svg+xml,%3Csvg xmlns=%22http://www.w3.org/2000/svg%22 viewBox=%220 0 100 100%22%3E%3Crect fill=%22%234F46E5%22 width=%22100%22 height=%22100%22 rx=%2220%22/%3E%3Ctext x=%2250%22 y=%2265%22 font-size=%2250%22 text-anchor=%22middle%22 fill=%22white%22 font-family=%22Arial, sans-serif%22 font-weight=%22bold%22%3EK%3C/text%3E%3C/svg%3E'">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <!-- Dark Mode Initialization -->
        <script>
            if (localStorage.getItem('darkMode') === 'true') {
                document.documentElement.classList.add('dark');
            }
        </script>
    </head>
    <body class="font-sans text-gray-900 dark:text-gray-100 antialiased">
        <!-- Animated Background with Brand Colors -->
        <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 px-4 relative overflow-hidden">

            <!-- Gradient Background -->
            <div class="absolute inset-0 bg-gradient-to-br from-blue-50 via-purple-50 to-pink-50 dark:from-gray-900 dark:via-gray-900 dark:to-gray-900"></div>

            <!-- Floating Orbs with Brand Colors (More Visible) -->
            <div class="absolute top-0 left-0 w-96 h-96 bg-gradient-to-br from-red-400 to-red-500 rounded-full mix-blend-multiply filter blur-3xl opacity-40 dark:opacity-20 animate-float"></div>
            <div class="absolute top-0 right-0 w-96 h-96 bg-gradient-to-br from-blue-400 to-blue-500 rounded-full mix-blend-multiply filter blur-3xl opacity-40 dark:opacity-20 animate-float" style="animation-delay: 2s;"></div>
            <div class="absolute -bottom-8 left-1/4 w-96 h-96 bg-gradient-to-br from-green-400 to-green-500 rounded-full mix-blend-multiply filter blur-3xl opacity-40 dark:opacity-20 animate-float" style="animation-delay: 4s;"></div>
            <div class="absolute -bottom-8 right-1/4 w-96 h-96 bg-gradient-to-br from-yellow-400 to-yellow-500 rounded-full mix-blend-multiply filter blur-3xl opacity-40 dark:opacity-20 animate-float" style="animation-delay: 6s;"></div>

            <!-- Floating Coding Symbols with Glass Effect -->
            <!-- Code Brackets </> -->
            <div class="absolute top-20 left-[10%] glass-icon text-4xl text-blue-600 dark:text-blue-400 animate-float-slow" style="animation-delay: 0s;">
                &lt;/&gt;
            </div>

            <!-- Rocket -->
            <div class="absolute top-32 right-[15%] glass-icon text-5xl animate-float-slow" style="animation-delay: 1s;">
                🚀
            </div>

            <!-- Android Robot -->
            <div class="absolute bottom-32 left-[12%] glass-icon text-5xl animate-float-slow" style="animation-delay: 2s;">
                🤖
            </div>

            <!-- Laptop/Computer -->
            <div class="absolute top-1/2 right-[8%] glass-icon text-4xl animate-float-slow" style="animation-delay: 3s;">
                💻
            </div>

            <!-- Light Bulb (Ideas) -->
            <div class="absolute bottom-20 right-[20%] glass-icon text-4xl animate-float-slow" style="animation-delay: 4s;">
                💡
            </div>

            <!-- Code Symbol {} -->
            <div class="absolute top-1/3 left-[8%] glass-icon text-3xl text-purple-600 dark:text-purple-400 animate-float-slow" style="animation-delay: 5s;">
                &#123;&#125;
            </div>

            <!-- Gear/Settings -->
            <div class="absolute bottom-1/3 right-[10%] glass-icon text-4xl animate-float-slow" style="animation-delay: 6s;">
                ⚙️
            </div>

            <!-- Star (Achievement) -->
            <div class="absolute top-[60%] left-[15%] glass-icon text-3xl animate-float-slow" style="animation-delay: 7s;">
                ⭐
            </div>

            <!-- Content Container -->
            <div class="relative z-10 w-full max-w-md">
                <!-- Logo -->
                <div class="flex justify-center mb-6">
                    <a href="/">
                        <img src="{{ asset('images/logo_light.png') }}"
                             alt="Kidz Tech Logo"
                             class="h-24 w-auto dark:hidden drop-shadow-lg"
                             onerror="this.src='data:image/svg+xml,%3Csvg xmlns=%22http://www.w3.org/2000/svg%22 viewBox=%220 0 100 100%22%3E%3Crect fill=%22%234F46E5%22 width=%22100%22 height=%22100%22 rx=%2220%22/%3E%3Ctext x=%2250%22 y=%2265%22 font-size=%2250%22 text-anchor=%22middle%22 fill=%22white%22 font-family=%22Arial, sans-serif%22 font-weight=%22bold%22%3EK%3C/text%3E%3C/svg%3E'">
                        <img src="{{ asset('images/logo_dark.png') }}"
                             alt="Kidz Tech Logo"
                             class="h-24 w-auto hidden dark:block drop-shadow-lg"
                             onerror="this.src='data:image/svg+xml,%3Csvg xmlns=%22http://www.w3.org/2000/svg%22 viewBox=%220 0 100 100%22%3E%3Crect fill=%22%234F46E5%22 width=%22100%22 height=%22100%22 rx=%2220%22/%3E%3Ctext x=%2250%22 y=%2265%22 font-size=%2250%22 text-anchor=%22middle%22 fill=%22white%22 font-family=%22Arial, sans-serif%22 font-weight=%22bold%22%3EK%3C/text%3E%3C/svg%3E'">
                    </a>
                </div>

                <!-- Welcome Text -->
                <div class="text-center mb-8">
                    <h1 class="text-4xl md:text-5xl font-bold text-transparent bg-clip-text bg-gradient-to-r from-blue-600 via-purple-600 to-pink-600 dark:from-blue-400 dark:via-purple-400 dark:to-pink-400">
                        Welcome
                    </h1>
                </div>

                <!-- Glass Card for Form -->
                <div class="glass-card rounded-2xl shadow-2xl overflow-hidden backdrop-blur-xl bg-white/90 dark:bg-gray-800/90 border border-white/20 dark:border-gray-700/50">
                    <div class="px-6 sm:px-8 py-8 sm:py-10">
                        {{ $slot }}
                    </div>
                </div>

                <!-- Additional Links or Info (if needed) -->
                <div class="text-center mt-6">
                    <p class="text-sm text-gray-600 dark:text-gray-400">
                        Empowering young minds through technology
                    </p>
                </div>
            </div>
        </div>

        <style>
            @keyframes float {
                0%, 100% {
                    transform: translateY(0) translateX(0) rotate(0deg);
                }
                33% {
                    transform: translateY(-30px) translateX(30px) rotate(5deg);
                }
                66% {
                    transform: translateY(30px) translateX(-30px) rotate(-5deg);
                }
            }

            @keyframes float-slow {
                0%, 100% {
                    transform: translateY(0) translateX(0) rotate(0deg);
                }
                25% {
                    transform: translateY(-15px) translateX(15px) rotate(3deg);
                }
                50% {
                    transform: translateY(-25px) translateX(-10px) rotate(-3deg);
                }
                75% {
                    transform: translateY(10px) translateX(20px) rotate(2deg);
                }
            }

            .animate-float {
                animation: float 20s ease-in-out infinite;
            }

            .animate-float-slow {
                animation: float-slow 15s ease-in-out infinite;
            }

            .glass-card {
                backdrop-filter: blur(12px);
                -webkit-backdrop-filter: blur(12px);
            }

            .glass-icon {
                backdrop-filter: blur(8px);
                -webkit-backdrop-filter: blur(8px);
                background: rgba(255, 255, 255, 0.1);
                border: 1px solid rgba(255, 255, 255, 0.2);
                border-radius: 16px;
                padding: 12px 16px;
                box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
                transition: all 0.3s ease;
            }

            .dark .glass-icon {
                background: rgba(255, 255, 255, 0.05);
                border-color: rgba(255, 255, 255, 0.1);
            }

            .glass-icon:hover {
                transform: scale(1.1);
                box-shadow: 0 12px 40px rgba(0, 0, 0, 0.15);
            }
        </style>
    </body>
</html>
