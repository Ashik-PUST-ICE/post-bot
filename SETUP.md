# SocialAgent — Setup Commands

## Step 1: Database Migration
```bash
php artisan queue:table
php artisan migrate
```

## Step 2: Clear Caches
```bash
php artisan config:clear
php artisan route:clear
php artisan cache:clear
```

## Step 3: Queue Worker (background এ চালাও)
```bash
php artisan queue:work --tries=3 --timeout=60
```

## Step 4: Dev Server
```bash
php artisan serve
```
