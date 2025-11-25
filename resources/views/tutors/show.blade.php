<x-app-layout>
    <x-slot name="title">{{ __('Tutors') }}</x-slot>

    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Tutor Profile: {{ $tutor->full_name }}
            </h2>
            <div class="flex gap-2">
                <a href="{{ route('tutors.edit', $tutor) }}" style="display: inline-block; padding: 10px 20px; background-color: #2563EB; color: white; font-weight: bold; border-radius: 8px; text-decoration: none;">
                    Edit Tutor
                </a>
                <a href="{{ route('tutors.index') }}" style="display: inline-block; padding: 10px 20px; background-color: #6B7280; color: white; font-weight: bold; border-radius: 8px; text-decoration: none;">
                    ← Back to List
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                
                <div class="lg:col-span-1">
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <div class="text-center">
                                <div class="w-32 h-32 mx-auto bg-purple-100 rounded-full flex items-center justify-center mb-4">
                                    <span class="text-4xl font-bold text-purple-600">{{ substr($tutor->first_name, 0, 1) }}{{ substr($tutor->last_name, 0, 1) }}</span>
                                </div>
                                
                                <h3 class="text-xl font-bold text-gray-900">{{ $tutor->full_name }}</h3>
                                <p class="text-sm text-gray-500 mt-1">{{ $tutor->tutor_id }}</p>
                                
                                <div class="mt-4">
                                    @if($tutor->status == 'active')
                                        <span class="px-3 py-1 inline-flex text-sm leading-5 font-semibold rounded-full bg-green-100 text-green-800">Active</span>
                                    @elseif($tutor->status == 'inactive')
                                        <span class="px-3 py-1 inline-flex text-sm leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">Inactive</span>
                                    @else
                                        <span class="px-3 py-1 inline-flex text-sm leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">On Leave</span>
                                    @endif
                                </div>

                                <div class="mt-6 pt-6 border-t border-gray-200">
                                    <dl class="space-y-3">
                                        <div>
                                            <dt class="text-sm font-medium text-gray-500">Email</dt>
                                            <dd class="mt-1 text-sm text-gray-900">{{ $tutor->email }}</dd>
                                        </div>
                                        <div>
                                            <dt class="text-sm font-medium text-gray-500">Phone</dt>
                                            <dd class="mt-1 text-sm text-gray-900">{{ $tutor->phone }}</dd>
                                        </div>
                                        <div>
                                            <dt class="text-sm font-medium text-gray-500">Location</dt>
                                            <dd class="mt-1 text-sm text-gray-900">{{ $tutor->location ?? 'Not specified' }}</dd>
                                        </div>
                                        <div>
                                            <dt class="text-sm font-medium text-gray-500">Hire Date</dt>
                                            <dd class="mt-1 text-sm text-gray-900">{{ $tutor->hire_date->format('M d, Y') }}</dd>
                                        </div>
                                        @if($tutor->hourly_rate)
                                        <div>
                                            <dt class="text-sm font-medium text-gray-500">Hourly Rate</dt>
                                            <dd class="mt-1 text-sm text-gray-900">₦{{ number_format($tutor->hourly_rate, 2) }}</dd>
                                        </div>
                                        @endif
                                    </dl>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="lg:col-span-2 space-y-6">
                    
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4 border-b pb-2">Specializations</h3>
                            <div class="flex flex-wrap gap-2">
                                @foreach($tutor->specializations as $spec)
                                    <span class="px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-sm font-medium">{{ $spec }}</span>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    @if($tutor->qualifications)
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4 border-b pb-2">Qualifications</h3>
                            <p class="text-sm text-gray-700 whitespace-pre-line">{{ $tutor->qualifications }}</p>
                        </div>
                    </div>
                    @endif

                    @if($tutor->notes)
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4 border-b pb-2">Notes</h3>
                            <p class="text-sm text-gray-700 whitespace-pre-line">{{ $tutor->notes }}</p>
                        </div>
                    </div>
                    @endif

                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4 border-b pb-2">Actions</h3>
                            
                            <div class="flex gap-3">
                                <a href="{{ route('tutors.edit', $tutor) }}" style="display: inline-block; padding: 10px 20px; background-color: #2563EB; color: white; font-weight: bold; border-radius: 8px; text-decoration: none;">
                                    Edit Tutor
                                </a>
                                
                                <form action="{{ route('tutors.destroy', $tutor) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this tutor?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" style="display: inline-block; padding: 10px 20px; background-color: #DC2626; color: white; font-weight: bold; border-radius: 8px; border: none; cursor: pointer;">
                                        Delete Tutor
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>

                </div>

            </div>

        </div>
    </div>
</x-app-layout>
