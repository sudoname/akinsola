# Email Communication Templates

This document contains sample email templates that are automatically sent by the Isan-Ekiti Indigene Scholarship Portal system.

---

## 1. Cycle Published Notification

**Sent to:** All registered applicants when an admin publishes a new scholarship cycle
**Trigger:** Admin clicks "Notify Applicants" button in the Cycles management section
**Purpose:** Inform applicants about a new scholarship opportunity

### Sample Email

```
Subject: New Scholarship Opportunity - 2025 Academic Year Scholarship

Hello John Doe!

We are excited to announce a new scholarship cycle is now open for applications!

Scholarship Cycle Details:

📚 2025 Academic Year Scholarship

📝 Description: This scholarship is available for secondary school, university, and polytechnic students from Isan-Ekiti.

Available Tracks: Secondary School, University, Polytechnic

Application Opens: January 15, 2025 9:00 AM
Deadline: March 31, 2025 11:59 PM
Results Release: April 30, 2025

This is your opportunity to receive financial support for your education. Don't miss this chance!

[Apply Now Button]

Application Requirements:

• Complete your profile with all required information
• Upload your scholarship essay (PDF format)
• Provide necessary supporting documents
• Submit your application before the deadline

Important Notes:

⏰ Applications must be submitted before the deadline
📋 Ensure your profile is complete before applying
📧 You will receive a confirmation email once your application is submitted
🎓 Results will be communicated via email after the results release date

For more information about eligibility requirements, please visit our website.

[View Eligibility Button]

Thank you for being a part of the Isan-Ekiti Indigene Scholarship Program. We look forward to receiving your application!

Best regards,
The Isan-Ekiti Scholarship Team
```

---

## 2. Application Submitted Confirmation

**Sent to:** Applicant immediately after submitting their application
**Trigger:** Applicant clicks "Submit Application" button
**Purpose:** Confirm successful application submission

### Sample Email

```
Subject: Application Submitted Successfully - 2025 Academic Year Scholarship

Hello John Doe!

Your scholarship application has been submitted successfully.

Application Details:

- Cycle: 2025 Academic Year Scholarship
- Track: University
- Submitted: January 20, 2025 3:45 PM

Your application is now under review. You will be notified once a decision has been made.

[View Application Button]

Results will be released on or after April 30, 2025.

Thank you for applying for the Isan-Ekiti Indigene Scholarship Program!
```

---

## 3. Application Decision Notification

**Sent to:** Applicant when results are published
**Trigger:** Admin clicks "Send Result Notifications" after results are visible
**Purpose:** Inform applicants of their application decision

### Sample Email - Approved

```
Subject: Scholarship Application Decision - Approved

Hello John Doe!

We are pleased to inform you that your scholarship application has been approved!

Congratulations on being selected as a recipient of the Isan-Ekiti Indigene Scholarship!

Application Details:

- Cycle: 2025 Academic Year Scholarship
- Track: University
- Status: Approved
- Decision: You have been awarded this scholarship

Next Steps:

1. Review your application details in your dashboard
2. Follow the instructions provided for scholarship disbursement
3. Maintain good academic standing as required by the scholarship terms

[View Application Details Button]

Thank you for your commitment to education. We wish you continued success in your academic journey!

Best regards,
The Isan-Ekiti Scholarship Team
```

### Sample Email - Rejected

```
Subject: Scholarship Application Decision - Update

Hello Jane Smith!

Thank you for submitting your application for the 2025 Academic Year Scholarship.

Application Details:

- Cycle: 2025 Academic Year Scholarship
- Track: Secondary School
- Status: Not Selected

While your application was not selected for this cycle, we encourage you to apply again in future cycles. The selection process is highly competitive, and we appreciate your interest in the program.

[View Application Details Button]

We encourage you to:

- Continue pursuing your educational goals
- Apply for future scholarship cycles
- Review eligibility requirements for the next cycle

Thank you for your interest in the Isan-Ekiti Indigene Scholarship Program!

Best regards,
The Isan-Ekiti Scholarship Team
```

### Sample Email - Waitlisted

```
Subject: Scholarship Application Decision - Waitlisted

Hello Mary Johnson!

Thank you for submitting your application for the 2025 Academic Year Scholarship.

Application Details:

- Cycle: 2025 Academic Year Scholarship
- Track: Polytechnic
- Status: Waitlisted

Your application has been placed on the waitlist. This means you may be considered for the scholarship if additional funding becomes available or if selected candidates decline their awards.

We will notify you if your status changes.

[View Application Details Button]

Thank you for your patience and continued interest in the Isan-Ekiti Indigene Scholarship Program!

Best regards,
The Isan-Ekiti Scholarship Team
```

---

## 4. Welcome Notification

**Sent to:** New users upon registration
**Trigger:** User completes registration
**Purpose:** Welcome new users to the platform

### Sample Email

```
Subject: Welcome to Isan-Ekiti Indigene Scholarship Portal

Hello John Doe!

Welcome to the Isan-Ekiti Indigene Scholarship Portal!

We're excited to have you join our community. This scholarship program was established to support the educational aspirations of Isan-Ekiti indigenes.

Next Steps:

1. Complete your profile with all required information
2. Upload your indigene certificate
3. Watch for scholarship cycle announcements
4. Apply when cycles are open

[Complete Your Profile Button]

For questions or support, please visit our website or contact our support team.

Best regards,
The Isan-Ekiti Scholarship Team
```

---

## How to Use These Templates

### For Administrators

1. **Publishing a New Cycle:**
   - Create a new cycle in the admin panel
   - Set the cycle status to "Published"
   - Click the "Notify Applicants" button
   - The system will automatically send the Cycle Published notification to all registered applicants

2. **Publishing Results:**
   - Once decisions are made and results release date has passed (or manually published)
   - Click "Send Result Notifications" in the Cycles management section
   - The system will automatically send decision emails to all applicants with decisions

### Email Settings

Ensure your `.env` file has the correct mail configuration:

```env
MAIL_MAILER=smtp
MAIL_HOST=your-smtp-host
MAIL_PORT=587
MAIL_USERNAME=your-email@example.com
MAIL_PASSWORD=your-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS="noreply@akinsola.org"
MAIL_FROM_NAME="Isan-Ekiti Scholarship Portal"
```

### Queue Configuration

All notifications are queued for background processing. Make sure to run the queue worker:

```bash
php artisan queue:work
```

Or use a process manager like Supervisor in production.

---

## Testing Email Templates

To preview email templates locally, you can use:

1. **Mailtrap** (for staging/development)
2. **Log driver** (emails written to log file)
3. **Laravel Tinker** to manually trigger notifications

Example using Tinker:

```php
php artisan tinker

// Test Cycle Published notification
$cycle = \App\Models\Cycle::first();
$user = \App\Models\User::where('role', 'applicant')->first();
$user->notify(new \App\Notifications\CyclePublished($cycle));

// Test Application Submitted notification
$application = \App\Models\Application::first();
$user->notify(new \App\Notifications\ApplicationSubmitted($application));
```

---

## Customizing Email Templates

To customize the email appearance:

1. Publish Laravel's mail templates:
   ```bash
   php artisan vendor:publish --tag=laravel-mail
   ```

2. Edit the templates in `resources/views/vendor/mail/`

3. Modify the notification classes in `app/Notifications/` to change content

---

## Email Tracking and Logs

All notification sending is logged in:

- **Queue Jobs:** Check `jobs` table in database
- **Failed Jobs:** Check `failed_jobs` table
- **Audit Logs:** Admin actions are logged in `audit_logs` table (who sent notifications, when, and to how many recipients)

---

## Best Practices

1. **Test Before Sending:** Always test notifications in staging before sending to production users
2. **Verify Recipients:** Check the recipient count before confirming bulk notifications
3. **Monitor Queue:** Ensure the queue worker is running to process emails
4. **Check Deliverability:** Monitor bounce rates and spam complaints
5. **Timing:** Send cycle announcements during business hours for better open rates
6. **Follow-ups:** Consider sending reminder emails before deadlines (can be implemented as scheduled notifications)

---

## Future Enhancements

Potential improvements to the notification system:

1. **Scheduled Reminders:** Automatic reminders 1 week and 1 day before deadline
2. **SMS Notifications:** Add SMS channel for critical updates
3. **In-App Notifications:** Browser notifications for logged-in users
4. **Email Preferences:** Allow users to customize which notifications they receive
5. **Template Customization:** Admin UI for editing email templates
6. **A/B Testing:** Test different email subject lines and content
7. **Analytics Dashboard:** Track open rates, click rates, and conversions

---

*Last Updated: November 2025*
