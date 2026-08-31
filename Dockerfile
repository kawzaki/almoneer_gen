FROM php:8.2-apache

# 1. Install system dependencies & PHP extensions
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    git \
    curl \
    sqlite3 \
    libsqlite3-dev \
    && docker-php-ext-install pdo_sqlite mbstring exif pcntl bcmath gd opcache

# 2. Enable Apache rewrite module
RUN a2enmod rewrite

# 3. Configure Apache Document Root to /var/www/html/public
ENV APACHE_DOCUMENT_ROOT /var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/conf-available/*.conf

# 4. Set Working Directory
WORKDIR /var/www/html

# 5. Copy Application Source Code
COPY . .

# 6. Install Composer & Dependencies
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
RUN composer install --no-dev --optimize-autoloader --no-interaction

# 7. Ensure all storage and framework directories exist
RUN mkdir -p storage/framework/views \
             storage/framework/cache/data \
             storage/framework/sessions \
             storage/logs \
             bootstrap/cache \
             database \
    && touch database/database.sqlite

# 8. Set File Permissions
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache /var/www/html/database
RUN chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache /var/www/html/database

# 9. Expose Port 80
EXPOSE 80

# 10. Startup Command: Create directories, Run Migrations & Seeders, then Start Apache
CMD sh -c "mkdir -p storage/framework/views storage/framework/cache/data storage/framework/sessions && chown -R www-data:www-data storage bootstrap/cache database && php artisan migrate --force && php artisan db:seed --force && apache2-foreground"
