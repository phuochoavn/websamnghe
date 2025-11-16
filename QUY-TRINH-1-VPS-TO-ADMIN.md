# 🚀 QUY TRÌNH 1: TỪ VPS TRỐNG ĐẾN ADMIN PANEL HOẠT ĐỘNG

> **Dự án:** samnghethaycu.com - E-Commerce Platform
> **Phiên bản:** 1.0
> **Thời gian:** ~4 giờ 15 phút
> **Mục tiêu:** Setup VPS từ đầu → Admin panel hoạt động với CRUD cơ bản

---

## 📋 MỤC LỤC

- [PHASE 0: Infrastructure Setup (3h)](#phase-0-infrastructure-setup)
- [PHASE 1: Laravel Deployment (25 phút)](#phase-1-laravel-deployment)
- [PHASE 2: Database & Models Skeleton (60 phút)](#phase-2-database--models-skeleton)
- [VERIFICATION & TROUBLESHOOTING](#verification--troubleshooting)

---

## 🎯 TỔNG QUAN

### Điều kiện tiên quyết

```
✅ VPS mới (Ubuntu 24.04 LTS) với IP public
✅ Domain đã trỏ về VPS (A record)
✅ SSH access với quyền root/sudo
✅ Windows machine với PowerShell (hoặc Mac/Linux terminal)
```

### Sau khi hoàn thành

```
✅ LEMP Stack: Nginx + MySQL 8 + PHP 8.4
✅ SSL Certificate (Let's Encrypt)
✅ Laravel 12 installed và hoạt động
✅ Database: 23 tables created
✅ Models: 15 Eloquent models (skeleton)
✅ Filament Admin Panel: 9 CRUD resources
✅ Admin user created
✅ Website: https://samnghethaycu.com ✅
✅ Admin: https://samnghethaycu.com/admin ✅
```

---

# PHASE 0: INFRASTRUCTURE SETUP

**Thời gian:** ~3 giờ
**Mục tiêu:** LEMP Stack + SSL + Security

## BƯỚC 0.1: INITIAL SERVER SETUP (20 phút)

### A. Connect to VPS

**Windows PowerShell:**

```powershell
ssh root@69.62.82.145
# Nhập password được cung cấp bởi VPS provider
```

✅ **Checkpoint 0.1A:** SSH connected

### B. Update System

**On VPS:**

```bash
# Update package list
apt update

# Upgrade all packages
apt upgrade -y

# Reboot if kernel updated
reboot
# (Wait 1-2 minutes, then reconnect)
```

✅ **Checkpoint 0.1B:** System updated

### C. Create Deploy User

**On VPS:**

```bash
# Create user
adduser deploy
# Password: Deploy@2025
# Full Name: Deploy User
# (Press Enter for other fields)

# Add to sudo group
usermod -aG sudo deploy

# Add to www-data group (for later)
usermod -aG www-data deploy

# Test sudo
su - deploy
sudo whoami
# Should output: root

exit
```

✅ **Checkpoint 0.1C:** Deploy user created

### D. Setup UFW Firewall

**On VPS as root:**

```bash
# Allow SSH (CRITICAL - do this first!)
ufw allow 22/tcp

# Allow HTTP
ufw allow 80/tcp

# Allow HTTPS
ufw allow 443/tcp

# Enable firewall
ufw enable
# Type: y

# Verify
ufw status verbose
```

**Expected output:**

```
Status: active

To                         Action      From
--                         ------      ----
22/tcp                     ALLOW       Anywhere
80/tcp                     ALLOW       Anywhere
443/tcp                    ALLOW       Anywhere
```

✅ **Checkpoint 0.1D:** Firewall configured

---

## BƯỚC 0.2: INSTALL NGINX (15 phút)

### A. Install Nginx

**On VPS:**

```bash
# Install
sudo apt install nginx -y

# Start service
sudo systemctl start nginx
sudo systemctl enable nginx

# Check status
sudo systemctl status nginx
# Should show: active (running)
```

✅ **Checkpoint 0.2A:** Nginx installed

### B. Test Nginx

**Browser:**

```
http://69.62.82.145
```

Should see: **"Welcome to nginx!"**

✅ **Checkpoint 0.2B:** Nginx working

---

## BƯỚC 0.3: INSTALL MYSQL 8 (20 phút)

### A. Install MySQL Server

**On VPS:**

```bash
# Install MySQL 8
sudo apt install mysql-server -y

# Start service
sudo systemctl start mysql
sudo systemctl enable mysql

# Check status
sudo systemctl status mysql
# Should show: active (running)
```

✅ **Checkpoint 0.3A:** MySQL installed

### B. Secure MySQL

**On VPS:**

```bash
sudo mysql_secure_installation

# Answers:
# - VALIDATE PASSWORD COMPONENT? n
# - Set root password? Y
#   Password: RootMySQL@2025
# - Remove anonymous users? Y
# - Disallow root login remotely? Y
# - Remove test database? Y
# - Reload privilege tables? Y
```

✅ **Checkpoint 0.3B:** MySQL secured

### C. Create Database & User

**On VPS:**

```bash
# Login to MySQL
sudo mysql -u root -p
# Password: RootMySQL@2025
```

**In MySQL console:**

```sql
-- Create database
CREATE DATABASE samnghethaycu CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- Create user
CREATE USER 'samnghethaycu_user'@'localhost' IDENTIFIED BY 'SamNghe@DB2025';

-- Grant privileges
GRANT ALL PRIVILEGES ON samnghethaycu.* TO 'samnghethaycu_user'@'localhost';

-- Flush privileges
FLUSH PRIVILEGES;

-- Verify
SHOW DATABASES;
SELECT User, Host FROM mysql.user WHERE User='samnghethaycu_user';

-- Exit
EXIT;
```

✅ **Checkpoint 0.3C:** Database created

### D. Test Connection

**On VPS:**

```bash
# Test login
mysql -u samnghethaycu_user -p samnghethaycu
# Password: SamNghe@DB2025

# In MySQL:
SHOW DATABASES;
# Should see: samnghethaycu

EXIT;
```

✅ **Checkpoint 0.3D:** Database accessible

---

## BƯỚC 0.4: INSTALL PHP 8.4 (30 phút)

### A. Add PHP 8.4 Repository

**On VPS:**

```bash
# Install dependencies
sudo apt install software-properties-common -y

# Add PPA
sudo add-apt-repository ppa:ondrej/php -y

# Update
sudo apt update
```

✅ **Checkpoint 0.4A:** Repository added

### B. Install PHP 8.4 & Extensions

**On VPS:**

```bash
# Install PHP 8.4 with extensions for Laravel
sudo apt install -y \
  php8.4-fpm \
  php8.4-cli \
  php8.4-common \
  php8.4-mysql \
  php8.4-mbstring \
  php8.4-xml \
  php8.4-bcmath \
  php8.4-curl \
  php8.4-gd \
  php8.4-zip \
  php8.4-intl \
  php8.4-redis \
  php8.4-imagick

# Verify installation
php -v
# Should show: PHP 8.4.x

# Check extensions
php -m | grep -E "mysql|mbstring|xml"
```

✅ **Checkpoint 0.4B:** PHP installed

### C. Configure PHP-FPM

**On VPS:**

```bash
# Edit PHP-FPM config
sudo nano /etc/php/8.4/fpm/pool.d/www.conf
```

**Find and verify these settings (usually correct by default):**

```ini
user = www-data
group = www-data
listen = /run/php/php8.4-fpm.sock
listen.owner = www-data
listen.group = www-data
listen.mode = 0660
```

**Save:** `Ctrl+O`, Enter, `Ctrl+X`

**Restart PHP-FPM:**

```bash
sudo systemctl restart php8.4-fpm
sudo systemctl status php8.4-fpm
# Should show: active (running)
```

✅ **Checkpoint 0.4C:** PHP-FPM configured

---

## BƯỚC 0.5: INSTALL REDIS (10 phút)

### A. Install Redis Server

**On VPS:**

```bash
# Install
sudo apt install redis-server -y

# Start service
sudo systemctl start redis-server
sudo systemctl enable redis-server

# Check status
sudo systemctl status redis-server
```

✅ **Checkpoint 0.5A:** Redis installed

### B. Test Redis

**On VPS:**

```bash
# Connect to Redis
redis-cli

# In Redis CLI:
PING
# Should output: PONG

SET test "Hello Redis"
GET test
# Should output: "Hello Redis"

EXIT
```

✅ **Checkpoint 0.5B:** Redis working

---

## BƯỚC 0.6: INSTALL COMPOSER (10 phút)

### A. Download & Install Composer

**On VPS:**

```bash
# Download installer
cd ~
curl -sS https://getcomposer.org/installer -o composer-setup.php

# Install globally
sudo php composer-setup.php --install-dir=/usr/local/bin --filename=composer

# Remove installer
rm composer-setup.php

# Verify
composer --version
# Should show: Composer version 2.x
```

✅ **Checkpoint 0.6A:** Composer installed

---

## BƯỚC 0.7: INSTALL NODE.JS (10 phút)

### A. Install Node.js 20.x

**On VPS:**

```bash
# Add NodeSource repository
curl -fsSL https://deb.nodesource.com/setup_20.x | sudo -E bash -

# Install Node.js
sudo apt install -y nodejs

# Verify
node -v
# Should show: v20.x.x

npm -v
# Should show: 10.x.x
```

✅ **Checkpoint 0.7A:** Node.js installed

---

## BƯỚC 0.8: SSL CERTIFICATE (15 phút)

### A. Install Certbot

**On VPS:**

```bash
# Install certbot
sudo apt install certbot python3-certbot-nginx -y

# Verify
certbot --version
```

✅ **Checkpoint 0.8A:** Certbot installed

### B. Obtain SSL Certificate

**IMPORTANT:** Domain MUST point to VPS IP first!

**On VPS:**

```bash
# Stop nginx temporarily
sudo systemctl stop nginx

# Obtain certificate
sudo certbot certonly --standalone -d samnghethaycu.com -d www.samnghethaycu.com

# Email: your-email@example.com
# Terms: A (Agree)
# Share email: N (No)

# Expected output:
# Successfully received certificate.
# Certificate is saved at: /etc/letsencrypt/live/samnghethaycu.com/fullchain.pem
# Key is saved at: /etc/letsencrypt/live/samnghethaycu.com/privkey.pem
```

✅ **Checkpoint 0.8B:** SSL certificate obtained

### C. Setup Auto-Renewal

**On VPS:**

```bash
# Test renewal
sudo certbot renew --dry-run

# Setup cron job (already automatic, but verify)
sudo systemctl status certbot.timer
# Should show: active (waiting)
```

✅ **Checkpoint 0.8C:** Auto-renewal configured

---

## BƯỚC 0.9: INSTALL FAIL2BAN (10 phút)

### A. Install & Configure Fail2ban

**On VPS:**

```bash
# Install
sudo apt install fail2ban -y

# Create local config
sudo cp /etc/fail2ban/jail.conf /etc/fail2ban/jail.local

# Edit config
sudo nano /etc/fail2ban/jail.local
```

**Find `[sshd]` section and ensure:**

```ini
[sshd]
enabled = true
port = ssh
logpath = /var/log/auth.log
maxretry = 5
bantime = 3600
```

**Save:** `Ctrl+O`, Enter, `Ctrl+X`

**Start service:**

```bash
sudo systemctl start fail2ban
sudo systemctl enable fail2ban

# Check status
sudo systemctl status fail2ban

# Check jails
sudo fail2ban-client status
```

✅ **Checkpoint 0.9A:** Fail2ban configured

---

## BƯỚC 0.10: CREATE WEB DIRECTORY (10 phút)

### A. Create Directory Structure

**On VPS:**

```bash
# Create web root
sudo mkdir -p /var/www/samnghethaycu.com

# Set ownership
sudo chown -R deploy:www-data /var/www/samnghethaycu.com

# Set permissions
sudo chmod -R 775 /var/www/samnghethaycu.com

# Verify
ls -la /var/www/
```

✅ **Checkpoint 0.10A:** Web directory created

### B. Create Credentials File

**On VPS:**

```bash
# Create credentials directory
mkdir -p ~/credentials

# Save database credentials
cat > ~/credentials/database.txt << 'EOF'
==============================================
DATABASE CREDENTIALS
==============================================

MySQL Root Password: RootMySQL@2025

Database Name: samnghethaycu
Database User: samnghethaycu_user
Database Password: SamNghe@DB2025
Database Host: localhost

==============================================
EOF

# Secure file
chmod 600 ~/credentials/database.txt

# View
cat ~/credentials/database.txt
```

✅ **Checkpoint 0.10B:** Credentials saved

---

## ✅ PHASE 0 HOÀN THÀNH!

**Đã cài đặt:**

```
✅ Ubuntu 24.04 LTS updated
✅ Nginx web server
✅ MySQL 8 database
✅ PHP 8.4 with extensions
✅ Redis cache
✅ Composer 2.x
✅ Node.js 20.x
✅ SSL Certificate (Let's Encrypt)
✅ Fail2ban security
✅ UFW Firewall
✅ Deploy user with permissions
✅ Web directory: /var/www/samnghethaycu.com
```

**Progress:**

```
Phase 0: Infrastructure   ████████████████████ 100%
Phase 1: Laravel          ░░░░░░░░░░░░░░░░░░░░   0%
Phase 2: Database         ░░░░░░░░░░░░░░░░░░░░   0%
```

---

# PHASE 1: LARAVEL DEPLOYMENT

**Thời gian:** ~25 phút
**Mục tiêu:** Laravel 12 installed và accessible

## BƯỚC 1.1: INSTALL LARAVEL (10 phút)

### A. Create Laravel Project

**On VPS as deploy user:**

```bash
# Switch to deploy user if not already
su - deploy

# Navigate to web directory
cd /var/www/samnghethaycu.com

# Install Laravel 12
composer create-project laravel/laravel . "^12.0"

# This will take 2-3 minutes...
```

✅ **Checkpoint 1.1A:** Laravel installed

### B. Set Permissions

**On VPS:**

```bash
cd /var/www/samnghethaycu.com

# Set ownership
sudo chown -R deploy:www-data .

# Set directory permissions
sudo find . -type d -exec chmod 775 {} \;

# Set file permissions
sudo find . -type f -exec chmod 664 {} \;

# Critical directories (must be writable by www-data)
sudo chown -R www-data:www-data storage bootstrap/cache
sudo chmod -R 775 storage bootstrap/cache

# Set ACL for deploy user
sudo setfacl -R -m u:deploy:rwx storage bootstrap/cache
sudo setfacl -R -d -m u:deploy:rwx storage bootstrap/cache
```

✅ **Checkpoint 1.1B:** Permissions set

---

## BƯỚC 1.2: CONFIGURE ENVIRONMENT (5 phút)

### A. Create .env File

**On VPS:**

```bash
cd /var/www/samnghethaycu.com

# Copy example
cp .env.example .env

# Generate key
php artisan key:generate
```

✅ **Checkpoint 1.2A:** .env created

### B. Edit .env Configuration

**On VPS:**

```bash
nano .env
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

CACHE_DRIVER=redis
SESSION_DRIVER=redis
QUEUE_CONNECTION=database

REDIS_HOST=127.0.0.1
REDIS_PASSWORD=null
REDIS_PORT=6379
```

**Save:** `Ctrl+O`, Enter, `Ctrl+X`

✅ **Checkpoint 1.2B:** .env configured

---

## BƯỚC 1.3: CONFIGURE NGINX (10 phút)

### A. Create Nginx Virtual Host

**On VPS:**

```bash
sudo nano /etc/nginx/sites-available/samnghethaycu.com
```

**Paste this configuration:**

```nginx
server {
    listen 80;
    listen [::]:80;
    server_name samnghethaycu.com www.samnghethaycu.com;
    return 301 https://$host$request_uri;
}

server {
    listen 443 ssl http2;
    listen [::]:443 ssl http2;
    server_name samnghethaycu.com www.samnghethaycu.com;

    root /var/www/samnghethaycu.com/public;
    index index.php index.html index.htm;

    # SSL Certificates
    ssl_certificate /etc/letsencrypt/live/samnghethaycu.com/fullchain.pem;
    ssl_certificate_key /etc/letsencrypt/live/samnghethaycu.com/privkey.pem;
    ssl_protocols TLSv1.2 TLSv1.3;
    ssl_ciphers HIGH:!aNULL:!MD5;

    # Logging
    access_log /var/log/nginx/samnghethaycu-access.log;
    error_log /var/log/nginx/samnghethaycu-error.log;

    # Laravel Configuration
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

**Save:** `Ctrl+O`, Enter, `Ctrl+X`

✅ **Checkpoint 1.3A:** Nginx config created

### B. Enable Site & Test

**On VPS:**

```bash
# Create symlink
sudo ln -s /etc/nginx/sites-available/samnghethaycu.com /etc/nginx/sites-enabled/

# Remove default site
sudo rm /etc/nginx/sites-enabled/default

# Test configuration
sudo nginx -t
# Should output: syntax is okay, test is successful

# Reload Nginx
sudo systemctl reload nginx
```

✅ **Checkpoint 1.3B:** Nginx configured

---

## BƯỚC 1.4: CREATE STORAGE SYMLINK (1 phút)

**On VPS:**

```bash
cd /var/www/samnghethaycu.com

# Create symlink
php artisan storage:link

# Verify
ls -la public/storage
# Should show: public/storage -> ../storage/app/public
```

✅ **Checkpoint 1.4:** Storage linked

---

## BƯỚC 1.5: TEST LARAVEL (1 phút)

### A. Browser Test

**Open browser:**

```
https://samnghethaycu.com
```

**Should see:** Laravel welcome page!

✅ **Checkpoint 1.5A:** Laravel accessible

### B. Create Health Check Route

**On VPS:**

```bash
cd /var/www/samnghethaycu.com

nano routes/web.php
```

**Add this route:**

```php
<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/health', function () {
    return response()->json([
        'status' => 'healthy',
        'timestamp' => now()->toDateTimeString(),
        'app' => config('app.name'),
    ]);
});
```

**Save and test:**

```
https://samnghethaycu.com/health
```

**Should output:**

```json
{
  "status": "healthy",
  "timestamp": "2025-11-16 10:30:45",
  "app": "Sam Nghe Thay Cu"
}
```

✅ **Checkpoint 1.5B:** Laravel working

---

## ✅ PHASE 1 HOÀN THÀNH!

**Đã có:**

```
✅ Laravel 12 installed
✅ Nginx virtual host configured
✅ SSL working (HTTPS)
✅ Database connection configured
✅ Storage symlink created
✅ Health check endpoint
✅ Website accessible: https://samnghethaycu.com ✅
```

**Progress:**

```
Phase 0: Infrastructure   ████████████████████ 100%
Phase 1: Laravel          ████████████████████ 100%
Phase 2: Database         ░░░░░░░░░░░░░░░░░░░░   0%
```

---

# PHASE 2: DATABASE & MODELS SKELETON

**Thời gian:** ~60 phút
**Mục tiêu:** Database schema + Models + Filament admin panel

## BƯỚC 2.1: CREATE MIGRATIONS (15 phút)

### A. Generate Migration Files

**On VPS:**

```bash
cd /var/www/samnghethaycu.com

# Core e-commerce migrations
php artisan make:migration create_categories_table
php artisan make:migration create_brands_table
php artisan make:migration create_products_table
php artisan make:migration create_product_variants_table
php artisan make:migration create_product_images_table
php artisan make:migration create_addresses_table
php artisan make:migration create_orders_table
php artisan make:migration create_order_items_table
php artisan make:migration create_order_status_histories_table
php artisan make:migration create_reviews_table
php artisan make:migration create_coupons_table
php artisan make:migration create_coupon_usages_table

# Blog migrations
php artisan make:migration create_post_categories_table
php artisan make:migration create_posts_table
```

✅ **Checkpoint 2.1A:** Migration files created (14 new migrations)

### B. Define Migration Schemas

**Note:** For brevity, I'll show the key tables. Full migrations are in the project repository.

**Example: categories migration**

```bash
nano database/migrations/YYYY_MM_DD_HHMMSS_create_categories_table.php
```

**Paste:**

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('parent_id')->nullable()->constrained('categories')->onDelete('cascade');
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->string('image')->nullable();
            $table->string('icon')->nullable();
            $table->integer('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->string('meta_title')->nullable();
            $table->text('meta_description')->nullable();
            $table->string('meta_keywords')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index('slug');
            $table->index('is_active');
            $table->index('sort_order');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('categories');
    }
};
```

**Repeat for other tables (brands, products, etc.)**

✅ **Checkpoint 2.1B:** Migrations defined

---

## BƯỚC 2.2: RUN MIGRATIONS (2 phút)

**On VPS:**

```bash
cd /var/www/samnghethaycu.com

# Run migrations
php artisan migrate

# Verify tables created
php artisan db:show
php artisan db:table products
```

**Expected:** 23 tables created

✅ **Checkpoint 2.2:** Database migrated (23 tables)

---

## BƯỚC 2.3: CREATE MODELS (10 phút)

### A. Generate Model Files

**On VPS:**

```bash
cd /var/www/samnghethaycu.com

# Generate models
php artisan make:model Category
php artisan make:model Brand
php artisan make:model Product
php artisan make:model ProductVariant
php artisan make:model ProductImage
php artisan make:model Order
php artisan make:model OrderItem
php artisan make:model OrderStatusHistory
php artisan make:model Review
php artisan make:model Coupon
php artisan make:model CouponUsage
php artisan make:model Address
php artisan make:model Post
php artisan make:model PostCategory
```

✅ **Checkpoint 2.3A:** Models created (15 models including User)

### B. Define Basic Model Properties

**Example: Category model**

```bash
nano app/Models/Category.php
```

**Paste basic skeleton:**

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'parent_id',
        'name',
        'slug',
        'description',
        'image',
        'icon',
        'sort_order',
        'is_active',
        'meta_title',
        'meta_description',
        'meta_keywords',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'sort_order' => 'integer',
    ];

    // Relationships will be added in Phase 3
}
```

**Repeat for other models (basic $fillable and $casts only)**

✅ **Checkpoint 2.3B:** Models defined (skeleton)

---

## BƯỚC 2.4: INSTALL FILAMENT (15 phút)

### A. Install Filament v3

**On VPS:**

```bash
cd /var/www/samnghethaycu.com

# Install Filament
composer require filament/filament:"^3.2" -W

# This takes 2-3 minutes...
```

✅ **Checkpoint 2.4A:** Filament installed

### B. Install Filament Panel

**On VPS:**

```bash
# Install admin panel
php artisan filament:install --panels

# Panel ID: admin
# (Press Enter for defaults)

# Clear cache
php artisan optimize:clear
```

✅ **Checkpoint 2.4B:** Filament panel installed

### C. Create Admin User

**On VPS:**

```bash
# Create Filament user
php artisan make:filament-user

# Name: Admin
# Email: admin@samnghethaycu.com
# Password: Admin@123456
```

✅ **Checkpoint 2.4C:** Admin user created

### D. Test Admin Panel

**Browser:**

```
https://samnghethaycu.com/admin
```

**Login:**
- Email: `admin@samnghethaycu.com`
- Password: `Admin@123456`

**Should see:** Filament dashboard!

✅ **Checkpoint 2.4D:** Admin panel accessible

---

## BƯỚC 2.5: GENERATE FILAMENT RESOURCES (15 phút)

### A. Generate Resources for Core Models

**On VPS:**

```bash
cd /var/www/samnghethaycu.com

# Generate Filament resources with forms & tables
php artisan make:filament-resource Category --generate
php artisan make:filament-resource Brand --generate
php artisan make:filament-resource Product --generate
php artisan make:filament-resource Order --generate --view
php artisan make:filament-resource Review --generate
php artisan make:filament-resource PostCategory --generate
php artisan make:filament-resource Post --generate
php artisan make:filament-resource Coupon --generate
php artisan make:filament-resource Address --generate --view
```

**This creates:**
- `app/Filament/Resources/[Model]Resource.php`
- `app/Filament/Resources/[Model]Resource/Pages/`

✅ **Checkpoint 2.5A:** Resources generated (9 resources)

### B. Clear Cache & Test

**On VPS:**

```bash
php artisan optimize:clear
```

**Browser:**

```
https://samnghethaycu.com/admin
```

**Should see sidebar with:**
- Categories
- Brands
- Products
- Orders
- Reviews
- Posts
- Post Categories
- Coupons
- Addresses

✅ **Checkpoint 2.5B:** Resources visible in admin

---

## BƯỚC 2.6: TEST CRUD OPERATIONS (3 phút)

### A. Test Create Category

**In admin panel:**

1. Click **Categories**
2. Click **New**
3. Fill form:
   - Name: `Sâm Hàn Quốc`
   - Description: `Sâm chính hãng từ Hàn Quốc`
   - Is Active: ✅
4. Click **Create**

**Should:** Category created successfully!

✅ **Checkpoint 2.6A:** CRUD working

### B. Test Other Resources

**Quickly test:**
- Create 1 Brand: `KGC`
- Create 1 Product: `Hồng Sâm 6 năm tuổi`
- View lists

All should work!

✅ **Checkpoint 2.6B:** All CRUD operations working

---

## ✅ PHASE 2 HOÀN THÀNH!

**Đã có:**

```
✅ Database: 23 tables created
✅ Models: 15 Eloquent models (skeleton)
✅ Filament v3 installed
✅ Admin panel: 9 resources
✅ Admin user created
✅ CRUD operations working
✅ Admin accessible: https://samnghethaycu.com/admin ✅
```

**Progress:**

```
Phase 0: Infrastructure   ████████████████████ 100%
Phase 1: Laravel          ████████████████████ 100%
Phase 2: Database         ████████████████████ 100%
```

---

# VERIFICATION & TROUBLESHOOTING

## ✅ FINAL VERIFICATION CHECKLIST

### System Health

- [ ] Website accessible: `https://samnghethaycu.com`
- [ ] Admin panel accessible: `https://samnghethaycu.com/admin`
- [ ] SSL certificate valid (green padlock)
- [ ] Health endpoint working: `/health`

### Services Status

**On VPS:**

```bash
# Check all services
sudo systemctl status nginx
sudo systemctl status php8.4-fpm
sudo systemctl status mysql
sudo systemctl status redis-server

# All should show: active (running)
```

### Database

```bash
# Login to MySQL
mysql -u samnghethaycu_user -p samnghethaycu
# Password: SamNghe@DB2025

# In MySQL:
SHOW TABLES;
# Should show 23 tables

SELECT COUNT(*) FROM users;
# Should show: 1 (admin user)

EXIT;
```

### Laravel

```bash
cd /var/www/samnghethaycu.com

# Check Laravel version
php artisan --version
# Should show: Laravel Framework 12.x.x

# Check migrations
php artisan migrate:status
# All should show: Ran

# Check Filament
php artisan about
# Should show Filament in packages
```

### Permissions

```bash
# Check critical directories
ls -la /var/www/samnghethaycu.com/storage
ls -la /var/www/samnghethaycu.com/bootstrap/cache

# Both should be owned by: www-data:www-data
# Permissions: drwxrwxr-x (775)
```

---

## 🔧 COMMON ISSUES & FIXES

### Issue 1: 500 Internal Server Error

**Check logs:**

```bash
# Laravel log
tail -50 /var/www/samnghethaycu.com/storage/logs/laravel.log

# Nginx error log
sudo tail -50 /var/log/nginx/samnghethaycu-error.log
```

**Fix permissions:**

```bash
cd /var/www/samnghethaycu.com
sudo chown -R www-data:www-data storage bootstrap/cache
sudo chmod -R 775 storage bootstrap/cache
php artisan optimize:clear
sudo systemctl reload php8.4-fpm
```

### Issue 2: Database Connection Refused

**Check credentials:**

```bash
cat .env | grep DB_

# Verify:
# DB_DATABASE=samnghethaycu
# DB_USERNAME=samnghethaycu_user
# DB_PASSWORD=SamNghe@DB2025
```

**Test MySQL:**

```bash
mysql -u samnghethaycu_user -p samnghethaycu
# Should connect
```

### Issue 3: Admin Panel Not Loading

**Clear all caches:**

```bash
cd /var/www/samnghethaycu.com
php artisan optimize:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache
sudo systemctl reload php8.4-fpm
```

**Check User model implements FilamentUser:**

```bash
grep -A 5 "implements FilamentUser" app/Models/User.php
# Should show the implementation
```

### Issue 4: SSL Certificate Not Working

**Check certificate:**

```bash
sudo certbot certificates
# Should show valid certificate
```

**Renew if needed:**

```bash
sudo certbot renew
sudo systemctl reload nginx
```

### Issue 5: Permission Denied When Creating Files

**Fix ACL:**

```bash
cd /var/www/samnghethaycu.com
sudo setfacl -R -m u:deploy:rwx storage bootstrap/cache
sudo setfacl -R -d -m u:deploy:rwx storage bootstrap/cache
sudo setfacl -R -m u:www-data:rwx storage bootstrap/cache
sudo setfacl -R -d -m u:www-data:rwx storage bootstrap/cache
```

---

## 📊 LOGS LOCATIONS

```bash
# Laravel application logs
/var/www/samnghethaycu.com/storage/logs/laravel.log

# Nginx access logs
/var/log/nginx/samnghethaycu-access.log

# Nginx error logs
/var/log/nginx/samnghethaycu-error.log

# PHP-FPM logs
/var/log/php8.4-fpm.log

# MySQL logs
/var/log/mysql/error.log

# System logs
/var/log/syslog
```

---

## 🎉 HOÀN THÀNH!

**Bạn đã có:**

✅ **LEMP Stack** hoàn chỉnh với PHP 8.4
✅ **SSL Certificate** from Let's Encrypt
✅ **Laravel 12** installed và hoạt động
✅ **Database** với 23 tables
✅ **15 Eloquent Models** (skeleton)
✅ **Filament Admin Panel** với 9 CRUD resources
✅ **Security** configured (UFW, Fail2ban)
✅ **Website live:** https://samnghethaycu.com
✅ **Admin panel:** https://samnghethaycu.com/admin

**Tiếp theo:**

👉 **QUY-TRINH-2-BACKEND-COMPLETE.md** - Hoàn thiện backend với business logic, relationships, Filament customization, và seeders

---

**Lưu thông tin quan trọng:**

```
VPS IP: 69.62.82.145
SSH: ssh deploy@69.62.82.145 (Password: Deploy@2025)

MySQL Root: RootMySQL@2025
Database: samnghethaycu
DB User: samnghethaycu_user
DB Pass: SamNghe@DB2025

Admin Panel: https://samnghethaycu.com/admin
Email: admin@samnghethaycu.com
Password: Admin@123456
```

---

**End of Quy Trình 1** 🚀
