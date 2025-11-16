# 🚀 WORKFLOW 2: CÀI ĐẶT LARAVEL

> **Dự án:** samnghethaycu.com - E-Commerce Platform
> **Version:** 4.0 Professional Vietnamese
> **Thời gian thực tế:** 15-20 phút
> **Mục tiêu:** Laravel 12 + Nginx + Production Ready

---

## 📋 ĐIỀU KIỆN TIÊN QUYẾT

### ✅ Phải hoàn thành trước

```
✅ WORKFLOW-1: Cài Đặt Hạ Tầng VPS (LEMP + SSL)
✅ Hạ tầng sẵn sàng (Nginx, MySQL, PHP 8.4, Composer)
✅ SSL certificate đã có
✅ Domain truy cập được: https://samnghethaycu.com
```

### ✅ Kiểm Tra Nhanh

**Trên VPS:**

```bash
# Kết nối VPS
ssh root@69.62.82.145

# Kiểm tra tất cả services
systemctl status nginx mysql php8.4-fpm redis-server | grep Active
# Tất cả phải: active (running)

# Kiểm tra SSL certificate
ls /etc/letsencrypt/live/samnghethaycu.com/fullchain.pem
# Phải tồn tại

# Kiểm tra Composer
composer --version
# Phải thấy: Composer version 2.x.x
```

**Tất cả OK?** → Tiếp tục!

---

## 🎯 NHỮNG GÌ CHÚNG TA SẼ XÂY DỰNG

```
Windows Local:
  composer create-project laravel/laravel
  ↓
  Cấu hình .env
  ↓
  git commit & push lên GitHub
  ↓
VPS Production:
  Tải code từ GitHub
  ↓
  Cấu hình Nginx virtual host
  ↓
  Setup permissions & storage
  ↓
Kết quả: https://samnghethaycu.com (Laravel welcome page) ✅
```

**Triết lý:** Cài trên LOCAL, deploy qua GIT!

---

## PHẦN 1: CÀI LARAVEL (TRÊN WINDOWS)

**Thời gian:** 5 phút

### 1.1. Tạo Thư Mục Project

**Trên Windows PowerShell:**

```powershell
# Tạo thư mục project
New-Item -ItemType Directory -Path "C:\Projects\samnghethaycu" -Force

# Di chuyển vào thư mục
cd C:\Projects\samnghethaycu

# Kiểm tra đã vào đúng thư mục chưa
Get-Location
# Phải thấy: C:\Projects\samnghethaycu
```

### 1.2. Cài Đặt Laravel 12

**Trên Windows PowerShell:**

```powershell
# Cài Laravel 12 vào thư mục tạm
composer create-project laravel/laravel temp "^12.0"

# Lệnh này sẽ mất 2-3 phút...
# Chờ thông báo: "Application ready! Build something amazing."
```

**Giải thích:**
- `composer create-project`: Tạo project mới từ template
- `laravel/laravel`: Package Laravel official
- `temp`: Tên thư mục tạm (sẽ di chuyển files ra ngoài)
- `"^12.0"`: Version Laravel 12.x mới nhất

### 1.3. Di Chuyển Files Laravel Ra Root

**Trên Windows PowerShell:**

```powershell
# Di chuyển tất cả files từ temp/ ra ngoài
Move-Item temp\* . -Force

# Xóa thư mục temp rỗng
Remove-Item temp

# Kiểm tra files đã có chưa
dir
# Phải thấy: app/, bootstrap/, public/, vendor/, artisan, composer.json, etc.
```

**Giải thích:**
- `Move-Item temp\* .`: Di chuyển tất cả từ temp ra root
- `-Force`: Ghi đè nếu file đã tồn tại
- `Remove-Item temp`: Xóa thư mục temp đã rỗng

### 1.4. Verify Cài Đặt

**Trên Windows PowerShell:**

```powershell
# Kiểm tra version Laravel
php artisan --version
# Phải thấy: Laravel Framework 12.x.x

# Kiểm tra PHP version
php -v
# Phải thấy: PHP 8.x.x
```

✅ **Checkpoint 1:** Laravel đã cài trên Windows

---

## PHẦN 2: CẤU HÌNH .ENV (TRÊN WINDOWS)

**Thời gian:** 3 phút

### 2.1. Tạo File .env

**Trên Windows PowerShell:**

```powershell
# Copy .env.example thành .env
Copy-Item .env.example .env

# Generate application key
php artisan key:generate

# Thông báo sẽ hiện:
# INFO  Application key set successfully.
```

**Giải thích:**
- `.env.example`: Template cấu hình mẫu (có trong Laravel)
- `.env`: File cấu hình thực tế (không push lên Git)
- `php artisan key:generate`: Tạo APP_KEY random cho mã hóa

### 2.2. Sửa File .env

**Trên Windows PowerShell:**

```powershell
# Mở .env bằng Notepad
notepad .env
```

**Cập nhật các giá trị sau:**

```env
# Thông tin ứng dụng
APP_NAME="Sam Nghe Thay Cu"
APP_ENV=production
APP_DEBUG=false
APP_TIMEZONE=Asia/Ho_Chi_Minh
APP_URL=https://samnghethaycu.com

# Database (lấy từ ~/credentials/database.txt trên VPS)
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=samnghethaycu
DB_USERNAME=samnghethaycu_user
DB_PASSWORD=SamNghe@DB2025

# Cache & Sessions (dùng Redis cho hiệu suất cao)
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

**Lưu file:** Ctrl+S, đóng Notepad

**Giải thích:**
- `APP_ENV=production`: Chế độ production (không hiện lỗi chi tiết)
- `APP_DEBUG=false`: Tắt debug (bảo mật)
- `APP_TIMEZONE`: Múi giờ Việt Nam
- `DB_*`: Thông tin database từ WORKFLOW-1
- `CACHE_STORE=redis`: Dùng Redis cho cache (nhanh hơn file)
- `SESSION_DRIVER=redis`: Dùng Redis cho sessions

### 2.3. Fix .env Cho Test Local (QUAN TRỌNG!)

⚠️ **VẤN ĐỀ:** File `.env` đang config Redis, nhưng Windows **KHÔNG CÓ** Redis server → Lỗi 500!

**Trên Windows PowerShell:**

```powershell
# Mở .env
notepad .env
```

**Đổi 2 dòng này (tạm thời cho test local):**

```env
# TÌM và ĐỔI:
SESSION_DRIVER=redis  →  SESSION_DRIVER=file
CACHE_STORE=redis     →  CACHE_STORE=file
```

**Lưu file:** `Ctrl+S`, đóng Notepad

**Giải thích:**
- Windows không có Redis server → connect Redis failed → 500 error
- `SESSION_DRIVER=file`: Lưu sessions vào `storage/framework/sessions/`
- `CACHE_STORE=file`: Lưu cache vào `storage/framework/cache/`
- **SAU KHI TEST XONG:** Đổi lại thành `redis` trước khi push lên VPS!

### 2.4. Test Laravel Local

**Trên Windows PowerShell:**

```powershell
# Chạy server Laravel trên local
php artisan serve

# Thông báo sẽ hiện:
# INFO  Server running on [http://127.0.0.1:8000]
```

**Mở trình duyệt:**

```
http://localhost:8000
```

**Phải thấy:** 🎉 Trang Laravel welcome page (màu cam)!

**Nếu vẫn lỗi 500:**
- Kiểm tra `SESSION_DRIVER=file` và `CACHE_STORE=file` trong `.env`
- Xem logs: `storage/logs/laravel.log`

**Dừng server:** Nhấn `Ctrl+C` trong PowerShell

### 2.5. Đổi Lại .env Về Production Config

⚠️ **QUAN TRỌNG:** Sau khi test xong, đổi lại về Redis!

**Trên Windows PowerShell:**

```powershell
# Mở .env
notepad .env
```

**Đổi lại 2 dòng:**

```env
# ĐỔI LẠI VỀ PRODUCTION:
SESSION_DRIVER=file  →  SESSION_DRIVER=redis
CACHE_STORE=file     →  CACHE_STORE=redis
```

**Lưu file:** `Ctrl+S`, đóng Notepad

**Giải thích:**
- VPS có Redis server → dùng Redis cho performance cao
- `.env` sẽ push lên Git (trong `.gitignore`), nhưng cần đúng config production
- Khi deploy lên VPS, copy `.env` này (đã có Redis config)

✅ **Checkpoint 2:** .env đã cấu hình và test thành công

---

## PHẦN 3: COMMIT & PUSH (TRÊN WINDOWS)

**Thời gian:** 2 phút

### 3.1. Khởi Tạo Git Repository

**Trên Windows PowerShell:**

```powershell
# Khởi tạo Git
git init

# Kiểm tra Git đã init chưa
git status
# Phải thấy: On branch main (hoặc master)
```

### 3.2. Commit Laravel

**Trên Windows PowerShell:**

```powershell
# Kiểm tra những gì sẽ commit
git status
# Phải thấy rất nhiều files: app/, bootstrap/, public/, etc.

# Thêm tất cả files vào staging
git add .
# Lưu ý: .env KHÔNG được add (đã có trong .gitignore)

# Commit
git commit -m "feat: Laravel 12 installation with production config"

# Kiểm tra commit đã tạo chưa
git log --oneline
# Phải thấy commit vừa tạo
```

**Giải thích:**
- `git add .`: Thêm tất cả files (trừ files trong .gitignore)
- `.gitignore`: Laravel tự động ignore .env, vendor/, node_modules/
- Commit message format: `feat: description` (Conventional Commits)

### 3.3. Push Lên GitHub

**Trên Windows PowerShell:**

```powershell
# Thêm remote GitHub (thay YOUR_USERNAME)
git remote add origin https://github.com/phuochoavn/websamnghe.git

# Kiểm tra remote đã add chưa
git remote -v
# Phải thấy:
# origin  https://github.com/phuochoavn/websamnghe.git (fetch)
# origin  https://github.com/phuochoavn/websamnghe.git (push)

# Đổi tên branch thành main
git branch -M main

# Push lên GitHub
git push -u origin main
```

**Authentication (nếu hỏi):**

```
Username: phuochoavn
Password: [PASTE PERSONAL ACCESS TOKEN]
```

**Chờ push hoàn tất...**

✅ **Checkpoint 3:** Laravel đã push lên GitHub

---

## PHẦN 4: DEPLOY LÊN VPS

**Thời gian:** 5 phút

### 4.1. Clone Repository Về VPS

**Trên VPS:**

```bash
# Kết nối VPS
ssh root@69.62.82.145

# Di chuyển vào /var/www
cd /var/www

# Clone repository từ GitHub
git clone https://github.com/phuochoavn/websamnghe.git samnghethaycu.com

# Di chuyển vào thư mục project
cd samnghethaycu.com

# Kiểm tra files đã có chưa
ls -la
# Phải thấy: app/, bootstrap/, public/, vendor/, artisan, composer.json
```

**Giải thích:**
- `git clone`: Tải code từ GitHub về VPS
- `samnghethaycu.com`: Tên thư mục trên VPS
- Repository URL: Lấy từ GitHub repository page

### 4.2. Cài Dependencies

**Trên VPS:**

```bash
# Cài Composer packages
composer install --no-dev --optimize-autoloader --no-interaction

# Lệnh này mất 1-2 phút...
# Chờ thông báo: "Generating optimized autoload files"
```

**Giải thích:**
- `composer install`: Cài packages từ composer.json
- `--no-dev`: Không cài dev packages (phpunit, etc.)
- `--optimize-autoloader`: Tối ưu autoloader cho production
- `--no-interaction`: Không hỏi confirm (chạy tự động)

### 4.3. Copy File .env

**.env không có trên Git (bảo mật), phải copy thủ công:**

**Trên VPS:**

```bash
# Tạo file .env
nano .env
```

**Paste nội dung .env từ Windows:**
- Mở file `C:\Projects\samnghethaycu\.env` trên Windows
- Copy toàn bộ nội dung
- Paste vào nano trên VPS
- Nhấn `Ctrl+O`, `Enter`, `Ctrl+X` để lưu

**Giải thích:**
- `.env` chứa credentials (passwords, keys)
- KHÔNG BAO GIỜ push .env lên Git (bảo mật)
- Phải copy thủ công lên VPS

### 4.4. Generate APP_KEY Cho VPS

**Trên VPS:**

```bash
# Generate APP_KEY mới cho VPS
php artisan key:generate

# Kiểm tra key đã tạo chưa
grep APP_KEY .env
# Phải thấy: APP_KEY=base64:xxxxxxxxxxxxxx
```

**Giải thích:**
- Mỗi server cần APP_KEY riêng
- APP_KEY dùng để mã hóa sessions, cookies, passwords

### 4.5. Set Permissions

**Trên VPS:**

```bash
# Set ownership cho deploy user
sudo chown -R root:www-data /var/www/samnghethaycu.com

# Cho phép www-data ghi vào storage và cache
sudo chown -R www-data:www-data storage bootstrap/cache
sudo chmod -R 775 storage bootstrap/cache

# Kiểm tra permissions
ls -la storage
# Phải thấy: drwxrwxr-x www-data www-data
```

**Giải thích:**
- `root:www-data`: Root owns files, www-data (Nginx) có quyền đọc
- `www-data:www-data`: www-data owns storage (cần ghi logs, cache)
- `775`: Owner & group có full quyền, others chỉ đọc

### 4.6. Tạo Storage Symlink

**Trên VPS:**

```bash
# Tạo symlink từ public/storage → storage/app/public
php artisan storage:link

# Kiểm tra symlink đã tạo chưa
ls -la public/storage
# Phải thấy: public/storage -> ../storage/app/public
```

**Giải thích:**
- Symlink để files upload (ảnh, PDF) accessible qua web
- Files upload vào `storage/app/public/`
- Truy cập qua URL: `https://samnghethaycu.com/storage/filename.jpg`

✅ **Checkpoint 4:** Laravel đã có trên VPS

---

## PHẦN 5: CẤU HÌNH NGINX

**Thời gian:** 5 phút

### 5.1. Tạo Nginx Virtual Host

**Trên VPS:**

```bash
# Tạo file config
sudo nano /etc/nginx/sites-available/samnghethaycu.com
```

**Paste config này:**

```nginx
# HTTP → HTTPS redirect
server {
    listen 80;
    listen [::]:80;
    server_name samnghethaycu.com www.samnghethaycu.com;

    # Chuyển hướng tất cả HTTP sang HTTPS
    return 301 https://$host$request_uri;
}

# HTTPS - Laravel Application
server {
    listen 443 ssl http2;
    listen [::]:443 ssl http2;
    server_name samnghethaycu.com www.samnghethaycu.com;

    # Document root trỏ đến thư mục public của Laravel
    root /var/www/samnghethaycu.com/public;
    index index.php index.html;

    # SSL Certificates (từ WORKFLOW-1)
    ssl_certificate /etc/letsencrypt/live/samnghethaycu.com/fullchain.pem;
    ssl_certificate_key /etc/letsencrypt/live/samnghethaycu.com/privkey.pem;
    ssl_protocols TLSv1.2 TLSv1.3;
    ssl_ciphers HIGH:!aNULL:!MD5;
    ssl_prefer_server_ciphers on;

    # Logging
    access_log /var/log/nginx/samnghethaycu-access.log;
    error_log /var/log/nginx/samnghethaycu-error.log;

    # Gzip Compression (tăng tốc độ tải trang)
    gzip on;
    gzip_vary on;
    gzip_types text/plain text/css application/json application/javascript text/xml application/xml;

    # Laravel URL Rewriting (quan trọng!)
    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    # PHP-FPM Configuration
    location ~ \.php$ {
        include snippets/fastcgi-php.conf;
        fastcgi_pass unix:/run/php/php8.4-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;

        # Tăng timeout cho requests lâu
        fastcgi_read_timeout 300;
    }

    # Ngăn truy cập files ẩn (.env, .git, etc.)
    location ~ /\.(?!well-known).* {
        deny all;
    }

    # Security Headers
    add_header X-Frame-Options "SAMEORIGIN" always;
    add_header X-Content-Type-Options "nosniff" always;
    add_header X-XSS-Protection "1; mode=block" always;
    add_header Referrer-Policy "no-referrer-when-downgrade" always;

    # Cache static files (CSS, JS, images)
    location ~* \.(jpg|jpeg|png|gif|ico|css|js|svg|woff|woff2|ttf|eot)$ {
        expires 1y;
        add_header Cache-Control "public, immutable";
    }
}
```

**Lưu file:** `Ctrl+O`, `Enter`, `Ctrl+X`

**Giải thích config:**
- `listen 443 ssl http2`: HTTPS với HTTP/2 (nhanh hơn)
- `root /var/www/.../public`: Laravel public directory
- `try_files ... /index.php`: Tất cả requests qua index.php (Laravel routing)
- `fastcgi_pass unix:/run/php/php8.4-fpm.sock`: Giao tiếp với PHP-FPM
- `gzip on`: Nén files trước khi gửi (giảm bandwidth)
- `expires 1y`: Cache static files 1 năm

### 5.2. Enable Site và Xóa Default

**Trên VPS:**

```bash
# Tạo symlink để enable site
sudo ln -s /etc/nginx/sites-available/samnghethaycu.com /etc/nginx/sites-enabled/

# Xóa default Nginx site
sudo rm -f /etc/nginx/sites-enabled/default

# Test config có lỗi không
sudo nginx -t

# Phải thấy:
# nginx: the configuration file /etc/nginx/nginx.conf syntax is ok
# nginx: configuration file /etc/nginx/nginx.conf test is successful
```

**Giải thích:**
- `sites-available/`: Tất cả configs (kể cả disabled)
- `sites-enabled/`: Chỉ configs đang enabled (symlinks)
- `nginx -t`: Test config trước khi restart (tránh break Nginx)

### 5.3. Restart Nginx

**Trên VPS:**

```bash
# Restart Nginx để áp dụng config mới
sudo systemctl restart nginx

# Kiểm tra status
sudo systemctl status nginx
# Phải thấy: active (running)
```

✅ **Checkpoint 5:** Nginx đã cấu hình

---

## PHẦN 6: CHẠY MIGRATIONS & TEST

**Thời gian:** 3 phút

### 6.1. Run Migrations

**Trên VPS:**

```bash
# Di chuyển vào project
cd /var/www/samnghethaycu.com

# Chạy migrations (tạo tables mặc định của Laravel)
php artisan migrate

# Sẽ hỏi: Do you really wish to run this command? (yes/no)
# Gõ: yes
```

**Giải thích:**
- Laravel tạo các tables: users, password_resets, sessions, cache, jobs, etc.
- Cần thiết để Laravel hoạt động đúng
- Hỏi confirm vì `APP_ENV=production`

### 6.2. Clear & Cache

**Trên VPS:**

```bash
# Clear tất cả caches
php artisan optimize:clear

# Cache config cho performance
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

**Giải thích:**
- `optimize:clear`: Xóa config, route, view cache
- `*:cache`: Tạo cache mới để tăng tốc độ (production best practice)

### 6.3. Test Trong Trình Duyệt

**Mở trình duyệt:**

```
https://samnghethaycu.com
```

**Phải thấy:** 🎉 **Laravel Welcome Page!** (màu cam, chữ "Laravel")

**Nếu thấy lỗi 500:**
- Xem phần Troubleshooting ở cuối workflow

### 6.4. Thêm Health Check Endpoint

**Trên Windows PowerShell:**

```powershell
cd C:\Projects\samnghethaycu

# Mở routes/web.php
notepad routes\web.php
```

**Thêm route này vào cuối file (TRƯỚC dấu `?>`nếu có):**

```php
// Health check endpoint
Route::get('/health', function () {
    try {
        \DB::connection()->getPdo();
        $dbStatus = 'connected';
    } catch (\Exception $e) {
        $dbStatus = 'failed: ' . $e->getMessage();
    }

    try {
        \Redis::connection()->ping();
        $redisStatus = 'connected';
    } catch (\Exception $e) {
        $redisStatus = 'failed';
    }

    return response()->json([
        'status' => 'healthy',
        'timestamp' => now()->toDateTimeString(),
        'app' => config('app.name'),
        'environment' => config('app.env'),
        'database' => $dbStatus,
        'cache' => \Cache::has('health_check') ? 'working' : 'available',
        'redis' => $redisStatus,
    ]);
});
```

**Lưu, commit, push:**

```powershell
git add routes\web.php
git commit -m "feat: add health check endpoint with DB and Redis status"
git push origin main
```

**Deploy trên VPS:**

```bash
# Pull code mới
cd /var/www/samnghethaycu.com
git pull origin main

# Clear cache
php artisan optimize:clear
```

**Test health endpoint:**

```bash
curl https://samnghethaycu.com/health
```

**Expected output:**

```json
{
  "status": "healthy",
  "timestamp": "2025-11-16 20:00:00",
  "app": "Sam Nghe Thay Cu",
  "environment": "production",
  "database": "connected",
  "cache": "available",
  "redis": "connected"
}
```

✅ **Checkpoint 6:** Laravel hoạt động hoàn hảo!

---

## ✅ HOÀN THÀNH WORKFLOW 2!

### Laravel Sẵn Sàng:

```
✅ Laravel 12 đã cài (qua Git!)
✅ .env cấu hình production
✅ Nginx virtual host configured
✅ SSL certificate áp dụng
✅ Database kết nối
✅ Redis cache hoạt động
✅ Storage symlink tạo
✅ Health check endpoint
✅ Website live: https://samnghethaycu.com
```

### Git Workflow Hoạt Động:

```
1. Code trên Windows
2. git add . && git commit -m "..." && git push origin main
3. SSH vào VPS
4. cd /var/www/samnghethaycu.com && git pull origin main
5. php artisan optimize:clear
6. Thay đổi live trong 30 giây! ✅
```

### Kiểm Tra Tổng Thể:

**Trên VPS:**

```bash
# Kiểm tra Laravel
cd /var/www/samnghethaycu.com
php artisan --version
# Phải thấy: Laravel Framework 12.x.x

# Kiểm tra database connection
php artisan migrate:status
# Phải thấy tables đã migrate

# Kiểm tra Nginx
sudo nginx -t
# Phải: syntax is ok

# Kiểm tra logs
tail -20 storage/logs/laravel.log
# Không có errors
```

### Bước Tiếp Theo:

```
→ WORKFLOW-3-GIT-WORKFLOW-SETUP.md
  Setup Git workflows chuyên nghiệp (SSH keys, branches)
```

---

## 🔧 XỬ LÝ SỰ CỐ

### Sự cố: 500 Internal Server Error

**Kiểm tra Laravel logs:**

**Trên VPS:**

```bash
tail -50 /var/www/samnghethaycu.com/storage/logs/laravel.log
```

**Kiểm tra Nginx error log:**

```bash
sudo tail -50 /var/log/nginx/samnghethaycu-error.log
```

**Fix thông thường:**

```bash
# Fix permissions
sudo chown -R www-data:www-data storage bootstrap/cache
sudo chmod -R 775 storage bootstrap/cache

# Clear caches
php artisan optimize:clear

# Restart PHP-FPM
sudo systemctl restart php8.4-fpm
```

---

### Sự cố: Database Connection Error

**Kiểm tra credentials:**

**Trên VPS:**

```bash
cat .env | grep DB_
# Kiểm tra DB_USERNAME, DB_PASSWORD, DB_DATABASE có đúng không
```

**Test MySQL connection:**

```bash
mysql -u samnghethaycu_user -p samnghethaycu
# Password: SamNghe@DB2025

# Nếu không kết nối được → credentials sai
# Xem lại file ~/credentials/database.txt
```

**Fix:**

```bash
# Sửa .env
nano .env
# Cập nhật DB_* cho đúng

# Clear cache
php artisan config:clear
```

---

### Sự cố: Nginx 403 Forbidden

**Nguyên nhân:** Permissions sai

**Trên VPS:**

```bash
# Fix directory permissions
sudo chmod 755 /var/www/samnghethaycu.com
sudo chmod 755 /var/www/samnghethaycu.com/public

# Fix file permissions
sudo chmod 644 /var/www/samnghethaycu.com/public/index.php

# Restart Nginx
sudo systemctl restart nginx
```

---

### Sự cố: SSL Certificate Không Hoạt Động

**Kiểm tra certificate:**

**Trên VPS:**

```bash
sudo certbot certificates
# Phải thấy certificate valid
```

**Kiểm tra Nginx config:**

```bash
sudo nginx -t
# Nếu có lỗi về SSL paths → check lại đường dẫn certificate
```

**Fix:**

```bash
# Sửa Nginx config
sudo nano /etc/nginx/sites-available/samnghethaycu.com
# Kiểm tra đường dẫn SSL certificate

# Test và restart
sudo nginx -t
sudo systemctl restart nginx
```

---

## 🔄 ROLLBACK: XÓA SẠCH VỀ WORKFLOW-1

⚠️ **KHI NÀO CẦN ROLLBACK:**
- WORKFLOW-2 gặp lỗi không fix được
- Muốn làm lại từ đầu
- Test lại quy trình

### BƯỚC 1: Xóa Laravel Khỏi VPS

**Trên VPS:**

```bash
# Dừng Nginx trước
sudo systemctl stop nginx

# Xóa toàn bộ thư mục Laravel
sudo rm -rf /var/www/samnghethaycu.com

# Kiểm tra đã xóa chưa
ls /var/www/
# Không còn thấy samnghethaycu.com
```

### BƯỚC 2: Xóa Nginx Config

**Trên VPS:**

```bash
# Xóa symlink sites-enabled
sudo rm -f /etc/nginx/sites-enabled/samnghethaycu.com

# Xóa file config
sudo rm -f /etc/nginx/sites-available/samnghethaycu.com

# Khôi phục default site
sudo ln -s /etc/nginx/sites-available/default /etc/nginx/sites-enabled/

# Test config
sudo nginx -t

# Restart Nginx
sudo systemctl start nginx
```

### BƯỚC 3: Xóa Database Tables Laravel

**Trên VPS:**

```bash
# Đăng nhập MySQL
mysql -u root -p
# Password: RootMySQL@2025
```

**Trong MySQL console:**

```sql
-- Xem tables Laravel đã tạo
USE samnghethaycu;
SHOW TABLES;

-- Xóa tất cả tables Laravel (nếu có)
SET FOREIGN_KEY_CHECKS = 0;
DROP TABLE IF EXISTS users, password_reset_tokens, sessions,
                     cache, cache_locks, jobs, job_batches,
                     failed_jobs, migrations;
SET FOREIGN_KEY_CHECKS = 1;

-- Kiểm tra đã xóa chưa
SHOW TABLES;
-- Phải empty (hoặc chỉ còn tables custom nếu có)

EXIT;
```

### BƯỚC 4: Xóa Logs

**Trên VPS:**

```bash
# Xóa Nginx logs của Laravel
sudo rm -f /var/log/nginx/samnghethaycu-access.log
sudo rm -f /var/log/nginx/samnghethaycu-error.log
```

### BƯỚC 5: Xóa Laravel Khỏi Windows (Tùy chọn)

**Trên Windows PowerShell:**

```powershell
# Backup trước (nếu cần)
Compress-Archive -Path C:\Projects\samnghethaycu -DestinationPath C:\Projects\samnghethaycu-backup.zip

# Xóa thư mục Laravel
Remove-Item C:\Projects\samnghethaycu -Recurse -Force

# Kiểm tra đã xóa chưa
Test-Path C:\Projects\samnghethaycu
# Phải trả về: False
```

### BƯỚC 6: Verify Rollback Hoàn Tất

**Trên VPS:**

```bash
# Kiểm tra services (phải còn chạy từ WORKFLOW-1)
systemctl status nginx mysql php8.4-fpm redis-server | grep Active
# Tất cả phải: active (running)

# Kiểm tra database còn sạch
mysql -u samnghethaycu_user -p samnghethaycu
# Password: SamNghe@DB2025

# Trong MySQL:
SHOW TABLES;
# Phải empty

EXIT;

# Kiểm tra Nginx
curl http://69.62.82.145
# Phải thấy: Welcome to nginx! (default page)
```

**Trên trình duyệt:**

```
http://69.62.82.145
```

**Phải thấy:** Trang "Welcome to nginx!" (default)

### ✅ Rollback Hoàn Tất!

**Bây giờ VPS về trạng thái sau WORKFLOW-1:**
- ✅ LEMP Stack còn nguyên
- ✅ MySQL database rỗng
- ✅ SSL certificate còn nguyên
- ✅ Nginx chạy default site
- ✅ Sẵn sàng làm lại WORKFLOW-2

**Để làm lại WORKFLOW-2:**
- Quay lại PHẦN 1 và làm từ đầu
- Hoặc fix lỗi cụ thể và continue

---

**Tạo ngày:** 2025-11-16
**Version:** 4.0 Professional Vietnamese
**Thời gian:** 15-20 phút thực tế

---

**KẾT THÚC WORKFLOW 2** 🚀
