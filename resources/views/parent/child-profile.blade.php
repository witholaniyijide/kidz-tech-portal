<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ $student->full_name }}
            </h2>
            <a href="{{ route('parent.dashboard') }}" style="display: inline-block; padding: 10px 20px; background-color: #6B7280; color: white; font-weight: bold; border-radius: 8px; text-decoration: none;">
                ← Back to Dashboard
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                
                <div class="lg:col-span-1">
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <div class="text-center">
                                <div class="w-32 h-32 mx-auto bg-blue-100 rounded-full flex items-center justify-center mb-4">
                                    <span class="text-4xl font-bold text-blue-600">{{ substr($student->first_name, 0, 1) }}{{ substr($student->last_name, 0, 1) }}</span>
                                </div>
                                
                                <h3 class="text-xl font-bold text-gray-900">{{ $student->full_name }}</h3>
                                <p class="text-sm text-gray-500 mt-1">{{ $student->student_id }}</p>
                                
                                <div class="mt-4">
                                    <span class="px-3 py-1 inline-flex text-sm leading-5 font-semibold rounded-full bg-green-100 text-green-800">Active</span>
                                </div>

                                <div class="mt-6 pt-6 border-t border-gray-200">
                                    <dl class="space-y-3 text-left">
                                        <div>
                                            <dt class="text-sm font-medium text-gray-500">Email</dt>
                                            <dd class="mt-1 text-sm text-gray-900">{{ $student->email }}</dd>
                                        </div>
                                        <div>
                                            <dt class="text-sm font-medium text-gray-500">Phone</dt>
                                            <dd class="mt-1 text-sm text-gray-900">{{ $student->phone }}</dd>
                                        </div>
                                        <div>
                                            <dt class="text-sm font-medium text-gray-500">Grade</dt>
                                            <dd class="mt-1 text-sm text-gray-900">{{ $student->grade }}</dd>
                                        </div>
                                        <div>
                                            <dt class="text-sm font-medium text-gray-500">Date of Birth</dt>
                                            <dd class="mt-1 text-sm text-gray-900">{{ $student->date_of_birth->format('M d, Y') }}</dd>
                                        </div>
                                        <div>
                                            <dt class="text-sm font-medium text-gray-500">Location</dt>
                                            <dd class="mt-1 text-sm text-gray-900">{{ $student->location ?? 'Not specified' }}</dd>
                                        </div>
                                    </dl>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="lg:col-span-2 space-y-6">
                    
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4 border-b pb-2">Quick Actions</h3>
                            
                            <div class="grid grid-cols-2 gap-4">
                                
                                <a href="{{ route('parent.child.reports', $student) }}" class="flex flex-col items-center justify-center p-6 bg-purple-50 rounded-lg hover:bg-purple-100 transition">
                                    <svg class="w-10 h-10 text-purple-600 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                    </svg>
                                    <span class="text-sm font-semibold text-gray-700">View Reports</span>
                                </a>

                                <a href="{{ route('parent.child.attendance', $student) }}" class="flex flex-col items-center justify-center p-6 bg-green-50 rounded-lg hover:bg-green-100 transition">
                                    <svg class="w-10 h-10 text-green-600 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                    </svg>
                                    <span class="text-sm font-semibold text-gray-700">View Attendance</span>
                                </a>

                            </div>
                        </div>
                    </div>

                    @if($student->emergency_contact_name)
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4 border-b pb-2">Emergency Contact</h3>
                            <div class="space-y-2">
                                <p class="text-sm"><span class="font-medium">Name:</span> {{ $student->emergency_contact_name }}</p>
                                <p class="text-sm"><span class="font-medium">Relationship:</span> {{ $student->emergency_contact_relationship }}</p>
                                <p class="text-sm"><span class="font-medium">Phone:</span> {{ $student->emergency_contact_phone }}</p>
                            </div>
                        </div>
                    </div>
                    @endif

                </div>

            </div>

        </div>
    </div>
</x-app-layout>
