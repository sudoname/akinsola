<?php

namespace App\Http\Controllers;

use App\Models\ApplicantProfile;
use App\Models\Application;
use App\Models\Cycle;
use App\Models\Document;
use App\Models\EducationRecord;
use App\Notifications\ApplicationSubmitted;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class ApplicantController extends Controller
{
    /**
     * Show the applicant dashboard.
     */
    public function dashboard()
    {
        $user = auth()->user();

        // Get or create profile
        $profile = $user->applicantProfile()->firstOrCreate([
            'user_id' => $user->id
        ]);

        // Get applications with cycle info
        $applications = $user->applications()
            ->with('cycle', 'educationRecord')
            ->latest()
            ->get();

        // Get active cycles
        $activeCycles = Cycle::where('status', 'published')
            ->where('deadline_at', '>', now())
            ->get();

        return view('applicant.dashboard', compact('user', 'profile', 'applications', 'activeCycles'));
    }

    /**
     * Show the profile form.
     */
    public function showProfile()
    {
        $user = auth()->user();
        $profile = $user->applicantProfile()->firstOrCreate([
            'user_id' => $user->id
        ]);

        return view('applicant.profile', compact('user', 'profile'));
    }

    /**
     * Update the profile.
     */
    public function updateProfile(Request $request)
    {
        $user = auth()->user();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'dob' => ['required', 'date', 'before:today'],
            'phone' => ['required', 'string', 'max:20'],
            'address' => ['required', 'string', 'max:500'],
            'state' => ['required', 'string', 'max:100'],
            'lga' => ['required', 'string', 'max:100'],
            'ward' => ['nullable', 'string', 'max:100'],
            'next_of_kin_name' => ['required', 'string', 'max:255'],
            'next_of_kin_phone' => ['required', 'string', 'max:20'],
            'indigene_issuer' => ['nullable', 'string', 'max:255'],
            'indigene_issue_date' => ['nullable', 'date', 'before_or_equal:today'],
            'indigene_proof' => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:10240'], // 10MB
        ]);

        // Update user name
        $user->update(['name' => $validated['name']]);

        // Get or create profile
        $profile = $user->applicantProfile()->firstOrCreate(['user_id' => $user->id]);

        // Handle file upload
        if ($request->hasFile('indigene_proof')) {
            // Delete old file if exists
            if ($profile->indigene_proof_url) {
                Storage::disk('public')->delete($profile->indigene_proof_url);
            }

            $path = $request->file('indigene_proof')->store('indigene-proofs', 'public');
            $validated['indigene_proof_url'] = $path;
        }

        unset($validated['name'], $validated['indigene_proof']);

        // Update profile
        $profile->update($validated);

        return redirect()->route('applicant.profile')
            ->with('success', 'Profile updated successfully!');
    }

    /**
     * Show application status.
     */
    public function showApplication(Application $application)
    {
        // Ensure user owns this application
        if ($application->user_id !== auth()->id()) {
            abort(403);
        }

        $application->load('cycle', 'educationRecord', 'documents', 'user');

        return view('applicant.application-show', compact('application'));
    }

    /**
     * Show the application form for a specific cycle and track.
     */
    public function createApplication(Cycle $cycle, $track)
    {
        $user = auth()->user();

        // Validate track
        if (!in_array($track, ['secondary', 'university', 'polytechnic'])) {
            abort(404, 'Invalid track');
        }

        // Check if cycle allows this track
        if (!in_array($track, $cycle->tracks_json)) {
            abort(403, 'This track is not available for this cycle.');
        }

        // Check if cycle is still open
        if ($cycle->hasDeadlinePassed()) {
            return redirect()->route('dashboard')
                ->with('error', 'This cycle has closed. Applications are no longer being accepted.');
        }

        // Check if profile is complete
        $profile = $user->applicantProfile;
        if (!$profile || !$profile->isComplete()) {
            return redirect()->route('applicant.profile')
                ->with('error', 'Please complete your profile before applying.');
        }

        // Check if user already has an application for this cycle (any track)
        $existingCycleApplication = Application::where('user_id', $user->id)
            ->where('cycle_id', $cycle->id)
            ->first();

        // If they have an application in a different track, prevent new application
        if ($existingCycleApplication && $existingCycleApplication->track !== $track) {
            return redirect()->route('applications.show', $existingCycleApplication)
                ->with('error', 'You can only apply for one track per cycle. You already have an application for the ' . ucfirst($existingCycleApplication->track) . ' track.');
        }

        // Check if user already has an application for this cycle and track
        $existingApplication = Application::where('user_id', $user->id)
            ->where('cycle_id', $cycle->id)
            ->where('track', $track)
            ->with(['educationRecord', 'documents'])
            ->first();

        if ($existingApplication && $existingApplication->status !== 'draft') {
            return redirect()->route('applications.show', $existingApplication)
                ->with('error', 'You have already submitted an application for this track.');
        }

        $application = $existingApplication;

        return view('applicant.application-create', compact('cycle', 'track', 'application', 'profile'));
    }

    /**
     * Store or update application (save as draft or submit directly).
     */
    public function storeApplication(Request $request, Cycle $cycle, $track)
    {
        $user = auth()->user();

        // Validate track
        if (!in_array($track, ['secondary', 'university', 'polytechnic'])) {
            abort(404, 'Invalid track');
        }

        // Check if cycle is still open
        if ($cycle->hasDeadlinePassed()) {
            return redirect()->route('dashboard')
                ->with('error', 'This cycle has closed.');
        }

        // Check if user already has an application for this cycle in a different track
        $existingCycleApplication = Application::where('user_id', $user->id)
            ->where('cycle_id', $cycle->id)
            ->where('track', '!=', $track)
            ->first();

        if ($existingCycleApplication) {
            return redirect()->route('applications.show', $existingCycleApplication)
                ->with('error', 'You can only apply for one track per cycle. You already have an application for the ' . ucfirst($existingCycleApplication->track) . ' track.');
        }

        // Determine if user wants to submit directly or save as draft
        $shouldSubmit = $request->input('action') === 'submit';

        // Validate education data first
        try {
            $educationData = $this->validateEducationData($request, $track);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return back()->withInput()->withErrors($e->errors());
        }

        // Validate file uploads
        $fileValidationRules = [];

        if ($request->hasFile('scholarship_essay')) {
            $fileValidationRules['scholarship_essay'] = ['file', 'mimes:pdf', 'max:10240'];
        }
        if ($request->hasFile('academic_transcript')) {
            $fileValidationRules['academic_transcript'] = ['file', 'mimes:pdf,jpg,jpeg,png', 'max:10240'];
        }
        if ($request->hasFile('admission_letter')) {
            $fileValidationRules['admission_letter'] = ['file', 'mimes:pdf,jpg,jpeg,png', 'max:10240'];
        }
        if ($request->hasFile('school_id_card')) {
            $fileValidationRules['school_id_card'] = ['file', 'mimes:pdf,jpg,jpeg,png', 'max:10240'];
        }
        if ($request->hasFile('birth_certificate')) {
            $fileValidationRules['birth_certificate'] = ['file', 'mimes:pdf,jpg,jpeg,png', 'max:10240'];
        }
        if ($request->hasFile('other_supporting_documents')) {
            $fileValidationRules['other_supporting_documents'] = ['file', 'mimes:pdf,jpg,jpeg,png', 'max:10240'];
        }

        if (!empty($fileValidationRules)) {
            try {
                $request->validate($fileValidationRules);
            } catch (\Illuminate\Validation\ValidationException $e) {
                return back()->withInput()->withErrors($e->errors());
            }
        }

        DB::beginTransaction();

        try {
            // Find or create application
            $application = Application::firstOrNew([
                'user_id' => $user->id,
                'cycle_id' => $cycle->id,
                'track' => $track,
            ]);

            // Ensure it's still a draft
            if ($application->exists && $application->status !== 'draft') {
                DB::rollBack();
                return redirect()->route('applications.show', $application)
                    ->with('error', 'Cannot edit a submitted application.');
            }

            // Initially save as draft
            $application->status = 'draft';
            $application->save();

            // Save education record
            $educationRecord = EducationRecord::firstOrNew([
                'application_id' => $application->id,
            ]);

            $educationRecord->fill($educationData);
            $educationRecord->save();

            // Handle document uploads
            $this->handleDocumentUploads($request, $application);

            // If user chose to submit directly, validate and submit
            if ($shouldSubmit) {
                // Refresh to get the updated documents
                $application->refresh();

                // Ensure scholarship essay is uploaded
                if (!$application->documents()->where('type', 'scholarship_essay')->exists()) {
                    DB::rollBack();
                    return back()->withInput()->withErrors([
                        'scholarship_essay' => 'Scholarship essay is required to submit your application. Please upload a PDF essay.'
                    ]);
                }

                // Submit the application
                if ($application->submit()) {
                    DB::commit();

                    // Send notification
                    $user->notify(new ApplicationSubmitted($application));

                    return redirect()->route('applications.show', $application)
                        ->with('success', 'Application submitted successfully! A confirmation email has been sent.');
                } else {
                    DB::rollBack();
                    return back()->withInput()->withErrors(['error' => 'Failed to submit application. Please try again.']);
                }
            }

            DB::commit();

            return redirect()->route('applications.edit', ['cycle' => $cycle->id, 'track' => $track])
                ->with('success', 'Application saved as draft successfully!');

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Application save error: ' . $e->getMessage(), [
                'user_id' => $user->id,
                'cycle_id' => $cycle->id,
                'track' => $track,
                'trace' => $e->getTraceAsString()
            ]);
            return back()->withInput()->withErrors(['error' => 'Failed to save application: ' . $e->getMessage()]);
        }
    }

    /**
     * Submit the application.
     */
    public function submitApplication(Cycle $cycle, $track)
    {
        $user = auth()->user();

        // Find application
        $application = Application::where('user_id', $user->id)
            ->where('cycle_id', $cycle->id)
            ->where('track', $track)
            ->firstOrFail();

        // Ensure it's a draft
        if ($application->status !== 'draft') {
            return redirect()->route('applications.show', $application)
                ->with('error', 'Application has already been submitted.');
        }

        // Check if cycle is still open
        if ($cycle->hasDeadlinePassed()) {
            return redirect()->route('dashboard')
                ->with('error', 'This cycle has closed.');
        }

        // Validate application completeness
        if (!$application->educationRecord) {
            return back()->with('error', 'Please complete the education section before submitting.');
        }

        // Ensure scholarship essay is uploaded
        if (!$application->documents()->where('type', 'scholarship_essay')->exists()) {
            return back()->with('error', 'Scholarship essay is required. Please upload your essay before submitting.');
        }

        // Submit the application
        if ($application->submit()) {
            // Send notification
            $user->notify(new ApplicationSubmitted($application));

            return redirect()->route('applications.show', $application)
                ->with('success', 'Application submitted successfully! A confirmation email has been sent.');
        }

        return back()->with('error', 'Failed to submit application. Please try again.');
    }

    /**
     * Validate education data based on track.
     */
    private function validateEducationData(Request $request, string $track): array
    {
        $rules = [
            'institution_name' => ['required', 'string', 'max:255'],
            'level_of_study' => ['required', 'string', 'max:100'],
        ];

        if (in_array($track, ['university', 'polytechnic'])) {
            $rules['course_of_study'] = ['required', 'string', 'max:255'];
            $rules['year_of_study'] = ['required', 'integer', 'min:1', 'max:10'];
            $rules['current_gpa'] = ['nullable', 'numeric', 'min:0', 'max:5.0'];
            $rules['graduation_year'] = ['required', 'integer', 'min:' . date('Y'), 'max:' . (date('Y') + 10)];
        }

        $validated = $request->validate($rules);

        // Map form fields to database column names
        return [
            'institution_name' => $validated['institution_name'],
            'level_or_class' => $validated['level_of_study'] ?? null,
            'program' => $validated['course_of_study'] ?? null,
            'year_of_study' => $validated['year_of_study'] ?? null,
            'cgpa' => $validated['current_gpa'] ?? null,
            'graduation_year' => $validated['graduation_year'] ?? null,
        ];
    }

    /**
     * Handle document uploads for the application.
     */
    private function handleDocumentUploads(Request $request, Application $application): void
    {
        $documentTypes = [
            'scholarship_essay',
            'academic_transcript',
            'admission_letter',
            'school_id_card',
            'birth_certificate',
            'other_supporting_documents',
        ];

        foreach ($documentTypes as $docType) {
            if ($request->hasFile($docType) && $request->file($docType)->isValid()) {
                // Check if document already exists
                $existingDoc = Document::where('application_id', $application->id)
                    ->where('type', $docType)
                    ->first();

                if ($existingDoc) {
                    // Delete old file
                    if (Storage::disk('public')->exists($existingDoc->url)) {
                        Storage::disk('public')->delete($existingDoc->url);
                    }
                    $existingDoc->delete();
                }

                // Store new file
                $file = $request->file($docType);
                $path = $file->store('documents', 'public');

                Document::create([
                    'application_id' => $application->id,
                    'type' => $docType,
                    'url' => $path,
                    'mime_type' => $file->getMimeType(),
                    'size_bytes' => $file->getSize(),
                    'uploaded_at' => now(),
                ]);
            }
        }
    }

    /**
     * Update bank account information for an approved application.
     */
    public function updateBankAccount(Request $request, Application $application)
    {
        // Authorization: must be the owner and must be approved
        if ($application->user_id !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }

        if ($application->status !== 'approved') {
            return back()->with('error', 'Bank account information can only be updated for approved applications.');
        }

        $validated = $request->validate([
            'bank_account_name' => ['required', 'string', 'max:255'],
            'bank_name' => ['required', 'string', 'max:255'],
            'bank_account_number' => ['required', 'string', 'regex:/^[0-9]{10}$/'],
            'bank_account_type' => ['required', 'in:savings,current'],
        ]);

        $application->update($validated);

        return back()->with('success', 'Bank account information saved successfully!');
    }

    public function confirmPayment(Request $request, Application $application)
    {
        // Authorization: must be the owner and payment must be sent
        if ($application->user_id !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }

        if ($application->payment_status !== 'sent') {
            return back()->with('error', 'Payment confirmation is only available when payment has been marked as sent.');
        }

        // Update payment status to received
        $application->update([
            'payment_status' => 'received',
            'payment_received_at' => now(),
        ]);

        // Notify admins that payment has been confirmed
        $admins = \App\Models\User::where('role', 'admin')->get();
        foreach ($admins as $admin) {
            $admin->notify(new \App\Notifications\PaymentReceivedNotification($application));
        }

        // Log the action
        \App\Models\AuditLog::logAction(
            'application.payment_received',
            auth()->id(),
            \App\Models\Application::class,
            $application->id
        );

        return back()->with('success', 'Thank you for confirming! The payment process is now complete.');
    }
}
