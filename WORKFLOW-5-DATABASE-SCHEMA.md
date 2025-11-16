# 🗄️ WORKFLOW 5: DATABASE SCHEMA

> **Dự án:** samnghethaycu.com - E-Commerce Platform
> **Version:** 5.0 Modular
> **Thời gian thực tế:** 25-35 phút
> **Mục tiêu:** 23 tables + 15 models + 9 Filament resources

---

## 📋 PREREQUISITES

### ✅ Must Complete First

```
✅ WORKFLOW-1: Git Foundation
✅ WORKFLOW-2: VPS Infrastructure
✅ WORKFLOW-3: Laravel Installation
✅ WORKFLOW-4: Filament Admin Panel
✅ Admin panel working at: https://samnghethaycu.com/admin
```

### ✅ Quick Verification

**Browser:**

```
https://samnghethaycu.com/admin
```

**Should see:** Filament dashboard (logged in)

**SSH test:**

```bash
ssh deploy@69.62.82.145

cd /var/www/samnghethaycu.com

# Check database connection
php artisan db:show
# Should show: samnghethaycu database

# Check Filament
php artisan route:list | grep admin
# Should show admin routes
```

**All OK?** → Continue!

---

## 🎯 WHAT WE'LL BUILD

```
23 Database Tables:
├── Core E-Commerce (8 tables)
│   ├── users
│   ├── products
│   ├── product_variants
│   ├── product_images
│   ├── categories
│   ├── brands
│   ├── orders
│   └── order_items
│
├── Supporting Tables (7 tables)
│   ├── addresses
│   ├── reviews
│   ├── coupons
│   ├── coupon_usages
│   ├── order_status_histories
│   ├── posts
│   └── post_categories
│
└── Laravel System (8 tables - already exist)
    ├── migrations
    ├── password_reset_tokens
    ├── sessions
    ├── cache, cache_locks
    └── jobs, job_batches, failed_jobs

15 Eloquent Models + 9 Filament Resources
```

**Philosophy:** Database-first design, Git-driven deployment!

---

## PART 1: CREATE MIGRATIONS (LOCAL)

**Time:** 10 phút

**Windows PowerShell:**

```powershell
cd C:\Projects\samnghethaycu

# Create all migrations at once
php artisan make:migration create_categories_table
php artisan make:migration create_brands_table
php artisan make:migration create_products_table
php artisan make:migration create_product_variants_table
php artisan make:migration create_product_images_table
php artisan make:migration create_addresses_table
php artisan make:migration create_orders_table
php artisan make:migration create_order_items_table
php artisan make:migration create_order_status_histories_table
php artisan make:migration create_reviews_table
php artisan make:migration create_coupons_table
php artisan make:migration create_coupon_usages_table
php artisan make:migration create_posts_table
php artisan make:migration create_post_categories_table
php artisan make:migration add_fields_to_users_table
```

### 1.1. Categories Migration

```powershell
notepad database\migrations\*_create_categories_table.php
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
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->string('image')->nullable();
            $table->foreignId('parent_id')->nullable()->constrained('categories')->nullOnDelete();
            $table->integer('order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();

            $table->index(['slug', 'is_active']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('categories');
    }
};
```

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
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->string('logo')->nullable();
            $table->string('website')->nullable();
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

### 1.3. Products Migration

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
            $table->foreignId('category_id')->constrained()->cascadeOnDelete();
            $table->foreignId('brand_id')->nullable()->constrained()->nullOnDelete();

            $table->string('name');
            $table->string('slug')->unique();
            $table->text('short_description')->nullable();
            $table->longText('description')->nullable();

            $table->decimal('price', 12, 2);
            $table->decimal('sale_price', 12, 2)->nullable();
            $table->decimal('cost_price', 12, 2)->nullable();

            $table->string('sku')->unique();
            $table->string('barcode')->nullable();

            $table->integer('stock_quantity')->default(0);
            $table->integer('min_stock_alert')->default(10);

            $table->decimal('weight', 8, 2)->nullable();
            $table->decimal('length', 8, 2)->nullable();
            $table->decimal('width', 8, 2)->nullable();
            $table->decimal('height', 8, 2)->nullable();

            $table->string('featured_image')->nullable();

            $table->boolean('is_featured')->default(false);
            $table->boolean('is_active')->default(true);
            $table->boolean('manage_stock')->default(true);

            $table->text('meta_title')->nullable();
            $table->text('meta_description')->nullable();
            $table->text('meta_keywords')->nullable();

            $table->timestamps();
            $table->softDeletes();

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

### 1.4. Product Variants Migration

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

            $table->string('name');
            $table->string('sku')->unique();

            $table->decimal('price', 12, 2);
            $table->decimal('sale_price', 12, 2)->nullable();

            $table->integer('stock_quantity')->default(0);

            $table->string('image')->nullable();

            $table->json('attributes')->nullable();

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

### 1.5. Product Images Migration

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

### 1.6. Addresses Migration

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

            $table->string('full_name');
            $table->string('phone');
            $table->string('address_line_1');
            $table->string('address_line_2')->nullable();
            $table->string('city');
            $table->string('district')->nullable();
            $table->string('ward')->nullable();
            $table->string('postal_code')->nullable();

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

### 1.7. Orders Migration

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

            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('shipping_address_id')->nullable()->constrained('addresses')->nullOnDelete();
            $table->foreignId('coupon_id')->nullable()->constrained()->nullOnDelete();

            $table->enum('status', [
                'pending',
                'processing',
                'packed',
                'shipped',
                'delivered',
                'cancelled',
                'refunded'
            ])->default('pending');

            $table->enum('payment_method', ['cod', 'vnpay', 'momo'])->default('cod');
            $table->enum('payment_status', ['pending', 'paid', 'failed', 'refunded'])->default('pending');

            $table->decimal('subtotal', 12, 2);
            $table->decimal('tax', 12, 2)->default(0);
            $table->decimal('shipping_fee', 12, 2)->default(0);
            $table->decimal('discount_amount', 12, 2)->default(0);
            $table->decimal('total', 12, 2);

            $table->text('customer_note')->nullable();
            $table->text('admin_note')->nullable();

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

### 1.8. Order Items Migration

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

            $table->string('product_name');
            $table->string('product_sku');
            $table->decimal('price', 12, 2);
            $table->integer('quantity');
            $table->decimal('subtotal', 12, 2);

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

### 1.9. Order Status Histories Migration

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

### 1.10. Reviews Migration

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

            $table->integer('rating');
            $table->string('title')->nullable();
            $table->text('comment');

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

### 1.11. Coupons Migration

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

            $table->string('code')->unique();
            $table->string('name');
            $table->text('description')->nullable();

            $table->enum('discount_type', ['fixed', 'percentage']);
            $table->decimal('discount_value', 12, 2);

            $table->decimal('min_purchase_amount', 12, 2)->nullable();
            $table->decimal('max_discount_amount', 12, 2)->nullable();

            $table->integer('usage_limit')->nullable();
            $table->integer('usage_limit_per_user')->nullable();

            $table->timestamp('starts_at')->nullable();
            $table->timestamp('expires_at')->nullable();

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

### 1.12. Coupon Usages Migration

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

### 1.13. Post Categories Migration

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

### 1.14. Posts Migration

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

            $table->string('title');
            $table->string('slug')->unique();
            $table->text('excerpt')->nullable();
            $table->longText('content');

            $table->string('featured_image')->nullable();

            $table->enum('status', ['draft', 'published', 'archived'])->default('draft');

            $table->timestamp('published_at')->nullable();

            $table->integer('views_count')->default(0);

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

### 1.15. Update Users Table

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

✅ **Checkpoint 1:** All migrations created

---

## PART 2: CREATE MODELS (LOCAL)

**Time:** 8 phút

**PowerShell:**

```powershell
# Create all models at once
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

**Note:** We'll add relationships in WORKFLOW-6. For now, just basic models with fillable fields.

### 2.1. Category Model

```powershell
notepad app\Models\Category.php
```

**Code:**

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
    ];
}
```

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

### 2.3. Product Model

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
        'is_featured' => 'boolean',
        'is_active' => 'boolean',
        'manage_stock' => 'boolean',
    ];
}
```

### 2.4. Other Models (Quick Creation)

**For remaining models, create with basic fillable and casts:**

**ProductVariant.php:**

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
        'product_id', 'name', 'sku', 'price', 'sale_price',
        'stock_quantity', 'image', 'attributes', 'is_active',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'sale_price' => 'decimal:2',
        'attributes' => 'array',
        'is_active' => 'boolean',
    ];
}
```

**ProductImage.php:**

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductImage extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id', 'image_path', 'alt_text', 'order', 'is_primary',
    ];

    protected $casts = [
        'is_primary' => 'boolean',
    ];
}
```

**Address.php:**

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
        'user_id', 'full_name', 'phone', 'address_line_1', 'address_line_2',
        'city', 'district', 'ward', 'postal_code', 'type', 'is_default',
    ];

    protected $casts = [
        'is_default' => 'boolean',
    ];
}
```

**Order.php:**

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
        'order_number', 'user_id', 'shipping_address_id', 'coupon_id',
        'status', 'payment_method', 'payment_status',
        'subtotal', 'tax', 'shipping_fee', 'discount_amount', 'total',
        'customer_note', 'admin_note', 'transaction_id',
        'paid_at', 'shipped_at', 'delivered_at',
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

**OrderItem.php:**

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id', 'product_id', 'product_variant_id',
        'product_name', 'product_sku', 'price', 'quantity', 'subtotal',
        'variant_attributes',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'subtotal' => 'decimal:2',
        'variant_attributes' => 'array',
    ];
}
```

**OrderStatusHistory.php:**

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderStatusHistory extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id', 'user_id', 'old_status', 'new_status', 'note',
    ];
}
```

**Review.php:**

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
        'product_id', 'user_id', 'order_id',
        'rating', 'title', 'comment', 'status', 'approved_at',
    ];

    protected $casts = [
        'rating' => 'integer',
        'approved_at' => 'datetime',
    ];
}
```

**Coupon.php:**

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
        'code', 'name', 'description',
        'discount_type', 'discount_value',
        'min_purchase_amount', 'max_discount_amount',
        'usage_limit', 'usage_limit_per_user',
        'starts_at', 'expires_at', 'is_active',
    ];

    protected $casts = [
        'discount_value' => 'decimal:2',
        'min_purchase_amount' => 'decimal:2',
        'max_discount_amount' => 'decimal:2',
        'starts_at' => 'datetime',
        'expires_at' => 'datetime',
        'is_active' => 'boolean',
    ];
}
```

**CouponUsage.php:**

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CouponUsage extends Model
{
    use HasFactory;

    protected $fillable = [
        'coupon_id', 'user_id', 'order_id', 'discount_amount',
    ];

    protected $casts = [
        'discount_amount' => 'decimal:2',
    ];
}
```

**Post.php:**

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
        'post_category_id', 'user_id',
        'title', 'slug', 'excerpt', 'content',
        'featured_image', 'status', 'published_at', 'views_count',
        'meta_title', 'meta_description', 'meta_keywords',
    ];

    protected $casts = [
        'published_at' => 'datetime',
        'views_count' => 'integer',
    ];
}
```

**PostCategory.php:**

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
        'name', 'slug', 'description', 'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];
}
```

✅ **Checkpoint 2:** All models created

---

## PART 3: COMMIT & DEPLOY

**Time:** 3 phút

**PowerShell:**

```powershell
# Check changes
git status

# Add all
git add .

# Commit
git commit -m "feat: create database schema with 23 tables and 15 models

- 15 migrations: categories, brands, products, variants, images,
  addresses, orders, order_items, reviews, coupons, posts, etc.
- 15 Eloquent models with fillable and casts
- Ready for Filament resource generation

Database schema ready for production deployment."

# Push
git push origin main
```

**On VPS:**

```bash
ssh deploy@69.62.82.145

cd /var/www/samnghethaycu.com

# Deploy!
deploy-sam

# Migrations will auto-run
# Should see: Migration table created successfully
# Should see: Migrated: YYYY_MM_DD_create_categories_table
# ... (all 15 migrations)
```

✅ **Checkpoint 3:** Database deployed to VPS

---

## PART 4: GENERATE FILAMENT RESOURCES

**Time:** 7 phút

**On LOCAL Windows:**

```powershell
# Generate resources for main entities
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

**What --generate does:**
- Creates Resource class
- Auto-generates form fields
- Auto-generates table columns
- Creates List, Create, Edit pages
- Detects relationships

**Expected output:**

```
Successfully created Product!

Resource: app\Filament\Resources\ProductResource.php
Pages:
- app\Filament\Resources\ProductResource\Pages\ListProducts.php
- app\Filament\Resources\ProductResource\Pages\CreateProduct.php
- app\Filament\Resources\ProductResource\Pages\EditProduct.php
```

**Commit & Deploy:**

```powershell
git add .
git commit -m "feat: generate Filament resources for 9 core entities

Auto-generated CRUD resources with forms, tables, and pages:
- ProductResource (e-commerce)
- CategoryResource, BrandResource
- OrderResource, ReviewResource
- CouponResource (promotions)
- PostResource, PostCategoryResource (blog)
- AddressResource (shipping)

Admin panel now has full CRUD operations."

git push origin main
```

**On VPS:**

```bash
deploy-sam
```

✅ **Checkpoint 4:** Filament resources generated

---

## PART 5: TEST ADMIN PANEL

**Time:** 5 phút

**Browser:**

```
https://samnghethaycu.com/admin
```

**Login:** admin@samnghethaycu.com / Admin@123456

**Test CRUD operations:**

### 5.1. Create Category

**Navigate:** Categories → Create

**Fill:**
- Name: `Sâm Hàn Quốc`
- Description: `Sản phẩm sâm nhập khẩu từ Hàn Quốc`
- Is Active: ✅

**Save**

**Should see:** Category created successfully!

### 5.2. Create Brand

**Navigate:** Brands → Create

**Fill:**
- Name: `KGC Cheong Kwan Jang`
- Description: `Thương hiệu sâm nổi tiếng từ Hàn Quốc`
- Website: `https://www.kgcus.com`
- Is Active: ✅

**Save**

### 5.3. Create Product

**Navigate:** Products → Create

**Fill:**
- Name: `Sâm Tươi Hàn Quốc 6 Năm Tuổi`
- Category: `Sâm Hàn Quốc`
- Brand: `KGC Cheong Kwan Jang`
- Price: `450000`
- Sale Price: `399000`
- SKU: `SAM-HQ-6Y-001`
- Stock Quantity: `50`
- Is Active: ✅

**Save**

**Should see:** Product created successfully!

### 5.4. Verify Database

**SSH to VPS:**

```bash
cd /var/www/samnghethaycu.com

php artisan tinker
```

**In Tinker:**

```php
// Check tables
Schema::hasTable('products')
// Should return: true

// Count records
App\Models\Product::count()
// Should return: 1

App\Models\Category::count()
// Should return: 1

App\Models\Brand::count()
// Should return: 1

// Fetch product with relationships
$product = App\Models\Product::with('category', 'brand')->first();
$product->name
// Error expected: relationships not defined yet (WORKFLOW-6 will fix)

exit
```

✅ **Checkpoint 5:** Admin panel CRUD working!

---

## VERIFICATION

### Final Checklist

- [ ] 15 migrations created ✅
- [ ] 15 models created ✅
- [ ] Database deployed to VPS ✅
- [ ] 9 Filament resources generated ✅
- [ ] Admin panel showing all resources ✅
- [ ] Can create categories, brands, products ✅
- [ ] Records visible in database ✅
- [ ] Git commit & push successful ✅

**All checked?** → SUCCESS! 🎉

---

## ✅ WORKFLOW 5 COMPLETE!

### Database Ready:

```
✅ 23 tables created (15 custom + 8 Laravel system)
✅ 15 Eloquent models with fillable & casts
✅ 9 Filament resources auto-generated
✅ Admin panel CRUD operations working
✅ Database deployed to production VPS
✅ Basic data entry tested
✅ Git-driven deployment verified
```

### What We Have Now:

```
Admin Panel Resources:
├── Products (CRUD working)
├── Categories (CRUD working)
├── Brands (CRUD working)
├── Orders (CRUD working)
├── Reviews (CRUD working)
├── Coupons (CRUD working)
├── Posts (CRUD working)
├── Post Categories (CRUD working)
└── Addresses (CRUD working)
```

### What's Missing (Next Workflows):

```
⏳ Model relationships (belongsTo, hasMany, etc.)
⏳ Business logic methods
⏳ Accessors & mutators
⏳ Scopes and query builders
⏳ Filament customization (tabs, filters, widgets)
⏳ Sample data seeders
```

### Next Step:

```
→ WORKFLOW-6-MODEL-BUSINESS-LOGIC.md
  Add relationships, business logic, and methods to all 15 models
```

---

## 🔧 TROUBLESHOOTING

### Issue: Migration Failed (Foreign Key Constraint)

**Error:**

```
SQLSTATE[HY000]: General error: 1215 Cannot add foreign key constraint
```

**Fix:**

```bash
# Check migration order
ls -la database/migrations/

# Ensure parent tables are migrated first:
# 1. categories (before products)
# 2. brands (before products)
# 3. products (before product_variants, product_images)
# 4. users (before orders, reviews, posts)
# 5. addresses (before orders)
# 6. orders (before order_items)
```

**If order is wrong:**

```bash
# Rollback
php artisan migrate:rollback

# Rename migration files to fix order
# (change timestamp prefix)

# Migrate again
php artisan migrate
```

### Issue: Filament Resource Not Showing

**Check:**

```bash
# Verify resource file exists
ls app/Filament/Resources/ProductResource.php

# Clear Filament cache
php artisan filament:optimize-clear

# Clear all caches
php artisan optimize:clear

# Check User model implements FilamentUser
grep -A 3 "implements FilamentUser" app/Models/User.php
```

### Issue: Cannot Create Records (500 Error)

**Check logs:**

```bash
tail -50 storage/logs/laravel.log
```

**Common causes:**

1. **Missing fillable fields**
   ```php
   // Add to model
   protected $fillable = ['field_name'];
   ```

2. **Validation error**
   - Check Filament resource form validation

3. **Database connection**
   ```bash
   php artisan db:show
   ```

### Issue: Auto-generated Forms Look Wrong

**This is normal!** Filament --generate creates basic forms. We'll customize in WORKFLOW-7.

**For now:** Just verify CRUD works, don't worry about UI.

---

**Created:** 2025-11-16
**Version:** 5.0 Modular
**Time:** 25-35 minutes actual

---

**END OF WORKFLOW 5** 🗄️
