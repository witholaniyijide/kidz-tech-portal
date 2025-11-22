<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Today's Schedule - {{ $schedule->day_name ?? now()->format('l') }}
            </h2>
            <span class="text-sm text-gray-600">
                {{ $schedule->schedule_date ? $schedule->schedule_date->format('F d, Y') : now()->format('F d, Y') }}
            </span>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-lg font-semibold">
                            Class Schedule
                            @if($schedule->status === 'draft')
                                <span class="text-sm text-yellow-600 ml-2">(Draft)</span>
                            @elseif($schedule->status === 'posted')
                                <span class="text-sm text-green-600 ml-2">(Posted)</span>
                            @endif
                        </h3>

                        @if($schedule->status !== 'posted')
                            <form action="{{ route('schedule.post') }}" method="POST">
                                @csrf
                                <input type="hidden" name="schedule_date" value="{{ $schedule->schedule_date ? $schedule->schedule_date->format('Y-m-d') : now()->format('Y-m-d') }}">
                                <input type="hidden" name="day_name" value="{{ $schedule->day_name ?? now()->format('l') }}">
                                <input type="hidden" name="classes" value="{{ json_encode($schedule->classes ?? []) }}">
                                <input type="hidden" name="footer_note" value="{{ $schedule->footer_note ?? 'Have a great day! - Kidz Tech Portal Team' }}">
                                <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                    Post Schedule
                                </button>
                            </form>
                        @endif
                    </div>

                    @if(empty($schedule->classes) || count($schedule->classes) === 0)
                        <div class="text-center py-8 text-gray-500">
                            <p class="text-lg">No classes scheduled for today.</p>
                        </div>
                    @else
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">#</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Time</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Student</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tutor</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($schedule->classes as $index => $class)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                {{ $index + 1 }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                                {{ $class['time'] ?? 'N/A' }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                {{ $class['student_name'] ?? 'N/A' }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                {{ $class['tutor_name'] ?? 'N/A' }}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="mt-4 text-sm text-gray-600">
                            <p><strong>Total Classes:</strong> {{ count($schedule->classes) }}</p>
                        </div>

                        @if($schedule->footer_note)
                            <div class="mt-6 p-4 bg-blue-50 rounded border border-blue-200">
                                <p class="text-sm text-blue-800">{{ $schedule->footer_note }}</p>
                            </div>
                        @endif

                        @if($schedule->status === 'posted' && $schedule->posted_at)
                            <div class="mt-4 text-sm text-gray-500">
                                <p>Posted on {{ $schedule->posted_at->format('F d, Y \a\t g:i A') }}</p>
                            </div>
                        @endif
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
