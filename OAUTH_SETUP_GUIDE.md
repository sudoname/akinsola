# OAuth Setup Guide for Social Login

This guide will help you set up Google and Facebook OAuth login for the scholarship portal.

## Google OAuth Setup

### Step 1: Create Google Cloud Project
1. Go to [Google Cloud Console](https://console.cloud.google.com/)
2. Click "Select a project" → "New Project"
3. Enter project name: "Isan-Ekiti Scholarship Portal"
4. Click "Create"

### Step 2: Enable Google+ API
1. In the left sidebar, go to "APIs & Services" → "Library"
2. Search for "Google+ API"
3. Click on it and click "Enable"

### Step 3: Create OAuth Credentials
1. Go to "APIs & Services" → "Credentials"
2. Click "Create Credentials" → "OAuth client ID"
3. If prompted, configure the OAuth consent screen:
   - User Type: External
   - App name: "Isan-Ekiti Scholarship Portal"
   - User support email: Your email
   - Developer contact: Your email
   - Click "Save and Continue" through all steps
4. Back to "Create OAuth client ID":
   - Application type: "Web application"
   - Name: "Scholarship Portal"
   - Authorized JavaScript origins:
     - `https://akinsola.org`
     - `http://localhost` (for testing)
   - Authorized redirect URIs:
     - `https://akinsola.org/auth/google/callback`
     - `http://localhost/auth/google/callback` (for testing)
5. Click "Create"
6. Copy your **Client ID** and **Client Secret**

### Step 4: Add to Environment Variables
SSH into your server and edit the .env file:

```bash
ssh root@147.182.242.177
nano /var/www/akinsola.org/.env
```

Add your credentials:
```
GOOGLE_CLIENT_ID=your-client-id-here
GOOGLE_CLIENT_SECRET=your-client-secret-here
```

## Facebook OAuth Setup

### Step 1: Create Facebook App
1. Go to [Facebook Developers](https://developers.facebook.com/)
2. Click "My Apps" → "Create App"
3. Select "Consumer" as the app type
4. Click "Next"

### Step 2: Configure App
1. App Display Name: "Isan-Ekiti Scholarship Portal"
2. App Contact Email: Your email
3. Click "Create App"
4. Complete the security check if prompted

### Step 3: Add Facebook Login Product
1. In the left sidebar, click "Add Product"
2. Find "Facebook Login" and click "Set Up"
3. Choose "Web" platform
4. Enter your site URL: `https://akinsola.org`
5. Click "Save" and "Continue"

### Step 4: Configure OAuth Settings
1. In the left sidebar, go to "Facebook Login" → "Settings"
2. Add to "Valid OAuth Redirect URIs":
   - `https://akinsola.org/auth/facebook/callback`
   - `http://localhost/auth/facebook/callback` (for testing)
3. Click "Save Changes"

### Step 5: Get App Credentials
1. Go to "Settings" → "Basic" in the left sidebar
2. Copy your **App ID** (this is your Client ID)
3. Click "Show" next to **App Secret** and copy it (this is your Client Secret)

### Step 6: Switch to Live Mode
1. Toggle the switch at the top from "Development" to "Live"
2. You may need to complete App Review for full functionality

### Step 7: Add to Environment Variables
SSH into your server and edit the .env file:

```bash
ssh root@147.182.242.177
nano /var/www/akinsola.org/.env
```

Add your credentials:
```
FACEBOOK_CLIENT_ID=your-app-id-here
FACEBOOK_CLIENT_SECRET=your-app-secret-here
```

## Final Steps

After adding all credentials to the .env file:

1. Clear the config cache:
```bash
cd /var/www/akinsola.org
php artisan config:clear
php artisan config:cache
```

2. Test the login:
   - Visit https://akinsola.org/login
   - You should see "Continue with Google" and "Continue with Facebook" buttons
   - Click each to test the OAuth flow

## Testing Locally

For local development, update your local `.env` file:

```env
APP_URL=http://localhost

GOOGLE_CLIENT_ID=your-local-client-id
GOOGLE_CLIENT_SECRET=your-local-client-secret

FACEBOOK_CLIENT_ID=your-local-app-id
FACEBOOK_CLIENT_SECRET=your-local-app-secret
```

Make sure to add `http://localhost/auth/google/callback` and `http://localhost/auth/facebook/callback` to your OAuth app configurations.

## Troubleshooting

### "redirect_uri_mismatch" Error
- Verify the redirect URI in your Google/Facebook app settings matches exactly
- Check that APP_URL in .env is correct
- Clear config cache: `php artisan config:clear`

### "App Not Setup" Error (Facebook)
- Make sure you've added Facebook Login product
- Check that OAuth redirect URIs are saved
- Verify your app is in Live mode (not Development)

### User Email Not Available
- For Google: Ensure "Google+ API" is enabled
- For Facebook: Request "email" permission in app review if needed

## Security Notes

- Keep your Client Secret secure and never commit it to version control
- Use different OAuth apps for development and production
- Regularly rotate your OAuth credentials
- Monitor your OAuth app usage in respective consoles
