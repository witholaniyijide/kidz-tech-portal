<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Pending Attendance Approvals
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                    {{ session('error') }}
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    @if($records->count() > 0)
                        <form id="bulk-action-form" method="POST">
                            @csrf
                            <div class="mb-4 flex justify-between items-center">
                                <div>
                                    <button type="button" onclick="selectAll()" class="text-sm text-blue-600 hover:underline mr-4">Select All</button>
                                    <button type="button" onclick="deselectAll()" class="text-sm text-blue-600 hover:underline">Deselect All</button>
                                </div>
                                <div>
                                    <button type="button" onclick="bulkApprove()" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded mr-2">
                                        Approve Selected
                                    </button>
                                    <button type="button" onclick="bulkReject()" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
                                        Reject Selected
                                    </button>
                                </div>
                            </div>

                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                <input type="checkbox" id="select-all-checkbox" onclick="toggleSelectAll(this)">
                                            </th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Student</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tutor</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Topic</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Duration</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        @foreach($records as $record)
                                            <tr>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <input type="checkbox" name="record_ids[]" value="{{ $record->id }}" class="record-checkbox">
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                    {{ $record->class_date->format('M d, Y') }}
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                    {{ $record->student->full_name ?? 'N/A' }}
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                    {{ $record->tutor->full_name ?? 'N/A' }}
                                                </td>
                                                <td class="px-6 py-4 text-sm text-gray-900">
                                                    {{ $record->topic ?? 'N/A' }}
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                    {{ $record->duration_minutes ?? 'N/A' }} min
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                                    <form action="{{ route('attendance.approve', $record->id) }}" method="POST" class="inline">
                                                        @csrf
                                                        <button type="submit" class="text-green-600 hover:text-green-900 mr-2">Approve</button>
                                                    </form>
                                                    <form action="{{ route('attendance.reject', $record->id) }}" method="POST" class="inline">
                                                        @csrf
                                                        <button type="submit" class="text-red-600 hover:text-red-900">Reject</button>
                                                    </form>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </form>

                        <div class="mt-4">
                            {{ $records->links() }}
                        </div>
                    @else
                        <div class="text-center py-8 text-gray-500">
                            <p class="text-lg">No pending attendance records.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <script>
        function toggleSelectAll(checkbox) {
            const checkboxes = document.querySelectorAll('.record-checkbox');
            checkboxes.forEach(cb => cb.checked = checkbox.checked);
        }

        function selectAll() {
            const checkboxes = document.querySelectorAll('.record-checkbox');
            checkboxes.forEach(cb => cb.checked = true);
            document.getElementById('select-all-checkbox').checked = true;
        }

        function deselectAll() {
            const checkboxes = document.querySelectorAll('.record-checkbox');
            checkboxes.forEach(cb => cb.checked = false);
            document.getElementById('select-all-checkbox').checked = false;
        }

        function bulkApprove() {
            const form = document.getElementById('bulk-action-form');
            const checkedBoxes = document.querySelectorAll('.record-checkbox:checked');

            if (checkedBoxes.length === 0) {
                alert('Please select at least one record.');
                return;
            }

            if (confirm(`Approve ${checkedBoxes.length} selected record(s)?`)) {
                form.action = "{{ route('attendance.bulk.approve') }}";
                form.submit();
            }
        }

        function bulkReject() {
            const form = document.getElementById('bulk-action-form');
            const checkedBoxes = document.querySelectorAll('.record-checkbox:checked');

            if (checkedBoxes.length === 0) {
                alert('Please select at least one record.');
                return;
            }

            if (confirm(`Reject ${checkedBoxes.length} selected record(s)?`)) {
                form.action = "{{ route('attendance.bulk.reject') }}";
                form.submit();
            }
        }
    </script>
</x-app-layout>
