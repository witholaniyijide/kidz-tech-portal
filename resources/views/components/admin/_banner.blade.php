@props(['user' => null])

<div class="relative overflow-hidden rounded-3xl bg-gradient-to-r from-teal-500 via-cyan-500 to-teal-400 p-8 shadow-2xl">
    <div class="absolute inset-0 bg-white/10 backdrop-blur-sm"></div>
    <div class="relative z-10">
        <h1 class="text-4xl font-bold text-white mb-2 font-inter">
            Welcome back, {{ $user ? $user->name : auth()->user()->name }}! 🛠️
        </h1>
        <p class="text-xl text-white/90 mb-3 font-inter">Student & Tutor Coordination Hub</p>
        <p class="text-white/80 text-sm font-inter">
            {{ now()->format('l, F j, Y') }}
        </p>
    </div>
    <div class="absolute top-0 right-0 -mt-4 -mr-4 h-32 w-32 rounded-full bg-white/10 blur-2xl"></div>
    <div class="absolute bottom-0 left-0 -mb-4 -ml-4 h-32 w-32 rounded-full bg-cyan-300/20 blur-2xl"></div>
</div>
