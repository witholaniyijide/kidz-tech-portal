<x-tutor-layout>
    <!-- Page Header -->
    <div class="mb-8">
        <h1 class="text-4xl font-bold text-gray-900 dark:text-white mb-2">
            My Students
        </h1>
        <p class="text-gray-600 dark:text-gray-400">
            View and manage your assigned students
        </p>
    </div>

    <!-- Students Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($students as $student)
            <a href="{{ route('tutor.students.show', $student) }}"
                class="bg-white/20 dark:bg-gray-900/30 backdrop-blur-xl border border-white/10 rounded-2xl p-6 shadow-lg hover:shadow-xl hover:-translate-y-1 transition-all">

                <!-- Student Name -->
                <div class="flex items-center mb-4">
                    <div class="w-12 h-12 bg-gradient-to-r from-purple-500 to-pink-500 rounded-full flex items-center justify-center text-white font-bold text-lg mr-3">
                        {{ substr($student->first_name, 0, 1) }}{{ substr($student->last_name, 0, 1) }}
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                            {{ $student->fullName() }}
                        </h3>
                        <p class="text-sm text-gray-600 dark:text-gray-400">
                            {{ $student->student_id }}
                        </p>
                    </div>
                </div>

                <!-- Student Stats -->
                <div class="space-y-2 text-sm">
                    <div class="flex justify-between">
                        <span class="text-gray-600 dark:text-gray-400">Status:</span>
                        <span class="font-medium text-gray-900 dark:text-white capitalize">{{ $student->status }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600 dark:text-gray-400">Reports:</span>
                        <span class="font-medium text-gray-900 dark:text-white">{{ $student->tutorReports->count() }}</span>
                    </div>
                </div>
            </a>
        @empty
            <div class="col-span-full text-center py-12">
                <p class="text-gray-600 dark:text-gray-400">No students assigned yet.</p>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if($students->hasPages())
        <div class="mt-8">
            {{ $students->links() }}
        </div>
    @endif
</x-tutor-layout>
