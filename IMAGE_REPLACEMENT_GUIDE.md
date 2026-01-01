# How to Add Photos to the Memorial Page

The memorial page currently has placeholder areas for photos of your parents. Here's how to add their actual photos:

## Option 1: Using External URLs (Easiest)

If you have photos hosted online (Google Drive, Dropbox, etc.):

1. **Get the image URL**
   - Upload photos to a public hosting service
   - Get the direct image URL

2. **Edit the memorial page**
   ```bash
   nano /var/www/akinsola.org/resources/views/public/in-memory.blade.php
   ```

3. **Replace Mother's placeholder** (around line 31)
   ```html
   <!-- Replace this entire div -->
   <div class="aspect-square bg-gradient-to-br from-rose-100...">
       <!-- Placeholder content -->
   </div>

   <!-- With this -->
   <img src="YOUR_MOM_PHOTO_URL"
        alt="Solape Elizabeth Olorunsola"
        class="w-full h-full object-cover rounded-lg border-4 border-rose-200 dark:border-rose-800">
   ```

4. **Replace Father's placeholder** (around line 96)
   ```html
   <!-- Replace this entire div -->
   <div class="aspect-square bg-gradient-to-br from-blue-100...">
       <!-- Placeholder content -->
   </div>

   <!-- With this -->
   <img src="YOUR_DAD_PHOTO_URL"
        alt="Akinola Sanmi Peter Olorunsola"
        class="w-full h-full object-cover rounded-lg border-4 border-blue-200 dark:border-blue-800">
   ```

5. **Clear cache**
   ```bash
   cd /var/www/akinsola.org
   php artisan view:clear
   php artisan view:cache
   ```

## Option 2: Upload to Server (Most Reliable)

1. **Prepare your photos**
   - Name them: `mom-photo.jpg` and `dad-photo.jpg`
   - Recommended size: 800x800 pixels (square)
   - Format: JPG or PNG

2. **Upload to server**
   ```bash
   scp mom-photo.jpg root@147.182.242.177:/var/www/akinsola.org/public/images/
   scp dad-photo.jpg root@147.182.242.177:/var/www/akinsola.org/public/images/
   ```

3. **Create images directory if it doesn't exist**
   ```bash
   ssh root@147.182.242.177
   mkdir -p /var/www/akinsola.org/public/images
   chmod 755 /var/www/akinsola.org/public/images
   chown www-data:www-data /var/www/akinsola.org/public/images/*
   ```

4. **Update the page** (same as Option 1, but use local URLs)
   ```html
   <!-- For Mom -->
   <img src="{{ asset('images/mom-photo.jpg') }}"
        alt="Solape Elizabeth Olorunsola"
        class="w-full h-full object-cover rounded-lg border-4 border-rose-200 dark:border-rose-800">

   <!-- For Dad -->
   <img src="{{ asset('images/dad-photo.jpg') }}"
        alt="Akinola Sanmi Peter Olorunsola"
        class="w-full h-full object-cover rounded-lg border-4 border-blue-200 dark:border-blue-800">
   ```

5. **Clear cache**
   ```bash
   cd /var/www/akinsola.org
   php artisan view:clear
   php artisan view:cache
   ```

## Quick Upload Script

Save this as `upload-photos.sh` for easy uploading:

```bash
#!/bin/bash
# Upload memorial photos

echo "Creating images directory..."
ssh root@147.182.242.177 "mkdir -p /var/www/akinsola.org/public/images"

echo "Uploading mom's photo..."
scp mom-photo.jpg root@147.182.242.177:/var/www/akinsola.org/public/images/

echo "Uploading dad's photo..."
scp dad-photo.jpg root@147.182.242.177:/var/www/akinsola.org/public/images/

echo "Setting permissions..."
ssh root@147.182.242.177 "chown -R www-data:www-data /var/www/akinsola.org/public/images && chmod 755 /var/www/akinsola.org/public/images && chmod 644 /var/www/akinsola.org/public/images/*"

echo "Photos uploaded successfully!"
```

## Exact Lines to Replace

**Mother's Photo (Line 30-40):**
```html
<div class="md:col-span-1">
    <img src="{{ asset('images/mom-photo.jpg') }}"
         alt="Solape Elizabeth Olorunsola"
         class="w-full aspect-square object-cover rounded-lg border-4 border-rose-200 dark:border-rose-800 shadow-lg">
</div>
```

**Father's Photo (Line 95-105):**
```html
<div class="md:col-span-1">
    <img src="{{ asset('images/dad-photo.jpg') }}"
         alt="Akinola Sanmi Peter Olorunsola"
         class="w-full aspect-square object-cover rounded-lg border-4 border-blue-200 dark:border-blue-800 shadow-lg">
</div>
```

## Testing

After making changes, visit: https://akinsola.org/in-memory

The photos should appear in place of the placeholders, maintaining the rounded corners and border styling.

## Troubleshooting

**Photos not showing?**
1. Check file permissions: `ls -la /var/www/akinsola.org/public/images/`
2. Clear browser cache (Ctrl+Shift+R)
3. Check Laravel logs: `tail -f /var/www/akinsola.org/storage/logs/laravel.log`
4. Verify file names match exactly in the code

**Photos too large/small?**
- Resize to 800x800 pixels before uploading
- Use square images for best results
- Compress images to reduce load time (recommended: under 500KB each)
