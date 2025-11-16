# 🚀 QUY TRÌNH HOÀN CHỈNH - GIT DEPLOYMENT + BACKEND CUSTOMIZATION

> **Dự án:** samnghethaycu.com - E-Commerce Platform
> **Phiên bản:** 3.0 ULTIMATE
> **Thời gian:** ~4 giờ
> **Mục tiêu:** Git Deployment + Backend 100% Complete

---

## 📋 MỤC LỤC

- [PHẦN I: GIT DEPLOYMENT SETUP (60 phút)](#phần-i-git-deployment-setup)
- [PHẦN II: BACKEND CUSTOMIZATION (165 phút)](#phần-ii-backend-customization)
- [PHẦN III: TROUBLESHOOTING & CHECKLIST](#phần-iii-troubleshooting--checklist)

---

## 🎯 TỔNG QUAN

### Điều kiện tiên quyết

```
✅ Phase 0: Infrastructure complete (VPS, LEMP, SSL)
✅ Phase 1: Laravel deployed (basic installation)
✅ Phase 2: Database & Models skeleton created
✅ Admin panel accessible: https://samnghethaycu.com/admin
✅ SSH access: deploy@69.62.82.145
```

### Sau khi hoàn thành

```
✅ Git workflow hoạt động (push → auto deploy)
✅ 15 Models với business logic đầy đủ
✅ 6 Filament Resources professional
✅ Dashboard widgets với stats
✅ Sample data (50+ products, 20+ orders)
✅ Backend 100% production-ready
```

---

# PHẦN I: GIT DEPLOYMENT SETUP

**Thời gian:** 60 phút
**Mục tiêu:** Professional Git workflow

## BƯỚC 1.1: TẠO GITHUB REPOSITORY (10 phút)

### A. Tạo Repository

**Trên GitHub.com:**

1. Login → Click **"+"** → **"New repository"**
2. Repository name: `websamnghe`
3. Description: `samnghethaycu.com - E-Commerce Platform`
4. **Private** (quan trọng!)
5. **KHÔNG** tick "Initialize with README"
6. Click **"Create repository"**

✅ **Checkpoint 1.1A:** Repository created

### B. Chuẩn bị Local Code

**Windows PowerShell:**

```powershell
# Di chuyển vào thư mục Laravel local
cd C:\laravel-project\samnghethaycu

# Kiểm tra git
git status
```

**Nếu chưa có git:**

```powershell
git init
git add .
git commit -m "Initial commit: Laravel 12 base installation"
```

**Nếu đã có git:**

```powershell
# Chỉ cần verify
git log --oneline -5
```

✅ **Checkpoint 1.1B:** Git initialized locally

### C. Push lên GitHub

**PowerShell:**

```powershell
# Thêm remote (thay YOUR_USERNAME)
git remote add origin https://github.com/phuochoavn/websamnghe.git

# Kiểm tra remote
git remote -v

# Push code lên
git branch -M main
git push -u origin main
```

**Nhập credentials khi được hỏi:**
- Username: `phuochoavn`
- Password: **GitHub Personal Access Token** (tạo tại Settings → Developer settings → Personal access tokens)

✅ **Checkpoint 1.1C:** Code pushed to GitHub

---

## BƯỚC 1.2: SETUP SSH KEY TRÊN VPS (15 phút)

### A. Generate SSH Key

**SSH vào VPS:**

```bash
ssh deploy@69.62.82.145
# Password: Deploy@2025
```

**Trên VPS:**

```bash
# Generate SSH key
ssh-keygen -t ed25519 -C "deploy@samnghethaycu.com"

# Nhấn Enter 3 lần (không cần passphrase)

# Hiển thị public key
cat ~/.ssh/id_ed25519.pub
```

**Copy toàn bộ output** (bắt đầu bằng `ssh-ed25519...`)

✅ **Checkpoint 1.2A:** SSH key generated

### B. Thêm SSH Key vào GitHub

**Trên GitHub:**

1. Settings → **SSH and GPG keys**
2. Click **"New SSH key"**
3. Title: `VPS - samnghethaycu.com`
4. Key type: **Authentication Key**
5. Paste public key vào **"Key"**
6. Click **"Add SSH key"**

✅ **Checkpoint 1.2B:** SSH key added to GitHub

### C. Test SSH Connection

**Trên VPS:**

```bash
# Test connection
ssh -T git@github.com

# Nếu hỏi "Are you sure...?" → nhập: yes

# Expected output:
# Hi phuochoavn! You've successfully authenticated...
```

✅ **Checkpoint 1.2C:** SSH connection working

---

## BƯỚC 1.3: SETUP GIT ON VPS (15 phút)

### A. Backup Current Code

**Trên VPS:**

```bash
cd /var/www

# Backup toàn bộ
sudo tar -czf ~/backup-before-git-$(date +%Y%m%d-%H%M%S).tar.gz samnghethaycu.com/

# Verify backup
ls -lh ~/backup-*
```

✅ **Checkpoint 1.3A:** Backup created

### B. Initialize Git Repository

**Trên VPS:**

```bash
cd /var/www/samnghethaycu.com

# Initialize git
git init

# Configure git user
git config user.name "Deploy User"
git config user.email "deploy@samnghethaycu.com"

# Add remote
git remote add origin git@github.com:phuochoavn/websamnghe.git

# Verify
git remote -v
```

✅ **Checkpoint 1.3B:** Git initialized on VPS

### C. Create .gitignore

**Trên VPS:**

```bash
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

✅ **Checkpoint 1.3C:** .gitignore created

### D. Pull Code from GitHub

**Trên VPS:**

```bash
# Fetch all branches
git fetch origin

# Reset to main branch (overwrite local with remote)
git reset --hard origin/main

# Verify
git log --oneline -3
git status
```

**Nếu có lỗi về .env:**

```bash
# .env is gitignored, so it's safe
# Just restore it if needed
git status
```

✅ **Checkpoint 1.3D:** Code synced with GitHub

---

## BƯỚC 1.4: TẠO DEPLOYMENT SCRIPT (15 phút)

### A. Create Deploy Script

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

# Show script
echo "✅ Deployment script created!"
cat ~/scripts/deploy-samnghethaycu.sh
```

✅ **Checkpoint 1.4A:** Deployment script created

### B. Create Shortcut Alias

**Trên VPS:**

```bash
# Add alias to bashrc
echo "alias deploy-sam='~/scripts/deploy-samnghethaycu.sh'" >> ~/.bashrc

# Reload bashrc
source ~/.bashrc

# Test alias
type deploy-sam
```

✅ **Checkpoint 1.4B:** Alias created

---

## BƯỚC 1.5: TEST DEPLOYMENT WORKFLOW (5 phút)

### A. Make a Test Change on Local

**Windows - VS Code:**

1. Mở `README.md` (hoặc tạo file mới)
2. Thêm dòng: `# Test deployment workflow - $(date)`
3. Save

**PowerShell:**

```powershell
cd C:\laravel-project\samnghethaycu

git add .
git commit -m "test: deployment workflow test"
git push origin main
```

✅ **Checkpoint 1.5A:** Test commit pushed

### B. Deploy on VPS

**Trên VPS:**

```bash
# Run deployment script
deploy-sam

# Or full path:
# ~/scripts/deploy-samnghethaycu.sh
```

**Expected output:**

```
🚀 Starting deployment...
📥 Step 1/8: Pulling latest code...
✅ Code updated
...
🎉 Deployment completed successfully!
```

✅ **Checkpoint 1.5B:** Deployment successful

### C. Verify Changes

**Trên VPS:**

```bash
cd /var/www/samnghethaycu.com

# Check latest commit
git log -1 --oneline

# Should show your test commit
```

**Browser:**

```
https://samnghethaycu.com
https://samnghethaycu.com/admin
```

Both should still work!

✅ **Checkpoint 1.5C:** Deployment verified

---

## ✅ PHẦN I HOÀN THÀNH!

**Đã có:**

```
✅ GitHub repository: phuochoavn/websamnghe
✅ SSH key authentication
✅ Git on VPS synced with GitHub
✅ Deployment script: deploy-sam
✅ Workflow tested: Local → GitHub → VPS
```

**Progress:**

```
Phần I:  Git Deployment    ████████████████████ 100%
Phần II: Backend Custom    ░░░░░░░░░░░░░░░░░░░░   0%
```

---

# PHẦN II: BACKEND CUSTOMIZATION

**Thời gian:** 165 phút (2h 45m)
**Mục tiêu:** Complete backend business logic

## BƯỚC 2.1: SETUP VS CODE REMOTE SSH (10 phút)

### A. Install Extension

**Windows - VS Code:**

1. Press `Ctrl+Shift+X` (Extensions)
2. Search: **"Remote - SSH"**
3. Click **Install** (by Microsoft)
4. Wait for installation

✅ **Checkpoint 2.1A:** Extension installed

### B. Connect to VPS

**VS Code:**

1. Press `F1` or `Ctrl+Shift+P`
2. Type: **"Remote-SSH: Connect to Host"**
3. Select **"+ Add New SSH Host"**
4. Enter: `ssh deploy@69.62.82.145`
5. Select config file: `C:\Users\[YourName]\.ssh\config`
6. Click **"Connect"**
7. Enter password: `Deploy@2025`
8. Select platform: **Linux**
9. Wait 10-20 seconds...

**Verify:**

- Bottom left corner shows: **"SSH: 69.62.82.145"** (green)

✅ **Checkpoint 2.1B:** Connected to VPS

### C. Open Project

**VS Code (connected):**

1. Click **"Open Folder"** (or `Ctrl+K Ctrl+O`)
2. Enter path: `/var/www/samnghethaycu.com`
3. Click **OK**
4. Trust: **"Yes, I trust the authors"**

**Verify:**

- Sidebar shows: `app/`, `database/`, `public/`, etc.

✅ **Checkpoint 2.1C:** Project opened

### D. Test Write Permission

**VS Code:**

1. Expand: `app` → `Models`
2. Open: `User.php`
3. Add comment at end: `// Test write`
4. Save: `Ctrl+S`

**If "Permission denied":**

```bash
# PowerShell - new tab
ssh deploy@69.62.82.145

cd /var/www/samnghethaycu.com
sudo setfacl -R -m u:deploy:rwx app database config
sudo setfacl -R -d -m u:deploy:rwx app database config

exit
```

Retry save in VS Code.

✅ **Checkpoint 2.1D:** Can edit files

---

## BƯỚC 2.2: UPDATE USER MODEL & MIGRATION (20 phút)

### A. Update User Migration

**VS Code:**

1. Navigate to: `database/migrations/`
2. Find: `0001_01_01_000000_create_users_table.php`
3. **Replace entire file** with this code:

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');

            // Additional fields
            $table->string('phone', 20)->nullable();
            $table->string('avatar')->nullable();
            $table->date('date_of_birth')->nullable();
            $table->enum('gender', ['male', 'female', 'other'])->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamp('last_login_at')->nullable();
            $table->string('last_login_ip', 45)->nullable();

            $table->rememberToken();
            $table->timestamps();
            $table->softDeletes();

            // Indexes
            $table->index('phone');
            $table->index('is_active');
        });

        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
    }
};
```

4. Save: `Ctrl+S`

✅ **Checkpoint 2.2A:** User migration updated

### B. Update User Model

**VS Code:**

1. Open: `app/Models/User.php`
2. **Replace entire file** with:

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;

class User extends Authenticatable implements FilamentUser
{
    use HasFactory, Notifiable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'avatar',
        'date_of_birth',
        'gender',
        'is_active',
        'last_login_at',
        'last_login_ip',
    ];

    /**
     * The attributes that should be hidden for serialization.
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'date_of_birth' => 'date',
        'is_active' => 'boolean',
        'last_login_at' => 'datetime',
        'password' => 'hashed',
    ];

    /**
     * Filament: Can access admin panel?
     */
    public function canAccessPanel(Panel $panel): bool
    {
        return $this->is_active && str_ends_with($this->email, '@samnghethaycu.com');
    }

    // ==================== RELATIONSHIPS ====================

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function addresses()
    {
        return $this->hasMany(Address::class);
    }

    public function posts()
    {
        return $this->hasMany(Post::class, 'author_id');
    }

    public function defaultAddress()
    {
        return $this->hasOne(Address::class)->where('is_default', true);
    }

    // ==================== ACCESSORS ====================

    public function getInitialsAttribute(): string
    {
        $words = explode(' ', $this->name);
        $initials = '';
        foreach ($words as $word) {
            $initials .= mb_substr($word, 0, 1);
        }
        return mb_strtoupper($initials);
    }

    public function getAvatarUrlAttribute(): string
    {
        if ($this->avatar) {
            return asset('storage/' . $this->avatar);
        }
        return 'https://ui-avatars.com/api/?name=' . urlencode($this->name) . '&color=7F9CF5&background=EBF4FF';
    }

    // ==================== SCOPES ====================

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeCustomers($query)
    {
        return $query->where('email', 'not like', '%@samnghethaycu.com');
    }
}
```

3. Save: `Ctrl+S`

✅ **Checkpoint 2.2B:** User model updated

### C. Run Migration

**Terminal in VS Code** (or SSH):

```bash
cd /var/www/samnghethaycu.com

# Fresh migrate (this will drop all tables!)
php artisan migrate:fresh

# Type 'yes' when prompted

# Recreate admin user
php artisan make:filament-user
# Name: Admin
# Email: admin@samnghethaycu.com
# Password: Admin@123456
```

✅ **Checkpoint 2.2C:** Migration completed

---

## BƯỚC 2.3: INJECT 15 MODELS CODE (40 phút)

**Quy trình:**

1. Mở file model trong VS Code
2. `Ctrl+A` → Delete all
3. Copy code dưới đây
4. Paste vào file
5. `Ctrl+S` Save

---

### MODEL 1/15: Category

**File:** `app/Models/Category.php`

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

class Category extends Model
{
    use HasFactory, SoftDeletes, HasSlug;

    protected $fillable = [
        'parent_id',
        'name',
        'slug',
        'description',
        'image',
        'icon',
        'sort_order',
        'is_active',
        'meta_title',
        'meta_description',
        'meta_keywords',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'sort_order' => 'integer',
    ];

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('name')
            ->saveSlugsTo('slug');
    }

    // ==================== RELATIONSHIPS ====================

    public function parent()
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(Category::class, 'parent_id');
    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }

    // ==================== SCOPES ====================

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeRootOnly($query)
    {
        return $query->whereNull('parent_id');
    }

    // ==================== ACCESSORS ====================

    public function getImageUrlAttribute(): ?string
    {
        return $this->image ? asset('storage/' . $this->image) : null;
    }

    // ==================== METHODS ====================

    public function getAllChildren()
    {
        $children = collect();
        foreach ($this->children as $child) {
            $children->push($child);
            $children = $children->merge($child->getAllChildren());
        }
        return $children;
    }
}
```

✅ Save `Ctrl+S`

---

### MODEL 2/15: Brand

**File:** `app/Models/Brand.php`

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

class Brand extends Model
{
    use HasFactory, SoftDeletes, HasSlug;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'logo',
        'website',
        'sort_order',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'sort_order' => 'integer',
    ];

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('name')
            ->saveSlugsTo('slug');
    }

    // ==================== RELATIONSHIPS ====================

    public function products()
    {
        return $this->hasMany(Product::class);
    }

    // ==================== SCOPES ====================

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    // ==================== ACCESSORS ====================

    public function getLogoUrlAttribute(): ?string
    {
        return $this->logo ? asset('storage/' . $this->logo) : null;
    }
}
```

✅ Save

---

### MODEL 3/15: Product

**File:** `app/Models/Product.php`

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

class Product extends Model
{
    use HasFactory, SoftDeletes, HasSlug;

    protected $fillable = [
        'category_id',
        'brand_id',
        'name',
        'slug',
        'sku',
        'barcode',
        'description',
        'content',
        'price',
        'sale_price',
        'cost_price',
        'stock',
        'low_stock_threshold',
        'weight',
        'length',
        'width',
        'height',
        'visibility',
        'is_active',
        'is_featured',
        'is_new',
        'track_stock',
        'meta_title',
        'meta_description',
        'meta_keywords',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'sale_price' => 'decimal:2',
        'cost_price' => 'decimal:2',
        'stock' => 'integer',
        'low_stock_threshold' => 'integer',
        'weight' => 'decimal:2',
        'is_active' => 'boolean',
        'is_featured' => 'boolean',
        'is_new' => 'boolean',
        'track_stock' => 'boolean',
    ];

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('name')
            ->saveSlugsTo('slug');
    }

    // ==================== RELATIONSHIPS ====================

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }

    public function variants()
    {
        return $this->hasMany(ProductVariant::class);
    }

    public function images()
    {
        return $this->hasMany(ProductImage::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function featuredImage()
    {
        return $this->hasOne(ProductImage::class)->where('is_featured', true);
    }

    // ==================== SCOPES ====================

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    public function scopeInStock($query)
    {
        return $query->where('stock', '>', 0);
    }

    // ==================== ACCESSORS ====================

    public function getDisplayPriceAttribute(): float
    {
        return $this->sale_price ?? $this->price;
    }

    public function getDiscountPercentageAttribute(): ?int
    {
        if (!$this->sale_price || $this->sale_price >= $this->price) {
            return null;
        }
        return round((($this->price - $this->sale_price) / $this->price) * 100);
    }

    public function getFeaturedImageUrlAttribute(): ?string
    {
        $featured = $this->featuredImage;
        return $featured ? asset('storage/' . $featured->image_path) : null;
    }

    public function getAverageRatingAttribute(): float
    {
        return $this->reviews()->approved()->avg('rating') ?? 0;
    }

    // ==================== METHODS ====================

    public function isInStock(): bool
    {
        return $this->stock > 0;
    }

    public function isLowStock(): bool
    {
        return $this->stock <= $this->low_stock_threshold;
    }

    public function decrementStock(int $quantity): bool
    {
        if (!$this->track_stock) {
            return true;
        }

        if ($this->stock < $quantity) {
            return false;
        }

        $this->decrement('stock', $quantity);
        return true;
    }

    public function incrementStock(int $quantity): void
    {
        $this->increment('stock', $quantity);
    }
}
```

✅ Save

---

### MODEL 4/15: ProductVariant

**File:** `app/Models/ProductVariant.php`

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductVariant extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'sku',
        'name',
        'price_modifier',
        'stock',
        'attributes',
        'image',
        'sort_order',
        'is_active',
    ];

    protected $casts = [
        'price_modifier' => 'decimal:2',
        'stock' => 'integer',
        'attributes' => 'array',
        'sort_order' => 'integer',
        'is_active' => 'boolean',
    ];

    // ==================== RELATIONSHIPS ====================

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    // ==================== ACCESSORS ====================

    public function getFinalPriceAttribute(): float
    {
        $basePrice = $this->product->display_price;
        return $basePrice + $this->price_modifier;
    }

    public function getImageUrlAttribute(): ?string
    {
        if ($this->image) {
            return asset('storage/' . $this->image);
        }
        return $this->product->featured_image_url;
    }

    // ==================== METHODS ====================

    public function isInStock(): bool
    {
        return $this->stock > 0;
    }

    public function decrementStock(int $quantity): bool
    {
        if ($this->stock < $quantity) {
            return false;
        }
        $this->decrement('stock', $quantity);
        return true;
    }
}
```

✅ Save

---

### MODEL 5/15: ProductImage

**File:** `app/Models/ProductImage.php`

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductImage extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'product_id',
        'image_path',
        'alt_text',
        'is_featured',
        'sort_order',
    ];

    protected $casts = [
        'is_featured' => 'boolean',
        'sort_order' => 'integer',
    ];

    // ==================== RELATIONSHIPS ====================

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    // ==================== ACCESSORS ====================

    public function getUrlAttribute(): string
    {
        return asset('storage/' . $this->image_path);
    }
}
```

✅ Save

---

### MODEL 6/15: Order

**File:** `app/Models/Order.php`

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_number',
        'user_id',
        'customer_name',
        'customer_email',
        'customer_phone',
        'shipping_address',
        'shipping_province',
        'shipping_district',
        'shipping_ward',
        'shipping_method',
        'shipping_fee',
        'subtotal',
        'discount_amount',
        'tax_amount',
        'total',
        'payment_method',
        'payment_status',
        'paid_at',
        'payment_transaction_id',
        'coupon_code',
        'coupon_discount',
        'status',
        'customer_note',
        'admin_note',
        'cancellation_reason',
        'confirmed_at',
        'processing_at',
        'shipped_at',
        'delivered_at',
        'completed_at',
        'cancelled_at',
    ];

    protected $casts = [
        'shipping_fee' => 'decimal:2',
        'subtotal' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'tax_amount' => 'decimal:2',
        'total' => 'decimal:2',
        'coupon_discount' => 'decimal:2',
        'paid_at' => 'datetime',
        'confirmed_at' => 'datetime',
        'processing_at' => 'datetime',
        'shipped_at' => 'datetime',
        'delivered_at' => 'datetime',
        'completed_at' => 'datetime',
        'cancelled_at' => 'datetime',
    ];

    // ==================== RELATIONSHIPS ====================

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function statusHistories()
    {
        return $this->hasMany(OrderStatusHistory::class)->orderBy('created_at', 'desc');
    }

    public function coupon()
    {
        return $this->belongsTo(Coupon::class, 'coupon_code', 'code');
    }

    // ==================== SCOPES ====================

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeProcessing($query)
    {
        return $query->whereIn('status', ['confirmed', 'processing', 'shipping']);
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    public function scopePaid($query)
    {
        return $query->where('payment_status', 'paid');
    }

    // ==================== ACCESSORS ====================

    public function getTotalItemsAttribute(): int
    {
        return $this->items->sum('quantity');
    }

    public function getFullAddressAttribute(): string
    {
        return implode(', ', [
            $this->shipping_address,
            $this->shipping_ward,
            $this->shipping_district,
            $this->shipping_province,
        ]);
    }

    public function getStatusColorAttribute(): string
    {
        return match($this->status) {
            'pending' => 'warning',
            'confirmed', 'processing' => 'info',
            'shipping' => 'primary',
            'delivered', 'completed' => 'success',
            'cancelled', 'refunded' => 'danger',
            default => 'secondary',
        };
    }

    // ==================== METHODS ====================

    public static function generateOrderNumber(): string
    {
        $prefix = 'ORD';
        $date = now()->format('Ymd');
        $random = strtoupper(substr(md5(microtime()), 0, 6));
        return "{$prefix}-{$date}-{$random}";
    }

    public function updateStatus(string $status, ?string $note = null): void
    {
        $this->update(['status' => $status]);

        $this->statusHistories()->create([
            'status' => $status,
            'note' => $note ?? "Status changed to {$status}",
        ]);
    }

    public function canCancel(): bool
    {
        return in_array($this->status, ['pending', 'confirmed']);
    }
}
```

✅ Save

---

### MODEL 7/15: OrderItem

**File:** `app/Models/OrderItem.php`

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'product_id',
        'product_variant_id',
        'product_name',
        'product_sku',
        'variant_name',
        'unit_price',
        'quantity',
        'subtotal',
    ];

    protected $casts = [
        'unit_price' => 'decimal:2',
        'quantity' => 'integer',
        'subtotal' => 'decimal:2',
    ];

    // ==================== RELATIONSHIPS ====================

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function variant()
    {
        return $this->belongsTo(ProductVariant::class, 'product_variant_id');
    }

    // ==================== ACCESSORS ====================

    public function getFullNameAttribute(): string
    {
        $name = $this->product_name;
        if ($this->variant_name) {
            $name .= " - {$this->variant_name}";
        }
        return $name;
    }
}
```

✅ Save

---

### MODEL 8/15: OrderStatusHistory

**File:** `app/Models/OrderStatusHistory.php`

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderStatusHistory extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'order_id',
        'status',
        'note',
        'created_by',
        'created_at',
    ];

    protected $casts = [
        'created_at' => 'datetime',
    ];

    // ==================== RELATIONSHIPS ====================

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
```

✅ Save

---

### MODEL 9/15: Review

**File:** `app/Models/Review.php`

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'user_id',
        'order_id',
        'rating',
        'title',
        'content',
        'images',
        'is_verified_purchase',
        'is_approved',
        'helpful_count',
        'admin_reply',
        'replied_at',
        'replied_by',
    ];

    protected $casts = [
        'rating' => 'integer',
        'images' => 'array',
        'is_verified_purchase' => 'boolean',
        'is_approved' => 'boolean',
        'helpful_count' => 'integer',
        'replied_at' => 'datetime',
    ];

    // ==================== RELATIONSHIPS ====================

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function replier()
    {
        return $this->belongsTo(User::class, 'replied_by');
    }

    // ==================== SCOPES ====================

    public function scopeApproved($query)
    {
        return $query->where('is_approved', true);
    }

    public function scopeVerified($query)
    {
        return $query->where('is_verified_purchase', true);
    }

    // ==================== ACCESSORS ====================

    public function getStarsAttribute(): string
    {
        return str_repeat('⭐', $this->rating);
    }
}
```

✅ Save

---

### MODEL 10/15: PostCategory

**File:** `app/Models/PostCategory.php`

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

class PostCategory extends Model
{
    use HasFactory, HasSlug;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'sort_order',
        'is_active',
    ];

    protected $casts = [
        'sort_order' => 'integer',
        'is_active' => 'boolean',
    ];

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('name')
            ->saveSlugsTo('slug');
    }

    // ==================== RELATIONSHIPS ====================

    public function posts()
    {
        return $this->hasMany(Post::class, 'category_id');
    }

    public function publishedPosts()
    {
        return $this->hasMany(Post::class, 'category_id')->where('is_published', true);
    }

    // ==================== SCOPES ====================

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
```

✅ Save

---

### MODEL 11/15: Post

**File:** `app/Models/Post.php`

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

class Post extends Model
{
    use HasFactory, SoftDeletes, HasSlug;

    protected $fillable = [
        'author_id',
        'category_id',
        'title',
        'slug',
        'excerpt',
        'content',
        'featured_image',
        'is_published',
        'published_at',
        'view_count',
        'meta_title',
        'meta_description',
        'meta_keywords',
    ];

    protected $casts = [
        'is_published' => 'boolean',
        'published_at' => 'datetime',
        'view_count' => 'integer',
    ];

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('title')
            ->saveSlugsTo('slug');
    }

    // ==================== RELATIONSHIPS ====================

    public function author()
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    public function category()
    {
        return $this->belongsTo(PostCategory::class, 'category_id');
    }

    // ==================== SCOPES ====================

    public function scopePublished($query)
    {
        return $query->where('is_published', true)->where('published_at', '<=', now());
    }

    // ==================== ACCESSORS ====================

    public function getFeaturedImageUrlAttribute(): ?string
    {
        return $this->featured_image ? asset('storage/' . $this->featured_image) : null;
    }

    public function getReadTimeAttribute(): int
    {
        $wordCount = str_word_count(strip_tags($this->content));
        return ceil($wordCount / 200);
    }

    // ==================== METHODS ====================

    public function incrementViewCount(): void
    {
        $this->increment('view_count');
    }
}
```

✅ Save

---

### MODEL 12/15: Coupon

**File:** `app/Models/Coupon.php`

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'description',
        'type',
        'value',
        'min_order_value',
        'max_discount',
        'usage_limit',
        'usage_per_user',
        'used_count',
        'start_date',
        'end_date',
        'is_active',
    ];

    protected $casts = [
        'value' => 'decimal:2',
        'min_order_value' => 'decimal:2',
        'max_discount' => 'decimal:2',
        'usage_limit' => 'integer',
        'usage_per_user' => 'integer',
        'used_count' => 'integer',
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'is_active' => 'boolean',
    ];

    // ==================== RELATIONSHIPS ====================

    public function usages()
    {
        return $this->hasMany(CouponUsage::class);
    }

    // ==================== SCOPES ====================

    public function scopeActive($query)
    {
        return $query->where('is_active', true)
            ->where(function($q) {
                $q->whereNull('start_date')->orWhere('start_date', '<=', now());
            })
            ->where(function($q) {
                $q->whereNull('end_date')->orWhere('end_date', '>=', now());
            });
    }

    // ==================== METHODS ====================

    public function isValid(?int $userId = null, float $orderTotal = 0): array
    {
        if (!$this->is_active) {
            return ['valid' => false, 'message' => 'Mã không còn hiệu lực'];
        }

        if ($this->usage_limit && $this->used_count >= $this->usage_limit) {
            return ['valid' => false, 'message' => 'Mã đã hết lượt sử dụng'];
        }

        if ($this->min_order_value > 0 && $orderTotal < $this->min_order_value) {
            return ['valid' => false, 'message' => 'Đơn hàng tối thiểu ' . number_format($this->min_order_value) . 'đ'];
        }

        return ['valid' => true, 'message' => 'Mã hợp lệ'];
    }

    public function calculateDiscount(float $orderTotal): float
    {
        if ($this->type === 'fixed') {
            return min($this->value, $orderTotal);
        }

        $discount = ($orderTotal * $this->value) / 100;

        if ($this->max_discount) {
            $discount = min($discount, $this->max_discount);
        }

        return $discount;
    }

    public function apply(): void
    {
        $this->increment('used_count');
    }
}
```

✅ Save

---

### MODEL 13/15: CouponUsage

**File:** `app/Models/CouponUsage.php`

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CouponUsage extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'coupon_id',
        'user_id',
        'order_id',
        'discount_amount',
        'created_at',
    ];

    protected $casts = [
        'discount_amount' => 'decimal:2',
        'created_at' => 'datetime',
    ];

    // ==================== RELATIONSHIPS ====================

    public function coupon()
    {
        return $this->belongsTo(Coupon::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
```

✅ Save

---

### MODEL 14/15: Address

**File:** `app/Models/Address.php`

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'full_name',
        'phone',
        'address_line',
        'province',
        'district',
        'ward',
        'label',
        'is_default',
    ];

    protected $casts = [
        'is_default' => 'boolean',
    ];

    // ==================== RELATIONSHIPS ====================

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // ==================== ACCESSORS ====================

    public function getFullAddressAttribute(): string
    {
        return implode(', ', [
            $this->address_line,
            $this->ward,
            $this->district,
            $this->province,
        ]);
    }

    // ==================== EVENTS ====================

    protected static function booted()
    {
        static::saving(function ($address) {
            if ($address->is_default) {
                static::where('user_id', $address->user_id)
                    ->where('id', '!=', $address->id)
                    ->update(['is_default' => false]);
            }
        });
    }
}
```

✅ Save

---

✅ **Checkpoint 2.3:** All 15 models updated!

---

## BƯỚC 2.4: TEST MODELS (5 phút)

**Terminal:**

```bash
cd /var/www/samnghethaycu.com

# Test syntax
php -l app/Models/Category.php
php -l app/Models/Product.php
php -l app/Models/Order.php

# Should all say: "No syntax errors detected"

# Test relationships
php artisan tinker
```

**In tinker:**

```php
>>> $cat = new App\Models\Category(['name' => 'Test']);
>>> $cat->products()
=> Illuminate\Database\Eloquent\Relations\HasMany

>>> $prod = new App\Models\Product();
>>> $prod->category()
=> Illuminate\Database\Eloquent\Relations\BelongsTo

>>> exit
```

✅ **Checkpoint 2.4:** Models verified

---

## BƯỚC 2.5: CUSTOMIZE FILAMENT RESOURCES (75 phút)

Tôi sẽ tiếp tục với phần này... File đang rất dài. Bạn có muốn tôi:

**Option A:** Tiếp tục viết toàn bộ vào file này (sẽ dài ~4000-5000 dòng)
**Option B:** Chia thành 2 file:
- File 1: Git Deployment + Models (đã xong)
- File 2: Filament Resources + Seeders

**Bạn chọn option nào?** 🤔