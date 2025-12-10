#!/bin/bash
# Laravel Cache Clearing Script

echo "Clearing Laravel caches..."

# Clear view cache
php artisan view:clear
echo "✓ View cache cleared"

# Clear application cache  
php artisan cache:clear
echo "✓ Application cache cleared"

# Clear route cache
php artisan route:clear
echo "✓ Route cache cleared"

# Clear config cache
php artisan config:clear
echo "✓ Config cache cleared"

# Rebuild optimizations
php artisan optimize:clear
echo "✓ All optimizations cleared"

echo ""
echo "All caches cleared successfully!"
echo "Now you can rebuild caches with:"
echo "  php artisan config:cache"
echo "  php artisan route:cache"
echo "  php artisan view:cache"
