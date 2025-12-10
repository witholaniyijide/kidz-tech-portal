<x-app-layout>
    <x-slot name="title">{{ __('Attendance') }}</x-slot>

    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Attendance Details
            </h2>
            <a href="{{ route('attendance.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                ← Back to List
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            
            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6 pb-6 border-b">
                        <div>
                            <h3 class="text-sm font-medium text-gray-500">Student</h3>
                            <p class="mt-1 text-lg font-semibold text-gray-900">{{ $attendance->student->full_name }}</p>
                            <p class="text-sm text-gray-500">{{ $attendance->student->student_id }}</p>
                        </div>
                        <div>
                            <h3 class="text-sm font-medium text-gray-500">Date</h3>
                            <p class="mt-1 text-lg font-semibold text-gray-900">{{ $attendance->class_date->format('M d, Y') }}</p>
                        </div>
                        <div>
                            <h3 class="text-sm font-medium text-gray-500">Status</h3>
                            <p class="mt-1">
                                @if($attendance->status == 'present')
                                    <span class="px-3 py-1 inline-flex text-sm leading-5 font-semibold rounded-full bg-green-100 text-green-800">Present</span>
                                @elseif($attendance->status == 'absent')
                                    <span class="px-3 py-1 inline-flex text-sm leading-5 font-semibold rounded-full bg-red-100 text-red-800">Absent</span>
                                @elseif($attendance->status == 'late')
                                    <span class="px-3 py-1 inline-flex text-sm leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">Late</span>
                                @else
                                    <span class="px-3 py-1 inline-flex text-sm leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">Excused</span>
                                @endif
                            </p>
                        </div>
                        <div>
                            <h3 class="text-sm font-medium text-gray-500">Session</h3>
                            <p class="mt-1 text-gray-900">{{ $attendance->session ?? 'N/A' }}</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6 pb-6 border-b">
                        <div>
                            <h3 class="text-sm font-medium text-gray-500">Submitted By</h3>
                            <p class="mt-1 text-gray-900">{{ $attendance->submittedBy->name }}</p>
                            <p class="text-xs text-gray-500">{{ $attendance->created_at->format('M d, Y H:i A') }}</p>
                        </div>
                        <div>
                            <h3 class="text-sm font-medium text-gray-500">Approval Status</h3>
                            <p class="mt-1">
                                @if($attendance->approval_status == 'pending')
                                    <span class="px-3 py-1 inline-flex text-sm leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">Pending</span>
                                @elseif($attendance->approval_status == 'approved')
                                    <span class="px-3 py-1 inline-flex text-sm leading-5 font-semibold rounded-full bg-green-100 text-green-800">Approved</span>
                                @else
                                    <span class="px-3 py-1 inline-flex text-sm leading-5 font-semibold rounded-full bg-red-100 text-red-800">Rejected</span>
                                @endif
                            </p>
                            @if($attendance->approvedBy)
                                <p class="text-xs text-gray-500 mt-1">
                                    By {{ $attendance->approvedBy->name }} on {{ $attendance->approved_at->format('M d, Y H:i A') }}
                                </p>
                            @endif
                        </div>
                    </div>

                    @if($attendance->notes)
                    <div class="mb-6 pb-6 border-b">
                        <h3 class="text-sm font-medium text-gray-500 mb-2">Notes</h3>
                        <p class="text-gray-900">{{ $attendance->notes }}</p>
                    </div>
                    @endif

                    @if($attendance->approval_status == 'pending')
                    <div class="flex gap-3">
                        <form action="{{ route('attendance.approve', $attendance) }}" method="POST">
                            @csrf
                            <button type="submit" style="display: inline-block; padding: 10px 20px; background-color: #059669; color: white; font-weight: bold; border-radius: 8px; border: none; cursor: pointer;">
                                ✓ Approve
                            </button>
                        </form>
                        
                        <form action="{{ route('attendance.reject', $attendance) }}" method="POST" onsubmit="return confirm('Are you sure you want to reject this attendance?');">
                            @csrf
                            <button type="submit" style="display: inline-block; padding: 10px 20px; background-color: #DC2626; color: white; font-weight: bold; border-radius: 8px; border: none; cursor: pointer;">
                                ✗ Reject
                            </button>
                        </form>
                    </div>
                    @endif

                </div>
            </div>

        </div>
    </div>
</x-app-layout>


