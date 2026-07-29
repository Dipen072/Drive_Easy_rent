#!/bin/bash
set -e

# Clear & Cache Laravel Configurations safely
php artisan config:clear || true
php artisan route:clear || true
php artisan view:clear || true
php artisan cache:clear || true

# Cache for production performance if APP_ENV is production
if [ "$APP_ENV" = "production" ]; then
    php artisan config:cache || true
    php artisan route:cache || true
    php artisan view:cache || true
fi

# Ensure storage & upload link & permissions exist
mkdir -p /var/www/html/public/upload/customers/avatars \
         /var/www/html/public/upload/customers/documents \
         /var/www/html/public/upload/cars \
         /var/www/html/storage/logs \
         /var/www/html/storage/framework/views \
         /var/www/html/storage/framework/sessions \
         /var/www/html/storage/framework/cache
chown -R www-data:www-data /var/www/html/public/upload /var/www/html/storage /var/www/html/bootstrap/cache || true
chmod -R 777 /var/www/html/public/upload /var/www/html/storage /var/www/html/bootstrap/cache || true
php artisan storage:link || true

# Run DB Migrations automatically if DB_HOST is set and not 127.0.0.1
if [ -n "$DB_HOST" ] && [ "$DB_HOST" != "127.0.0.1" ]; then
    echo "Running database migration for $DB_HOST..."
    php artisan migrate --force || echo "Migrations failed or already completed."
fi

# Start Apache in foreground
exec apache2-foreground
