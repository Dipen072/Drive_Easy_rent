#!/bin/bash
set -e

echo "=== Starting DriveEase Laravel Application Setup ==="

# 1. Clear existing caches safely
echo "Clearing Laravel application caches..."
php artisan config:clear || true
php artisan route:clear || true
php artisan view:clear || true
php artisan cache:clear || true

# 2. Optimize for production if APP_ENV is production
if [ "$APP_ENV" = "production" ]; then
    echo "Caching configuration for production..."
    php artisan config:cache || true
    php artisan route:cache || true
    php artisan view:cache || true
fi

# 3. Create storage symlink
echo "Creating storage symlink..."
php artisan storage:link --force || true

# 4. Ensure upload & storage directories exist with full permissions
echo "Setting up directory permissions..."
mkdir -p /var/www/html/public/upload/customers/avatars \
         /var/www/html/public/upload/customers/documents \
         /var/www/html/public/upload/cars \
         /var/www/html/storage/logs \
         /var/www/html/storage/framework/views \
         /var/www/html/storage/framework/sessions \
         /var/www/html/storage/framework/cache \
         /var/www/html/bootstrap/cache

chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache /var/www/html/public/upload || true
chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache /var/www/html/public/upload || true

# 5. Execute DB Migrations automatically if DB_HOST is defined
if [ -n "$DB_HOST" ] && [ "$DB_HOST" != "127.0.0.1" ]; then
    echo "Database host found ($DB_HOST). Running database migrations..."
    php artisan migrate --force || echo "Database migration failed or already up to date."
fi

echo "=== DriveEase Ready. Starting Web Server ==="

# Start Apache in foreground
exec apache2-foreground
