<x-tutor-layout>
    <!-- Page Header -->
    <div class="mb-8">
        <h1 class="text-4xl font-bold text-gray-900 dark:text-white mb-2">
            My Availability
        </h1>
        <p class="text-gray-600 dark:text-gray-400">
            Manage your weekly availability schedule
        </p>
    </div>

    <!-- Add Availability Form -->
    <div class="mb-8 bg-white/20 dark:bg-gray-900/30 backdrop-blur-xl border border-white/10 rounded-2xl p-6 shadow-lg">
        <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-4">Add Availability</h2>
        <form action="{{ route('tutor.availability.store') }}" method="POST" class="grid grid-cols-1 md:grid-cols-6 gap-4">
            @csrf

            <div>
                <label for="day" class="block text-sm font-medium text-gray-900 dark:text-white mb-2">Day</label>
                <select id="day" name="day" required
                    class="w-full px-4 py-3 bg-white/50 dark:bg-gray-800/50 border border-gray-300 dark:border-gray-600 rounded-xl text-gray-900 dark:text-white focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                    <option value="">Select</option>
                    @foreach($daysOfWeek as $day)
                        <option value="{{ $day }}">{{ $day }}</option>
                    @endforeach
                </select>
                @error('day')
                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="start_time" class="block text-sm font-medium text-gray-900 dark:text-white mb-2">Start Time</label>
                <input type="time" id="start_time" name="start_time" required
                    class="w-full px-4 py-3 bg-white/50 dark:bg-gray-800/50 border border-gray-300 dark:border-gray-600 rounded-xl text-gray-900 dark:text-white focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                @error('start_time')
                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="end_time" class="block text-sm font-medium text-gray-900 dark:text-white mb-2">End Time</label>
                <input type="time" id="end_time" name="end_time" required
                    class="w-full px-4 py-3 bg-white/50 dark:bg-gray-800/50 border border-gray-300 dark:border-gray-600 rounded-xl text-gray-900 dark:text-white focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                @error('end_time')
                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                @enderror
            </div>

            <div class="md:col-span-2">
                <label for="notes" class="block text-sm font-medium text-gray-900 dark:text-white mb-2">Notes (Optional)</label>
                <input type="text" id="notes" name="notes" maxlength="255"
                    class="w-full px-4 py-3 bg-white/50 dark:bg-gray-800/50 border border-gray-300 dark:border-gray-600 rounded-xl text-gray-900 dark:text-white focus:ring-2 focus:ring-purple-500 focus:border-transparent"
                    placeholder="e.g., Preferred, After school">
                @error('notes')
                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex items-end">
                <button type="submit"
                    class="w-full px-6 py-3 bg-gradient-to-r from-purple-500 to-pink-500 text-white rounded-xl hover:shadow-lg hover:-translate-y-0.5 transition-all font-medium">
                    Add
                </button>
            </div>
        </form>
    </div>

    <!-- Availability List -->
    @if($availabilities->isEmpty())
        <div class="bg-white/20 dark:bg-gray-900/30 backdrop-blur-xl border border-white/10 rounded-2xl p-12 text-center shadow-lg">
            <svg class="w-16 h-16 mx-auto text-gray-400 dark:text-gray-600 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
            </svg>
            <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-2">No Availability Set</h3>
            <p class="text-gray-600 dark:text-gray-400">Add your first availability slot using the form above</p>
        </div>
    @else
        <div class="bg-white/20 dark:bg-gray-900/30 backdrop-blur-xl border border-white/10 rounded-2xl p-6 shadow-lg">
            <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-4">Your Weekly Schedule</h2>
            <div class="space-y-3">
                @foreach($availabilities as $availability)
                    <div class="bg-white/10 dark:bg-gray-800/30 rounded-xl p-4 border border-white/5 flex items-center justify-between">
                        <div class="flex-1 grid grid-cols-1 md:grid-cols-4 gap-4">
                            <div>
                                <label class="block text-xs font-medium text-gray-600 dark:text-gray-400 mb-1">Day</label>
                                <p class="font-medium text-gray-900 dark:text-white">{{ $availability->day }}</p>
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-gray-600 dark:text-gray-400 mb-1">Time</label>
                                <p class="text-gray-900 dark:text-white">
                                    {{ \Carbon\Carbon::parse($availability->start_time)->format('g:i A') }} - {{ \Carbon\Carbon::parse($availability->end_time)->format('g:i A') }}
                                </p>
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-gray-600 dark:text-gray-400 mb-1">Status</label>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                    @if($availability->is_active) bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400
                                    @else bg-gray-100 text-gray-800 dark:bg-gray-900/30 dark:text-gray-400
                                    @endif">
                                    {{ $availability->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-gray-600 dark:text-gray-400 mb-1">Notes</label>
                                <p class="text-sm text-gray-700 dark:text-gray-300">{{ $availability->notes ?? '-' }}</p>
                            </div>
                        </div>

                        <!-- Actions -->
                        <div class="flex items-center space-x-2 ml-4">
                            <!-- Toggle Active -->
                            <form action="{{ route('tutor.availability.update', $availability) }}" method="POST" class="inline-block">
                                @csrf
                                @method('PUT')
                                <input type="hidden" name="day" value="{{ $availability->day }}">
                                <input type="hidden" name="start_time" value="{{ \Carbon\Carbon::parse($availability->start_time)->format('H:i') }}">
                                <input type="hidden" name="end_time" value="{{ \Carbon\Carbon::parse($availability->end_time)->format('H:i') }}">
                                <input type="hidden" name="notes" value="{{ $availability->notes }}">
                                <input type="hidden" name="is_active" value="{{ $availability->is_active ? 0 : 1 }}">
                                <button type="submit"
                                    class="p-2 {{ $availability->is_active ? 'text-yellow-600 dark:text-yellow-400' : 'text-green-600 dark:text-green-400' }} hover:bg-gray-100 dark:hover:bg-gray-800 rounded-lg transition-colors"
                                    title="{{ $availability->is_active ? 'Deactivate' : 'Activate' }}">
                                    @if($availability->is_active)
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 9v6m4-6v6m7-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                    @else
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                    @endif
                                </button>
                            </form>

                            <!-- Delete -->
                            <form action="{{ route('tutor.availability.destroy', $availability) }}" method="POST" class="inline-block"
                                onsubmit="return confirm('Are you sure you want to delete this availability?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                    class="p-2 text-red-600 dark:text-red-400 hover:bg-red-100 dark:hover:bg-red-900/30 rounded-lg transition-colors"
                                    title="Delete">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                </button>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif
</x-tutor-layout>
