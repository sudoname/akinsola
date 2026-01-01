<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Application Details') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- Application Header --}}
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="flex items-start justify-between">
                        <div>
                            <h3 class="text-2xl font-bold text-gray-900 dark:text-gray-100">
                                {{ $application->cycle->title }}
                            </h3>
                            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                                Track: <span class="font-medium">{{ ucfirst($application->track) }}</span>
                            </p>
                            <div class="mt-3 space-y-1 text-sm text-gray-600 dark:text-gray-400">
                                <p><span class="font-medium">Applicant:</span> {{ $application->user->name }}</p>
                                <p><span class="font-medium">Email:</span> {{ $application->user->email }}</p>
                                <p><span class="font-medium">Application ID:</span> #{{ $application->id }}</p>
                            </div>
                        </div>
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                            @if($application->status === 'approved') bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200
                            @elseif($application->status === 'rejected') bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200
                            @elseif($application->status === 'waitlisted') bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200
                            @elseif($application->status === 'under_review') bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200
                            @elseif($application->status === 'submitted') bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-200
                            @else bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-200
                            @endif">
                            {{ ucwords(str_replace('_', ' ', $application->status)) }}
                        </span>
                    </div>

                    {{-- Timeline --}}
                    <div class="mt-6 border-t border-gray-200 dark:border-gray-700 pt-6">
                        <h4 class="text-sm font-semibold text-gray-900 dark:text-gray-100 mb-4">Application Timeline</h4>
                        <div class="flow-root">
                            <ul class="-mb-8">
                                {{-- Created --}}
                                <li>
                                    <div class="relative pb-8">
                                        <span class="absolute top-4 left-4 -ml-px h-full w-0.5 bg-gray-200 dark:bg-gray-700" aria-hidden="true"></span>
                                        <div class="relative flex space-x-3">
                                            <div>
                                                <span class="h-8 w-8 rounded-full bg-gray-400 dark:bg-gray-600 flex items-center justify-center ring-8 ring-white dark:ring-gray-800">
                                                    <svg class="h-5 w-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-11a1 1 0 10-2 0v2H7a1 1 0 100 2h2v2a1 1 0 102 0v-2h2a1 1 0 100-2h-2V7z" clip-rule="evenodd"/>
                                                    </svg>
                                                </span>
                                            </div>
                                            <div class="min-w-0 flex-1 pt-1.5 flex justify-between space-x-4">
                                                <div>
                                                    <p class="text-sm text-gray-600 dark:text-gray-400">Application created</p>
                                                </div>
                                                <div class="text-right text-sm whitespace-nowrap text-gray-500 dark:text-gray-500">
                                                    {{ $application->created_at->format('M d, Y') }}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </li>

                                {{-- Submitted --}}
                                @if($application->submission_at)
                                    <li>
                                        <div class="relative pb-8">
                                            @if($application->status !== 'draft' && $application->status !== 'submitted')
                                                <span class="absolute top-4 left-4 -ml-px h-full w-0.5 bg-gray-200 dark:bg-gray-700" aria-hidden="true"></span>
                                            @endif
                                            <div class="relative flex space-x-3">
                                                <div>
                                                    <span class="h-8 w-8 rounded-full bg-purple-500 dark:bg-purple-600 flex items-center justify-center ring-8 ring-white dark:ring-gray-800">
                                                        <svg class="h-5 w-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                                            <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z"/>
                                                            <path fill-rule="evenodd" d="M4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm9.707 5.707a1 1 0 00-1.414-1.414L9 12.586l-1.293-1.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                                        </svg>
                                                    </span>
                                                </div>
                                                <div class="min-w-0 flex-1 pt-1.5 flex justify-between space-x-4">
                                                    <div>
                                                        <p class="text-sm text-gray-600 dark:text-gray-400">Application submitted</p>
                                                    </div>
                                                    <div class="text-right text-sm whitespace-nowrap text-gray-500 dark:text-gray-500">
                                                        {{ $application->submission_at->format('M d, Y') }}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                @endif

                                {{-- Under Review --}}
                                @if(in_array($application->status, ['under_review', 'decision_pending_release', 'approved', 'rejected', 'waitlisted']))
                                    <li>
                                        <div class="relative pb-8">
                                            @if(in_array($application->status, ['decision_pending_release', 'approved', 'rejected', 'waitlisted']))
                                                <span class="absolute top-4 left-4 -ml-px h-full w-0.5 bg-gray-200 dark:bg-gray-700" aria-hidden="true"></span>
                                            @endif
                                            <div class="relative flex space-x-3">
                                                <div>
                                                    <span class="h-8 w-8 rounded-full bg-blue-500 dark:bg-blue-600 flex items-center justify-center ring-8 ring-white dark:ring-gray-800">
                                                        <svg class="h-5 w-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                                            <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z"/>
                                                            <path fill-rule="evenodd" d="M4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm3 4a1 1 0 000 2h.01a1 1 0 100-2H7zm3 0a1 1 0 000 2h3a1 1 0 100-2h-3zm-3 4a1 1 0 100 2h.01a1 1 0 100-2H7zm3 0a1 1 0 100 2h3a1 1 0 100-2h-3z" clip-rule="evenodd"/>
                                                        </svg>
                                                    </span>
                                                </div>
                                                <div class="min-w-0 flex-1 pt-1.5 flex justify-between space-x-4">
                                                    <div>
                                                        <p class="text-sm text-gray-600 dark:text-gray-400">Under review</p>
                                                        @if($application->reviewer)
                                                            <p class="text-xs text-gray-500 dark:text-gray-500 mt-1">
                                                                Reviewer: {{ $application->reviewer->name }}
                                                            </p>
                                                        @endif
                                                    </div>
                                                    <div class="text-right text-sm whitespace-nowrap text-gray-500 dark:text-gray-500">
                                                        In progress
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                @endif

                                {{-- Decision --}}
                                @if($application->isDecisionVisible())
                                    <li>
                                        <div class="relative pb-8">
                                            <div class="relative flex space-x-3">
                                                <div>
                                                    <span class="h-8 w-8 rounded-full flex items-center justify-center ring-8 ring-white dark:ring-gray-800
                                                        @if($application->status === 'approved') bg-green-500 dark:bg-green-600
                                                        @elseif($application->status === 'rejected') bg-red-500 dark:bg-red-600
                                                        @else bg-yellow-500 dark:bg-yellow-600
                                                        @endif">
                                                        @if($application->status === 'approved')
                                                            <svg class="h-5 w-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                                            </svg>
                                                        @elseif($application->status === 'rejected')
                                                            <svg class="h-5 w-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                                                            </svg>
                                                        @else
                                                            <svg class="h-5 w-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8 7a1 1 0 000 2h.01a1 1 0 100-2H8zm0 4a1 1 0 000 2h4a1 1 0 100-2H8z" clip-rule="evenodd"/>
                                                            </svg>
                                                        @endif
                                                    </span>
                                                </div>
                                                <div class="min-w-0 flex-1 pt-1.5">
                                                    <p class="text-sm font-medium
                                                        @if($application->status === 'approved') text-green-800 dark:text-green-200
                                                        @elseif($application->status === 'rejected') text-red-800 dark:text-red-200
                                                        @else text-yellow-800 dark:text-yellow-200
                                                        @endif">
                                                        {{ ucwords($application->status) }}
                                                    </p>
                                                    @if($application->decision_reason)
                                                        <p class="text-xs text-gray-600 dark:text-gray-400 mt-1">
                                                            {{ ucwords(str_replace('_', ' ', $application->decision_reason)) }}
                                                        </p>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                @endif
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Education Record --}}
            @if($application->educationRecord)
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">
                            Education Information
                        </h3>
                        <dl class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Institution</dt>
                                <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $application->educationRecord->institution_name ?: 'Not provided' }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Level of Study</dt>
                                <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $application->educationRecord->level_or_class ? ucfirst($application->educationRecord->level_or_class) : 'Not provided' }}</dd>
                            </div>
                            @if(in_array($application->track, ['university', 'polytechnic']))
                                <div>
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Course of Study</dt>
                                    <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $application->educationRecord->program ?: 'Not provided' }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Year of Study</dt>
                                    <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $application->educationRecord->year_of_study ? 'Year ' . $application->educationRecord->year_of_study : 'Not provided' }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Current GPA/CGPA</dt>
                                    <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $application->educationRecord->cgpa ?: 'Not provided' }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Expected Graduation</dt>
                                    <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $application->educationRecord->graduation_year ?: 'Not provided' }}</dd>
                                </div>
                            @endif
                        </dl>
                    </div>
                </div>
            @endif

            {{-- Uploaded Documents --}}
            @if($application->documents->isNotEmpty())
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">
                            Uploaded Documents
                        </h3>
                        <div class="space-y-3">
                            @foreach($application->documents as $document)
                                <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-900 rounded-lg border border-gray-200 dark:border-gray-700">
                                    <div class="flex items-center">
                                        <svg class="h-8 w-8 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z" clip-rule="evenodd"/>
                                        </svg>
                                        <div class="ml-3">
                                            <p class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                                {{ ucwords(str_replace('_', ' ', $document->type)) }}
                                            </p>
                                            <p class="text-xs text-gray-500 dark:text-gray-500">
                                                Uploaded {{ $document->uploaded_at ? $document->uploaded_at->format('M d, Y') : 'N/A' }}
                                            </p>
                                        </div>
                                    </div>
                                    <a href="{{ Storage::url($document->url) }}" target="_blank" class="inline-flex items-center px-3 py-2 border border-gray-300 dark:border-gray-600 shadow-sm text-sm leading-4 font-medium rounded-md text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                                        <svg class="mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                        </svg>
                                        View
                                    </a>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif

            {{-- Decision Details (if visible) --}}
            @if($application->isDecisionVisible())
                <div class="p-4 rounded-lg
                    @if($application->status === 'approved') bg-green-50 dark:bg-green-900/10 border border-green-200 dark:border-green-800
                    @elseif($application->status === 'rejected') bg-red-50 dark:bg-red-900/10 border border-red-200 dark:border-red-800
                    @else bg-yellow-50 dark:bg-yellow-900/10 border border-yellow-200 dark:border-yellow-800
                    @endif">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            @if($application->status === 'approved')
                                <svg class="h-5 w-5 text-green-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                </svg>
                            @elseif($application->status === 'rejected')
                                <svg class="h-5 w-5 text-red-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                                </svg>
                            @else
                                <svg class="h-5 w-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                                </svg>
                            @endif
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium
                                @if($application->status === 'approved') text-green-800 dark:text-green-200
                                @elseif($application->status === 'rejected') text-red-800 dark:text-red-200
                                @else text-yellow-800 dark:text-yellow-200
                                @endif">
                                @if($application->status === 'approved')
                                    Congratulations! Your application has been approved.
                                @elseif($application->status === 'rejected')
                                    Application Not Approved
                                @else
                                    You have been placed on the waitlist.
                                @endif
                            </h3>
                            <div class="mt-2 text-sm
                                @if($application->status === 'approved') text-green-700 dark:text-green-300
                                @elseif($application->status === 'rejected') text-red-700 dark:text-red-300
                                @else text-yellow-700 dark:text-yellow-300
                                @endif">
                                @if($application->status === 'approved')
                                    <p>You have been selected for the {{ $application->cycle->title }}. Further instructions will be sent to your registered email address.</p>
                                    @if($application->scholarship_amount)
                                        <p class="mt-2 font-semibold">
                                            Scholarship Amount: ₦{{ number_format($application->scholarship_amount, 2) }}
                                        </p>
                                    @endif
                                @elseif($application->status === 'rejected')
                                    <p>After careful review, we regret to inform you that your application was not approved for this cycle. We encourage you to apply in future cycles.</p>
                                @else
                                    <p>Your application is currently on the waitlist. We will notify you if a spot becomes available.</p>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            {{-- Back Button --}}
            <div class="flex justify-between items-center">
                <a href="{{ route('dashboard') }}" class="inline-flex items-center text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-200">
                    <svg class="mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    Back to Dashboard
                </a>
            </div>

        </div>
    </div>
</x-app-layout>
