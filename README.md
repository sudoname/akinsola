# Isan-Ekiti Indigene Scholarship Portal

A comprehensive scholarship management system built with Laravel 11, FilamentPHP, and Tailwind CSS for managing scholarship applications for Isan-Ekiti indigenes across Secondary, University, and Polytechnic tracks.

## 🎯 Features

### Core Functionality
- **Multi-Track Applications**: Secondary School, University, and Polytechnic
- **Email & Social Authentication**: Email/password + Google/Facebook/Apple login via Socialite
- **Role-Based Access Control**: Applicant, Reviewer, Approver, Super Admin
- **Application Workflow**: Draft → Submitted → Under Review → Decision Pending → Approved/Rejected/Waitlisted
- **Time-Gated Results**: Automatic visibility control based on release dates
- **Manual Publishing**: Admin can publish results early via "Publish Now" action
- **Comprehensive Scoring**: Academic, Financial Need, Community Service, Leadership with configurable weights
- **Audit Logging**: Track all admin actions with IP addresses and metadata
- **Email Notifications**: Automated emails for application submission and decision results
- **CSV Export**: Export application data for analysis
- **Rate Limiting**: Protection against abuse on sensitive endpoints

### Admin Panel (FilamentPHP)
- **Cycle Management**: Create/edit cycles, set deadlines, manage results release
- **Application Review**: Score, review, and make decisions on applications
- **User Management**: Manage users and assign roles
- **Settings Management**: Configure scoring weights, file limits, decision reason codes
- **Bulk Actions**: Assign reviewers, export data, send notifications
- **Audit Log Viewer**: View all admin actions with filtering and search

### Applicant Portal
- **Dashboard**: View application status and active cycles
- **Profile Management**: Complete profile with indigene certificate upload
- **Application Form**: Track-specific forms with draft saving
- **Document Upload**: Upload academic transcripts, admission letters, etc.
- **Status Tracking**: Real-time application status updates

### Public Pages
- **Home**: Landing page with active cycles
- **About**: Program information and selection criteria
- **Eligibility**: Detailed requirements for each track
- **Awardees**: Time-gated results display with statistics

## 📋 Requirements

- PHP 8.3+
- Composer
- Node.js & NPM
- SQLite (or PostgreSQL/MySQL)

## 🚀 Installation

### 1. Install Dependencies
\`\`\`bash
cd laravel-scholarship
composer install
npm install
\`\`\`

### 2. Run Migrations & Seeders
\`\`\`bash
php artisan migrate --database=sqlite
php artisan db:seed --database=sqlite
\`\`\`

### 3. Create Storage Link
\`\`\`bash
php artisan storage:link
\`\`\`

### 4. Build Assets & Start Server
\`\`\`bash
npm run build
php artisan serve
\`\`\`

### 5. Start Queue Worker (for Email Notifications)
\`\`\`bash
php artisan queue:work
\`\`\`

Visit: **http://localhost:8000**

## 🔐 Default Login Credentials

### Admin Panel: `/admin`
\`\`\`
Super Admin: admin@isan-ekiti.ng / password
Reviewer: reviewer@isan-ekiti.ng / password
Approver: approver@isan-ekiti.ng / password
\`\`\`

### Applicant Portal: `/dashboard`
\`\`\`
Email: applicant@example.com / password
\`\`\`

## 📊 Database Schema

11 tables: users, applicant_profiles, cycles, applications, education_records, documents, audit_logs, settings, + Laravel defaults

## 🔄 Application Workflow

1. **Applicant**: Register → Complete Profile → Create Application → Submit
2. **Reviewer**: Review → Score (0-100 in 4 categories)
3. **Approver**: Make Decision (Approve/Reject/Waitlist)
4. **Admin**: Publish Results (auto after release date OR manual "Publish Now")

## ⚙️ Key Configuration

Edit in Admin → Settings:
- Scoring Weights (Academic: 40%, Need: 30%, Service: 15%, Leadership: 15%)
- File Upload Limits (10MB, PDF/JPG/PNG)
- Decision Reason Codes

## 🎨 Social Auth Setup

Update `.env`:
\`\`\`env
GOOGLE_CLIENT_ID=
GOOGLE_CLIENT_SECRET=

FACEBOOK_CLIENT_ID=
FACEBOOK_CLIENT_SECRET=

APPLE_CLIENT_ID=
APPLE_CLIENT_SECRET=
\`\`\`

## 📦 Tech Stack

- Laravel 11 + Breeze + Socialite
- FilamentPHP v3 (Admin Panel)
- Tailwind CSS + Alpine.js
- SQLite (dev) / PostgreSQL (prod)

## 🚨 Decision Visibility Gating

Results hidden until: `now() >= results_release_at` OR `manual_published_at !== null`

**No cron jobs needed** - visibility checked in real-time.

## 📝 Key Files

- `app/Models/Cycle.php:64` - `resultsAreVisible()`
- `app/Models/Application.php:116` - `isDecisionVisible()`
- `app/Filament/Resources/CycleResource.php:147` - "Publish Now" action
- `app/Filament/Resources/ApplicationResource.php:236` - "Make Decision"

## 🛠️ Development

\`\`\`bash
php artisan migrate --database=sqlite
php artisan db:seed --database=sqlite
npm run dev
php artisan serve
\`\`\`

## 🚀 Production

\`\`\`bash
composer install --optimize-autoloader --no-dev
php artisan config:cache
php artisan route:cache
npm run build
\`\`\`

## 📧 Email Notifications

The system sends automated emails for:
- **Welcome**: When a new user registers (ready to integrate)
- **Application Submitted**: Confirmation when applicant submits
- **Decision Notification**: Results (Approved/Rejected/Waitlisted)

All notifications are queued for background processing. Run `php artisan queue:work` to process them.

## 🔒 Security Features

- **Rate Limiting**:
  - Profile updates: 10 requests/minute
  - Application submissions: 5 requests/minute
  - General authenticated routes: 60 requests/minute
- **File Validation**: PDF/JPG/PNG only, 10MB max
- **Role-Based Access**: Strict permission checks on all admin actions
- **Audit Logging**: All admin actions logged with IP addresses
- **CSRF Protection**: Laravel's built-in protection on all forms
- **Email Verification**: Required before accessing application features

## 📊 Export & Analytics

- **CSV Export**: Select applications and export to CSV with all details
- **Audit Logs**: View comprehensive admin action history
- **Application Metrics**: Track submissions, approvals, and rejections
- **Awardee Statistics**: Public display of scholarship recipients

---

**Built for Isan-Ekiti Indigenes**
