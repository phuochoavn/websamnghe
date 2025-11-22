# 🚀 WORKFLOW 4: TỰ ĐỘNG HÓA TRIỂN KHAI

> **Dự án:** samnghethaycu.com - E-Commerce Platform
> **Version:** 4.0 Professional Vietnamese (Standardized Edition)
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

**📍 Trên VPS - Verify trước khi bắt đầu:**

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

## PHẦN 1: TẠO DEPLOYMENT SCRIPT

**Thời gian:** 7 phút

### BƯỚC 1.1: Tạo Deployment Script

**⚠️ IMPORTANT:** Đảm bảo đã SSH vào VPS với user `deploy`

**📍 Trên VPS (as deploy user):**

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

# Xem nội dung script (20 dòng đầu)
head -20 ~/scripts/deploy-samnghethaycu.sh
# Phải thấy: #!/bin/bash và các dòng comment
```

✅ **Checkpoint 1.1:** Deployment script created

---

### BƯỚC 1.2: Tạo Deployment Alias

**📍 Trên VPS (as deploy user):**

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
# Verify alias exists
alias | grep deploy-sam
# Phải thấy: alias deploy-sam='~/scripts/deploy-samnghethaycu.sh'
```

✅ **Checkpoint 1.2:** Deployment alias created

---

## PHẦN 2: CẤU HÌNH SUDO PERMISSIONS

**Thời gian:** 3 phút

### BƯỚC 2.1: Cấu Hình Passwordless Sudo

**Tại sao cần?** Deploy script cần sudo để:
- Reload PHP-FPM (`systemctl reload php8.4-fpm`)
- Fix permissions (`chown`, `chmod`)
- Remove symlinks (`rm -f`)

**📍 Trên VPS (as deploy user):**

```bash
# Mở sudoers file (secure editor)
sudo visudo
```

**⚠️ IMPORTANT:** Lệnh trên sẽ mở nano/vim editor, KHÔNG phải bash!

**Inside editor (nano hoặc vim):**

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

### BƯỚC 2.2: Test Sudo Permissions

**📍 Trên VPS (as deploy user):**

```bash
# Test reload PHP-FPM (không cần password)
sudo systemctl status php8.4-fpm
# Phải hiển thị status KHÔNG hỏi password

# Test chown (should not ask password)
sudo chown deploy:deploy /tmp/test-file 2>/dev/null || echo "✅ Command allowed"

# Test chmod (should not ask password)
sudo chmod 755 /tmp/test-file 2>/dev/null || echo "✅ Command allowed"

# Test rm (should not ask password)
sudo rm -f /tmp/test-file 2>/dev/null || echo "✅ Command allowed"
```

**⚠️ Nếu hỏi password → Sudoers configuration SAI, cần fix lại BƯỚC 2.1**

✅ **Checkpoint 2.2:** Sudo permissions tested

---

## PHẦN 3: TEST DEPLOYMENT

**Thời gian:** 5 phút

### BƯỚC 3.1: Test Deployment Script (First Run)

**📍 Trên VPS (as deploy user):**

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
f63c59e (HEAD -> main, origin/main) feat: add health check endpoint

📅 Deployed at: 2025-11-21 01:00:00
👤 Deployed by: deploy
```

**⚠️ Nếu thấy errors:**

- **"Cannot access /var/www/samnghethaycu.com"** → Laravel chưa cài (WF-2)
- **".env file not found"** → Tạo .env từ .env.example
- **"composer: command not found"** → Composer chưa cài (WF-1)
- **"php: command not found"** → PHP chưa cài (WF-1)
- **"sudo: a password is required"** → Sudo config chưa đúng (BƯỚC 2.1)

✅ **Checkpoint 3.1:** Deployment script tested successfully

---

### BƯỚC 3.2: Test End-to-End Workflow

**Complete workflow: Local → GitHub → VPS**

**📍 Trên Windows - Bước 1: Tạo thay đổi trên local**

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

**📍 Trên VPS - Bước 2: Deploy lên VPS**

```bash
# On VPS (as deploy user)
cd /var/www/samnghethaycu.com
deploy-sam
```

**Expected output:**

```
📥 Step 1/8: Pulling latest code from GitHub...
✅ Code updated
9d7b8a2 test: verify deployment automation

...

🎉 Deployment completed successfully!
```

**📍 Trên VPS - Bước 3: Verify file đã sync**

```bash
# Kiểm tra file đã pull về VPS chưa
ls -la /var/www/samnghethaycu.com/DEPLOY-TEST.md
# Phải thấy file!

cat /var/www/samnghethaycu.com/DEPLOY-TEST.md
# Phải thấy nội dung "Deployment test"
```

**📍 Trên VPS - Bước 4: Cleanup test file**

```bash
# Xóa test file
rm /var/www/samnghethaycu.com/DEPLOY-TEST.md
```

**📍 Trên Windows - Bước 5: Cleanup trên local**

```powershell
# Windows PowerShell
cd C:\Projects\samnghethaycu
git rm DEPLOY-TEST.md
git commit -m "chore: remove deployment test file"
git push origin main
```

✅ **Checkpoint 3.2:** End-to-end workflow verified

---

### BƯỚC 3.3: Performance Test

**Measure deployment time:**

**📍 Trên VPS:**

```bash
# Test với time command
time deploy-sam
```

**Expected time:**
- **First run:** 30-60 seconds (Composer install đầy đủ)
- **Subsequent runs (no changes):** 5-10 seconds
- **Subsequent runs (with changes):** 15-30 seconds

✅ **Checkpoint 3.3:** Performance verified

---

## ✅ VERIFICATION - HOÀN THÀNH WORKFLOW 4

### Full Workflow Checklist

**✅ Checklist - Deployment Automation:**

```
✅ Deployment script created at ~/scripts/deploy-samnghethaycu.sh
✅ Script is executable (chmod +x)
✅ Alias 'deploy-sam' configured in .bashrc
✅ Sudo configured for deploy user (NOPASSWD for specific commands)
✅ Script runs successfully with colored output
✅ All 8 steps execute without errors
✅ End-to-end workflow tested: Local → GitHub → VPS
✅ Test file synced successfully
✅ Performance < 30 seconds
```

**Final test deployment workflow:**

**📍 Trên VPS:**

```bash
# Test deployment
deploy-sam
# Should complete in < 30 seconds
# Should show green checkmarks ✅ for all 8 steps
# Should reload PHP-FPM without password prompt
```

**Expected successful output:**

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
```

---

## 🎉 WORKFLOW 4 COMPLETE!

### Bạn đã có:

```
✅ Professional deployment script (8 automated steps)
✅ One-command deployment: deploy-sam
✅ Sudo permissions configured (secure, minimal privileges)
✅ Colored output with status tracking
✅ Error handling (exit on failure)
✅ Git-based workflow: Local → GitHub → VPS
✅ Deployment time: 5-30 seconds (vs 15-20 minutes manual)
✅ Production-ready automation
```

### Deployment Workflow Comparison:

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
... (many more manual steps)

AFTER (Automated - 5-30 seconds):
─────────────────────────────────
1. SSH to VPS
2. deploy-sam ✨
   → Done! All 8 steps automated!
```

### Script Features:

- **8 automated steps:** Pull, check .env, fix bootstrap/cache, install, migrate, cache, permissions, reload
- **Colored output:** Easy to read (🟢 green ✅, 🟡 yellow ⚠️, 🔴 red ❌, 🔵 blue ℹ️)
- **Error handling:** Exits immediately on failure with clear error messages
- **Smart checks:** Skips steps gracefully if files missing
- **Deployment info:** Shows current version, timestamp, deployed by user
- **Professional:** Production-ready script following DevOps best practices

---

## 🚀 NEXT STEP:

```
✅ WORKFLOW-1: VPS Infrastructure (LEMP + SSL)
✅ WORKFLOW-2: Laravel Installation (Health check working)
✅ WORKFLOW-3: Git Workflow Setup (Passwordless SSH)
✅ WORKFLOW-4: Deployment Automation (One-command deployment)
→ WORKFLOW-5: FILAMENT ADMIN PANEL
  Install Filament v4 admin panel
  Create admin user
  Setup admin authentication
  Time: 10-15 minutes
```

---

## 🔄 ROLLBACK: XÓA DEPLOYMENT AUTOMATION VỀ WORKFLOW-3

**Nếu muốn xóa sạch Deployment Automation và quay về trạng thái WORKFLOW-3:**

### **📍 Trên VPS (as deploy user):**

```bash
# BƯỚC 1: Xóa deployment script
rm -f ~/scripts/deploy-samnghethaycu.sh
rmdir ~/scripts 2>/dev/null  # Xóa thư mục nếu rỗng

# Verify
ls -la ~/scripts
# Phải thấy: No such file or directory

# BƯỚC 2: Xóa alias khỏi .bashrc
sed -i '/alias deploy-sam=/d' ~/.bashrc

# Reload .bashrc
source ~/.bashrc

# Verify alias đã bị xóa
type deploy-sam 2>&1
# Phải thấy: bash: type: deploy-sam: not found

# BƯỚC 3: Xóa sudo configuration cho deploy user
sudo visudo
# Trong editor, TÌM VÀ XÓA dòng:
# deploy ALL=(ALL) NOPASSWD: /bin/systemctl reload php8.4-fpm, /bin/chown, /bin/chmod, /bin/rm
# Save: Ctrl+O, Enter, Ctrl+X (nano) hoặc ESC :wq (vim)

# Verify sudoers syntax
sudo visudo -c
# Phải thấy: parsed OK

# BƯỚC 4: Test sudo permissions (phải hỏi password)
sudo systemctl status php8.4-fpm
# Phải hỏi password (không còn NOPASSWD)

# BƯỚC 5: Xóa test files nếu có
cd /var/www/samnghethaycu.com
rm -f DEPLOY-TEST.md
git status
# Nếu có uncommitted changes, reset:
git reset --hard origin/main
```

✅ **Rollback complete! Bạn đã về trạng thái WORKFLOW-3:**
- ✅ Deployment script đã xóa
- ✅ Alias deploy-sam đã xóa
- ✅ Sudo NOPASSWD đã xóa
- ✅ VPS vẫn có Git workflow (Local → GitHub → VPS)
- ✅ Laravel app vẫn chạy bình thường

**Bây giờ bạn có thể làm lại WORKFLOW-4 từ đầu.**

---

## 🔧 TROUBLESHOOTING

### Issue 1: "deploy-sam: command not found"

**Error:**
```bash
deploy-sam
bash: deploy-sam: command not found
```

**Fix:**

```bash
# Reload .bashrc
source ~/.bashrc

# Verify alias
type deploy-sam
# Should show: deploy-sam is aliased to '~/scripts/deploy-samnghethaycu.sh'

# If still not found, run script directly
~/scripts/deploy-samnghethaycu.sh

# Or add alias manually
echo "alias deploy-sam='~/scripts/deploy-samnghethaycu.sh'" >> ~/.bashrc
source ~/.bashrc
```

---

### Issue 2: "Permission denied" when reloading PHP-FPM

**Error:**
```
sudo: no tty present and no askpass program specified
```

**Cause:** Sudoers configuration chưa đúng

**Fix:**

```bash
# Re-check sudoers configuration
sudo visudo -c
# Should show: parsed OK

# Verify line exists in sudoers
sudo grep "deploy.*NOPASSWD" /etc/sudoers /etc/sudoers.d/*

# If missing, add it again via:
sudo visudo
# Add this line at the end:
# deploy ALL=(ALL) NOPASSWD: /bin/systemctl reload php8.4-fpm, /bin/chown, /bin/chmod, /bin/rm
```

---

### Issue 3: "Cannot access /var/www/samnghethaycu.com"

**Error:**
```
❌ ERROR: Cannot access /var/www/samnghethaycu.com
```

**Cause:** Laravel application chưa cài hoặc directory không tồn tại

**Fix:**

```bash
# Verify directory exists
ls -la /var/www/samnghethaycu.com

# If missing, go back to WORKFLOW-2
# Or clone repository again:
cd /var/www
sudo git clone git@github.com:phuochoavn/websamnghe.git samnghethaycu.com
sudo chown -R deploy:www-data samnghethaycu.com
```

---

### Issue 4: Deployment runs but website shows errors

**Check logs:**

```bash
# Laravel application logs
tail -50 /var/www/samnghethaycu.com/storage/logs/laravel.log

# Nginx error logs
sudo tail -50 /var/log/nginx/samnghethaycu-error.log

# PHP-FPM logs
sudo tail -50 /var/log/php8.4-fpm.log
```

**Common fixes:**

```bash
# Clear all caches manually
cd /var/www/samnghethaycu.com
php artisan optimize:clear

# Fix permissions manually
sudo chown -R www-data:www-data storage bootstrap/cache
sudo chmod -R 775 storage bootstrap/cache

# Restart PHP-FPM (not just reload)
sudo systemctl restart php8.4-fpm

# Verify .env configuration
cat .env | grep -E "^(APP_|DB_|CACHE_|SESSION_)"
```

---

### Issue 5: Git pull fails with "Permission denied"

**Error:**
```
Permission denied (publickey).
fatal: Could not read from remote repository.
```

**Cause:** SSH key chưa được add vào GitHub

**Fix:**

```bash
# Test SSH connection
ssh -T git@github.com
# Should show: Hi username! You've successfully authenticated

# If fails, go back to WORKFLOW-3 and setup SSH key again
# Or verify SSH key exists:
cat ~/.ssh/id_ed25519.pub
# Copy key and add to GitHub SSH keys
```

---

### Issue 6: Composer install errors

**Error:**
```
Your requirements could not be resolved to an installable set of packages.
```

**Fix:**

```bash
# Clear Composer cache
composer clear-cache

# Remove vendor and reinstall
cd /var/www/samnghethaycu.com
rm -rf vendor/
composer install --no-dev --optimize-autoloader

# Verify PHP version matches composer.json
php -v
# Should be PHP 8.4.x
```

---

### Issue 7: Database migration errors

**Error:**
```
SQLSTATE[HY000] [1045] Access denied for user
```

**Cause:** Database credentials in .env sai

**Fix:**

```bash
# Verify database credentials
cat ~/credentials/database.txt

# Update .env with correct credentials
nano /var/www/samnghethaycu.com/.env
# Update:
# DB_DATABASE=samnghethaycu
# DB_USERNAME=samnghethaycu_user
# DB_PASSWORD=<password from credentials.txt>

# Clear config cache
php artisan config:clear
php artisan config:cache

# Test database connection
php artisan tinker
>>> DB::connection()->getPdo();
# Should not throw error
```

---

**Created:** 2025-11-21
**Version:** 4.0 Professional Vietnamese (Standardized Edition)
**Time:** 10-15 minutes actual
**Format:** Standardized with WORKFLOW-2 v6.0 and WORKFLOW-3 v4.0

---

**END OF WORKFLOW 4** 🚀
