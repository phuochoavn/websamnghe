# 🎨 WORKFLOW 5: FILAMENT ADMIN PANEL

> **Dự án:** samnghethaycu.com - E-Commerce Platform
> **Version:** 3.0 Reorganized
> **Thời gian thực tế:** 10-15 phút
> **Mục tiêu:** Filament v3 + Admin user + Dashboard working

---

## 📋 PREREQUISITES

### ✅ Must Complete First

```
✅ WORKFLOW-1: VPS Infrastructure
✅ WORKFLOW-2: Laravel Installation
✅ WORKFLOW-3: Git Workflow Setup
✅ WORKFLOW-4: Deployment Automation
✅ Laravel working at: https://samnghethaycu.com
```

### ✅ Quick Verification

**Browser:**

```
https://samnghethaycu.com
```

**Should see:** Laravel welcome page

**SSH test:**

```bash
ssh deploy@69.62.82.145

cd /var/www/samnghethaycu.com

# Check Laravel
php artisan --version
# Should show: Laravel Framework 12.x.x

# Test deploy command
deploy-sam
# Should work
```

**All OK?** → Continue!

---

## 🎯 WHAT WE'LL BUILD

```
Laravel 12
    ↓
Install Filament v3 (local)
    ↓
Git commit & push
    ↓
Deploy to VPS (deploy-sam)
    ↓
Create admin user
    ↓
Result: https://samnghethaycu.com/admin ✅
```

---

## PART 1: INSTALL FILAMENT (LOCAL)

**Time:** 5 phút

**Windows PowerShell:**

```powershell
# Navigate to project
cd C:\Projects\samnghethaycu

# Install Filament v3
composer require filament/filament:"^3.2" -W

# This takes 1-2 minutes...
# Wait for completion
```

### 1.1. Install Admin Panel

```powershell
# Install Filament panels
php artisan filament:install --panels

# Prompt: "What is the ID of the panel you would like to create?"
# Answer: admin (press Enter)

# Creates:
# - app/Providers/Filament/AdminPanelProvider.php
# - config/filament.php
```

### 1.2. Verify Installation

```powershell
# Check if Filament routes exist
php artisan route:list | Select-String "admin"

# Should show multiple /admin/* routes
```

✅ **Checkpoint 1:** Filament installed locally

---

## PART 2: COMMIT & PUSH

**Time:** 1 phút

**PowerShell:**

```powershell
# Check changes
git status

# Add all changes
git add .

# Commit
git commit -m "feat: install Filament v3 admin panel with default configuration"

# Push to GitHub
git push origin main
```

✅ **Checkpoint 2:** Filament pushed to GitHub

---

## PART 3: DEPLOY TO VPS

**Time:** 2 phút

**SSH to VPS:**

```bash
ssh deploy@69.62.82.145

cd /var/www/samnghethaycu.com

# Deploy with our automation script!
deploy-sam

# This will:
# 1. Pull latest code
# 2. Install composer dependencies
# 3. Run migrations
# 4. Clear & rebuild caches
# 5. Fix permissions
# 6. Reload PHP-FPM
```

**Expected output:**

```
🚀 Starting deployment...
📥 Step 1/8: Pulling latest code from GitHub...
✅ Code updated
...
🎉 Deployment completed successfully!
```

✅ **Checkpoint 3:** Filament deployed to VPS

---

## PART 4: CREATE ADMIN USER

**Time:** 2 phút

**On VPS:**

```bash
cd /var/www/samnghethaycu.com

# Create Filament admin user
php artisan make:filament-user
```

**Prompts & Answers:**

```
Name: Admin
Email address: admin@samnghethaycu.com
Password: Admin@123456
```

**Success message:**

```
Success! admin@samnghethaycu.com may now log in at https://samnghethaycu.com/admin
```

✅ **Checkpoint 4:** Admin user created

---

## PART 5: TEST ADMIN PANEL

**Time:** 2 phút

### 5.1. Access Admin Panel

**Browser:**

```
https://samnghethaycu.com/admin
```

**Should see:** Filament login page

### 5.2. Login

**Credentials:**

```
Email: admin@samnghethaycu.com
Password: Admin@123456
```

**Click "Sign in"**

**Should see:** 🎉 **Filament Dashboard!**

### 5.3. Explore Dashboard

**Check:**

- ✅ Sidebar with navigation
- ✅ User menu (top right)
- ✅ Dashboard widgets area (empty for now)
- ✅ Dark mode toggle working

✅ **Checkpoint 5:** Admin panel working

---

## PART 6: CONFIGURE USER MODEL (Optional)

**Time:** 3 phút

**Why?** Make User model Filament-compatible with canAccessPanel() method.

### 6.1. Update User Model (Local)

**Windows PowerShell:**

```powershell
# Open User model
notepad app\Models\User.php
```

**Add Filament interface:**

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

    // ... existing code ...

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

**Save**

### 6.2. Commit & Deploy

```powershell
git add app\Models\User.php
git commit -m "feat: configure User model for Filament admin panel access"
git push origin main
```

**On VPS:**

```bash
deploy-sam
```

✅ **Checkpoint 6:** User model configured

---

## VERIFICATION

### Final Checklist

- [ ] Admin panel accessible: https://samnghethaycu.com/admin ✅
- [ ] Can login with admin credentials ✅
- [ ] Dashboard loads without errors ✅
- [ ] User menu working ✅
- [ ] Logout function working ✅
- [ ] Git deployment tested ✅

**All checked?** → SUCCESS! 🎉

---

## ✅ WORKFLOW 4 COMPLETE!

### Filament Ready:

```
✅ Filament v3 installed
✅ Admin panel configured at /admin
✅ Admin user created
✅ Dashboard accessible
✅ User authentication working
✅ Dark mode available
✅ Deployed via Git
```

### Admin Credentials:

```
URL: https://samnghethaycu.com/admin
Email: admin@samnghethaycu.com
Password: Admin@123456
```

### Current Features:

```
✅ User authentication
✅ Dashboard (empty widgets)
✅ User profile management
✅ Dark mode toggle
✅ Responsive design
```

### Next Steps:

```
→ WORKFLOW-6-DATABASE-SCHEMA.md
  Create database tables and basic models
  Generate Filament resources for CRUD operations
```

---

## 🔧 TROUBLESHOOTING

### Issue: Cannot access /admin (404 error)

**Fix:**

```bash
cd /var/www/samnghethaycu.com

# Clear route cache
php artisan route:clear
php artisan route:cache

# Verify admin routes exist
php artisan route:list | grep admin

# Restart PHP-FPM
sudo systemctl restart php8.4-fpm
```

### Issue: "Class FilamentUser not found"

**Fix:**

```bash
# Install missing Filament dependencies
composer require filament/filament:"^3.2" -W

# Rebuild autoloader
composer dump-autoload

# Clear all caches
php artisan optimize:clear
```

### Issue: Login but dashboard shows errors

**Check logs:**

```bash
# Laravel log
tail -50 storage/logs/laravel.log

# Nginx error log
sudo tail -50 /var/log/nginx/samnghethaycu-error.log
```

**Common fix:**

```bash
# Clear Filament cache
php artisan filament:optimize-clear

# Rebuild caches
php artisan optimize
```

### Issue: Cannot create admin user

**Error:** "Database connection failed"

**Fix:**

```bash
# Test database connection
php artisan db:show

# Check .env
cat .env | grep DB_

# Test MySQL connection
mysql -u samnghethaycu_user -p samnghethaycu
```

### Issue: Admin user exists but cannot login

**Reset password:**

```bash
cd /var/www/samnghethaycu.com

php artisan tinker
```

**In tinker:**

```php
$user = App\Models\User::where('email', 'admin@samnghethaycu.com')->first();
$user->password = bcrypt('Admin@123456');
$user->save();
exit
```

---

## 📚 FILAMENT RESOURCES

### Official Documentation

- Filament v3 Docs: https://filamentphp.com/docs/3.x
- Panels: https://filamentphp.com/docs/3.x/panels
- Tables: https://filamentphp.com/docs/3.x/tables
- Forms: https://filamentphp.com/docs/3.x/forms

### Common Commands

```bash
# Create resource
php artisan make:filament-resource ModelName

# Create user
php artisan make:filament-user

# Clear Filament cache
php artisan filament:optimize-clear

# List all Filament commands
php artisan list filament
```

---

**Created:** 2025-11-16
**Version:** 4.0 Modular
**Time:** 10-15 minutes actual

---

**END OF WORKFLOW 4** 🎨
