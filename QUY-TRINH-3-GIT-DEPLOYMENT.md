# 🔧 QUY TRÌNH 3: GIT DEPLOYMENT AUTOMATION (OPTIONAL)

> **Dự án:** samnghethaycu.com - E-Commerce Platform
> **Phiên bản:** 3.0
> **Thời gian:** ~60 phút
> **Mục tiêu:** Professional Git workflow with automated deployment

---

## 📋 MỤC LỤC

- [GIỚI THIỆU](#giới-thiệu)
- [BƯỚC 1: Tạo GitHub Repository (10 phút)](#bước-1-tạo-github-repository)
- [BƯỚC 2: Setup SSH Key trên VPS (15 phút)](#bước-2-setup-ssh-key-trên-vps)
- [BƯỚC 3: Setup Git on VPS (15 phút)](#bước-3-setup-git-on-vps)
- [BƯỚC 4: Tạo Deployment Script (15 phút)](#bước-4-tạo-deployment-script)
- [BƯỚC 5: Test Deployment Workflow (5 phút)](#bước-5-test-deployment-workflow)
- [TROUBLESHOOTING](#troubleshooting)

---

## 🎯 GIỚI THIỆU

### Quy trình này là OPTIONAL!

**Bạn có thể:**
- Làm sau khi hoàn thành Quy trình 1 & 2
- Làm bất kỳ lúc nào trong quá trình phát triển
- Bỏ qua nếu dùng manual deployment (WinSCP)

### Lợi ích của Git Deployment

**✅ Workflow hiện tại (Manual):**
```
Local Windows → Compress ZIP → WinSCP Upload → VPS Extract → Fix permissions
```
**Time:** ~10-15 phút mỗi lần deploy

**✅ Workflow mới (Git):**
```
Local → git push → VPS → deploy-sam (1 command)
```
**Time:** ~2-3 phút mỗi lần deploy

### Prerequisites

```
✅ Project đã chạy trên VPS (Quy trình 1 complete)
✅ Windows với Git installed
✅ GitHub account
✅ SSH access to VPS
```

### Sau khi hoàn thành

```
✅ GitHub repository (private)
✅ SSH authentication (no password)
✅ Deployment script: deploy-sam
✅ One-command deployment
✅ Version control cho toàn bộ code
```

---

# BƯỚC 1: TẠO GITHUB REPOSITORY

**Thời gian:** 10 phút

## 1.1. Tạo Repository

**Trên GitHub.com:**

1. Login → Click **"+"** (top right) → **"New repository"**
2. **Repository name:** `websamnghe`
3. **Description:** `samnghethaycu.com - E-Commerce Platform`
4. **Visibility:** **Private** ⚠️ (quan trọng!)
5. **KHÔNG** tick "Initialize this repository with:"
   - ❌ No README
   - ❌ No .gitignore
   - ❌ No license
6. Click **"Create repository"**

**Result:** GitHub sẽ show trang hướng dẫn push existing repository

✅ **Checkpoint 1.1:** Repository created

---

## 1.2. Chuẩn bị Local Code

**Windows PowerShell:**

```powershell
# Di chuyển vào thư mục Laravel local
cd C:\laravel-project\samnghethaycu

# Kiểm tra git status
git status
```

**Nếu chưa có git (new project):**

```powershell
# Initialize git
git init

# Add all files
git add .

# First commit
git commit -m "Initial commit: Laravel 12 base installation"
```

**Nếu đã có git:**

```powershell
# Chỉ cần verify
git log --oneline -5

# Should show existing commits
```

✅ **Checkpoint 1.2:** Git initialized locally

---

## 1.3. Push lên GitHub

**PowerShell:**

```powershell
# Thêm remote (replace with your username)
git remote add origin https://github.com/phuochoavn/websamnghe.git

# Verify remote
git remote -v
# Should show:
# origin  https://github.com/phuochoavn/websamnghe.git (fetch)
# origin  https://github.com/phuochoavn/websamnghe.git (push)

# Rename branch to main (if needed)
git branch -M main

# Push code lên GitHub
git push -u origin main
```

**Nhập credentials khi được hỏi:**

- **Username:** `phuochoavn`
- **Password:** **Personal Access Token** (NOT your GitHub password!)

**Tạo Personal Access Token:**

1. GitHub → Settings → Developer settings → Personal access tokens → Tokens (classic)
2. Generate new token (classic)
3. Note: "VPS Deployment"
4. Scopes: ✅ `repo` (full control)
5. Generate token
6. **Copy token** (chỉ show 1 lần!)
7. Paste vào password prompt

**Success output:**

```
Enumerating objects: 120, done.
Counting objects: 100% (120/120), done.
...
To https://github.com/phuochoavn/websamnghe.git
 * [new branch]      main -> main
Branch 'main' set up to track remote branch 'main' from 'origin'.
```

✅ **Checkpoint 1.3:** Code pushed to GitHub

---

# BƯỚC 2: SETUP SSH KEY TRÊN VPS

**Thời gian:** 15 phút

## 2.1. Generate SSH Key

**SSH vào VPS:**

```bash
ssh deploy@69.62.82.145
# Password: Deploy@2025
```

**Trên VPS:**

```bash
# Generate SSH key (ed25519 - modern & secure)
ssh-keygen -t ed25519 -C "deploy@samnghethaycu.com"

# Prompts:
# Enter file: (nhấn Enter - use default)
# Enter passphrase: (nhấn Enter - no passphrase)
# Enter same passphrase again: (nhấn Enter)

# Key generated!
```

**Hiển thị public key:**

```bash
cat ~/.ssh/id_ed25519.pub
```

**Output sẽ giống:**

```
ssh-ed25519 AAAAC3NzaC1lZDI1NTE5AAAAIJxK... deploy@samnghethaycu.com
```

**⚠️ COPY TOÀN BỘ** output này (từ `ssh-ed25519` đến cuối)

✅ **Checkpoint 2.1:** SSH key generated

---

## 2.2. Thêm SSH Key vào GitHub

**Trên GitHub.com:**

1. Click avatar (top right) → **Settings**
2. Sidebar → **SSH and GPG keys**
3. Click **"New SSH key"**
4. **Title:** `VPS - samnghethaycu.com`
5. **Key type:** Authentication Key
6. **Key:** Paste public key từ bước trên
7. Click **"Add SSH key"**
8. Confirm with password nếu được hỏi

✅ **Checkpoint 2.2:** SSH key added to GitHub

---

## 2.3. Test SSH Connection

**Trên VPS:**

```bash
# Test connection to GitHub
ssh -T git@github.com
```

**First time prompt:**

```
The authenticity of host 'github.com (...)' can't be established.
ED25519 key fingerprint is SHA256:+DiY...
Are you sure you want to continue connecting (yes/no/[fingerprint])?
```

**Type:** `yes` (Enter)

**Expected success output:**

```
Hi phuochoavn! You've successfully authenticated, but GitHub does not provide shell access.
```

**Nếu thấy message này:** ✅ SSH working!

✅ **Checkpoint 2.3:** SSH connection working

---

# BƯỚC 3: SETUP GIT ON VPS

**Thời gian:** 15 phút

## 3.1. Backup Current Code

**Trên VPS:**

```bash
cd /var/www

# Create backup with timestamp
sudo tar -czf ~/backup-before-git-$(date +%Y%m%d-%H%M%S).tar.gz samnghethaycu.com/

# Verify backup created
ls -lh ~/backup-*
```

**Example output:**

```
-rw-r--r-- 1 deploy deploy 45M Nov 16 10:30 backup-before-git-20251116-103045.tar.gz
```

✅ **Checkpoint 3.1:** Backup created

---

## 3.2. Initialize Git Repository

**Trên VPS:**

```bash
cd /var/www/samnghethaycu.com

# Initialize git
git init

# Configure git user
git config user.name "Deploy User"
git config user.email "deploy@samnghethaycu.com"

# Add remote (SSH format - important!)
git remote add origin git@github.com:phuochoavn/websamnghe.git

# Verify remote
git remote -v
```

**Should show:**

```
origin  git@github.com:phuochoavn/websamnghe.git (fetch)
origin  git@github.com:phuochoavn/websamnghe.git (push)
```

✅ **Checkpoint 3.2:** Git initialized on VPS

---

## 3.3. Create .gitignore

**Trên VPS:**

```bash
cd /var/www/samnghethaycu.com

# Create .gitignore
cat > .gitignore << 'EOF'
/node_modules
/public/hot
/public/storage
/storage/*.key
/vendor
.env
.env.backup
.phpunit.result.cache
Homestead.json
Homestead.yaml
npm-debug.log
yarn-error.log
/.idea
/.vscode
EOF

# Verify
cat .gitignore
```

✅ **Checkpoint 3.3:** .gitignore created

---

## 3.4. Pull Code from GitHub

**Trên VPS:**

```bash
cd /var/www/samnghethaycu.com

# Fetch all branches
git fetch origin

# Reset to main branch (overwrite local with remote)
git reset --hard origin/main

# Verify
git log --oneline -3
git status
```

**Expected output:**

```
HEAD is now at abc1234 Initial commit: Laravel 12 base installation
On branch main
Your branch is up to date with 'origin/main'.

nothing to commit, working tree clean
```

**Nếu có warning về .env:**

```bash
# .env is gitignored, so it's safe
# Just verify it still exists
ls -la .env
```

✅ **Checkpoint 3.4:** Code synced with GitHub

---

# BƯỚC 4: TẠO DEPLOYMENT SCRIPT

**Thời gian:** 15 phút

## 4.1. Create Deploy Script

**Trên VPS:**

```bash
# Create script directory
mkdir -p ~/scripts

# Create deployment script
cat > ~/scripts/deploy-samnghethaycu.sh << 'DEPLOY_SCRIPT'
#!/bin/bash

# ===============================================
# DEPLOYMENT SCRIPT - samnghethaycu.com
# ===============================================

set -e  # Exit on error

echo "🚀 Starting deployment..."
echo ""

# Configuration
APP_DIR="/var/www/samnghethaycu.com"
BRANCH="main"

# Navigate to app directory
cd $APP_DIR

echo "📂 Current directory: $(pwd)"
echo ""

# Step 1: Git Pull
echo "📥 Step 1/8: Pulling latest code from GitHub..."
git fetch origin $BRANCH
git reset --hard origin/$BRANCH
echo "✅ Code updated"
echo ""

# Step 2: Check .env exists
echo "🔍 Step 2/8: Checking .env file..."
if [ ! -f .env ]; then
    echo "❌ ERROR: .env file not found!"
    exit 1
fi
echo "✅ .env exists"
echo ""

# Step 3: Fix bootstrap/cache symlink issue
echo "🔧 Step 3/8: Checking bootstrap/cache..."
if [ -L bootstrap/cache ]; then
    echo "⚠️  Found symlink, removing..."
    sudo rm -f bootstrap/cache
    mkdir -p bootstrap/cache
fi
mkdir -p bootstrap/cache
echo "✅ bootstrap/cache is directory"
echo ""

# Step 4: Composer Install
echo "📦 Step 4/8: Installing Composer dependencies..."
composer install --no-dev --optimize-autoloader --no-interaction --quiet
echo "✅ Dependencies installed"
echo ""

# Step 5: Run Migrations
echo "🗄️  Step 5/8: Running database migrations..."
php artisan migrate --force --no-interaction
echo "✅ Migrations complete"
echo ""

# Step 6: Clear & Cache
echo "🧹 Step 6/8: Clearing caches..."
php artisan optimize:clear --quiet
php artisan config:cache --quiet
php artisan route:cache --quiet
php artisan view:cache --quiet
echo "✅ Caches rebuilt"
echo ""

# Step 7: Fix Permissions
echo "🔐 Step 7/8: Fixing permissions..."
sudo chown -R www-data:www-data storage bootstrap/cache
sudo chmod -R 775 storage bootstrap/cache
echo "✅ Permissions fixed"
echo ""

# Step 8: Reload PHP-FPM
echo "🔄 Step 8/8: Reloading PHP-FPM..."
sudo systemctl reload php8.4-fpm
echo "✅ PHP-FPM reloaded"
echo ""

echo "🎉 Deployment completed successfully!"
echo ""
echo "🌐 Website: https://samnghethaycu.com"
echo "🔧 Admin: https://samnghethaycu.com/admin"
echo ""

# Show current commit
echo "📌 Current version:"
git log -1 --oneline
echo ""

DEPLOY_SCRIPT

# Make executable
chmod +x ~/scripts/deploy-samnghethaycu.sh

echo "✅ Deployment script created!"
```

✅ **Checkpoint 4.1:** Deployment script created

---

## 4.2. Create Shortcut Alias

**Trên VPS:**

```bash
# Add alias to bashrc
echo "alias deploy-sam='~/scripts/deploy-samnghethaycu.sh'" >> ~/.bashrc

# Reload bashrc
source ~/.bashrc

# Test alias exists
type deploy-sam
```

**Should show:**

```
deploy-sam is aliased to `~/scripts/deploy-samnghethaycu.sh'
```

✅ **Checkpoint 4.2:** Alias created

---

## 4.3. View Script Content

**Verify script:**

```bash
cat ~/scripts/deploy-samnghethaycu.sh
```

**Should show the full 8-step deployment script**

✅ **Checkpoint 4.3:** Script ready

---

# BƯỚC 5: TEST DEPLOYMENT WORKFLOW

**Thời gian:** 5 phút

## 5.1. Make a Test Change on Local

**Windows - VS Code:**

1. Mở project
2. Create/edit `README.md`:

```markdown
# samnghethaycu.com

E-Commerce Platform for Natural Health Products

Test deployment: 2025-11-16
```

3. Save file

**PowerShell:**

```powershell
cd C:\laravel-project\samnghethaycu

git add README.md
git commit -m "test: deployment workflow verification"
git push origin main
```

**Wait for push to complete (~5-10 seconds)**

✅ **Checkpoint 5.1:** Test commit pushed

---

## 5.2. Deploy on VPS

**Trên VPS:**

```bash
# Run deployment script
deploy-sam
```

**Expected output:**

```
🚀 Starting deployment...

📂 Current directory: /var/www/samnghethaycu.com

📥 Step 1/8: Pulling latest code from GitHub...
✅ Code updated

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
abc1234 test: deployment workflow verification
```

✅ **Checkpoint 5.2:** Deployment successful

---

## 5.3. Verify Changes

**Trên VPS:**

```bash
cd /var/www/samnghethaycu.com

# Check latest commit
git log -1 --oneline
# Should show your test commit

# Verify README.md updated
cat README.md
# Should show new content
```

**Browser:**

```
https://samnghethaycu.com
https://samnghethaycu.com/admin
```

Both should still work perfectly!

✅ **Checkpoint 5.3:** Deployment verified

---

## 5.4. Clean Up Test

**Optional - remove test:**

```bash
cd /var/www/samnghethaycu.com

rm README.md
git add -A
git commit -m "chore: remove test file"

# Push from VPS (or from local)
git push origin main
```

✅ **Checkpoint 5.4:** Test cleanup

---

# ✅ QUY TRÌNH 3 HOÀN THÀNH!

**Đã có:**

```
✅ GitHub repository: phuochoavn/websamnghe (private)
✅ SSH authentication working (no password needed)
✅ Git on VPS synced with GitHub
✅ Deployment script: ~/scripts/deploy-samnghethaycu.sh
✅ Alias: deploy-sam
✅ Workflow tested: Local → GitHub → VPS
```

---

# 🚀 SỬ DỤNG HÀNG NGÀY

## Workflow mới:

**1. Local - Develop:**

```powershell
# Work on features
code .

# Edit files...
# Test locally...

# Commit changes
git add .
git commit -m "feat: add product filters"
git push origin main
```

**2. VPS - Deploy:**

```bash
ssh deploy@69.62.82.145
deploy-sam
```

**Done!** Website updated trong 2-3 phút.

---

## Deployment Script Explained

**8 Steps:**

1. **Git Pull**: Fetch latest code from GitHub
2. **Check .env**: Ensure environment file exists
3. **Fix bootstrap/cache**: Remove symlink if exists
4. **Composer Install**: Update PHP dependencies
5. **Migrations**: Run new database migrations
6. **Cache**: Rebuild all Laravel caches
7. **Permissions**: Fix storage/bootstrap permissions
8. **PHP-FPM**: Reload PHP service

**Safety features:**

- ✅ `set -e`: Exit on any error
- ✅ Check .env exists before proceeding
- ✅ Fix bootstrap/cache symlink issue
- ✅ Show current version after deploy

---

# 🔧 TROUBLESHOOTING

## Issue 1: Git Push Failed (Authentication)

**Error:**

```
remote: Support for password authentication was removed...
fatal: Authentication failed
```

**Solution:**

Must use **Personal Access Token**, NOT password!

1. Create token: GitHub → Settings → Developer settings → Personal access tokens
2. Use token as password when pushing

---

## Issue 2: SSH Connection Failed

**Error:**

```
ssh: connect to host github.com port 22: Connection refused
```

**Solution:**

```bash
# Test connection
ssh -T git@github.com

# Regenerate key if needed
ssh-keygen -t ed25519 -C "deploy@samnghethaycu.com"
cat ~/.ssh/id_ed25519.pub
# Re-add to GitHub
```

---

## Issue 3: Permission Denied on Deploy

**Error:**

```
error: cannot open .git/FETCH_HEAD: Permission denied
```

**Solution:**

```bash
cd /var/www/samnghethaycu.com

# Fix ownership
sudo chown -R deploy:www-data .
sudo chown -R www-data:www-data storage bootstrap/cache
```

---

## Issue 4: bootstrap/cache Symlink

**Error:**

```
ErrorException: file_put_contents(bootstrap/cache/...): Failed to open stream
```

**Solution:**

Already handled in deployment script (Step 3)!

Manual fix:

```bash
cd /var/www/samnghethaycu.com

if [ -L bootstrap/cache ]; then
    sudo rm -f bootstrap/cache
    mkdir -p bootstrap/cache
fi

sudo chown www-data:www-data bootstrap/cache
sudo chmod 775 bootstrap/cache
```

---

## Issue 5: .env Missing After Deploy

**Error:**

```
❌ ERROR: .env file not found!
```

**Explanation:** `.env` is in `.gitignore` (correct!)

**Solution:**

```bash
cd /var/www/samnghethaycu.com

# Restore .env from backup
cp ~/backup-before-git-*/samnghethaycu.com/.env .

# Or recreate manually
nano .env
# Paste .env content
```

---

# 📊 SO SÁNH WORKFLOWS

## Before Git (Manual):

```
1. Code thay đổi trên Windows
2. Compress toàn bộ project → ZIP (~5-10 phút)
3. Open WinSCP
4. Upload ZIP to VPS (~5-15 phút depending on size)
5. SSH to VPS
6. Extract ZIP
7. Fix permissions
8. Clear cache
9. Reload PHP-FPM

Total: 15-30 phút
```

## After Git (Automated):

```
1. Code thay đổi trên Windows
2. git add . && git commit -m "..." && git push (~30 giây)
3. SSH to VPS
4. deploy-sam (~2-3 phút)

Total: 3-5 phút
```

**Speed up:** 5-6x faster!

---

# 🎉 KẾT LUẬN

**Bạn đã có:**

✅ **Version Control** - Track all code changes
✅ **Automated Deployment** - One command deploy
✅ **Backup History** - Git history = backup
✅ **Team Collaboration** - Multiple developers can work
✅ **Rollback** - Revert to any previous version

**Next steps:**

- Continue với Frontend Development
- Setup CI/CD (GitHub Actions) - advanced
- Add staging environment - advanced

---

**End of Quy Trình 3** 🔧

**Tạo bởi:** Claude AI
**Ngày:** 2025-11-16
**Version:** 3.0 OPTIONAL
