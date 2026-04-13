<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Applicant Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- Flash Messages --}}
            @if(session('success'))
                <div class="bg-green-50 dark:bg-green-900/10 border border-green-200 dark:border-green-800 rounded-lg p-4">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 text-green-600 dark:text-green-400 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                        <p class="text-sm font-medium text-green-800 dark:text-green-200">{{ session('success') }}</p>
                    </div>
                </div>
            @endif

            @if(session('error'))
                <div class="bg-red-50 dark:bg-red-900/10 border border-red-200 dark:border-red-800 rounded-lg p-4">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 text-red-600 dark:text-red-400 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                        </svg>
                        <p class="text-sm font-medium text-red-800 dark:text-red-200">{{ session('error') }}</p>
                    </div>
                </div>
            @endif

            {{-- Profile Completeness Card --}}
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">
                                Your Profile
                            </h3>
                            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                                @if($profile->isComplete())
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                        </svg>
                                        Profile Complete - Ready to Apply!
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200">
                                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                        </svg>
                                        Action Required
                                    </span>
                                @endif
                            </p>
                        </div>
                        <a href="{{ route('applicant.profile') }}" class="inline-flex items-center px-4 py-2 bg-gray-800 dark:bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-white dark:text-gray-800 uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-white focus:bg-gray-700 dark:focus:bg-white active:bg-gray-900 dark:active:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                            {{ $profile->isComplete() ? 'Update Profile' : 'Complete Profile' }}
                        </a>
                    </div>
                    @if(!$profile->isComplete())
                        <div class="mt-4 p-4 bg-yellow-50 dark:bg-yellow-900/10 border border-yellow-200 dark:border-yellow-800 rounded-md">
                            <p class="text-sm font-semibold text-yellow-800 dark:text-yellow-200 mb-2">
                                You must complete your profile before you can apply
                            </p>
                            <p class="text-sm text-yellow-700 dark:text-yellow-300">
                                We need: Your date of birth, contact information, and indigene certificate. Click "Complete Profile" above to finish.
                            </p>
                        </div>
                    @endif
                </div>
            </div>

            {{-- My Applications --}}
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">
                        My Applications
                    </h3>

                    @if($applications->isEmpty())
                        <div class="text-center py-8">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                            <p class="mt-2 text-base font-medium text-gray-900 dark:text-gray-100">
                                You haven't applied yet
                            </p>
                            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                                When scholarship cycles are open, you'll see them below. Apply from there!
                            </p>
                        </div>
                    @else
                        <div class="space-y-4">
                            @foreach($applications as $application)
                                <div class="border border-gray-200 dark:border-gray-700 rounded-lg p-4 hover:border-gray-300 dark:hover:border-gray-600 transition">
                                    <div class="flex items-start justify-between">
                                        <div class="flex-1">
                                            <div class="flex items-center space-x-2">
                                                <h4 class="text-base font-medium text-gray-900 dark:text-gray-100">
                                                    {{ $application->cycle->title }}
                                                </h4>
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                                    @if($application->status === 'approved') bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200
                                                    @elseif($application->status === 'rejected') bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200
                                                    @elseif($application->status === 'waitlisted') bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200
                                                    @elseif($application->status === 'under_review') bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200
                                                    @else bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-200
                                                    @endif">
                                                    {{ ucwords(str_replace('_', ' ', $application->status)) }}
                                                </span>
                                            </div>
                                            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                                                Track: <span class="font-medium">{{ ucfirst($application->track) }}</span>
                                            </p>
                                            @if($application->submission_at)
                                                <p class="mt-1 text-xs text-gray-500 dark:text-gray-500">
                                                    Submitted: {{ $application->submission_at->format('M d, Y') }}
                                                </p>
                                            @endif

                                            {{-- Decision Visibility --}}
                                            @if($application->isDecisionVisible())
                                                <div class="mt-3 p-3 rounded-md
                                                    @if($application->status === 'approved') bg-green-50 dark:bg-green-900/10 border border-green-200 dark:border-green-800
                                                    @elseif($application->status === 'rejected') bg-red-50 dark:bg-red-900/10 border border-red-200 dark:border-red-800
                                                    @else bg-yellow-50 dark:bg-yellow-900/10 border border-yellow-200 dark:border-yellow-800
                                                    @endif">
                                                    <p class="text-sm font-semibold
                                                        @if($application->status === 'approved') text-green-800 dark:text-green-200
                                                        @elseif($application->status === 'rejected') text-red-800 dark:text-red-200
                                                        @else text-yellow-800 dark:text-yellow-200
                                                        @endif">
                                                        @if($application->status === 'approved')
                                                            🎉 Congratulations! You won the scholarship!
                                                        @elseif($application->status === 'rejected')
                                                            We're sorry - you were not selected this time.
                                                        @else
                                                            You're on the waitlist - we may still award you!
                                                        @endif
                                                    </p>
                                                    @if($application->decision_reason)
                                                        <p class="mt-1 text-xs
                                                            @if($application->status === 'approved') text-green-700 dark:text-green-300
                                                            @elseif($application->status === 'rejected') text-red-700 dark:text-red-300
                                                            @else text-yellow-700 dark:text-yellow-300
                                                            @endif">
                                                            Reason: {{ ucwords(str_replace('_', ' ', $application->decision_reason)) }}
                                                        </p>
                                                    @endif
                                                </div>
                                            @endif
                                        </div>
                                        <a href="{{ route('applications.show', $application) }}" class="ml-4 inline-flex items-center px-3 py-2 border border-gray-300 dark:border-gray-600 shadow-sm text-sm leading-4 font-medium rounded-md text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                            View Details
                                        </a>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>

            {{-- Bank Account Information for Approved Applications --}}
            @foreach($applications->where('status', 'approved') as $approvedApp)
                @include('applicant.partials.bank-account-form', ['application' => $approvedApp])
                @include('applicant.partials.payment-tracking', ['application' => $approvedApp])
            @endforeach

            {{-- Active Cycles --}}
            @if($activeCycles->isNotEmpty())
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">
                            Apply Now - Current Scholarship Cycle
                        </h3>

                        <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-3">
                            @foreach($activeCycles as $cycle)
                                <div class="border border-gray-200 dark:border-gray-700 rounded-lg p-4">
                                    <h4 class="font-medium text-gray-900 dark:text-gray-100">
                                        {{ $cycle->title }}
                                    </h4>
                                    <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                                        Deadline: {{ $cycle->deadline_at->format('M d, Y') }}
                                    </p>
                                    <div class="mt-3 space-y-2">
                                        <p class="text-xs font-medium text-gray-700 dark:text-gray-300">Available Tracks:</p>
                                        @if($profile->isComplete())
                                            @foreach($cycle->tracks_json as $cycleTrack)
                                                <a href="{{ route('applications.create', ['cycle' => $cycle->id, 'track' => $cycleTrack]) }}" class="block w-full text-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-500 active:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                                                    Apply: {{ ucfirst($cycleTrack) }}
                                                </a>
                                            @endforeach
                                        @else
                                            <button disabled class="w-full inline-flex justify-center items-center px-4 py-2 bg-gray-300 dark:bg-gray-700 border border-transparent rounded-md font-semibold text-xs text-gray-500 dark:text-gray-400 uppercase tracking-widest cursor-not-allowed">
                                                Complete Profile First
                                            </button>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif

        </div>
    </div>
</x-app-layout>
