#!/bin/bash

# Kidz Tech Portal - Deployment Script
# This script automates common deployment tasks and ensures proper permissions

set -e  # Exit on any error

echo "========================================="
echo "Kidz Tech Portal - Deployment Script"
echo "========================================="
echo ""

# Colors for output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
NC='\033[0m' # No Color

# Function to print colored output
print_success() {
    echo -e "${GREEN}✓ $1${NC}"
}

print_warning() {
    echo -e "${YELLOW}⚠ $1${NC}"
}

print_error() {
    echo -e "${RED}✗ $1${NC}"
}

# Check if we're in the right directory
if [ ! -f "artisan" ]; then
    print_error "Error: artisan file not found. Please run this script from the Laravel project root."
    exit 1
fi

# Step 1: Fix Storage Permissions
echo "Step 1: Fixing storage and cache permissions..."
if [ -d "storage" ] && [ -d "bootstrap/cache" ]; then
    chmod -R 775 storage bootstrap/cache 2>/dev/null || chmod -R 755 storage bootstrap/cache
    print_success "Permissions updated for storage and bootstrap/cache"

    # Detect web server user and set ownership
    WEB_USER=""
    WEB_GROUP=""

    # Check for OpenLiteSpeed
    if pgrep -x "lshttpd" > /dev/null || pgrep -x "openlitespeed" > /dev/null; then
        WEB_USER="nobody"
        WEB_GROUP="nogroup"
        print_success "Detected OpenLiteSpeed web server"
    # Check for Apache
    elif pgrep -x "apache2" > /dev/null || pgrep -x "httpd" > /dev/null; then
        if id "www-data" &>/dev/null; then
            WEB_USER="www-data"
            WEB_GROUP="www-data"
        elif id "apache" &>/dev/null; then
            WEB_USER="apache"
            WEB_GROUP="apache"
        fi
        print_success "Detected Apache web server"
    # Check for Nginx (uses PHP-FPM)
    elif pgrep -x "nginx" > /dev/null; then
        if id "www-data" &>/dev/null; then
            WEB_USER="www-data"
            WEB_GROUP="www-data"
        elif id "nginx" &>/dev/null; then
            WEB_USER="nginx"
            WEB_GROUP="nginx"
        fi
        print_success "Detected Nginx web server"
    fi

    # Apply ownership if web user was detected
    if [ -n "$WEB_USER" ]; then
        chown -R $WEB_USER:$WEB_GROUP storage bootstrap/cache 2>/dev/null && \
            print_success "Ownership set to $WEB_USER:$WEB_GROUP" || \
            print_warning "Could not set ownership to $WEB_USER:$WEB_GROUP (may need sudo)"
    else
        print_warning "Could not detect web server. Please manually set ownership."
        print_warning "Run: chown -R <web-user>:<web-group> storage bootstrap/cache"
    fi
else
    print_error "storage or bootstrap/cache directory not found"
    exit 1
fi

# Step 2: Ensure required directories exist
echo ""
echo "Step 2: Creating required storage directories..."
mkdir -p storage/app/public/profile_photos/tutors
mkdir -p storage/app/public/profile_photos/students
mkdir -p storage/app/public/notices
mkdir -p storage/framework/sessions
mkdir -p storage/framework/views
mkdir -p storage/framework/cache
mkdir -p storage/logs
print_success "Required directories created"

# Step 3: Create storage symlink
echo ""
echo "Step 3: Creating storage symlink..."
php artisan storage:link 2>/dev/null && print_success "Storage link created" || print_warning "Storage link may already exist"

# Step 4: Clear all caches
echo ""
echo "Step 4: Clearing all caches..."
php artisan optimize:clear
print_success "All caches cleared"

# Step 5: Cache config, routes, and views (production only)
if [ "${APP_ENV}" = "production" ] || [ "$1" = "production" ]; then
    echo ""
    echo "Step 5: Caching configuration for production..."
    php artisan config:cache
    php artisan route:cache
    php artisan view:cache
    print_success "Configuration cached for production"
else
    print_warning "Skipping cache generation (not in production mode)"
    print_warning "Run with 'production' argument to enable: ./deploy.sh production"
fi

# Step 6: Run migrations (with confirmation)
echo ""
read -p "Do you want to run database migrations? (y/N) " -n 1 -r
echo
if [[ $REPLY =~ ^[Yy]$ ]]; then
    echo "Running migrations..."
    php artisan migrate --force
    print_success "Migrations completed"
else
    print_warning "Skipped migrations"
fi

# Step 7: Restart queue workers
echo ""
read -p "Do you want to restart queue workers? (y/N) " -n 1 -r
echo
if [[ $REPLY =~ ^[Yy]$ ]]; then
    echo "Restarting queue workers..."
    php artisan queue:restart
    print_success "Queue workers restarted"
else
    print_warning "Skipped queue restart"
fi

# Final permissions check
echo ""
echo "Step 8: Final permissions check..."
chmod -R 775 storage bootstrap/cache 2>/dev/null || chmod -R 755 storage bootstrap/cache

# Re-apply ownership in case new files were created
if [ -n "$WEB_USER" ]; then
    chown -R $WEB_USER:$WEB_GROUP storage bootstrap/cache 2>/dev/null
fi

print_success "Final permissions applied"

echo ""
echo "========================================="
print_success "Deployment completed successfully!"
echo "========================================="
echo ""
echo "Next steps:"
echo "1. Test the application: Visit your domain"
echo "2. Check logs: tail -f storage/logs/laravel.log"
echo "3. Monitor for errors in the first 30 minutes"
echo ""
