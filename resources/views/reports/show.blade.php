<x-app-layout>
    <x-slot name="title">{{ __('Reports') }}</x-slot>

    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Monthly Progress Report
            </h2>
            <div class="flex gap-2">
                @if($report->status == 'draft' || $report->status == 'rejected')
                    <a href="{{ route('reports.edit', $report) }}" style="display: inline-block; padding: 10px 20px; background-color: #2563EB; color: white; font-weight: bold; border-radius: 8px; text-decoration: none;">
                        Edit Report
                    </a>
                @endif
                <a href="{{ route('reports.index') }}" style="display: inline-block; padding: 10px 20px; background-color: #6B7280; color: white; font-weight: bold; border-radius: 8px; text-decoration: none;">
                    ← Back to Reports
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            
            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <div class="text-center mb-6">
                        <h1 class="text-2xl font-bold text-gray-900">Kidz Tech Coding Club</h1>
                        <p class="text-lg text-gray-600">Monthly Progress Report</p>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6 pb-6 border-b">
                        <div>
                            <h3 class="text-sm font-medium text-gray-500">Student</h3>
                            <p class="mt-1 text-lg font-semibold text-gray-900">{{ $report->student->full_name }}</p>
                            <p class="text-sm text-gray-500">{{ $report->student->student_id }}</p>
                        </div>
                        <div>
                            <h3 class="text-sm font-medium text-gray-500">Month/Year</h3>
                            <p class="mt-1 text-lg font-semibold text-gray-900">{{ $report->month }} {{ $report->year }}</p>
                        </div>
                        <div>
                            <h3 class="text-sm font-medium text-gray-500">Instructor</h3>
                            <p class="mt-1 text-gray-900">{{ $report->instructor->name }}</p>
                        </div>
                        <div>
                            <h3 class="text-sm font-medium text-gray-500">Status</h3>
                            <p class="mt-1">
                                @if($report->status == 'draft')
                                    <span class="px-3 py-1 inline-flex text-sm leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">Draft</span>
                                @elseif($report->status == 'submitted')
                                    <span class="px-3 py-1 inline-flex text-sm leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">Submitted</span>
                                @elseif($report->status == 'approved')
                                    <span class="px-3 py-1 inline-flex text-sm leading-5 font-semibold rounded-full bg-green-100 text-green-800">Approved</span>
                                @else
                                    <span class="px-3 py-1 inline-flex text-sm leading-5 font-semibold rounded-full bg-red-100 text-red-800">Rejected</span>
                                @endif
                            </p>
                            @if($report->approvedBy)
                                <p class="text-xs text-gray-500 mt-1">
                                    By {{ $report->approvedBy->name }} on {{ $report->approved_at->format('M d, Y') }}
                                </p>
                            @endif
                        </div>
                    </div>

                    <div class="mb-6 pb-6 border-b">
                        <h3 class="text-lg font-semibold text-gray-900 mb-3">Course(s) Taught</h3>
                        <div class="flex flex-wrap gap-2">
                            @foreach($report->courses as $course)
                                <span class="px-3 py-1 bg-purple-100 text-purple-800 rounded-full text-sm font-medium">{{ $course }}</span>
                            @endforeach
                        </div>
                    </div>

                    <div class="mb-6 pb-6 border-b" style="background-color: #F0F9FF; padding: 20px; border-radius: 12px;">
                        <h3 class="text-lg font-semibold text-gray-900 mb-3">1. Progress Overview</h3>
                        
                        <div class="mb-4">
                            <h4 class="text-sm font-medium text-gray-700 mb-2">Skills Mastered</h4>
                            <div class="flex flex-wrap gap-2">
                                @foreach($report->skills_mastered as $skill)
                                    <span class="px-3 py-1 bg-green-100 text-green-800 rounded-full text-sm">{{ $skill }}</span>
                                @endforeach
                            </div>
                        </div>

                        @if($report->skills_new && count($report->skills_new) > 0)
                        <div>
                            <h4 class="text-sm font-medium text-gray-700 mb-2">New Skills</h4>
                            <div class="flex flex-wrap gap-2">
                                @foreach($report->skills_new as $skill)
                                    <span class="px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-sm">{{ $skill }}</span>
                                @endforeach
                            </div>
                        </div>
                        @endif
                    </div>

                    <div class="mb-6 pb-6 border-b" style="background-color: #ECFDF5; padding: 20px; border-radius: 12px;">
                        <h3 class="text-lg font-semibold text-gray-900 mb-3">2. Projects/Activities Completed</h3>
                        <div class="space-y-3">
                            @foreach($report->projects as $index => $project)
                                <div class="bg-white p-4 rounded-lg border">
                                    <div class="font-semibold text-gray-900">Project {{ $index + 1 }}: {{ $project['title'] }}</div>
                                    @if(isset($project['link']) && $project['link'])
                                        <div class="text-sm text-gray-600 mt-1">{{ $project['link'] }}</div>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <div class="mb-6 pb-6 border-b" style="background-color: #FFF2F2; padding: 20px; border-radius: 12px;">
                        <h3 class="text-lg font-semibold text-gray-900 mb-3">3. Areas for Improvement</h3>
                        <p class="text-gray-700 whitespace-pre-line">{{ $report->improvement }}</p>
                    </div>

                    <div class="mb-6 pb-6 border-b" style="background-color: #FFFBEB; padding: 20px; border-radius: 12px;">
                        <h3 class="text-lg font-semibold text-gray-900 mb-3">4. Goals for Next Month</h3>
                        <p class="text-gray-700 whitespace-pre-line">{{ $report->goals }}</p>
                    </div>

                    <div class="mb-6 pb-6 border-b" style="background-color: #F0F9FF; padding: 20px; border-radius: 12px;">
                        <h3 class="text-lg font-semibold text-gray-900 mb-3">5. Assignment/Projects during the month</h3>
                        <p class="text-gray-700 whitespace-pre-line">{{ $report->assignments }}</p>
                    </div>

                    <div class="mb-6" style="background-color: #ECFDF5; padding: 20px; border-radius: 12px;">
                        <h3 class="text-lg font-semibold text-gray-900 mb-3">6. Comments/Observation</h3>
                        <p class="text-gray-700 whitespace-pre-line">{{ $report->comments }}</p>
                    </div>

                    <div class="flex flex-wrap gap-3 pt-6 border-t">
                        <button onclick="copyForWhatsApp()" style="display: inline-block; padding: 10px 20px; background-color: #25D366; color: white; font-weight: bold; border-radius: 8px; border: none; cursor: pointer;">
                            📱 Copy for WhatsApp
                        </button>

                        <button onclick="window.print()" style="display: inline-block; padding: 10px 20px; background-color: #DC2626; color: white; font-weight: bold; border-radius: 8px; border: none; cursor: pointer;">
                            📄 Print/PDF
                        </button>

                        @if($report->status == 'submitted')
                            <form action="{{ route('reports.approve', $report) }}" method="POST" class="inline">
                                @csrf
                                <button type="submit" style="display: inline-block; padding: 10px 20px; background-color: #059669; color: white; font-weight: bold; border-radius: 8px; border: none; cursor: pointer;">
                                    ✓ Approve Report
                                </button>
                            </form>
                            
                            <form action="{{ route('reports.reject', $report) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to reject this report?');">
                                @csrf
                                <button type="submit" style="display: inline-block; padding: 10px 20px; background-color: #DC2626; color: white; font-weight: bold; border-radius: 8px; border: none; cursor: pointer;">
                                    ✗ Reject Report
                                </button>
                            </form>
                        @endif

                        @if($report->status == 'draft' || $report->status == 'rejected')
                            <form action="{{ route('reports.destroy', $report) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this report?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" style="display: inline-block; padding: 10px 20px; background-color: #6B7280; color: white; font-weight: bold; border-radius: 8px; border: none; cursor: pointer;">
                                    🗑️ Delete Report
                                </button>
                            </form>
                        @endif
                    </div>

                </div>
            </div>

        </div>
    </div>

    <script>
        function copyForWhatsApp() {
            const text = `*Kidz Tech Coding Club: Monthly Progress Report*

*Student:* {{ $report->student->full_name }}
*Month:* {{ $report->month }} {{ $report->year }}
*Instructor:* {{ $report->instructor->name }}
*Course(s):* {{ implode(', ', $report->courses) }}

*1. Progress Overview:*
*Skills Mastered:* {{ implode(', ', $report->skills_mastered) }}
@if($report->skills_new && count($report->skills_new) > 0)*New Skills:* {{ implode(', ', $report->skills_new) }}@endif

*2. Projects/Activities Completed:*
@foreach($report->projects as $index => $project)Project {{ $index + 1 }}: {{ $project['title'] }}@if(isset($project['link']) && $project['link']) - {{ $project['link'] }}@endif
@endforeach

*3. Areas for Improvement:*
{{ $report->improvement }}

*4. Goals for Next Month:*
{{ $report->goals }}

*5. Assignment/Projects during the month:*
{{ $report->assignments }}

*6. Comments/Observation:*
{{ $report->comments }}`;

            navigator.clipboard.writeText(text).then(() => {
                alert('Report copied to clipboard! You can now paste it in WhatsApp.');
            }).catch(() => {
                alert('Failed to copy. Please try again.');
            });
        }
    </script>

    <style>
        @media print {
            .no-print, header, .flex.gap-3.pt-6 {
                display: none !important;
            }
            body {
                background: white;
            }
        }
    </style>
</x-app-layout>
