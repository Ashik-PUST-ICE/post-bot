#!/bin/sh

# Set directory permissions
chmod -R 777 /var/www/html/storage /var/www/html/bootstrap/cache

# Generate app key if not set
if [ -z "$APP_KEY" ]; then
    php artisan key:generate --force
fi

# Run migrations (force for production/docker deployment)
php artisan migrate --force

# Run supervisor to start Nginx & PHP-FPM & Queue worker
exec supervisord -c /etc/supervisor/conf.d/supervisord.conf
