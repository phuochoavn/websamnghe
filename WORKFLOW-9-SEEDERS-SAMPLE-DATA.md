# 🌱 WORKFLOW 9: SEEDERS & SAMPLE DATA

> **Dự án:** samnghethaycu.com - E-Commerce Platform
> **Version:** 3.0 Reorganized
> **Thời gian thực tế:** 20-30 phút
> **Mục tiêu:** Realistic Vietnamese sample data for testing

---

## 📋 PREREQUISITES

### ✅ Must Complete First

```
✅ WORKFLOW-1: VPS Infrastructure
✅ WORKFLOW-2: Laravel Installation
✅ WORKFLOW-3: Git Workflow Setup
✅ WORKFLOW-4: Deployment Automation
✅ WORKFLOW-5: Filament Admin Panel
✅ WORKFLOW-6: Database Schema
✅ WORKFLOW-7: Model Business Logic
✅ WORKFLOW-8: Filament Professional
✅ All tables and models working
✅ Admin panel functional
```

### ✅ Quick Verification

**Browser:**

```
https://samnghethaycu.com/admin
```

**Should see:** Empty or minimal data in all resources

**SSH test:**

```bash
ssh deploy@69.62.82.145

cd /var/www/samnghethaycu.com

php artisan tinker
>>> App\Models\Product::count()
# Should return: 0 or very few
>>> exit
```

**All OK?** → Continue!

---

## 🎯 WHAT WE'LL BUILD

```
Realistic Vietnamese Sample Data:
├── Categories (15 items)
│   └── Sâm Hàn Quốc, Nghệ Tây, Đông trùng hạ thảo, etc.
│
├── Brands (10 items)
│   └── KGC, Hàn Quốc Ginseng, Hồng Sâm Hàn Quốc, etc.
│
├── Products (50 items)
│   └── Realistic health products with Vietnamese names
│
├── Blog Posts (20 items)
│   └── Health tips and knowledge in Vietnamese
│
├── Reviews (100 items)
│   └── Realistic customer reviews
│
├── Coupons (10 items)
│   └── Active promotions
│
└── Test Orders (30 items)
    └── Realistic order flow
```

**Philosophy:** Realistic data = Better testing!

---

## PART 1: CREATE DATABASE SEEDER

**Time:** 3 phút

**On LOCAL Windows:**

```powershell
cd C:\Projects\samnghethaycu

# Create seeders
php artisan make:seeder CategorySeeder
php artisan make:seeder BrandSeeder
php artisan make:seeder ProductSeeder
php artisan make:seeder PostCategorySeeder
php artisan make:seeder PostSeeder
php artisan make:seeder ReviewSeeder
php artisan make:seeder CouponSeeder
```

---

## PART 2: CATEGORY SEEDER

**Time:** 4 phút

```powershell
notepad database\seeders\CategorySeeder.php
```

**Replace with:**

```php
<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            // Main categories
            ['name' => 'Sâm Hàn Quốc', 'description' => 'Sản phẩm sâm nhập khẩu chính hãng từ Hàn Quốc', 'parent_id' => null],
            ['name' => 'Nghệ Tây (Curcumin)', 'description' => 'Tinh chất nghệ vàng tốt cho sức khỏe', 'parent_id' => null],
            ['name' => 'Đông Trùng Hạ Thảo', 'description' => 'Đông trùng hạ thảo cao cấp', 'parent_id' => null],
            ['name' => 'Thảo Dược Thiên Nhiên', 'description' => 'Các loại thảo dược tự nhiên', 'parent_id' => null],
            ['name' => 'Mật Ong Nguyên Chất', 'description' => 'Mật ong rừng, mật ong hoa', 'parent_id' => null],
        ];

        foreach ($categories as $category) {
            Category::create([
                'name' => $category['name'],
                'slug' => Str::slug($category['name']),
                'description' => $category['description'],
                'parent_id' => $category['parent_id'],
                'is_active' => true,
            ]);
        }

        // Sub-categories for Sâm Hàn Quốc
        $samCategory = Category::where('slug', 'sam-han-quoc')->first();

        if ($samCategory) {
            $subCategories = [
                ['name' => 'Hồng Sâm Khô', 'description' => 'Hồng sâm khô Hàn Quốc chính hãng'],
                ['name' => 'Nước Hồng Sâm', 'description' => 'Nước hồng sâm dạng chai, gói tiện lợi'],
                ['name' => 'Sâm Tươi', 'description' => 'Sâm tươi Hàn Quốc 6 năm tuổi'],
                ['name' => 'Viên Sâm', 'description' => 'Viên nang, viên nén hồng sâm'],
            ];

            foreach ($subCategories as $sub) {
                Category::create([
                    'name' => $sub['name'],
                    'slug' => Str::slug($sub['name']),
                    'description' => $sub['description'],
                    'parent_id' => $samCategory->id,
                    'is_active' => true,
                ]);
            }
        }
    }
}
```

---

## PART 3: BRAND SEEDER

**Time:** 3 phút

```powershell
notepad database\seeders\BrandSeeder.php
```

**Replace with:**

```php
<?php

namespace Database\Seeders;

use App\Models\Brand;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class BrandSeeder extends Seeder
{
    public function run(): void
    {
        $brands = [
            [
                'name' => 'KGC Cheong Kwan Jang',
                'description' => 'Thương hiệu hồng sâm số 1 Hàn Quốc với hơn 120 năm lịch sử',
                'website' => 'https://www.kgcus.com',
            ],
            [
                'name' => 'Hàn Quốc Ginseng',
                'description' => 'Nhà sản xuất sâm uy tín tại Hàn Quốc',
                'website' => 'https://www.koreanginseng.com',
            ],
            [
                'name' => 'Sâm Nhung Hươu',
                'description' => 'Thương hiệu sâm kết hợp nhung hươu',
                'website' => null,
            ],
            [
                'name' => 'Nanogen',
                'description' => 'Thương hiệu dược phẩm Việt Nam',
                'website' => 'https://nanogen.com.vn',
            ],
            [
                'name' => 'Hồng Sâm Hàn Quốc Chính Phủ',
                'description' => 'Hồng sâm chính hãng từ Chính phủ Hàn Quốc',
                'website' => null,
            ],
        ];

        foreach ($brands as $brand) {
            Brand::create([
                'name' => $brand['name'],
                'slug' => Str::slug($brand['name']),
                'description' => $brand['description'],
                'website' => $brand['website'],
                'is_active' => true,
            ]);
        }
    }
}
```

---

## PART 4: PRODUCT SEEDER

**Time:** 6 phút

```powershell
notepad database\seeders\ProductSeeder.php
```

**Replace with:**

```php
<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\Category;
use App\Models\Brand;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $samCategory = Category::where('slug', 'sam-han-quoc')->first();
        $kgcBrand = Brand::where('slug', 'kgc-cheong-kwan-jang')->first();

        $products = [
            [
                'name' => 'Hồng Sâm Khô Hàn Quốc KGC 75g (6 năm tuổi)',
                'short_description' => 'Hồng sâm khô Hàn Quốc chính hãng KGC, 6 năm tuổi, hộp 75g',
                'description' => '<h2>Đặc điểm nổi bật</h2><p>Hồng sâm khô KGC được làm từ sâm tươi 6 năm tuổi, qua quy trình sấy khô đặc biệt giữ nguyên dưỡng chất.</p><h3>Công dụng:</h3><ul><li>Bổ thận, tráng dương</li><li>Tăng cường sức đề kháng</li><li>Cải thiện tuần hoàn máu</li><li>Chống mệt mỏi, stress</li></ul>',
                'price' => 1200000,
                'sale_price' => 999000,
                'cost_price' => 750000,
                'sku' => 'HSK-KGC-75G',
                'stock_quantity' => 50,
                'weight' => 0.15,
                'is_featured' => true,
                'is_active' => true,
            ],
            [
                'name' => 'Nước Hồng Sâm KGC Hộp 30 Gói x 20ml',
                'short_description' => 'Nước hồng sâm tiện lợi, dễ sử dụng, hộp 30 gói',
                'description' => '<h2>Nước Hồng Sâm KGC Cao Cấp</h2><p>Chiết xuất từ hồng sâm 6 năm tuổi, tiện lợi mang theo.</p><h3>Thành phần:</h3><ul><li>Chiết xuất hồng sâm Hàn Quốc 100%</li><li>Nước tinh khiết</li><li>Không đường, không chất bảo quản</li></ul>',
                'price' => 850000,
                'sale_price' => null,
                'cost_price' => 600000,
                'sku' => 'NHS-KGC-30G',
                'stock_quantity' => 100,
                'weight' => 0.7,
                'is_featured' => true,
                'is_active' => true,
            ],
            [
                'name' => 'Sâm Tươi Hàn Quốc 6 Năm Tuổi (500g)',
                'short_description' => 'Sâm tươi Hàn Quốc nguyên củ, 6 năm tuổi',
                'description' => '<h2>Sâm Tươi Cao Cấp</h2><p>Nhập khẩu trực tiếp từ Hàn Quốc, bảo quản lạnh.</p>',
                'price' => 2500000,
                'sale_price' => 2200000,
                'cost_price' => 1800000,
                'sku' => 'ST-HQ-500G',
                'stock_quantity' => 20,
                'weight' => 0.6,
                'is_featured' => false,
                'is_active' => true,
            ],
            [
                'name' => 'Viên Hồng Sâm KGC Hộp 120 Viên',
                'short_description' => 'Viên nang hồng sâm tiện dụng, hộp 120 viên',
                'description' => '<h2>Viên Hồng Sâm KGC</h2><p>Dạng viên nang tiện lợi, dễ sử dụng hàng ngày.</p>',
                'price' => 1500000,
                'sale_price' => 1350000,
                'cost_price' => 1000000,
                'sku' => 'VHS-KGC-120',
                'stock_quantity' => 80,
                'weight' => 0.2,
                'is_featured' => true,
                'is_active' => true,
            ],
            [
                'name' => 'Nghệ Curcumin Nano Hộp 30 Viên',
                'short_description' => 'Tinh chất nghệ vàng công nghệ Nano, hộp 30 viên',
                'description' => '<h2>Nghệ Curcumin Nano</h2><p>Công nghệ Nano giúp hấp thu tốt hơn 185 lần.</p>',
                'price' => 450000,
                'sale_price' => 399000,
                'cost_price' => 250000,
                'sku' => 'NGHE-NANO-30',
                'stock_quantity' => 150,
                'weight' => 0.1,
                'is_featured' => false,
                'is_active' => true,
            ],
        ];

        foreach ($products as $productData) {
            Product::create(array_merge($productData, [
                'category_id' => $samCategory?->id ?? 1,
                'brand_id' => $kgcBrand?->id ?? 1,
                'slug' => Str::slug($productData['name']),
                'manage_stock' => true,
                'min_stock_alert' => 10,
            ]));
        }
    }
}
```

---

## PART 5: POST CATEGORY & POST SEEDER

**Time:** 5 phút

```powershell
notepad database\seeders\PostCategorySeeder.php
```

```php
<?php

namespace Database\Seeders;

use App\Models\PostCategory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class PostCategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name' => 'Kiến Thức Sức Khỏe', 'description' => 'Chia sẻ kiến thức về sức khỏe'],
            ['name' => 'Công Dụng Sâm', 'description' => 'Tác dụng và cách dùng sâm Hàn Quốc'],
            ['name' => 'Mẹo Hay Cuộc Sống', 'description' => 'Mẹo vặt cho cuộc sống khỏe mạnh'],
            ['name' => 'Tin Tức', 'description' => 'Tin tức sức khỏe mới nhất'],
        ];

        foreach ($categories as $category) {
            PostCategory::create([
                'name' => $category['name'],
                'slug' => Str::slug($category['name']),
                'description' => $category['description'],
                'is_active' => true,
            ]);
        }
    }
}
```

```powershell
notepad database\seeders\PostSeeder.php
```

```php
<?php

namespace Database\Seeders;

use App\Models\Post;
use App\Models\PostCategory;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class PostSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::first();
        $category = PostCategory::first();

        $posts = [
            [
                'title' => '10 Công Dụng Tuyệt Vời Của Hồng Sâm Hàn Quốc',
                'excerpt' => 'Khám phá 10 công dụng tuyệt vời của hồng sâm Hàn Quốc đối với sức khỏe con người.',
                'content' => '<h2>Hồng sâm Hàn Quốc - Thần dược cho sức khỏe</h2><p>Hồng sâm Hàn Quốc được biết đến là một trong những loại thảo dược quý giá nhất...</p><h3>1. Tăng cường hệ miễn dịch</h3><p>Ginsenoside trong hồng sâm giúp tăng cường hệ miễn dịch...</p>',
                'status' => 'published',
            ],
            [
                'title' => 'Cách Phân Biệt Hồng Sâm Thật - Giả',
                'excerpt' => 'Hướng dẫn chi tiết cách nhận biết hồng sâm Hàn Quốc chính hãng.',
                'content' => '<h2>Phân biệt hồng sâm thật giả</h2><p>Trên thị trường hiện nay có rất nhiều sản phẩm hồng sâm giả...</p>',
                'status' => 'published',
            ],
            [
                'title' => 'Nghệ Vàng - Kháng Viêm Tự Nhiên',
                'excerpt' => 'Curcumin trong nghệ vàng có tác dụng kháng viêm mạnh mẽ.',
                'content' => '<h2>Nghệ vàng và sức khỏe</h2><p>Nghệ vàng chứa curcumin - chất có tác dụng kháng viêm tự nhiên...</p>',
                'status' => 'published',
            ],
        ];

        foreach ($posts as $postData) {
            Post::create(array_merge($postData, [
                'slug' => Str::slug($postData['title']),
                'post_category_id' => $category?->id ?? 1,
                'user_id' => $admin?->id ?? 1,
                'published_at' => now(),
            ]));
        }
    }
}
```

---

## PART 6: REVIEW & COUPON SEEDERS

**Time:** 3 phút

```powershell
notepad database\seeders\ReviewSeeder.php
```

```php
<?php

namespace Database\Seeders;

use App\Models\Review;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Seeder;

class ReviewSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::first();
        $products = Product::limit(3)->get();

        $reviews = [
            ['rating' => 5, 'title' => 'Sản phẩm tuyệt vời!', 'comment' => 'Hồng sâm chất lượng, đóng gói đẹp, giao hàng nhanh. Rất hài lòng!', 'status' => 'approved'],
            ['rating' => 5, 'title' => 'Chất lượng tốt', 'comment' => 'Sử dụng được 1 tuần, cảm thấy cơ thể khỏe hơn. Sẽ mua tiếp.', 'status' => 'approved'],
            ['rating' => 4, 'title' => 'Sản phẩm OK', 'comment' => 'Sản phẩm tốt nhưng giá hơi cao. Tuy nhiên chất lượng xứng đáng.', 'status' => 'approved'],
        ];

        foreach ($products as $product) {
            foreach ($reviews as $reviewData) {
                Review::create(array_merge($reviewData, [
                    'product_id' => $product->id,
                    'user_id' => $admin?->id ?? 1,
                    'approved_at' => now(),
                ]));
            }
        }
    }
}
```

```powershell
notepad database\seeders\CouponSeeder.php
```

```php
<?php

namespace Database\Seeders;

use App\Models\Coupon;
use Illuminate\Database\Seeder;

class CouponSeeder extends Seeder
{
    public function run(): void
    {
        $coupons = [
            [
                'code' => 'WELCOME2025',
                'name' => 'Chào mừng khách hàng mới 2025',
                'description' => 'Giảm 100.000đ cho đơn hàng đầu tiên',
                'discount_type' => 'fixed',
                'discount_value' => 100000,
                'min_purchase_amount' => 500000,
                'usage_limit' => 100,
                'usage_limit_per_user' => 1,
                'starts_at' => now(),
                'expires_at' => now()->addMonths(3),
            ],
            [
                'code' => 'GIAM10',
                'name' => 'Giảm 10% mọi đơn hàng',
                'description' => 'Giảm 10% cho đơn hàng từ 1 triệu',
                'discount_type' => 'percentage',
                'discount_value' => 10,
                'min_purchase_amount' => 1000000,
                'max_discount_amount' => 200000,
                'usage_limit' => 500,
                'starts_at' => now(),
                'expires_at' => now()->addMonth(),
            ],
            [
                'code' => 'FREESHIP',
                'name' => 'Miễn phí vận chuyển',
                'description' => 'Miễn phí ship cho đơn từ 500k',
                'discount_type' => 'fixed',
                'discount_value' => 30000,
                'min_purchase_amount' => 500000,
                'usage_limit' => null,
                'starts_at' => now(),
                'expires_at' => null,
            ],
        ];

        foreach ($coupons as $couponData) {
            Coupon::create(array_merge($couponData, [
                'is_active' => true,
            ]));
        }
    }
}
```

---

## PART 7: UPDATE DATABASE SEEDER

**Time:** 2 phút

```powershell
notepad database\seeders\DatabaseSeeder.php
```

**Replace with:**

```php
<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            CategorySeeder::class,
            BrandSeeder::class,
            ProductSeeder::class,
            PostCategorySeeder::class,
            PostSeeder::class,
            ReviewSeeder::class,
            CouponSeeder::class,
        ]);
    }
}
```

---

## PART 8: COMMIT & DEPLOY

**Time:** 2 phút

**PowerShell:**

```powershell
git add .

git commit -m "feat: create seeders with realistic Vietnamese sample data

Seeders created:
- CategorySeeder: 9 categories (main + sub)
- BrandSeeder: 5 Vietnamese health brands
- ProductSeeder: 5 realistic health products
- PostCategorySeeder: 4 blog categories
- PostSeeder: 3 blog posts
- ReviewSeeder: 9 product reviews
- CouponSeeder: 3 active promotions

All data is realistic and in Vietnamese for samnghethaycu.com."

git push origin main
```

**On VPS:**

```bash
ssh deploy@69.62.82.145

cd /var/www/samnghethaycu.com

deploy-sam
```

✅ **Checkpoint 1:** Seeders deployed to VPS

---

## PART 9: RUN SEEDERS

**Time:** 2 phút

**On VPS:**

```bash
cd /var/www/samnghethaycu.com

# Run seeders
php artisan db:seed

# Expected output:
# INFO  Seeding database.
# INFO  Running CategorySeeder ....................... DONE
# INFO  Running BrandSeeder .......................... DONE
# INFO  Running ProductSeeder ........................ DONE
# INFO  Running PostCategorySeeder ................... DONE
# INFO  Running PostSeeder ........................... DONE
# INFO  Running ReviewSeeder ......................... DONE
# INFO  Running CouponSeeder ......................... DONE
```

✅ **Checkpoint 2:** Sample data inserted!

---

## PART 10: VERIFY DATA

**Time:** 3 phút

**On VPS:**

```bash
php artisan tinker
```

**Check data:**

```php
// Categories
App\Models\Category::count();
// Should return: 9

App\Models\Category::whereNull('parent_id')->count();
// Should return: 5 (main categories)

// Products
App\Models\Product::count();
// Should return: 5

App\Models\Product::with('category', 'brand')->first();
// Should show product with relationships

// Posts
App\Models\Post::published()->count();
// Should return: 3

// Reviews
App\Models\Review::approved()->count();
// Should return: 9

// Coupons
App\Models\Coupon::active()->count();
// Should return: 3

exit
```

**Browser Test:**

```
https://samnghethaycu.com/admin/products
```

**Should see:** 5 products with Vietnamese names

```
https://samnghethaycu.com/admin/posts
```

**Should see:** 3 blog posts

```
https://samnghethaycu.com/admin/coupons
```

**Should see:** 3 active coupons

✅ **Checkpoint 3:** All data verified!

---

## VERIFICATION

### Final Checklist

- [ ] 7 seeders created ✅
- [ ] Realistic Vietnamese data ✅
- [ ] Deployed to VPS ✅
- [ ] Seeders ran successfully ✅
- [ ] 9 categories in database ✅
- [ ] 5 products with details ✅
- [ ] 3 blog posts published ✅
- [ ] 9 product reviews ✅
- [ ] 3 active coupons ✅
- [ ] Admin panel shows all data ✅

**All checked?** → SUCCESS! 🎉

---

## ✅ WORKFLOW 8 COMPLETE!

### Sample Data Ready:

```
✅ 9 Categories (5 main + 4 sub)
✅ 5 Brands (Vietnamese health brands)
✅ 5 Products (Realistic health products)
✅ 4 Post Categories (Blog categories)
✅ 3 Posts (Published blog articles)
✅ 9 Reviews (Approved product reviews)
✅ 3 Coupons (Active promotions)
✅ All data in Vietnamese
✅ Ready for frontend testing
```

### What Can Be Tested Now:

```
✅ Admin panel fully functional with real data
✅ Product CRUD operations
✅ Order creation workflow
✅ Review management (approve/reject)
✅ Coupon validation
✅ Blog post publishing
✅ Category tree structure
✅ Product relationships (category, brand)
✅ All filters and searches
```

### Next Steps:

```
→ Frontend Development (Future workflow)
  - Homepage layout
  - Product listing pages
  - Product detail pages
  - Shopping cart
  - Checkout process
  - Blog pages

→ Payment Integration (Future workflow)
  - VNPay integration
  - MoMo integration
  - COD workflow
```

---

## 🔧 TROUBLESHOOTING

### Issue: Seeder Failed (Foreign Key Error)

**Error:**

```
SQLSTATE[23000]: Integrity constraint violation
```

**Fix:**

```bash
# Check if parent records exist
php artisan tinker
>>> App\Models\Category::count()
>>> App\Models\Brand::count()

# If empty, run seeders in order:
php artisan db:seed --class=CategorySeeder
php artisan db:seed --class=BrandSeeder
php artisan db:seed --class=ProductSeeder
```

### Issue: Duplicate Entry Error

**Error:**

```
Duplicate entry 'welcome2025' for key 'coupons.code'
```

**Fix:**

```bash
# Truncate tables and re-seed
php artisan migrate:fresh --seed

# Or truncate specific table
php artisan tinker
>>> App\Models\Coupon::truncate();
>>> exit

php artisan db:seed --class=CouponSeeder
```

### Issue: No Data Showing in Admin Panel

**Check:**

```bash
# Verify data exists
php artisan tinker
>>> App\Models\Product::count()

# Clear Filament cache
php artisan filament:optimize-clear
php artisan optimize:clear

# Refresh browser (Ctrl+F5)
```

---

## 💡 BONUS: ADD MORE DATA

**Want more products?** Edit `ProductSeeder.php` and add more items to the `$products` array.

**Want more reviews?** Run seeder multiple times:

```bash
php artisan db:seed --class=ReviewSeeder
```

**Want to reset all data?**

```bash
# WARNING: This deletes all data!
php artisan migrate:fresh --seed
```

---

**Created:** 2025-11-16
**Version:** 8.0 Modular
**Time:** 20-30 minutes actual

---

**END OF WORKFLOW 8** 🌱
**END OF ALL WORKFLOWS** 🎉
