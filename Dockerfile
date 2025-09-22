# Base image with PHP + Apache
FROM php:8.2-apache

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git unzip zip libpng-dev libjpeg-dev libfreetype6-dev libpq-dev postgresql-client \
    nodejs npm gettext-base \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install gd pdo pdo_pgsql bcmath opcache

# Enable Apache rewrite
RUN a2enmod rewrite

# Copy Composer from official image
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www/html

# Copy only composer files first (for caching)
COPY composer.json composer.lock ./
RUN composer install --no-dev --optimize-autoloader --no-interaction --prefer-dist

# Copy package.json for frontend build
COPY package*.json ./
RUN npm install

# Copy the rest of the project
COPY . .

# Build frontend assets (Laravel Mix)
RUN npm run production

# Fix permissions
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

# Copy and prepare entrypoint
COPY entrypoint.sh /entrypoint.sh
RUN chmod +x /entrypoint.sh

# Expose port (Render will override, but this helps locally)
EXPOSE 8080

# Use entrypoint script
ENTRYPOINT ["/entrypoint.sh"]
