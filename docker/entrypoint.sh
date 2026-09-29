#!/bin/sh
set -e

# Change directory to application root
cd /var/www/html

# If using SQLite and database file doesn't exist, create it
if [ "${DB_CONNECTION}" = "sqlite" ] || [ -z "${DB_CONNECTION}" ]; then
    mkdir -p /var/www/html/database
    if [ ! -f /var/www/html/database/database.sqlite ]; then
        touch /var/www/html/database/database.sqlite
    fi
    chown -R www-data:www-data /var/www/html/database
    chmod -R 775 /var/www/html/database
fi

# Ensure storage directories exist and have proper permissions
mkdir -p storage/framework/cache/data \
         storage/framework/sessions \
         storage/framework/views \
         storage/logs
chown -R www-data:www-data storage bootstrap/cache
chmod -R 775 storage bootstrap/cache

# Generate app key if not set
if [ -z "${APP_KEY}" ]; then
    echo "Generating Application Key..."
    php artisan key:generate --force
fi

# Run database migrations and seed default data
echo "Running migrations..."
php artisan migrate --force --no-interaction || true

echo "Seeding initial ISP packages and coverage areas..."
php artisan db:seed --force --no-interaction || true

# Optimize cache for production
echo "Caching configuration and routes..."
php artisan config:cache || true
php artisan route:cache || true
php artisan view:cache || true

echo "Starting services via Supervisor..."
exec /usr/bin/supervisord -c /etc/supervisor/conf.d/supervisord.conf
