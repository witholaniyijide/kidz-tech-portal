<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Parent Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <div class="mb-6">
                <h3 class="text-lg font-semibold text-gray-900">Welcome back, {{ Auth::user()->name }}!</h3>
                <p class="text-gray-600">Here's an overview of your children's progress.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-600">My Children</p>
                                <p class="text-3xl font-bold text-blue-600 mt-2">{{ $children->count() }}</p>
                            </div>
                            <div class="p-3 bg-blue-100 rounded-full">
                                <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-600">Recent Reports</p>
                                <p class="text-3xl font-bold text-purple-600 mt-2">{{ $recentReports->count() }}</p>
                            </div>
                            <div class="p-3 bg-purple-100 rounded-full">
                                <svg class="w-8 h-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-600">Attendance ({{ $currentMonth }})</p>
                                <p class="text-3xl font-bold text-green-600 mt-2">{{ $attendanceRate }}%</p>
                            </div>
                            <div class="p-3 bg-green-100 rounded-full">
                                <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4 border-b pb-2">My Children</h3>
                        
                        <div class="space-y-4">
                            @foreach($children as $child)
                                <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition">
                                    <div class="flex items-center">
                                        <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center mr-4">
                                            <span class="text-lg font-bold text-blue-600">{{ substr($child->first_name, 0, 1) }}</span>
                                        </div>
                                        <div>
                                            <h4 class="font-semibold text-gray-900">{{ $child->full_name }}</h4>
                                            <p class="text-sm text-gray-500">{{ $child->student_id }} • {{ $child->grade }}</p>
                                        </div>
                                    </div>
                                    <a href="{{ route('parent.child.show', $child) }}" style="display: inline-block; padding: 8px 16px; background-color: #2563EB; color: white; font-weight: bold; border-radius: 8px; text-decoration: none; font-size: 14px;">
                                        View Profile
                                    </a>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4 border-b pb-2">Recent Reports</h3>
                        
                        @if($recentReports->count() > 0)
                            <div class="space-y-3">
                                @foreach($recentReports as $report)
                                    <div class="p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition">
                                        <div class="flex justify-between items-start mb-2">
                                            <div>
                                                <h4 class="font-semibold text-gray-900">{{ $report->student->full_name }}</h4>
                                                <p class="text-sm text-gray-500">{{ $report->month }} {{ $report->year }}</p>
                                            </div>
                                            <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">Approved</span>
                                        </div>
                                        <p class="text-sm text-gray-600 mb-2">{{ Str::limit($report->comments, 80) }}</p>
                                        <a href="{{ route('parent.child.report.view', [$report->student, $report]) }}" class="text-sm text-blue-600 hover:text-blue-800 font-medium">
                                            View Full Report →
                                        </a>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-8">
                                <p class="text-gray-500">No reports available yet.</p>
                            </div>
                        @endif
                    </div>
                </div>

            </div>

        </div>
    </div>
</x-app-layout>
