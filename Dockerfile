# -----------------------
# Stage 1: build frontend
# -----------------------
FROM node:18-alpine AS node_builder
WORKDIR /app

# copy package manifest and install deps (cacheable)
COPY package*.json ./
RUN npm ci --no-audit --no-fund

# copy source and build assets (Mix)
COPY . .
RUN npm run production

# -----------------------
# Stage 2: PHP + Apache
# -----------------------
FROM php:8.2-apache

# install system deps and php extensions
RUN apt-get update && apt-get install -y \
    git unzip zip libpng-dev libjpeg-dev libfreetype6-dev libpq-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install gd pdo pdo_pgsql bcmath opcache \
    && rm -rf /var/lib/apt/lists/*

# enable apache rewrite
RUN a2enmod rewrite

# set DocumentRoot to /var/www/html/public
RUN sed -i 's#/var/www/html#/var/www/html/public#g' /etc/apache2/sites-available/000-default.conf

WORKDIR /var/www/html

# composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# copy composer files, install PHP deps (cache)
COPY composer.json composer.lock ./
RUN composer install --no-dev --optimize-autoloader --no-interaction --prefer-dist \
    && rm -rf ~/.composer/cache

# copy app files
COPY . .

# copy built assets from node_builder
COPY --from=node_builder /app/public /var/www/html/public

# fix permissions
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

# copy entrypoint
COPY entrypoint.sh /usr/local/bin/entrypoint.sh
RUN chmod +x /usr/local/bin/entrypoint.sh

EXPOSE 80
ENTRYPOINT ["entrypoint.sh"]
