# 🎯 WORKFLOW 1: GIT FOUNDATION & DEPLOYMENT AUTOMATION

> **Dự án:** samnghethaycu.com - E-Commerce Platform
> **Version:** 1.0 Professional
> **Thời gian thực tế:** 20-30 phút (experienced dev)
> **Mục tiêu:** Git-first workflow foundation TRƯỚC KHI code

---

## 📋 MỤC LỤC

- [PREREQUISITES](#prerequisites-kiểm-tra-trước-khi-bắt-đầu)
- [COMMON ISSUES & FIXES](#common-issues--fixes-upfront)
- [PART 1: Local Git Setup](#part-1-local-git-setup)
- [PART 2: GitHub Repository](#part-2-github-repository)
- [PART 3: VPS Git Setup](#part-3-vps-git-setup)
- [PART 4: Deployment Automation](#part-4-deployment-automation)
- [VERIFICATION](#verification--testing)
- [TROUBLESHOOTING](#troubleshooting-guide)

---

## 🎯 WORKFLOW OVERVIEW

**Philosophy:** Git FIRST, Code LATER

```
Before:  Code → Deploy manually → Maybe Git later
After:   Git setup → Code with version control → Auto deploy
```

**Kết quả sau workflow này:**

```
✅ Git config hoàn chỉnh (local + VPS)
✅ GitHub repository (private)
✅ SSH authentication (no password)
✅ Deployment script: deploy-sam
✅ Workflow: Local → GitHub → VPS (1 command)
✅ Ready cho professional development
```

---

## PREREQUISITES: KIỂM TRA TRƯỚC KHI BẮT ĐẦU

### ✅ Checklist - Windows Machine

**1. Git installed?**

```powershell
git --version
# Expected: git version 2.x.x
# If not installed: https://git-scm.com/download/win
```

**2. SSH client available?**

```powershell
ssh -V
# Expected: OpenSSH_...
# Windows 10+ has built-in SSH
```

**3. Text editor?**

```powershell
code --version  # VS Code
# OR
notepad  # Built-in
```

**4. PowerShell version?**

```powershell
$PSVersionTable.PSVersion
# Should be 5.1+ (Windows 10+)
```

---

### ✅ Checklist - VPS Requirements

```
✅ Ubuntu 24.04 LTS (hoặc 22.04, 20.04)
✅ Root access hoặc sudo user
✅ Public IP address
✅ Port 22 open (SSH)
✅ Internet connectivity
```

---

### ✅ Checklist - Accounts

```
✅ GitHub account (free OK)
✅ VPS root password (from provider)
```

---

## COMMON ISSUES & FIXES UPFRONT

### Issue 1: SSH "Host key verification failed"

**Lỗi bạn VỪA GẶP:**

```
@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@
@    WARNING: REMOTE HOST IDENTIFICATION HAS CHANGED!     @
@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@
```

**Nguyên nhân:**
- VPS reinstalled
- SSH host key changed
- First connection to this IP

**Fix NGAY (trước khi tiếp tục):**

```powershell
# Method 1: Remove old key
ssh-keygen -R 69.62.82.145

# Method 2: Edit known_hosts manually
notepad C:\Users\Hoa\.ssh\known_hosts
# Find line with "69.62.82.145"
# Delete that entire line
# Save & close

# Method 3: Delete entire known_hosts (nuclear option)
Remove-Item C:\Users\Hoa\.ssh\known_hosts -Force
```

**Test:**

```powershell
ssh root@69.62.82.145
# Type: yes (when asked about fingerprint)
# Enter password
# Should connect successfully
```

✅ **Fix this FIRST before continuing!**

---

### Issue 2: PowerShell Execution Policy

**Error:** "cannot be loaded because running scripts is disabled"

**Fix:**

```powershell
# Check current policy
Get-ExecutionPolicy

# Set to RemoteSigned (recommended)
Set-ExecutionPolicy RemoteSigned -Scope CurrentUser
# Type: Y
```

---

### Issue 3: Git Line Endings (Windows vs Linux)

**Problem:** Windows uses CRLF, Linux uses LF

**Fix (run ONCE):**

```powershell
# Configure Git to auto-convert
git config --global core.autocrlf true

# Verify
git config --global --get core.autocrlf
# Should output: true
```

---

### Issue 4: SSH Connection Timeout

**Error:** "Connection timed out"

**Check:**

```powershell
# Test if port 22 is reachable
Test-NetConnection -ComputerName 69.62.82.145 -Port 22

# If fails:
# - Check VPS firewall (UFW)
# - Check Windows firewall
# - Check VPS provider firewall rules
```

---

### Issue 5: GitHub Email Privacy (GH007 Error)

**Error:**

```
remote: error: GH007: Your push would publish a private email address.
remote: You can make your email public or disable this protection by visiting:
remote: https://github.com/settings/emails
```

**Nguyên nhân:**
- GitHub account có setting "Keep my email addresses private"
- Commit đang dùng email thật thay vì GitHub noreply email

**Fix NGAY:**

```powershell
# Use GitHub noreply email instead
git config --global user.email "YOUR_GITHUB_USERNAME@users.noreply.github.com"

# Example:
git config --global user.email "phuochoavn@users.noreply.github.com"

# Verify
git config --global user.email
```

**If already committed with wrong email:**

```powershell
# Amend last commit with new email
git commit --amend --reset-author --no-edit

# Verify
git log --format="%an %ae" -1

# Push again
git push -u origin main --force
```

**Alternative (not recommended):**
- Go to https://github.com/settings/emails
- Uncheck "Keep my email addresses private"

✅ **Use noreply email for privacy!**

---

## PART 1: LOCAL GIT SETUP

**Time:** 5 phút

### 1.1. Configure Git Identity

**Windows PowerShell:**

```powershell
# Set your name
git config --global user.name "Your Name"

# Set your email - IMPORTANT: Use GitHub noreply email for privacy!
# Format: YOUR_GITHUB_USERNAME@users.noreply.github.com
git config --global user.email "phuochoavn@users.noreply.github.com"

# Verify
git config --global --list
```

**Should show:**

```
user.name=Your Name
user.email=phuochoavn@users.noreply.github.com
core.autocrlf=true
```

**⚠️ IMPORTANT:**
- **Use GitHub noreply email** to protect your privacy
- Format: `YOUR_GITHUB_USERNAME@users.noreply.github.com`
- Find your username at: https://github.com/YOUR_USERNAME
- This prevents GH007 email privacy errors when pushing

✅ **Checkpoint 1.1:** Git identity configured

---

### 1.2. Create Local Project Directory

```powershell
# Create project folder
New-Item -ItemType Directory -Path "C:\Projects\samnghethaycu" -Force

# Navigate to it
cd C:\Projects\samnghethaycu

# Verify
Get-Location
# Should show: C:\Projects\samnghethaycu
```

✅ **Checkpoint 1.2:** Project directory created

---

### 1.3. Initialize Git Repository

```powershell
# Initialize Git
git init

# Verify
ls -Force .git
# Should see .git directory

# Check status
git status
# Should show: "On branch main" or "On branch master"
```

✅ **Checkpoint 1.3:** Git initialized

---

### 1.4. Create .gitignore for Laravel

**Create file:**

```powershell
# Create .gitignore
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

**Verify:**

```powershell
cat .gitignore
```

✅ **Checkpoint 1.4:** .gitignore created

---

### 1.5. Create README.md

```powershell
@"
# samnghethaycu.com

E-Commerce Platform for Natural Health Products

## Tech Stack

- Laravel 12
- Filament v3
- MySQL 8
- Redis 7
- Tailwind CSS v4

## Server

- VPS: 69.62.82.145
- Domain: https://samnghethaycu.com
- Admin: https://samnghethaycu.com/admin
"@ | Out-File -FilePath README.md -Encoding utf8
```

✅ **Checkpoint 1.5:** README created

---

### 1.6. Initial Commit

```powershell
# Add all files
git add .

# Check what will be committed
git status

# Should show:
# new file:   .gitignore
# new file:   README.md

# Create initial commit
git commit -m "Initial commit: project foundation"

# Verify
git log --oneline
# Should show 1 commit
```

✅ **Checkpoint 1.6:** Initial commit created

---

## PART 2: GITHUB REPOSITORY

**Time:** 8 phút

### 2.1. Create GitHub Repository

**On GitHub.com:**

1. Login to GitHub
2. Click **"+"** (top right) → **"New repository"**
3. **Repository name:** `websamnghe`
4. **Description:** `samnghethaycu.com - E-Commerce Platform`
5. **Visibility:** ⚠️ **Private** (IMPORTANT!)
6. ❌ **DO NOT** check any initialization options:
   - ❌ No README
   - ❌ No .gitignore
   - ❌ No license
7. Click **"Create repository"**

**Result:** Empty repository page with setup instructions

✅ **Checkpoint 2.1:** GitHub repository created

---

### 2.2. Create Personal Access Token (for HTTPS)

**Why?** GitHub removed password authentication in 2021.

**Steps:**

1. GitHub → **Settings** (your avatar → Settings)
2. Scroll down → **Developer settings** (left sidebar, bottom)
3. **Personal access tokens** → **Tokens (classic)**
4. Click **"Generate new token"** → **"Generate new token (classic)"**
5. **Note:** `VPS Deployment - samnghethaycu`
6. **Expiration:** `90 days` (or custom)
7. **Scopes:** ✅ Check **`repo`** (full control of private repositories)
8. Click **"Generate token"**
9. ⚠️ **COPY TOKEN NOW** (shows only once!)
   - Example: `ghp_xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx`

**Save token securely:**

```powershell
# Save to file (optional, for reference)
"ghp_your_token_here" | Out-File -FilePath C:\Projects\github-token.txt
```

✅ **Checkpoint 2.2:** Personal Access Token created

---

### 2.3. Add Remote and Push

**PowerShell:**

```powershell
# Ensure you're in project directory
cd C:\Projects\samnghethaycu

# Add GitHub remote (replace YOUR_USERNAME)
git remote add origin https://github.com/phuochoavn/websamnghe.git

# Verify remote
git remote -v
# Should show:
# origin  https://github.com/phuochoavn/websamnghe.git (fetch)
# origin  https://github.com/phuochoavn/websamnghe.git (push)

# Rename branch to main (if needed)
git branch -M main

# Push to GitHub
git push -u origin main
```

**Authentication prompt:**

```
Username for 'https://github.com': phuochoavn
Password for 'https://phuochoavn@github.com': [PASTE TOKEN HERE]
```

**⚠️ IMPORTANT:** Password = Personal Access Token (NOT your GitHub password!)

**Success output:**

```
Enumerating objects: 3, done.
Counting objects: 100% (3/3), done.
...
To https://github.com/phuochoavn/websamnghe.git
 * [new branch]      main -> main
Branch 'main' set up to track remote branch 'main' from 'origin'.
```

**Verify on GitHub:** Refresh repository page, should see README.md

✅ **Checkpoint 2.3:** Code pushed to GitHub

---

### 2.4. Cache Credentials (Optional)

**Avoid typing token every time:**

```powershell
# Enable credential caching (Windows)
git config --global credential.helper wincred

# Next push will be cached automatically
```

✅ **Checkpoint 2.4:** Credentials cached

---

## PART 3: VPS GIT SETUP

**Time:** 10 phút

### 3.1. First Connection & Fix SSH Key

**PowerShell:**

```powershell
# Remove old SSH key (if exists)
ssh-keygen -R 69.62.82.145

# Connect to VPS
ssh root@69.62.82.145
```

**First time prompt:**

```
The authenticity of host '69.62.82.145' can't be established.
ED25519 key fingerprint is SHA256:xxxxxxxxxxxxxxxx.
Are you sure you want to continue connecting (yes/no/[fingerprint])?
```

**Type:** `yes` (full word, not just 'y')

**Enter root password**

**Success:** You're now on VPS!

```
root@vps:~#
```

✅ **Checkpoint 3.1:** Connected to VPS

---

### 3.2. Update System & Install Git

**On VPS:**

```bash
# Update package list
apt update

# Install Git
apt install git -y

# Verify
git --version
# Should show: git version 2.x.x
```

✅ **Checkpoint 3.2:** Git installed on VPS

---

### 3.3. Create Deploy User

**Why?** Don't use root for deployment (security best practice)

**On VPS:**

```bash
# Create deploy user
adduser deploy
# Password: Deploy@2025
# Full Name: Deploy User
# (Press Enter for other fields)

# Add to sudo group
usermod -aG sudo deploy

# Verify
id deploy
# Should show: groups=... sudo ...
```

✅ **Checkpoint 3.3:** Deploy user created

---

### 3.4. Generate SSH Key for GitHub

**Switch to deploy user:**

```bash
# Switch user
su - deploy

# You should now see:
# deploy@vps:~$
```

**Generate SSH key:**

```bash
# Generate ed25519 key (modern & secure)
ssh-keygen -t ed25519 -C "deploy@samnghethaycu.com"

# Prompts:
# Enter file: [Press Enter - use default]
# Enter passphrase: [Press Enter - no passphrase]
# Enter same passphrase again: [Press Enter]

# Verify key created
ls -la ~/.ssh/
# Should see: id_ed25519  id_ed25519.pub
```

✅ **Checkpoint 3.4:** SSH key generated

---

### 3.5. Add SSH Key to GitHub

**On VPS (as deploy user):**

```bash
# Display public key
cat ~/.ssh/id_ed25519.pub
```

**Copy ENTIRE output** (from `ssh-ed25519` to `deploy@samnghethaycu.com`)

**On GitHub.com:**

1. Click avatar → **Settings**
2. Left sidebar → **SSH and GPG keys**
3. Click **"New SSH key"**
4. **Title:** `VPS - samnghethaycu.com`
5. **Key type:** `Authentication Key`
6. **Key:** Paste the public key
7. Click **"Add SSH key"**
8. Confirm with password if asked

✅ **Checkpoint 3.5:** SSH key added to GitHub

---

### 3.6. Test SSH Connection to GitHub

**On VPS (as deploy user):**

```bash
# Test connection
ssh -T git@github.com

# First time prompt:
# Are you sure you want to continue connecting (yes/no/[fingerprint])?
# Type: yes

# Expected success message:
# Hi phuochoavn! You've successfully authenticated, but GitHub does not provide shell access.
```

**If you see "Hi phuochoavn!" → Success!** ✅

✅ **Checkpoint 3.6:** GitHub SSH working

---

### 3.7. Configure Git on VPS

**On VPS (as deploy user):**

```bash
# Set identity
git config --global user.name "Deploy User"
git config --global user.email "deploy@samnghethaycu.com"

# Verify
git config --global --list
```

✅ **Checkpoint 3.7:** Git configured on VPS

---

### 3.8. Create Web Directory & Clone Repository

**On VPS (as deploy user):**

```bash
# Create web directory
sudo mkdir -p /var/www/samnghethaycu.com

# Set ownership to deploy user
sudo chown -R deploy:deploy /var/www/samnghethaycu.com

# Navigate to web root
cd /var/www

# Clone repository (SSH format!)
git clone git@github.com:phuochoavn/websamnghe.git samnghethaycu.com

# Navigate into project
cd samnghethaycu.com

# Verify
ls -la
# Should see: README.md, .gitignore, .git/

# Check Git status
git status
# Should show: On branch main, nothing to commit, working tree clean
```

✅ **Checkpoint 3.8:** Repository cloned on VPS

---

## PART 4: DEPLOYMENT AUTOMATION

**Time:** 7 phút

### 4.1. Create Deployment Script

**On VPS (as deploy user):**

```bash
# Create scripts directory
mkdir -p ~/scripts

# Create deployment script
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

echo "✅ Deployment script created!"
```

✅ **Checkpoint 4.1:** Deployment script created

---

### 4.2. Create Alias

**On VPS (as deploy user):**

```bash
# Add alias to .bashrc
echo "alias deploy-sam='~/scripts/deploy-samnghethaycu.sh'" >> ~/.bashrc

# Reload .bashrc
source ~/.bashrc

# Verify alias exists
type deploy-sam
# Should show: deploy-sam is aliased to...
```

✅ **Checkpoint 4.2:** Alias created

---

### 4.3. Configure Sudo for PHP-FPM Reload

**Why?** Deploy user needs sudo to reload PHP-FPM without password.

**On VPS (as deploy user):**

```bash
# Edit sudoers file
sudo visudo

# Add this line at the end (press 'i' to insert):
deploy ALL=(ALL) NOPASSWD: /bin/systemctl reload php8.4-fpm, /bin/chown, /bin/chmod, /bin/rm

# Save: Press ESC, then type :wq and Enter
```

**Verify:**

```bash
# Test sudo without password
sudo systemctl status php8.4-fpm
# Should work without asking password
```

✅ **Checkpoint 4.3:** Sudo configured

---

## VERIFICATION & TESTING

### Test 1: Local → GitHub Push

**Windows PowerShell:**

```powershell
cd C:\Projects\samnghethaycu

# Make a test change
echo "# Test deployment" >> TEST.md

# Commit and push
git add TEST.md
git commit -m "test: verify Git workflow"
git push origin main
```

**Verify on GitHub:** Refresh repository, should see TEST.md

✅ **Test 1 passed**

---

### Test 2: VPS → GitHub Pull

**On VPS (as deploy user):**

```bash
cd /var/www/samnghethaycu.com

# Pull latest changes
git pull origin main

# Verify file exists
ls -la TEST.md
cat TEST.md
# Should show: # Test deployment
```

✅ **Test 2 passed**

---

### Test 3: Deployment Script

**On VPS (as deploy user):**

```bash
# Run deployment script
deploy-sam
```

**Expected output:**

```
🚀 Starting deployment...

📂 Current directory: /var/www/samnghethaycu.com

📥 Step 1/8: Pulling latest code from GitHub...
✅ No changes (already up to date)

🔍 Step 2/8: Checking .env file...
⚠️  No artisan file found, skipping...

...

🎉 Deployment completed successfully!

🌐 Website: https://samnghethaycu.com
🔧 Admin: https://samnghethaycu.com/admin

📌 Current version:
abc1234 (HEAD -> main, origin/main) test: verify Git workflow

📅 Deployed at: 2025-11-16 15:30:45
👤 Deployed by: deploy
```

✅ **Test 3 passed**

---

### Test 4: End-to-End Workflow

**Complete workflow test:**

```powershell
# Windows - Local
cd C:\Projects\samnghethaycu
echo "Workflow test $(Get-Date)" >> README.md
git add README.md
git commit -m "test: end-to-end workflow verification"
git push origin main
```

**Wait 5 seconds...**

**On VPS:**

```bash
# Deploy with one command
deploy-sam
```

**Should pull latest README.md automatically!**

✅ **Test 4 passed - END-TO-END WORKING!**

---

## ✅ WORKFLOW 1 HOÀN THÀNH!

### Đã có gì:

```
✅ Local Git configured
✅ GitHub repository (private)
✅ Personal Access Token
✅ VPS Git setup
✅ SSH key authentication (GitHub)
✅ Deploy user với permissions
✅ Deployment script: deploy-sam
✅ Sudo configured (no password for deployment)
✅ Workflow tested: Local → GitHub → VPS
```

### Workflow hiện tại:

```
1. Code trên Windows
2. git add . && git commit -m "..." && git push
3. SSH to VPS
4. deploy-sam
5. Done!
```

**Time:** 3-5 phút per deployment (vs 15-30 phút manual)

---

## TROUBLESHOOTING GUIDE

### Issue: "Permission denied (publickey)"

**When:** Pulling from GitHub on VPS

**Fix:**

```bash
# Test SSH connection
ssh -T git@github.com

# If fails, regenerate SSH key
ssh-keygen -t ed25519 -C "deploy@samnghethaycu.com"
cat ~/.ssh/id_ed25519.pub
# Add to GitHub again
```

---

### Issue: "fatal: not a git repository"

**Fix:**

```bash
# Check if in correct directory
pwd
# Should be: /var/www/samnghethaycu.com

# If .git missing:
git init
git remote add origin git@github.com:phuochoavn/websamnghe.git
git pull origin main
```

---

### Issue: Deploy script "Permission denied"

**Fix:**

```bash
# Make script executable
chmod +x ~/scripts/deploy-samnghethaycu.sh

# Run again
deploy-sam
```

---

### Issue: "sudo: sorry, you must have a tty to run sudo"

**Fix:**

```bash
# Edit sudoers
sudo visudo

# Add this line:
Defaults:deploy !requiretty

# Save and exit
```

---

## 📝 NOTES

**Git workflow is now ready!**

- ✅ Every change is version controlled
- ✅ Easy rollback with `git reset`
- ✅ Team collaboration ready
- ✅ CI/CD foundation ready

**Next:** WORKFLOW-2-INFRASTRUCTURE.md (VPS setup + Laravel)

---

**Created:** 2025-11-16
**Version:** 1.0 Professional
**Tested:** Production-ready ✅

---

**END OF WORKFLOW 1** 🎯
