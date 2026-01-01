# Quick Deployment Guide

## Files to Upload to 147.182.242.177:/var/www/akinsola.org

Run these commands from PowerShell or Command Prompt:

```powershell
# Navigate to project directory
cd C:\Users\yomi\laravel-scholarship

# Upload changed files one by one
scp resources\views\public\in-memory.blade.php root@147.182.242.177:/var/www/akinsola.org/resources/views/public/

scp resources\views\applicant\application-create.blade.php root@147.182.242.177:/var/www/akinsola.org/resources/views/applicant/

scp app\Http\Controllers\ApplicantController.php root@147.182.242.177:/var/www/akinsola.org/app/Http/Controllers/

scp app\Notifications\CyclePublished.php root@147.182.242.177:/var/www/akinsola.org/app/Notifications/

scp app\Filament\Resources\CycleResource.php root@147.182.242.177:/var/www/akinsola.org/app/Filament/Resources/

scp EMAIL_TEMPLATES_GUIDE.md root@147.182.242.177:/var/www/akinsola.org/

# After upload, SSH into server and run Laravel commands
ssh root@147.182.242.177

# Once connected, run these commands:
cd /var/www/akinsola.org
php artisan cache:clear
php artisan config:clear
php artisan view:clear
php artisan route:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan queue:restart
exit
```

## Or Use WinSCP / FileZilla

If you prefer a GUI:

1. Open WinSCP or FileZilla
2. Connect to: 147.182.242.177
3. Username: root (or your SSH user)
4. Navigate to: /var/www/akinsola.org

Upload these files to their respective folders:

- `resources/views/public/in-memory.blade.php` → `/var/www/akinsola.org/resources/views/public/`
- `resources/views/applicant/application-create.blade.php` → `/var/www/akinsola.org/resources/views/applicant/`
- `app/Http/Controllers/ApplicantController.php` → `/var/www/akinsola.org/app/Http/Controllers/`
- `app/Notifications/CyclePublished.php` → `/var/www/akinsola.org/app/Notifications/`
- `app/Filament/Resources/CycleResource.php` → `/var/www/akinsola.org/app/Filament/Resources/`
- `EMAIL_TEMPLATES_GUIDE.md` → `/var/www/akinsola.org/`

Then use WinSCP's terminal or SSH separately to run the Laravel commands above.

## That's it!

After deployment, test:
- https://akinsola.org/in-memory (should show "our beloved parents" in blue)
- Application form should have submit button
- Admin panel should have "Notify Applicants" button in Cycles
