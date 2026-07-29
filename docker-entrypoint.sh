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

# Ensure storage link & permissions exist
mkdir -p /var/www/html/storage/logs /var/www/html/storage/framework/views /var/www/html/storage/framework/sessions /var/www/html/storage/framework/cache
chmod -R 777 /var/www/html/storage /var/www/html/bootstrap/cache || true
php artisan storage:link || true

# Run DB Migrations & Seeders automatically on Aiven / Remote MySQL
if [ -n "$DB_HOST" ] && [ "$DB_HOST" != "127.0.0.1" ]; then
    echo "Running database migration and seeders for $DB_HOST..."
    php artisan migrate --force || echo "Migrations failed or already completed."
    php artisan db:seed --force || echo "Database seeding completed or skipped."
fi

# Start Apache in foreground
exec apache2-foreground
