@props([
    'students' => []
])

<x-ui.glass-card>
    <div class="flex items-center justify-between mb-6">
        <x-ui.section-title>Recent Students</x-ui.section-title>
        <a
            href="{{ route('students.index') }}"
            class="text-sm text-sky-600 dark:text-sky-400 hover:text-sky-700 dark:hover:text-sky-300 font-medium inline-flex items-center gap-1 transition-colors"
            aria-label="View all students"
        >
            View All
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
            </svg>
        </a>
    </div>

    @if(count($students) > 0)
        <div class="overflow-x-auto">
            <table class="w-full" role="table" aria-label="Recent Students">
                <thead>
                    <tr class="border-b border-white/10 dark:border-gray-700/10">
                        <th class="text-left py-3 px-2 text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase tracking-wider" scope="col">Student</th>
                        <th class="text-left py-3 px-2 text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase tracking-wider" scope="col">Class</th>
                        <th class="text-left py-3 px-2 text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase tracking-wider" scope="col">Status</th>
                        <th class="text-left py-3 px-2 text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase tracking-wider" scope="col">Attendance</th>
                        <th class="text-right py-3 px-2 text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase tracking-wider" scope="col">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-white/10 dark:divide-gray-700/10">
                    @foreach($students as $student)
                    <tr class="hover:bg-white/10 dark:hover:bg-gray-900/20 transition-colors" role="row">
                        <td class="py-3 px-2" role="cell">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-full bg-gradient-manager flex items-center justify-center text-white font-semibold shadow-md">
                                    {{ substr($student['name'] ?? 'S', 0, 1) }}
                                </div>
                                <div>
                                    <p class="font-medium text-gray-900 dark:text-white">{{ $student['name'] ?? 'Student Name' }}</p>
                                    <p class="text-xs text-gray-500 dark:text-gray-500">{{ $student['email'] ?? 'student@example.com' }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="py-3 px-2 text-sm text-gray-700 dark:text-gray-300" role="cell">
                            {{ $student['class'] ?? 'Class A' }}
                        </td>
                        <td class="py-3 px-2" role="cell">
                            <span class="px-2 py-1 rounded-full text-xs font-medium {{ ($student['status'] ?? 'active') === 'active' ? 'bg-green-500/20 text-green-600 dark:text-green-400' : 'bg-gray-500/20 text-gray-600 dark:text-gray-400' }}">
                                {{ ucfirst($student['status'] ?? 'Active') }}
                            </span>
                        </td>
                        <td class="py-3 px-2" role="cell">
                            <div class="flex items-center gap-2">
                                <div class="w-16 bg-gray-200 dark:bg-gray-700 rounded-full h-2">
                                    <div class="bg-gradient-manager h-2 rounded-full" style="width: {{ $student['attendance'] ?? 85 }}%"></div>
                                </div>
                                <span class="text-xs font-medium text-gray-700 dark:text-gray-300">{{ $student['attendance'] ?? 85 }}%</span>
                            </div>
                        </td>
                        <td class="py-3 px-2 text-right" role="cell">
                            <a
                                href="{{ route('students.show', $student['id'] ?? 1) }}"
                                class="text-sky-600 dark:text-sky-400 hover:text-sky-700 dark:hover:text-sky-300 text-sm font-medium transition-colors"
                                aria-label="View {{ $student['name'] ?? 'student' }} details"
                            >
                                View
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
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
            </svg>
            <p class="text-gray-600 dark:text-gray-400">No students to display.</p>
        </div>
    @endif
</x-ui.glass-card>
