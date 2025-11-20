<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Student Profile: {{ $student->full_name }}
            </h2>
            <div class="flex gap-2">
                <a href="{{ route('students.edit', $student) }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                    Edit Student
                </a>
                <a href="{{ route('students.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
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
                                <div class="w-32 h-32 mx-auto bg-blue-100 rounded-full flex items-center justify-center mb-4">
                                    <span class="text-4xl font-bold text-blue-600">{{ substr($student->first_name, 0, 1) }}{{ substr($student->last_name, 0, 1) }}</span>
                                </div>
                                
                                <h3 class="text-xl font-bold text-gray-900">{{ $student->full_name }}</h3>
                                <p class="text-sm text-gray-500 mt-1">{{ $student->student_id }}</p>
                                
                                <div class="mt-4">
                                    @if($student->status == 'active')
                                        <span class="px-3 py-1 inline-flex text-sm leading-5 font-semibold rounded-full bg-green-100 text-green-800">Active</span>
                                    @elseif($student->status == 'inactive')
                                        <span class="px-3 py-1 inline-flex text-sm leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">Inactive</span>
                                    @elseif($student->status == 'graduated')
                                        <span class="px-3 py-1 inline-flex text-sm leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">Graduated</span>
                                    @else
                                        <span class="px-3 py-1 inline-flex text-sm leading-5 font-semibold rounded-full bg-red-100 text-red-800">Withdrawn</span>
                                    @endif
                                </div>

                                <div class="mt-6 pt-6 border-t border-gray-200">
                                    <dl class="space-y-3">
                                        <div>
                                            <dt class="text-sm font-medium text-gray-500">Age</dt>
                                            <dd class="mt-1 text-sm text-gray-900">{{ $student->age }} years old</dd>
                                        </div>
                                        <div>
                                            <dt class="text-sm font-medium text-gray-500">Gender</dt>
                                            <dd class="mt-1 text-sm text-gray-900">{{ ucfirst($student->gender) }}</dd>
                                        </div>
                                        <div>
                                            <dt class="text-sm font-medium text-gray-500">Location</dt>
                                            <dd class="mt-1 text-sm text-gray-900">{{ $student->location ?? 'Not specified' }}</dd>
                                        </div>
                                        <div>
                                            <dt class="text-sm font-medium text-gray-500">Enrollment Date</dt>
                                            <dd class="mt-1 text-sm text-gray-900">{{ $student->enrollment_date->format('M d, Y') }}</dd>
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
                            <h3 class="text-lg font-semibold text-gray-900 mb-4 border-b pb-2">Personal Information</h3>
                            
                            <dl class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-4">
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Full Name</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $student->full_name }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Date of Birth</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $student->date_of_birth->format('M d, Y') }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Email</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $student->email ?? 'Not provided' }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Phone</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $student->phone ?? 'Not provided' }}</dd>
                                </div>
                                <div class="md:col-span-2">
                                    <dt class="text-sm font-medium text-gray-500">Address</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $student->address ?? 'Not provided' }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">State</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $student->state ?? 'Not provided' }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Country</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $student->country }}</dd>
                                </div>
                            </dl>
                        </div>
                    </div>

                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4 border-b pb-2">Parent/Guardian Information</h3>
                            
                            <dl class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-4">
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Name</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $student->parent_name }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Relationship</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $student->parent_relationship }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Email</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $student->parent_email ?? 'Not provided' }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Phone</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $student->parent_phone }}</dd>
                                </div>
                            </dl>
                        </div>
                    </div>

                    @if($student->notes)
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4 border-b pb-2">Notes</h3>
                            <p class="text-sm text-gray-700">{{ $student->notes }}</p>
                        </div>
                    </div>
                    @endif

                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4 border-b pb-2">Actions</h3>
                            
                            <div class="flex gap-3">
                                <a href="{{ route('students.edit', $student) }}" style="display: inline-block; padding: 10px 20px; background-color: #2563EB; color: white; font-weight: bold; border-radius: 8px; text-decoration: none;">
                                    Edit Student
                                </a>
                                
                                <form action="{{ route('students.destroy', $student) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this student? This action cannot be undone.');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" style="display: inline-block; padding: 10px 20px; background-color: #DC2626; color: white; font-weight: bold; border-radius: 8px; border: none; cursor: pointer;">
                                        Delete Student
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

