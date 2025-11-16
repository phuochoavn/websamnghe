# 🖥️ WORKFLOW 1: CÀI ĐẶT HẠ TẦNG VPS

> **Dự án:** samnghethaycu.com - E-Commerce Platform
> **Version:** 4.0 Professional Vietnamese
> **Thời gian thực tế:** 20-25 phút
> **Mục tiêu:** LEMP Stack + SSL Certificate

---

## 📋 ĐIỀU KIỆN TIÊN QUYẾT

### ✅ Những gì cần có

```
✅ VPS chạy Ubuntu (khuyên dùng 24.04 LTS)
✅ Quyền root hoặc sudo user
✅ Địa chỉ IP public (ví dụ: 69.62.82.145)
✅ Tên miền đã trỏ về IP của VPS (ví dụ: samnghethaycu.com)
```

**Lưu ý:** Đây là workflow ĐẦU TIÊN. Không cần làm workflow nào trước đó.

---

## 🎯 NHỮNG GÌ CHÚNG TA SẼ XÂY DỰNG

```
VPS mới tinh
    ↓
Nginx Web Server (máy chủ web)
    ↓
MySQL 8 Database (cơ sở dữ liệu)
    ↓
PHP 8.4 + Extensions (ngôn ngữ Laravel)
    ↓
Redis Cache (bộ nhớ đệm)
    ↓
Node.js 20 (biên dịch assets)
    ↓
Composer (quản lý packages PHP)
    ↓
SSL Certificate Let's Encrypt (HTTPS miễn phí)
    ↓
Sẵn sàng cài Laravel →
```

---

## PHẦN 0: CHUẨN BỊ BAN ĐẦU

**Thời gian:** 2 phút

### 0.1. Xóa SSH Key Cũ (Nếu Reset VPS)

⚠️ **QUAN TRỌNG:** Nếu bạn đã reset VPS hoặc cài lại Ubuntu, SSH key cũ sẽ conflict!

**Triệu chứng:**

```
@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@
@    WARNING: REMOTE HOST IDENTIFICATION HAS CHANGED!     @
@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@
```

**Giải thích:**
- Khi reset VPS, server tạo SSH key mới
- SSH key cũ đã lưu trên máy Windows của bạn không khớp nữa
- Phải xóa key cũ để lưu key mới

**Trên Windows PowerShell:**

```powershell
# Xóa SSH key cũ của VPS
ssh-keygen -R 69.62.82.145

# Thông báo sẽ hiện:
# Host 69.62.82.145 found: line 1
# /c/Users/Hoa/.ssh/known_hosts updated.
# Original contents retained as /c/Users/Hoa/.ssh/known_hosts.old
```

**Hoặc xóa thủ công:**

```powershell
# Mở file known_hosts
notepad C:\Users\Hoa\.ssh\known_hosts

# Tìm dòng chứa "69.62.82.145"
# Xóa toàn bộ dòng đó
# Save (Ctrl+S) và đóng Notepad
```

✅ **Checkpoint 0:** SSH key cũ đã xóa

---

### 0.2. Kết Nối VPS Lần Đầu

**Trên Windows PowerShell:**

```powershell
# Kết nối bằng user root
ssh root@69.62.82.145
```

**Lần đầu tiên sẽ hỏi:**

```
The authenticity of host '69.62.82.145 (69.62.82.145)' can't be established.
ED25519 key fingerprint is SHA256:xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx.
Are you sure you want to continue connecting (yes/no/[fingerprint])?
```

**Gõ:** `yes` (phải gõ đủ từ `yes`, không được chỉ gõ `y`)

**Nhập password root** (do VPS provider cung cấp)

**Thành công:** Bạn sẽ thấy prompt `root@vps:~#`

✅ **Checkpoint 0.2:** Đã kết nối VPS thành công

---

## PHẦN 1: CẬP NHẬT HỆ THỐNG

**Thời gian:** 2 phút

**Trên VPS (as root):**

```bash
# Cập nhật danh sách packages
apt update

# Nâng cấp các packages đã cài
apt upgrade -y

# Cài các công cụ cơ bản
apt install -y curl wget git unzip software-properties-common build-essential
```

**Giải thích:**
- `apt update`: Cập nhật danh sách phần mềm có sẵn
- `apt upgrade -y`: Nâng cấp tất cả phần mềm lên version mới nhất (-y = tự động Yes)
- `curl, wget`: Tải file từ internet
- `git`: Version control
- `unzip`: Giải nén file
- `software-properties-common`: Quản lý PPA repositories
- `build-essential`: Công cụ biên dịch (gcc, make, etc.)

✅ **Checkpoint 1:** Hệ thống đã cập nhật

---

## PHẦN 2: NGINX WEB SERVER

**Thời gian:** 3 phút

**Trên VPS:**

```bash
# Cài đặt Nginx
apt install nginx -y

# Khởi động Nginx
systemctl start nginx

# Cho phép Nginx tự chạy khi reboot
systemctl enable nginx

# Kiểm tra trạng thái
systemctl status nginx
# Nhấn 'q' để thoát

# Cấu hình firewall
ufw allow 'Nginx Full'
ufw allow OpenSSH
ufw --force enable

# Test Nginx có chạy không
curl http://localhost
# Phải thấy HTML (trang welcome của Nginx)
```

**Giải thích:**
- `Nginx Full`: Cho phép cả HTTP (80) và HTTPS (443)
- `OpenSSH`: Cho phép SSH (22) - QUAN TRỌNG để không bị khóa khỏi VPS!
- `ufw --force enable`: Bật firewall (--force để không hỏi confirm)

**Kiểm tra trên trình duyệt:**

```
http://69.62.82.145
```

**Phải thấy:** Trang "Welcome to nginx!"

✅ **Checkpoint 2:** Nginx đang chạy

---

## PHẦN 3: MYSQL 8 DATABASE

**Thời gian:** 5 phút

### 3.1. Cài Đặt MySQL

**Trên VPS:**

```bash
# Cài MySQL Server
apt install mysql-server -y

# Khởi động MySQL
systemctl start mysql

# Cho phép tự chạy khi reboot
systemctl enable mysql
```

### 3.2. Bảo Mật MySQL

**Trên VPS:**

```bash
# Chạy script bảo mật
mysql_secure_installation
```

**Các câu hỏi và trả lời:**

```
VALIDATE PASSWORD COMPONENT? n
  → Không dùng password complexity check (cho dễ nhớ)

Set root password? Y
  New password: RootMySQL@2025
  Re-enter: RootMySQL@2025
  → Đặt password cho root

Remove anonymous users? Y
  → Xóa user ẩn danh (bảo mật)

Disallow root login remotely? Y
  → Không cho root login từ xa (bảo mật)

Remove test database? Y
  → Xóa database test (không cần)

Reload privilege tables? Y
  → Reload để áp dụng thay đổi
```

### 3.3. Tạo Database và User

**Trên VPS:**

```bash
# Đăng nhập MySQL với user root
mysql -u root -p
# Nhập password: RootMySQL@2025
```

**Trong MySQL console:**

```sql
-- Tạo database với UTF-8 encoding (hỗ trợ tiếng Việt)
CREATE DATABASE samnghethaycu
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;

-- Tạo user riêng cho Laravel (không dùng root)
CREATE USER 'samnghethaycu_user'@'localhost'
  IDENTIFIED BY 'SamNghe@DB2025';

-- Cấp quyền cho user trên database
GRANT ALL PRIVILEGES ON samnghethaycu.*
  TO 'samnghethaycu_user'@'localhost';

-- Áp dụng thay đổi
FLUSH PRIVILEGES;

-- Kiểm tra database đã tạo chưa
SHOW DATABASES;
-- Phải thấy: samnghethaycu

-- Thoát khỏi MySQL
EXIT;
```

**Giải thích:**
- `utf8mb4`: Hỗ trợ emoji và ký tự đặc biệt (Unicode đầy đủ)
- `'user'@'localhost'`: User chỉ kết nối từ chính VPS (bảo mật)
- `GRANT ALL PRIVILEGES`: User có full quyền trên database này
- `FLUSH PRIVILEGES`: Reload để áp dụng ngay

### 3.4. Test Kết Nối

**Trên VPS:**

```bash
# Đăng nhập bằng user Laravel
mysql -u samnghethaycu_user -p samnghethaycu
# Password: SamNghe@DB2025

# Trong MySQL console:
SELECT DATABASE();
# Phải thấy: samnghethaycu

EXIT;
```

### 3.5. Lưu Thông Tin Database

**Trên VPS:**

```bash
# Tạo thư mục lưu credentials
mkdir -p ~/credentials

# Tạo file lưu thông tin database
cat > ~/credentials/database.txt << 'EOF'
============================================
THÔNG TIN DATABASE - samnghethaycu.com
============================================

MySQL Root Password: RootMySQL@2025

Database Name: samnghethaycu
Database User: samnghethaycu_user
Database Password: SamNghe@DB2025
Database Host: localhost
Database Port: 3306

Kết nối MySQL:
mysql -u samnghethaycu_user -p samnghethaycu

Laravel .env:
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=samnghethaycu
DB_USERNAME=samnghethaycu_user
DB_PASSWORD=SamNghe@DB2025
EOF

# Bảo mật file (chỉ owner đọc được)
chmod 600 ~/credentials/database.txt

# Xem nội dung
cat ~/credentials/database.txt
```

**Giải thích:**
- `chmod 600`: Chỉ user root đọc được, người khác không thấy
- File này để xem lại thông tin khi cần (không phải nhớ password)

✅ **Checkpoint 3:** MySQL đã cấu hình xong

---

## PHẦN 4: PHP 8.4

**Thời gian:** 5 phút

### 4.1. Thêm Repository PHP 8.4

**Trên VPS:**

```bash
# Thêm PPA của Ondřej Surý (nguồn PHP chính thức)
add-apt-repository ppa:ondrej/php -y

# Cập nhật lại danh sách packages
apt update
```

**Giải thích:**
- Ubuntu mặc định chỉ có PHP 8.3
- PPA Ondřej cung cấp PHP 8.4 mới nhất
- Đây là PPA official, an toàn và được cập nhật thường xuyên

### 4.2. Cài PHP 8.4 + Extensions Laravel Cần

**Trên VPS:**

```bash
# Cài PHP 8.4 và tất cả extensions Laravel yêu cầu
apt install -y \
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

# Kiểm tra version PHP
php -v
# Phải thấy: PHP 8.4.x

# Kiểm tra extensions đã load chưa
php -m | grep -E "mysql|mbstring|xml|curl"
# Phải thấy cả 4 extensions
```

**Giải thích các extensions:**
- `php8.4-fpm`: FastCGI Process Manager (chạy PHP với Nginx)
- `php8.4-cli`: Command Line Interface (chạy artisan commands)
- `php8.4-mysql`: Kết nối MySQL
- `php8.4-mbstring`: Xử lý chuỗi đa ngôn ngữ (tiếng Việt)
- `php8.4-xml`: Xử lý XML (Laravel cần)
- `php8.4-bcmath`: Tính toán số lớn (tiền tệ chính xác)
- `php8.4-curl`: HTTP requests
- `php8.4-gd`: Xử lý ảnh (resize, crop)
- `php8.4-zip`: Giải nén/nén file
- `php8.4-intl`: Internationalization (format số, tiền tệ)
- `php8.4-redis`: Kết nối Redis cache
- `php8.4-imagick`: Xử lý ảnh nâng cao (ImageMagick)

### 4.3. Cấu Hình PHP-FPM

**Trên VPS:**

```bash
# Kiểm tra socket PHP-FPM đã tạo chưa
ls -la /run/php/php8.4-fpm.sock
# Phải thấy file socket

# Khởi động PHP-FPM
systemctl start php8.4-fpm

# Cho phép tự chạy khi reboot
systemctl enable php8.4-fpm

# Kiểm tra trạng thái
systemctl status php8.4-fpm
# Phải thấy: active (running)
```

**Giải thích:**
- PHP-FPM socket: Nginx sẽ giao tiếp với PHP qua socket này
- Socket nằm ở `/run/php/php8.4-fpm.sock`

✅ **Checkpoint 4:** PHP 8.4 sẵn sàng

---

## PHẦN 5: REDIS CACHE

**Thời gian:** 2 phút

**Trên VPS:**

```bash
# Cài Redis Server
apt install redis-server -y

# Khởi động Redis
systemctl start redis-server

# Cho phép tự chạy khi reboot
systemctl enable redis-server

# Test Redis có hoạt động không
redis-cli ping
# Phải trả về: PONG

# Test set/get key
redis-cli
```

**Trong Redis CLI:**

```
> SET test "Hello Redis"
OK
> GET test
"Hello Redis"
> EXIT
```

**Giải thích:**
- Redis: In-memory database, dùng làm cache, session storage
- Laravel dùng Redis để cache queries, sessions, queue jobs
- Nhanh hơn MySQL rất nhiều (vì ở RAM)

✅ **Checkpoint 5:** Redis đang chạy

---

## PHẦN 6: COMPOSER

**Thời gian:** 2 phút

**Trên VPS:**

```bash
# Tải installer của Composer
curl -sS https://getcomposer.org/installer -o composer-setup.php

# Cài Composer global (dùng được mọi nơi)
php composer-setup.php \
  --install-dir=/usr/local/bin \
  --filename=composer

# Xóa file installer
rm composer-setup.php

# Kiểm tra version
composer --version
# Phải thấy: Composer version 2.x.x

# Đặt quyền cho file composer
chown root:root /usr/local/bin/composer
chmod 755 /usr/local/bin/composer
```

**Giải thích:**
- Composer: Package manager của PHP (như npm của JavaScript)
- Laravel dùng Composer để cài packages (Filament, Spatie, etc.)
- `--install-dir=/usr/local/bin`: Cài global, mọi user đều dùng được
- `chmod 755`: Mọi user đọc và thực thi được

✅ **Checkpoint 6:** Composer đã cài

---

## PHẦN 7: NODE.JS 20

**Thời gian:** 2 phút

**Trên VPS:**

```bash
# Thêm repository NodeSource cho Node.js 20
curl -fsSL https://deb.nodesource.com/setup_20.x | bash -

# Cài Node.js
apt install -y nodejs

# Kiểm tra version
node -v
# Phải thấy: v20.x.x

npm -v
# Phải thấy: 10.x.x
```

**Giải thích:**
- Node.js: JavaScript runtime
- Laravel dùng npm để cài Vite, Tailwind CSS
- Vite dùng để biên dịch CSS/JS (build assets)

✅ **Checkpoint 7:** Node.js sẵn sàng

---

## PHẦN 8: SSL CERTIFICATE (HTTPS)

**Thời gian:** 5 phút

### 8.1. Cài Certbot

**Trên VPS:**

```bash
# Cài Certbot và plugin Nginx
apt install certbot python3-certbot-nginx -y
```

### 8.2. Kiểm Tra DNS Trước!

⚠️ **QUAN TRỌNG:** Tên miền PHẢI trỏ về IP của VPS trước khi lấy SSL!

**Trên VPS:**

```bash
# Kiểm tra domain có trỏ về VPS chưa
dig +short samnghethaycu.com
# Phải trả về: 69.62.82.145

dig +short www.samnghethaycu.com
# Phải trả về: 69.62.82.145
```

**Nếu KHÔNG trả về IP của VPS:**

**Trên Windows/Mac (trình duyệt):**

1. Đăng nhập vào nhà đăng ký tên miền (GoDaddy, Namecheap, etc.)
2. Vào **DNS Management** hoặc **DNS Settings**
3. Thêm 2 bản ghi A record:
   ```
   Type: A
   Name: @
   Value: 69.62.82.145
   TTL: 600 (hoặc tự động)

   Type: A
   Name: www
   Value: 69.62.82.145
   TTL: 600
   ```
4. **Chờ 5-30 phút** để DNS propagation (lan truyền DNS)
5. Chạy lại lệnh `dig` ở trên để kiểm tra

### 8.3. Lấy SSL Certificate

**Trên VPS:**

```bash
# Dừng Nginx trước (để Certbot dùng port 80)
systemctl stop nginx

# Lấy SSL certificate
certbot certonly --standalone \
  -d samnghethaycu.com \
  -d www.samnghethaycu.com
```

**Các câu hỏi:**

```
Enter email address: your-email@example.com
  → Nhập email của bạn (để nhận thông báo renew)

Terms of Service: A
  → Gõ A để Agree (đồng ý)

Share email with EFF: N
  → Gõ N (không chia sẻ email)
```

**Thông báo thành công:**

```
Successfully received certificate.
Certificate is saved at: /etc/letsencrypt/live/samnghethaycu.com/fullchain.pem
Key is saved at: /etc/letsencrypt/live/samnghethaycu.com/privkey.pem
This certificate expires on 2025-02-14.
```

**Giải thích:**
- Let's Encrypt: SSL miễn phí, tự động renew
- `certonly --standalone`: Lấy cert mà không config Nginx (ta sẽ config thủ công)
- Certificate có hiệu lực 90 ngày, tự động renew sau 60 ngày

### 8.4. Kiểm Tra Files Certificate

**Trên VPS:**

```bash
# Liệt kê files certificate
ls -la /etc/letsencrypt/live/samnghethaycu.com/
# Phải thấy:
# fullchain.pem  (certificate + chain)
# privkey.pem    (private key)
# cert.pem       (certificate only)
# chain.pem      (chain only)
```

### 8.5. Setup Tự Động Renew

**Trên VPS:**

```bash
# Test renewal (dry run - không thật)
certbot renew --dry-run
# Phải thành công

# Kiểm tra timer tự động renew
systemctl status certbot.timer
# Phải thấy: active (waiting)
```

**Giải thích:**
- Certbot tự động tạo systemd timer
- Timer này chạy 2 lần/ngày để check và renew certificate
- Certificate tự renew khi còn < 30 ngày

### 8.6. Khởi Động Lại Nginx

**Trên VPS:**

```bash
# Khởi động lại Nginx
systemctl start nginx
```

✅ **Checkpoint 8:** SSL certificate sẵn sàng

---

## ✅ HOÀN THÀNH WORKFLOW 1!

### Hạ Tầng Đã Sẵn Sàng:

```
✅ Nginx 1.x (web server)
✅ MySQL 8.x (database)
✅ PHP 8.4 + FPM (ngôn ngữ ứng dụng)
✅ Redis 7.x (cache)
✅ Composer 2.x (quản lý packages PHP)
✅ Node.js 20.x (biên dịch assets)
✅ SSL Certificate (HTTPS miễn phí)
✅ Firewall UFW (bảo mật)
✅ Tất cả services đang chạy
```

### Kiểm Tra Tổng Thể

**Trên VPS:**

```bash
# Kiểm tra tất cả services
systemctl status nginx mysql php8.4-fpm redis-server | grep Active
# Tất cả phải: active (running)

# Kiểm tra versions
echo "=== VERSIONS ==="
nginx -v
mysql --version
php -v
composer --version
node -v
redis-server --version

# Kiểm tra SSL certificate
certbot certificates
# Phải thấy certificate hợp lệ cho samnghethaycu.com
```

### Thông Tin Đã Lưu:

**Trên VPS:**

```bash
# Xem lại thông tin database bất cứ lúc nào
cat ~/credentials/database.txt
```

### Bước Tiếp Theo:

```
→ WORKFLOW-2-LARAVEL-INSTALLATION.md
  Cài đặt Laravel 12 trên VPS
```

---

## 🔧 XỬ LÝ SỰ CỐ

### Sự cố: Quên password root MySQL

**Trên VPS:**

```bash
# Dừng MySQL
sudo systemctl stop mysql

# Khởi động MySQL ở chế độ skip password
sudo mysqld_safe --skip-grant-tables &

# Đăng nhập MySQL không cần password
mysql -u root
```

**Trong MySQL:**

```sql
FLUSH PRIVILEGES;
ALTER USER 'root'@'localhost' IDENTIFIED BY 'RootMySQL@2025';
EXIT;
```

**Trên VPS:**

```bash
# Khởi động lại MySQL bình thường
sudo systemctl restart mysql
```

---

### Sự cố: PHP-FPM không khởi động

**Trên VPS:**

```bash
# Xem logs lỗi
journalctl -u php8.4-fpm -n 50

# Thử khởi động lại
systemctl restart php8.4-fpm

# Kiểm tra config có lỗi không
php-fpm8.4 -t
```

---

### Sự cố: SSL certificate failed

**Nguyên nhân thường gặp:**
- Domain chưa trỏ về VPS
- Port 80 đang bị dùng
- Firewall chặn port 80

**Trên VPS:**

```bash
# Kiểm tra DNS trước
dig +short samnghethaycu.com
# PHẢI trả về IP của VPS!

# Xóa certificate cũ và thử lại
certbot delete

# Lấy lại certificate
systemctl stop nginx
certbot certonly --standalone \
  -d samnghethaycu.com \
  -d www.samnghethaycu.com
systemctl start nginx
```

---

### Sự cố: SSH bị khóa sau khi enable UFW

**Nếu bạn quên `ufw allow OpenSSH`:**

**Trên VPS Provider Dashboard:**
- Dùng console/VNC của provider để login
- Chạy: `ufw allow OpenSSH`
- Hoặc: `ufw disable` (tạm thời tắt firewall)

**Phòng tránh:**
- LUÔN LUÔN chạy `ufw allow OpenSSH` TRƯỚC KHI `ufw enable`!

---

**Tạo ngày:** 2025-11-16
**Version:** 4.0 Professional Vietnamese
**Thời gian:** 20-25 phút thực tế

---

**KẾT THÚC WORKFLOW 1** 🖥️
