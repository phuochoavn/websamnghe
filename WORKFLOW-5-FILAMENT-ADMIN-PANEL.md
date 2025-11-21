# 🎨 WORKFLOW 5: QUẢN TRỊ FILAMENT

> **Dự án:** samnghethaycu.com - E-Commerce Platform
> **Version:** 4.2 Professional Vietnamese (Updated for Filament v4 + Asset Publishing)
> **Thời gian thực tế:** 12-17 phút
> **Mục tiêu:** Filament (latest) + Admin user + Dashboard working

---

## 📖 WORKFLOW NÀY LÀM GÌ?

### 🎯 Mục đích:

**Cài đặt Filament Admin Panel để quản lý nội dung website.**

Sau khi đã có Laravel working (WF-2) và deployment automation (WF-4), bây giờ cài đặt:
- Filament admin panel (latest version, tự động tương thích với Laravel 12)
- Tạo admin user
- Truy cập dashboard tại `/admin`
- Chuẩn bị cho CRUD operations (WF-6)

**📝 Note:** Với Laravel 12, Composer sẽ tự động cài Filament v4.x (latest stable version).

### 🎁 Kết quả sau workflow:

✅ **Filament installed:**
- Admin panel tại `/admin`
- User authentication working
- Dashboard accessible
- Dark mode toggle

✅ **Admin user created:**
- Email: admin@samnghethaycu.com
- Password: Admin@123456
- Can login and manage site

✅ **Ready for next workflow:**
- Database schema (WF-6)
- CRUD resources (WF-7)

### ⚠️ PREREQUISITES:

**PHẢI hoàn thành trước:**
```
✅ WORKFLOW-1: VPS Infrastructure (LEMP + SSL)
✅ WORKFLOW-2: Laravel Installation (Laravel working)
✅ WORKFLOW-3: Git Workflow Setup (Git automation)
✅ WORKFLOW-4: Deployment Automation (deploy-sam command)
✅ Laravel working at: https://samnghethaycu.com
```

**📍 Trên Windows - Verify trước khi bắt đầu:**

```powershell
# Check Laravel working locally
cd C:\Projects\samnghethaycu
php artisan --version
# Phải thấy: Laravel Framework 12.x.x
```

**📍 Trên VPS - Verify Laravel working:**

```bash
ssh deploy@69.62.82.145

cd /var/www/samnghethaycu.com

# Check Laravel
php artisan --version
# Phải thấy: Laravel Framework 12.x.x

# Test deploy command
type deploy-sam
# Phải thấy: deploy-sam is aliased to '...'
```

**Browser test:**

```
https://samnghethaycu.com
```

**Should see:** Laravel welcome page

**Nếu bất kỳ check nào FAIL → DỪNG LẠI, hoàn thành WF-1 đến WF-4 trước!**

---

## PHẦN 1: CÀI ĐẶT FILAMENT (LOCAL)

**Thời gian:** 5 phút

### BƯỚC 1.1: Install Filament Package

**📍 Trên Windows (PowerShell):**

```powershell
# Navigate to project
cd C:\Projects\samnghethaycu

# Install Filament (latest version compatible with Laravel 12)
composer require filament/filament -W

# This takes 1-2 minutes...
# Wait for completion
```

**Expected output:**

```
Using version ^4.2 for filament/filament
./composer.json has been updated
Running composer update filament/filament --with-all-dependencies
Loading composer repositories with package information
Updating dependencies
Lock file operations: 34 installs, 0 updates, 0 removals
  - Locking filament/filament (v4.2.3)
  - Locking livewire/livewire (v3.6.4)
  ...
Installing dependencies from lock file
Package operations: 34 installs, 0 updates, 0 removals
  - Installing filament/filament (v4.2.3): Extracting archive
  ...
Generating optimized autoload files
> Illuminate\Foundation\ComposerScripts::postAutoloadDump
> @php artisan package:discover --ansi

   INFO  Discovering packages.

  filament/filament ............................................................. DONE
  livewire/livewire ............................................................. DONE
  ...
```

**📝 Note:** Filament v4.2.x sẽ được cài tự động (tương thích với Laravel 12).

✅ **Checkpoint 1.1:** Filament package installed

---

### BƯỚC 1.2: Install Admin Panel

**📍 Trên Windows:**

```powershell
# Install Filament panels
php artisan filament:install --panels
```

**Prompt and answer:**

```
What is the ID of the panel you would like to create?
> admin
```

**Press Enter**

**Expected output:**

```
Creating admin panel...

Panel created successfully!

The following files have been created:
- app/Providers/Filament/AdminPanelProvider.php

You can now access the panel at: /admin
```

✅ **Checkpoint 1.2:** Admin panel installed

---

### BƯỚC 1.3: Verify Installation

**📍 Trên Windows:**

```powershell
# Check if Filament routes exist
php artisan route:list | Select-String "admin"

# Should show multiple /admin/* routes like:
# GET|HEAD  admin ................ filament.admin.pages.dashboard
# GET|HEAD  admin/login .......... filament.admin.auth.login
# POST      admin/logout ......... filament.admin.auth.logout
```

**Verify files created:**

```powershell
# Check AdminPanelProvider exists
ls app\Providers\Filament\

# Should show: AdminPanelProvider.php
```

✅ **Checkpoint 1.3:** Filament routes verified

---

## PHẦN 2: COMMIT & PUSH

**Thời gian:** 1 phút

### BƯỚC 2.1: Git Commit

**📍 Trên Windows:**

```powershell
# Check changes
git status

# Should see:
# - modified: composer.json
# - modified: composer.lock
# - new file: app/Providers/Filament/AdminPanelProvider.php
# - new file: config/filament.php

# Add all changes
git add .

# Commit
git commit -m "feat: install Filament v4 admin panel with default configuration"

# Push to GitHub
git push origin main
```

**Expected output:**

```
[main abc1234] feat: install Filament v4 admin panel with default configuration
 X files changed, XXX insertions(+), X deletions(-)
 create mode 100644 app/Providers/Filament/AdminPanelProvider.php
 create mode 100644 config/filament.php

Enumerating objects: X, done.
...
To https://github.com/phuochoavn/websamnghe.git
   abc1234..def5678  main -> main
```

✅ **Checkpoint 2.1:** Filament pushed to GitHub

---

## PHẦN 3: DEPLOY LÊN VPS

**Thời gian:** 2 phút

### BƯỚC 3.1: Deploy với deploy-sam

**📍 Trên VPS:**

```bash
# SSH to VPS
ssh deploy@69.62.82.145

cd /var/www/samnghethaycu.com

# Deploy with our automation script!
deploy-sam
```

**Expected output:**

```
🚀 Starting deployment...

📂 Current directory: /var/www/samnghethaycu.com

📥 Step 1/8: Pulling latest code from GitHub...
✅ Code updated
def5678 feat: install Filament v4 admin panel with default configuration

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
```

✅ **Checkpoint 3.1:** Filament deployed to VPS

---

### BƯỚC 3.2: Verify Filament Routes on VPS

**📍 Trên VPS:**

```bash
# Check Filament routes exist
php artisan route:list | grep admin

# Should show multiple /admin/* routes
```

✅ **Checkpoint 3.2:** Filament routes verified on VPS

---

## PHẦN 3A: PUBLISH ASSETS TRÊN VPS

**Thời gian:** 2 phút

**⚠️ CRITICAL:** Filament v4 assets (Livewire JS/CSS) phải được publish trên VPS sau deployment. Nếu không, admin panel sẽ không load được (404 errors cho livewire.min.js).

**📝 Note:** Assets này KHÔNG được commit vào Git (do .gitignore), nên phải publish trực tiếp trên VPS.

### BƯỚC 3A.1: Publish Livewire và Filament Assets

**📍 Trên VPS:**

```bash
cd /var/www/samnghethaycu.com

# Publish Livewire assets
php artisan livewire:publish --assets

# Publish Filament assets
php artisan filament:assets

# Fix permissions
sudo chown -R www-data:www-data public/
sudo chmod -R 755 public/

# Clear all caches
php artisan optimize:clear
php artisan config:cache
php artisan route:cache
```

**Expected output:**

```
INFO  Publishing [livewire:assets] assets.

Copying directory [vendor/livewire/livewire/dist] to [public/vendor/livewire] .................. DONE

INFO  Successfully published assets for [livewire:assets]!

[... Filament assets list ...]
INFO  Successfully published assets!

Configuration cache cleared successfully.
Configuration cached successfully.
Route cache cleared successfully.
Routes cached successfully.
```

✅ **Checkpoint 3A.1:** Assets published successfully

---

### BƯỚC 3A.2: Verify Assets Exist

**📍 Trên VPS:**

```bash
# Check Livewire assets
ls -la public/vendor/livewire/
# Should show: livewire.min.js, livewire.min.js.map

# Check Filament assets
ls -la public/js/filament/ 2>/dev/null || echo "Filament JS not found (OK if using CDN)"
ls -la public/css/filament/ 2>/dev/null || echo "Filament CSS not found (OK if using CDN)"
ls -la public/fonts/filament/ 2>/dev/null || echo "Filament fonts not found (OK if using CDN)"

# Test file accessibility
curl -I https://samnghethaycu.com/vendor/livewire/livewire.min.js
# Should return: HTTP/2 200
```

**Expected output:**

```
public/vendor/livewire/:
-rw-r--r-- 1 www-data www-data 123456 Nov 21 10:30 livewire.min.js
-rw-r--r-- 1 www-data www-data 234567 Nov 21 10:30 livewire.min.js.map

HTTP/2 200
content-type: application/javascript
...
```

✅ **Checkpoint 3A.2:** Assets accessible via web

---

## PHẦN 4: TẠO ADMIN USER

**Thời gian:** 2 phút

### BƯỚC 4.1: Create Admin User

**📍 Trên VPS:**

```bash
cd /var/www/samnghethaycu.com

# Create Filament admin user
php artisan make:filament-user
```

**Prompts and answers:**

```
Name:
> Admin

Email address:
> admin@samnghethaycu.com

Password:
> Admin@123456

(Nhập password 2 lần)
```

**Expected output:**

```
Success! admin@samnghethaycu.com may now log in at https://samnghethaycu.com/admin
```

✅ **Checkpoint 4.1:** Admin user created

---

### BƯỚC 4.2: Verify User in Database

**📍 Trên VPS:**

```bash
# Check user exists
php artisan tinker
```

**In tinker:**

```php
User::where('email', 'admin@samnghethaycu.com')->first();
# Should return User object

exit
```

✅ **Checkpoint 4.2:** Admin user verified

---

## PHẦN 5: TEST ADMIN PANEL

**Thời gian:** 2 phút

### BƯỚC 5.1: Access Admin Login Page

**📍 Browser:**

```
https://samnghethaycu.com/admin
```

**Should see:**
- Filament login page
- "Sign in" heading
- Email and Password fields
- "Sign in" button
- Professional Filament UI

✅ **Checkpoint 5.1:** Login page accessible

---

### BƯỚC 5.2: Login to Dashboard

**📍 Browser - Login credentials:**

```
Email: admin@samnghethaycu.com
Password: Admin@123456
```

**Click "Sign in"**

**Should see:** 🎉 **Filament Dashboard!**

- Dashboard heading
- Sidebar navigation (empty for now)
- User menu (top right with "Admin" name)
- Dark mode toggle
- Clean, professional interface

✅ **Checkpoint 5.2:** Login successful

---

### BƯỚC 5.3: Explore Dashboard Features

**📍 Browser - Check these features:**

```
✅ Sidebar: Navigation menu (empty, will add resources in WF-6)
✅ User Menu: Click your name (top right)
   - Profile link
   - Logout link
✅ Dark Mode: Toggle dark/light mode (moon/sun icon)
✅ Dashboard: Main content area (empty widgets for now)
✅ Responsive: Resize browser window (mobile-friendly)
```

✅ **Checkpoint 5.3:** All features working

---

### BƯỚC 5.4: Test Logout

**📍 Browser:**

```
1. Click user menu (top right)
2. Click "Sign out"
3. Should redirect to login page
4. Try login again - should work
```

✅ **Checkpoint 5.4:** Logout working

---

## PHẦN 6: CẤU HÌNH USER MODEL (Optional but Recommended)

**Thời gian:** 3 phút

**Tại sao cần?** Add `canAccessPanel()` method để kiểm soát ai có thể truy cập admin panel.

### BƯỚC 6.1: Update User Model

**📍 Trên Windows:**

```powershell
# Open User model in your editor
notepad app\Models\User.php
# Or use VS Code: code app\Models\User.php
```

**Update User.php with Filament interface:**

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;

class User extends Authenticatable implements FilamentUser
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Determine if the user can access the Filament admin panel.
     */
    public function canAccessPanel(Panel $panel): bool
    {
        // Allow access if email ends with @samnghethaycu.com
        return str_ends_with($this->email, '@samnghethaycu.com');
    }
}
```

**Save file**

✅ **Checkpoint 6.1:** User model updated

---

### BƯỚC 6.2: Commit & Deploy

**📍 Trên Windows:**

```powershell
git add app\Models\User.php
git commit -m "feat: configure User model for Filament admin panel access control"
git push origin main
```

**📍 Trên VPS:**

```bash
deploy-sam
```

**Expected:** Deploy completes successfully

✅ **Checkpoint 6.2:** User model deployed

---

### BƯỚC 6.3: Test Access Control

**📍 Browser:**

```
1. Logout from admin panel
2. Login with admin@samnghethaycu.com
   - Should work ✅
3. (Optional) Try creating user with different email domain
   - Should be blocked from accessing /admin
```

✅ **Checkpoint 6.3:** Access control working

---

## ✅ VERIFICATION - HOÀN THÀNH WORKFLOW 5

### Full Workflow Checklist

**✅ Checklist - Filament Admin Panel:**

```
✅ Filament v4 installed locally
✅ AdminPanelProvider created
✅ Code committed and pushed to GitHub
✅ Deployed to VPS with deploy-sam
✅ Livewire & Filament assets published on VPS
✅ Assets accessible (livewire.min.js returns HTTP 200)
✅ Admin user created (admin@samnghethaycu.com)
✅ Admin panel accessible at /admin
✅ Can login successfully
✅ Dashboard loads without errors
✅ User menu working
✅ Dark mode toggle working
✅ Logout function working
✅ User model configured with canAccessPanel()
```

**Final test:**

**📍 Browser:**

```
1. Visit: https://samnghethaycu.com/admin
2. Login with admin@samnghethaycu.com
3. Verify dashboard loads
4. Toggle dark mode
5. Check user menu
6. Logout
7. Login again
```

**All working?** → SUCCESS! 🎉

---

## 🎉 WORKFLOW 5 COMPLETE!

### Bạn đã có:

```
✅ Filament v4.x installed and configured (latest stable)
✅ Admin panel at /admin with professional UI
✅ Admin user (admin@samnghethaycu.com)
✅ User authentication working
✅ Dashboard accessible
✅ Dark mode toggle
✅ Access control via canAccessPanel()
✅ Deployed via Git workflow
✅ Ready for CRUD resources (WF-6)
✅ Compatible with Laravel 12
```

### Admin Credentials:

```
URL: https://samnghethaycu.com/admin
Email: admin@samnghethaycu.com
Password: Admin@123456
```

**⚠️ IMPORTANT:** Change this password in production!

### Current Features:

```
✅ User authentication with Filament
✅ Dashboard (empty widgets, will add in WF-8)
✅ User profile management
✅ Dark mode toggle
✅ Responsive design (mobile-friendly)
✅ Access control (@samnghethaycu.com domain only)
```

### Deployment Workflow Verified:

```
Local (Windows)          GitHub              VPS (Production)
───────────────          ──────              ────────────────
Install Filament    →    Push code      →    deploy-sam ✨
Configure User      →    Push changes   →    deploy-sam ✨
                                              → Filament working!
```

---

## 🚀 NEXT STEP:

```
✅ WORKFLOW-1: VPS Infrastructure (LEMP + SSL)
✅ WORKFLOW-2: Laravel Installation (Health check working)
✅ WORKFLOW-3: Git Workflow Setup (Passwordless SSH)
✅ WORKFLOW-4: Deployment Automation (One-command deployment)
✅ WORKFLOW-5: Filament Admin Panel (Dashboard working)
→ WORKFLOW-6: DATABASE SCHEMA
  Create 15 models and 23 database tables
  Generate Filament resources for CRUD
  Time: 25-35 minutes
```

---

## 🔄 ROLLBACK: XÓA FILAMENT VỀ WORKFLOW-4

**Nếu muốn xóa Filament và quay về trạng thái WORKFLOW-4 (Laravel without admin panel):**

**⚠️ IMPORTANT:** Rollback phải xóa cả code VÀ published assets trên VPS!

### **PHẦN 1: XÓA FILAMENT TRÊN LOCAL**

**Thời gian:** 3-5 phút

**⚠️ CRITICAL ORDER:** Phải xóa Filament files TRƯỚC, rồi mới xóa package. Nếu xóa package trước sẽ gặp lỗi "Class Filament\PanelProvider not found"!

**📍 Trên Windows (Local):**

```powershell
cd C:\Projects\samnghethaycu

# BƯỚC 1: Delete Filament files FIRST (CRITICAL!)
Remove-Item -Recurse -Force app\Providers\Filament -ErrorAction SilentlyContinue
Remove-Item -Force config\filament.php -ErrorAction SilentlyContinue

# Verify files deleted
ls app\Providers\
# Should NOT show: Filament directory

ls config\filament.php
# Should show: File not found (error is expected)

# BƯỚC 2: Remove Filament package
composer remove filament/filament -W

# Expected output:
# Removing filament/filament (v4.2.3)
# ...
# Package operations: 0 installs, 0 updates, 34 removals
# (May show error about filament:upgrade - this is OK, will fix in next step)

# BƯỚC 3: Remove filament:upgrade script from composer.json
code composer.json

# Trong VS Code, tìm section "scripts" -> "post-autoload-dump"
# XÓA dòng: "@php artisan filament:upgrade"
#
# BEFORE (3 dòng):
# "post-autoload-dump": [
#     "Illuminate\\Foundation\\ComposerScripts::postAutoloadDump",
#     "@php artisan package:discover --ansi",
#     "@php artisan filament:upgrade"   <-- XÓA DÒNG NÀY
# ],
#
# AFTER (2 dòng):
# "post-autoload-dump": [
#     "Illuminate\\Foundation\\ComposerScripts::postAutoloadDump",
#     "@php artisan package:discover --ansi"
# ],
#
# Save file (Ctrl+S)

# BƯỚC 4: Rebuild autoloader
composer dump-autoload

# Expected output:
# Generating optimized autoload files
# > Illuminate\Foundation\ComposerScripts::postAutoloadDump
# > @php artisan package:discover --ansi
#
#    INFO  Discovering packages.
#
#   laravel/pail .................................... DONE
#   laravel/sail .................................... DONE
#   laravel/tinker .................................. DONE
#   ...
#
# (Should complete WITHOUT errors - no filament:upgrade error)
```

✅ **Checkpoint 1:** Filament files, package, and scripts removed locally

---

### **BƯỚC 4: Revert User Model**

**📍 Trên Windows:**

**Option A: Manual Edit (Recommended)**

```powershell
# Open User model in editor
code app\Models\User.php
```

**Xóa các dòng này:**

```php
// Line ~5-6: Remove these imports
use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;

// Line ~10: Remove FilamentUser interface
class User extends Authenticatable implements FilamentUser  // ❌ Remove

// Change to:
class User extends Authenticatable  // ✅ Keep only this

// Line ~35-42: Remove entire canAccessPanel method
public function canAccessPanel(Panel $panel): bool  // ❌ Remove this method
{
    return str_ends_with($this->email, '@samnghethaycu.com');
}
```

**User.php sau khi revert:**

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
}
```

**Save file (Ctrl+S)**

**Option B: Git Revert (If you committed User model separately)**

```powershell
# Find the commit that added FilamentUser
git log --oneline app\Models\User.php

# Revert that specific commit
git revert <commit-hash> --no-commit

# Then continue with BƯỚC 5
```

✅ **Checkpoint 2:** User model reverted

---

### **BƯỚC 5: Clear Caches**

**📍 Trên Windows:**

```powershell
php artisan optimize:clear

# Expected output:
# Configuration cache cleared successfully.
# Route cache cleared successfully.
# View cache cleared successfully.
# Compiled services and packages files removed successfully.
# Caches cleared successfully.
```

✅ **Checkpoint 3:** Caches cleared

---

### **BƯỚC 6: Verify Locally**

**📍 Trên Windows:**

```powershell
# Check Filament package removed
composer show | Select-String "filament"
# Should show: (empty)

# Check routes (should have no admin routes)
php artisan route:list | Select-String "admin"
# Should show: (empty)

# Test Laravel still works
php artisan --version
# Should show: Laravel Framework 12.x.x
```

✅ **Checkpoint 4:** Local verification passed

---

### **BƯỚC 7: Commit & Push**

**📍 Trên Windows:**

```powershell
# Check changes
git status

# Should show:
# - modified: composer.json
# - modified: composer.lock
# - deleted: app/Providers/Filament/
# - deleted: config/filament.php
# - modified: app/Models/User.php

# Add all changes
git add .

# Commit
git commit -m "revert: remove Filament admin panel and restore to WORKFLOW-4 state"

# Push to GitHub
git push origin main
```

**Expected output:**

```
[main abc1234] revert: remove Filament admin panel and restore to WORKFLOW-4 state
 X files changed, X insertions(+), XXX deletions(-)
 delete mode 100644 app/Providers/Filament/AdminPanelProvider.php
 delete mode 100644 config/filament.php

To https://github.com/phuochoavn/websamnghe.git
   def5678..abc1234  main -> main
```

✅ **Checkpoint 5:** Changes committed and pushed to GitHub

---

### **PHẦN 2: XÓA FILAMENT TRÊN VPS**

**Thời gian:** 5-7 phút

**📍 Trên VPS:**

```bash
# BƯỚC 8: Deploy removal to VPS
ssh deploy@69.62.82.145

cd /var/www/samnghethaycu.com

deploy-sam
```

**Expected output:**

```
🚀 Starting deployment...

📥 Step 1/8: Pulling latest code from GitHub...
✅ Code updated
abc1234 revert: remove Filament admin panel and restore to WORKFLOW-4 state

📦 Step 4/8: Installing Composer dependencies...
✅ Dependencies installed
(Filament packages will be removed)

...

🎉 Deployment completed successfully!
```

✅ **Checkpoint 6:** Code deployed to VPS

---

### **BƯỚC 9: Clear Cache on VPS**

**📍 Trên VPS:**

```bash
# Clear all caches
php artisan optimize:clear

# Rebuild caches (without Filament)
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Restart PHP-FPM
sudo systemctl reload php8.4-fpm
```

**Expected output:**

```
Configuration cache cleared successfully.
Route cache cleared successfully.
View cache cleared successfully.
...
Configuration cached successfully.
Routes cached successfully.
Views cached successfully.
```

✅ **Checkpoint 7:** Caches rebuilt on VPS

---

### **BƯỚC 10: XÓA PUBLISHED ASSETS (CRITICAL!)**

**📍 Trên VPS:**

**⚠️ CRITICAL:** Đây là bước quan trọng nhất! Assets được publish với sudo permissions, không bị xóa tự động bởi deploy-sam.

```bash
cd /var/www/samnghethaycu.com

# List assets before deletion
ls -la public/vendor/livewire/ 2>/dev/null || echo "Livewire assets not found"
ls -la public/js/filament/ 2>/dev/null || echo "Filament JS not found"
ls -la public/css/filament/ 2>/dev/null || echo "Filament CSS not found"
ls -la public/fonts/filament/ 2>/dev/null || echo "Filament fonts not found"

# DELETE Livewire assets
sudo rm -rf public/vendor/livewire/

# DELETE Filament assets (if exist)
sudo rm -rf public/js/filament/
sudo rm -rf public/css/filament/
sudo rm -rf public/fonts/filament/

# Verify deletion
ls -la public/vendor/ 2>/dev/null
# Should NOT show: livewire directory

# Test 404 (assets should be gone)
curl -I https://samnghethaycu.com/vendor/livewire/livewire.min.js
# Should return: HTTP/2 404
```

**Expected output:**

```
# Before deletion:
public/vendor/livewire/:
drwxr-xr-x 2 www-data www-data   4096 Nov 21 10:30 .
-rw-r--r-- 1 www-data www-data 123456 Nov 21 10:30 livewire.min.js
-rw-r--r-- 1 www-data www-data 234567 Nov 21 10:30 livewire.min.js.map

# After deletion:
ls: cannot access 'public/vendor/livewire/': No such file or directory

# Curl test:
HTTP/2 404
content-type: text/html; charset=UTF-8
```

✅ **Checkpoint 8:** Published assets deleted from VPS

---

### **BƯỚC 11: Remove Admin User (Optional)**

**📍 Trên VPS:**

```bash
# Remove admin user from database
php artisan tinker
```

**In tinker:**

```php
// Check if user exists
$user = User::where('email', 'admin@samnghethaycu.com')->first();
$user;
// Should return: User object or null

// Delete user
User::where('email', 'admin@samnghethaycu.com')->delete();
// Should return: 1 (1 row deleted)

// Verify deletion
User::where('email', 'admin@samnghethaycu.com')->count();
// Should return: 0

exit
```

✅ **Checkpoint 9:** Admin user deleted

---

### **PHẦN 3: VERIFICATION - HOÀN THÀNH ROLLBACK**

**📍 Trên VPS:**

```bash
# 1. Check Filament routes removed
php artisan route:list | grep admin
# Expected: (empty, no output)

# 2. Check Filament package removed
composer show | grep filament
# Expected: (empty, no output)

# 3. Verify published assets removed
ls -la public/vendor/livewire/ 2>/dev/null
# Expected: No such file or directory

# 4. Test Livewire JS 404
curl -I https://samnghethaycu.com/vendor/livewire/livewire.min.js
# Expected: HTTP/2 404

# 5. Test website still works
curl https://samnghethaycu.com
# Expected: Laravel welcome page HTML

# 6. Check Laravel version
php artisan --version
# Expected: Laravel Framework 12.x.x

# 7. Check database (optional)
php artisan db:show
# Expected: Database connection info (should work)
```

**📍 Browser Test:**

```
1. Visit: https://samnghethaycu.com
   - Should show: Laravel welcome page ✅

2. Visit: https://samnghethaycu.com/admin
   - Should show: 404 Not Found ✅

3. Open browser console (F12)
   - Should show: No errors ✅
```

---

### **✅ ROLLBACK COMPLETE CHECKLIST:**

```
✅ Filament package removed (composer remove)
✅ Autoloader rebuilt (composer dump-autoload)
✅ Filament files deleted (AdminPanelProvider, config)
✅ User model reverted (removed FilamentUser interface)
✅ Caches cleared locally
✅ Local verification passed
✅ Changes committed and pushed to GitHub
✅ Changes deployed to VPS (deploy-sam)
✅ Caches rebuilt on VPS
✅ Published assets deleted from VPS (CRITICAL!)
✅ Admin routes removed (php artisan route:list)
✅ Admin panel inaccessible (404 at /admin)
✅ Admin user deleted (optional)
✅ Laravel welcome page working
✅ Website functioning normally
```

---

### **🎉 Rollback Success!**

**Bạn đã về trạng thái WORKFLOW-4:**

```
✅ Laravel 12 working at https://samnghethaycu.com
✅ No Filament admin panel
✅ No admin routes
✅ No published assets
✅ No admin user
✅ Git workflow hoạt động bình thường
✅ VPS deployment automation vẫn work (deploy-sam)
```

**Bây giờ bạn có thể:**
- Làm lại WORKFLOW-5 từ đầu
- Tiếp tục với project khác
- Test deployment workflow

---

### **📝 Common Rollback Issues:**

**Issue 1: Assets vẫn còn sau rollback**

**Symptom:** Curl vẫn trả về HTTP 200 cho livewire.min.js

**Fix:**
```bash
# Force delete with sudo
sudo rm -rf /var/www/samnghethaycu.com/public/vendor/livewire/
sudo rm -rf /var/www/samnghethaycu.com/public/js/filament/
sudo rm -rf /var/www/samnghethaycu.com/public/css/filament/
sudo rm -rf /var/www/samnghethaycu.com/public/fonts/filament/
```

**Issue 2: Permission denied khi xóa assets**

**Error:** `rm: cannot remove 'public/vendor/livewire/': Permission denied`

**Fix:**
```bash
# Use sudo
sudo rm -rf public/vendor/livewire/

# Or change ownership first
sudo chown -R deploy:deploy public/vendor/
rm -rf public/vendor/livewire/
```

**Issue 3: Composer autoload errors sau rollback**

**Error:** `Class 'Filament\...' not found`

**Fix:**
```bash
# Rebuild autoloader
composer dump-autoload

# Clear all caches
php artisan optimize:clear
php artisan config:clear

# Restart PHP-FPM
sudo systemctl restart php8.4-fpm
```

**Issue 4: "Class Filament\PanelProvider not found" khi composer remove**

**Error:**
```
In AdminPanelProvider.php line 22:
  Class "Filament\PanelProvider" not found

Script @php artisan package:discover --ansi handling the post-autoload-dump event returned with error code 1
```

**Root Cause:** Sai thứ tự! AdminPanelProvider.php vẫn còn trong `app/Providers/Filament/` nên Laravel cố load nó, nhưng class `Filament\PanelProvider` đã bị xóa bởi `composer remove`.

**Fix (trên Windows):**
```powershell
# Step 1: Delete Filament files (should have done this FIRST!)
Remove-Item -Recurse -Force app\Providers\Filament -ErrorAction SilentlyContinue
Remove-Item -Force config\filament.php -ErrorAction SilentlyContinue

# Step 2: Rebuild autoloader (will work now)
composer dump-autoload

# Expected output (no errors):
# Generating optimized autoload files
# > Illuminate\Foundation\ComposerScripts::postAutoloadDump
# > @php artisan package:discover --ansi
#
#    INFO  Discovering packages.
#
# (NO Filament packages listed)
```

**Prevention:** Luôn làm theo đúng thứ tự trong PHẦN 1:
1. BƯỚC 1: Delete Filament files FIRST ✅
2. BƯỚC 2: Remove package ✅
3. BƯỚC 3: Rebuild autoloader ✅

---

## 🔧 TROUBLESHOOTING

### Issue 1: Cannot access /admin (404 error)

**Error:** 404 Not Found khi truy cập `/admin`

**Cause:** Routes chưa được cache hoặc Filament chưa install đúng

**📍 Trên VPS - Fix:**

```bash
cd /var/www/samnghethaycu.com

# Clear route cache
php artisan route:clear
php artisan route:cache

# Verify admin routes exist
php artisan route:list | grep admin
# Should show multiple /admin/* routes

# If no routes, reinstall Filament
composer require filament/filament:"^3.2" -W
php artisan filament:install --panels

# Restart PHP-FPM
sudo systemctl restart php8.4-fpm
```

---

### Issue 2: "Class FilamentUser not found"

**Error:**
```
Class 'Filament\Models\Contracts\FilamentUser' not found
```

**Cause:** Filament dependencies chưa cài đầy đủ

**📍 Trên VPS - Fix:**

```bash
# Install missing Filament dependencies
composer require filament/filament:"^3.2" -W

# Rebuild autoloader
composer dump-autoload

# Clear all caches
php artisan optimize:clear

# Verify Filament installed
composer show | grep filament
# Should show: filament/filament v3.2.x
```

---

### Issue 3: Login but dashboard shows errors

**Error:** Dashboard loads but shows errors or blank page

**📍 Trên VPS - Check logs:**

```bash
# Laravel log
tail -50 /var/www/samnghethaycu.com/storage/logs/laravel.log

# Nginx error log
sudo tail -50 /var/log/nginx/samnghethaycu-error.log

# PHP-FPM log
sudo tail -50 /var/log/php8.4-fpm.log
```

**📍 Trên VPS - Common fixes:**

```bash
# Clear Filament cache
php artisan filament:optimize-clear

# Rebuild caches
php artisan optimize

# Fix permissions
sudo chown -R www-data:www-data storage bootstrap/cache
sudo chmod -R 775 storage bootstrap/cache

# Restart PHP-FPM
sudo systemctl restart php8.4-fpm
```

---

### Issue 4: Cannot create admin user

**Error:** "Database connection failed" or "SQLSTATE[HY000] [1045] Access denied"

**Cause:** Database credentials sai trong .env

**📍 Trên VPS - Fix:**

```bash
# Test database connection
php artisan db:show
# Should show database info

# If fails, check .env
cat .env | grep DB_
# Verify:
# DB_DATABASE=samnghethaycu
# DB_USERNAME=samnghethaycu_user
# DB_PASSWORD=<correct password>

# Get correct credentials
cat ~/credentials/database.txt

# Update .env if wrong
nano .env

# Clear config cache
php artisan config:clear
php artisan config:cache

# Test MySQL connection manually
mysql -u samnghethaycu_user -p samnghethaycu
# Enter password from credentials.txt
# Should connect successfully
```

---

### Issue 5: Admin user exists but cannot login

**Error:** "These credentials do not match our records"

**Cause:** Password sai hoặc user chưa tạo đúng

**📍 Trên VPS - Reset password:**

```bash
cd /var/www/samnghethaycu.com

php artisan tinker
```

**In tinker:**

```php
$user = App\Models\User::where('email', 'admin@samnghethaycu.com')->first();

// Check if user exists
$user;
// Should return User object

// Reset password
$user->password = bcrypt('Admin@123456');
$user->save();

// Verify
$user->email;
// Should show: admin@samnghethaycu.com

exit
```

**Try login again with Admin@123456**

---

### Issue 6: "Too Many Attempts" login error

**Error:** "Too many login attempts. Please try again in X seconds."

**Cause:** Rate limiting bị trigger do thử login sai nhiều lần

**📍 Trên VPS - Fix:**

```bash
# Clear application cache (includes rate limiter)
php artisan cache:clear

# Wait 1 minute then try login again
```

---

### Issue 7: Composer install errors during deploy

**Error:**
```
Your requirements could not be resolved to an installable set of packages.
  Problem 1
    - filament/filament[v4.2.0, ..., v4.2.x] require php ^8.1 -> ...
```

**Cause:** PHP version mismatch

**📍 Trên VPS - Fix:**

```bash
# Check PHP version
php -v
# Should be PHP 8.4.x

# If wrong version, check php command
which php
# Should be: /usr/bin/php8.4

# Update alternatives if needed
sudo update-alternatives --config php
# Select php8.4

# Clear Composer cache
composer clear-cache

# Try deploy again
deploy-sam
```

---

### Issue 8: Dependency conflict errors (termwind/collision)

**Error:**
```
Your requirements could not be resolved to an installable set of packages.
  Problem 1
    - filament/filament v4.2.0 requires illuminate/console ^10.0 -> ...
    - illuminate/console require nunomaduro/termwind ^1.13 -> ...
    - but these were not loaded, likely because it conflicts with another require.
```

**Cause:** Xung đột version giữa Filament và các dependencies của Laravel 12

**📍 Trên Windows - Fix (3 cách, thử theo thứ tự):**

**Cách 1: Cài Filament không chỉ định version (RECOMMENDED)**

```powershell
# Let Composer choose compatible version
composer require filament/filament -W
```

**Cách 2: Update collision cùng lúc**

```powershell
# Update both Filament and collision
composer require filament/filament nunomaduro/collision -W
```

**Cách 3: Update toàn bộ dependencies trước**

```powershell
# Step 1: Update all packages
composer update -W

# Step 2: Install Filament
composer require filament/filament
```

**⚠️ Note:** Với Laravel 12, luôn dùng `composer require filament/filament -W` (không chỉ định version) để Composer tự động chọn version tương thích.

---

### Issue 9: Missing Livewire/Filament assets (404 errors)

**Error (Browser Console):**
```
GET https://samnghethaycu.com/livewire/livewire.min.js?id=df3a17f2
net::ERR_ABORTED 404 (Not Found)
```

**Symptoms:**
- Admin login page loads but không login được
- Dashboard hiện trang trắng hoặc không có interactive elements
- Browser console shows 404 errors for JS/CSS files
- Livewire components không hoạt động

**Root Cause:** Filament/Livewire assets chưa được publish trên VPS

**Why this happens:**
- Assets được tạo trong `public/vendor/livewire/`, `public/js/filament/`, etc.
- Nhưng `.gitignore` bỏ qua thư mục này (không commit vào Git)
- Khi deploy với `deploy-sam`, assets không có trong Git repository
- VPS không có assets → 404 errors

**📍 Trên VPS - Fix (Step-by-step):**

```bash
cd /var/www/samnghethaycu.com

# STEP 1: Publish Livewire assets
php artisan livewire:publish --assets

# Expected output:
# INFO  Publishing [livewire:assets] assets.
# Copying directory [vendor/livewire/livewire/dist] to [public/vendor/livewire] .... DONE

# STEP 2: Publish Filament assets
php artisan filament:assets

# Expected output:
# [... list of Filament assets ...]
# INFO  Successfully published assets!

# STEP 3: Fix permissions (CRITICAL!)
sudo chown -R www-data:www-data public/
sudo chmod -R 755 public/

# STEP 4: Verify assets exist
ls -la public/vendor/livewire/
# Should show: livewire.min.js, livewire.min.js.map

# STEP 5: Test accessibility from web
curl -I https://samnghethaycu.com/vendor/livewire/livewire.min.js
# Should return: HTTP/2 200

# STEP 6: Clear all caches
php artisan optimize:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache

# STEP 7: Restart PHP-FPM
sudo systemctl reload php8.4-fpm
```

**📍 Verify Fix:**

**Browser:**
```
1. Open: https://samnghethaycu.com/admin
2. Open browser console (F12)
3. Look for errors
4. Should see NO 404 errors for livewire.min.js
5. Login should work now
```

**Terminal:**
```bash
# Verify Livewire asset
curl https://samnghethaycu.com/vendor/livewire/livewire.min.js | head -c 100
# Should show: JavaScript code (starts with "!function...")

# Check file size
ls -lh public/vendor/livewire/livewire.min.js
# Should show: ~100-200KB file
```

✅ **Solution Applied:** Assets published and accessible

**📝 Prevention (Future Deployments):**

**Option 1: Add to deploy-sam script (RECOMMENDED)**

Edit `~/.bashrc` to add asset publishing to deploy-sam:

```bash
# After composer install, add:
echo "📦 Step 4B/8: Publishing assets..."
php artisan livewire:publish --assets --force > /dev/null 2>&1
php artisan filament:assets --force > /dev/null 2>&1
echo "✅ Assets published"
```

**Option 2: Manual publish after each deploy**

Sau mỗi lần chạy `deploy-sam`, run:
```bash
php artisan livewire:publish --assets && php artisan filament:assets
sudo chown -R www-data:www-data public/
```

**Option 3: Commit assets to Git (NOT RECOMMENDED)**

Remove from `.gitignore`:
```
# Comment out or remove these lines:
# /public/hot
# /public/storage
# /public/build
```

**⚠️ Warning:** Committing assets có thể gây permission conflicts giữa deploy user và www-data user.

---

## 📚 FILAMENT RESOURCES

### Official Documentation

- **Filament Docs (Latest)**: https://filamentphp.com/docs
- **Panels**: https://filamentphp.com/docs/panels
- **Tables**: https://filamentphp.com/docs/tables
- **Forms**: https://filamentphp.com/docs/forms
- **Actions**: https://filamentphp.com/docs/actions
- **Notifications**: https://filamentphp.com/docs/notifications

### Common Artisan Commands

```bash
# Create Filament resource (will use in WF-6)
php artisan make:filament-resource ModelName

# Create Filament user
php artisan make:filament-user

# Clear Filament cache
php artisan filament:optimize-clear

# Rebuild Filament assets
php artisan filament:assets

# List all Filament commands
php artisan list filament
```

### Filament Plugins (Future)

- **Spatie Media Library**: https://filamentphp.com/plugins/filament-spatie-media-library
- **Import**: https://filamentphp.com/plugins/konnco-import
- **Shield (Permissions)**: https://filamentphp.com/plugins/bezhansalleh-shield

---

**Created:** 2025-11-21
**Updated:** 2025-11-21 (Added asset publishing step + Issue 9)
**Version:** 4.2 Professional Vietnamese (Updated for Filament v4 + Asset Publishing)
**Time:** 12-17 minutes actual
**Format:** Standardized with WORKFLOW-2 v6.0, WORKFLOW-3 v4.0, and WORKFLOW-4 v4.0

---

**END OF WORKFLOW 5** 🎨
