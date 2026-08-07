# Stage 1: Build frontend assets with Node.js
FROM node:20-alpine AS node-builder
WORKDIR /app
COPY package*.json vite.config.js ./
RUN npm ci || npm install
COPY . .
RUN npm run build

# Stage 2: PHP 8.2 Apache runtime
FROM php:8.2-apache

# Set working directory
WORKDIR /var/www/html

# Install system dependencies & required PHP extensions
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libjpeg62-turbo-dev \
    libfreetype6-dev \
    libonig-dev \
    libxml2-dev \
    libzip-dev \
    libicu-dev \
    zip \
    unzip \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd zip opcache intl \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Enable Apache mod_rewrite module
RUN a2enmod rewrite

# Update Apache DocumentRoot to point to Laravel's /public directory
ENV APACHE_DOCUMENT_ROOT /var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# Allow .htaccess overrides for Laravel routing
RUN echo '<Directory /var/www/html/public>\n\
    Options Indexes FollowSymLinks\n\
    AllowOverride All\n\
    Require all granted\n\
</Directory>' > /etc/apache2/conf-available/laravel.conf \
    && a2enconf laravel

# Install Composer binary
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copy application files
COPY . .

# Copy Vite compiled assets from Node build stage
COPY --from=node-builder /app/public/build ./public/build

# Install production PHP dependencies with Composer
RUN composer install --no-dev --optimize-autoloader --no-interaction

# Create necessary upload & storage directories with proper permissions
RUN mkdir -p /var/www/html/public/upload/customers/avatars \
    /var/www/html/public/upload/customers/documents \
    /var/www/html/public/upload/cars \
    /var/www/html/storage/logs \
    /var/www/html/storage/framework/views \
    /var/www/html/storage/framework/sessions \
    /var/www/html/storage/framework/cache \
    /var/www/html/bootstrap/cache \
    && chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache /var/www/html/public/upload \
    && chmod -R 777 /var/www/html/storage /var/www/html/bootstrap/cache /var/www/html/public/upload

# Make entrypoint script executable
RUN chmod +x /var/www/html/docker-entrypoint.sh

# Expose default HTTP port
EXPOSE 80

# Define container entrypoint
ENTRYPOINT ["/var/www/html/docker-entrypoint.sh"]
