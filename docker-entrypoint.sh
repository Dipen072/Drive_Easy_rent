#!/bin/bash
set -e

echo "=== Starting DriveEase Laravel Application Setup ==="

# 1. Ensure upload & storage directories exist with full permissions FIRST
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
chmod -R 777 /var/www/html/storage /var/www/html/bootstrap/cache /var/www/html/public/upload || true

# 2. Dynamically configure Apache port from Render $PORT environment variable (default 80)
CONTAINER_PORT="${PORT:-80}"
echo "Configuring Apache to listen on port ${CONTAINER_PORT}..."
sed -i "s/Listen [0-9]*/Listen ${CONTAINER_PORT}/g" /etc/apache2/ports.conf
sed -i "s/<VirtualHost \*:[0-9]*>/<VirtualHost *:${CONTAINER_PORT}>/g" /etc/apache2/sites-available/*.conf || true

# 3. Clear existing caches safely
echo "Clearing Laravel application caches..."
php artisan config:clear || true
php artisan route:clear || true
php artisan view:clear || true
php artisan cache:clear || true

# 4. Optimize for production if APP_ENV is production
if [ "$APP_ENV" = "production" ]; then
    echo "Caching configuration for production..."
    php artisan config:cache || true
    php artisan route:cache || true
    php artisan view:cache || true
fi

# 5. Create storage symlink
echo "Creating storage symlink..."
php artisan storage:link --force || true

# 6. Execute DB Migrations automatically if DB_HOST is defined
if [ -n "$DB_HOST" ] && [ "$DB_HOST" != "127.0.0.1" ]; then
    echo "Database host found ($DB_HOST). Running database migrations..."
    php artisan migrate --force || echo "Database migration failed or already up to date."
fi

echo "=== DriveEase Ready. Starting Web Server on Port ${CONTAINER_PORT} ==="

# Start Apache in foreground
exec apache2-foreground
