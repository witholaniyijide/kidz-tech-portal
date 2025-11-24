@props([
    'tutors' => []
])

<x-ui.glass-card>
    <div class="flex items-center justify-between mb-6">
        <x-ui.section-title>Recent Tutors</x-ui.section-title>
        <a
            href="{{ route('tutors.index') }}"
            class="text-sm text-sky-600 dark:text-sky-400 hover:text-sky-700 dark:hover:text-sky-300 font-medium inline-flex items-center gap-1 transition-colors"
            aria-label="View all tutors"
        >
            View All
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
            </svg>
        </a>
    </div>

    @if(count($tutors) > 0)
        <div class="overflow-x-auto">
            <table class="w-full" role="table" aria-label="Recent Tutors">
                <thead>
                    <tr class="border-b border-white/10 dark:border-gray-700/10">
                        <th class="text-left py-3 px-2 text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase tracking-wider" scope="col">Tutor</th>
                        <th class="text-left py-3 px-2 text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase tracking-wider" scope="col">Subject</th>
                        <th class="text-left py-3 px-2 text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase tracking-wider" scope="col">Students</th>
                        <th class="text-left py-3 px-2 text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase tracking-wider" scope="col">Rating</th>
                        <th class="text-right py-3 px-2 text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase tracking-wider" scope="col">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-white/10 dark:divide-gray-700/10">
                    @foreach($tutors as $tutor)
                    <tr class="hover:bg-white/10 dark:hover:bg-gray-900/20 transition-colors" role="row">
                        <td class="py-3 px-2" role="cell">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-full bg-gradient-manager flex items-center justify-center text-white font-semibold shadow-md">
                                    {{ substr($tutor['name'] ?? 'T', 0, 1) }}
                                </div>
                                <div>
                                    <p class="font-medium text-gray-900 dark:text-white">{{ $tutor['name'] ?? 'Tutor Name' }}</p>
                                    <p class="text-xs text-gray-500 dark:text-gray-500">{{ $tutor['email'] ?? 'tutor@example.com' }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="py-3 px-2 text-sm text-gray-700 dark:text-gray-300" role="cell">
                            {{ $tutor['subject'] ?? 'Mathematics' }}
                        </td>
                        <td class="py-3 px-2" role="cell">
                            <span class="inline-flex items-center gap-1 text-sm text-gray-700 dark:text-gray-300">
                                <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                                </svg>
                                {{ $tutor['students'] ?? 12 }}
                            </span>
                        </td>
                        <td class="py-3 px-2" role="cell">
                            <div class="flex items-center gap-1">
                                @php $rating = $tutor['rating'] ?? 4.5; @endphp
                                @for($i = 1; $i <= 5; $i++)
                                    <svg class="w-4 h-4 {{ $i <= $rating ? 'text-amber-400' : 'text-gray-300 dark:text-gray-600' }}" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                    </svg>
                                @endfor
                                <span class="text-xs text-gray-600 dark:text-gray-400 ml-1">({{ number_format($rating, 1) }})</span>
                            </div>
                        </td>
                        <td class="py-3 px-2 text-right" role="cell">
                            <a
                                href="{{ route('reports.index', ['filter' => 'tutor', 'tutor_id' => $tutor['id'] ?? 1]) }}"
                                class="text-sky-600 dark:text-sky-400 hover:text-sky-700 dark:hover:text-sky-300 text-sm font-medium transition-colors"
                                aria-label="View {{ $tutor['name'] ?? 'tutor' }} reports"
                            >
                                Reports
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @else
        <div class="py-12 text-center">
            <svg class="w-16 h-16 mx-auto text-gray-400 dark:text-gray-600 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
            </svg>
            <p class="text-gray-600 dark:text-gray-400">No tutors to display.</p>
        </div>
    @endif
</x-ui.glass-card>
