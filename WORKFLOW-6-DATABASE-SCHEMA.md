# 🗄️ WORKFLOW 6: DATABASE SCHEMA

> **Dự án:** samnghethaycu.com - E-Commerce Platform
> **Phiên bản:** 6.2 Professional Vietnamese (Fixed Structure & Logic)
> **Thời gian thực tế:** 25-35 phút
> **Mục tiêu:** 23 tables + 15 models + 9 Filament resources + CORRECT DEPENDENCY ORDER
> **Cập nhật:** 2025-11-22 - Fixed section structure, migration order, canAccessPanel logic, and minor improvements

---

## 📖 WORKFLOW NÀY LÀM GÌ?

### 🎯 Mục đích:

**Tạo database schema hoàn chỉnh cho e-commerce platform với migrations, models, và Filament CRUD resources.**

Sau khi đã có Filament admin panel working (WF-5), bây giờ xây dựng:
- Database schema với 15 migrations (23 bảng tổng cộng)
- 15 Eloquent models với fillable và casts
- 9 Filament resources tự động generate
- CRUD operations hoàn chỉnh trong admin panel
- Sẵn sàng cho business logic (WF-7)

**📝 Note:** Workflow này tập trung vào DATABASE STRUCTURE, chưa có relationships. Relationships sẽ được thêm trong WORKFLOW-7.

### 🎁 Kết quả sau workflow:

✅ **Database Schema Complete:**
- 23 bảng (15 custom + 8 Laravel system)
- Foreign keys và indexes được tối ưu
- Soft deletes cho data recovery
- Enum types cho business logic
- Migration rollback có thể undo

✅ **Eloquent Models Ready:**
- 15 models với fillable & casts đầy đủ
- SoftDeletes traits where applicable
- Ép kiểu dữ liệu cho data consistency
- Ready for relationships (WF-7)

✅ **Filament Resources Generated:**
- 9 auto-generated CRUD resources
- Form fields tự động từ database schema
- Table columns với filters cơ bản
- List/Create/Edit pages working
- Navigation menu tự động

✅ **Production Deployed:**
- Database schema deployed lên VPS
- Admin panel có đầy đủ CRUD operations
- Test data có thể tạo được
- Ready for customization (WF-8)

### ⚠️ PREREQUISITES:

**PHẢI hoàn thành trước:**
```
✅ WORKFLOW-1: VPS Infrastructure (LEMP + SSL)
✅ WORKFLOW-2: Laravel Installation (Laravel working)
✅ WORKFLOW-3: Git Workflow Setup (Git automation)
✅ WORKFLOW-4: Deployment Automation (deploy-sam command)
✅ WORKFLOW-5: Filament Admin Panel (Dashboard accessible)
✅ Admin panel working at: https://samnghethaycu.com/admin
```

**📍 Trên Windows - Verify trước khi bắt đầu:**

```powershell
# Check Laravel working locally
cd C:\Projects\samnghethaycu
php artisan --version
# Phải thấy: Laravel Framework 12.x.x

# Check database connection
php artisan db:show
# Phải thấy: database info (MySQL hoặc SQLite local)
```

**📍 Trên VPS - Verify Filament working:**

```bash
ssh deploy@69.62.82.145

cd /var/www/samnghethaycu.com

# Check Filament installed
php artisan route:list | grep admin
# Phải thấy: nhiều admin routes

# Test admin panel
curl -I https://samnghethaycu.com/admin
# Phải thấy: HTTP/2 200
```

**Browser test:**

```
https://samnghethaycu.com/admin
```

**Should see:** Filament dashboard (có thể login được)

**Nếu bất kỳ check nào FAIL → DỪNG LẠI, hoàn thành WF-1 đến WF-5 trước!**

### 💡 Triết lý:

**Database-first design → Git-driven deployment → Auto-generate admin panel → Customize later**

- Tạo migrations chính xác ngay từ đầu (ít phải sửa sau)
- Models đơn giản trước, relationships sau (WF-7)
- Filament auto-generate để có CRUD nhanh
- Customize UI sau khi logic hoạn chỉnh (WF-8)

---

## 🎯 NHỮNG GÌ CHÚNG TA SẼ XÂY DỰNG

```
23 Database Tables:
├── Core E-Commerce (8 tables)
│   ├── users (đã có từ Laravel, sẽ mở rộng)
│   ├── products (sản phẩm chính)
│   ├── product_variants (biến thể: size, màu)
│   ├── product_images (thư viện ảnh)
│   ├── categories (danh mục có cây)
│   ├── brands (thương hiệu)
│   ├── orders (đơn hàng)
│   └── order_items (chi tiết đơn hàng)
│
├── Supporting Tables (7 tables)
│   ├── addresses (địa chỉ giao hàng)
│   ├── reviews (đánh giá sản phẩm)
│   ├── coupons (mã giảm giá)
│   ├── coupon_usages (lịch sử dùng coupon)
│   ├── order_status_histories (audit trail)
│   ├── posts (bài viết blog)
│   └── post_categories (danh mục blog)
│
└── Laravel System (8 tables - đã có)
    ├── migrations
    ├── password_reset_tokens
    ├── sessions
    ├── cache, cache_locks
    └── jobs, job_batches, failed_jobs

15 Eloquent Models + 9 Filament Resources
```

**⚠️ MIGRATION ORDER CRITICAL!** Foreign keys phải tạo SAU khi bảng tham chiếu đã tồn tại!

**Dependency Levels:**
```
Level 0 (Laravel defaults - đã tồn tại):
└── users ✅

Level 1 (Bảng độc lập - không foreign key):
├── categories (có self-reference parent_id)
├── brands
├── post_categories
└── coupons

Level 2 (Phụ thuộc Level 0 + Level 1):
├── products        → cần: categories, brands
├── posts           → cần: post_categories, users
└── addresses       → cần: users

Level 3 (Phụ thuộc Level 2):
├── product_variants  → cần: products
├── product_images    → cần: products
└── orders            → cần: users, addresses, coupons

Level 4 (Phụ thuộc Level 3):
├── order_items            → cần: orders, products, product_variants
├── reviews                → cần: products, users, orders
├── coupon_usages          → cần: coupons, users, orders
└── order_status_histories → cần: orders, users

Level 5 (Mở rộng bảng có sẵn):
└── add_fields_to_users_table
```

**🔥 LƯU Ý QUAN TRỌNG:** Tạo migrations theo NHÓM với delay để đảm bảo timestamp khác nhau!

---

## PHẦN 0: SYNC LOCAL CODE (CRITICAL!)

**Thời gian:** 5 phút

**⚠️ QUAN TRỌNG:** Nếu local code chưa có Filament, PHẢI install trước!

### 0.1. Kiểm Tra Filament Đã Cài Chưa

**📍 Trên Windows PowerShell:**

```powershell
cd C:\Projects\samnghethaycu

# Check Filament routes
php artisan route:list | Select-String "admin"

# ✅ Nếu thấy admin routes → Filament đã cài, skip đến PHẦN 1
# ❌ Nếu không thấy gì → Chưa có Filament, làm tiếp 0.2
```

### 0.2. Install Filament Locally (Nếu Chưa Có)

**📍 Trên Windows PowerShell:**

```powershell
# Install Filament v3
composer require filament/filament:"^3.2" -W

# ⏳ Chờ 1-2 phút...
# ✅ Phải thấy: Package manifest generated successfully.

# Install Filament panels
php artisan filament:install --panels

# Chọn options:
# - Panel name: admin (default, nhấn Enter)
# - Panel path: admin (default, nhấn Enter)
```

### 0.3. Update User Model (Nếu Chưa Có FilamentUser)

**Kiểm tra User model:**

```powershell
# Mở User model
notepad app\Models\User.php

# Tìm dòng: use Illuminate\Foundation\Auth\User as Authenticatable;
# Kiểm tra có: implements FilamentUser không?
```

**Nếu CHƯA có `implements FilamentUser`, cập nhật:**

```powershell
notepad app\Models\User.php
```

**Thêm vào đầu file (sau namespace):**

```php
use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
```

**Sửa dòng class:**

```php
// Từ:
class User extends Authenticatable

// Thành:
class User extends Authenticatable implements FilamentUser
```

**Thêm method vào cuối class (trước dấu `}` cuối cùng):**

```php
/**
 * Determine if user can access Filament panel
 */
public function canAccessPanel(Panel $panel): bool
{
    return true; // Tất cả users có thể access admin (sửa sau nếu cần)
}
```

**Save file (Ctrl+S).**

### 0.4. Configure Vietnamese Locale

**Mở config/app.php:**

```powershell
notepad config\app.php
```

**Tìm và sửa:**

```php
// Tìm dòng:
'locale' => env('APP_LOCALE', 'en'),
// Sửa thành:
'locale' => env('APP_LOCALE', 'vi'),

// Tìm dòng:
'timezone' => env('APP_TIMEZONE', 'UTC'),
// Sửa thành:
'timezone' => env('APP_TIMEZONE', 'Asia/Ho_Chi_Minh'),
```

**Save file.**

### 0.5. Verify Installation

```powershell
# Check Filament routes
php artisan route:list | Select-String "admin"

# ✅ Phải thấy:
# GET|HEAD  admin ................ filament.admin.pages.dashboard
# GET|HEAD  admin/login .......... filament.admin.auth.login
# POST      admin/logout ......... filament.admin.auth.logout
```

### 0.6. Create Local Admin User (Optional)

```powershell
# Tạo admin user để test local
php artisan make:filament-user

# Nhập thông tin:
# Name: Admin
# Email: admin@local.test
# Password: admin123
```

### 0.7. Test Local Admin Panel (Optional)

```powershell
# Start local server
php artisan serve

# Mở browser: http://localhost:8000/admin
# ✅ Phải thấy Filament login page
# Login với: admin@local.test / admin123
```

✅ **Checkpoint 0:** Filament đã cài xong trên local, sẵn sàng tạo migrations!

---

## PHẦN 1: TẠO MIGRATIONS (LOCAL)

**Thời gian:** 12 phút

**📍 Trên Windows PowerShell:**

```powershell
cd C:\Projects\samnghethaycu
```

**🔥 QUAN TRỌNG:** Tạo migrations theo NHÓM (theo dependency level) với delay 2 giây giữa các nhóm để đảm bảo timestamp khác nhau và thứ tự đúng!

### Nhóm 1: Level 1 - Bảng độc lập (4 migrations)

```powershell
php artisan make:migration create_categories_table
php artisan make:migration create_brands_table
php artisan make:migration create_post_categories_table
php artisan make:migration create_coupons_table

# Đợi 2 giây để timestamp khác nhau
Start-Sleep -Seconds 2
```

### Nhóm 2: Level 2 - Phụ thuộc Level 1 (3 migrations)

```powershell
php artisan make:migration create_products_table
php artisan make:migration create_posts_table
php artisan make:migration create_addresses_table

# Đợi 2 giây
Start-Sleep -Seconds 2
```

### Nhóm 3: Level 3 - Phụ thuộc Level 2 (3 migrations)

```powershell
php artisan make:migration create_product_variants_table
php artisan make:migration create_product_images_table
php artisan make:migration create_orders_table

# Đợi 2 giây
Start-Sleep -Seconds 2
```

### Nhóm 4: Level 4 - Phụ thuộc Level 3 (4 migrations)

```powershell
php artisan make:migration create_order_items_table
php artisan make:migration create_reviews_table
php artisan make:migration create_coupon_usages_table
php artisan make:migration create_order_status_histories_table

# Đợi 2 giây
Start-Sleep -Seconds 2
```

### Nhóm 5: Level 5 - Mở rộng bảng có sẵn (1 migration)

```powershell
php artisan make:migration add_fields_to_users_table
```

**Kết quả mong đợi:**

```
INFO  Migration [database/migrations/2025_11_22_120001_create_categories_table.php] created successfully.
INFO  Migration [database/migrations/2025_11_22_120002_create_brands_table.php] created successfully.
...
INFO  Migration [database/migrations/2025_11_22_120015_add_fields_to_users_table.php] created successfully.
```

✅ **Checkpoint 1.0:** 15 file migration đã tạo theo đúng thứ tự dependency

---

### 1.1. Categories Migration

**📍 Windows PowerShell:**

```powershell
# Tìm file migration mới nhất cho categories
notepad database\migrations\*_create_categories_table.php
```

**Xóa toàn bộ nội dung và thay bằng code sau:**

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Chạy migrations.
     */
    public function up(): void
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->id();

            // Thông tin cơ bản
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->string('image')->nullable();

            // Danh mục lồng nhau (tự tham chiếu)
            $table->foreignId('parent_id')->nullable()->constrained('categories')->nullOnDelete();

            // Thứ tự hiển thị
            $table->integer('order')->default(0);

            // Trạng thái
            $table->boolean('is_active')->default(true);

            $table->timestamps();
            $table->softDeletes();

            // Chỉ mục để tối ưu hiệu suất
            $table->index(['slug', 'is_active']);
            $table->index('parent_id');
        });
    }

    /**
     * Hoàn tác migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('categories');
    }
};
```

**Lưu (Ctrl+S) và đóng Notepad**

✅ **Checkpoint 1.1:** Categories migration đã tạo

---

### 1.2. Brands Migration

```powershell
notepad database\migrations\*_create_brands_table.php
```

**Code:**

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('brands', function (Blueprint $table) {
            $table->id();

            // Thông tin cơ bản
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();

            // Tài sản thương hiệu
            $table->string('logo')->nullable();
            $table->string('website')->nullable();

            // Trạng thái
            $table->boolean('is_active')->default(true);

            $table->timestamps();
            $table->softDeletes();

            $table->index(['slug', 'is_active']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('brands');
    }
};
```

**Lưu và đóng**

✅ **Checkpoint 1.2:** Brands migration đã tạo

---

### 1.3. Post Categories Migration

```powershell
notepad database\migrations\*_create_post_categories_table.php
```

**Code:**

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('post_categories', function (Blueprint $table) {
            $table->id();

            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();

            $table->boolean('is_active')->default(true);

            $table->timestamps();
            $table->softDeletes();

            $table->index(['slug', 'is_active']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('post_categories');
    }
};
```

**Lưu và đóng**

✅ **Checkpoint 1.3:** Post Categories migration đã tạo

---

### 1.4. Coupons Migration

```powershell
notepad database\migrations\*_create_coupons_table.php
```

**Code:**

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('coupons', function (Blueprint $table) {
            $table->id();

            // Thông tin mã giảm giá
            $table->string('code')->unique();
            $table->string('name');
            $table->text('description')->nullable();

            // Quy tắc giảm giá
            $table->enum('discount_type', ['fixed', 'percentage']);
            $table->decimal('discount_value', 12, 2);

            // Ràng buộc
            $table->decimal('min_purchase_amount', 12, 2)->nullable();
            $table->decimal('max_discount_amount', 12, 2)->nullable();

            // Giới hạn sử dụng
            $table->integer('usage_limit')->nullable(); // Tổng số lần dùng
            $table->integer('usage_limit_per_user')->nullable(); // Mỗi người dùng

            // Thời hạn hiệu lực
            $table->timestamp('starts_at')->nullable();
            $table->timestamp('expires_at')->nullable();

            // Trạng thái
            $table->boolean('is_active')->default(true);

            $table->timestamps();
            $table->softDeletes();

            $table->index(['code', 'is_active']);
            $table->index(['starts_at', 'expires_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('coupons');
    }
};
```

**Lưu và đóng**

✅ **Checkpoint 1.4:** Coupons migration đã tạo

---

### 1.5. Products Migration

```powershell
notepad database\migrations\*_create_products_table.php
```

**Code:**

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();

            // Khóa ngoại
            $table->foreignId('category_id')->constrained()->cascadeOnDelete();
            $table->foreignId('brand_id')->nullable()->constrained()->nullOnDelete();

            // Thông tin cơ bản
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('short_description')->nullable();
            $table->longText('description')->nullable();

            // Giá cả
            $table->decimal('price', 12, 2);
            $table->decimal('sale_price', 12, 2)->nullable();
            $table->decimal('cost_price', 12, 2)->nullable();

            // Tồn kho
            $table->string('sku')->unique();
            $table->string('barcode')->nullable();
            $table->integer('stock_quantity')->default(0);
            $table->integer('min_stock_alert')->default(10);

            // Kích thước & trọng lượng (cho vận chuyển)
            $table->decimal('weight', 8, 2)->nullable();
            $table->decimal('length', 8, 2)->nullable();
            $table->decimal('width', 8, 2)->nullable();
            $table->decimal('height', 8, 2)->nullable();

            // Phương tiện
            $table->string('featured_image')->nullable();

            // Trạng thái & tính năng
            $table->boolean('is_featured')->default(false);
            $table->boolean('is_active')->default(true);
            $table->boolean('manage_stock')->default(true);

            // SEO
            $table->text('meta_title')->nullable();
            $table->text('meta_description')->nullable();
            $table->text('meta_keywords')->nullable();

            $table->timestamps();
            $table->softDeletes();

            // Chỉ mục
            $table->index(['slug', 'is_active', 'is_featured']);
            $table->index(['category_id', 'brand_id']);
            $table->index('sku');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
```

**Lưu và đóng**

✅ **Checkpoint 1.5:** Products migration đã tạo

---

### 1.6. Product Variants Migration

```powershell
notepad database\migrations\*_create_product_variants_table.php
```

**Code:**

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('product_variants', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();

            // Thông tin biến thể
            $table->string('name'); // ví dụ: "Hộp 10 củ", "Túi 500g", "Chai 100 viên"
            $table->string('sku')->unique();

            // Giá cả (ghi đè giá sản phẩm)
            $table->decimal('price', 12, 2);
            $table->decimal('sale_price', 12, 2)->nullable();

            // Tồn kho
            $table->integer('stock_quantity')->default(0);

            // Phương tiện
            $table->string('image')->nullable();

            // Thuộc tính (JSON: {size: "M", color: "red"})
            $table->json('attributes')->nullable();

            // Trạng thái
            $table->boolean('is_active')->default(true);

            $table->timestamps();
            $table->softDeletes();

            $table->index(['product_id', 'is_active']);
            $table->index('sku');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('product_variants');
    }
};
```

**Lưu và đóng**

✅ **Checkpoint 1.6:** Product Variants migration đã tạo

---

### 1.7. Product Images Migration

```powershell
notepad database\migrations\*_create_product_images_table.php
```

**Code:**

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('product_images', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();

            $table->string('image_path');
            $table->string('alt_text')->nullable();
            $table->integer('order')->default(0);
            $table->boolean('is_primary')->default(false);

            $table->timestamps();

            $table->index(['product_id', 'order']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('product_images');
    }
};
```

**Lưu và đóng**

✅ **Checkpoint 1.7:** Product Images migration đã tạo

---

### 1.8. Posts Migration

```powershell
notepad database\migrations\*_create_posts_table.php
```

**Code:**

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('posts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('post_category_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();

            // Nội dung
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('excerpt')->nullable();
            $table->longText('content');

            // Phương tiện
            $table->string('featured_image')->nullable();

            // Xuất bản
            $table->enum('status', ['draft', 'published', 'archived'])->default('draft');
            $table->timestamp('published_at')->nullable();

            // Phân tích
            $table->integer('views_count')->default(0);

            // SEO
            $table->text('meta_title')->nullable();
            $table->text('meta_description')->nullable();
            $table->text('meta_keywords')->nullable();

            $table->timestamps();
            $table->softDeletes();

            $table->index(['slug', 'status', 'published_at']);
            $table->index(['post_category_id', 'user_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('posts');
    }
};
```

**Lưu và đóng**

✅ **Checkpoint 1.8:** Posts migration đã tạo

---

### 1.9. Addresses Migration

```powershell
notepad database\migrations\*_create_addresses_table.php
```

**Code:**

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('addresses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();

            // Thông tin liên hệ
            $table->string('full_name');
            $table->string('phone');

            // Chi tiết địa chỉ (cấu trúc Việt Nam)
            $table->string('address_line_1');
            $table->string('address_line_2')->nullable();
            $table->string('city'); // Tỉnh/Thành phố
            $table->string('district')->nullable(); // Quận/Huyện
            $table->string('ward')->nullable(); // Phường/Xã
            $table->string('postal_code')->nullable();

            // Loại
            $table->enum('type', ['shipping', 'billing'])->default('shipping');
            $table->boolean('is_default')->default(false);

            $table->timestamps();
            $table->softDeletes();

            $table->index(['user_id', 'is_default']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('addresses');
    }
};
```

**Lưu và đóng**

✅ **Checkpoint 1.9:** Addresses migration đã tạo

---

### 1.11. Orders Migration

```powershell
notepad database\migrations\*_create_orders_table.php
```

**Code:**

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_number')->unique();

            // Quan hệ
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('shipping_address_id')->nullable()->constrained('addresses')->nullOnDelete();
            $table->foreignId('coupon_id')->nullable()->constrained()->nullOnDelete();

            // Trạng thái đơn hàng
            $table->enum('status', [
                'pending',      // Chờ xác nhận
                'processing',   // Đang xử lý
                'packed',       // Đã đóng gói
                'shipped',      // Đang giao
                'delivered',    // Đã giao
                'cancelled',    // Đã hủy
                'refunded'      // Đã hoàn tiền
            ])->default('pending');

            // Thanh toán
            $table->enum('payment_method', ['cod', 'vnpay', 'momo'])->default('cod');
            $table->enum('payment_status', ['pending', 'paid', 'failed', 'refunded'])->default('pending');

            // Số tiền
            $table->decimal('subtotal', 12, 2);
            $table->decimal('tax', 12, 2)->default(0);
            $table->decimal('shipping_fee', 12, 2)->default(0);
            $table->decimal('discount_amount', 12, 2)->default(0);
            $table->decimal('total', 12, 2);

            // Ghi chú
            $table->text('customer_note')->nullable();
            $table->text('admin_note')->nullable();

            // Thanh toán tracking
            $table->string('transaction_id')->nullable();
            $table->timestamp('paid_at')->nullable();
            $table->timestamp('shipped_at')->nullable();
            $table->timestamp('delivered_at')->nullable();

            $table->timestamps();
            $table->softDeletes();

            $table->index(['order_number', 'status', 'payment_status']);
            $table->index(['user_id', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
```

**Lưu và đóng**

✅ **Checkpoint 1.11:** Orders migration đã tạo

---

### 1.12. Order Items Migration

```powershell
notepad database\migrations\*_create_order_items_table.php
```

**Code:**

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->cascadeOnDelete();
            $table->foreignId('product_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('product_variant_id')->nullable()->constrained()->nullOnDelete();

            // Dữ liệu snapshot (để giữ lại thông tin khi product bị xóa)
            $table->string('product_name');
            $table->string('product_sku');
            $table->decimal('price', 12, 2);
            $table->integer('quantity');
            $table->decimal('subtotal', 12, 2);

            // Chi tiết biến thể (JSON snapshot)
            $table->json('variant_attributes')->nullable();

            $table->timestamps();

            $table->index(['order_id', 'product_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('order_items');
    }
};
```

**Lưu và đóng**

✅ **Checkpoint 1.12:** Order Items migration đã tạo

---

### 1.13. Reviews Migration

```powershell
notepad database\migrations\*_create_reviews_table.php
```

**Code:**

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('order_id')->nullable()->constrained()->nullOnDelete();

            // Nội dung đánh giá
            $table->integer('rating'); // 1-5 sao
            $table->string('title')->nullable();
            $table->text('comment');

            // Kiểm duyệt
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->timestamp('approved_at')->nullable();

            $table->timestamps();
            $table->softDeletes();

            $table->index(['product_id', 'status', 'rating']);
            $table->index(['user_id', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reviews');
    }
};
```

**Lưu và đóng**

✅ **Checkpoint 1.13:** Reviews migration đã tạo

---

### 1.14. Coupon Usages Migration

```powershell
notepad database\migrations\*_create_coupon_usages_table.php
```

**Code:**

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('coupon_usages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('coupon_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('order_id')->constrained()->cascadeOnDelete();

            $table->decimal('discount_amount', 12, 2);

            $table->timestamps();

            $table->index(['coupon_id', 'user_id']);
            $table->index('order_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('coupon_usages');
    }
};
```

**Lưu và đóng**

✅ **Checkpoint 1.14:** Coupon Usages migration đã tạo

---

### 1.15. Order Status Histories Migration

```powershell
notepad database\migrations\*_create_order_status_histories_table.php
```

**Code:**

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('order_status_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();

            // Trạng thái change tracking
            $table->string('old_status')->nullable();
            $table->string('new_status');
            $table->text('note')->nullable();

            $table->timestamps();

            $table->index(['order_id', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('order_status_histories');
    }
};
```

**Lưu và đóng**

✅ **Checkpoint 1.15:** Order Status Histories migration đã tạo

---

### 1.16. Update Users Table

**📝 Lưu ý:** Đây là migration thêm fields vào bảng `users` có sẵn, KHÔNG phải tạo mới!

```powershell
notepad database\migrations\*_add_fields_to_users_table.php
```

**Code:**

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('phone')->nullable()->after('email');
            $table->string('avatar')->nullable()->after('password');
            $table->date('date_of_birth')->nullable()->after('avatar');
            $table->enum('gender', ['male', 'female', 'other'])->nullable()->after('date_of_birth');
            $table->timestamp('last_login_at')->nullable()->after('gender');
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'phone',
                'avatar',
                'date_of_birth',
                'gender',
                'last_login_at',
            ]);
            $table->dropSoftDeletes();
        });
    }
};
```

**Lưu và đóng**

✅ **Checkpoint 1.16:** Users table extension migration đã tạo

---

### 1.17. Verify All Migrations

**📍 Windows PowerShell:**

```powershell
# Kiểm tra có bao nhiêu migration files
ls database\migrations\*_create_*.php | Measure-Object
# Phải thấy: Count : 14 (không tính users vì là add_fields)

ls database\migrations\*_add_fields_*.php | Measure-Object
# Phải thấy: Count : 1

# Tổng cộng phải có 15 migration files mới
```

**Kết quả mong đợi:**

```
Count    : 14
...
Count    : 1
```

**🔥 CRITICAL: Verify Migration Order (Timestamp)**

```powershell
# List migrations theo thứ tự timestamp
ls database\migrations\2025_* | Sort-Object Name | Select-Object -First 20 Name
```

**Kết quả phải theo thứ tự dependency:**

```
2025_11_22_HHMMSS_create_categories_table.php          ← Level 1 (độc lập)
2025_11_22_HHMMSS_create_brands_table.php              ← Level 1
2025_11_22_HHMMSS_create_post_categories_table.php     ← Level 1
2025_11_22_HHMMSS_create_coupons_table.php             ← Level 1

2025_11_22_HHMMSS_create_products_table.php            ← Level 2 (cần categories, brands)
2025_11_22_HHMMSS_create_posts_table.php               ← Level 2 (cần post_categories, users)
2025_11_22_HHMMSS_create_addresses_table.php           ← Level 2 (cần users)

2025_11_22_HHMMSS_create_product_variants_table.php    ← Level 3 (cần products)
2025_11_22_HHMMSS_create_product_images_table.php      ← Level 3 (cần products)
2025_11_22_HHMMSS_create_orders_table.php              ← Level 3 (cần users, addresses, coupons)

2025_11_22_HHMMSS_create_order_items_table.php         ← Level 4 (cần orders, products)
2025_11_22_HHMMSS_create_reviews_table.php             ← Level 4 (cần products, users, orders)
2025_11_22_HHMMSS_create_coupon_usages_table.php       ← Level 4 (cần coupons, users, orders)
2025_11_22_HHMMSS_create_order_status_histories_table.php ← Level 4 (cần orders)

2025_11_22_HHMMSS_add_fields_to_users_table.php        ← Level 5 (mở rộng users)
```

**⚠️ NẾU THỨ TỰ SAI:**

Nếu timestamp không theo đúng thứ tự dependency (ví dụ: products trước categories), bạn PHẢI đổi tên file để sửa timestamp:

```powershell
# Ví dụ: Nếu products (timestamp 120002) trước categories (120003), đổi lại:
# Rename products thành timestamp lớn hơn categories

# Hoặc XÓA TẤT CẢ và tạo lại theo nhóm với delay (khuyến nghị!)
```

**🔍 Test Migration Locally (DRY RUN):**

```powershell
# Test chạy migration local để kiểm tra không có lỗi
php artisan migrate

# Nếu thành công, rollback lại để VPS có thể chạy lại sau
php artisan migrate:rollback
```

**Kết quả mong đợi:**

```
INFO  Running migrations.

2025_11_22_xxx_create_categories_table ................ 15ms DONE
2025_11_22_xxx_create_brands_table .................... 12ms DONE
... (15 migrations total)

INFO  Migration completed successfully.
```

✅ **Checkpoint 1:** Tất cả 15 migrations đã tạo xong với thứ tự ĐÚNG!

---

## PHẦN 2: TẠO MODELS (LOCAL)

**Thời gian:** 10 phút

**📍 Windows PowerShell:**

```powershell
# Tạo tất cả models cùng lúc
php artisan make:model Category
php artisan make:model Brand
php artisan make:model Product
php artisan make:model ProductVariant
php artisan make:model ProductImage
php artisan make:model Address
php artisan make:model Order
php artisan make:model OrderItem
php artisan make:model OrderStatusHistory
php artisan make:model Review
php artisan make:model Coupon
php artisan make:model CouponUsage
php artisan make:model Post
php artisan make:model PostCategory
```

**Kết quả mong đợi:**

```
   INFO  Model [app/Models/Category.php] created successfully.
   INFO  Model [app/Models/Brand.php] created successfully.
...
```

**📝 Note:**
- User model đã có sẵn, không cần tạo
- Chúng ta sẽ update User model sau
- Tổng cộng tạo 14 models mới

✅ **Checkpoint 2.0:** 14 file model đã tạo

---

### 2.1. Category Model

```powershell
notepad app\Models\Category.php
```

**Xóa toàn bộ và thay bằng:**

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'image',
        'parent_id',
        'order',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'order' => 'integer',
    ];
}
```

**Lưu và đóng**

✅ **Checkpoint 2.1:** Category model đã tạo

---

### 2.2. Brand Model

```powershell
notepad app\Models\Brand.php
```

**Code:**

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Brand extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'logo',
        'website',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];
}
```

**Lưu và đóng**

✅ **Checkpoint 2.2:** Brand model đã tạo

---

### 2.3. PostCategory Model

```powershell
notepad app\Models\PostCategory.php
```

**Code:**

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PostCategory extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];
}
```

**Lưu và đóng**

✅ **Checkpoint 2.3:** PostCategory model đã tạo

---

### 2.4. Product Model

```powershell
notepad app\Models\Product.php
```

**Code:**

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'category_id',
        'brand_id',
        'name',
        'slug',
        'short_description',
        'description',
        'price',
        'sale_price',
        'cost_price',
        'sku',
        'barcode',
        'stock_quantity',
        'min_stock_alert',
        'weight',
        'length',
        'width',
        'height',
        'featured_image',
        'is_featured',
        'is_active',
        'manage_stock',
        'meta_title',
        'meta_description',
        'meta_keywords',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'sale_price' => 'decimal:2',
        'cost_price' => 'decimal:2',
        'weight' => 'decimal:2',
        'length' => 'decimal:2',
        'width' => 'decimal:2',
        'height' => 'decimal:2',
        'stock_quantity' => 'integer',
        'min_stock_alert' => 'integer',
        'is_featured' => 'boolean',
        'is_active' => 'boolean',
        'manage_stock' => 'boolean',
    ];
}
```

**Lưu và đóng**

✅ **Checkpoint 2.4:** Product model đã tạo

---

### 2.5. ProductVariant Model

```powershell
notepad app\Models\ProductVariant.php
```

**Code:**

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductVariant extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'product_id',
        'name',
        'sku',
        'price',
        'sale_price',
        'stock_quantity',
        'image',
        'attributes',
        'is_active',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'sale_price' => 'decimal:2',
        'stock_quantity' => 'integer',
        'attributes' => 'array',
        'is_active' => 'boolean',
    ];
}
```

**Lưu và đóng**

✅ **Checkpoint 2.5:** ProductVariant model đã tạo

---

### 2.6. ProductImage Model

```powershell
notepad app\Models\ProductImage.php
```

**Code:**

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductImage extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'image_path',
        'alt_text',
        'order',
        'is_primary',
    ];

    protected $casts = [
        'order' => 'integer',
        'is_primary' => 'boolean',
    ];
}
```

**Lưu và đóng**

✅ **Checkpoint 2.6:** ProductImage model đã tạo

---

### 2.7. Post Model

```powershell
notepad app\Models\Post.php
```

**Code:**

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Post extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'post_category_id',
        'user_id',
        'title',
        'slug',
        'excerpt',
        'content',
        'featured_image',
        'status',
        'published_at',
        'views_count',
        'meta_title',
        'meta_description',
        'meta_keywords',
    ];

    protected $casts = [
        'published_at' => 'datetime',
        'views_count' => 'integer',
    ];
}
```

**Lưu và đóng**

✅ **Checkpoint 2.7:** Post model đã tạo

---

### 2.8. Address Model

```powershell
notepad app\Models\Address.php
```

**Code:**

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Address extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'full_name',
        'phone',
        'address_line_1',
        'address_line_2',
        'city',
        'district',
        'ward',
        'postal_code',
        'type',
        'is_default',
    ];

    protected $casts = [
        'is_default' => 'boolean',
    ];
}
```

**Lưu và đóng**

✅ **Checkpoint 2.8:** Address model đã tạo

---

### 2.9. Coupon Model

```powershell
notepad app\Models\Coupon.php
```

**Code:**

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Coupon extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'code',
        'name',
        'description',
        'discount_type',
        'discount_value',
        'min_purchase_amount',
        'max_discount_amount',
        'usage_limit',
        'usage_limit_per_user',
        'starts_at',
        'expires_at',
        'is_active',
    ];

    protected $casts = [
        'discount_value' => 'decimal:2',
        'min_purchase_amount' => 'decimal:2',
        'max_discount_amount' => 'decimal:2',
        'usage_limit' => 'integer',
        'usage_limit_per_user' => 'integer',
        'starts_at' => 'datetime',
        'expires_at' => 'datetime',
        'is_active' => 'boolean',
    ];
}
```

**Lưu và đóng**

✅ **Checkpoint 2.9:** Coupon model đã tạo

---

### 2.10. Order Model

```powershell
notepad app\Models\Order.php
```

**Code:**

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'order_number',
        'user_id',
        'shipping_address_id',
        'coupon_id',
        'status',
        'payment_method',
        'payment_status',
        'subtotal',
        'tax',
        'shipping_fee',
        'discount_amount',
        'total',
        'customer_note',
        'admin_note',
        'transaction_id',
        'paid_at',
        'shipped_at',
        'delivered_at',
    ];

    protected $casts = [
        'subtotal' => 'decimal:2',
        'tax' => 'decimal:2',
        'shipping_fee' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'total' => 'decimal:2',
        'paid_at' => 'datetime',
        'shipped_at' => 'datetime',
        'delivered_at' => 'datetime',
    ];
}
```

**Lưu và đóng**

✅ **Checkpoint 2.10:** Order model đã tạo

---

### 2.11. OrderItem Model

```powershell
notepad app\Models\OrderItem.php
```

**Code:**

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
        'price',
        'quantity',
        'subtotal',
        'variant_attributes',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'quantity' => 'integer',
        'subtotal' => 'decimal:2',
        'variant_attributes' => 'array',
    ];
}
```

**Lưu và đóng**

✅ **Checkpoint 2.11:** OrderItem model đã tạo

---

### 2.12. Review Model

```powershell
notepad app\Models\Review.php
```

**Code:**

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Review extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'product_id',
        'user_id',
        'order_id',
        'rating',
        'title',
        'comment',
        'status',
        'approved_at',
    ];

    protected $casts = [
        'rating' => 'integer',
        'approved_at' => 'datetime',
    ];
}
```

**Lưu và đóng**

✅ **Checkpoint 2.12:** Review model đã tạo

---

### 2.13. CouponUsage Model

```powershell
notepad app\Models\CouponUsage.php
```

**Code:**

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CouponUsage extends Model
{
    use HasFactory;

    protected $fillable = [
        'coupon_id',
        'user_id',
        'order_id',
        'discount_amount',
    ];

    protected $casts = [
        'discount_amount' => 'decimal:2',
    ];
}
```

**Lưu và đóng**

✅ **Checkpoint 2.13:** CouponUsage model đã tạo

---

### 2.14. OrderStatusHistory Model

```powershell
notepad app\Models\OrderStatusHistory.php
```

**Code:**

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderStatusHistory extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'user_id',
        'old_status',
        'new_status',
        'note',
    ];
}
```

**Lưu và đóng**

✅ **Checkpoint 2.14:** OrderStatusHistory model đã tạo

---

### 2.15. Update User Model

**⚠️ CRITICAL:** User model đã có sẵn, chúng ta chỉ UPDATE thêm fields!

```powershell
notepad app\Models\User.php
```

**Tìm dòng `protected $fillable = [...]` và UPDATE:**

**BEFORE:**

```php
protected $fillable = [
    'name',
    'email',
    'password',
];
```

**AFTER (thêm các fields mới):**

```php
protected $fillable = [
    'name',
    'email',
    'password',
    'phone',
    'avatar',
    'date_of_birth',
    'gender',
    'last_login_at',
];
```

**Tìm dòng `protected function casts(): array` và UPDATE:**

**BEFORE:**

```php
protected function casts(): array
{
    return [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];
}
```

**AFTER (thêm casts mới):**

```php
protected function casts(): array
{
    return [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'date_of_birth' => 'date',
        'last_login_at' => 'datetime',
    ];
}
```

**Thêm SoftDeletes trait (sau dòng `use HasFactory, Notifiable;`):**

**BEFORE:**

```php
class User extends Authenticatable implements FilamentUser
{
    use HasFactory, Notifiable;
```

**AFTER:**

```php
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Authenticatable implements FilamentUser
{
    use HasFactory, Notifiable, SoftDeletes;
```

**Nhớ thêm use statement ở đầu file:**

```php
use Illuminate\Database\Eloquent\SoftDeletes;
```

**FULL CODE của User model sau khi update:**

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;

class User extends Authenticatable implements FilamentUser
{
    use HasFactory, Notifiable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'avatar',
        'date_of_birth',
        'gender',
        'last_login_at',
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
            'date_of_birth' => 'date',
            'last_login_at' => 'datetime',
        ];
    }

    /**
     * Determine if the user can access the Filament admin panel.
     */
    public function canAccessPanel(Panel $panel): bool
    {
        // Allow access if email ends with @samnghethaycu.com
        return true; // Allow all users (customize in production)
    }
}
```

**Lưu và đóng**

✅ **Checkpoint 2.15:** User model updated

---

### 2.16. Verify All Models

```powershell
# Đếm tất cả models (không tính User vì đã có sẵn)
ls app\Models\*.php | Measure-Object
# Phải thấy: Count : 15 (14 mới + 1 User)
```

**Kết quả mong đợi:**

```
Count    : 15
```

✅ **Checkpoint 2:** Tất cả 15 models đã hoàn thành

---

## PHẦN 3: COMMIT & PUSH (LOCAL)

**Thời gian:** 3 phút

**📍 Windows PowerShell:**

```powershell
# Kiểm tra changes
git status
```

**Kết quả mong đợi:**

```
On branch main
Changes not staged for commit:
  modified:   app/Models/User.php

Untracked files:
  app/Models/Address.php
  app/Models/Brand.php
  app/Models/Category.php
  ... (14 models)
  database/migrations/..._create_categories_table.php
  database/migrations/..._create_brands_table.php
  ... (15 migrations)
```

**Add all changes:**

```powershell
git add .

# Commit với message chi tiết
git commit -m "feat: create complete database schema for e-commerce platform

MIGRATIONS (15 total):
- Core e-commerce: categories, brands, products, variants, images
- Orders system: orders, order_items, order_status_histories
- Customer data: addresses, reviews
- Promotions: coupons, coupon_usages
- Blog: posts, post_categories
- Users extension: added phone, avatar, birth date, gender, last_login

MODELS (15 total):
- All models with fillable and casts configured
- SoftDeletes traits where applicable
- Ép kiểu dữ liệu for data consistency
- Ready for relationships (WORKFLOW-7)

DATABASE STRUCTURE:
- 23 tables total (15 custom + 8 Laravel system)
- Foreign keys with cascade/null actions
- Indexes for performance optimization
- Enum types for business logic
- JSON fields for flexible data

Ready for Filament resource generation and deployment."
```

**Kết quả mong đợi:**

```
[main abc1234] feat: create complete database schema for e-commerce platform
 30 files changed, 1500 insertions(+)
 create mode 100644 app/Models/Address.php
 create mode 100644 app/Models/Brand.php
 ...
 create mode 100644 database/migrations/..._create_categories_table.php
 ...
```

**Push to GitHub:**

```powershell
git push origin main
```

**Kết quả mong đợi:**

```
Enumerating objects: 45, done.
Counting objects: 100% (45/45), done.
...
To https://github.com/phuochoavn/websamnghe.git
   def5678..abc1234  main -> main
```

✅ **Checkpoint 3:** Code committed and pushed to GitHub

---

## PHẦN 4: DEPLOY LÊN VPS & RUN MIGRATIONS

**Thời gian:** 5 phút

**📍 Trên VPS:**

```bash
# SSH to VPS
ssh deploy@69.62.82.145

cd /var/www/samnghethaycu.com

# Deploy với automation script
deploy-sam
```

**Kết quả mong đợi:**

```
🚀 Starting deployment...

📂 Current directory: /var/www/samnghethaycu.com

📥 Step 1/8: Pulling latest code from GitHub...
✅ Code updated
abc1234 feat: create complete database schema for e-commerce platform

🔍 Step 2/8: Checking .env file...
✅ .env exists

🔧 Step 3/8: Checking bootstrap/cache...
✅ bootstrap/cache is directory

📦 Step 4/8: Installing Composer dependencies...
✅ Dependencies installed

🗄️  Step 5/8: Running database migrations...

   INFO  Running migrations.

  2025_11_22_123456_create_categories_table .................... 15.23ms DONE
  2025_11_22_123457_create_brands_table ....................... 12.45ms DONE
  2025_11_22_123458_create_post_categories_table ............... 10.67ms DONE
  2025_11_22_123459_create_products_table ..................... 25.89ms DONE
  2025_11_22_123500_create_product_variants_table ............. 18.34ms DONE
  2025_11_22_123501_create_product_images_table ............... 14.56ms DONE
  2025_11_22_123502_create_posts_table ........................ 20.12ms DONE
  2025_11_22_123503_create_addresses_table .................... 16.78ms DONE
  2025_11_22_123504_create_coupons_table ...................... 19.23ms DONE
  2025_11_22_123505_create_orders_table ....................... 28.91ms DONE
  2025_11_22_123506_create_order_items_table .................. 17.45ms DONE
  2025_11_22_123507_create_reviews_table ...................... 15.67ms DONE
  2025_11_22_123508_create_coupon_usages_table ................ 13.89ms DONE
  2025_11_22_123509_create_order_status_histories_table ....... 14.23ms DONE
  2025_11_22_123510_add_fields_to_users_table ................. 11.56ms DONE

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

✅ **Checkpoint 4:** Database migrated to VPS successfully

---

### Verify Database Tables

**📍 Trên VPS:**

```bash
# Kiểm tra database
php artisan db:show
```

**Kết quả mong đợi:**

```
  MySQL ......................................................... 8.0.44
  Connection .................................................... mysql
  Database .................................................. samnghethaycu
  Host .......................................................... 127.0.0.1
  Port .......................................................... 3306
  Username .............................................. samnghethaycu_user
  Tables ........................................................ 23
  Total Size ................................................ 512.00 KB
```

**Kiểm tra các bảng cụ thể:**

```bash
# List all tables
php artisan db:table --database=mysql

# Or via tinker
php artisan tinker
```

**In tinker:**

```php
// Check if tables exist
Schema::hasTable('products')
// Should return: true

Schema::hasTable('orders')
// Should return: true

Schema::hasTable('categories')
// Should return: true

// Count tables
collect(DB::select('SHOW TABLES'))->count()
// Should return: 23

exit
```

**Kết quả mong đợi:**

```php
> Schema::hasTable('products')
= true

> Schema::hasTable('orders')
= true

> collect(DB::select('SHOW TABLES'))->count()
= 23
```

✅ **Checkpoint 4.1:** Database verified - 23 tables exist

---

## PHẦN 5: GENERATE FILAMENT RESOURCES (LOCAL)

**Thời gian:** 8 phút

**📝 Lưu ý quan trọng:**
- Làm trên LOCAL Windows trước
- `--generate` flag sẽ tự động tạo forms & tables từ database schema
- Chỉ tạo resources cho các entities chính (9 resources)
- Không tạo cho các bảng phụ trợ (ProductImage, OrderItem, CouponUsage, OrderStatusHistory)

**📍 Windows PowerShell:**

```powershell
cd C:\Projects\samnghethaycu

# Generate Filament resources với --generate flag
php artisan make:filament-resource Product --generate
php artisan make:filament-resource Category --generate
php artisan make:filament-resource Brand --generate
php artisan make:filament-resource Order --generate
php artisan make:filament-resource Review --generate
php artisan make:filament-resource Coupon --generate
php artisan make:filament-resource Post --generate
php artisan make:filament-resource PostCategory --generate
php artisan make:filament-resource Address --generate
```

**Expected output (for each command):**

```
   INFO  Filament resource [app/Filament/Resources/ProductResource.php] created successfully.

The following resource has been created:

Resource: app\Filament\Resources\ProductResource.php
Pages:
  - app\Filament\Resources\ProductResource\Pages\ListProducts.php
  - app\Filament\Resources\ProductResource\Pages\CreateProduct.php
  - app\Filament\Resources\ProductResource\Pages\EditProduct.php
```

**📝 What `--generate` does:**
- ✅ Auto-generates form fields based on database columns
- ✅ Auto-generates table columns
- ✅ Detects foreign keys and creates Select fields
- ✅ Creates List, Create, Edit pages
- ✅ Adds navigation menu items
- ✅ Configures basic validation

✅ **Checkpoint 5:** 9 Filament resource đã tạo

---

### Verify Resources Created

```powershell
# Kiểm tra resource files
ls app\Filament\Resources\*Resource.php | Measure-Object
# Phải thấy: Count : 9

# Kiểm tra pages
ls app\Filament\Resources\*\Pages\*.php | Measure-Object
# Phải thấy: Count : 27 (9 resources × 3 pages)
```

**Kết quả mong đợi:**

```
Count    : 9
...
Count    : 27
```

✅ **Checkpoint 5.1:** All resource files verified

---

## PHẦN 6: COMMIT & DEPLOY FILAMENT RESOURCES

**Thời gian:** 3 phút

**📍 Windows PowerShell:**

```powershell
# Check changes
git status

# Add all Filament resources
git add app/Filament/

# Commit
git commit -m "feat: generate Filament resources for 9 core entities

AUTO-GENERATED RESOURCES:
- ProductResource (e-commerce core)
- CategoryResource (product categorization)
- BrandResource (product brands)
- OrderResource (order management)
- ReviewResource (customer reviews)
- CouponResource (discount codes)
- PostResource (blog posts)
- PostCategoryResource (blog categories)
- AddressResource (shipping addresses)

FEATURES:
- Auto-generated forms with all database fields
- Auto-generated table columns
- Select fields for foreign keys
- List/Create/Edit pages
- Navigation menu items
- Basic validation rules

Total: 9 resources × 3 pages = 27 files
Admin panel now has full CRUD operations!"

# Push to GitHub
git push origin main
```

**Kết quả mong đợi:**

```
[main xyz9876] feat: generate Filament resources for 9 core entities
 27 files changed, 2500 insertions(+)
 create mode 100644 app/Filament/Resources/ProductResource.php
 ...
To https://github.com/phuochoavn/websamnghe.git
   abc1234..xyz9876  main -> main
```

**Deploy to VPS:**

```bash
# SSH if not already connected
ssh deploy@69.62.82.145

cd /var/www/samnghethaycu.com

# Deploy!
deploy-sam
```

**Kết quả mong đợi:**

```
🚀 Starting deployment...
...
📥 Step 1/8: Pulling latest code from GitHub...
✅ Code updated
xyz9876 feat: generate Filament resources for 9 core entities
...
🎉 Deployment completed successfully!
```

✅ **Checkpoint 6:** Filament resources deployed to production

---

## PHẦN 7: TEST ADMIN PANEL & CREATE SAMPLE DATA

**Thời gian:** 8 phút

**📍 Browser:**

```
https://samnghethaycu.com/admin
```

**Login:** admin@samnghethaycu.com / Admin@123456

**Should see:** Dashboard với sidebar navigation hiển thị 9 resources mới!

```
Sidebar Navigation:
├── Dashboard
├── Products
├── Categories
├── Brands
├── Orders
├── Reviews
├── Coupons
├── Posts
├── Post Categories
└── Addresses
```

✅ **Checkpoint 7.0:** Admin panel showing all resources

---

### 7.1. Test Create Category

**Navigate:** Categories → Create

**Fill form:**
- Name: `Sâm Hàn Quốc`
- Slug: `sam-han-quoc` (auto-generated from name)
- Description: `Sản phẩm sâm nhập khẩu từ Hàn Quốc, chất lượng cao`
- Order: `1`
- Is Active: ✅ (checked)

**Click "Create"**

**Should see:** ✅ Success notification "Category created successfully!"

**Should redirect to:** Categories list page

**Should see:** 1 category in the table

✅ **Checkpoint 7.1:** Category CRUD working

---

### 7.2. Test Create Brand

**Navigate:** Brands → Create

**Fill:**
- Name: `KGC Cheong Kwan Jang`
- Slug: `kgc-cheong-kwan-jang`
- Description: `Thương hiệu sâm nổi tiếng từ Hàn Quốc, hơn 120 năm lịch sử`
- Website: `https://www.kgcus.com`
- Is Active: ✅

**Create**

**Should see:** ✅ "Brand created successfully!"

✅ **Checkpoint 7.2:** Brand CRUD working

---

### 7.3. Test Create Product

**Navigate:** Products → Create

**Fill basic info:**
- Name: `Sâm Tươi Hàn Quốc 6 Năm Tuổi`
- Slug: `sam-tuoi-han-quoc-6-nam-tuoi`
- Category: Select "Sâm Hàn Quốc"
- Brand: Select "KGC Cheong Kwan Jang"
- Short Description: `Sâm tươi 6 năm tuổi chất lượng cao từ Hàn Quốc`

**Fill pricing:**
- Price: `450000`
- Sale Price: `399000`
- Cost Price: `300000`

**Fill inventory:**
- SKU: `SAM-HQ-6Y-001`
- Stock Quantity: `50`
- Min Stock Alert: `10`
- Manage Stock: ✅

**Fill status:**
- Is Featured: ✅
- Is Active: ✅

**Create**

**Should see:** ✅ "Product created successfully!"

**Should see:** Product in list with:
- Name displayed
- Category: "Sâm Hàn Quốc"
- Brand: "KGC Cheong Kwan Jang"
- Price: ₫399,000 (formatted)
- Stock: 50

✅ **Checkpoint 7.3:** Product CRUD working with relationships

---

### 7.4. Test Create Post Category

**Navigate:** Post Categories → Create

**Fill:**
- Name: `Sức khỏe & Dinh dưỡng`
- Slug: `suc-khoe-dinh-duong`
- Description: `Các bài viết về sức khỏe và dinh dưỡng`
- Is Active: ✅

**Create**

**Should see:** ✅ Success

✅ **Checkpoint 7.4:** Post Category CRUD working

---

### 7.5. Test Create Post

**Navigate:** Posts → Create

**Fill:**
- Title: `Lợi ích của sâm Hàn Quốc đối với sức khỏe`
- Slug: `loi-ich-cua-sam-han-quoc`
- Post Category: Select "Sức khỏe & Dinh dưỡng"
- User: Select "Admin" (your admin user)
- Excerpt: `Tìm hiểu về các lợi ích tuyệt vời của sâm Hàn Quốc`
- Content: `Sâm Hàn Quốc là một trong những vị thuốc quý...`
- Status: `published`
- Published At: (today's date)

**Create**

**Should see:** ✅ Post created

✅ **Checkpoint 7.5:** Post CRUD working

---

### 7.6. Verify Database Records on VPS

**📍 SSH to VPS:**

```bash
cd /var/www/samnghethaycu.com

php artisan tinker
```

**In tinker:**

```php
// Check all tables have data
App\Models\Category::count()
// Should return: 1

App\Models\Brand::count()
// Should return: 1

App\Models\Product::count()
// Should return: 1

App\Models\PostCategory::count()
// Should return: 1

App\Models\Post::count()
// Should return: 1

// Test with relationships (will work in WORKFLOW-7)
$product = App\Models\Product::first();
$product->name
// Should return: "Sâm Tươi Hàn Quốc 6 Năm Tuổi"

$product->category_id
// Should return: 1

$product->brand_id
// Should return: 1

// Test User model updates
$user = App\Models\User::first();
$user
// Should show: phone, avatar, date_of_birth, gender, last_login_at columns

exit
```

**Kết quả mong đợi:**

```php
> App\Models\Category::count()
= 1

> App\Models\Brand::count()
= 1

> App\Models\Product::count()
= 1

> $product = App\Models\Product::first();
= App\Models\Product {#5678
    id: 1,
    category_id: 1,
    brand_id: 1,
    name: "Sâm Tươi Hàn Quốc 6 Năm Tuổi",
    ...
  }
```

✅ **Checkpoint 7.6:** Database records verified

---

## ✅ VERIFICATION - HOÀN THÀNH WORKFLOW 6

### Full Workflow Checklist

```
PHẦN 1: TẠO MIGRATIONS
✅ 15 file migration đã tạo
✅ All foreign keys configured correctly
✅ Indexes added for performance
✅ SoftDeletes where applicable
✅ Enum types for business logic

PHẦN 2: TẠO MODELS
✅ 14 new models created
✅ 1 existing model (User) updated
✅ All fillable arrays configured
✅ All casts configured correctly
✅ SoftDeletes traits added

PHẦN 3: COMMIT & PUSH
✅ Code committed locally
✅ Pushed to GitHub successfully
✅ Commit message descriptive

PHẦN 4: DEPLOY & MIGRATE
✅ Deployed via deploy-sam
✅ All 15 migrations ran successfully
✅ 23 tables exist in database
✅ Database structure verified

PHẦN 5: GENERATE FILAMENT RESOURCES
✅ 9 resource đã tạo with --generate
✅ 27 page files created (9 × 3)
✅ Forms auto-generated from schema
✅ Tables auto-generated

PHẦN 6: DEPLOY RESOURCES
✅ Resources committed and pushed
✅ Deployed to production
✅ Navigation menu updated

PHẦN 7: TEST ADMIN PANEL
✅ All 9 resources visible in sidebar
✅ Can create Category
✅ Can create Brand
✅ Can create Product with relationships
✅ Can create Post Category
✅ Can create Post
✅ Database records verified on VPS
✅ User model updated fields working
```

**Final test:**

**📍 Browser:**

```
1. Visit: https://samnghethaycu.com/admin
2. Login successfully
3. See all 9 resources in sidebar
4. Click Products → See 1 product
5. Click Categories → See 1 category
6. Click Brands → See 1 brand
7. Click Posts → See 1 post
8. All pages load without errors
```

**All working?** → SUCCESS! 🎉

---

## 🎉 WORKFLOW 6 COMPLETE!

### Bạn đã có:

```
✅ DATABASE SCHEMA COMPLETE:
- 23 tables total (15 custom + 8 Laravel)
- Foreign keys with proper constraints
- Indexes for query performance
- SoftDeletes for data recovery
- Enum types for validation
- JSON fields for flexibility

✅ ELOQUENT MODELS READY:
- 15 models with fillable & casts
- Ép kiểu dữ liệu configured
- SoftDeletes traits
- Ready for relationships (WF-7)

✅ FILAMENT ADMIN PANEL FUNCTIONAL:
- 9 auto-generated CRUD resources
- Forms with all database fields
- Tables with columns and filters
- Navigation menu working
- Can create/edit/delete records

✅ PRODUCTION DEPLOYED:
- Database schema on VPS
- Models accessible
- Admin panel fully functional
- Test data created successfully

✅ GIT WORKFLOW VERIFIED:
- Local → GitHub → VPS pipeline working
- deploy-sam automation successful
- Migrations auto-run on deploy
```

### Current Admin Panel Resources:

```
Admin Panel (https://samnghethaycu.com/admin):
├── Products (CRUD working ✅)
├── Categories (CRUD working ✅)
├── Brands (CRUD working ✅)
├── Orders (CRUD working ✅)
├── Reviews (CRUD working ✅)
├── Coupons (CRUD working ✅)
├── Posts (CRUD working ✅)
├── Post Categories (CRUD working ✅)
└── Addresses (CRUD working ✅)
```

### What's Missing (Next Workflows):

```
⏳ Model relationships (belongsTo, hasMany, etc.) → WORKFLOW-7
⏳ Business logic methods (URL helpers, calculations) → WORKFLOW-7
⏳ Accessors & mutators (formatted prices, status labels) → WORKFLOW-7
⏳ Scopes and query builders (active(), featured()) → WORKFLOW-7
⏳ Filament customization (tabs, filters, widgets, actions) → WORKFLOW-8
⏳ Vietnamese sample data (categories, products, posts) → WORKFLOW-9
```

### Database Structure Created:

```
E-Commerce Core:
- users (customers & admin) ✅
- products (main catalog) ✅
- product_variants (sizes, colors) ✅
- product_images (gallery) ✅
- categories (nested tree) ✅
- brands ✅

Order Management:
- orders (main orders) ✅
- order_items (line items) ✅
- order_status_histories (audit trail) ✅
- addresses (shipping/billing) ✅

Marketing:
- coupons (discount codes) ✅
- coupon_usages (tracking) ✅
- reviews (product ratings) ✅

Content:
- posts (blog articles) ✅
- post_categories ✅
```

### Deployment Workflow Verified:

```
LOCAL (Windows)          GITHUB              VPS (Production)
───────────────          ──────              ────────────────
Create migrations   →    Push code      →    deploy-sam ✨
Create models       →    Push changes   →    → Migrations auto-run
Generate resources  →    Push resources →    → Resources available
                                              → Admin panel working!
```

---

## 🚀 NEXT STEP:

```
✅ WORKFLOW-1: VPS Infrastructure
✅ WORKFLOW-2: Laravel Installation
✅ WORKFLOW-3: Git Workflow Setup
✅ WORKFLOW-4: Deployment Automation
✅ WORKFLOW-5: Filament Admin Panel
✅ WORKFLOW-6: Database Schema (YOU ARE HERE! ✅)
→ WORKFLOW-7: MODEL BUSINESS LOGIC
  Add 50+ relationships, scopes, accessors, mutators, and helper methods
  Time: 30-40 minutes
  File: WORKFLOW-7-MODEL-BUSINESS-LOGIC.md
```

---

## 🔄 ROLLBACK: XÓA DATABASE SCHEMA VỀ WORKFLOW-5

**Nếu muốn xóa toàn bộ database schema và quay về trạng thái WORKFLOW-5 (chỉ có Filament, chưa có database):**

**⚠️ IMPORTANT:** Rollback sẽ xóa:
- Tất cả 15 migrations
- Tất cả 15 models (trừ User - sẽ restore về version cũ)
- Tất cả 9 Filament resources
- Tất cả data trong database

### PHẦN 1: ROLLBACK TRÊN LOCAL (Windows)

**Thời gian:** 5-8 phút

**⚠️ THỨ TỰ QUAN TRỌNG:** Xóa Filament resources TRƯỚC, models SAU, migrations CUỐI!

#### BƯỚC 1: Xóa Filament Resources

**📍 Windows PowerShell:**

```powershell
cd C:\Projects\samnghethaycu

# Xóa toàn bộ thư mục Resources
Remove-Item -Recurse -Force app\Filament\Resources\ -ErrorAction SilentlyContinue

# Verify đã xóa
ls app\Filament\
# Kết quả: Không còn thư mục Resources
```

**Mong đợi:**

```
Mode                 LastWriteTime         Length Name
----                 -------------         ------ ----
(empty - no Resources directory)
```

✅ **Checkpoint 1.1:** Filament resources deleted

---

#### BƯỚC 2: Xóa Models (Trừ User)

```powershell
# Xóa 14 models mới (GIỮ LẠI User.php)
Remove-Item app\Models\Address.php
Remove-Item app\Models\Brand.php
Remove-Item app\Models\Category.php
Remove-Item app\Models\Coupon.php
Remove-Item app\Models\CouponUsage.php
Remove-Item app\Models\Order.php
Remove-Item app\Models\OrderItem.php
Remove-Item app\Models\OrderStatusHistory.php
Remove-Item app\Models\Post.php
Remove-Item app\Models\PostCategory.php
Remove-Item app\Models\Product.php
Remove-Item app\Models\ProductImage.php
Remove-Item app\Models\ProductVariant.php
Remove-Item app\Models\Review.php

# Verify
ls app\Models\
# Phải còn lại: User.php
```

**Mong đợi:**

```
Mode                 LastWriteTime         Length Name
----                 -------------         ------ ----
-a----         11/22/2025   2:00 PM           2345 User.php
```

✅ **Checkpoint 1.2:** 14 models deleted, User.php retained

---

#### BƯỚC 3: Restore User Model về Version WORKFLOW-5

**Option A: Git Restore (Recommended)**

```powershell
# Tìm commit của WORKFLOW-5
git log --oneline --grep="Filament" | Select-Object -First 5

# Restore User.php về version WORKFLOW-5
git checkout <commit-hash-of-workflow-5> -- app/Models/User.php
```

**Option B: Manual Edit**

```powershell
notepad app\Models\User.php
```

**Xóa các dòng đã thêm trong WORKFLOW-6:**

- Xóa `'phone', 'avatar', 'date_of_birth', 'gender', 'last_login_at'` khỏi `$fillable`
- Xóa `'date_of_birth' => 'date', 'last_login_at' => 'datetime'` khỏi `casts()`
- Xóa `, SoftDeletes` khỏi `use HasFactory, Notifiable, SoftDeletes;`
- Xóa `use Illuminate\Database\Eloquent\SoftDeletes;` ở đầu file

**User.php SAU KHI RESTORE (giống WORKFLOW-5):**

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

    public function canAccessPanel(Panel $panel): bool
    {
        return true; // Allow all users (customize in production)
    }
}
```

**Lưu và đóng**

✅ **Checkpoint 1.3:** User model restored to WORKFLOW-5 state

---

#### BƯỚC 4: Xóa Migration Files

```powershell
# Xóa 15 migration files của WORKFLOW-6
Remove-Item database\migrations\*_create_categories_table.php
Remove-Item database\migrations\*_create_brands_table.php
Remove-Item database\migrations\*_create_post_categories_table.php
Remove-Item database\migrations\*_create_products_table.php
Remove-Item database\migrations\*_create_product_variants_table.php
Remove-Item database\migrations\*_create_product_images_table.php
Remove-Item database\migrations\*_create_posts_table.php
Remove-Item database\migrations\*_create_addresses_table.php
Remove-Item database\migrations\*_create_coupons_table.php
Remove-Item database\migrations\*_create_orders_table.php
Remove-Item database\migrations\*_create_order_items_table.php
Remove-Item database\migrations\*_create_reviews_table.php
Remove-Item database\migrations\*_create_coupon_usages_table.php
Remove-Item database\migrations\*_create_order_status_histories_table.php
Remove-Item database\migrations\*_add_fields_to_users_table.php

# Verify
ls database\migrations\ | Measure-Object
# Phải còn lại 3 migrations (default Laravel)
```

**Mong đợi:**

```
Count    : 3

Mode                 LastWriteTime         Length Name
----                 -------------         ------ ----
-a----         10/01/2025  10:00 AM           1234 0001_01_01_000000_create_users_table.php
-a----         10/01/2025  10:00 AM            567 0001_01_01_000001_create_cache_table.php
-a----         10/01/2025  10:00 AM            890 0001_01_01_000002_create_jobs_table.php
```

✅ **Checkpoint 1.4:** All WORKFLOW-6 migrations deleted

---

#### BƯỚC 5: Verify Locally

```powershell
# Check models
ls app\Models\
# Phải chỉ có: User.php

# Check migrations
ls database\migrations\ | Measure-Object
# Phải có: Count : 3

# Check Filament resources
ls app\Filament\Resources\ -ErrorAction SilentlyContinue
# Phải lỗi: Cannot find path (đúng!)

# Test Laravel still works
php artisan --version
# Phải thấy: Laravel Framework 12.x.x
```

✅ **Checkpoint 1.5:** Local verification passed

---

#### BƯỚC 6: Commit & Push Rollback

```powershell
# Check changes
git status

# Add all deletions and modifications
git add .

# Commit với message rõ ràng
git commit -m "revert: rollback database schema to WORKFLOW-5 state

REMOVED:
- 15 database migrations (all WORKFLOW-6 tables)
- 14 Eloquent models (kept User.php)
- 9 Filament resources (Products, Categories, Orders, etc.)

RESTORED:
- User model to WORKFLOW-5 version (removed WF-6 fields)

RESULT:
- Back to clean Laravel + Filament state
- Only 3 default migrations (users, cache, jobs)
- Only 1 model (User with FilamentUser)
- No custom Filament resources
- Database will be rolled back on VPS deployment

Reason: [Your reason here, e.g., 'Need to redesign schema']"

# Push to GitHub
git push origin main
```

**Kết quả mong đợi:**

```
[main abc1234] revert: rollback database schema to WORKFLOW-5 state
 44 files changed, 50 insertions(+), 3500 deletions(-)
 delete mode 100644 app/Filament/Resources/ProductResource.php
 delete mode 100644 app/Models/Product.php
 ...
To https://github.com/phuochoavn/websamnghe.git
   xyz9876..abc1234  main -> main
```

✅ **Checkpoint 1:** Local rollback complete and pushed

---

### PHẦN 2: ROLLBACK TRÊN VPS

**Thời gian:** 5-10 phút

**⚠️ CRITICAL:** Database rollback sẽ XÓA TẤT CẢ DATA trong 15 bảng custom!

#### BƯỚC 7: Backup Database TRƯỚC KHI ROLLBACK

**📍 Trên VPS:**

```bash
ssh deploy@69.62.82.145

cd /var/www/samnghethaycu.com

# Backup database (QUAN TRỌNG!)
php artisan db:seed --class=DatabaseSeeder --no-interaction || true
mysqldump -u samnghethaycu_user -p samnghethaycu > ~/backup-before-rollback-$(date +%Y%m%d-%H%M%S).sql
# Enter password khi được hỏi (check ~/credentials/database.txt)

# Verify backup created
ls -lh ~/backup-before-rollback-*.sql
# Phải thấy file backup với size > 0
```

**Mong đợi:**

```
-rw-r--r-- 1 deploy deploy 15K Nov 22 15:30 backup-before-rollback-20251122-153045.sql
```

✅ **Checkpoint 2.1:** Database backed up

---

#### BƯỚC 8: Pull Rollback Code from GitHub

```bash
cd /var/www/samnghethaycu.com

# Pull rollback commit
git fetch origin
git pull origin main

# Or use deploy-sam (will auto-pull)
deploy-sam
```

**Kết quả mong đợi:**

```
🚀 Starting deployment...
📥 Step 1/8: Pulling latest code from GitHub...
✅ Code updated
abc1234 revert: rollback database schema to WORKFLOW-5 state
...
```

✅ **Checkpoint 2.2:** Rollback code pulled

---

#### BƯỚC 9: Rollback Database Migrations

**⚠️ CRITICAL:** Bước này sẽ XÓA 15 bảng và TẤT CẢ DATA!

```bash
# Kiểm tra migrations hiện tại
php artisan migrate:status
```

**Mong đợi:**

```
Migration name ................................................ Batch / Status
0001_01_01_000000_create_users_table ....................... [1] Ran
0001_01_01_000001_create_cache_table ....................... [1] Ran
0001_01_01_000002_create_jobs_table ........................ [1] Ran
2025_11_22_123456_create_categories_table .................. [2] Ran
2025_11_22_123457_create_brands_table ...................... [2] Ran
... (tất cả 15 migrations của WORKFLOW-6)
```

**Rollback batch 2 (tất cả WORKFLOW-6 migrations):**

```bash
# Rollback 1 batch (sẽ xóa tất cả migrations trong batch 2)
php artisan migrate:rollback --step=1

# Nếu có nhiều batches, có thể rollback specific batch
# php artisan migrate:rollback --batch=2
```

**Kết quả mong đợi:**

```
   INFO  Rolling back migrations.

  2025_11_22_123510_add_fields_to_users_table ................. 8.23ms DONE
  2025_11_22_123509_create_order_status_histories_table ....... 6.45ms DONE
  2025_11_22_123508_create_coupon_usages_table ................ 5.67ms DONE
  2025_11_22_123507_create_reviews_table ...................... 7.89ms DONE
  2025_11_22_123506_create_order_items_table .................. 6.12ms DONE
  2025_11_22_123505_create_orders_table ....................... 8.91ms DONE
  2025_11_22_123504_create_coupons_table ...................... 7.23ms DONE
  2025_11_22_123503_create_addresses_table .................... 6.78ms DONE
  2025_11_22_123502_create_posts_table ........................ 8.12ms DONE
  2025_11_22_123501_create_product_images_table ............... 5.56ms DONE
  2025_11_22_123500_create_product_variants_table ............. 7.34ms DONE
  2025_11_22_123459_create_products_table ..................... 9.89ms DONE
  2025_11_22_123458_create_post_categories_table .............. 6.67ms DONE
  2025_11_22_123457_create_brands_table ....................... 5.45ms DONE
  2025_11_22_123456_create_categories_table ................... 6.23ms DONE
```

**Verify rollback:**

```bash
# Check migrations status
php artisan migrate:status
```

**Mong đợi:**

```
Migration name ................................................ Batch / Status
0001_01_01_000000_create_users_table ....................... [1] Ran
0001_01_01_000001_create_cache_table ....................... [1] Ran
0001_01_01_000002_create_jobs_table ........................ [1] Ran

(No WORKFLOW-6 migrations listed)
```

**Check database:**

```bash
php artisan db:show
```

**Mong đợi:**

```
Tables ........................................................ 9
(Down from 23 to 9 - correct!)

Schema / Table
- cache
- cache_locks
- failed_jobs
- job_batches
- jobs
- migrations
- password_reset_tokens
- sessions
- users
```

✅ **Checkpoint 2.3:** Database rolled back successfully

---

#### BƯỚC 10: Clear All Caches

```bash
# Clear application cache
php artisan optimize:clear

# Rebuild caches
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Reload PHP-FPM
sudo systemctl reload php8.4-fpm
```

**Kết quả mong đợi:**

```
   INFO  Clearing cached bootstrap files.
   ...
   INFO  Configuration cached successfully.
   INFO  Routes cached successfully.
   INFO  Blade templates cached successfully.
```

✅ **Checkpoint 2.4:** Caches cleared

---

### PHẦN 3: VERIFICATION - ROLLBACK HOÀN TẤT

**Thời gian:** 3 phút

#### Verify on VPS

**📍 Trên VPS:**

```bash
cd /var/www/samnghethaycu.com

# 1. Check migrations
php artisan migrate:status
# Phải chỉ có 3 migrations (users, cache, jobs)

# 2. Check models exist
ls app/Models/
# Phải chỉ có: User.php

# 3. Check Filament resources
ls app/Filament/Resources/ 2>/dev/null || echo "No resources (correct!)"
# Phải: No resources (correct!)

# 4. Check database tables
php artisan db:table --database=mysql | grep -E "(products|categories|orders)" || echo "Tables not found (correct!)"
# Phải: Tables not found (correct!)

# 5. Check database count
php artisan tinker
```

**In tinker:**

```php
// Count tables
collect(DB::select('SHOW TABLES'))->count()
// Should return: 9 (down from 23)

// Verify User model
App\Models\User::count()
// Should return: 1 (admin user still exists)

// Verify columns restored
$user = App\Models\User::first();
$user
// Should NOT show: phone, avatar, date_of_birth, gender, last_login_at

exit
```

**Kết quả mong đợi:**

```php
> collect(DB::select('SHOW TABLES'))->count()
= 9

> App\Models\User::count()
= 1

> $user = App\Models\User::first();
= App\Models\User {#5678
    id: 1,
    name: "Admin",
    email: "admin@samnghethaycu.com",
    email_verified_at: null,
    created_at: "2025-11-21 10:30:00",
    updated_at: "2025-11-21 10:30:00",
  }
(No phone, avatar, etc. - correct!)
```

✅ **Checkpoint 3.1:** VPS verification passed

---

#### Verify in Browser

**📍 Browser:**

```
1. Visit: https://samnghethaycu.com/admin
2. Login: admin@samnghethaycu.com / Admin@123456
3. Should see: Dashboard ONLY (no Products, Categories, etc.)
4. Sidebar navigation: Only Dashboard + User menu
5. No errors in browser console (F12)
```

**Mong đợi:**

```
Sidebar Navigation:
├── Dashboard
└── (no other resources)
```

✅ **Checkpoint 3.2:** Browser verification passed

---

### ✅ ROLLBACK COMPLETE CHECKLIST

```
PHẦN 1: LOCAL ROLLBACK
✅ Filament resources deleted (9 resources)
✅ Models deleted (14 models, kept User.php)
✅ User model restored to WORKFLOW-5 version
✅ Migration files deleted (15 migrations)
✅ Local verification passed
✅ Rollback committed and pushed to GitHub

PHẦN 2: VPS ROLLBACK
✅ Database backed up before rollback
✅ Rollback code pulled from GitHub
✅ Database migrations rolled back (15 tables dropped)
✅ Users table columns restored (phone, avatar, etc. removed)
✅ Caches cleared and rebuilt
✅ PHP-FPM reloaded

PHẦN 3: VERIFICATION
✅ Only 9 tables exist (down from 23)
✅ Only 1 model exists (User.php)
✅ No Filament resources exist
✅ Truy cập admin panel thành công (Dashboard only)
✅ No errors in browser
✅ User can login successfully
✅ Database consistent
```

**Total Time:** ~15-20 phút

---

### 🎉 Rollback Success!

**Bạn đã về trạng thái WORKFLOW-5:**

```
✅ Laravel 12 working
✅ Filament Admin Panel installed
✅ Admin user exists (admin@samnghethaycu.com)
✅ Dashboard accessible
✅ No custom database schema
✅ No custom Filament resources
✅ Ready to redo WORKFLOW-6 hoặc làm việc khác
```

**Database backup location:**

```bash
# On VPS
ls -lh ~/backup-before-rollback-*.sql

# To restore backup if needed:
mysql -u samnghethaycu_user -p samnghethaycu < ~/backup-before-rollback-YYYYMMDD-HHMMSS.sql
```

---

## 🔧 TROUBLESHOOTING

### Issue 1: Migration Failed - Foreign Key Constraint

**Error:**

```
SQLSTATE[HY000]: General error: 1215 Cannot add foreign key constraint
(SQL: alter table `products` add constraint `products_category_id_foreign`...)
```

**Cause:** Parent table chưa được migrate trước child table

**Fix on LOCAL:**

```powershell
# Check migration order
ls database\migrations\ | Sort-Object

# Migrations PHẢI theo thứ tự:
# 1. categories, brands, post_categories (no dependencies)
# 2. products, posts (need categories)
# 3. product_variants, product_images (need products)
# 4. addresses (need users)
# 5. coupons (no dependencies)
# 6. orders (need users, addresses, coupons)
# 7. order_items (need orders, products)
# 8. reviews (need products, users, orders)
# 9. coupon_usages (need coupons, users, orders)
# 10. order_status_histories (need orders)
```

**If order wrong:**

```powershell
# Rollback locally
php artisan migrate:rollback

# Rename migration files to fix timestamp order
# Example: Change from 2025_11_22_123456 to 2025_11_22_123400
# Make sure parent tables have earlier timestamps

# Migrate again
php artisan migrate

# Test successful, then commit and deploy
git add database/migrations/
git commit -m "fix: correct migration order for foreign keys"
git push origin main
```

**On VPS:**

```bash
# If already deployed with wrong order
php artisan migrate:rollback

# Pull fixed migrations
git pull origin main

# Migrate again
php artisan migrate --force
```

✅ **Solution:** Migrations now run in correct order

---

### Issue 2: Filament Resources Not Showing in Sidebar

**Symptom:** Dashboard loads but no Products, Categories, etc. in sidebar

**📍 Check on VPS:**

```bash
# 1. Verify resource files exist
ls app/Filament/Resources/
# Should show: ProductResource.php, CategoryResource.php, etc.

# 2. Check resource registered
php artisan route:list | grep admin
# Should show routes for all resources

# 3. Clear Filament cache
php artisan filament:optimize-clear

# 4. Clear all caches
php artisan optimize:clear
php artisan config:cache
php artisan route:cache

# 5. Rebuild Filament assets
php artisan filament:assets

# 6. Fix permissions
sudo chown -R www-data:www-data app/Filament/
sudo chmod -R 755 app/Filament/

# 7. Reload PHP-FPM
sudo systemctl reload php8.4-fpm
```

**Check browser console (F12):**

```
Look for JavaScript errors
If you see 404 errors for Filament assets:
→ Run: php artisan filament:assets on VPS
→ Run: php artisan livewire:publish --assets on VPS
```

✅ **Solution:** Resources now visible

---

### Issue 3: Cannot Create Records - 500 Error

**Error in browser:** 500 Internal Server Error when clicking "Create"

**📍 Check Laravel logs on VPS:**

```bash
tail -50 /var/www/samnghethaycu.com/storage/logs/laravel.log
```

**Common causes:**

**A) Missing fillable field:**

```
Error: Add [field_name] to fillable property
```

**Fix:**

```powershell
# On LOCAL
notepad app\Models\YourModel.php

# Add missing field to $fillable array
protected $fillable = [
    'existing_field',
    'missing_field', // Add this
];

# Commit and deploy
git add app/Models/YourModel.php
git commit -m "fix: add missing field to fillable"
git push origin main

# On VPS
deploy-sam
```

**B) Database connection issue:**

```bash
# Test connection
php artisan db:show

# If fails, check .env
cat .env | grep DB_

# Verify matches credentials
cat ~/credentials/database.txt

# Fix .env if wrong
nano .env
# Update DB_PASSWORD, DB_DATABASE, etc.

# Clear config cache
php artisan config:clear
php artisan config:cache
```

**C) Validation error:**

Check Filament resource form validation rules - may be too strict

✅ **Solution:** Record creation working

---

### Issue 4: Auto-generated Forms Look Wrong

**Symptom:**
- All fields in one long column
- No proper field types (everything is TextInput)
- Foreign keys show IDs instead of names

**This is NORMAL!**

`--generate` creates BASIC forms. We'll customize in WORKFLOW-8.

**For now:**
- ✅ Verify CRUD operations work
- ✅ Don't worry about UI/UX
- ⏳ Will customize forms in WORKFLOW-8

**Quick improvements (optional):**

```php
// Example: CategoryResource.php
public static function form(Form $form): Form
{
    return $form
        ->schema([
            Forms\Components\TextInput::make('name')
                ->required()
                ->maxLength(255)
                ->live(onBlur: true)
                ->afterStateUpdated(fn (Set $set, ?string $state) => $set('slug', Str::slug($state))),

            Forms\Components\TextInput::make('slug')
                ->required()
                ->maxLength(255)
                ->unique(ignoreRecord: true),

            Forms\Components\Textarea::make('description')
                ->rows(3),

            Forms\Components\FileUpload::make('image')
                ->image()
                ->directory('categories'),

            Forms\Components\Select::make('parent_id')
                ->relationship('parent', 'name')
                ->searchable()
                ->preload(),

            Forms\Components\TextInput::make('order')
                ->numeric()
                ->default(0),

            Forms\Components\Toggle::make('is_active')
                ->default(true),
        ]);
}
```

But this is for WORKFLOW-8! For now, just make sure CRUD works.

---

### Issue 5: Database Shows Only 3 Tables After Migration

**Symptom:** `php artisan db:show` shows Tables: 3 instead of 23

**Check:**

```bash
# Check migration status
php artisan migrate:status
```

**If migrations show "Pending":**

```bash
# Run migrations
php artisan migrate --force

# Verify
php artisan db:show
# Should now show: Tables: 23
```

**If migrations failed silently:**

```bash
# Check Laravel logs
tail -100 storage/logs/laravel.log

# Look for migration errors
# Fix errors and re-run
```

✅ **Solution:** All 23 tables created

---

### Issue 6: User Table Missing New Columns

**Symptom:** User model doesn't have phone, avatar, etc.

**Check:**

```bash
php artisan db:table users --database=mysql
```

**If columns missing:**

```bash
# Check migration status
php artisan migrate:status | grep add_fields_to_users

# If not ran:
php artisan migrate --force

# If ran but columns still missing:
# Rollback that specific migration
php artisan migrate:rollback --step=1

# Run again
php artisan migrate --force
```

✅ **Solution:** User table has all columns

---

### Issue 7: Permission Denied on VPS During Deploy

**Error:**

```
Permission denied: app/Filament/Resources/
```

**Fix:**

```bash
# Fix ownership
sudo chown -R deploy:www-data /var/www/samnghethaycu.com

# Fix permissions
sudo chmod -R 775 /var/www/samnghethaycu.com/app
sudo chmod -R 775 /var/www/samnghethaycu.com/database

# ACL for deploy user
sudo setfacl -R -m u:deploy:rwx /var/www/samnghethaycu.com
sudo setfacl -R -d -m u:deploy:rwx /var/www/samnghethaycu.com

# Try deploy again
deploy-sam
```

✅ **Solution:** Deployment successful

---

### Issue 8: Slug Not Auto-Generating

**Symptom:** Creating category/product requires manual slug entry

**This is normal** with `--generate`. Auto-slug will be added in WORKFLOW-7 with Spatie Sluggable.

**For now:** Manually enter slugs or add basic JavaScript:

```php
// In Resource form
TextInput::make('name')
    ->live(onBlur: true)
    ->afterStateUpdated(fn (Set $set, ?string $state) => $set('slug', Str::slug($state))),

TextInput::make('slug')
    ->required(),
```

Better solution in WORKFLOW-7!

---

### Issue 9: Foreign Keys Show IDs Instead of Names

**Symptom:** Category shows "1" instead of "Sâm Hàn Quốc"

**Fix in Resource:**

```php
// ProductResource.php
Forms\Components\Select::make('category_id')
    ->relationship('category', 'name') // Will work after WORKFLOW-7 adds relationships
    ->required(),
```

**But wait!** Relationships haven't been defined yet (WORKFLOW-7).

**For now:** Just verify the ID saves correctly. Pretty display in WORKFLOW-8.

---

### Issue 10: "Class Category not found" Error

**Error:**

```
Class "App\Models\Category" not found
```

**Cause:** Composer autoload cache outdated

**Fix:**

```powershell
# On LOCAL
composer dump-autoload

# Test
php artisan tinker
> App\Models\Category::count()
> exit

# If works, commit and deploy
```

```bash
# On VPS
composer dump-autoload

# Or use deploy-sam (runs composer install automatically)
deploy-sam
```

✅ **Solution:** Models autoloaded correctly

---

## 📊 DATABASE RELATIONSHIPS DIAGRAM

**Visual representation of table relationships:**

```
┌─────────────────┐
│     USERS       │
│  - id           │
│  - name         │
│  - email        │
│  - phone        │
│  - avatar       │
└────────┬────────┘
         │
         │ 1:N (has many)
         ├──────────────────────┐
         │                      │
         ▼                      ▼
┌─────────────────┐    ┌─────────────────┐
│   ADDRESSES     │    │     ORDERS      │
│  - id           │    │  - id           │
│  - user_id  ────┤    │  - user_id  ────┤
│  - full_name    │    │  - order_number │
│  - phone        │    │  - status       │
│  - city         │    │  - total        │
└─────────────────┘    └────────┬────────┘
         ▲                       │
         │                       │ 1:N
         │                       ▼
         │              ┌─────────────────┐
         │              │   ORDER_ITEMS   │
         │              │  - id           │
         │              │  - order_id ────┤
         │              │  - product_id   │
         │              │  - quantity     │
         │              │  - subtotal     │
         └──────────────┤  - variant_id   │
                        └─────────┬───────┘
                                  │
                                  │ N:1 (belongs to)
                                  ▼
┌─────────────────┐     ┌─────────────────┐
│   CATEGORIES    │     │    PRODUCTS     │
│  - id           │     │  - id           │
│  - name         │     │  - category_id ─┤◄────┐
│  - slug         │     │  - brand_id     │     │
│  - parent_id ───┤─┐   │  - name         │     │ N:1
└─────────────────┘ │   │  - price        │     │
         ▲          │   │  - sku          │     │
         │          │   └────────┬────────┘     │
         │ Self     │            │              │
         │ Ref      │            │ 1:N          │
         └──────────┘            ├──────────────┘
                                 │
                     ┌───────────┼───────────┐
                     │           │           │
                     ▼           ▼           ▼
          ┌─────────────┐ ┌─────────┐ ┌──────────┐
          │  VARIANTS   │ │ IMAGES  │ │ REVIEWS  │
          │- product_id─┤ │-prod_id─┤ │-prod_id──┤
          │- sku        │ │- path   │ │- user_id │
          │- price      │ │- order  │ │- rating  │
          └─────────────┘ └─────────┘ └──────────┘

┌─────────────────┐     ┌─────────────────┐
│     BRANDS      │     │     COUPONS     │
│  - id           │     │  - id           │
│  - name         │     │  - code         │
│  - logo         │     │  - discount     │
└─────────────────┘     └────────┬────────┘
         ▲                        │
         │ N:1                    │ N:M (via coupon_usages)
         │                        │
         └────────────────────────┴──────────┐
                                             │
┌─────────────────┐              ┌───────────▼──────┐
│POST_CATEGORIES  │              │  COUPON_USAGES   │
│  - id           │              │  - coupon_id     │
│  - name         │              │  - user_id       │
└────────┬────────┘              │  - order_id      │
         │                       └──────────────────┘
         │ 1:N
         ▼
┌─────────────────┐
│     POSTS       │
│  - id           │
│  - category_id ─┤
│  - user_id      │
│  - title        │
│  - content      │
└─────────────────┘
```

**Chú giải:**
- `─┤` : Quan hệ khóa ngoại
- `1:N` : Một-nhiều (hasMany)
- `N:1` : Nhiều-một (belongsTo)
- `N:M` : Nhiều-nhiều (belongsToMany qua pivot)
- `Self Ref` : Tự tham chiếu (danh mục cha-con)

**Note:** Relationships sẽ được implement trong WORKFLOW-7!

---

**Tạo ngày:** 2025-11-22
**Cập nhật:** 2025-11-22
**Phiên bản:** 6.2 Professional Vietnamese (Fixed Structure & Logic)
**Thời gian:** 25-35 minutes actual
**Định dạng:** Standardized with WORKFLOW-5 Professional Vietnamese Edition

---

**END OF WORKFLOW 6** 🗄️
