#!/bin/bash

# Deployment script for akinsola.org
# Digital Ocean Droplet: 147.182.242.177

# Configuration
SERVER_IP="147.182.242.177"
SERVER_USER="root"  # Change this if you use a different user
REMOTE_PATH="/var/www/akinsola.org"  # Adjust if needed
LOCAL_PATH="."

echo "================================================"
echo "Deploying to akinsola.org (Digital Ocean)"
echo "Server: $SERVER_IP"
echo "Remote Path: $REMOTE_PATH"
echo "================================================"
echo ""

# Confirm deployment
read -p "Are you sure you want to deploy? (yes/no): " confirm
if [ "$confirm" != "yes" ]; then
    echo "Deployment cancelled."
    exit 0
fi

echo ""
echo "Starting deployment..."
echo ""

# Rsync files to server (excluding unnecessary files)
rsync -avz --progress \
    --exclude='.git' \
    --exclude='.env' \
    --exclude='node_modules' \
    --exclude='vendor' \
    --exclude='.phpunit.cache' \
    --exclude='storage/*.key' \
    --exclude='storage/logs/*' \
    --exclude='storage/framework/cache/*' \
    --exclude='storage/framework/sessions/*' \
    --exclude='storage/framework/views/*' \
    --exclude='public/storage' \
    --exclude='public/hot' \
    --exclude='public/build' \
    --exclude='.vscode' \
    --exclude='.idea' \
    --exclude='*.log' \
    $LOCAL_PATH/ $SERVER_USER@$SERVER_IP:$REMOTE_PATH/

echo ""
echo "Files uploaded successfully!"
echo ""
echo "Running post-deployment commands on server..."
echo ""

# Run Laravel commands on server
ssh $SERVER_USER@$SERVER_IP << 'ENDSSH'
cd /var/www/akinsola.org

# Clear caches
php artisan cache:clear
php artisan config:clear
php artisan view:clear
php artisan route:clear

# Optimize
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Restart queue workers (if running)
php artisan queue:restart

echo ""
echo "Post-deployment commands completed!"
echo ""
ENDSSH

echo "================================================"
echo "Deployment completed successfully!"
echo "================================================"
echo ""
echo "Changes deployed:"
echo "✓ Updated in-memory page text and colors"
echo "✓ Added CyclePublished notification"
echo "✓ Updated Filament Cycle resource with 'Notify Applicants' button"
echo "✓ Created EMAIL_TEMPLATES_GUIDE.md"
echo ""
echo "Don't forget to:"
echo "1. Ensure queue worker is running: php artisan queue:work"
echo "2. Check the admin panel to test the 'Notify Applicants' button"
echo ""
