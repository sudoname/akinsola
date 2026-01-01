<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Apply for Scholarship') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">

            {{-- Success/Error Messages --}}
            @if(session('success'))
                <div class="mb-6 bg-green-50 dark:bg-green-900/10 border border-green-200 dark:border-green-800 rounded-lg p-4">
                    <div class="flex">
                        <svg class="h-5 w-5 text-green-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                        <p class="ml-3 text-sm text-green-800 dark:text-green-200">{{ session('success') }}</p>
                    </div>
                </div>
            @endif

            @if(session('error'))
                <div class="mb-6 bg-red-50 dark:bg-red-900/10 border border-red-200 dark:border-red-800 rounded-lg p-4">
                    <div class="flex">
                        <svg class="h-5 w-5 text-red-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                        </svg>
                        <p class="ml-3 text-sm text-red-800 dark:text-red-200">{{ session('error') }}</p>
                    </div>
                </div>
            @endif

            @if($errors->any())
                <div class="mb-6 bg-red-50 dark:bg-red-900/10 border border-red-200 dark:border-red-800 rounded-lg p-4">
                    <div class="flex">
                        <svg class="h-5 w-5 text-red-400 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                        </svg>
                        <div class="ml-3">
                            <p class="text-sm font-semibold text-red-800 dark:text-red-200 mb-2">Please fix the following errors:</p>
                            <ul class="list-disc list-inside text-sm text-red-700 dark:text-red-300 space-y-1">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            @endif

            {{-- Application Header --}}
            <div class="mb-6 bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">
                        {{ $cycle->title }}
                    </h3>
                    <div class="mt-2 flex items-center space-x-4 text-sm text-gray-600 dark:text-gray-400">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800 dark:bg-indigo-900 dark:text-indigo-200">
                            {{ ucfirst($track) }} Track
                        </span>
                        <span>Deadline: {{ $cycle->deadline_at->format('M d, Y') }}</span>
                    </div>
                    @if($application)
                        <div class="mt-3 p-3 bg-yellow-50 dark:bg-yellow-900/10 border border-yellow-200 dark:border-yellow-800 rounded-md">
                            <p class="text-sm text-yellow-800 dark:text-yellow-200">
                                You have a draft application for this track. Continue editing or submit when ready.
                            </p>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Application Form --}}
            <form method="POST" action="{{ route('applications.store', ['cycle' => $cycle->id, 'track' => $track]) }}" enctype="multipart/form-data" class="space-y-6">
                @csrf

                {{-- Education Information --}}
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4 pb-2 border-b border-gray-200 dark:border-gray-700">
                            Education Information
                        </h3>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            {{-- Institution Name --}}
                            <div class="md:col-span-2">
                                <label for="institution_name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                    Institution Name <span class="text-red-600">*</span>
                                </label>
                                <input type="text" name="institution_name" id="institution_name"
                                    value="{{ old('institution_name', $application?->educationRecord?->institution_name) }}" required
                                    class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                    placeholder="e.g., Ekiti State University">
                                @error('institution_name')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Level of Study --}}
                            <div>
                                <label for="level_of_study" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                    Level of Study <span class="text-red-600">*</span>
                                </label>
                                <input type="text" name="level_of_study" id="level_of_study"
                                    value="{{ old('level_of_study', $application?->educationRecord?->level_or_class) }}" required
                                    class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                    placeholder="e.g., 200L, SS2, ND2">
                                @error('level_of_study')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>

                            @if(in_array($track, ['university', 'polytechnic']))
                                {{-- Course of Study --}}
                                <div>
                                    <label for="course_of_study" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                        Course of Study <span class="text-red-600">*</span>
                                    </label>
                                    <input type="text" name="course_of_study" id="course_of_study"
                                        value="{{ old('course_of_study', $application?->educationRecord?->program) }}" required
                                        class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                        placeholder="e.g., Computer Science">
                                    @error('course_of_study')
                                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                    @enderror
                                </div>

                                {{-- Year of Study --}}
                                <div>
                                    <label for="year_of_study" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                        Year of Study <span class="text-red-600">*</span>
                                    </label>
                                    <input type="number" name="year_of_study" id="year_of_study" min="1" max="10"
                                        value="{{ old('year_of_study', $application?->educationRecord?->year_of_study) }}" required
                                        class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                        placeholder="1">
                                    @error('year_of_study')
                                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                    @enderror
                                </div>

                                {{-- Current GPA --}}
                                <div>
                                    <label for="current_gpa" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                        Current GPA/CGPA
                                    </label>
                                    <input type="number" name="current_gpa" id="current_gpa" step="0.01" min="0" max="5.0"
                                        value="{{ old('current_gpa', $application?->educationRecord?->cgpa) }}"
                                        class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                        placeholder="4.50">
                                    @error('current_gpa')
                                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                    @enderror
                                </div>

                                {{-- Graduation Year --}}
                                <div>
                                    <label for="graduation_year" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                        Expected Graduation Year <span class="text-red-600">*</span>
                                    </label>
                                    <input type="number" name="graduation_year" id="graduation_year"
                                        min="{{ date('Y') }}" max="{{ date('Y') + 10 }}"
                                        value="{{ old('graduation_year', $application?->educationRecord?->graduation_year) }}" required
                                        class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                        placeholder="{{ date('Y') + 4 }}">
                                    @error('graduation_year')
                                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                    @enderror
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- Scholarship Essay (Required) --}}
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4 pb-2 border-b border-gray-200 dark:border-gray-700">
                            Scholarship Essay <span class="text-red-600">*</span>
                        </h3>

                        <div class="mb-4 p-4 bg-indigo-50 dark:bg-indigo-900/20 rounded-lg border border-indigo-200 dark:border-indigo-800">
                            <p class="text-sm text-gray-700 dark:text-gray-300 mb-2">
                                <strong>Required:</strong> Upload a one-page essay (PDF format only) explaining why you should receive this scholarship.
                            </p>
                            <p class="text-xs text-gray-600 dark:text-gray-400">
                                Your essay should describe your academic goals, financial need, and how this scholarship will help you achieve your educational aspirations.
                            </p>
                        </div>

                        <div>
                            <label for="scholarship_essay" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                Upload Essay (PDF, max 10MB) <span class="text-red-600">*</span>
                            </label>
                            @if($application?->documents->where('type', 'scholarship_essay')->first())
                                <div class="mt-1 mb-2 text-xs text-green-600 dark:text-green-400">
                                    ✓ Already uploaded - upload again to replace
                                </div>
                            @endif
                            <input type="file" name="scholarship_essay" id="scholarship_essay" accept=".pdf"
                                class="mt-1 block w-full text-sm text-gray-500 dark:text-gray-400
                                    file:mr-4 file:py-2 file:px-4
                                    file:rounded-md file:border-0
                                    file:text-sm file:font-semibold
                                    file:bg-red-50 file:text-red-700
                                    hover:file:bg-red-100
                                    dark:file:bg-red-900/20 dark:file:text-red-400
                                    dark:hover:file:bg-red-900/30">
                            @error('scholarship_essay')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                {{-- Supporting Documents --}}
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4 pb-2 border-b border-gray-200 dark:border-gray-700">
                            Supporting Documents
                        </h3>

                        <p class="mb-4 text-sm text-gray-600 dark:text-gray-400">
                            Upload required documents (PDF, JPG, or PNG, max 10MB each)
                        </p>

                        <div class="space-y-4">
                            {{-- Academic Transcript --}}
                            <div>
                                <label for="academic_transcript" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                    Academic Transcript/Result
                                </label>
                                @if($application?->documents->where('type', 'academic_transcript')->first())
                                    <div class="mt-1 mb-2 text-xs text-green-600 dark:text-green-400">
                                        ✓ Already uploaded - upload again to replace
                                    </div>
                                @endif
                                <input type="file" name="academic_transcript" id="academic_transcript" accept=".pdf,.jpg,.jpeg,.png"
                                    class="mt-1 block w-full text-sm text-gray-500 dark:text-gray-400
                                        file:mr-4 file:py-2 file:px-4
                                        file:rounded-md file:border-0
                                        file:text-sm file:font-semibold
                                        file:bg-indigo-50 file:text-indigo-700
                                        hover:file:bg-indigo-100
                                        dark:file:bg-indigo-900/20 dark:file:text-indigo-400
                                        dark:hover:file:bg-indigo-900/30">
                                @error('academic_transcript')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>

                            @if(in_array($track, ['university', 'polytechnic']))
                                {{-- Admission Letter --}}
                                <div>
                                    <label for="admission_letter" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                        Admission Letter
                                    </label>
                                    @if($application?->documents->where('type', 'admission_letter')->first())
                                        <div class="mt-1 mb-2 text-xs text-green-600 dark:text-green-400">
                                            ✓ Already uploaded - upload again to replace
                                        </div>
                                    @endif
                                    <input type="file" name="admission_letter" id="admission_letter" accept=".pdf,.jpg,.jpeg,.png"
                                        class="mt-1 block w-full text-sm text-gray-500 dark:text-gray-400
                                            file:mr-4 file:py-2 file:px-4
                                            file:rounded-md file:border-0
                                            file:text-sm file:font-semibold
                                            file:bg-indigo-50 file:text-indigo-700
                                            hover:file:bg-indigo-100
                                            dark:file:bg-indigo-900/20 dark:file:text-indigo-400
                                            dark:hover:file:bg-indigo-900/30">
                                    @error('admission_letter')
                                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                    @enderror
                                </div>
                            @endif

                            {{-- School ID Card --}}
                            <div>
                                <label for="school_id_card" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                    School ID Card
                                </label>
                                @if($application?->documents->where('type', 'school_id_card')->first())
                                    <div class="mt-1 mb-2 text-xs text-green-600 dark:text-green-400">
                                        ✓ Already uploaded - upload again to replace
                                    </div>
                                @endif
                                <input type="file" name="school_id_card" id="school_id_card" accept=".pdf,.jpg,.jpeg,.png"
                                    class="mt-1 block w-full text-sm text-gray-500 dark:text-gray-400
                                        file:mr-4 file:py-2 file:px-4
                                        file:rounded-md file:border-0
                                        file:text-sm file:font-semibold
                                        file:bg-indigo-50 file:text-indigo-700
                                        hover:file:bg-indigo-100
                                        dark:file:bg-indigo-900/20 dark:file:text-indigo-400
                                        dark:hover:file:bg-indigo-900/30">
                                @error('school_id_card')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Birth Certificate --}}
                            <div>
                                <label for="birth_certificate" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                    Birth Certificate
                                </label>
                                @if($application?->documents->where('type', 'birth_certificate')->first())
                                    <div class="mt-1 mb-2 text-xs text-green-600 dark:text-green-400">
                                        ✓ Already uploaded - upload again to replace
                                    </div>
                                @endif
                                <input type="file" name="birth_certificate" id="birth_certificate" accept=".pdf,.jpg,.jpeg,.png"
                                    class="mt-1 block w-full text-sm text-gray-500 dark:text-gray-400
                                        file:mr-4 file:py-2 file:px-4
                                        file:rounded-md file:border-0
                                        file:text-sm file:font-semibold
                                        file:bg-indigo-50 file:text-indigo-700
                                        hover:file:bg-indigo-100
                                        dark:file:bg-indigo-900/20 dark:file:text-indigo-400
                                        dark:hover:file:bg-indigo-900/30">
                                @error('birth_certificate')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Other Supporting Documents --}}
                            <div>
                                <label for="other_supporting_documents" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                    Other Supporting Documents (Optional)
                                </label>
                                @if($application?->documents->where('type', 'other_supporting_documents')->first())
                                    <div class="mt-1 mb-2 text-xs text-green-600 dark:text-green-400">
                                        ✓ Already uploaded - upload again to replace
                                    </div>
                                @endif
                                <input type="file" name="other_supporting_documents" id="other_supporting_documents" accept=".pdf,.jpg,.jpeg,.png"
                                    class="mt-1 block w-full text-sm text-gray-500 dark:text-gray-400
                                        file:mr-4 file:py-2 file:px-4
                                        file:rounded-md file:border-0
                                        file:text-sm file:font-semibold
                                        file:bg-indigo-50 file:text-indigo-700
                                        hover:file:bg-indigo-100
                                        dark:file:bg-indigo-900/20 dark:file:text-indigo-400
                                        dark:hover:file:bg-indigo-900/30">
                                @error('other_supporting_documents')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Action Buttons --}}
                <div class="flex items-center justify-between pt-6">
                    <a href="{{ route('dashboard') }}" class="text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-200">
                        ← Back to Dashboard
                    </a>
                    <div class="flex space-x-4">
                        <button type="submit" name="action" value="draft" class="inline-flex items-center px-6 py-3 bg-gray-600 border border-transparent rounded-md font-semibold text-sm text-white uppercase tracking-widest hover:bg-gray-500 active:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                            Save as Draft
                        </button>
                        <button type="submit" name="action" value="submit" onclick="return confirm('Are you sure you want to submit this application? You cannot edit it after submission.');" class="inline-flex items-center px-6 py-3 bg-green-600 border border-transparent rounded-md font-semibold text-sm text-white uppercase tracking-widest hover:bg-green-500 active:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                            <svg class="mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            Submit Application
                        </button>
                    </div>
                </div>
            </form>

            {{-- Information Notice --}}
            <div class="bg-blue-50 dark:bg-blue-900/10 border border-blue-200 dark:border-blue-800 rounded-lg p-4">
                <div class="flex">
                    <svg class="h-5 w-5 text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                    </svg>
                    <div class="ml-3">
                        <p class="text-sm text-blue-800 dark:text-blue-200">
                            <strong>Note:</strong> You can save your application as a draft and come back later to edit it, or submit it directly. Once submitted, you cannot edit your application.
                        </p>
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
