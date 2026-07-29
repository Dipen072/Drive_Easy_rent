#!/bin/bash
set -e

# Clear & Cache Laravel Configurations safely (prevent container crash if DB is not ready)
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

# Ensure storage link exists
php artisan storage:link || true

# Run DB Migrations automatically if DB_HOST is set and not 127.0.0.1
if [ -n "$DB_HOST" ] && [ "$DB_HOST" != "127.0.0.1" ]; then
    echo "Attempting database migration for $DB_HOST..."
    php artisan migrate --force || echo "Migrations skipped or failed."
fi

# Start Apache in foreground
exec apache2-foreground
