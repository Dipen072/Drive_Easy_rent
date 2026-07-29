#!/bin/bash
set -e

# Clear & Cache Laravel Configurations
php artisan config:clear
php artisan route:clear
php artisan view:clear
php artisan cache:clear

# Cache for production performance if APP_ENV is production
if [ "$APP_ENV" = "production" ]; then
    php artisan config:cache || true
    php artisan route:cache || true
    php artisan view:cache || true
fi

# Ensure storage link exists
php artisan storage:link || true

# Run DB Migrations automatically if DB_HOST is set
if [ -n "$DB_HOST" ]; then
    echo "Attempting database migration..."
    php artisan migrate --force || echo "Migrations skipped or already up to date."
fi

# Start Apache in foreground
exec apache2-foreground
