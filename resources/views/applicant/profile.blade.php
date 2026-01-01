<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('My Profile') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">

            {{-- Success Message --}}
            @if(session('success'))
                <div class="mb-6 bg-green-50 dark:bg-green-900/10 border border-green-200 dark:border-green-800 rounded-lg p-4">
                    <div class="flex">
                        <svg class="h-5 w-5 text-green-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                        <p class="ml-3 text-sm text-green-800 dark:text-green-200">
                            {{ session('success') }}
                        </p>
                    </div>
                </div>
            @endif

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <form method="POST" action="{{ route('applicant.profile.update') }}" enctype="multipart/form-data">
                        @csrf

                        {{-- Personal Information Section --}}
                        <div class="mb-8">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4 pb-2 border-b border-gray-200 dark:border-gray-700">
                                Personal Information
                            </h3>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                {{-- Full Name --}}
                                <div class="md:col-span-2">
                                    <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                        Full Name <span class="text-red-600">*</span>
                                    </label>
                                    <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}" required
                                        class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    @error('name')
                                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                    @enderror
                                </div>

                                {{-- Date of Birth --}}
                                <div>
                                    <label for="dob" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                        Date of Birth <span class="text-red-600">*</span>
                                    </label>
                                    <input type="date" name="dob" id="dob" value="{{ old('dob', $profile->dob ? $profile->dob->format('Y-m-d') : '') }}" required
                                        class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    @error('dob')
                                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                    @enderror
                                </div>

                                {{-- Phone --}}
                                <div>
                                    <label for="phone" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                        Phone Number <span class="text-red-600">*</span>
                                    </label>
                                    <input type="tel" name="phone" id="phone" value="{{ old('phone', $profile->phone) }}" required
                                        class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    @error('phone')
                                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                    @enderror
                                </div>

                                {{-- Address --}}
                                <div class="md:col-span-2">
                                    <label for="address" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                        Residential Address <span class="text-red-600">*</span>
                                    </label>
                                    <textarea name="address" id="address" rows="2" required
                                        class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old('address', $profile->address) }}</textarea>
                                    @error('address')
                                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                    @enderror
                                </div>

                                {{-- State --}}
                                <div>
                                    <label for="state" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                        State <span class="text-red-600">*</span>
                                    </label>
                                    <input type="text" name="state" id="state" value="{{ old('state', $profile->state) }}" required
                                        class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    @error('state')
                                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                    @enderror
                                </div>

                                {{-- LGA --}}
                                <div>
                                    <label for="lga" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                        LGA (Local Government Area) <span class="text-red-600">*</span>
                                    </label>
                                    <input type="text" name="lga" id="lga" value="{{ old('lga', $profile->lga) }}" required
                                        class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    @error('lga')
                                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                    @enderror
                                </div>

                                {{-- Ward --}}
                                <div class="md:col-span-2">
                                    <label for="ward" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                        Ward (Optional)
                                    </label>
                                    <input type="text" name="ward" id="ward" value="{{ old('ward', $profile->ward) }}"
                                        class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    @error('ward')
                                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        {{-- Next of Kin Section --}}
                        <div class="mb-8">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4 pb-2 border-b border-gray-200 dark:border-gray-700">
                                Next of Kin Information
                            </h3>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                {{-- Next of Kin Name --}}
                                <div>
                                    <label for="next_of_kin_name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                        Next of Kin Name <span class="text-red-600">*</span>
                                    </label>
                                    <input type="text" name="next_of_kin_name" id="next_of_kin_name" value="{{ old('next_of_kin_name', $profile->next_of_kin_name) }}" required
                                        class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    @error('next_of_kin_name')
                                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                    @enderror
                                </div>

                                {{-- Next of Kin Phone --}}
                                <div>
                                    <label for="next_of_kin_phone" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                        Next of Kin Phone <span class="text-red-600">*</span>
                                    </label>
                                    <input type="tel" name="next_of_kin_phone" id="next_of_kin_phone" value="{{ old('next_of_kin_phone', $profile->next_of_kin_phone) }}" required
                                        class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    @error('next_of_kin_phone')
                                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        {{-- Indigene Proof Section --}}
                        <div class="mb-8">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4 pb-2 border-b border-gray-200 dark:border-gray-700">
                                Indigene Certificate
                            </h3>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                {{-- Issuer --}}
                                <div>
                                    <label for="indigene_issuer" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                        Issuing Authority
                                    </label>
                                    <input type="text" name="indigene_issuer" id="indigene_issuer" value="{{ old('indigene_issuer', $profile->indigene_issuer) }}"
                                        class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                        placeholder="e.g., Isan-Ekiti Local Government">
                                    @error('indigene_issuer')
                                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                    @enderror
                                </div>

                                {{-- Issue Date --}}
                                <div>
                                    <label for="indigene_issue_date" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                        Issue Date
                                    </label>
                                    <input type="date" name="indigene_issue_date" id="indigene_issue_date" value="{{ old('indigene_issue_date', $profile->indigene_issue_date ? $profile->indigene_issue_date->format('Y-m-d') : '') }}"
                                        class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    @error('indigene_issue_date')
                                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                    @enderror
                                </div>

                                {{-- File Upload --}}
                                <div class="md:col-span-2">
                                    <label for="indigene_proof" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                        Certificate Document <span class="text-red-600">*</span>
                                    </label>
                                    <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                                        Upload your indigene certificate (PDF, JPG, or PNG, max 10MB)
                                    </p>

                                    @if($profile->indigene_proof_url)
                                        <div class="mt-2 p-3 bg-gray-50 dark:bg-gray-900 rounded-md border border-gray-200 dark:border-gray-700">
                                            <div class="flex items-center justify-between">
                                                <div class="flex items-center">
                                                    <svg class="h-5 w-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M8 4a3 3 0 00-3 3v4a5 5 0 0010 0V7a1 1 0 112 0v4a7 7 0 11-14 0V7a5 5 0 0110 0v4a3 3 0 11-6 0V7a1 1 0 012 0v4a1 1 0 102 0V7a3 3 0 00-3-3z" clip-rule="evenodd"/>
                                                    </svg>
                                                    <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">
                                                        Current certificate uploaded
                                                    </span>
                                                </div>
                                                <a href="{{ Storage::url($profile->indigene_proof_url) }}" target="_blank" class="text-sm text-indigo-600 dark:text-indigo-400 hover:text-indigo-500">
                                                    View
                                                </a>
                                            </div>
                                        </div>
                                    @endif

                                    <input type="file" name="indigene_proof" id="indigene_proof" accept=".pdf,.jpg,.jpeg,.png"
                                        class="mt-2 block w-full text-sm text-gray-500 dark:text-gray-400
                                            file:mr-4 file:py-2 file:px-4
                                            file:rounded-md file:border-0
                                            file:text-sm file:font-semibold
                                            file:bg-indigo-50 file:text-indigo-700
                                            hover:file:bg-indigo-100
                                            dark:file:bg-indigo-900/20 dark:file:text-indigo-400
                                            dark:hover:file:bg-indigo-900/30">
                                    @error('indigene_proof')
                                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        {{-- Submit Button --}}
                        <div class="flex items-center justify-between pt-6 border-t border-gray-200 dark:border-gray-700">
                            <a href="{{ route('dashboard') }}" class="text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-200">
                                ← Back to Dashboard
                            </a>
                            <button type="submit" class="inline-flex items-center px-6 py-3 bg-indigo-600 border border-transparent rounded-md font-semibold text-sm text-white uppercase tracking-widest hover:bg-indigo-500 active:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                                Save Profile
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
