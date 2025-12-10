<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-2xl text-white">{{ __('Tutor Details') }}</h2>
    </x-slot>
    <x-slot name="title">{{ __('Admin - Tutor Details') }}</x-slot>

    <div class="min-h-screen bg-gradient-to-br from-teal-50 via-cyan-50 to-blue-50 dark:from-slate-900 dark:via-slate-800 dark:to-slate-900 py-8 relative overflow-hidden">
        <div class="absolute top-0 left-0 w-72 h-72 bg-teal-300 dark:bg-teal-900 rounded-full mix-blend-multiply filter blur-xl opacity-70 animate-float"></div>
        <div class="absolute top-0 right-0 w-72 h-72 bg-cyan-300 dark:bg-cyan-900 rounded-full mix-blend-multiply filter blur-xl opacity-70 animate-float" style="animation-delay: 2s;"></div>

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
                        <div class="w-16 h-16 bg-gradient-to-br from-cyan-400 to-blue-500 rounded-2xl flex items-center justify-center text-white font-bold text-2xl shadow-lg">
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
                <div class="px-6 py-4 bg-gradient-to-r from-teal-500 to-cyan-600 text-white">
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

            {{-- Assigned Students --}}
            @if($tutor->students && $tutor->students->count() > 0)
                <div class="bg-white/60 dark:bg-gray-800/60 backdrop-blur-md border border-white/20 rounded-2xl shadow overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 flex justify-between items-center">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Assigned Students ({{ $tutor->students->count() }})</h3>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                            @foreach($tutor->students as $student)
                                <a href="{{ route('admin.students.show', $student) }}" class="flex items-center gap-3 p-3 bg-gray-50 dark:bg-gray-700/50 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                                    <div class="w-10 h-10 bg-gradient-to-br from-teal-400 to-cyan-500 rounded-full flex items-center justify-center text-white font-bold text-sm">
                                        {{ strtoupper(substr($student->first_name, 0, 1)) }}{{ strtoupper(substr($student->last_name, 0, 1)) }}
                                    </div>
                                    <div>
                                        <div class="font-medium text-gray-900 dark:text-white">{{ $student->first_name }} {{ $student->last_name }}</div>
                                        <div class="text-xs text-gray-500">{{ $student->classes_per_week ?? 0 }} classes/week</div>
                                    </div>
                                    <span class="ml-auto px-2 py-0.5 text-xs font-medium rounded-full
                                        @if($student->status === 'active') bg-emerald-100 text-emerald-700
                                        @else bg-gray-100 text-gray-600
                                        @endif">
                                        {{ ucfirst($student->status) }}
                                    </span>
                                </a>
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
