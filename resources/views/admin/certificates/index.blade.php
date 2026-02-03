<x-app-layout>
    <x-slot name="header">{{ __('Create Certificate') }}</x-slot>
    <x-slot name="title">{{ __('Admin - Create Certificate') }}</x-slot>

    <div class="min-h-screen bg-gradient-to-br from-[#423A8E]/5 via-[#00CCCD]/5 to-blue-50 dark:from-slate-900 dark:via-slate-800 dark:to-slate-900 py-8 relative overflow-hidden">
        <div class="absolute top-0 left-0 w-72 h-72 bg-[#423A8E]/30 dark:bg-[#423A8E]/30 rounded-full mix-blend-multiply filter blur-xl opacity-70 animate-float"></div>
        <div class="absolute top-0 right-0 w-72 h-72 bg-[#00CCCD]/30 dark:bg-[#00CCCD]/40 rounded-full mix-blend-multiply filter blur-xl opacity-70 animate-float" style="animation-delay: 2s;"></div>

        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            {{-- Header --}}
            <div class="flex items-center justify-between mb-8">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Create Certificate</h1>
                    <p class="text-gray-600 dark:text-gray-400 mt-1">Generate a certificate for a student</p>
                </div>
                <a href="{{ route('admin.dashboard') }}" class="inline-flex items-center px-4 py-2 bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-300 dark:hover:bg-gray-600 transition-colors">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    Back
                </a>
            </div>

            {{-- Success Message with Certificate Details --}}
            @if(session('success') && session('certificate_id'))
                <div class="mb-6 bg-emerald-100 dark:bg-emerald-900/30 border border-emerald-400 rounded-2xl overflow-hidden">
                    <div class="px-6 py-4 bg-gradient-to-r from-emerald-500 to-teal-600 text-white">
                        <div class="flex items-center">
                            <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <span class="font-semibold">Certificate Created Successfully!</span>
                        </div>
                    </div>
                    <div class="p-6 space-y-4">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <p class="text-sm text-gray-600 dark:text-gray-400">Student Name</p>
                                <p class="font-semibold text-gray-900 dark:text-white">{{ session('student_name') }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600 dark:text-gray-400">Course</p>
                                <p class="font-semibold text-gray-900 dark:text-white">{{ session('course_name') }}</p>
                            </div>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600 dark:text-gray-400">Certificate ID</p>
                            <p class="font-mono font-bold text-lg text-[#423A8E] dark:text-[#00CCCD]">{{ session('certificate_id') }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600 dark:text-gray-400 mb-2">Verification URL</p>
                            <div class="flex items-center gap-2">
                                <input type="text" id="verification-url" value="{{ session('verification_url') }}" readonly
                                       class="flex-1 px-4 py-2 bg-gray-100 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg font-mono text-sm text-gray-700 dark:text-gray-300">
                                <button onclick="copyToClipboard()" id="copy-btn"
                                        class="px-4 py-2 bg-gradient-to-r from-[#423A8E] to-[#00CCCD] text-white rounded-lg hover:shadow-lg transition-all flex items-center gap-2">
                                    <svg id="copy-icon" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2m0 0h2a2 2 0 012 2v3m2 4H10m0 0l3-3m-3 3l3 3"/>
                                    </svg>
                                    <svg id="check-icon" class="w-5 h-5 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                    </svg>
                                    <span id="copy-text">Copy</span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            {{-- Error Message --}}
            @if(session('error'))
                <div class="mb-6 bg-red-100 dark:bg-red-900/30 border border-red-400 text-red-800 dark:text-red-400 px-6 py-4 rounded-xl">
                    {{ session('error') }}
                </div>
            @endif

            {{-- Validation Errors --}}
            @if($errors->any())
                <div class="mb-6 bg-red-100 dark:bg-red-900/30 border border-red-400 text-red-800 dark:text-red-400 px-6 py-4 rounded-xl">
                    <ul class="list-disc list-inside">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('admin.certificates.store') }}" method="POST">
                @csrf

                {{-- Certificate Details --}}
                <div class="bg-white/60 dark:bg-gray-800/60 backdrop-blur-md border border-white/20 rounded-2xl shadow overflow-hidden mb-6">
                    <div class="px-6 py-4 bg-gradient-to-r from-[#423A8E] to-[#00CCCD] text-white">
                        <h3 class="text-lg font-semibold">Certificate Details</h3>
                    </div>
                    <div class="p-6 space-y-6">
                        {{-- Student Selection --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Student <span class="text-red-500">*</span></label>
                            <select name="student_id" required
                                    class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg focus:ring-2 focus:ring-[#423A8E]">
                                <option value="">Select a student...</option>
                                @foreach($students as $student)
                                    <option value="{{ $student['id'] }}" {{ old('student_id') == $student['id'] ? 'selected' : '' }}>
                                        {{ $student['name'] }}
                                    </option>
                                @endforeach
                            </select>
                            @error('student_id')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Course Selection --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Course <span class="text-red-500">*</span></label>
                            <select name="course_code" required
                                    class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg focus:ring-2 focus:ring-[#423A8E]">
                                <option value="">Select a course...</option>
                                @foreach($courses as $code => $name)
                                    <option value="{{ $code }}" {{ old('course_code') == $code ? 'selected' : '' }}>
                                        {{ $code }} - {{ $name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('course_code')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                {{-- Certificate ID Format Info --}}
                <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-xl p-4 mb-6">
                    <div class="flex items-start">
                        <svg class="w-5 h-5 text-blue-500 mr-3 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <div>
                            <p class="text-sm font-medium text-blue-800 dark:text-blue-300">Certificate ID Format</p>
                            <p class="text-sm text-blue-600 dark:text-blue-400 mt-1">
                                Certificates are generated with the format: <span class="font-mono font-bold">KTCC-YYYY-COURSECODE-XXXX</span>
                            </p>
                            <p class="text-sm text-blue-600 dark:text-blue-400 mt-1">
                                Example: <span class="font-mono">KTCC-2025-SCR-0001</span>
                            </p>
                        </div>
                    </div>
                </div>

                {{-- Submit Buttons --}}
                <div class="flex justify-end gap-4">
                    <a href="{{ route('admin.dashboard') }}" class="px-6 py-3 bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-xl hover:bg-gray-300 dark:hover:bg-gray-600 transition-colors font-medium">
                        Cancel
                    </a>
                    <button type="submit" class="px-8 py-3 bg-gradient-to-r from-[#423A8E] to-[#00CCCD] text-white rounded-xl hover:shadow-lg transform hover:-translate-y-0.5 transition-all font-medium">
                        Generate Certificate
                    </button>
                </div>
            </form>
        </div>
    </div>

    @push('styles')
    <style>
        @keyframes float { 0%, 100% { transform: translateY(0px); } 50% { transform: translateY(-20px); } }
        .animate-float { animation: float 6s ease-in-out infinite; }
    </style>
    @endpush

    @push('scripts')
    <script>
        function copyToClipboard() {
            const urlInput = document.getElementById('verification-url');
            const copyBtn = document.getElementById('copy-btn');
            const copyIcon = document.getElementById('copy-icon');
            const checkIcon = document.getElementById('check-icon');
            const copyText = document.getElementById('copy-text');

            // Try modern clipboard API first
            if (navigator.clipboard && window.isSecureContext) {
                navigator.clipboard.writeText(urlInput.value).then(function() {
                    showCopiedState();
                }).catch(function() {
                    fallbackCopy();
                });
            } else {
                fallbackCopy();
            }

            function fallbackCopy() {
                // Fallback for HTTP or older browsers
                const textArea = document.createElement('textarea');
                textArea.value = urlInput.value;
                textArea.style.position = 'fixed';
                textArea.style.left = '-999999px';
                textArea.style.top = '-999999px';
                document.body.appendChild(textArea);
                textArea.focus();
                textArea.select();

                try {
                    document.execCommand('copy');
                    showCopiedState();
                } catch (err) {
                    alert('Failed to copy. Please copy manually.');
                }

                document.body.removeChild(textArea);
            }

            function showCopiedState() {
                copyIcon.classList.add('hidden');
                checkIcon.classList.remove('hidden');
                copyText.textContent = 'Copied!';

                setTimeout(function() {
                    copyIcon.classList.remove('hidden');
                    checkIcon.classList.add('hidden');
                    copyText.textContent = 'Copy';
                }, 2000);
            }
        }
    </script>
    @endpush
</x-app-layout>
