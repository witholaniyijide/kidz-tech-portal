<x-app-layout>
    <x-slot name="header">{{ __('Tutor Details') }}</x-slot>
    <x-slot name="title">{{ __('Admin - Tutor Details') }}</x-slot>

    <div class="min-h-screen bg-gradient-to-br from-[#423A8E]/5 via-[#00CCCD]/5 to-blue-50 dark:from-slate-900 dark:via-slate-800 dark:to-slate-900 py-8 relative overflow-hidden">
        <div class="absolute top-0 left-0 w-72 h-72 bg-[#423A8E]/30 dark:bg-[#423A8E]/30 rounded-full mix-blend-multiply filter blur-xl opacity-70 animate-float"></div>
        <div class="absolute top-0 right-0 w-72 h-72 bg-[#00CCCD]/30 dark:bg-[#00CCCD]/40 rounded-full mix-blend-multiply filter blur-xl opacity-70 animate-float" style="animation-delay: 2s;"></div>

        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            @if(session('success'))
                <div class="mb-6 bg-emerald-100 dark:bg-emerald-900/30 border border-emerald-400 text-emerald-800 dark:text-emerald-400 px-6 py-4 rounded-xl">
                    {{ session('success') }}
                </div>
            @endif

            {{-- Header --}}
            <div class="flex flex-wrap items-center justify-between gap-4 mb-8">
                <div class="flex items-center gap-4">
                    @if($tutor->profile_photo)
                        <img src="{{ Storage::url($tutor->profile_photo) }}" alt="Profile" class="w-16 h-16 rounded-2xl object-cover shadow-lg">
                    @else
                        <div class="w-16 h-16 bg-gradient-to-br from-[#00CCCD] to-blue-500 rounded-2xl flex items-center justify-center text-white font-bold text-2xl shadow-lg">
                            {{ strtoupper(substr($tutor->first_name, 0, 1)) }}{{ strtoupper(substr($tutor->last_name, 0, 1)) }}
                        </div>
                    @endif
                    <div>
                        <h1 class="text-3xl font-bold text-gray-900 dark:text-white">
                            {{ $tutor->first_name }} {{ $tutor->last_name }}
                        </h1>
                        <p class="text-gray-600 dark:text-gray-400">{{ $tutor->email }}</p>
                    </div>
                </div>
                <div class="flex gap-2">
                    <a href="{{ route('admin.tutors.edit', $tutor) }}" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                        </svg>
                        Edit
                    </a>
                    <a href="{{ route('admin.tutors.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-300 dark:hover:bg-gray-600 transition-colors">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                        </svg>
                        Back
                    </a>
                </div>
            </div>

            {{-- Status & Stats --}}
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
                <div class="bg-white/60 dark:bg-gray-800/60 backdrop-blur-md border border-white/20 rounded-2xl p-5 shadow">
                    <div class="text-sm text-gray-500 dark:text-gray-400 mb-1">Status</div>
                    <span class="inline-flex px-3 py-1 text-sm font-semibold rounded-full
                        @if($tutor->status === 'active') bg-emerald-100 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-400
                        @elseif($tutor->status === 'inactive') bg-gray-100 text-gray-700 dark:bg-gray-700 dark:text-gray-300
                        @elseif($tutor->status === 'on_leave') bg-amber-100 text-amber-700 dark:bg-amber-900/30 dark:text-amber-400
                        @else bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400
                        @endif">
                        {{ ucfirst(str_replace('_', ' ', $tutor->status)) }}
                    </span>
                </div>
                <div class="bg-white/60 dark:bg-gray-800/60 backdrop-blur-md border border-white/20 rounded-2xl p-5 shadow">
                    <div class="text-sm text-gray-500 dark:text-gray-400 mb-1">Students Assigned</div>
                    <div class="text-2xl font-bold text-gray-900 dark:text-white">{{ $tutor->students->count() }}</div>
                </div>
                <div class="bg-white/60 dark:bg-gray-800/60 backdrop-blur-md border border-white/20 rounded-2xl p-5 shadow">
                    <div class="text-sm text-gray-500 dark:text-gray-400 mb-1">Total Classes</div>
                    <div class="text-2xl font-bold text-gray-900 dark:text-white">{{ $tutor->attendances->count() ?? 0 }}</div>
                </div>
                <div class="bg-white/60 dark:bg-gray-800/60 backdrop-blur-md border border-white/20 rounded-2xl p-5 shadow">
                    <div class="text-sm text-gray-500 dark:text-gray-400 mb-1">Joined</div>
                    <div class="text-lg font-semibold text-gray-900 dark:text-white">{{ $tutor->created_at->format('M Y') }}</div>
                </div>
            </div>

            {{-- Personal Info --}}
            <div class="bg-white/60 dark:bg-gray-800/60 backdrop-blur-md border border-white/20 rounded-2xl shadow mb-6 overflow-hidden">
                <div class="px-6 py-4 bg-gradient-to-r from-[#423A8E] to-[#00CCCD] text-white">
                    <h3 class="text-lg font-semibold">Personal Information</h3>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        <div>
                            <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 uppercase mb-1">Full Name</label>
                            <p class="text-gray-900 dark:text-white font-medium">{{ $tutor->first_name }} {{ $tutor->last_name }}</p>
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 uppercase mb-1">Email</label>
                            <p class="text-gray-900 dark:text-white">{{ $tutor->email }}</p>
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 uppercase mb-1">Phone</label>
                            <p class="text-gray-900 dark:text-white">{{ $tutor->phone ?? '-' }}</p>
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 uppercase mb-1">Gender</label>
                            <p class="text-gray-900 dark:text-white">{{ ucfirst($tutor->gender ?? '-') }}</p>
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 uppercase mb-1">Date of Birth</label>
                            <p class="text-gray-900 dark:text-white">{{ $tutor->date_of_birth?->format('M j, Y') ?? '-' }}</p>
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 uppercase mb-1">Location</label>
                            <p class="text-gray-900 dark:text-white">{{ $tutor->location ?? '-' }}</p>
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 uppercase mb-1">Occupation</label>
                            <p class="text-gray-900 dark:text-white">{{ $tutor->occupation ?? '-' }}</p>
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 uppercase mb-1">Bio</label>
                            <p class="text-gray-900 dark:text-white">{{ $tutor->bio ?? '-' }}</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Emergency Contact --}}
            <div class="bg-white/60 dark:bg-gray-800/60 backdrop-blur-md border border-white/20 rounded-2xl shadow mb-6 overflow-hidden">
                <div class="px-6 py-4 bg-gradient-to-r from-orange-500 to-red-600 text-white">
                    <h3 class="text-lg font-semibold">Emergency Contact</h3>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div>
                            <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 uppercase mb-1">Contact Person</label>
                            <p class="text-gray-900 dark:text-white">{{ $tutor->emergency_contact_name ?? '-' }}</p>
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 uppercase mb-1">Relationship</label>
                            <p class="text-gray-900 dark:text-white">{{ $tutor->emergency_contact_relationship ?? '-' }}</p>
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 uppercase mb-1">Phone</label>
                            <p class="text-gray-900 dark:text-white">{{ $tutor->emergency_contact_phone ?? '-' }}</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Payment Details --}}
            <div class="bg-white/60 dark:bg-gray-800/60 backdrop-blur-md border border-white/20 rounded-2xl shadow mb-6 overflow-hidden">
                <div class="px-6 py-4 bg-gradient-to-r from-emerald-500 to-green-600 text-white">
                    <h3 class="text-lg font-semibold">Payment Details</h3>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div>
                            <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 uppercase mb-1">Bank Name</label>
                            <p class="text-gray-900 dark:text-white">{{ $tutor->bank_name ?? '-' }}</p>
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 uppercase mb-1">Account Number</label>
                            <p class="text-gray-900 dark:text-white">{{ $tutor->account_number ?? '-' }}</p>
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 uppercase mb-1">Account Name</label>
                            <p class="text-gray-900 dark:text-white">{{ $tutor->account_name ?? '-' }}</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Availability Calendar --}}
            <div class="bg-white/60 dark:bg-gray-800/60 backdrop-blur-md border border-white/20 rounded-2xl shadow mb-6 overflow-hidden">
                <div class="px-6 py-4 bg-gradient-to-r from-purple-500 to-indigo-600 text-white">
                    <h3 class="text-lg font-semibold">Weekly Availability</h3>
                </div>
                <div class="p-6">
                    @php
                        $availabilities = $tutor->availabilities ?? collect();
                        $days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
                        $availabilityByDay = $availabilities->groupBy('day_of_week');
                    @endphp

                    @if($availabilities->isEmpty())
                        <div class="text-center py-8">
                            <svg class="w-12 h-12 mx-auto text-gray-300 dark:text-gray-600 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                            <p class="text-gray-500 dark:text-gray-400">No availability set</p>
                        </div>
                    @else
                        <div class="grid grid-cols-7 gap-2">
                            @foreach($days as $day)
                                <div class="text-center">
                                    <div class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase mb-2">{{ substr($day, 0, 3) }}</div>
                                    @if(isset($availabilityByDay[$day]) && $availabilityByDay[$day]->count() > 0)
                                        <div class="space-y-1">
                                            @foreach($availabilityByDay[$day] as $slot)
                                                <div class="px-2 py-1 bg-emerald-100 dark:bg-emerald-900/30 text-emerald-700 dark:text-emerald-400 rounded text-xs">
                                                    {{ \Carbon\Carbon::parse($slot->start_time)->format('g:i A') }}
                                                    -
                                                    {{ \Carbon\Carbon::parse($slot->end_time)->format('g:i A') }}
                                                </div>
                                            @endforeach
                                        </div>
                                    @else
                                        <div class="px-2 py-1 bg-gray-100 dark:bg-gray-700 text-gray-400 dark:text-gray-500 rounded text-xs">
                                            -
                                        </div>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>

            {{-- Assigned Students --}}
            @if($tutor->students && $tutor->students->count() > 0)
                <div class="bg-white/60 dark:bg-gray-800/60 backdrop-blur-md border border-white/20 rounded-2xl shadow overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 flex justify-between items-center">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Assigned Students ({{ $tutor->students->count() }})</h3>
                    </div>
                    <div class="p-6">
                        <div class="space-y-4">
                            @foreach($tutor->students as $student)
                                <div class="bg-gray-50 dark:bg-gray-700/50 rounded-xl p-4 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                                    <div class="flex items-start gap-4">
                                        <div class="w-12 h-12 bg-gradient-to-br from-[#00CCCD] to-[#00CCCD] rounded-full flex items-center justify-center text-white font-bold text-sm flex-shrink-0">
                                            {{ strtoupper(substr($student->first_name, 0, 1)) }}{{ strtoupper(substr($student->last_name, 0, 1)) }}
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <div class="flex items-center justify-between gap-2 mb-2">
                                                <a href="{{ route('admin.students.show', $student) }}" class="font-semibold text-gray-900 dark:text-white hover:text-[#423A8E] dark:hover:text-[#00CCCD]">
                                                    {{ $student->first_name }} {{ $student->last_name }}
                                                </a>
                                                <span class="px-2 py-0.5 text-xs font-medium rounded-full flex-shrink-0
                                                    @if($student->status === 'active') bg-emerald-100 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-400
                                                    @else bg-gray-100 text-gray-600 dark:bg-gray-600 dark:text-gray-300
                                                    @endif">
                                                    {{ ucfirst($student->status) }}
                                                </span>
                                            </div>

                                            <div class="text-sm text-gray-600 dark:text-gray-400 mb-2">
                                                {{ $student->classes_per_week ?? 0 }} classes/week
                                            </div>

                                            {{-- Class Schedule --}}
                                            @php
                                                $studentSchedule = $student->class_schedule;
                                                if (is_string($studentSchedule)) {
                                                    $studentSchedule = json_decode($studentSchedule, true) ?? [];
                                                }
                                            @endphp
                                            @if($studentSchedule && is_array($studentSchedule) && count($studentSchedule) > 0)
                                                <div class="mb-3">
                                                    <div class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase mb-1">Schedule</div>
                                                    <div class="flex flex-wrap gap-1">
                                                        @foreach($studentSchedule as $schedule)
                                                            @if(isset($schedule['day']) && isset($schedule['time']))
                                                                <span class="inline-flex items-center px-2 py-1 bg-blue-50 dark:bg-blue-900/30 text-blue-700 dark:text-blue-300 rounded text-xs">
                                                                    {{ ucfirst($schedule['day']) }} @ {{ $schedule['time'] }}
                                                                </span>
                                                            @endif
                                                        @endforeach
                                                    </div>
                                                </div>
                                            @endif

                                            {{-- Links --}}
                                            <div class="flex flex-wrap gap-3">
                                                @if($student->class_link)
                                                    <a href="{{ $student->class_link }}" target="_blank" class="inline-flex items-center text-xs text-[#423A8E] dark:text-[#00CCCD] hover:underline">
                                                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                                                        </svg>
                                                        Class Link
                                                    </a>
                                                @endif
                                                @if($student->google_classroom_link)
                                                    <a href="{{ $student->google_classroom_link }}" target="_blank" class="inline-flex items-center text-xs text-green-600 dark:text-green-400 hover:underline">
                                                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                                                        </svg>
                                                        Google Classroom
                                                    </a>
                                                @endif
                                                @if(!$student->class_link && !$student->google_classroom_link)
                                                    <span class="text-xs text-gray-400 italic">No links set</span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>

    @push('styles')
    <style>
        @keyframes float { 0%, 100% { transform: translateY(0px); } 50% { transform: translateY(-20px); } }
        .animate-float { animation: float 6s ease-in-out infinite; }
    </style>
    @endpush
</x-app-layout>
