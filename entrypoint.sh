#!/usr/bin/env bash
set -e

# Render provides $PORT env var. Default to 8080 if not set.
PORT=${PORT:-8080}

# Replace Apache’s default port with $PORT
sed -i "s/Listen 80/Listen ${PORT}/" /etc/apache2/ports.conf
sed -i "s/:80/:${PORT}/" /etc/apache2/sites-available/000-default.conf

# Laravel setup
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan storage:link || true

# Run migrations on boot (safe if DB already migrated)
php artisan migrate --force || true

# Start Apache
apache2-foreground
