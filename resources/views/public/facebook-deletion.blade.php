<x-public-layout>
    <!-- Header Section -->
    <div class="bg-gradient-to-br from-indigo-700 to-purple-800 text-white py-16">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h1 class="text-4xl font-bold mb-4">Facebook Data Deletion</h1>
            <p class="text-xl text-indigo-100">Request Deletion of Your Facebook Data</p>
        </div>
    </div>

    <!-- Content Section -->
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-8 space-y-6">

            <section>
                <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-4">About Facebook Login</h2>
                <p class="text-gray-700 dark:text-gray-300 leading-relaxed">
                    When you log in to the Isan-Ekiti Indigene Scholarship Program portal using Facebook Login, we receive
                    limited information from your Facebook account to create and manage your account on our platform.
                </p>
            </section>

            <section>
                <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-4">What Data We Receive</h2>
                <p class="text-gray-700 dark:text-gray-300 leading-relaxed mb-3">
                    When you use Facebook Login, we receive the following information:
                </p>
                <ul class="list-disc list-inside text-gray-700 dark:text-gray-300 space-y-2 ml-4">
                    <li>Your name</li>
                    <li>Your email address</li>
                    <li>Your Facebook user ID</li>
                    <li>Your profile picture (optional)</li>
                </ul>
                <p class="text-gray-700 dark:text-gray-300 leading-relaxed mt-4">
                    This information is used solely to create and authenticate your account. We do not receive access to your
                    Facebook posts, friends list, or other Facebook activity.
                </p>
            </section>

            <section>
                <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-4">How to Delete Your Data</h2>
                <p class="text-gray-700 dark:text-gray-300 leading-relaxed mb-4">
                    If you wish to delete all data associated with your Facebook login from our system, you have two options:
                </p>

                <div class="space-y-4">
                    <div class="p-4 bg-indigo-50 dark:bg-indigo-900/20 border border-indigo-200 dark:border-indigo-800 rounded-lg">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">Option 1: Delete Your Account</h3>
                        <p class="text-gray-700 dark:text-gray-300 leading-relaxed mb-3">
                            Log in to your account and navigate to your profile settings to permanently delete your account.
                            This will remove all your data from our system.
                        </p>
                        @auth
                            <a href="{{ route('profile.edit') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-500 active:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                Go to Profile Settings
                            </a>
                        @else
                            <a href="{{ route('login') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-500 active:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                Log In to Your Account
                            </a>
                        @endauth
                    </div>

                    <div class="p-4 bg-purple-50 dark:bg-purple-900/20 border border-purple-200 dark:border-purple-800 rounded-lg">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">Option 2: Contact Us Directly</h3>
                        <p class="text-gray-700 dark:text-gray-300 leading-relaxed mb-3">
                            Send us an email with your request to delete your data. Please include your name and the email
                            address associated with your Facebook account.
                        </p>
                        <div class="p-3 bg-white dark:bg-gray-900 rounded border border-purple-200 dark:border-purple-700">
                            <p class="text-gray-700 dark:text-gray-300">
                                <strong>Email:</strong> <a href="mailto:info@khan.ng" class="text-indigo-600 dark:text-indigo-400 hover:underline">info@khan.ng</a><br>
                                <strong>WhatsApp:</strong> <a href="https://wa.me/2348168166109" target="_blank" class="text-green-600 dark:text-green-400 hover:underline">+234 816 816 6109</a><br>
                                <strong>Subject:</strong> Data Deletion Request - Facebook Login
                            </p>
                        </div>
                    </div>
                </div>
            </section>

            <section>
                <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-4">What Happens After Deletion</h2>
                <p class="text-gray-700 dark:text-gray-300 leading-relaxed mb-3">
                    When you delete your account or request data deletion:
                </p>
                <ul class="list-disc list-inside text-gray-700 dark:text-gray-300 space-y-2 ml-4">
                    <li>All your personal information will be permanently removed from our database</li>
                    <li>Any scholarship applications you submitted will be archived for record-keeping purposes but will be anonymized</li>
                    <li>You will no longer be able to access your account</li>
                    <li>The process typically takes 30 days to complete</li>
                </ul>
            </section>

            <section>
                <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-4">Data Retention Policy</h2>
                <p class="text-gray-700 dark:text-gray-300 leading-relaxed">
                    We retain your data only as long as necessary to provide our services and comply with legal obligations.
                    After account deletion, we may retain anonymized statistical information for program improvement purposes,
                    but this data cannot be linked back to you.
                </p>
            </section>

            <section>
                <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-4">Questions or Concerns</h2>
                <p class="text-gray-700 dark:text-gray-300 leading-relaxed">
                    If you have any questions about data deletion or our data practices, please review our
                    <a href="{{ route('policy.privacy') }}" class="text-indigo-600 dark:text-indigo-400 hover:underline">Privacy Policy</a>
                    or contact us directly:
                </p>
                <div class="mt-4 p-4 bg-gray-50 dark:bg-gray-900 rounded-lg">
                    <p class="text-gray-700 dark:text-gray-300">
                        <strong>Email:</strong> <a href="mailto:info@khan.ng" class="text-indigo-600 dark:text-indigo-400 hover:underline">info@khan.ng</a><br>
                        <strong>WhatsApp:</strong> <a href="https://wa.me/2348168166109" target="_blank" class="text-green-600 dark:text-green-400 hover:underline">+234 816 816 6109</a><br>
                        <strong>Address:</strong> Isan-Ekiti, Oye Local Government, Ekiti State, Nigeria
                    </p>
                </div>
            </section>

        </div>
    </div>
</x-public-layout>
