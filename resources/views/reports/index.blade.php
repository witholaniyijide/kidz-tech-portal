<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Monthly Progress Reports
            </h2>
            <a href="{{ route('reports.create') }}" style="display: inline-block; padding: 10px 20px; background-color: #2563EB; color: white; font-weight: bold; border-radius: 8px; text-decoration: none;">
                + Create Report
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <form method="GET" action="{{ route('reports.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                        
                        <div>
                            <select name="status" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                <option value="">All Status</option>
                                <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                                <option value="submitted" {{ request('status') == 'submitted' ? 'selected' : '' }}>Submitted</option>
                                <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved</option>
                                <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                            </select>
                        </div>

                        <div>
                            <select name="month" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                <option value="">All Months</option>
                                <option value="January" {{ request('month') == 'January' ? 'selected' : '' }}>January</option>
                                <option value="February" {{ request('month') == 'February' ? 'selected' : '' }}>February</option>
                                <option value="March" {{ request('month') == 'March' ? 'selected' : '' }}>March</option>
                                <option value="April" {{ request('month') == 'April' ? 'selected' : '' }}>April</option>
                                <option value="May" {{ request('month') == 'May' ? 'selected' : '' }}>May</option>
                                <option value="June" {{ request('month') == 'June' ? 'selected' : '' }}>June</option>
                                <option value="July" {{ request('month') == 'July' ? 'selected' : '' }}>July</option>
                                <option value="August" {{ request('month') == 'August' ? 'selected' : '' }}>August</option>
                                <option value="September" {{ request('month') == 'September' ? 'selected' : '' }}>September</option>
                                <option value="October" {{ request('month') == 'October' ? 'selected' : '' }}>October</option>
                                <option value="November" {{ request('month') == 'November' ? 'selected' : '' }}>November</option>
                                <option value="December" {{ request('month') == 'December' ? 'selected' : '' }}>December</option>
                            </select>
                        </div>

                        <div>
                            <select name="year" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                <option value="">All Years</option>
                                <option value="2024" {{ request('year') == '2024' ? 'selected' : '' }}>2024</option>
                                <option value="2025" {{ request('year') == '2025' ? 'selected' : '' }}>2025</option>
                                <option value="2026" {{ request('year') == '2026' ? 'selected' : '' }}>2026</option>
                            </select>
                        </div>

                        <div class="flex gap-2">
                            <button type="submit" style="display: inline-block; padding: 10px 20px; background-color: #2563EB; color: white; font-weight: bold; border-radius: 8px; border: none; cursor: pointer;">
                                Filter
                            </button>
                            <a href="{{ route('reports.index') }}" style="display: inline-block; padding: 10px 20px; background-color: #6B7280; color: white; font-weight: bold; border-radius: 8px; text-decoration: none;">
                                Clear
                            </a>
                        </div>

                    </form>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    
                    @if($reports->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Student</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Month/Year</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Instructor</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Created</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($reports as $report)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm font-medium text-gray-900">{{ $report->student->full_name }}</div>
                                                <div class="text-sm text-gray-500">{{ $report->student->student_id }}</div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                {{ $report->month }} {{ $report->year }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                {{ $report->instructor->name }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                @if($report->status == 'draft')
                                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">Draft</span>
                                                @elseif($report->status == 'submitted')
                                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">Submitted</span>
                                                @elseif($report->status == 'approved')
                                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Approved</span>
                                                @else
                                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">Rejected</span>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                {{ $report->created_at->format('M d, Y') }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                                <a href="{{ route('reports.show', $report) }}" class="text-blue-600 hover:text-blue-900 mr-3">View</a>
                                                
                                                @if($report->status == 'draft' || $report->status == 'rejected')
                                                    <a href="{{ route('reports.edit', $report) }}" class="text-indigo-600 hover:text-indigo-900 mr-3">Edit</a>
                                                @endif

                                                @if($report->status == 'submitted')
                                                    <form action="{{ route('reports.approve', $report) }}" method="POST" class="inline">
                                                        @csrf
                                                        <button type="submit" class="text-green-600 hover:text-green-900 mr-3">Approve</button>
                                                    </form>
                                                    <form action="{{ route('reports.reject', $report) }}" method="POST" class="inline">
                                                        @csrf
                                                        <button type="submit" class="text-red-600 hover:text-red-900">Reject</button>
                                                    </form>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="mt-4">
                            {{ $reports->links() }}
                        </div>

                    @else
                        <div class="text-center py-12">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900">No reports found</h3>
                            <p class="mt-1 text-sm text-gray-500">Get started by creating a new report.</p>
                            <div class="mt-6">
                                <a href="{{ route('reports.create') }}" style="display: inline-block; padding: 12px 24px; background-color: #2563EB; color: white; font-weight: bold; border-radius: 8px; text-decoration: none;">
                                    + Create Report
                                </a>
                            </div>
                        </div>
                    @endif

                </div>
            </div>

        </div>
    </div>
</x-app-layout>
