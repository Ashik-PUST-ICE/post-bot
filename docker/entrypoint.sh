#!/bin/sh

# Set directory permissions
chmod -R 777 /var/www/html/storage /var/www/html/bootstrap/cache

# Create storage symlink
php artisan storage:link --force

# Generate app key if not set
if [ -z "$APP_KEY" ]; then
    php artisan key:generate --force
fi

# Run migrations (force for production/docker deployment)
php artisan migrate --force

# Seed the database only if settings are empty
php artisan tinker --execute="if (\App\Models\Setting::count() === 0) { \Illuminate\Support\Facades\Artisan::call('db:seed', ['--force' => true]); }"

# Optimize Laravel performance by caching config and views
php artisan config:cache
php artisan view:cache

# Dynamically configure Nginx port if PORT env is set (standard for Render/PaaS)
if [ ! -z "$PORT" ]; then
    sed -i "s/listen 80;/listen $PORT;/g" /etc/nginx/http.d/default.conf
    sed -i "s/listen \[::\]:80;/listen \[::\]:$PORT;/g" /etc/nginx/http.d/default.conf
fi

# Run supervisor to start Nginx & PHP-FPM & Queue worker
exec supervisord -c /etc/supervisor/conf.d/supervisord.conf
