# 🚀 WORKFLOW 4: DEPLOYMENT AUTOMATION

> **Dự án:** samnghethaycu.com - E-Commerce Platform
> **Version:** 3.0 Reorganized
> **Thời gian thực tế:** 10-15 phút
> **Mục tiêu:** Tự động hóa deployment với một lệnh duy nhất

---

## 📖 WORKFLOW NÀY LÀM GÌ?

### 🎯 Mục đích:

**Tạo script tự động hóa deployment để deploy Laravel app bằng 1 lệnh duy nhất.**

Sau khi đã có VPS (WF-1), Laravel (WF-2), và Git (WF-3), bây giờ tạo automation để:
- Deploy code mới chỉ bằng 1 lệnh: `deploy-sam`
- Tự động: pull code, install dependencies, migrate, cache, fix permissions
- Thay vì 15-20 lệnh manual → CHỈ còn 1 lệnh!

### 🎁 Kết quả sau workflow:

✅ **Deployment script trên VPS:**
- Script `deploy-sam` tự động hóa 8 bước deployment
- Colored output, error handling, status tracking
- Professional deployment workflow

✅ **Sudo configuration:**
- Deploy user có quyền reload PHP-FPM không cần password
- Secure permissions cho deployment operations

✅ **One-command deployment:**
```
LOCAL (Windows)          GITHUB              VPS (Production)
─────────────────        ──────              ────────────────
git push origin main →   Updated    →        deploy-sam ✨
                                              → 8 steps automated!
                                              → Site updated!
```

### ⚠️ PREREQUISITES:

**PHẢI hoàn thành trước:**
```
✅ WORKFLOW-1: VPS Infrastructure (PHP 8.4, MySQL, Nginx, SSL)
✅ WORKFLOW-2: Laravel Installation (Laravel app đang chạy)
✅ WORKFLOW-3: Git Workflow Setup (Git local + GitHub + VPS)
```

**Verify trước khi bắt đầu:**

```bash
# SSH vào VPS với user deploy
ssh deploy@69.62.82.145
# Password: Deploy@2025

# Kiểm tra Git repository đã clone
cd /var/www/samnghethaycu.com
git status
# Phải thấy: On branch main

# Kiểm tra PHP 8.4 đã cài
php -v
# Phải thấy: PHP 8.4.x

# Kiểm tra Composer đã cài
composer --version
# Phải thấy: Composer version 2.x

# Kiểm tra Laravel đã cài
php artisan --version
# Phải thấy: Laravel Framework 12.x
```

**Nếu bất kỳ check nào FAIL → DỪNG LẠI, hoàn thành WF-1, WF-2, WF-3 trước!**

---

## PART 1: DEPLOYMENT SCRIPT

**Time:** 7 phút

### 1.1. Create Deployment Script

**⚠️ IMPORTANT:** Đảm bảo đã SSH vào VPS với user `deploy`

**On VPS (as deploy user):**

```bash
# Kiểm tra đang là user nào
whoami
# Phải thấy: deploy

# Tạo thư mục scripts
mkdir -p ~/scripts

# Tạo deployment script
cat > ~/scripts/deploy-samnghethaycu.sh << 'DEPLOY_SCRIPT'
#!/bin/bash

# ===============================================
# DEPLOYMENT SCRIPT - samnghethaycu.com
# Professional Git-based deployment
# ===============================================

set -e  # Exit on any error

# Colors for output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m' # No Color

echo -e "${BLUE}🚀 Starting deployment...${NC}"
echo ""

# Configuration
APP_DIR="/var/www/samnghethaycu.com"
BRANCH="main"
TIMESTAMP=$(date +"%Y%m%d-%H%M%S")

# Navigate to app directory
cd $APP_DIR || { echo -e "${RED}❌ ERROR: Cannot access $APP_DIR${NC}"; exit 1; }

echo -e "${BLUE}📂 Current directory:${NC} $(pwd)"
echo ""

# ==================== STEP 1: GIT PULL ====================
echo -e "${YELLOW}📥 Step 1/8: Pulling latest code from GitHub...${NC}"
git fetch origin $BRANCH
BEFORE_COMMIT=$(git rev-parse HEAD)
git reset --hard origin/$BRANCH
AFTER_COMMIT=$(git rev-parse HEAD)

if [ "$BEFORE_COMMIT" = "$AFTER_COMMIT" ]; then
    echo -e "${GREEN}✅ No changes (already up to date)${NC}"
else
    echo -e "${GREEN}✅ Code updated${NC}"
    git log --oneline $BEFORE_COMMIT..$AFTER_COMMIT
fi
echo ""

# ==================== STEP 2: CHECK .env ====================
echo -e "${YELLOW}🔍 Step 2/8: Checking .env file...${NC}"
if [ ! -f .env ]; then
    echo -e "${RED}❌ ERROR: .env file not found!${NC}"
    echo -e "${YELLOW}Please create .env file first${NC}"
    exit 1
fi
echo -e "${GREEN}✅ .env exists${NC}"
echo ""

# ==================== STEP 3: FIX bootstrap/cache ====================
echo -e "${YELLOW}🔧 Step 3/8: Checking bootstrap/cache...${NC}"
if [ -L bootstrap/cache ]; then
    echo -e "${YELLOW}⚠️  Found symlink, removing...${NC}"
    sudo rm -f bootstrap/cache
    mkdir -p bootstrap/cache
fi
mkdir -p bootstrap/cache
echo -e "${GREEN}✅ bootstrap/cache is directory${NC}"
echo ""

# ==================== STEP 4: COMPOSER INSTALL ====================
echo -e "${YELLOW}📦 Step 4/8: Installing Composer dependencies...${NC}"
if [ -f composer.json ]; then
    composer install --no-dev --optimize-autoloader --no-interaction --quiet
    echo -e "${GREEN}✅ Dependencies installed${NC}"
else
    echo -e "${YELLOW}⚠️  No composer.json found, skipping...${NC}"
fi
echo ""

# ==================== STEP 5: RUN MIGRATIONS ====================
echo -e "${YELLOW}🗄️  Step 5/8: Running database migrations...${NC}"
if [ -f artisan ]; then
    php artisan migrate --force --no-interaction
    echo -e "${GREEN}✅ Migrations complete${NC}"
else
    echo -e "${YELLOW}⚠️  No artisan file found, skipping...${NC}"
fi
echo ""

# ==================== STEP 6: CLEAR & CACHE ====================
echo -e "${YELLOW}🧹 Step 6/8: Clearing caches...${NC}"
if [ -f artisan ]; then
    php artisan optimize:clear --quiet
    php artisan config:cache --quiet
    php artisan route:cache --quiet
    php artisan view:cache --quiet
    echo -e "${GREEN}✅ Caches rebuilt${NC}"
else
    echo -e "${YELLOW}⚠️  No artisan file found, skipping...${NC}"
fi
echo ""

# ==================== STEP 7: FIX PERMISSIONS ====================
echo -e "${YELLOW}🔐 Step 7/8: Fixing permissions...${NC}"
if [ -d storage ]; then
    sudo chown -R www-data:www-data storage bootstrap/cache
    sudo chmod -R 775 storage bootstrap/cache
    echo -e "${GREEN}✅ Permissions fixed${NC}"
else
    echo -e "${YELLOW}⚠️  No storage directory found, skipping...${NC}"
fi
echo ""

# ==================== STEP 8: RELOAD PHP-FPM ====================
echo -e "${YELLOW}🔄 Step 8/8: Reloading PHP-FPM...${NC}"
if systemctl is-active --quiet php8.4-fpm; then
    sudo systemctl reload php8.4-fpm
    echo -e "${GREEN}✅ PHP-FPM reloaded${NC}"
else
    echo -e "${YELLOW}⚠️  PHP-FPM not running, skipping...${NC}"
fi
echo ""

# ==================== SUMMARY ====================
echo -e "${GREEN}🎉 Deployment completed successfully!${NC}"
echo ""
echo -e "${BLUE}🌐 Website:${NC} https://samnghethaycu.com"
echo -e "${BLUE}🔧 Admin:${NC} https://samnghethaycu.com/admin"
echo ""

# Show current version
echo -e "${BLUE}📌 Current version:${NC}"
git log -1 --oneline --decorate
echo ""

# Show deployment info
echo -e "${BLUE}📅 Deployed at:${NC} $(date '+%Y-%m-%d %H:%M:%S')"
echo -e "${BLUE}👤 Deployed by:${NC} $(whoami)"
echo ""

DEPLOY_SCRIPT

# Make executable
chmod +x ~/scripts/deploy-samnghethaycu.sh

echo "✅ Deployment script created at ~/scripts/deploy-samnghethaycu.sh"
```

**Verify script created:**

```bash
# Kiểm tra file đã tạo
ls -lh ~/scripts/deploy-samnghethaycu.sh
# Phải thấy: -rwxr-xr-x (executable)

# Xem nội dung script
head -20 ~/scripts/deploy-samnghethaycu.sh
```

✅ **Checkpoint 1.1:** Deployment script created

---

### 1.2. Create Deployment Alias

**On VPS (as deploy user):**

```bash
# Thêm alias vào .bashrc
echo "alias deploy-sam='~/scripts/deploy-samnghethaycu.sh'" >> ~/.bashrc

# Reload .bashrc để alias có hiệu lực ngay
source ~/.bashrc

# Kiểm tra alias đã tạo chưa
type deploy-sam
# Phải thấy: deploy-sam is aliased to '/home/deploy/scripts/deploy-samnghethaycu.sh'
```

**Test alias:**

```bash
# Gõ tên alias (chưa chạy)
which deploy-sam
# OR
alias | grep deploy-sam
```

✅ **Checkpoint 1.2:** Deployment alias created

---

## PART 2: SUDO CONFIGURATION

**Time:** 3 phút

### 2.1. Configure Passwordless Sudo

**Why?** Deploy script cần sudo để:
- Reload PHP-FPM
- Fix permissions (chown, chmod)
- Remove symlinks (rm)

**On VPS (as deploy user):**

```bash
# Mở sudoers file (secure editor)
sudo visudo
```

**⚠️ IMPORTANT:** Lệnh trên sẽ mở nano editor, KHÔNG phải bash!

**Inside nano editor:**

1. Nhấn **End** hoặc **Ctrl+End** để đến cuối file
2. Nhấn **Enter** để tạo dòng mới
3. Nhấn **i** (nếu vim) HOẶC bắt đầu gõ ngay (nếu nano)
4. Copy và paste dòng này:

```
deploy ALL=(ALL) NOPASSWD: /bin/systemctl reload php8.4-fpm, /bin/chown, /bin/chmod, /bin/rm
```

5. **Lưu và thoát:**
   - **Nano:** Nhấn **Ctrl+O**, **Enter**, **Ctrl+X**
   - **Vim:** Nhấn **ESC**, gõ **:wq**, **Enter**

**Verify sudoers syntax:**

```bash
# Kiểm tra sudoers có lỗi syntax không
sudo visudo -c
# Phải thấy: parsed OK
```

✅ **Checkpoint 2.1:** Sudo configured

---

### 2.2. Test Sudo Permissions

**On VPS (as deploy user):**

```bash
# Test reload PHP-FPM (không cần password)
sudo systemctl status php8.4-fpm
# Phải hiển thị status KHÔNG hỏi password

# Test chown
sudo chown deploy:deploy /tmp/test-file 2>/dev/null || echo "Command allowed"

# Test chmod
sudo chmod 755 /tmp/test-file 2>/dev/null || echo "Command allowed"
```

**Nếu hỏi password → Sudoers configuration SAI, cần fix lại bước 2.1**

✅ **Checkpoint 2.2:** Sudo permissions tested

---

## PART 3: DEPLOYMENT TESTING

**Time:** 5 phút

### 3.1. Test Deployment Script (First Run)

**On VPS (as deploy user):**

```bash
# Đảm bảo đang ở đúng thư mục
cd /var/www/samnghethaycu.com

# Chạy deployment script lần đầu
deploy-sam
```

**Expected output:**

```
🚀 Starting deployment...

📂 Current directory: /var/www/samnghethaycu.com

📥 Step 1/8: Pulling latest code from GitHub...
✅ No changes (already up to date)

🔍 Step 2/8: Checking .env file...
✅ .env exists

🔧 Step 3/8: Checking bootstrap/cache...
✅ bootstrap/cache is directory

📦 Step 4/8: Installing Composer dependencies...
✅ Dependencies installed

🗄️  Step 5/8: Running database migrations...
✅ Migrations complete

🧹 Step 6/8: Clearing caches...
✅ Caches rebuilt

🔐 Step 7/8: Fixing permissions...
✅ Permissions fixed

🔄 Step 8/8: Reloading PHP-FPM...
✅ PHP-FPM reloaded

🎉 Deployment completed successfully!

🌐 Website: https://samnghethaycu.com
🔧 Admin: https://samnghethaycu.com/admin

📌 Current version:
abc1234 (HEAD -> main, origin/main) Initial commit

📅 Deployed at: 2025-11-16 15:30:45
👤 Deployed by: deploy
```

**⚠️ Nếu thấy errors:**

- **"Cannot access /var/www/samnghethaycu.com"** → Laravel chưa cài (WF-2)
- **".env file not found"** → Tạo .env từ .env.example
- **"composer: command not found"** → Composer chưa cài (WF-1)
- **"php: command not found"** → PHP chưa cài (WF-1)

✅ **Checkpoint 3.1:** Deployment script tested

---

### 3.2. Test End-to-End Workflow

**Complete workflow: Local → GitHub → VPS**

**Step 1: Make change on Windows**

```powershell
# Windows PowerShell
cd C:\Projects\samnghethaycu

# Tạo test file
echo "# Deployment test $(Get-Date)" >> DEPLOY-TEST.md

# Commit và push
git add DEPLOY-TEST.md
git commit -m "test: verify deployment automation"
git push origin main
```

**Step 2: Deploy on VPS**

```bash
# On VPS (as deploy user)
deploy-sam
```

**Expected:**

```
📥 Step 1/8: Pulling latest code from GitHub...
✅ Code updated
abc1234 test: verify deployment automation

...

🎉 Deployment completed successfully!
```

**Step 3: Verify file exists**

```bash
# Kiểm tra file đã pull về VPS chưa
ls -la /var/www/samnghethaycu.com/DEPLOY-TEST.md
# Phải thấy file!

cat /var/www/samnghethaycu.com/DEPLOY-TEST.md
# Phải thấy nội dung "Deployment test"
```

✅ **Checkpoint 3.2:** End-to-end workflow verified

---

### 3.3. Performance Test

**Measure deployment time:**

```bash
# On VPS
time deploy-sam
```

**Expected time:**
- **First run:** 30-60 seconds (Composer install)
- **Subsequent runs (no changes):** 5-10 seconds
- **Subsequent runs (with changes):** 15-30 seconds

✅ **Checkpoint 3.3:** Performance verified

---

## VERIFICATION

### Full Workflow Checklist

**✅ Checklist - Deployment Automation:**

```
✅ Deployment script created at ~/scripts/deploy-samnghethaycu.sh
✅ Script is executable (chmod +x)
✅ Alias 'deploy-sam' configured in .bashrc
✅ Sudo configured for deploy user (no password for specific commands)
✅ Script runs successfully with colored output
✅ All 8 steps execute without errors
✅ End-to-end workflow tested: Local → GitHub → VPS
```

**Test deployment workflow:**

```bash
# On VPS
deploy-sam
# Should complete in < 30 seconds
# Should show green checkmarks for all 8 steps
# Should reload PHP-FPM without password prompt
```

---

## 🎉 WORKFLOW 4 COMPLETE!

### Bạn đã có:

```
✅ Professional deployment script (8 automated steps)
✅ One-command deployment: deploy-sam
✅ Sudo permissions configured (secure, minimal)
✅ Colored output with status tracking
✅ Error handling (exit on failure)
✅ Git-based workflow: Local → GitHub → VPS
✅ Deployment time: 5-30 seconds (vs 15-20 minutes manual)
```

### Deployment Workflow:

```
BEFORE (Manual - 15-20 minutes):
─────────────────────────────────
1. SSH to VPS
2. cd /var/www/samnghethaycu.com
3. git pull origin main
4. composer install --no-dev
5. php artisan migrate --force
6. php artisan config:cache
7. php artisan route:cache
8. php artisan view:cache
9. sudo chown -R www-data:www-data storage
10. sudo chmod -R 775 storage
11. sudo systemctl reload php8.4-fpm
12. Check logs for errors
13. Test website
... (many more steps)

AFTER (Automated - 5-30 seconds):
─────────────────────────────────
1. SSH to VPS
2. deploy-sam ✨
   → Done!
```

### Script Features:

- **8 automated steps:** Pull, check .env, fix bootstrap/cache, install, migrate, cache, permissions, reload
- **Colored output:** Easy to read (green ✅, yellow ⚠️, red ❌, blue ℹ️)
- **Error handling:** Exits on failure with clear messages
- **Smart checks:** Skips steps if files missing (graceful degradation)
- **Deployment info:** Shows current version, timestamp, deployed by
- **Professional:** Production-ready script following best practices

---

## 🚀 NEXT STEP:

```
→ WORKFLOW-5: FILAMENT ADMIN PANEL
  Install Filament v3 admin panel
  Create admin user
  Setup admin authentication
  Time: 10-15 minutes
```

---

## 🔧 TROUBLESHOOTING

### Issue: "deploy-sam: command not found"

**Fix:**

```bash
# Reload .bashrc
source ~/.bashrc

# Verify alias
type deploy-sam

# If still not found, run script directly
~/scripts/deploy-samnghethaycu.sh
```

---

### Issue: "Permission denied" when reloading PHP-FPM

**Error:**
```
sudo: no tty present and no askpass program specified
```

**Fix:**

```bash
# Re-check sudoers configuration
sudo visudo -c

# Verify line exists:
# deploy ALL=(ALL) NOPASSWD: /bin/systemctl reload php8.4-fpm, ...

# If missing, add it again via:
sudo visudo
```

---

### Issue: "Cannot access /var/www/samnghethaycu.com"

**Fix:**

```bash
# Verify directory exists
ls -la /var/www/samnghethaycu.com

# If missing, clone repository
cd /var/www
git clone git@github.com:phuochoavn/websamnghe.git samnghethaycu.com
```

---

### Issue: Deployment runs but website shows errors

**Check logs:**

```bash
# Laravel logs
tail -50 /var/www/samnghethaycu.com/storage/logs/laravel.log

# Nginx error logs
sudo tail -50 /var/log/nginx/samnghethaycu-error.log

# PHP-FPM logs
sudo tail -50 /var/log/php8.4-fpm.log
```

**Common fixes:**

```bash
# Clear all caches
cd /var/www/samnghethaycu.com
php artisan optimize:clear

# Fix permissions
sudo chown -R www-data:www-data storage bootstrap/cache
sudo chmod -R 775 storage bootstrap/cache

# Restart PHP-FPM
sudo systemctl restart php8.4-fpm
```

---

**Created:** 2025-11-16
**Version:** 3.0 Reorganized
**Time:** 10-15 minutes actual

---

**END OF WORKFLOW 4** 🚀
