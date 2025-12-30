<x-tutor-layout title="Edit Monthly Schedule">
<div class="space-y-6">
    <!-- Page Header -->
    <div class="flex flex-wrap items-center justify-between gap-4">
        <div>
            <h1 class="text-3xl font-bold text-slate-900 dark:text-white">Edit Monthly Schedule</h1>
            <p class="text-slate-600 dark:text-slate-400 mt-1">
                {{ $student->first_name }} {{ $student->last_name }} - {{ $monthName }}
            </p>
        </div>
        <a href="{{ route('tutor.monthly-schedule.index', ['year' => $year, 'month' => $month]) }}"
           class="inline-flex items-center gap-2 px-4 py-2 text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white transition-colors">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            Back to List
        </a>
    </div>

    @if($errors->any())
        <div class="bg-red-100 dark:bg-red-900/30 border border-red-400 text-red-800 dark:text-red-400 px-6 py-4 rounded-xl">
            <ul class="list-disc list-inside">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="max-w-2xl">
        <form action="{{ route('tutor.monthly-schedule.update', $student) }}" method="POST" class="glass-card rounded-2xl shadow-lg overflow-hidden">
            @csrf
            @method('PUT')
            <input type="hidden" name="year" value="{{ $year }}">
            <input type="hidden" name="month" value="{{ $month }}">

            <!-- Header -->
            <div class="px-6 py-4 bg-gradient-to-r from-[#4B49AC] to-[#7978E9]">
                <h2 class="text-lg font-semibold text-white flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    Schedule Details
                </h2>
            </div>

            <div class="p-6 space-y-6">
                <!-- Student Info -->
                <div class="flex items-center gap-4 p-4 bg-slate-50 dark:bg-slate-800/50 rounded-xl">
                    <div class="w-12 h-12 bg-gradient-to-br from-[#7978E9] to-[#98BDFF] rounded-full flex items-center justify-center text-white font-bold">
                        {{ strtoupper(substr($student->first_name, 0, 1)) }}{{ strtoupper(substr($student->last_name, 0, 1)) }}
                    </div>
                    <div>
                        <h3 class="font-semibold text-slate-900 dark:text-white">
                            {{ $student->first_name }} {{ $student->last_name }}
                        </h3>
                        <p class="text-sm text-slate-500">{{ $monthName }}</p>
                    </div>
                </div>

                <!-- Class Days -->
                <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                        Class Days
                    </label>
                    <p class="text-xs text-slate-500 mb-3">Select the days of the week when classes are held</p>

                    @if(!empty($suggestedDays))
                        <div class="mb-3 p-3 bg-blue-50 dark:bg-blue-900/20 rounded-lg text-sm text-blue-700 dark:text-blue-300">
                            <strong>Suggested based on student profile:</strong> {{ implode(', ', $suggestedDays) }}
                        </div>
                    @endif

                    <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                        @php
                            $days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
                            $selectedDays = old('class_days', $schedule?->class_days ?? $suggestedDays ?? []);
                        @endphp
                        @foreach($days as $day)
                            <label class="flex items-center gap-2 p-3 bg-slate-50 dark:bg-slate-800 rounded-lg cursor-pointer hover:bg-slate-100 dark:hover:bg-slate-700 transition-colors">
                                <input type="checkbox" name="class_days[]" value="{{ $day }}"
                                       {{ in_array($day, $selectedDays) ? 'checked' : '' }}
                                       class="w-4 h-4 rounded text-[#7978E9] focus:ring-[#7978E9] border-slate-300 dark:border-slate-600">
                                <span class="text-sm text-slate-700 dark:text-slate-300">{{ $day }}</span>
                            </label>
                        @endforeach
                    </div>
                </div>

                <!-- Total Classes -->
                <div>
                    <label for="total_classes" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                        Total Classes for Month <span class="text-red-500">*</span>
                    </label>
                    <p class="text-xs text-slate-500 mb-2">
                        Number of classes expected for {{ $monthName }}
                    </p>
                    <input type="number" id="total_classes" name="total_classes"
                           value="{{ old('total_classes', $schedule?->total_classes ?? 0) }}"
                           min="0" max="31" required
                           class="w-full px-4 py-3 bg-white dark:bg-slate-700 border border-slate-300 dark:border-slate-600 rounded-xl text-slate-900 dark:text-white focus:ring-2 focus:ring-[#7978E9] focus:border-transparent">
                    <p class="mt-2 text-xs text-slate-500">
                        Tip: For a student with 2 classes per week, the total is typically 8-9 classes per month.
                    </p>
                </div>

                <!-- Notes -->
                <div>
                    <label for="notes" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                        Notes (Optional)
                    </label>
                    <textarea id="notes" name="notes" rows="3"
                              placeholder="Any special notes for this month's schedule..."
                              class="w-full px-4 py-3 bg-white dark:bg-slate-700 border border-slate-300 dark:border-slate-600 rounded-xl text-slate-900 dark:text-white focus:ring-2 focus:ring-[#7978E9] focus:border-transparent resize-none">{{ old('notes', $schedule?->notes) }}</textarea>
                </div>

                <!-- Current Progress -->
                @if($schedule)
                    <div class="p-4 bg-slate-50 dark:bg-slate-800/50 rounded-xl">
                        <h4 class="text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Current Progress</h4>
                        <div class="flex items-center gap-4">
                            <div class="text-2xl font-bold text-[#7978E9]">
                                {{ $schedule->completed_classes }}/{{ $schedule->total_classes }}
                            </div>
                            <div class="flex-1">
                                <div class="h-2 bg-slate-200 dark:bg-slate-700 rounded-full overflow-hidden">
                                    @php
                                        $percent = $schedule->total_classes > 0 ? ($schedule->completed_classes / $schedule->total_classes) * 100 : 0;
                                    @endphp
                                    <div class="h-full bg-gradient-to-r from-[#7978E9] to-[#98BDFF] rounded-full" style="width: {{ $percent }}%"></div>
                                </div>
                            </div>
                            <div class="text-sm text-slate-500">{{ round($percent) }}%</div>
                        </div>
                    </div>
                @endif
            </div>

            <!-- Action Buttons -->
            <div class="px-6 py-4 bg-slate-50 dark:bg-slate-800/50 border-t border-slate-200 dark:border-slate-700 flex items-center justify-end gap-4">
                <a href="{{ route('tutor.monthly-schedule.index', ['year' => $year, 'month' => $month]) }}"
                   class="px-6 py-2.5 text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white font-medium transition-colors">
                    Cancel
                </a>
                <button type="submit"
                        class="px-6 py-2.5 bg-gradient-to-r from-[#4B49AC] to-[#7978E9] text-white rounded-xl hover:opacity-90 hover:shadow-lg hover:-translate-y-0.5 transition-all font-semibold">
                    Save Schedule
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    // Auto-calculate total classes based on selected days
    document.querySelectorAll('input[name="class_days[]"]').forEach(checkbox => {
        checkbox.addEventListener('change', calculateTotalClasses);
    });

    function calculateTotalClasses() {
        const selectedDays = Array.from(document.querySelectorAll('input[name="class_days[]"]:checked'))
            .map(cb => cb.value);

        if (selectedDays.length === 0) return;

        const year = {{ $year }};
        const month = {{ $month }};
        const startDate = new Date(year, month - 1, 1);
        const endDate = new Date(year, month, 0);

        const dayMap = {
            'Sunday': 0, 'Monday': 1, 'Tuesday': 2, 'Wednesday': 3,
            'Thursday': 4, 'Friday': 5, 'Saturday': 6
        };

        let count = 0;
        for (let d = new Date(startDate); d <= endDate; d.setDate(d.getDate() + 1)) {
            const dayName = Object.keys(dayMap).find(k => dayMap[k] === d.getDay());
            if (selectedDays.includes(dayName)) {
                count++;
            }
        }

        document.getElementById('total_classes').value = count;
    }
</script>
@endpush
</x-tutor-layout>
