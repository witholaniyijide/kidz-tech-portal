<x-parent-layout>
    <x-slot name="title">My Children</x-slot>
    <x-slot name="subtitle">View and manage all your children's profiles</x-slot>

    <div class="space-y-6">
        @if($children->count() > 0)
            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($children as $child)
                    <a href="{{ route('parent.children.show', $child) }}"
                       class="glass-card rounded-2xl p-6 hover-lift block group">
                        <!-- Child Header -->
                        <div class="flex items-center space-x-4 mb-4">
                            <div class="w-16 h-16 rounded-2xl bg-parent-gradient flex items-center justify-center text-white shadow-lg overflow-hidden">
                                @if($child->profile_photo)
                                    <img src="{{ asset('storage/' . $child->profile_photo) }}"
                                         alt="{{ $child->full_name }}"
                                         class="w-full h-full object-cover">
                                @else
                                    <span class="text-2xl font-heading font-bold">{{ substr($child->first_name, 0, 1) }}</span>
                                @endif
                            </div>
                            <div class="flex-1">
                                <h3 class="text-lg font-heading font-bold text-gray-800 dark:text-white group-hover:text-sky-600 dark:group-hover:text-sky-400 transition-colors">
                                    {{ $child->full_name }}
                                </h3>
                                <p class="text-sm text-gray-500 dark:text-gray-400">
                                    @if($child->tutor)
                                        Tutor: {{ $child->tutor->first_name }} {{ $child->tutor->last_name }}
                                    @else
                                        No tutor assigned
                                    @endif
                                </p>
                            </div>
                        </div>

                        <!-- Class Schedule -->
                        <div class="bg-sky-50 dark:bg-sky-900/30 rounded-xl p-3 mb-4">
                            <h4 class="text-xs font-semibold text-sky-700 dark:text-sky-400 mb-1">Class Schedule</h4>
                            @if($child->class_schedule && is_array($child->class_schedule) && count($child->class_schedule) > 0)
                                <div class="space-y-0.5">
                                    @foreach(array_slice($child->class_schedule, 0, 2) as $schedule)
                                        <p class="text-xs text-gray-600 dark:text-gray-300">
                                            @if(is_array($schedule))
                                                {{ $schedule['day'] ?? '' }} {{ $schedule['time'] ?? '' }}
                                            @else
                                                {{ $schedule }}
                                            @endif
                                        </p>
                                    @endforeach
                                </div>
                            @else
                                <p class="text-xs text-gray-500 dark:text-gray-400">No schedule set</p>
                            @endif
                        </div>

                        <!-- Progress Bar -->
                        <div class="mb-4">
                            <div class="flex items-center justify-between mb-1">
                                <span class="text-sm font-medium text-gray-600 dark:text-gray-400">Progress</span>
                                <span class="text-sm font-bold text-sky-600 dark:text-sky-400">{{ $child->progress_percentage }}%</span>
                            </div>
                            <div class="h-2 bg-gray-200 dark:bg-gray-700 rounded-full overflow-hidden">
                                <div class="h-full bg-parent-gradient rounded-full transition-all duration-500"
                                     style="width: {{ $child->progress_percentage }}%"></div>
                            </div>
                        </div>

                        <!-- View Profile Button -->
                        <div class="flex items-center justify-between pt-4 border-t border-gray-200 dark:border-gray-700">
                            <span class="text-sm text-gray-500 dark:text-gray-400">
                                Stage {{ $child->roadmap_stage ?? 1 }} of 12
                            </span>
                            <span class="flex items-center text-sm font-medium text-sky-600 dark:text-sky-400 group-hover:translate-x-1 transition-transform">
                                View Profile
                                <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                </svg>
                            </span>
                        </div>
                    </a>
                @endforeach
            </div>
        @else
            <div class="glass-card rounded-2xl p-12 text-center">
                <svg class="w-16 h-16 mx-auto text-gray-300 dark:text-gray-600 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                </svg>
                <h3 class="text-xl font-heading font-bold text-gray-800 dark:text-white mb-2">No Children Linked</h3>
                <p class="text-gray-500 dark:text-gray-400 mb-4">
                    You don't have any children linked to your account yet.
                </p>
                <p class="text-sm text-gray-400 dark:text-gray-500">
                    Please contact the administrator to link your children to your account.
                </p>
            </div>
        @endif
    </div>
</x-parent-layout>
