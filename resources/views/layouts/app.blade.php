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
            // Initialize dark mode before page renders to prevent flash
            if (localStorage.getItem('darkMode') === 'true') {
                document.documentElement.classList.add('dark');
            }
        </script>
    </head>
    <body class="min-h-screen bg-gray-100 dark:bg-gray-900 font-sans antialiased">
        <!-- Flash Messages -->
        <x-ui.flash-messages />

        <div class="min-h-screen flex flex-col">
            @auth
                @if(Auth::user()->hasRole('admin'))
                    <x-admin.navigation />
                @else
                    @include('layouts.navigation')
                @endif
            @else
                @include('layouts.navigation')
            @endauth

            <!-- Page Heading -->
            @if (isset($header) || isset($title))
                <header class="w-full h-40 {{ themeGradient() }} text-white rounded-b-3xl shadow-xl p-10">
                    <h1 class="text-3xl font-bold">
                        {{ $title ?? ($header ?? 'Dashboard') }}
                    </h1>
                    <p class="text-white/80 mt-2">{{ now()->format('l, F j, Y') }}</p>
                </header>
            @endif

            <!-- Page Content -->
            <main class="flex-1">
                {{ $slot }}
            </main>

            @include('layouts.footer')
        </div>

        {{-- Stack for scripts and styles --}}
        @stack('styles')
        @stack('scripts')
    </body>
</html>
