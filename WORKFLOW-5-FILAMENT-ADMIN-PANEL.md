# 🎨 WORKFLOW 5: QUẢN TRỊ FILAMENT

> **Dự án:** samnghethaycu.com - E-Commerce Platform
> **Version:** 5.0 Professional Vietnamese (FIXED: Correct Order + Vietnamese Locale + No 403 Error)
> **Thời gian thực tế:** 14-20 phút
> **Mục tiêu:** Filament (latest) + Vietnamese UI + User Model + Admin user + Dashboard working

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

### BƯỚC 1.3: Configure Vietnamese Locale

**📍 Trên Windows:**

**⚠️ QUAN TRỌNG:** Thêm tiếng Việt ngay sau khi install để admin panel hiển thị tiếng Việt!

```powershell
# Open AdminPanelProvider
code app\Providers\Filament\AdminPanelProvider.php
```

**Xóa toàn bộ nội dung file cũ và thay thế bằng code mới bên dưới:**

**Copy TOÀN BỘ code này vào file `app\Providers\Filament\AdminPanelProvider.php`:**

```php
<?php

namespace App\Providers\Filament;

use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Pages\Dashboard;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Widgets\AccountWidget;
use Filament\Widgets\FilamentInfoWidget;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->path('admin')
            ->locale('vi')  // ← Vietnamese locale cho admin panel
            ->login()
            ->colors([
                'primary' => Color::Amber,
            ])
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->pages([
                Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets')
            ->widgets([
                AccountWidget::class,
                FilamentInfoWidget::class,
            ])
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->authMiddleware([
                Authenticate::class,
            ]);
    }
}
```

**📝 Lưu ý:**
- Dòng quan trọng nhất: `->locale('vi')` (dòng 30)
- Copy paste TOÀN BỘ code từ `<?php` đến dấu `}` cuối cùng

**Save file (Ctrl+S hoặc File → Save)**

✅ **Checkpoint 1.3:** Vietnamese locale configured

---

### BƯỚC 1.4: Verify Installation

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

# Verify Vietnamese locale added
Select-String -Path app\Providers\Filament\AdminPanelProvider.php -Pattern "locale\('vi'\)"

# Should show:         ->locale('vi')
```

✅ **Checkpoint 1.4:** Filament routes and Vietnamese locale verified

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
git commit -m "feat: install Filament v4 admin panel with Vietnamese locale"

# Push to GitHub
git push origin main
```

**Expected output:**

```
[main abc1234] feat: install Filament v4 admin panel with Vietnamese locale
 X files changed, XXX insertions(+), X deletions(-)
 create mode 100644 app/Providers/Filament/AdminPanelProvider.php
 create mode 100644 config/filament.php

Enumerating objects: X, done.
...
To https://github.com/phuochoavn/websamnghe.git
   abc1234..def5678  main -> main
```

✅ **Checkpoint 2.1:** Filament with Vietnamese locale pushed to GitHub

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

## PHẦN 4: CẤU HÌNH USER MODEL (BẮT BUỘC!)

**Thời gian:** 3 phút

**⚠️ CRITICAL:** Bước này là **BẮT BUỘC** phải làm TRƯỚC KHI tạo admin user! Nếu không, login sẽ gặp lỗi **403 Forbidden**.

**Tại sao bắt buộc?** User model phải implement `FilamentUser` interface và có method `canAccessPanel()` để Filament kiểm tra quyền truy cập.

### BƯỚC 4.1: Update User Model (Local)

**📍 Trên Windows:**

```powershell
# Open User model in your editor
code app\Models\User.php
# Or: notepad app\Models\User.php
```

**Update User.php với Filament interface:**

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

**Save file (Ctrl+S)**

✅ **Checkpoint 4.1:** User model updated locally

---

### BƯỚC 4.2: Commit & Push

**📍 Trên Windows:**

```powershell
# Check changes
git status
# Should show: modified: app/Models/User.php

# Add and commit
git add app\Models\User.php
git commit -m "feat: configure User model for Filament admin panel access control"

# Push to GitHub
git push origin main
```

**Expected output:**

```
[main abc1234] feat: configure User model for Filament admin panel access control
 1 file changed, XX insertions(+), X deletions(-)

To https://github.com/phuochoavn/websamnghe.git
   abc1234..def5678  main -> main
```

✅ **Checkpoint 4.2:** User model pushed to GitHub

---

### BƯỚC 4.3: Deploy to VPS

**📍 Trên VPS:**

```bash
# SSH if not already connected
ssh deploy@69.62.82.145

cd /var/www/samnghethaycu.com

# Deploy with automation script
deploy-sam
```

**Expected output:**

```
🚀 Starting deployment...
...
📥 Step 1/8: Pulling latest code from GitHub...
✅ Code updated
def5678 feat: configure User model for Filament admin panel access control
...
🎉 Deployment completed successfully!
```

✅ **Checkpoint 4.3:** User model deployed to VPS

---

## PHẦN 5: TẠO ADMIN USER

**Thời gian:** 2 phút

**⚠️ LƯU Ý:** Chỉ làm phần này SAU KHI đã hoàn thành PHẦN 4 (User model configured)!

### BƯỚC 5.1: Create Admin User

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
   INFO  Success! admin@samnghethaycu.com may now log in at https://samnghethaycu.com/admin/login.
```

✅ **Checkpoint 5.1:** Admin user created

---

### BƯỚC 5.2: Verify User in Database

**📍 Trên VPS:**

```bash
# Check user exists
php artisan tinker
```

**In tinker:**

```php
User::where('email', 'admin@samnghethaycu.com')->first();
# Should return User object with id, name, email

exit
```

**Expected output:**

```php
> User::where('email', 'admin@samnghethaycu.com')->first();
= App\Models\User {#6863
    id: 1,
    name: "Admin",
    email: "admin@samnghethaycu.com",
    ...
  }
```

✅ **Checkpoint 5.2:** Admin user verified in database

---

## PHẦN 6: TEST ADMIN PANEL

**Thời gian:** 3 phút

### BƯỚC 6.1: Access Admin Login Page

**📍 Browser:**

```
https://samnghethaycu.com/admin
```

**Should see:**
- ✅ Filament login page
- ✅ "Đăng nhập" heading (Vietnamese)
- ✅ Email and Password fields
- ✅ "Đăng nhập" button
- ✅ Professional Filament UI

✅ **Checkpoint 6.1:** Login page accessible

---

### BƯỚC 6.2: Login to Dashboard

**📍 Browser - Login credentials:**

```
Email: admin@samnghethaycu.com
Password: Admin@123456
```

**Click "Đăng nhập"**

**Should see:** 🎉 **Filament Dashboard!**

- ✅ "Trang tổng quan" heading (Vietnamese)
- ✅ Sidebar navigation (empty for now)
- ✅ User menu (top right with "Admin" name)
- ✅ Dark mode toggle
- ✅ Clean, professional interface
- ✅ NO 403 ERROR (because User model configured correctly)

**⚠️ If you see 403 Forbidden:** You forgot PHẦN 4! Go back and configure User model first!

✅ **Checkpoint 6.2:** Login successful (no 403 error!)

---

### BƯỚC 6.3: Explore Dashboard Features

**📍 Browser - Check these features:**

```
✅ Sidebar: Navigation menu (empty, will add resources in WF-6)
✅ User Menu: Click your name (top right)
   - "Hồ sơ" (Profile)
   - "Đăng xuất" (Logout)
✅ Dark Mode: Toggle dark/light mode (moon/sun icon)
✅ Dashboard: Main content area (empty widgets for now)
✅ Responsive: Resize browser window (mobile-friendly)
✅ Vietnamese interface
```

✅ **Checkpoint 6.3:** All features working

---

### BƯỚC 6.4: Test Logout

**📍 Browser:**

```
1. Click user menu (top right)
2. Click "Đăng xuất"
3. Should redirect to login page
4. Try login again - should work
```

✅ **Checkpoint 6.4:** Logout working

---

## ✅ VERIFICATION - HOÀN THÀNH WORKFLOW 5

### Full Workflow Checklist

**✅ Checklist - Filament Admin Panel (Correct Order!):**

```
PHẦN 1: CÀI ĐẶT FILAMENT (LOCAL)
✅ Filament v4 installed locally (composer require filament/filament -W)
✅ AdminPanelProvider created (php artisan filament:install --panels)
✅ Vietnamese locale configured (->locale('vi') in AdminPanelProvider)
✅ Code committed and pushed to GitHub

PHẦN 2 & 3: DEPLOY LÊN VPS
✅ Deployed to VPS with deploy-sam
✅ Livewire & Filament assets published on VPS (php artisan livewire:publish --assets)
✅ Assets accessible (livewire.min.js returns HTTP 200)

PHẦN 4: CẤU HÌNH USER MODEL (TRƯỚC KHI TẠO USER!)
✅ User model implements FilamentUser interface
✅ canAccessPanel() method added
✅ User model committed & deployed to VPS

PHẦN 5: TẠO ADMIN USER (SAU KHI CẤU HÌNH USER MODEL!)
✅ Admin user created (admin@samnghethaycu.com)
✅ User verified in database

PHẦN 6: TEST ADMIN PANEL
✅ Admin panel accessible at /admin
✅ Vietnamese interface displayed ("Đăng nhập", "Trang tổng quan")
✅ Can login successfully WITHOUT 403 ERROR
✅ Dashboard loads without errors
✅ User menu working ("Hồ sơ", "Đăng xuất")
✅ Dark mode toggle working
✅ Logout function working
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
✅ Vietnamese interface (Đăng nhập, Trang tổng quan, Hồ sơ, Đăng xuất)
✅ Admin panel at /admin with professional Vietnamese UI
✅ User Model implemented FilamentUser (NO 403 ERROR!)
✅ Access control via canAccessPanel() (@samnghethaycu.com domain only)
✅ Admin user (admin@samnghethaycu.com) working
✅ User authentication working without errors
✅ Dashboard accessible
✅ Dark mode toggle
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

**Thời gian:** 5-10 phút

**⚠️ CRITICAL ISSUES DISCOVERED:**
1. **Permission denied khi git pull**: File trong `public/` thuộc `www-data`, user `deploy` không xóa được
2. **Cache files còn tồn tại**: `bootstrap/cache/*.php` vẫn tìm `AdminPanelProvider.php` đã xóa
3. **Published assets không tự xóa**: Filament assets trong `public/` cần xóa thủ công với sudo

**📍 Trên VPS:**

```bash
# SSH to VPS
ssh deploy@69.62.82.145

cd /var/www/samnghethaycu.com
```

---

### **BƯỚC 8: Fix public/ Permissions (CRITICAL!)**

**⚠️ QUAN TRỌNG:** Phải fix permissions TRƯỚC KHI git pull, nếu không sẽ gặp lỗi "Permission denied"!

```bash
# Fix ownership of public/ directory
sudo chown -R deploy:www-data public/

# Verify ownership changed
ls -ld public/
# Expected: drwxr-xr-x ... deploy www-data ... public/
```

**Expected output:**

```bash
drwxr-xr-x 6 deploy www-data 4096 Nov 21 13:22 public/
```

✅ **Checkpoint 6:** Public directory ownership fixed

---

### **BƯỚC 9: Clear Bootstrap Cache Files (CRITICAL!)**

**⚠️ QUAN TRỌNG:** Phải clear cache files TRƯỚC KHI git pull để tránh lỗi ClassLoader!

```bash
# Delete bootstrap cache files (may contain references to AdminPanelProvider)
rm -f bootstrap/cache/services.php
rm -f bootstrap/cache/packages.php

# Or clear all .php cache files
sudo rm -rf bootstrap/cache/*.php

# Verify deletion
ls -la bootstrap/cache/
# Expected: Only .gitignore file remains
```

**Expected output:**

```bash
total 12
drwxrwxr-x 2 deploy www-data 4096 Nov 21 13:23 .
drwxr-xr-x 4 deploy www-data 4096 Nov 21 00:03 ..
-rw-r--r-- 1 deploy www-data   39 Nov 21 00:03 .gitignore
```

✅ **Checkpoint 7:** Bootstrap cache cleared

---

### **BƯỚC 10: Git Pull Changes from GitHub**

**📍 Trên VPS:**

```bash
# Pull latest changes from GitHub (rollback commit)
git reset --hard origin/main

# Or use git pull if you prefer
git pull origin main
```

**Expected output:**

```bash
HEAD is now at ffacf06 revert: remove Filament admin panel and restore to WORKFLOW-4 state
```

**⚠️ If you see "Permission denied" errors:**

```bash
error: unable to unlink old 'public/.htaccess': Permission denied
error: unable to unlink old 'public/css/filament/filament/app.css': Permission denied
...
```

**Fix:** Go back to BƯỚC 8 and run `sudo chown -R deploy:www-data public/` again!

✅ **Checkpoint 8:** Code pulled from GitHub

---

### **BƯỚC 11: Reinstall Composer Dependencies**

**📍 Trên VPS:**

```bash
# Reinstall dependencies (this will REMOVE 34 Filament packages)
composer install --no-dev --optimize-autoloader
```

**Expected output:**

```bash
Installing dependencies from lock file
Verifying lock file contents can be installed on current platform.
Package operations: 0 installs, 0 updates, 34 removals
  - Removing ueberdosis/tiptap-php (2.0.0)
  - Removing symfony/html-sanitizer (v7.3.6)
  - Removing spatie/shiki-php (2.3.2)
  ...
  - Removing livewire/livewire (v3.6.4)
  ...
  - Removing filament/widgets (v4.2.3)
  - Removing filament/tables (v4.2.3)
  - Removing filament/support (v4.2.3)
  - Removing filament/schemas (v4.2.3)
  - Removing filament/query-builder (v4.2.3)
  - Removing filament/notifications (v4.2.3)
  - Removing filament/infolists (v4.2.3)
  - Removing filament/forms (v4.2.3)
  - Removing filament/filament (v4.2.3)
  - Removing filament/actions (v4.2.3)
  ...
  - Removing blade-ui-kit/blade-icons (1.8.0)
  - Removing blade-ui-kit/blade-heroicons (2.6.0)
  ...
Generating optimized autoload files
> Illuminate\Foundation\ComposerScripts::postAutoloadDump
> @php artisan package:discover --ansi

   INFO  Discovering packages.

  laravel/tinker ................................................................................................ DONE
  nesbot/carbon ................................................................................................. DONE
  nunomaduro/termwind ........................................................................................... DONE

53 packages you are using are looking for funding.
```

**⚠️ If you see "Class BladeHeroiconsServiceProvider not found" error:**

This is normal! The package is being removed but cache still references it. Continue to next step to fix.

✅ **Checkpoint 9:** Composer dependencies reinstalled (34 packages removed)

---

### **BƯỚC 12: XÓA PUBLISHED ASSETS (CRITICAL!)**

**📍 Trên VPS:**

**⚠️ CRITICAL:** Đây là bước quan trọng nhất! Assets được publish với sudo permissions, không bị xóa tự động bởi composer!

```bash
cd /var/www/samnghethaycu.com

# List assets before deletion (to verify they exist)
ls -la public/vendor/livewire/ 2>/dev/null || echo "Livewire assets not found"
ls -la public/js/filament/ 2>/dev/null || echo "Filament JS not found"
ls -la public/css/filament/ 2>/dev/null || echo "Filament CSS not found"
ls -la public/fonts/filament/ 2>/dev/null || echo "Filament fonts not found"

# DELETE Livewire assets
sudo rm -rf public/vendor/livewire/

# DELETE Filament assets
sudo rm -rf public/js/filament/
sudo rm -rf public/css/filament/
sudo rm -rf public/fonts/filament/

# Verify deletion (should show empty or not found)
ls -la public/vendor/ 2>/dev/null
# Expected: Should NOT show livewire directory

# Test 404 (assets should return 404 Not Found)
curl -I https://samnghethaycu.com/vendor/livewire/livewire.min.js
# Expected: HTTP/2 404
```

**Expected output:**

```bash
# Before deletion:
drwxr-xr-x 2 deploy www-data   4096 Nov 21 10:50 public/vendor/livewire/
drwxr-xr-x 10 deploy www-data   4096 Nov 21 02:58 public/js/filament/
drwxr-xr-x 3 deploy www-data   4096 Nov 21 02:58 public/css/filament/
drwxr-xr-x 3 deploy www-data   4096 Nov 21 02:58 public/fonts/filament/

# After deletion:
total 8
drwxr-xr-x 2 deploy www-data 4096 Nov 21 13:27 public/vendor/
(empty - no livewire/)

# Curl test:
HTTP/2 404
server: nginx/1.24.0 (Ubuntu)
content-type: text/html
```

✅ **Checkpoint 10:** Published assets deleted from VPS

---

### **BƯỚC 13: Remove Admin User (Optional)**

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
// Expected: App\Models\User object or null

// Delete user
User::where('email', 'admin@samnghethaycu.com')->delete();
// Expected: 1 (1 row deleted)

// Verify deletion
User::where('email', 'admin@samnghethaycu.com')->count();
// Expected: 0

exit
```

**Expected output:**

```php
> $user = User::where('email', 'admin@samnghethaycu.com')->first();
= App\Models\User {#5976
    id: 1,
    name: "Admin",
    email: "admin@samnghethaycu.com",
    ...
  }

> User::where('email', 'admin@samnghethaycu.com')->delete();
= 1

> User::where('email', 'admin@samnghethaycu.com')->count();
= 0
```

✅ **Checkpoint 11:** Admin user deleted from database

---

### **BƯỚC 14: Rebuild Cache and Reload PHP-FPM**

**📍 Trên VPS:**

```bash
# Clear all caches
php artisan optimize:clear

# Rebuild caches (without Filament)
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Reload PHP-FPM to apply changes
sudo systemctl reload php8.4-fpm
```

**Expected output:**

```bash
# optimize:clear
   INFO  Clearing cached bootstrap files.

  config ................................................................................................. 0.87ms DONE
  cache .................................................................................................. 6.39ms DONE
  compiled ............................................................................................... 0.86ms DONE
  events ................................................................................................. 0.50ms DONE
  routes ................................................................................................. 0.44ms DONE
  views .................................................................................................. 8.05ms DONE

# config:cache
   INFO  Configuration cached successfully.

# route:cache
   INFO  Routes cached successfully.

# view:cache
   INFO  Blade templates cached successfully.
```

✅ **Checkpoint 12:** Caches rebuilt and PHP-FPM reloaded

---

### **PHẦN 3: VERIFICATION - HOÀN THÀNH ROLLBACK**

**Thời gian:** 2-3 phút

**📍 Trên VPS:**

```bash
# 1. Check Laravel version
php artisan --version
# Expected: Laravel Framework 12.39.0

# 2. Check Filament routes removed
php artisan route:list | grep admin
# Expected: (empty, no output)

# 3. Check Filament package removed
composer show | grep filament
# Expected: (empty, no output)

# 4. Verify published assets removed
ls -la public/vendor/livewire/ 2>/dev/null
# Expected: ls: cannot access 'public/vendor/livewire/': No such file or directory

# 5. Test Livewire JS returns 404
curl -I https://samnghethaycu.com/vendor/livewire/livewire.min.js
# Expected: HTTP/2 404

# 6. Check database connection still works
php artisan db:show
# Expected: Database info with 9 tables (users, cache, sessions, etc.)

# 7. Check routes (should only show Laravel default routes)
php artisan route:list
# Expected:
#   GET|HEAD       / ...............................
#   GET|HEAD       health ............................
#   GET|HEAD       storage/{path} .......... storage.local
#   GET|HEAD       up ................................
```

**Expected output from php artisan db:show:**

```bash
  MySQL ...................................................................................... 8.0.44-0ubuntu0.24.04.1
  Connection ................................................................................................... mysql
  Database ............................................................................................. samnghethaycu
  Host ..................................................................................................... 127.0.0.1
  Port .......................................................................................................... 3306
  Username ........................................................................................ samnghethaycu_user
  Tables ........................................................................................................... 9
  Total Size ............................................................................................... 160.00 KB

  Schema / Table ................................................................................................ Size
  samnghethaycu / cache ..................................................................................... 16.00 KB
  samnghethaycu / cache_locks ............................................................................... 16.00 KB
  samnghethaycu / failed_jobs ............................................................................... 16.00 KB
  samnghethaycu / job_batches ............................................................................... 16.00 KB
  samnghethaycu / jobs ...................................................................................... 16.00 KB
  samnghethaycu / migrations ................................................................................ 16.00 KB
  samnghethaycu / password_reset_tokens ..................................................................... 16.00 KB
  samnghethaycu / sessions .................................................................................. 16.00 KB
  samnghethaycu / users ..................................................................................... 32.00 KB
```

✅ **Checkpoint 13:** All VPS verifications passed

---

**📍 Browser Test:**

Open browser and test:

```
1. Visit: https://samnghethaycu.com
   Expected: Laravel welcome page ✅

2. Visit: https://samnghethaycu.com/admin
   Expected: 404 Not Found ✅

3. Open browser console (F12)
   Expected: No errors ✅

4. Check Network tab (F12 → Network)
   Expected: No failed requests to /vendor/livewire/* or /js/filament/* ✅
```

✅ **Checkpoint 14:** Browser tests passed

---

### **✅ ROLLBACK COMPLETE CHECKLIST:**

**LOCAL (Windows):**
```
✅ BƯỚC 1: Filament files deleted (AdminPanelProvider, config/filament.php)
✅ BƯỚC 2: Filament package removed (composer remove filament/filament -W)
✅ BƯỚC 3: filament:upgrade script removed from composer.json
✅ BƯỚC 4: Autoloader rebuilt (composer dump-autoload) - NO ERRORS
✅ BƯỚC 5: User model reverted (removed FilamentUser interface & canAccessPanel method)
✅ BƯỚC 6: Caches cleared locally (php artisan optimize:clear)
✅ BƯỚC 7: Local verification passed (no filament package, no admin routes)
✅ BƯỚC 8: Changes committed and pushed to GitHub
```

**VPS (Production):**
```
✅ BƯỚC 8: public/ permissions fixed (sudo chown -R deploy:www-data public/)
✅ BƯỚC 9: Bootstrap cache cleared (rm -f bootstrap/cache/*.php)
✅ BƯỚC 10: Code pulled from GitHub (git reset --hard origin/main)
✅ BƯỚC 11: Composer dependencies reinstalled (34 Filament packages removed)
✅ BƯỚC 12: Published assets deleted (livewire, filament JS/CSS/fonts)
✅ BƯỚC 13: Admin user deleted from database (optional)
✅ BƯỚC 14: Caches rebuilt and PHP-FPM reloaded
✅ BƯỚC 15: All verifications passed:
   ✅ No filament packages (composer show | grep filament)
   ✅ No admin routes (php artisan route:list | grep admin)
   ✅ Assets return 404 (curl livewire.min.js)
   ✅ Laravel welcome page working
   ✅ Admin panel inaccessible (404 at /admin)
   ✅ Database connection working (php artisan db:show)
```

**TOTAL TIME:** ~15-20 minutes (Local: 5-10 min, VPS: 5-10 min, Verification: 2-3 min)
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
