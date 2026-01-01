<x-public-layout>
    <div class="py-12 bg-white dark:bg-gray-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="text-center mb-12">
                <h1 class="text-4xl font-extrabold text-gray-900 dark:text-gray-100 sm:text-5xl">
                    Past Scholarship Winners
                </h1>
                <p class="mt-4 text-xl text-gray-600 dark:text-gray-400">
                    Meet the students we've helped. You could be next!
                </p>
            </div>

            @if($cycles->isEmpty())
                <!-- No Results Available -->
                <div class="text-center py-12">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <h3 class="mt-2 text-lg font-medium text-gray-900 dark:text-gray-100">No Results Published Yet</h3>
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                        Scholarship results will be displayed here after they are officially released.
                    </p>
                    <div class="mt-6">
                        <a href="{{ route('home') }}" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            <svg class="-ml-1 mr-2 h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M9.707 14.707a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 1.414L7.414 9H15a1 1 0 110 2H7.414l2.293 2.293a1 1 0 010 1.414z" clip-rule="evenodd"/>
                            </svg>
                            Back to Home
                        </a>
                    </div>
                </div>
            @else
                <!-- Stats Overview -->
                <div class="mb-12 grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-3">
                    <div class="bg-white dark:bg-gray-900 overflow-hidden shadow rounded-lg">
                        <div class="p-5">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <svg class="h-6 w-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                                    </svg>
                                </div>
                                <div class="ml-5 w-0 flex-1">
                                    <dl>
                                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 truncate">
                                            Total Winners
                                        </dt>
                                        <dd class="text-3xl font-semibold text-gray-900 dark:text-gray-100">
                                            {{ $awardeesByCycle->flatten()->count() }}
                                        </dd>
                                    </dl>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white dark:bg-gray-900 overflow-hidden shadow rounded-lg">
                        <div class="p-5">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <svg class="h-6 w-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                    </svg>
                                </div>
                                <div class="ml-5 w-0 flex-1">
                                    <dl>
                                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 truncate">
                                            Scholarship Cycles
                                        </dt>
                                        <dd class="text-3xl font-semibold text-gray-900 dark:text-gray-100">
                                            {{ $cycles->count() }}
                                        </dd>
                                    </dl>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white dark:bg-gray-900 overflow-hidden shadow rounded-lg">
                        <div class="p-5">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <svg class="h-6 w-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                    </svg>
                                </div>
                                <div class="ml-5 w-0 flex-1">
                                    <dl>
                                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 truncate">
                                            Education Tracks
                                        </dt>
                                        <dd class="text-3xl font-semibold text-gray-900 dark:text-gray-100">
                                            3
                                        </dd>
                                    </dl>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Awardees by Cycle -->
                @foreach($cycles as $cycle)
                    @if($awardeesByCycle->has($cycle->id))
                        <div class="mb-12">
                            <div class="bg-indigo-50 dark:bg-indigo-900/20 rounded-lg px-6 py-4 mb-6">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <h2 class="text-2xl font-bold text-gray-900 dark:text-gray-100">
                                            {{ $cycle->title }}
                                        </h2>
                                        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                                            Published: {{ $cycle->manual_published_at ? $cycle->manual_published_at->format('F d, Y') : $cycle->results_release_at->format('F d, Y') }}
                                        </p>
                                    </div>
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                                        {{ $awardeesByCycle[$cycle->id]->count() }} Winners
                                    </span>
                                </div>
                            </div>

                            <!-- Group by track -->
                            @php
                                $cycleAwardeesByTrack = $awardeesByCycle[$cycle->id]->groupBy('track');
                            @endphp

                            @foreach($cycleAwardeesByTrack as $track => $trackAwardees)
                                <div class="mb-8">
                                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4 capitalize">
                                        {{ $track }} Track ({{ $trackAwardees->count() }})
                                    </h3>

                                    <!-- Awardees Grid (Tiles) -->
                                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                                        @foreach($trackAwardees as $awardee)
                                            <div class="bg-white dark:bg-gray-900 rounded-lg shadow-lg overflow-hidden hover:shadow-xl transition-shadow duration-300">
                                                <!-- Photo Section -->
                                                <div class="relative bg-gradient-to-br from-indigo-500 to-purple-600 h-48 flex items-center justify-center">
                                                    @if($awardee->awardee_photo)
                                                        <img src="{{ Storage::url($awardee->awardee_photo) }}"
                                                             alt="{{ $awardee->user->name }}"
                                                             class="h-32 w-32 rounded-full object-cover border-4 border-white shadow-lg">
                                                    @else
                                                        <div class="h-32 w-32 rounded-full bg-white flex items-center justify-center border-4 border-white shadow-lg">
                                                            <span class="text-4xl font-bold text-indigo-600">
                                                                {{ strtoupper(substr($awardee->user->name, 0, 1)) }}
                                                            </span>
                                                        </div>
                                                    @endif
                                                </div>

                                                <!-- Content Section -->
                                                <div class="p-6">
                                                    <!-- Name -->
                                                    <h4 class="text-xl font-bold text-gray-900 dark:text-gray-100 mb-2 text-center">
                                                        {{ $awardee->user->name }}
                                                    </h4>

                                                    <!-- Track Badge -->
                                                    <div class="flex justify-center mb-4">
                                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-indigo-100 text-indigo-800 dark:bg-indigo-900 dark:text-indigo-200 capitalize">
                                                            {{ $track }} Track
                                                        </span>
                                                    </div>

                                                    <!-- Details Grid -->
                                                    <dl class="space-y-2 text-sm mb-4">
                                                        @if($awardee->educationRecord)
                                                            <div>
                                                                <dt class="text-gray-500 dark:text-gray-400 font-medium">School:</dt>
                                                                <dd class="text-gray-900 dark:text-gray-100">{{ $awardee->educationRecord->institution_name }}</dd>
                                                            </div>
                                                            @if($awardee->educationRecord->program)
                                                                <div>
                                                                    <dt class="text-gray-500 dark:text-gray-400 font-medium">Program:</dt>
                                                                    <dd class="text-gray-900 dark:text-gray-100">{{ $awardee->educationRecord->program }}</dd>
                                                                </div>
                                                            @endif
                                                        @endif
                                                        @if($awardee->scholarship_amount)
                                                            <div>
                                                                <dt class="text-gray-500 dark:text-gray-400 font-medium">Amount Awarded:</dt>
                                                                <dd class="text-green-600 dark:text-green-400 font-bold text-lg">₦{{ number_format($awardee->scholarship_amount, 2) }}</dd>
                                                            </div>
                                                        @endif
                                                    </dl>

                                                    <!-- Description -->
                                                    @if($awardee->awardee_profile)
                                                        <div class="border-t border-gray-200 dark:border-gray-700 pt-4">
                                                            <p class="text-sm text-gray-700 dark:text-gray-300 leading-relaxed">
                                                                {{ $awardee->awardee_profile }}
                                                            </p>
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                @endforeach

                <!-- Congratulations Message -->
                <div class="mt-12 bg-gradient-to-r from-indigo-500 to-purple-600 rounded-lg p-8 text-center">
                    <h3 class="text-2xl font-bold text-white mb-4">
                        Congratulations to All Our Winners!
                    </h3>
                    <p class="text-indigo-100 mb-6">
                        These students are now one step closer to their dreams. Apply today and join them next year!
                    </p>
                    <a href="{{ route('register') }}" class="inline-flex items-center justify-center px-6 py-3 border border-transparent text-base font-medium rounded-md text-indigo-600 bg-white hover:bg-indigo-50">
                        Apply for Scholarship
                    </a>
                </div>
            @endif
        </div>
    </div>
</x-public-layout>
