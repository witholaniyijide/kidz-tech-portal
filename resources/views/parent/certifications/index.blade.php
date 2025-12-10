<x-parent-layout>
    <x-slot name="title">Certifications</x-slot>
    <x-slot name="subtitle">View and download earned certificates</x-slot>

    <div class="space-y-6">
        <!-- Filter by Child -->
        @if($children->count() > 1)
            <div class="glass-card rounded-2xl p-4">
                <div class="flex items-center justify-between flex-wrap gap-3">
                    <span class="text-sm font-medium text-gray-600 dark:text-gray-400">Filter by Child:</span>
                    <div class="flex items-center space-x-2 flex-wrap gap-2">
                        <a href="{{ route('parent.certifications.index') }}"
                           class="px-4 py-2 rounded-xl text-sm font-medium transition-all duration-200
                                  {{ !$selectedChildId ? 'bg-parent-gradient text-white shadow-lg' : 'bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-600' }}">
                            All Children
                        </a>
                        @foreach($children as $child)
                            <a href="{{ route('parent.certifications.index', ['student_id' => $child->id]) }}"
                               class="flex items-center space-x-2 px-4 py-2 rounded-xl text-sm font-medium transition-all duration-200
                                      {{ $selectedChildId == $child->id
                                         ? 'bg-parent-gradient text-white shadow-lg'
                                         : 'bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-600' }}">
                                {{ $child->first_name }}
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>
        @endif

        <!-- Certifications Grid -->
        @if($certifications->count() > 0)
            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($certifications as $cert)
                    <div class="glass-card rounded-2xl overflow-hidden hover-lift">
                        <!-- Certificate Preview -->
                        <div class="bg-gradient-to-br from-amber-100 to-orange-100 dark:from-amber-900/30 dark:to-orange-900/30 p-6 text-center">
                            <div class="w-20 h-20 mx-auto mb-4 rounded-full bg-amber-500 flex items-center justify-center">
                                <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/>
                                </svg>
                            </div>
                            <h3 class="font-heading font-bold text-gray-800 dark:text-white mb-1">{{ $cert->title }}</h3>
                            @if($cert->course_name)
                                <p class="text-sm text-gray-600 dark:text-gray-400">{{ $cert->course_name }}</p>
                            @endif
                        </div>

                        <!-- Certificate Details -->
                        <div class="p-5">
                            <div class="space-y-2 mb-4">
                                <div class="flex items-center justify-between text-sm">
                                    <span class="text-gray-500 dark:text-gray-400">Student</span>
                                    <span class="font-medium text-gray-800 dark:text-white">{{ $cert->student->first_name ?? 'Unknown' }}</span>
                                </div>
                                <div class="flex items-center justify-between text-sm">
                                    <span class="text-gray-500 dark:text-gray-400">Issued</span>
                                    <span class="font-medium text-gray-800 dark:text-white">{{ $cert->issue_date->format('M d, Y') }}</span>
                                </div>
                                <div class="flex items-center justify-between text-sm">
                                    <span class="text-gray-500 dark:text-gray-400">Certificate ID</span>
                                    <span class="font-mono text-xs text-gray-600 dark:text-gray-400">{{ $cert->certificate_id }}</span>
                                </div>
                            </div>

                            <div class="flex space-x-2">
                                <a href="{{ route('parent.certifications.view', $cert) }}"
                                   target="_blank"
                                   class="flex-1 flex items-center justify-center space-x-2 px-4 py-2 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-200 dark:hover:bg-gray-600 transition-colors text-sm font-medium">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                    </svg>
                                    <span>View</span>
                                </a>
                                <a href="{{ route('parent.certifications.download', $cert) }}"
                                   class="flex-1 flex items-center justify-center space-x-2 px-4 py-2 bg-amber-500 text-white rounded-lg hover:bg-amber-600 transition-colors text-sm font-medium">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                                    </svg>
                                    <span>Download</span>
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="mt-6">
                {{ $certifications->links() }}
            </div>
        @else
            <div class="glass-card rounded-2xl p-12 text-center">
                <svg class="w-16 h-16 mx-auto text-gray-300 dark:text-gray-600 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/>
                </svg>
                <h3 class="text-xl font-heading font-bold text-gray-800 dark:text-white mb-2">No Certifications Yet</h3>
                <p class="text-gray-500 dark:text-gray-400">
                    Certificates will appear here once your child earns them.
                </p>
            </div>
        @endif

        <!-- Validate Certificate Section -->
        <div class="glass-card rounded-2xl p-6">
            <h3 class="text-lg font-heading font-bold text-gray-800 dark:text-white mb-4">Validate a Certificate</h3>
            <form id="validate-form" class="flex flex-col sm:flex-row gap-3">
                <input type="text"
                       id="certificate-id"
                       placeholder="Enter Certificate ID (e.g., KTCC-2024-ABC123)"
                       class="flex-1 px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-800 text-gray-800 dark:text-white focus:ring-2 focus:ring-sky-500 focus:border-transparent">
                <button type="submit"
                        class="px-6 py-2 bg-sky-500 text-white rounded-lg hover:bg-sky-600 transition-colors font-medium">
                    Validate
                </button>
            </form>
            <div id="validation-result" class="mt-4 hidden"></div>
        </div>
    </div>

    @push('scripts')
    <script>
        document.getElementById('validate-form').addEventListener('submit', function(e) {
            e.preventDefault();
            const certificateId = document.getElementById('certificate-id').value;
            const resultDiv = document.getElementById('validation-result');

            if (!certificateId) return;

            fetch('{{ route("parent.certifications.validate") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ certificate_id: certificateId })
            })
            .then(response => response.json())
            .then(data => {
                resultDiv.classList.remove('hidden');
                if (data.valid) {
                    resultDiv.innerHTML = `
                        <div class="p-4 bg-emerald-50 dark:bg-emerald-900/30 border border-emerald-200 dark:border-emerald-800 rounded-lg">
                            <div class="flex items-center space-x-2 text-emerald-700 dark:text-emerald-400 mb-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                <span class="font-semibold">Valid Certificate</span>
                            </div>
                            <div class="text-sm text-gray-600 dark:text-gray-400 space-y-1">
                                <p><strong>Title:</strong> ${data.certificate.title}</p>
                                <p><strong>Student:</strong> ${data.certificate.student_name}</p>
                                <p><strong>Issued:</strong> ${data.certificate.issue_date}</p>
                            </div>
                        </div>
                    `;
                } else {
                    resultDiv.innerHTML = `
                        <div class="p-4 bg-red-50 dark:bg-red-900/30 border border-red-200 dark:border-red-800 rounded-lg">
                            <div class="flex items-center space-x-2 text-red-700 dark:text-red-400">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                <span class="font-semibold">${data.message}</span>
                            </div>
                        </div>
                    `;
                }
            })
            .catch(error => {
                resultDiv.classList.remove('hidden');
                resultDiv.innerHTML = `
                    <div class="p-4 bg-red-50 dark:bg-red-900/30 border border-red-200 dark:border-red-800 rounded-lg">
                        <p class="text-red-700 dark:text-red-400">An error occurred. Please try again.</p>
                    </div>
                `;
            });
        });
    </script>
    @endpush
</x-parent-layout>
