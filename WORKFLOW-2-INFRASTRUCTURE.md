# 🏗️ WORKFLOW 2: INFRASTRUCTURE & APPLICATION

> **Dự án:** samnghethaycu.com - E-Commerce Platform
> **Version:** 2.0 Professional
> **Thời gian thực tế:** 35-50 phút (experienced dev)
> **Mục tiêu:** VPS trống → Laravel + Admin panel working (via Git!)

---

## 📋 MỤC LỤC

- [PREREQUISITES](#prerequisites-must-complete-first)
- [OVERVIEW](#overview)
- [PART 1: LEMP Stack Installation](#part-1-lemp-stack-installation)
- [PART 2: SSL Certificate](#part-2-ssl-certificate)
- [PART 3: Laravel Installation via Git](#part-3-laravel-installation-via-git)
- [PART 4: Database & Filament](#part-4-database--filament)
- [PART 5: First Git Deployment](#part-5-first-git-deployment)
- [VERIFICATION](#verification)

---

## PREREQUISITES: MUST COMPLETE FIRST

### ✅ Workflow 1 Complete

```
✅ WORKFLOW-1-GIT-FOUNDATION.md completed
✅ Git working: Local → GitHub → VPS
✅ deploy-sam command ready
✅ Repository cloned to /var/www/samnghethaycu.com
```

**If NOT complete:** Stop and do Workflow 1 first!

---

### ✅ Server Requirements

```
✅ Ubuntu 24.04 LTS VPS
✅ Root/sudo access
✅ Domain pointed to VPS IP (A record)
✅ Port 80, 443 open
✅ 2GB+ RAM (recommended)
```

---

### ✅ Quick Check

**On VPS:**

```bash
# Should work (from Workflow 1)
ssh deploy@69.62.82.145

# Should exist
ls /var/www/samnghethaycu.com
# Should show: README.md, .git, etc.

# deploy-sam command exists?
type deploy-sam
# Should show: aliased to...
```

**If any fails:** Go back to Workflow 1!

---

## OVERVIEW

**What we'll build:**

```
Fresh VPS
    ↓
Install LEMP (Nginx + MySQL + PHP 8.4)
    ↓
SSL Certificate (Let's Encrypt)
    ↓
Laravel 12 (via Composer on LOCAL, then Git push)
    ↓
Database Schema + Filament
    ↓
Test Git Deployment
    ↓
Result: https://samnghethaycu.com/admin ✅
```

**Philosophy:** Infrastructure first, Code via Git

---

## PART 1: LEMP STACK INSTALLATION

**Time:** 20-25 phút

### 1.1. Update System

**On VPS (as root or sudo user):**

```bash
# Connect
ssh root@69.62.82.145
# Or: ssh deploy@69.62.82.145

# Update
apt update && apt upgrade -y

# Install basic tools
apt install -y curl wget git unzip software-properties-common
```

✅ **Checkpoint 1.1:** System updated

---

### 1.2. Install Nginx

```bash
# Install
apt install nginx -y

# Start & enable
systemctl start nginx
systemctl enable nginx

# Check status
systemctl status nginx
# Should show: active (running)

# Configure firewall
ufw allow 'Nginx Full'
ufw allow OpenSSH
ufw enable
# Type: y

# Verify
curl http://localhost
# Should show Nginx welcome page
```

✅ **Checkpoint 1.2:** Nginx installed

---

### 1.3. Install MySQL 8

```bash
# Install
apt install mysql-server -y

# Secure installation
mysql_secure_installation

# Answers:
# - VALIDATE PASSWORD? n
# - Set root password? Y
#   Password: RootMySQL@2025
# - Remove anonymous users? Y
# - Disallow root login remotely? Y
# - Remove test database? Y
# - Reload privilege tables? Y

# Create database
mysql -u root -p
# Password: RootMySQL@2025
```

**In MySQL:**

```sql
CREATE DATABASE samnghethaycu CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
CREATE USER 'samnghethaycu_user'@'localhost' IDENTIFIED BY 'SamNghe@DB2025';
GRANT ALL PRIVILEGES ON samnghethaycu.* TO 'samnghethaycu_user'@'localhost';
FLUSH PRIVILEGES;
SHOW DATABASES;
EXIT;
```

**Save credentials:**

```bash
# As deploy user
mkdir -p ~/credentials
cat > ~/credentials/database.txt << 'EOF'
MySQL Root: RootMySQL@2025
Database: samnghethaycu
DB User: samnghethaycu_user
DB Pass: SamNghe@DB2025
EOF
chmod 600 ~/credentials/database.txt
```

✅ **Checkpoint 1.3:** MySQL installed & database created

---

### 1.4. Install PHP 8.4

```bash
# Add PPA
add-apt-repository ppa:ondrej/php -y
apt update

# Install PHP 8.4 + extensions for Laravel
apt install -y php8.4-fpm php8.4-cli php8.4-common \
  php8.4-mysql php8.4-mbstring php8.4-xml php8.4-bcmath \
  php8.4-curl php8.4-gd php8.4-zip php8.4-intl \
  php8.4-redis php8.4-imagick

# Verify
php -v
# Should show: PHP 8.4.x

# Start PHP-FPM
systemctl start php8.4-fpm
systemctl enable php8.4-fpm
systemctl status php8.4-fpm
# Should show: active (running)
```

✅ **Checkpoint 1.4:** PHP 8.4 installed

---

### 1.5. Install Redis

```bash
# Install
apt install redis-server -y

# Start & enable
systemctl start redis-server
systemctl enable redis-server

# Test
redis-cli ping
# Should return: PONG
```

✅ **Checkpoint 1.5:** Redis installed

---

### 1.6. Install Composer

```bash
# Download
curl -sS https://getcomposer.org/installer -o composer-setup.php

# Install globally
php composer-setup.php --install-dir=/usr/local/bin --filename=composer

# Clean up
rm composer-setup.php

# Verify
composer --version
# Should show: Composer version 2.x
```

✅ **Checkpoint 1.6:** Composer installed

---

### 1.7. Install Node.js 20

```bash
# Add repository
curl -fsSL https://deb.nodesource.com/setup_20.x | bash -

# Install
apt install -y nodejs

# Verify
node -v  # v20.x.x
npm -v   # 10.x.x
```

✅ **Checkpoint 1.7:** Node.js installed

---

## PART 2: SSL CERTIFICATE

**Time:** 5 phút

### 2.1. Install Certbot

```bash
apt install certbot python3-certbot-nginx -y
```

✅ **Checkpoint 2.1:** Certbot installed

---

### 2.2. Obtain SSL Certificate

**IMPORTANT:** Domain MUST point to VPS first!

**Check DNS:**

```bash
# Check if domain points to VPS
dig +short samnghethaycu.com
# Should show: 69.62.82.145

# If not ready, wait for DNS propagation (up to 48h, usually 5-30 min)
```

**Obtain certificate:**

```bash
# Stop Nginx temporarily
systemctl stop nginx

# Get certificate
certbot certonly --standalone \
  -d samnghethaycu.com \
  -d www.samnghethaycu.com

# Email: your-email@example.com
# Terms: A (Agree)
# Share email: N (No)

# Success message:
# Certificate is saved at: /etc/letsencrypt/live/samnghethaycu.com/fullchain.pem
# Key is saved at: /etc/letsencrypt/live/samnghethaycu.com/privkey.pem
```

**Test renewal:**

```bash
certbot renew --dry-run
# Should succeed
```

✅ **Checkpoint 2.2:** SSL certificate obtained

---

## PART 3: LARAVEL INSTALLATION VIA GIT

**Time:** 10 phút

**Key Difference:** We install Laravel on LOCAL, then push to GitHub!

### 3.1. Install Laravel on Local (Windows)

**PowerShell:**

```powershell
# Navigate to project (from Workflow 1)
cd C:\Projects\samnghethaycu

# Install Laravel 12
composer create-project laravel/laravel . "^12.0" --prefer-dist

# This takes 2-3 minutes...
```

**Wait for completion...**

✅ **Checkpoint 3.1:** Laravel installed locally

---

### 3.2. Configure .env (Local)

**PowerShell:**

```powershell
# Copy .env.example
cp .env.example .env

# Generate key
php artisan key:generate

# Edit .env
notepad .env
```

**Update these values:**

```env
APP_NAME="Sam Nghe Thay Cu"
APP_ENV=production
APP_DEBUG=false
APP_URL=https://samnghethaycu.com

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=samnghethaycu
DB_USERNAME=samnghethaycu_user
DB_PASSWORD=SamNghe@DB2025

CACHE_STORE=redis
SESSION_DRIVER=redis
QUEUE_CONNECTION=database
```

**Save & close**

✅ **Checkpoint 3.2:** .env configured

---

### 3.3. Commit & Push Laravel

**PowerShell:**

```powershell
# Add all Laravel files
git add .

# Check what will be committed
git status

# Commit
git commit -m "feat: Laravel 12 initial installation with configuration"

# Push to GitHub
git push origin main
```

**Wait for push to complete...**

✅ **Checkpoint 3.3:** Laravel pushed to GitHub

---

### 3.4. Pull Laravel on VPS

**On VPS (as deploy user):**

```bash
# Navigate to project
cd /var/www/samnghethaycu.com

# Pull latest code
git pull origin main

# Verify Laravel files
ls -la
# Should see: app/, bootstrap/, public/, vendor/ (if pushed), etc.

# Install Composer dependencies (if vendor/ not pushed)
composer install --no-dev --optimize-autoloader

# Set permissions
sudo chown -R deploy:www-data .
sudo chown -R www-data:www-data storage bootstrap/cache
sudo chmod -R 775 storage bootstrap/cache
```

✅ **Checkpoint 3.4:** Laravel on VPS

---

### 3.5. Copy .env to VPS

**.env is gitignored (correct!), so copy manually:**

**On VPS:**

```bash
cd /var/www/samnghethaycu.com

# Create .env
nano .env

# Paste the .env content from local
# (Copy from Windows: C:\Projects\samnghethaycu\.env)
# Ctrl+O to save, Ctrl+X to exit

# Generate new APP_KEY (VPS-specific)
php artisan key:generate

# Verify
cat .env | grep APP_KEY
# Should show: APP_KEY=base64:...
```

✅ **Checkpoint 3.5:** .env on VPS

---

### 3.6. Create Storage Symlink

```bash
cd /var/www/samnghethaycu.com

# Create symlink
php artisan storage:link

# Verify
ls -la public/storage
# Should show: public/storage -> ../storage/app/public
```

✅ **Checkpoint 3.6:** Storage linked

---

### 3.7. Configure Nginx Virtual Host

**On VPS:**

```bash
# Create Nginx config
sudo nano /etc/nginx/sites-available/samnghethaycu.com
```

**Paste this config:**

```nginx
# HTTP → HTTPS redirect
server {
    listen 80;
    listen [::]:80;
    server_name samnghethaycu.com www.samnghethaycu.com;
    return 301 https://$host$request_uri;
}

# HTTPS
server {
    listen 443 ssl http2;
    listen [::]:443 ssl http2;
    server_name samnghethaycu.com www.samnghethaycu.com;

    root /var/www/samnghethaycu.com/public;
    index index.php index.html;

    # SSL Certificates
    ssl_certificate /etc/letsencrypt/live/samnghethaycu.com/fullchain.pem;
    ssl_certificate_key /etc/letsencrypt/live/samnghethaycu.com/privkey.pem;
    ssl_protocols TLSv1.2 TLSv1.3;
    ssl_ciphers HIGH:!aNULL:!MD5;

    # Logging
    access_log /var/log/nginx/samnghethaycu-access.log;
    error_log /var/log/nginx/samnghethaycu-error.log;

    # Laravel
    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        include snippets/fastcgi-php.conf;
        fastcgi_pass unix:/run/php/php8.4-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }

    # Security headers
    add_header X-Frame-Options "SAMEORIGIN" always;
    add_header X-Content-Type-Options "nosniff" always;
    add_header X-XSS-Protection "1; mode=block" always;
}
```

**Save: Ctrl+O, Enter, Ctrl+X**

**Enable site:**

```bash
# Enable site
sudo ln -s /etc/nginx/sites-available/samnghethaycu.com /etc/nginx/sites-enabled/

# Remove default
sudo rm -f /etc/nginx/sites-enabled/default

# Test config
sudo nginx -t
# Should show: syntax is okay, test is successful

# Restart Nginx
sudo systemctl restart nginx
```

✅ **Checkpoint 3.7:** Nginx configured

---

### 3.8. Test Laravel

**Browser:**

```
https://samnghethaycu.com
```

**Should see:** Laravel welcome page!

✅ **Checkpoint 3.8:** Laravel accessible

---

## PART 4: DATABASE & FILAMENT

**Time:** 10 phút

### 4.1. Run Laravel Migrations

**On VPS:**

```bash
cd /var/www/samnghethaycu.com

# Run migrations
php artisan migrate

# Type: yes (when prompted)

# Verify
php artisan db:show
# Should show database info
```

✅ **Checkpoint 4.1:** Migrations run

---

### 4.2. Install Filament (on LOCAL)

**Windows PowerShell:**

```powershell
cd C:\Projects\samnghethaycu

# Install Filament
composer require filament/filament:"^3.2" -W

# Install admin panel
php artisan filament:install --panels
# Panel ID: admin (press Enter)

# Commit & push
git add .
git commit -m "feat: install Filament v3 admin panel"
git push origin main
```

✅ **Checkpoint 4.2:** Filament installed

---

### 4.3. Deploy Filament to VPS

**On VPS:**

```bash
# Use our deployment script!
deploy-sam
```

**Should pull Filament code automatically!**

✅ **Checkpoint 4.3:** Filament on VPS

---

### 4.4. Create Admin User

**On VPS:**

```bash
cd /var/www/samnghethaycu.com

# Create admin
php artisan make:filament-user

# Name: Admin
# Email: admin@samnghethaycu.com
# Password: Admin@123456
```

✅ **Checkpoint 4.4:** Admin user created

---

### 4.5. Test Admin Panel

**Browser:**

```
https://samnghethaycu.com/admin
```

**Login:**
- Email: `admin@samnghethaycu.com`
- Password: `Admin@123456`

**Should see:** Filament dashboard!

✅ **Checkpoint 4.5:** Admin panel working

---

## PART 5: FIRST GIT DEPLOYMENT

**Time:** 3 phút

### Test Full Workflow

**Local (Windows):**

```powershell
cd C:\Projects\samnghethaycu

# Create health check route
notepad routes/web.php
```

**Add this route:**

```php
Route::get('/health', function () {
    return response()->json([
        'status' => 'healthy',
        'timestamp' => now()->toDateTimeString(),
        'app' => config('app.name'),
        'db' => DB::connection()->getPdo() ? 'connected' : 'failed',
    ]);
});
```

**Save, commit, push:**

```powershell
git add routes/web.php
git commit -m "feat: add health check endpoint"
git push origin main
```

**Deploy on VPS:**

```bash
deploy-sam
```

**Test health endpoint:**

```bash
curl https://samnghethaycu.com/health
```

**Should return:**

```json
{
  "status": "healthy",
  "timestamp": "2025-11-16 16:00:00",
  "app": "Sam Nghe Thay Cu",
  "db": "connected"
}
```

✅ **Git deployment working!**

---

## VERIFICATION

### Final Checklist

- [ ] Website: https://samnghethaycu.com ✅
- [ ] Admin: https://samnghethaycu.com/admin ✅
- [ ] SSL certificate valid (green padlock) ✅
- [ ] Health endpoint working ✅
- [ ] Git deployment tested ✅
- [ ] Database connected ✅

**All green?** → WORKFLOW 2 COMPLETE! 🎉

---

## ✅ WORKFLOW 2 HOÀN THÀNH!

### Đã có:

```
✅ LEMP Stack (Nginx, MySQL 8, PHP 8.4)
✅ SSL Certificate (Let's Encrypt)
✅ Redis cache
✅ Laravel 12 installed (via Git!)
✅ Filament v3 admin panel
✅ Database configured
✅ Admin user created
✅ Git deployment tested
✅ Website live: https://samnghethaycu.com
✅ Admin panel: https://samnghethaycu.com/admin
```

### Professional Workflow:

```
1. Code on Windows
2. git add . && git commit && git push
3. SSH to VPS
4. deploy-sam
5. Changes live in 30 seconds!
```

---

## 🚀 NEXT: WORKFLOW-3-BACKEND-COMPLETE.md

Now we have infrastructure + basic Laravel.

Next workflow: Add business logic, models, Filament customization!

---

**Created:** 2025-11-16
**Version:** 2.0 Professional
**Tested:** Production-ready ✅

---

**END OF WORKFLOW 2** 🏗️
