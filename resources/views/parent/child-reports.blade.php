<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Reports for {{ $student->full_name }}
            </h2>
            <a href="{{ route('parent.child.show', $student) }}" style="display: inline-block; padding: 10px 20px; background-color: #6B7280; color: white; font-weight: bold; border-radius: 8px; text-decoration: none;">
                ← Back to Profile
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    
                    @if($reports->count() > 0)
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                            @foreach($reports as $report)
                                <div class="border rounded-lg p-6 hover:shadow-lg transition">
                                    <div class="flex justify-between items-start mb-4">
                                        <div>
                                            <h3 class="font-bold text-lg text-gray-900">{{ $report->month }} {{ $report->year }}</h3>
                                            <p class="text-sm text-gray-500">{{ $report->created_at->format('M d, Y') }}</p>
                                        </div>
                                        <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">Approved</span>
                                    </div>
                                    
                                    <div class="mb-4">
                                        <p class="text-sm text-gray-600 mb-2">
                                            <strong>Instructor:</strong> {{ $report->instructor->name }}
                                        </p>
                                        <p class="text-sm text-gray-600">
                                            {{ Str::limit($report->comments, 100) }}
                                        </p>
                                    </div>
                                    
                                    <a href="{{ route('parent.child.report.view', [$student, $report]) }}" style="display: inline-block; padding: 8px 16px; background-color: #2563EB; color: white; font-weight: bold; border-radius: 8px; text-decoration: none; font-size: 14px; width: 100%; text-align: center;">
                                        View Full Report
                                    </a>
                                </div>
                            @endforeach
                        </div>

                        <div class="mt-6">
                            {{ $reports->links() }}
                        </div>

                    @else
                        <div class="text-center py-12">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900">No reports available</h3>
                            <p class="mt-1 text-sm text-gray-500">Reports will appear here once they are approved by the director.</p>
                        </div>
                    @endif

                </div>
            </div>

        </div>
    </div>
</x-app-layout>
