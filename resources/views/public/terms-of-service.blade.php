<x-public-layout>
    <!-- Header Section -->
    <div class="bg-gradient-to-br from-indigo-700 to-purple-800 text-white py-16">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h1 class="text-4xl font-bold mb-4">Terms of Service</h1>
            <p class="text-xl text-indigo-100">Last Updated: {{ date('F d, Y') }}</p>
        </div>
    </div>

    <!-- Content Section -->
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-8 space-y-6">

            <section>
                <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-4">1. Acceptance of Terms</h2>
                <p class="text-gray-700 dark:text-gray-300 leading-relaxed">
                    By accessing and using the Isan-Ekiti Indigene Scholarship Program portal ("Portal"), you accept and agree to
                    be bound by these Terms of Service. If you do not agree to these terms, please do not use the Portal.
                </p>
            </section>

            <section>
                <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-4">2. Eligibility Requirements</h2>
                <p class="text-gray-700 dark:text-gray-300 leading-relaxed mb-3">
                    To apply for the scholarship, you must:
                </p>
                <ul class="list-disc list-inside text-gray-700 dark:text-gray-300 space-y-2 ml-4">
                    <li>Be an indigene of Isan-Ekiti, verified by proper documentation</li>
                    <li>Be enrolled or accepted into a recognized secondary school, university, or polytechnic</li>
                    <li>Meet all specific eligibility criteria for your chosen track</li>
                    <li>Provide accurate and truthful information in your application</li>
                </ul>
            </section>

            <section>
                <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-4">3. Account Registration</h2>
                <p class="text-gray-700 dark:text-gray-300 leading-relaxed mb-3">
                    When creating an account:
                </p>
                <ul class="list-disc list-inside text-gray-700 dark:text-gray-300 space-y-2 ml-4">
                    <li>You must provide accurate, current, and complete information</li>
                    <li>You are responsible for maintaining the confidentiality of your account credentials</li>
                    <li>You are responsible for all activities that occur under your account</li>
                    <li>You must notify us immediately of any unauthorized access or security breach</li>
                </ul>
            </section>

            <section>
                <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-4">4. Application Process</h2>
                <p class="text-gray-700 dark:text-gray-300 leading-relaxed mb-3">
                    By submitting an application:
                </p>
                <ul class="list-disc list-inside text-gray-700 dark:text-gray-300 space-y-2 ml-4">
                    <li>You certify that all information provided is accurate and truthful</li>
                    <li>You authorize us to verify the information you provide</li>
                    <li>You understand that false information may result in disqualification</li>
                    <li>You agree that the scholarship committee's decisions are final</li>
                    <li>You acknowledge that submitting an application does not guarantee award</li>
                </ul>
            </section>

            <section>
                <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-4">5. Prohibited Conduct</h2>
                <p class="text-gray-700 dark:text-gray-300 leading-relaxed mb-3">
                    You agree not to:
                </p>
                <ul class="list-disc list-inside text-gray-700 dark:text-gray-300 space-y-2 ml-4">
                    <li>Submit false or misleading information</li>
                    <li>Create multiple accounts or duplicate applications</li>
                    <li>Attempt to manipulate or interfere with the application process</li>
                    <li>Access or attempt to access other users' accounts</li>
                    <li>Use the Portal for any unlawful purpose</li>
                    <li>Upload malicious code or attempt to compromise system security</li>
                </ul>
            </section>

            <section>
                <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-4">6. Scholarship Award Terms</h2>
                <p class="text-gray-700 dark:text-gray-300 leading-relaxed mb-3">
                    If you are awarded a scholarship:
                </p>
                <ul class="list-disc list-inside text-gray-700 dark:text-gray-300 space-y-2 ml-4">
                    <li>Awards are subject to continued eligibility and academic performance</li>
                    <li>You must provide proof of enrollment and academic progress when requested</li>
                    <li>Funds must be used for educational purposes only</li>
                    <li>The scholarship committee may revoke awards for violation of terms or poor academic performance</li>
                    <li>Maximum scholarship amount per candidate is ₦200,000 per cycle</li>
                </ul>
            </section>

            <section>
                <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-4">7. Intellectual Property</h2>
                <p class="text-gray-700 dark:text-gray-300 leading-relaxed">
                    All content on the Portal, including text, graphics, logos, and software, is the property of the Isan-Ekiti
                    Indigene Scholarship Program and protected by copyright and intellectual property laws. You may not
                    reproduce, distribute, or create derivative works without written permission.
                </p>
            </section>

            <section>
                <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-4">8. Disclaimer of Warranties</h2>
                <p class="text-gray-700 dark:text-gray-300 leading-relaxed">
                    The Portal is provided "as is" without warranties of any kind, either express or implied. We do not warrant
                    that the Portal will be uninterrupted, error-free, or free of viruses or other harmful components.
                </p>
            </section>

            <section>
                <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-4">9. Limitation of Liability</h2>
                <p class="text-gray-700 dark:text-gray-300 leading-relaxed">
                    To the maximum extent permitted by law, we shall not be liable for any indirect, incidental, special,
                    consequential, or punitive damages arising from your use of the Portal or scholarship program.
                </p>
            </section>

            <section>
                <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-4">10. Privacy</h2>
                <p class="text-gray-700 dark:text-gray-300 leading-relaxed">
                    Your use of the Portal is also governed by our <a href="{{ route('policy.privacy') }}" class="text-indigo-600 dark:text-indigo-400 hover:underline">Privacy Policy</a>.
                    Please review it to understand our data practices.
                </p>
            </section>

            <section>
                <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-4">11. Modifications to Terms</h2>
                <p class="text-gray-700 dark:text-gray-300 leading-relaxed">
                    We reserve the right to modify these Terms of Service at any time. Changes will be effective immediately
                    upon posting to the Portal. Your continued use after changes constitutes acceptance of the modified terms.
                </p>
            </section>

            <section>
                <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-4">12. Termination</h2>
                <p class="text-gray-700 dark:text-gray-300 leading-relaxed">
                    We reserve the right to suspend or terminate your account and access to the Portal at any time, without
                    notice, for conduct that we believe violates these Terms of Service or is harmful to the scholarship program
                    or other users.
                </p>
            </section>

            <section>
                <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-4">13. Governing Law</h2>
                <p class="text-gray-700 dark:text-gray-300 leading-relaxed">
                    These Terms of Service shall be governed by and construed in accordance with the laws of the Federal
                    Republic of Nigeria, without regard to conflict of law principles.
                </p>
            </section>

            <section>
                <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-4">14. Contact Information</h2>
                <p class="text-gray-700 dark:text-gray-300 leading-relaxed">
                    If you have questions about these Terms of Service, please contact us:
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
