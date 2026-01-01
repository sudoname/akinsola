@echo off
REM Deployment script for akinsola.org (Windows)
REM Digital Ocean Droplet: 147.182.242.177

SET SERVER_IP=147.182.242.177
SET SERVER_USER=root
SET REMOTE_PATH=/var/www/akinsola.org

echo ================================================
echo Deploying to akinsola.org (Digital Ocean)
echo Server: %SERVER_IP%
echo Remote Path: %REMOTE_PATH%
echo ================================================
echo.

SET /P confirm="Are you sure you want to deploy? (yes/no): "
if /I NOT "%confirm%"=="yes" (
    echo Deployment cancelled.
    exit /b 0
)

echo.
echo Starting deployment...
echo.

REM Upload only the changed files
echo Uploading changed files...
echo.

REM Upload view file
scp "resources\views\public\in-memory.blade.php" %SERVER_USER%@%SERVER_IP%:%REMOTE_PATH%/resources/views/public/

REM Upload notification file
scp "app\Notifications\CyclePublished.php" %SERVER_USER%@%SERVER_IP%:%REMOTE_PATH%/app/Notifications/

REM Upload controller
scp "app\Http\Controllers\ApplicantController.php" %SERVER_USER%@%SERVER_IP%:%REMOTE_PATH%/app/Http/Controllers/

REM Upload Filament resource
scp "app\Filament\Resources\CycleResource.php" %SERVER_USER%@%SERVER_IP%:%REMOTE_PATH%/app/Filament/Resources/

REM Upload application form view
scp "resources\views\applicant\application-create.blade.php" %SERVER_USER%@%SERVER_IP%:%REMOTE_PATH%/resources/views/applicant/

REM Upload documentation
scp "EMAIL_TEMPLATES_GUIDE.md" %SERVER_USER%@%SERVER_IP%:%REMOTE_PATH%/

echo.
echo Files uploaded successfully!
echo.
echo Running post-deployment commands on server...
echo.

REM Run Laravel commands on server
ssh %SERVER_USER%@%SERVER_IP% "cd /var/www/akinsola.org && php artisan cache:clear && php artisan config:clear && php artisan view:clear && php artisan route:clear && php artisan config:cache && php artisan route:cache && php artisan view:cache && php artisan queue:restart"

echo.
echo ================================================
echo Deployment completed successfully!
echo ================================================
echo.
echo Changes deployed:
echo [x] Updated in-memory page text and colors
echo [x] Added submit button to application form
echo [x] Added CyclePublished notification
echo [x] Updated Filament Cycle resource with 'Notify Applicants' button
echo [x] Created EMAIL_TEMPLATES_GUIDE.md
echo.
echo Don't forget to:
echo 1. Ensure queue worker is running: php artisan queue:work
echo 2. Check the admin panel to test the 'Notify Applicants' button
echo.

pause
