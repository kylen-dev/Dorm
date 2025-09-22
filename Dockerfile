# Use PHP 8.2 with Apache
FROM php:8.2-apache

# Install required system dependencies
RUN apt-get update && apt-get install -y \
    git unzip zip libpng-dev libjpeg-dev libfreetype6-dev libpq-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install gd pdo pdo_pgsql

# Enable Apache rewrite module (needed for Laravel routes)
RUN a2enmod rewrite

# Set Apache DocumentRoot to /var/www/html/public
RUN sed -i 's#/var/www/html#/var/www/html/public#g' /etc/apache2/sites-available/000-default.conf

# Set working directory
WORKDIR /var/www/html

# Install Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Copy project files
COPY . .

# Install PHP dependencies
RUN composer install --no-dev --optimize-autoloader

# Fix storage/cache permissions for Laravel
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

# Expose Apache port
EXPOSE 80

# Start Apache in the foreground
CMD ["apache2-foreground"]
