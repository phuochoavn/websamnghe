# 🔄 WORKFLOW 3: GIT WORKFLOW SETUP

> **Dự án:** samnghethaycu.com - E-Commerce Platform
> **Version:** 3.0 Reorganized
> **Thời gian thực tế:** 15-20 phút
> **Mục tiêu:** Setup Git version control cho Laravel app

---

## 📖 WORKFLOW NÀY LÀM GÌ?

### 🎯 Mục đích:

**Thiết lập Git version control cho Laravel application đã cài đặt.**

Sau khi server và Laravel đã sẵn sàng (WF-1, WF-2), bây giờ setup Git để:
- Version control code
- Collaboration qua GitHub
- Chuẩn bị cho deployment automation (WF-4)

### 🎁 Kết quả sau workflow:

✅ **Git setup trên 3 nơi:**
- Local (Windows): C:\Projects\samnghethaycu
- GitHub: Private repository
- VPS: /var/www/samnghethaycu.com

✅ **SSH authentication:**
- GitHub SSH key hoặc Personal Access Token
- VPS SSH key cho deploy user

✅ **Professional workflow:**
```
Local → git push → GitHub → Ready for deployment
```

### ⚠️ PREREQUISITES:

**PHẢI hoàn thành trước:**
```
✅ WORKFLOW-1: VPS Infrastructure (PHP, MySQL, Nginx, SSL)
✅ WORKFLOW-2: Laravel Installation (Laravel app đang chạy)
✅ Laravel app accessible tại: https://samnghethaycu.com
```

**Verify Laravel working:**
```bash
curl https://samnghethaycu.com
# Phải thấy Laravel homepage
```

---

## PART 1: LOCAL GIT SETUP

**Time:** 5 phút

### 1.1. Configure Git Identity

**Windows PowerShell:**

```powershell
# Đặt tên của bạn
git config --global user.name "Hoa Nguyen"

# Đặt email - QUAN TRỌNG: Dùng GitHub noreply email!
git config --global user.email "201552537+phuochoavn@users.noreply.github.com"

# Kiểm tra config
git config --global --list
```

**Should show:**
```
user.name=Hoa Nguyen
user.email=201552537+phuochoavn@users.noreply.github.com
```

✅ **Checkpoint 1.1:** Git identity configured

---

### 1.2. Navigate to Laravel Project

```powershell
# Di chuyển vào thư mục Laravel đã cài (từ WF-2)
cd C:\Projects\samnghethaycu

# Kiểm tra Laravel files có sẵn
ls

# Phải thấy:
# - artisan
# - composer.json
# - app/
# - public/
```

✅ **Checkpoint 1.2:** In Laravel directory

---

### 1.3. Initialize Git Repository

```powershell
# Khởi tạo Git
git init

# Kiểm tra
git status
# Phải thấy: "On branch main" hoặc "On branch master"
```

✅ **Checkpoint 1.3:** Git initialized

---

### 1.4. Create .gitignore

**Laravel đã có .gitignore mặc định, nhưng verify:**

```powershell
# Kiểm tra file .gitignore
cat .gitignore

# Phải có các dòng quan trọng:
# /vendor
# .env
# /node_modules
# /storage/*.key
```

**Nếu chưa có, tạo:**

```powershell
@"
/node_modules
/public/hot
/public/storage
/storage/*.key
/vendor
.env
.env.backup
.env.production
.phpunit.result.cache
Homestead.json
Homestead.yaml
auth.json
npm-debug.log
yarn-error.log
/.fleet
/.idea
/.vscode
"@ | Out-File -FilePath .gitignore -Encoding utf8
```

✅ **Checkpoint 1.4:** .gitignore ready

---

### 1.5. Initial Commit

```powershell
# Add tất cả files (trừ những file trong .gitignore)
git add .

# Kiểm tra
git status

# Tạo commit đầu tiên
git commit -m "feat: initial Laravel 12 setup with Filament"

# Verify
git log --oneline
# Phải thấy 1 commit
```

✅ **Checkpoint 1.5:** Initial commit created

---

## PART 2: GITHUB REPOSITORY

**Time:** 8 phút

### 2.1. Create GitHub Repository

**On GitHub.com:**

1. Login → Click **"+"** → **"New repository"**
2. **Repository name:** `websamnghe`
3. **Description:** `samnghethaycu.com - E-Commerce Platform`
4. **Visibility:** ⚠️ **Private**
5. ❌ **DO NOT** initialize with README, .gitignore, or license
6. Click **"Create repository"**

✅ **Checkpoint 2.1:** Repository created

---

### 2.2. Create Personal Access Token

**Why?** GitHub no longer accepts password authentication.

**Steps:**

1. GitHub → **Settings** → **Developer settings** → **Personal access tokens** → **Tokens (classic)**
2. Click **"Generate new token (classic)"**
3. **Note:** `samnghethaycu deployment`
4. **Expiration:** 90 days
5. **Scopes:** Check `repo` (full control)
6. Click **"Generate token"**
7. **COPY TOKEN IMMEDIATELY** (shows once only!)

**Save token:**

```powershell
# Lưu token vào file (để dùng lại)
"ghp_YourTokenHere" | Out-File -FilePath C:\Projects\github-token.txt
```

✅ **Checkpoint 2.2:** Token created & saved

---

### 2.3. Add Remote and Push

**Windows PowerShell:**

```powershell
# QUAN TRỌNG: Đảm bảo đang ở thư mục project
cd C:\Projects\samnghethaycu

# Thêm GitHub remote
git remote add origin https://github.com/phuochoavn/websamnghe.git

# Kiểm tra
git remote -v
# Phải thấy origin với URL GitHub

# Đổi branch thành main (nếu cần)
git branch -M main

# Push lên GitHub
git push -u origin main
```

**Authentication prompt:**
```
Username: phuochoavn
Password: [PASTE TOKEN - not your GitHub password!]
```

**Success:**
```
Branch 'main' set up to track remote branch 'main' from 'origin'.
```

**Verify on GitHub:** Refresh repository → Should see Laravel files

✅ **Checkpoint 2.3:** Code pushed to GitHub

---

## PART 3: VPS GIT SETUP

**Time:** 7 phút

**⚠️ IMPORTANT:** SSH vào VPS với user `deploy` (đã tạo ở WF-1)

### 3.1. SSH to VPS

**Windows PowerShell:**

```powershell
# SSH vào VPS với user deploy
ssh deploy@69.62.82.145
# Password: Deploy@2025
```

---

### 3.2. Generate SSH Key for GitHub

**On VPS (sau khi SSH vào):**

```bash
# Tạo SSH key cho deploy user
ssh-keygen -t ed25519 -C "deploy@samnghethaycu.com"

# Press Enter 3 times (no passphrase)

# Hiển thị public key
cat ~/.ssh/id_ed25519.pub
```

**Copy public key** (bắt đầu từ `ssh-ed25519...`)

---

### 3.3. Add SSH Key to GitHub

**On GitHub.com:**

1. **Settings** → **SSH and GPG keys** → **New SSH key**
2. **Title:** `VPS Deploy User - samnghethaycu`
3. **Key:** Paste public key
4. Click **"Add SSH key"**

---

### 3.4. Test SSH Connection

**On VPS:**

```bash
# Test GitHub SSH
ssh -T git@github.com

# Expected:
# Hi phuochoavn! You've successfully authenticated...
```

✅ **Checkpoint 3.4:** GitHub SSH working

---

### 3.5. Clone Repository to VPS

```bash
# Configure Git identity (trên VPS)
git config --global user.name "Deploy User"
git config --global user.email "deploy@samnghethaycu.com"

# Di chuyển vào /var/www
cd /var/www

# Clone repository (thay thế folder Laravel hiện tại)
# Backup trước nếu cần
sudo mv samnghethaycu.com samnghethaycu.com.backup

# Clone từ GitHub
git clone git@github.com:phuochoavn/websamnghe.git samnghethaycu.com

# Verify
cd samnghethaycu.com
ls -la

# Phải thấy Laravel files
```

✅ **Checkpoint 3.5:** Repository cloned to VPS

---

### 3.6. Setup Laravel on VPS

```bash
cd /var/www/samnghethaycu.com

# Copy .env từ backup (nếu có) hoặc tạo mới
sudo cp ../samnghethaycu.com.backup/.env .env
# HOẶC
cp .env.example .env

# Generate app key
php artisan key:generate

# Install Composer dependencies
composer install --no-dev --optimize-autoloader

# Create storage link
php artisan storage:link

# Run migrations
php artisan migrate --force

# Fix permissions
sudo chown -R www-data:www-data storage bootstrap/cache
sudo chmod -R 775 storage bootstrap/cache

# Test
curl http://localhost
# Phải thấy Laravel response
```

✅ **Checkpoint 3.6:** Laravel working via Git

---

## VERIFICATION

### Test Full Workflow

**Windows PowerShell:**

```powershell
cd C:\Projects\samnghethaycu

# Tạo test file
echo "# Test deployment" >> TEST.md

# Add, commit, push
git add TEST.md
git commit -m "test: verify Git workflow"
git push origin main
```

**On VPS:**

```bash
cd /var/www/samnghethaycu.com

# Pull changes
git pull origin main

# Verify
ls -la TEST.md
# File phải có!
```

**Success!** Git workflow hoàn chỉnh!

✅ **Checkpoint:** Full workflow tested

---

## 🎉 WORKFLOW 3 COMPLETE!

### Bạn đã có:

```
✅ Git setup local (Windows)
✅ GitHub repository (private)
✅ VPS cloned từ GitHub
✅ SSH authentication working
✅ Git workflow: Local → GitHub → VPS
✅ Laravel app synced giữa local và VPS
```

### Git Workflow Diagram:

```
LOCAL (Windows)          GITHUB              VPS (Production)
─────────────────        ──────              ────────────────
C:\Projects\...          Repository          /var/www/...

git push origin main →   Updated    →        git pull origin main
                                              → Site updated!
```

---

## 🚀 NEXT STEP:

```
→ WORKFLOW-4: DEPLOYMENT AUTOMATION
  Tạo script tự động deploy (pull, install, migrate, cache)
  Thay vì 10+ lệnh → Chỉ còn: deploy-sam
```

---

## 🔧 TROUBLESHOOTING

### Issue: Permission denied (publickey)

**Error on git push:**
```
Permission denied (publickey).
fatal: Could not read from remote repository.
```

**Fix:**
```powershell
# Use HTTPS instead of SSH
git remote set-url origin https://github.com/phuochoavn/websamnghe.git

# Push với Personal Access Token
git push origin main
```

---

### Issue: .env missing on VPS

**Error:**
```
RuntimeException: No application encryption key has been specified.
```

**Fix:**
```bash
cd /var/www/samnghethaycu.com

# Copy from backup OR create new
cp .env.example .env

# Edit .env (database credentials, etc.)
nano .env

# Generate key
php artisan key:generate
```

---

**Created:** 2025-11-16
**Version:** 3.0 Reorganized
**Time:** 15-20 minutes actual

---

**END OF WORKFLOW 3** 🔄
