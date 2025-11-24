@props(['user' => null])

<div class="mb-8 glass-card rounded-2xl p-8 shadow-xl animate-slide-in">
    <div class="flex items-center justify-between">
        <div>
            <h3 class="text-3xl font-bold text-gray-900 dark:text-white mb-2">
                Welcome back, <span class="text-transparent bg-clip-text bg-gradient-to-r from-[#14B8A6] to-[#06B6D4]">{{ $user ? $user->name : auth()->user()->name }}</span>!
            </h3>
            <p class="text-gray-600 dark:text-gray-400 text-lg">Here's what's happening with your organization today.</p>
        </div>
        <div class="hidden md:block">
            <div class="text-right">
                <div class="text-sm text-gray-600 dark:text-gray-400">{{ now()->timezone('Africa/Lagos')->format('l, F j, Y') }}</div>
                <div class="text-xs text-gray-500">{{ now()->timezone('Africa/Lagos')->format('g:i A') }}</div>
            </div>
        </div>
    </div>
</div>
