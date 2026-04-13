<x-public-layout>
    <!-- Modern Hero Section with Gradient -->
    <div class="relative bg-gradient-to-br from-indigo-600 via-purple-600 to-pink-500 py-24 overflow-hidden">
        <!-- Background Pattern -->
        <div class="absolute inset-0 opacity-10">
            <div class="absolute inset-0" style="background-image: url('data:image/svg+xml,%3Csvg width=&quot;60&quot; height=&quot;60&quot; viewBox=&quot;0 0 60 60&quot; xmlns=&quot;http://www.w3.org/2000/svg&quot;%3E%3Cg fill=&quot;none&quot; fill-rule=&quot;evenodd&quot;%3E%3Cg fill=&quot;%23ffffff&quot; fill-opacity=&quot;0.4&quot;%3E%3Cpath d=&quot;M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z&quot;%3E%3C/path%3E%3C/g%3E%3C/g%3E%3C/svg%3E');"></div>
        </div>

        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <div class="inline-flex items-center px-4 py-2 bg-white/20 backdrop-blur-sm rounded-full text-yellow-300 text-sm font-bold mb-6 animate-bounce drop-shadow-lg">
                <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                </svg>
                Celebrating Excellence
            </div>

            <h1 class="text-5xl md:text-6xl font-black text-yellow-400 mb-6 drop-shadow-2xl">
                Scholarship <span class="text-yellow-300 font-black">Awardees</span>
            </h1>
            <p class="text-xl md:text-2xl text-yellow-200/90 max-w-3xl mx-auto leading-relaxed font-medium drop-shadow-lg">
                Empowering the brightest minds from Isan-Ekiti to achieve their educational dreams
            </p>
        </div>
    </div>

    @if($cycles->isEmpty())
        <!-- No Results Available -->
        <div class="py-24 bg-gray-50 dark:bg-gray-900">
            <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl p-12">
                    <div class="w-24 h-24 bg-indigo-100 dark:bg-indigo-900 rounded-full flex items-center justify-center mx-auto mb-6">
                        <svg class="w-12 h-12 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 dark:text-gray-100 mb-4">Results Coming Soon</h3>
                    <p class="text-gray-600 dark:text-gray-400 mb-8 text-lg">
                        Scholarship winners will be announced here after the official release date. Stay tuned!
                    </p>
                    <a href="{{ route('home') }}" class="inline-flex items-center px-6 py-3 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold rounded-xl transition-all duration-200 shadow-lg hover:shadow-xl">
                        <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M9.707 14.707a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 1.414L7.414 9H15a1 1 0 110 2H7.414l2.293 2.293a1 1 0 010 1.414z" clip-rule="evenodd"/>
                        </svg>
                        Return Home
                    </a>
                </div>
            </div>
        </div>
    @else
        <!-- Main Content -->
        <div class="py-16 bg-gray-50 dark:bg-gray-900">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

                <!-- Modern Stats Grid -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-16">
                    <!-- Total Winners Card -->
                    <div class="group bg-white dark:bg-gray-800 rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-300 p-6 transform hover:-translate-y-2">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-xs text-gray-500 dark:text-gray-400 font-semibold uppercase tracking-wide mb-2">Total Winners</p>
                                <p class="text-5xl font-black text-gray-900 dark:text-gray-100 mb-1">{{ $awardeesByCycle->flatten()->count() }}</p>
                                <p class="text-sm text-gray-600 dark:text-gray-400 font-medium">Across all cycles</p>
                            </div>
                            <div class="w-14 h-14 bg-gradient-to-br from-emerald-100 to-teal-100 dark:from-emerald-900 dark:to-teal-900 rounded-xl flex items-center justify-center">
                                <svg class="w-7 h-7 text-emerald-600 dark:text-emerald-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z"/>
                                </svg>
                            </div>
                        </div>
                    </div>

                    <!-- Cycles Card -->
                    <div class="group bg-white dark:bg-gray-800 rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-300 p-6 transform hover:-translate-y-2">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-xs text-gray-500 dark:text-gray-400 font-semibold uppercase tracking-wide mb-2">Scholarship Cycles</p>
                                <p class="text-5xl font-black text-gray-900 dark:text-gray-100 mb-1">{{ $cycles->count() }}</p>
                                <p class="text-sm text-gray-600 dark:text-gray-400 font-medium">Years of impact</p>
                            </div>
                            <div class="w-14 h-14 bg-gradient-to-br from-orange-100 to-amber-100 dark:from-orange-900 dark:to-amber-900 rounded-xl flex items-center justify-center">
                                <svg class="w-7 h-7 text-orange-600 dark:text-orange-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"/>
                                </svg>
                            </div>
                        </div>
                    </div>

                    <!-- Tracks Card -->
                    <div class="group bg-white dark:bg-gray-800 rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-300 p-6 transform hover:-translate-y-2">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-xs text-gray-500 dark:text-gray-400 font-semibold uppercase tracking-wide mb-2">Education Tracks</p>
                                <p class="text-5xl font-black text-gray-900 dark:text-gray-100 mb-1">3</p>
                                <p class="text-sm text-gray-600 dark:text-gray-400 font-medium">Secondary, University, Polytechnic</p>
                            </div>
                            <div class="w-14 h-14 bg-gradient-to-br from-indigo-100 to-purple-100 dark:from-indigo-900 dark:to-purple-900 rounded-xl flex items-center justify-center">
                                <svg class="w-7 h-7 text-indigo-600 dark:text-indigo-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M10.394 2.08a1 1 0 00-.788 0l-7 3a1 1 0 000 1.84L5.25 8.051a.999.999 0 01.356-.257l4-1.714a1 1 0 11.788 1.838L7.667 9.088l1.94.831a1 1 0 00.787 0l7-3a1 1 0 000-1.838l-7-3zM3.31 9.397L5 10.12v4.102a8.969 8.969 0 00-1.05-.174 1 1 0 01-.89-.89 11.115 11.115 0 01.25-3.762zM9.3 16.573A9.026 9.026 0 007 14.935v-3.957l1.818.78a3 3 0 002.364 0l5.508-2.361a11.026 11.026 0 01.25 3.762 1 1 0 01-.89.89 8.968 8.968 0 00-5.35 2.524 1 1 0 01-1.4 0zM6 18a1 1 0 001-1v-2.065a8.935 8.935 0 00-2-.712V17a1 1 0 001 1z"/>
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Awardees by Cycle -->
                @foreach($cycles->sortByDesc('id') as $cycle)
                    @if(isset($awardeesByCycle[$cycle->id]))
                        <div class="mb-16">
                            <!-- Cycle Header -->
                            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-8 mb-8">
                                <div class="flex flex-col md:flex-row md:items-center md:justify-between">
                                    <div>
                                        <h2 class="text-3xl font-black text-gray-900 dark:text-gray-100 mb-2">
                                            {{ $cycle->title }}
                                        </h2>
                                        <p class="text-gray-600 dark:text-gray-400">
                                            <span class="inline-flex items-center">
                                                <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"/>
                                                </svg>
                                                Published: {{ $cycle->results_release_at ? \Carbon\Carbon::parse($cycle->results_release_at)->format('F d, Y') : 'N/A' }}
                                            </span>
                                        </p>
                                    </div>
                                    <div class="mt-4 md:mt-0">
                                        <div class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-green-500 to-emerald-500 rounded-xl text-yellow-300 font-black text-lg shadow-lg drop-shadow-lg">
                                            <svg class="w-6 h-6 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z"/>
                                            </svg>
                                            {{ $awardeesByCycle[$cycle->id]->count() }} Winners
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Group by track -->
                            @php
                                $cycleAwardeesByTrack = $awardeesByCycle[$cycle->id]->groupBy('track');
                            @endphp

                            @foreach($cycleAwardeesByTrack as $track => $trackAwardees)
                                <div class="mb-12">
                                    <!-- Track Header -->
                                    <div class="flex items-center mb-6">
                                        <div class="flex-grow h-px bg-gradient-to-r from-transparent via-gray-300 dark:via-gray-700 to-transparent"></div>
                                        <h3 class="px-6 text-xl font-bold text-gray-900 dark:text-gray-100 uppercase tracking-wider">
                                            <span class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-indigo-500 to-purple-500 rounded-full text-yellow-300 font-black shadow-lg drop-shadow-lg">
                                                {{ $track }} Track
                                                <span class="ml-2 px-2 py-1 bg-white/20 rounded-full text-sm">{{ $trackAwardees->count() }}</span>
                                            </span>
                                        </h3>
                                        <div class="flex-grow h-px bg-gradient-to-r from-transparent via-gray-300 dark:via-gray-700 to-transparent"></div>
                                    </div>

                                    <!-- Modern Awardees Grid -->
                                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                                        @foreach($trackAwardees as $awardee)
                                            <div class="group bg-white dark:bg-gray-800 rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-300 overflow-hidden transform hover:-translate-y-2">
                                                <!-- Photo Header with Gradient -->
                                                <div class="relative h-48 bg-gradient-to-br from-indigo-400 via-purple-400 to-pink-400 flex items-center justify-center overflow-hidden">
                                                    <!-- Animated Background -->
                                                    <div class="absolute inset-0 bg-gradient-to-br from-indigo-600/50 to-purple-600/50 group-hover:scale-110 transition-transform duration-700"></div>

                                                    @if($awardee->awardee_photo)
                                                        <img src="{{ Storage::url($awardee->awardee_photo) }}"
                                                             alt="{{ $awardee->user->name }}"
                                                             class="relative z-10 h-20 w-20 rounded-full object-cover border-3 border-white shadow-xl group-hover:scale-110 transition-transform duration-300">
                                                    @else
                                                        @php
                                                            $avatarSeed = md5($awardee->user->name);
                                                        @endphp
                                                        <img src="https://api.dicebear.com/7.x/notionists/svg?seed={{ $avatarSeed }}&backgroundColor=b6e3f4,c0aede,d1d4f9&skinColor=brown,black&radius=50" 
                                                             alt="{{ $awardee->user->name }}" 
                                                             class="relative z-10 h-20 w-20 rounded-full object-cover border-3 border-white shadow-xl group-hover:scale-110 transition-transform duration-300">
                                                    @endif

                                                    <!-- Trophy Badge -->
                                                    <div class="absolute top-4 right-4 z-20">
                                                        <div class="w-12 h-12 bg-yellow-400 rounded-full flex items-center justify-center shadow-lg animate-pulse">
                                                            <svg class="w-7 h-7 text-yellow-900" fill="currentColor" viewBox="0 0 20 20">
                                                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                                            </svg>
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- Content Section -->
                                                <div class="p-6">
                                                    <!-- Name -->
                                                    <h4 class="text-xl font-bold text-gray-900 dark:text-gray-100 mb-3 text-center group-hover:text-indigo-600 dark:group-hover:text-indigo-400 transition-colors">
                                                        {{ $awardee->user->name }}
                                                    </h4>

                                                    <!-- Track Badge -->
                                                    <div class="flex justify-center mb-4">
                                                        <span class="inline-flex items-center px-4 py-1.5 rounded-full text-xs font-bold uppercase tracking-wide bg-gradient-to-r from-indigo-100 to-purple-100 text-indigo-800 dark:from-indigo-900 dark:to-purple-900 dark:text-indigo-200">
                                                            <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                                <path d="M10.394 2.08a1 1 0 00-.788 0l-7 3a1 1 0 000 1.84L5.25 8.051a.999.999 0 01.356-.257l4-1.714a1 1 0 11.788 1.838L7.667 9.088l1.94.831a1 1 0 00.787 0l7-3a1 1 0 000-1.838l-7-3z"/>
                                                            </svg>
                                                            {{ $track }}
                                                        </span>
                                                    </div>

                                                    <!-- Info Cards -->
                                                    <div class="space-y-3 mb-4">
                                                        @if($awardee->educationRecord)
                                                            <div class="bg-gray-50 dark:bg-gray-900 rounded-xl p-3">
                                                                <p class="text-xs text-gray-500 dark:text-gray-400 font-semibold uppercase tracking-wide mb-1">Institution</p>
                                                                <p class="text-sm text-gray-900 dark:text-gray-100 font-medium leading-tight">{{ $awardee->educationRecord->institution_name }}</p>
                                                            </div>
                                                            @if($awardee->educationRecord->program)
                                                                <div class="bg-gray-50 dark:bg-gray-900 rounded-xl p-3">
                                                                    <p class="text-xs text-gray-500 dark:text-gray-400 font-semibold uppercase tracking-wide mb-1">Program</p>
                                                                    <p class="text-sm text-gray-900 dark:text-gray-100 font-medium leading-tight">{{ $awardee->educationRecord->program }}</p>
                                                                </div>
                                                            @endif
                                                        @endif

                                                        @if($awardee->scholarship_amount)
                                                            <div class="bg-gradient-to-r from-green-50 to-emerald-50 dark:from-green-900/30 dark:to-emerald-900/30 rounded-xl p-4 border-2 border-green-200 dark:border-green-800">
                                                                <p class="text-xs text-green-700 dark:text-green-300 font-semibold uppercase tracking-wide mb-1">Award Amount</p>
                                                                <p class="text-2xl text-green-600 dark:text-green-400 font-black">₦{{ number_format($awardee->scholarship_amount, 2) }}</p>
                                                            </div>
                                                        @endif
                                                    </div>

                                                    <!-- Profile Description -->
                                                    @if($awardee->awardee_profile)
                                                        <div class="border-t border-gray-200 dark:border-gray-700 pt-4 mt-4">
                                                            <p class="text-sm text-gray-700 dark:text-gray-300 leading-relaxed italic">
                                                                "{{ $awardee->awardee_profile }}"
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

                <!-- Call to Action -->
                <div class="mt-16 relative overflow-hidden rounded-3xl shadow-2xl">
                    <div class="absolute inset-0 bg-gradient-to-br from-indigo-600 via-purple-600 to-pink-600"></div>
                    <div class="absolute inset-0 bg-black/20"></div>

                    <!-- Animated Background Elements -->
                    <div class="absolute inset-0 opacity-20">
                        <div class="absolute top-10 left-10 w-32 h-32 bg-white rounded-full mix-blend-overlay filter blur-xl animate-blob"></div>
                        <div class="absolute top-20 right-10 w-40 h-40 bg-yellow-300 rounded-full mix-blend-overlay filter blur-xl animate-blob animation-delay-2000"></div>
                        <div class="absolute -bottom-8 left-1/2 w-36 h-36 bg-pink-300 rounded-full mix-blend-overlay filter blur-xl animate-blob animation-delay-4000"></div>
                    </div>

                    <div class="relative px-8 py-16 text-center">
                        <div class="inline-flex items-center px-4 py-2 bg-white/20 backdrop-blur-sm rounded-full text-yellow-300 text-sm font-bold mb-6 drop-shadow-lg">
                            <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M11.3 1.046A1 1 0 0112 2v5h4a1 1 0 01.82 1.573l-7 10A1 1 0 018 18v-5H4a1 1 0 01-.82-1.573l7-10a1 1 0 011.12-.38z" clip-rule="evenodd"/>
                            </svg>
                            Your Turn to Shine
                        </div>

                        <h3 class="text-4xl md:text-5xl font-black text-yellow-300 mb-6 leading-tight drop-shadow-2xl">
                            Join Our Next<br/>
                            <span class="text-yellow-300">Success Story!</span>
                        </h3>

                        <p class="text-xl text-yellow-200/90 mb-10 max-w-2xl mx-auto leading-relaxed font-medium drop-shadow-lg">
                            Applications are now open for the 2027 scholarship cycle. Don't miss your chance to receive educational support!
                        </p>

                        <a href="{{ route('register') }}" class="inline-flex items-center justify-center px-8 py-4 text-lg font-bold text-indigo-600 bg-white rounded-2xl hover:bg-gray-50 transition-all duration-200 shadow-2xl hover:shadow-3xl hover:scale-105 transform">
                            <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                            </svg>
                            Apply for 2027 Scholarship
                        </a>

                        <p class="mt-6 text-yellow-200/80 text-sm font-medium drop-shadow-lg">
                            ✨ Free application • No hidden fees • Fast processing
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Custom Animations -->
        <style>
            @keyframes blob {
                0%, 100% { transform: translate(0, 0) scale(1); }
                33% { transform: translate(30px, -50px) scale(1.1); }
                66% { transform: translate(-20px, 20px) scale(0.9); }
            }
            .animate-blob {
                animation: blob 7s infinite;
            }
            .animation-delay-2000 {
                animation-delay: 2s;
            }
            .animation-delay-4000 {
                animation-delay: 4s;
            }
        </style>
    @endif
</x-public-layout>
