<x-public-layout>
    <!-- Hero Section -->
    <div class="relative bg-indigo-600 dark:bg-indigo-800 overflow-hidden">
        <div class="max-w-7xl mx-auto">
            <div class="relative z-10 pb-8 sm:pb-16 md:pb-20 lg:w-full lg:pb-28 xl:pb-32">
                <main class="mt-10 mx-auto max-w-7xl px-4 sm:mt-12 sm:px-6 md:mt-16 lg:mt-20 lg:px-8 xl:mt-28">
                    <div class="text-center">
                        <h1 class="text-4xl tracking-tight font-extrabold text-white sm:text-5xl md:text-6xl">
                            <span class="block">Get Up to ₦200,000</span>
                            <span class="block text-indigo-200">For Your Education</span>
                        </h1>
                        <p class="mt-3 max-w-md mx-auto text-base text-indigo-100 sm:text-lg md:mt-5 md:text-xl md:max-w-3xl">
                            Free scholarship for Isan-Ekiti indigenes in secondary school, university, or polytechnic. No repayment required.
                        </p>

                        {{-- Quick Eligibility Badges --}}
                        <div class="mt-4 flex flex-wrap justify-center gap-2 text-sm">
                            <span class="inline-flex items-center px-3 py-1 rounded-full bg-white/20 text-white">
                                ✓ Isan-Ekiti indigenes only
                            </span>
                            <span class="inline-flex items-center px-3 py-1 rounded-full bg-white/20 text-white">
                                ✓ Age 30 and below
                            </span>
                            <span class="inline-flex items-center px-3 py-1 rounded-full bg-white/20 text-white">
                                ✓ 75%+ average grade
                            </span>
                        </div>
                        <div class="mt-5 max-w-md mx-auto sm:flex sm:justify-center md:mt-8 gap-3">
                            @auth
                                <a href="{{ route('dashboard') }}" class="w-full flex items-center justify-center px-8 py-3 border border-transparent text-base font-medium rounded-md text-indigo-700 bg-white hover:bg-indigo-50 md:py-4 md:text-lg md:px-10">
                                    Go to My Dashboard
                                </a>
                            @else
                                <a href="{{ route('register') }}" class="w-full flex items-center justify-center px-8 py-3 border border-transparent text-base font-medium rounded-md text-indigo-700 bg-white hover:bg-indigo-50 md:py-4 md:text-lg md:px-10">
                                    Apply for Scholarship
                                </a>
                                <a href="{{ route('eligibility') }}" class="mt-3 w-full flex items-center justify-center px-8 py-3 border border-white text-base font-medium rounded-md text-white bg-transparent hover:bg-white/10 md:py-4 md:text-lg md:px-10 sm:mt-0">
                                    Check if You Qualify
                                </a>
                            @endauth
                        </div>
                    </div>
                </main>
            </div>
        </div>
    </div>

    <!-- Active Cycles Section -->
    @if($activeCycles->isNotEmpty())
        <div class="py-12 bg-white dark:bg-gray-800">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center">
                    <h2 class="text-base text-indigo-600 dark:text-indigo-400 font-semibold tracking-wide uppercase">Applications Open Now</h2>
                    <p class="mt-2 text-3xl leading-8 font-extrabold tracking-tight text-gray-900 dark:text-gray-100 sm:text-4xl">
                        Apply Before the Deadline
                    </p>
                </div>

                <div class="mt-10 grid gap-8 md:grid-cols-2 lg:grid-cols-3">
                    @foreach($activeCycles as $cycle)
                        <div class="flex flex-col rounded-lg shadow-lg overflow-hidden bg-white dark:bg-gray-800 border-2 border-indigo-500">
                            <div class="flex-1 p-6 flex flex-col justify-between">
                                <div class="flex-1">
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                                        ✓ Open for Applications
                                    </span>
                                    <div class="block mt-3">
                                        <p class="text-xl font-semibold text-gray-900 dark:text-gray-100">
                                            {{ $cycle->title }}
                                        </p>
                                        <div class="mt-3 space-y-2">
                                            <p class="text-sm text-gray-500 dark:text-gray-400">
                                                <span class="font-medium text-red-600 dark:text-red-400">Deadline:</span> {{ $cycle->deadline_at->format('F d, Y') }}
                                            </p>
                                            <p class="text-sm text-gray-500 dark:text-gray-400">
                                                <span class="font-medium">Tracks:</span> {{ implode(', ', array_map('ucfirst', $cycle->tracks_json)) }}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                                <div class="mt-6">
                                    @auth
                                        <a href="{{ route('dashboard') }}" class="block w-full text-center px-4 py-3 border border-transparent text-base font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700">
                                            Apply Now
                                        </a>
                                    @else
                                        <a href="{{ route('register') }}" class="block w-full text-center px-4 py-3 border border-transparent text-base font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700">
                                            Register to Apply
                                        </a>
                                    @endauth
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    @endif

    <!-- How It Works Section -->
    <div class="py-16 bg-gray-50 dark:bg-gray-900">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center">
                <h2 class="text-3xl font-extrabold text-gray-900 dark:text-gray-100 sm:text-4xl">
                    How to Get Your Scholarship
                </h2>
                <p class="mt-4 text-lg text-gray-600 dark:text-gray-400">
                    Three simple steps to receive financial support for your education
                </p>
            </div>

            <div class="mt-12 grid gap-8 md:grid-cols-3">
                {{-- Step 1 --}}
                <div class="relative bg-white dark:bg-gray-800 rounded-lg p-6">
                    <div class="flex items-center justify-center h-16 w-16 rounded-full bg-indigo-600 text-white text-2xl font-bold mx-auto mb-4">
                        1
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 dark:text-gray-100 text-center mb-3">
                        Check if You Qualify
                    </h3>
                    <ul class="space-y-2 text-gray-600 dark:text-gray-400">
                        <li class="flex items-start">
                            <svg class="h-5 w-5 text-green-500 mt-0.5 mr-2 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                            <span>Isan-Ekiti indigene with certificate</span>
                        </li>
                        <li class="flex items-start">
                            <svg class="h-5 w-5 text-green-500 mt-0.5 mr-2 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                            <span>30 years old or younger</span>
                        </li>
                        <li class="flex items-start">
                            <svg class="h-5 w-5 text-green-500 mt-0.5 mr-2 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                            <span>75%+ grades (or 3.5 CGPA)</span>
                        </li>
                    </ul>
                    <div class="text-center mt-6">
                        <a href="{{ route('eligibility') }}" class="text-indigo-600 dark:text-indigo-400 hover:text-indigo-800 font-medium">
                            See full requirements →
                        </a>
                    </div>
                </div>

                {{-- Step 2 --}}
                <div class="relative bg-white dark:bg-gray-800 rounded-lg p-6">
                    <div class="flex items-center justify-center h-16 w-16 rounded-full bg-indigo-600 text-white text-2xl font-bold mx-auto mb-4">
                        2
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 dark:text-gray-100 text-center mb-3">
                        Apply Online
                    </h3>
                    <ul class="space-y-2 text-gray-600 dark:text-gray-400">
                        <li class="flex items-start">
                            <svg class="h-5 w-5 text-green-500 mt-0.5 mr-2 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                            <span>Create free account</span>
                        </li>
                        <li class="flex items-start">
                            <svg class="h-5 w-5 text-green-500 mt-0.5 mr-2 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                            <span>Upload required documents</span>
                        </li>
                        <li class="flex items-start">
                            <svg class="h-5 w-5 text-green-500 mt-0.5 mr-2 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                            <span>Write short essay (one page)</span>
                        </li>
                        <li class="flex items-start">
                            <svg class="h-5 w-5 text-green-500 mt-0.5 mr-2 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                            <span>Submit before deadline</span>
                        </li>
                    </ul>
                </div>

                {{-- Step 3 --}}
                <div class="relative bg-white dark:bg-gray-800 rounded-lg p-6">
                    <div class="flex items-center justify-center h-16 w-16 rounded-full bg-indigo-600 text-white text-2xl font-bold mx-auto mb-4">
                        3
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 dark:text-gray-100 text-center mb-3">
                        Get Award Notification
                    </h3>
                    <ul class="space-y-2 text-gray-600 dark:text-gray-400">
                        <li class="flex items-start">
                            <svg class="h-5 w-5 text-green-500 mt-0.5 mr-2 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                            <span>Committee reviews applications</span>
                        </li>
                        <li class="flex items-start">
                            <svg class="h-5 w-5 text-green-500 mt-0.5 mr-2 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                            <span>Results on release date</span>
                        </li>
                        <li class="flex items-start">
                            <svg class="h-5 w-5 text-green-500 mt-0.5 mr-2 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                            <span>Email and SMS notification</span>
                        </li>
                        <li class="flex items-start">
                            <svg class="h-5 w-5 text-green-500 mt-0.5 mr-2 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                            <span>Receive scholarship money</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <!-- Impact & Trust Section -->
    <div class="py-16 bg-white dark:bg-gray-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h2 class="text-3xl font-extrabold text-gray-900 dark:text-gray-100 sm:text-4xl">
                    Our Impact on Isan-Ekiti Students
                </h2>
                <p class="mt-4 text-lg text-gray-600 dark:text-gray-400">
                    Supporting education since our founding, in memory of dedicated educators and community leaders
                </p>
            </div>

            <div class="grid grid-cols-1 gap-8 sm:grid-cols-3">
                {{-- Stat 1 --}}
                <div class="text-center">
                    <div class="flex items-center justify-center h-16 w-16 rounded-full bg-indigo-100 dark:bg-indigo-900 text-indigo-600 dark:text-indigo-400 text-2xl font-bold mx-auto mb-4">
                        <svg class="h-8 w-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                        </svg>
                    </div>
                    <p class="text-4xl font-extrabold text-indigo-600 dark:text-indigo-400">50+</p>
                    <p class="mt-2 text-base font-medium text-gray-900 dark:text-gray-100">Students Awarded</p>
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Across all scholarship cycles</p>
                </div>

                {{-- Stat 2 --}}
                <div class="text-center">
                    <div class="flex items-center justify-center h-16 w-16 rounded-full bg-green-100 dark:bg-green-900 text-green-600 dark:text-green-400 text-2xl font-bold mx-auto mb-4">
                        <svg class="h-8 w-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <p class="text-4xl font-extrabold text-green-600 dark:text-green-400">₦200,000</p>
                    <p class="mt-2 text-base font-medium text-gray-900 dark:text-gray-100">Maximum Award</p>
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Per scholarship recipient</p>
                </div>

                {{-- Stat 3 --}}
                <div class="text-center">
                    <div class="flex items-center justify-center h-16 w-16 rounded-full bg-purple-100 dark:bg-purple-900 text-purple-600 dark:text-purple-400 text-2xl font-bold mx-auto mb-4">
                        <svg class="h-8 w-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                        </svg>
                    </div>
                    <p class="text-4xl font-extrabold text-purple-600 dark:text-purple-400">3</p>
                    <p class="mt-2 text-base font-medium text-gray-900 dark:text-gray-100">Education Tracks</p>
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Secondary, University, Polytechnic</p>
                </div>
            </div>

            {{-- Past Winners Link --}}
            <div class="mt-12 text-center">
                <a href="{{ route('awardees') }}" class="inline-flex items-center px-6 py-3 border border-indigo-600 text-base font-medium rounded-md text-indigo-600 dark:text-indigo-400 bg-white dark:bg-gray-800 hover:bg-indigo-50 dark:hover:bg-gray-700">
                    See Past Scholarship Winners
                    <svg class="ml-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </a>
            </div>
        </div>
    </div>

    <!-- Support / Help Section -->
    <div class="py-12 bg-indigo-50 dark:bg-gray-900">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h2 class="text-2xl font-bold text-gray-900 dark:text-gray-100 mb-4">
                Need Help with Your Application?
            </h2>
            <p class="text-lg text-gray-600 dark:text-gray-400 mb-6">
                Our team is here to assist you. Contact us for support with eligibility questions, application issues, or technical problems.
            </p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="mailto:info@khan.ng" class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700">
                    <svg class="mr-2 h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z"/>
                        <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z"/>
                    </svg>
                    Email: info@khan.ng
                </a>
                <a href="https://wa.me/2348168166109" target="_blank" class="inline-flex items-center px-6 py-3 border border-indigo-600 text-base font-medium rounded-md text-indigo-600 dark:text-indigo-400 bg-white dark:bg-gray-800 hover:bg-indigo-50 dark:hover:bg-gray-700">
                    <svg class="mr-2 h-5 w-5" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/>
                    </svg>
                    WhatsApp: +234 816 816 6109
                </a>
            </div>
        </div>
    </div>

    <!-- Final CTA Section -->
    <div class="bg-indigo-700 dark:bg-indigo-900">
        <div class="max-w-7xl mx-auto py-12 px-4 sm:px-6 lg:py-16 lg:px-8 lg:flex lg:items-center lg:justify-between">
            <h2 class="text-3xl font-extrabold tracking-tight text-white sm:text-4xl">
                <span class="block">Ready to apply?</span>
                <span class="block text-indigo-300">Start your scholarship journey today.</span>
            </h2>
            <div class="mt-8 flex lg:mt-0 lg:flex-shrink-0">
                <div class="inline-flex rounded-md shadow">
                    <a href="{{ route('register') }}" class="inline-flex items-center justify-center px-5 py-3 border border-transparent text-base font-medium rounded-md text-indigo-600 bg-white hover:bg-indigo-50">
                        Create Free Account
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-public-layout>
