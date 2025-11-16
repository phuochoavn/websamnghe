# 🚀 WORKFLOW 2: LARAVEL INSTALLATION

> **Dự án:** samnghethaycu.com - E-Commerce Platform
> **Version:** 3.0 Reorganized
> **Thời gian thực tế:** 15-20 phút
> **Mục tiêu:** Laravel 12 via Composer + Nginx configured

---

## 📋 PREREQUISITES

### ✅ Must Complete First

```
✅ WORKFLOW-1: VPS Infrastructure (LEMP + SSL)
✅ Infrastructure ready (Nginx, MySQL, PHP 8.4, Composer)
✅ SSL certificate obtained
✅ Domain accessible: https://samnghethaycu.com
```

### ✅ Quick Verification

```bash
# Test infrastructure
ssh deploy@69.62.82.145

# Check services
systemctl status nginx mysql php8.4-fpm redis-server | grep Active
# All should show: active (running)

# Check SSL certificate
ls /etc/letsencrypt/live/samnghethaycu.com/fullchain.pem
# Should exist

# Check Git repository
cd /var/www/samnghethaycu.com
ls -la
# Should show: README.md, .git/
```

**All OK?** → Continue!

---

## 🎯 WHAT WE'LL BUILD

```
Local Windows:
  composer create-project laravel/laravel
  ↓
  git commit & push to GitHub
  ↓
VPS:
  git pull from GitHub
  ↓
  Configure .env
  ↓
  Setup Nginx virtual host
  ↓
  Storage symlink
  ↓
Result: https://samnghethaycu.com (Laravel welcome page) ✅
```

**Philosophy:** Install on LOCAL, deploy via GIT!

---

## PART 1: INSTALL LARAVEL (LOCAL)

**Time:** 5 phút

**Windows PowerShell:**

```powershell
# Navigate to project directory
cd C:\Projects\samnghethaycu

# IMPORTANT: Backup README.md first (will be overwritten)
Copy-Item README.md README.backup.md

# Install Laravel 12
composer create-project laravel/laravel temp "^12.0"

# Move Laravel files to project root
Move-Item temp\* . -Force
Remove-Item temp

# Restore README
Copy-Item README.backup.md README.md -Force
Remove-Item README.backup.md

# This takes 2-3 minutes...
# Wait for "Application ready! Build something amazing."
```

### Verify Installation:

```powershell
# Check Laravel version
php artisan --version
# Should show: Laravel Framework 12.x.x

# List files
dir
# Should see: app/, bootstrap/, public/, vendor/, artisan, etc.
```

✅ **Checkpoint 1:** Laravel installed locally

---

## PART 2: CONFIGURE .ENV (LOCAL)

**Time:** 3 phút

### 2.1. Create .env

```powershell
# Copy .env.example
Copy-Item .env.example .env

# Generate application key
php artisan key:generate
```

### 2.2. Edit .env

```powershell
# Open in editor
notepad .env
```

**Update these values:**

```env
APP_NAME="Sam Nghe Thay Cu"
APP_ENV=production
APP_DEBUG=false
APP_TIMEZONE=Asia/Ho_Chi_Minh
APP_URL=https://samnghethaycu.com

# Database (from Workflow 2 credentials)
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=samnghethaycu
DB_USERNAME=samnghethaycu_user
DB_PASSWORD=SamNghe@DB2025

# Cache & Sessions
CACHE_STORE=redis
FILESYSTEM_DISK=local
QUEUE_CONNECTION=database
SESSION_DRIVER=redis
SESSION_LIFETIME=120

# Redis
REDIS_CLIENT=phpredis
REDIS_HOST=127.0.0.1
REDIS_PASSWORD=null
REDIS_PORT=6379
```

**Save and close**

### 2.3. Test Laravel Locally (Optional)

```powershell
# Start local server
php artisan serve

# Open browser: http://localhost:8000
# Should see Laravel welcome page

# Stop server: Ctrl+C
```

✅ **Checkpoint 2:** .env configured

---

## PART 3: COMMIT & PUSH LARAVEL

**Time:** 2 phút

**PowerShell:**

```powershell
# Check what will be committed
git status

# Add all Laravel files
git add .

# Commit
git commit -m "feat: Laravel 12 installation with production config"

# Push to GitHub
git push origin main

# Wait for push to complete...
```

✅ **Checkpoint 3:** Laravel pushed to GitHub

---

## PART 4: DEPLOY TO VPS

**Time:** 3 phút

**SSH to VPS:**

```bash
ssh deploy@69.62.82.145

# Navigate to project
cd /var/www/samnghethaycu.com

# Pull Laravel from GitHub
git pull origin main

# Verify Laravel files
ls -la
# Should see: app/, bootstrap/, public/, vendor/ (if pushed), artisan, etc.
```

### 4.1. Install Dependencies (if vendor/ not pushed)

```bash
# Install Composer dependencies
composer install --no-dev --optimize-autoloader --no-interaction

# This takes 1-2 minutes...
```

### 4.2. Copy .env to VPS

**.env is gitignored (security best practice), so copy manually:**

**Option A: Create manually**

```bash
nano .env
# Paste .env content from local
# (Copy from Windows: C:\Projects\samnghethaycu\.env)
# Ctrl+O to save, Ctrl+X to exit
```

**Option B: SCP from local (if Windows has scp)**

```powershell
# Windows PowerShell
scp C:\Projects\samnghethaycu\.env deploy@69.62.82.145:/var/www/samnghethaycu.com/.env
```

### 4.3. Generate VPS-Specific APP_KEY

```bash
cd /var/www/samnghethaycu.com

# Generate new APP_KEY for VPS
php artisan key:generate

# Verify
grep APP_KEY .env
# Should show: APP_KEY=base64:...
```

### 4.4. Set Permissions

```bash
# Set ownership
sudo chown -R deploy:www-data /var/www/samnghethaycu.com

# Critical directories for www-data
sudo chown -R www-data:www-data storage bootstrap/cache
sudo chmod -R 775 storage bootstrap/cache

# Verify
ls -la storage
# Should show: drwxrwxr-x www-data www-data
```

### 4.5. Create Storage Symlink

```bash
# Create symlink from public/storage to storage/app/public
php artisan storage:link

# Verify
ls -la public/storage
# Should show: public/storage -> ../storage/app/public
```

✅ **Checkpoint 4:** Laravel on VPS

---

## PART 5: CONFIGURE NGINX

**Time:** 5 phút

### 5.1. Create Nginx Virtual Host

```bash
# Create config
sudo nano /etc/nginx/sites-available/samnghethaycu.com
```

**Paste this configuration:**

```nginx
# HTTP → HTTPS redirect
server {
    listen 80;
    listen [::]:80;
    server_name samnghethaycu.com www.samnghethaycu.com;
    return 301 https://$host$request_uri;
}

# HTTPS - Laravel Application
server {
    listen 443 ssl http2;
    listen [::]:443 ssl http2;
    server_name samnghethaycu.com www.samnghethaycu.com;

    # Document root points to Laravel's public directory
    root /var/www/samnghethaycu.com/public;
    index index.php index.html;

    # SSL Certificates (from Workflow 2)
    ssl_certificate /etc/letsencrypt/live/samnghethaycu.com/fullchain.pem;
    ssl_certificate_key /etc/letsencrypt/live/samnghethaycu.com/privkey.pem;
    ssl_protocols TLSv1.2 TLSv1.3;
    ssl_ciphers HIGH:!aNULL:!MD5;
    ssl_prefer_server_ciphers on;

    # Logging
    access_log /var/log/nginx/samnghethaycu-access.log;
    error_log /var/log/nginx/samnghethaycu-error.log;

    # Gzip Compression
    gzip on;
    gzip_vary on;
    gzip_types text/plain text/css application/json application/javascript text/xml application/xml;

    # Laravel URL Rewriting
    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    # PHP-FPM Configuration
    location ~ \.php$ {
        include snippets/fastcgi-php.conf;
        fastcgi_pass unix:/run/php/php8.4-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;

        # Increase timeouts for long requests
        fastcgi_read_timeout 300;
    }

    # Deny access to hidden files (except .well-known for SSL)
    location ~ /\.(?!well-known).* {
        deny all;
    }

    # Security Headers
    add_header X-Frame-Options "SAMEORIGIN" always;
    add_header X-Content-Type-Options "nosniff" always;
    add_header X-XSS-Protection "1; mode=block" always;
    add_header Referrer-Policy "no-referrer-when-downgrade" always;

    # Asset Caching
    location ~* \.(jpg|jpeg|png|gif|ico|css|js|svg|woff|woff2|ttf|eot)$ {
        expires 1y;
        add_header Cache-Control "public, immutable";
    }
}
```

**Save:** `Ctrl+O`, Enter, `Ctrl+X`

### 5.2. Enable Site & Remove Default

```bash
# Create symlink to enable site
sudo ln -s /etc/nginx/sites-available/samnghethaycu.com /etc/nginx/sites-enabled/

# Remove default Nginx site
sudo rm -f /etc/nginx/sites-enabled/default

# Test Nginx configuration
sudo nginx -t

# Expected output:
# nginx: the configuration file /etc/nginx/nginx.conf syntax is ok
# nginx: configuration file /etc/nginx/nginx.conf test is successful
```

### 5.3. Restart Nginx

```bash
# Restart Nginx
sudo systemctl restart nginx

# Check status
sudo systemctl status nginx
# Should show: active (running)
```

✅ **Checkpoint 5:** Nginx configured

---

## PART 6: TEST LARAVEL

**Time:** 2 phút

### 6.1. Run Migrations

```bash
cd /var/www/samnghethaycu.com

# Run initial migrations (creates default Laravel tables)
php artisan migrate

# Type: yes (when prompted about production)
```

### 6.2. Clear & Cache

```bash
# Clear all caches
php artisan optimize:clear

# Cache config for performance
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

### 6.3. Test in Browser

**Open:**

```
https://samnghethaycu.com
```

**Should see:** 🎉 **Laravel Welcome Page!**

### 6.4. Add Health Check Endpoint

**On Local (Windows):**

```powershell
cd C:\Projects\samnghethaycu

# Edit routes/web.php
notepad routes\web.php
```

**Add after existing routes:**

```php
Route::get('/health', function () {
    try {
        DB::connection()->getPdo();
        $dbStatus = 'connected';
    } catch (\Exception $e) {
        $dbStatus = 'failed: ' . $e->getMessage();
    }

    return response()->json([
        'status' => 'healthy',
        'timestamp' => now()->toDateTimeString(),
        'app' => config('app.name'),
        'environment' => config('app.env'),
        'database' => $dbStatus,
        'cache' => Cache::has('health_check') ? 'working' : 'available',
        'redis' => Redis::connection()->ping() ? 'connected' : 'failed',
    ]);
});
```

**Save, commit, push:**

```powershell
git add routes\web.php
git commit -m "feat: add health check endpoint with service status"
git push origin main
```

**Deploy on VPS:**

```bash
# Use our deployment script!
deploy-sam
```

**Test health endpoint:**

```bash
curl https://samnghethaycu.com/health
```

**Expected output:**

```json
{
  "status": "healthy",
  "timestamp": "2025-11-16 17:00:00",
  "app": "Sam Nghe Thay Cu",
  "environment": "production",
  "database": "connected",
  "cache": "available",
  "redis": "connected"
}
```

✅ **Checkpoint 6:** Laravel fully working!

---

## VERIFICATION

### Final Checklist

- [ ] Website accessible: https://samnghethaycu.com ✅
- [ ] SSL valid (green padlock in browser) ✅
- [ ] Laravel welcome page loads ✅
- [ ] Health endpoint returns JSON ✅
- [ ] Database connected ✅
- [ ] Redis connected ✅
- [ ] Git deployment working (deploy-sam) ✅

**All checked?** → SUCCESS! 🎉

---

## ✅ WORKFLOW 3 COMPLETE!

### Laravel Ready:

```
✅ Laravel 12 installed (via Git!)
✅ .env configured for production
✅ Nginx virtual host configured
✅ SSL certificate applied
✅ Database connected
✅ Redis cache working
✅ Storage symlink created
✅ Health check endpoint
✅ Git deployment tested
✅ Website live: https://samnghethaycu.com
```

### Git Workflow Working:

```
1. Code on Windows
2. git add . && git commit -m "..." && git push
3. SSH to VPS
4. deploy-sam
5. Changes live in 30 seconds! ✅
```

### Next Step:

```
→ WORKFLOW-3-GIT-WORKFLOW-SETUP.md
  Setup Git version control (Local → GitHub → VPS)
```

---

## 🔧 TROUBLESHOOTING

### Issue: 500 Internal Server Error

**Check Laravel logs:**

```bash
tail -50 /var/www/samnghethaycu.com/storage/logs/laravel.log
```

**Check Nginx error log:**

```bash
sudo tail -50 /var/log/nginx/samnghethaycu-error.log
```

**Common fixes:**

```bash
# Fix permissions
sudo chown -R www-data:www-data storage bootstrap/cache
sudo chmod -R 775 storage bootstrap/cache

# Clear caches
php artisan optimize:clear

# Restart PHP-FPM
sudo systemctl restart php8.4-fpm
```

### Issue: Database Connection Error

**Check credentials:**

```bash
cat .env | grep DB_
```

**Test MySQL connection:**

```bash
mysql -u samnghethaycu_user -p samnghethaycu
# Password: SamNghe@DB2025
```

**Check database credentials file:**

```bash
cat ~/credentials/database.txt
```

### Issue: Nginx 403 Forbidden

**Fix directory permissions:**

```bash
sudo chmod 755 /var/www/samnghethaycu.com
sudo chmod 755 /var/www/samnghethaycu.com/public
```

### Issue: SSL Certificate Not Working

**Check certificate:**

```bash
sudo certbot certificates
```

**Verify Nginx config:**

```bash
sudo nginx -t
```

---

**Created:** 2025-11-16
**Version:** 3.0 Modular
**Time:** 15-20 minutes actual

---

**END OF WORKFLOW 3** 🚀
