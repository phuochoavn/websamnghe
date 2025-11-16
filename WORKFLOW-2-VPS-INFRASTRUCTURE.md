# 🖥️ WORKFLOW 2: VPS INFRASTRUCTURE

> **Dự án:** samnghethaycu.com - E-Commerce Platform
> **Version:** 2.0 Modular
> **Thời gian thực tế:** 20-25 phút
> **Mục tiêu:** LEMP Stack + SSL Certificate

---

## 📋 PREREQUISITES

### ✅ Must Complete First

```
✅ WORKFLOW-1-GIT-FOUNDATION.md completed
✅ Git working: Local → GitHub → VPS
✅ deploy-sam command ready
✅ Repository exists at: /var/www/samnghethaycu.com
```

### ✅ Quick Verification

**On VPS:**

```bash
ssh deploy@69.62.82.145
# Should connect without host key warning

cd /var/www/samnghethaycu.com
ls -la
# Should show: README.md, .git/

deploy-sam
# Should show: deployment script output
```

**All OK?** → Continue!

---

## 🎯 WHAT WE'LL BUILD

```
Fresh VPS
    ↓
Nginx Web Server
    ↓
MySQL 8 Database
    ↓
PHP 8.4 + Extensions
    ↓
Redis Cache
    ↓
Node.js 20
    ↓
Composer
    ↓
SSL Certificate (Let's Encrypt)
    ↓
Ready for Laravel Installation →
```

---

## PART 1: SYSTEM UPDATE

**Time:** 2 phút

**On VPS (as root or deploy with sudo):**

```bash
# Connect as root (easier for initial setup)
ssh root@69.62.82.145

# Update package list
apt update

# Upgrade packages
apt upgrade -y

# Install basic tools
apt install -y curl wget git unzip software-properties-common build-essential
```

✅ **Checkpoint 1:** System updated

---

## PART 2: NGINX WEB SERVER

**Time:** 3 phút

```bash
# Install Nginx
apt install nginx -y

# Start and enable
systemctl start nginx
systemctl enable nginx

# Check status
systemctl status nginx
# Press 'q' to exit

# Configure firewall
ufw allow 'Nginx Full'
ufw allow OpenSSH
ufw --force enable

# Test
curl http://localhost
# Should show HTML (Nginx welcome page)
```

**Browser test:**

```
http://69.62.82.145
```

**Should see:** "Welcome to nginx!"

✅ **Checkpoint 2:** Nginx working

---

## PART 3: MYSQL 8 DATABASE

**Time:** 5 phút

### 3.1. Install MySQL

```bash
apt install mysql-server -y

# Start and enable
systemctl start mysql
systemctl enable mysql
```

### 3.2. Secure MySQL

```bash
mysql_secure_installation
```

**Prompts & Answers:**

```
VALIDATE PASSWORD COMPONENT? n
Set root password? Y
  New password: RootMySQL@2025
  Re-enter: RootMySQL@2025
Remove anonymous users? Y
Disallow root login remotely? Y
Remove test database? Y
Reload privilege tables? Y
```

### 3.3. Create Database

```bash
# Login to MySQL
mysql -u root -p
# Password: RootMySQL@2025
```

**In MySQL console:**

```sql
-- Create database
CREATE DATABASE samnghethaycu
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;

-- Create user
CREATE USER 'samnghethaycu_user'@'localhost'
  IDENTIFIED BY 'SamNghe@DB2025';

-- Grant privileges
GRANT ALL PRIVILEGES ON samnghethaycu.*
  TO 'samnghethaycu_user'@'localhost';

-- Flush
FLUSH PRIVILEGES;

-- Verify
SHOW DATABASES;
-- Should show: samnghethaycu

-- Exit
EXIT;
```

### 3.4. Test Connection

```bash
# Test login as app user
mysql -u samnghethaycu_user -p samnghethaycu
# Password: SamNghe@DB2025

# In MySQL:
SELECT DATABASE();
# Should show: samnghethaycu

EXIT;
```

### 3.5. Save Credentials

```bash
# Switch to deploy user
su - deploy

# Create credentials file
mkdir -p ~/credentials
cat > ~/credentials/database.txt << 'EOF'
============================================
DATABASE CREDENTIALS - samnghethaycu.com
============================================

MySQL Root Password: RootMySQL@2025

Database Name: samnghethaycu
Database User: samnghethaycu_user
Database Password: SamNghe@DB2025
Database Host: localhost
Database Port: 3306

Connection String:
mysql -u samnghethaycu_user -p samnghethaycu
EOF

# Secure file
chmod 600 ~/credentials/database.txt

# View
cat ~/credentials/database.txt
```

✅ **Checkpoint 3:** MySQL configured

---

## PART 4: PHP 8.4

**Time:** 5 phút

### 4.1. Add PHP 8.4 Repository

```bash
# Switch back to root
exit
# (Or use sudo for each command)

# Add Ondřej PPA (trusted source for latest PHP)
add-apt-repository ppa:ondrej/php -y

# Update
apt update
```

### 4.2. Install PHP 8.4 + Laravel Extensions

```bash
# Install PHP 8.4 with all Laravel-required extensions
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

# Verify installation
php -v
# Should show: PHP 8.4.x

# Check loaded extensions
php -m | grep -E "mysql|mbstring|xml|curl"
# Should show all 4 extensions
```

### 4.3. Configure PHP-FPM

```bash
# Check PHP-FPM socket
ls -la /run/php/php8.4-fpm.sock
# Should exist

# Start and enable
systemctl start php8.4-fpm
systemctl enable php8.4-fpm

# Check status
systemctl status php8.4-fpm
# Should show: active (running)
```

✅ **Checkpoint 4:** PHP 8.4 ready

---

## PART 5: REDIS CACHE

**Time:** 2 phút

```bash
# Install Redis
apt install redis-server -y

# Start and enable
systemctl start redis-server
systemctl enable redis-server

# Test
redis-cli ping
# Should return: PONG

# Test set/get
redis-cli
> SET test "Hello Redis"
> GET test
# Should return: "Hello Redis"
> EXIT
```

✅ **Checkpoint 5:** Redis working

---

## PART 6: COMPOSER

**Time:** 2 phút

```bash
# Download installer
curl -sS https://getcomposer.org/installer -o composer-setup.php

# Install globally
php composer-setup.php \
  --install-dir=/usr/local/bin \
  --filename=composer

# Remove installer
rm composer-setup.php

# Verify
composer --version
# Should show: Composer version 2.x.x

# Make available to deploy user
chown root:root /usr/local/bin/composer
chmod 755 /usr/local/bin/composer
```

✅ **Checkpoint 6:** Composer installed

---

## PART 7: NODE.JS 20

**Time:** 2 phút

```bash
# Add NodeSource repository
curl -fsSL https://deb.nodesource.com/setup_20.x | bash -

# Install Node.js
apt install -y nodejs

# Verify
node -v
# Should show: v20.x.x

npm -v
# Should show: 10.x.x
```

✅ **Checkpoint 7:** Node.js ready

---

## PART 8: SSL CERTIFICATE

**Time:** 5 phút

### 8.1. Install Certbot

```bash
apt install certbot python3-certbot-nginx -y
```

### 8.2. Check DNS First!

**CRITICAL:** Domain must point to VPS before getting SSL!

```bash
# Check if domain resolves to VPS IP
dig +short samnghethaycu.com
# Should return: 69.62.82.145

dig +short www.samnghethaycu.com
# Should return: 69.62.82.145
```

**If NOT showing your IP:**
- Login to domain registrar (GoDaddy, Namecheap, etc.)
- Add A record: `@` → `69.62.82.145`
- Add A record: `www` → `69.62.82.145`
- Wait 5-30 minutes for DNS propagation

### 8.3. Obtain SSL Certificate

**STOP NGINX FIRST:**

```bash
systemctl stop nginx
```

**Get certificate:**

```bash
certbot certonly --standalone \
  -d samnghethaycu.com \
  -d www.samnghethaycu.com
```

**Prompts:**

```
Email address: your-email@example.com
Terms of Service: A (Agree)
Share email with EFF: N (No)
```

**Success message:**

```
Successfully received certificate.
Certificate is saved at: /etc/letsencrypt/live/samnghethaycu.com/fullchain.pem
Key is saved at: /etc/letsencrypt/live/samnghethaycu.com/privkey.pem
```

### 8.4. Verify Certificate Files

```bash
ls -la /etc/letsencrypt/live/samnghethaycu.com/
# Should show:
# fullchain.pem
# privkey.pem
# cert.pem
# chain.pem
```

### 8.5. Setup Auto-Renewal

```bash
# Test renewal (dry run)
certbot renew --dry-run
# Should succeed

# Check renewal timer
systemctl status certbot.timer
# Should show: active (waiting)
```

### 8.6. Start Nginx Again

```bash
systemctl start nginx
```

✅ **Checkpoint 8:** SSL certificate ready

---

## VERIFICATION

### Check All Services

```bash
# Nginx
systemctl status nginx | grep Active
# Should show: active (running)

# MySQL
systemctl status mysql | grep Active
# Should show: active (running)

# PHP-FPM
systemctl status php8.4-fpm | grep Active
# Should show: active (running)

# Redis
systemctl status redis-server | grep Active
# Should show: active (running)

# All services
systemctl list-units --state=running | grep -E "nginx|mysql|php|redis"
# Should show all 4 services
```

### Check Versions

```bash
# Nginx
nginx -v
# nginx version: nginx/1.x.x

# MySQL
mysql --version
# mysql  Ver 8.x.x

# PHP
php -v
# PHP 8.4.x

# Composer
composer --version
# Composer version 2.x.x

# Node
node -v && npm -v
# v20.x.x
# 10.x.x

# Redis
redis-server --version
# Redis server v=7.x.x
```

### Check SSL Certificate

```bash
certbot certificates
# Should show valid certificate for samnghethaycu.com
```

---

## ✅ WORKFLOW 2 COMPLETE!

### Infrastructure Ready:

```
✅ Nginx 1.x (web server)
✅ MySQL 8.x (database)
✅ PHP 8.4 + FPM (application)
✅ Redis 7.x (cache)
✅ Composer 2.x (dependency manager)
✅ Node.js 20.x (asset compiler)
✅ SSL Certificate (Let's Encrypt)
✅ Firewall configured (UFW)
✅ All services running
```

### Saved Credentials:

```bash
# View database credentials anytime
cat ~/credentials/database.txt
```

### Next Step:

```
→ WORKFLOW-3-LARAVEL-INSTALLATION.md
  Install Laravel 12 via Git
```

---

## 🔧 TROUBLESHOOTING

### Issue: MySQL root password forgotten

**Reset:**

```bash
sudo systemctl stop mysql
sudo mysqld_safe --skip-grant-tables &
mysql -u root

# In MySQL:
FLUSH PRIVILEGES;
ALTER USER 'root'@'localhost' IDENTIFIED BY 'RootMySQL@2025';
EXIT;

sudo systemctl restart mysql
```

### Issue: PHP-FPM not starting

**Check logs:**

```bash
journalctl -u php8.4-fpm -n 50
```

**Common fix:**

```bash
systemctl restart php8.4-fpm
```

### Issue: SSL certificate failed

**Check DNS first:**

```bash
dig +short samnghethaycu.com
# Must return VPS IP!
```

**Retry:**

```bash
systemctl stop nginx
certbot delete
certbot certonly --standalone -d samnghethaycu.com -d www.samnghethaycu.com
```

---

**Created:** 2025-11-16
**Version:** 2.0 Modular
**Time:** 20-25 minutes actual

---

**END OF WORKFLOW 2** 🖥️
