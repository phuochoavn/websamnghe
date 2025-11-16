# CLAUDE.md - AI Assistant Guide

**Project**: samnghethaycu.com - E-Commerce Platform
**Repository**: websamnghe
**Last Updated**: 2025-11-16
**Status**: Backend Complete - Frontend Development Phase

---

## 📋 Table of Contents

1. [Project Overview](#project-overview)
2. [Technology Stack](#technology-stack)
3. [Project Structure](#project-structure)
4. [Development Phases](#development-phases)
5. [Database Schema](#database-schema)
6. [Development Workflow](#development-workflow)
7. [VPS Deployment](#vps-deployment)
8. [Coding Conventions](#coding-conventions)
9. [Git Workflow](#git-workflow)
10. [Testing Strategy](#testing-strategy)
11. [AI Assistant Guidelines](#ai-assistant-guidelines)
12. [Common Tasks](#common-tasks)
13. [Troubleshooting](#troubleshooting)

---

## 🎯 Project Overview

**samnghethaycu.com** is a full-stack e-commerce platform specializing in natural health products (Ginseng, Turmeric, Herbal medicines) with an integrated blog for health knowledge.

### Project Goals

- ✅ E-commerce website for natural health products
- ✅ Blog system for health knowledge sharing
- ✅ Standardized boilerplate for future projects
- ✅ Professional admin panel with Filament
- ✅ Production-ready deployment on VPS

### Live URLs

- **Website**: https://samnghethaycu.com
- **Admin Panel**: https://samnghethaycu.com/admin
- **VPS IP**: 69.62.82.145

### Admin Credentials

```
Email: admin@samnghethaycu.com
Password: Admin@123456
```

### SSH Access

```bash
ssh deploy@69.62.82.145
Password: Deploy@2025
```

---

## 🛠 Technology Stack

### Backend

- **Framework**: Laravel 12
- **Admin Panel**: Filament v3
- **Real-time**: Livewire 3
- **Language**: PHP 8.4

### Frontend

- **Template Engine**: Blade
- **JavaScript**: Alpine.js
- **CSS Framework**: Tailwind CSS v4

### Database & Cache

- **Primary Database**: MySQL 8
- **Cache/Sessions**: Redis 7
- **ORM**: Eloquent

### Storage & CDN

- **Cloud Storage**: Cloudflare R2 (S3-compatible)
- **Local Storage**: Laravel Storage with symlinks

### Payment Gateways

- **Phase 1**: VNPay + COD (Cash on Delivery)
- **Phase 2**: MoMo integration

### Infrastructure

- **Server**: VPS (Ubuntu 24.04 LTS)
- **Web Server**: Nginx
- **Process Manager**: PHP-FPM 8.4
- **SSL**: Let's Encrypt (Certbot)
- **Firewall**: UFW
- **Security**: Fail2ban

### Development Tools

- **Package Manager**: Composer 2.x
- **Build Tools**: Vite
- **Node.js**: v20.x
- **Version Control**: Git

---

## 📁 Project Structure

```
samnghethaycu.com/
├── app/
│   ├── Filament/          # Filament admin resources
│   │   └── Resources/     # CRUD resources for admin panel
│   ├── Http/
│   │   ├── Controllers/   # Application controllers
│   │   └── Middleware/    # Custom middleware
│   ├── Livewire/          # Livewire components
│   ├── Models/            # Eloquent models (15 models)
│   │   ├── User.php
│   │   ├── Product.php
│   │   ├── Category.php
│   │   ├── Brand.php
│   │   ├── Order.php
│   │   ├── OrderItem.php
│   │   ├── ProductVariant.php
│   │   ├── ProductImage.php
│   │   ├── Review.php
│   │   ├── Post.php
│   │   ├── PostCategory.php
│   │   ├── Coupon.php
│   │   ├── CouponUsage.php
│   │   ├── Address.php
│   │   └── OrderStatusHistory.php
│   └── Services/          # Business logic services
│
├── bootstrap/
│   └── cache/             # ⚠️ MUST be real directory, NOT symlink
│
├── config/                # Configuration files
│   ├── database.php
│   ├── filesystems.php
│   ├── filament.php
│   └── ...
│
├── database/
│   ├── migrations/        # Database migrations (23 tables)
│   ├── seeders/           # Database seeders
│   └── factories/         # Model factories
│
├── public/                # Web root
│   ├── index.php
│   └── storage/           # Symlinked to storage/app/public
│
├── resources/
│   ├── views/             # Blade templates
│   │   ├── layouts/
│   │   ├── components/
│   │   ├── products/
│   │   └── blog/
│   ├── css/               # Tailwind CSS
│   └── js/                # Alpine.js components
│
├── routes/
│   ├── web.php            # Web routes
│   ├── api.php            # API routes
│   └── console.php        # Artisan commands
│
├── storage/               # ⚠️ Critical permissions: www-data:www-data 775
│   ├── app/
│   │   └── public/        # User uploads (products, brands, etc.)
│   ├── framework/
│   │   ├── cache/
│   │   ├── sessions/
│   │   └── views/
│   └── logs/
│
├── tests/
│   ├── Feature/           # Feature tests
│   └── Unit/              # Unit tests
│
├── .env                   # Environment configuration
├── artisan                # CLI tool
├── composer.json          # PHP dependencies
├── package.json           # Node dependencies
└── vite.config.js         # Vite configuration
```

### Key Directories

- **`app/Models/`**: 15 Eloquent models with relationships
- **`app/Filament/Resources/`**: Admin panel CRUD resources (9 resources)
- **`database/migrations/`**: Database schema (23 tables total)
- **`storage/app/public/`**: User uploads (symlinked to public/storage)
- **`bootstrap/cache/`**: Framework cache (must be real directory, not symlink)

---

## 🚀 Development Phases

### ✅ Backend Complete (100%) - Following 9 Modular Workflows

All backend development has been completed following a professional, infrastructure-first approach with 9 modular workflows:

#### **WORKFLOW-1: VPS Infrastructure** ✅ (20-25 min)
- [x] LEMP stack (Nginx, MySQL 8, PHP 8.4)
- [x] Redis 7 cache
- [x] Composer, Node.js 20
- [x] SSL certificate (Let's Encrypt)
- [x] Firewall & security (UFW)
- [x] Database credentials saved

**See:** `WORKFLOW-1-VPS-INFRASTRUCTURE.md`

#### **WORKFLOW-2: Laravel Installation** ✅ (15-20 min)
- [x] Laravel 12 via Git deployment
- [x] Nginx virtual host configuration
- [x] Storage symlink setup
- [x] .env production configuration
- [x] Health check endpoint
- [x] Permissions with ACL

**See:** `WORKFLOW-2-LARAVEL-INSTALLATION.md`

#### **WORKFLOW-3: Git Workflow Setup** ✅ (15-20 min)
- [x] Local Git configuration
- [x] GitHub repository setup
- [x] Personal Access Token authentication
- [x] VPS Git setup with SSH keys
- [x] Repository cloned to VPS
- [x] Git workflow: Local → GitHub → VPS

**See:** `WORKFLOW-3-GIT-WORKFLOW-SETUP.md`

#### **WORKFLOW-4: Deployment Automation** ✅ (10-15 min)
- [x] deploy-sam automation script (8-step deployment)
- [x] One-command deployment workflow
- [x] Sudo configuration for deploy user
- [x] Deployment alias setup
- [x] End-to-end workflow tested

**See:** `WORKFLOW-4-DEPLOYMENT-AUTOMATION.md`

#### **WORKFLOW-5: Filament Admin Panel** ✅ (10-15 min)
- [x] Filament v3 installation
- [x] Admin panel at /admin
- [x] Admin user creation
- [x] User model with FilamentUser interface
- [x] Dashboard accessible

**See:** `WORKFLOW-5-FILAMENT-ADMIN-PANEL.md`

#### **WORKFLOW-6: Database Schema** ✅ (25-35 min)
- [x] 15 migrations (23 tables total)
- [x] 15 Eloquent models with fillable & casts
- [x] 9 Filament resources auto-generated
- [x] CRUD operations working
- [x] Deployed to production

**See:** `WORKFLOW-6-DATABASE-SCHEMA.md`

#### **WORKFLOW-7: Model Business Logic** ✅ (30-40 min)
- [x] 50+ relationships (belongsTo, hasMany, etc.)
- [x] 30+ scopes for filtering
- [x] 40+ accessors & mutators
- [x] 25+ business logic methods
- [x] Helper methods (URLs, labels, calculations)
- [x] All models production-ready

**See:** `WORKFLOW-7-MODEL-BUSINESS-LOGIC.md`

#### **WORKFLOW-8: Filament Professional** ✅ (35-45 min)
- [x] ProductResource: Tabs, filters, bulk actions
- [x] OrderResource: Custom actions, status management
- [x] Dashboard widgets (stats + latest orders)
- [x] Review approve/reject workflow
- [x] Coupon validity tracking
- [x] Professional UI with badges and colors

**See:** `WORKFLOW-8-FILAMENT-PROFESSIONAL.md`

#### **WORKFLOW-9: Seeders & Sample Data** ✅ (20-30 min)
- [x] 9 categories (Vietnamese health products)
- [x] 5 brands (Vietnamese brands)
- [x] 5 realistic products
- [x] 3 blog posts
- [x] 9 product reviews
- [x] 3 active coupons
- [x] All data in Vietnamese

**See:** `WORKFLOW-9-SEEDERS-SAMPLE-DATA.md`

---

### 📊 Current Project Status

```
✅ VPS Infrastructure (LEMP + SSL)
✅ Laravel 12 Application
✅ Git Workflow Setup (Local → GitHub → VPS)
✅ Deployment Automation (One-command deployment)
✅ Filament v3 Admin Panel
✅ 23 Database Tables
✅ 15 Eloquent Models (with full relationships)
✅ 9 Professional Filament Resources
✅ Dashboard with Widgets
✅ Realistic Vietnamese Sample Data
✅ Production Deployment Working
```

**Total Backend Time:** ~3-3.5 hours (following 9 workflows)
**Actual Time (experienced):** ~2-2.5 hours

---

### 📅 Next Phases: Frontend Development (PLANNED)

**Target Time**: TBD

- [ ] Homepage layout
- [ ] Product listing pages
- [ ] Product detail pages
- [ ] Shopping cart functionality
- [ ] Checkout process
- [ ] User authentication pages
- [ ] Blog listing and detail pages
- [ ] Responsive design (mobile-first)

### 📅 Payment Integration (PLANNED)

- [ ] VNPay integration
- [ ] COD (Cash on Delivery) workflow
- [ ] MoMo integration (Phase 2)
- [ ] Payment webhook handling
- [ ] Order confirmation emails

### 📅 Testing & Optimization (PLANNED)

- [ ] Unit tests for models
- [ ] Feature tests for user flows
- [ ] Performance optimization
- [ ] SEO optimization
- [ ] Security audit
- [ ] Load testing

---

## 🗄 Database Schema

### 23 Tables Overview

#### Core E-Commerce Tables (8)

1. **users** - User accounts (customers, admin)
2. **products** - Product catalog
3. **product_variants** - Product variations (size, color, etc.)
4. **product_images** - Product image gallery
5. **categories** - Product categories (nested)
6. **brands** - Product brands
7. **orders** - Customer orders
8. **order_items** - Order line items

#### Supporting Tables (7)

9. **addresses** - Customer shipping addresses
10. **reviews** - Product reviews & ratings
11. **coupons** - Discount coupons
12. **coupon_usages** - Coupon usage tracking
13. **order_status_histories** - Order status audit trail
14. **posts** - Blog posts
15. **post_categories** - Blog categories

#### Laravel System Tables (8)

16. **password_reset_tokens**
17. **sessions**
18. **cache**
19. **cache_locks**
20. **jobs**
21. **job_batches**
22. **failed_jobs**
23. **migrations**

### Key Relationships

```
User
├── hasMany → Orders
├── hasMany → Reviews
├── hasMany → Addresses
└── hasMany → CouponUsages

Product
├── belongsTo → Category
├── belongsTo → Brand
├── hasMany → ProductVariants
├── hasMany → ProductImages
├── hasMany → Reviews
└── hasMany → OrderItems

Order
├── belongsTo → User
├── belongsTo → Coupon
├── belongsTo → Address (shipping)
├── hasMany → OrderItems
└── hasMany → OrderStatusHistories

Category
├── belongsTo → Category (parent)
└── hasMany → Categories (children)

Post
├── belongsTo → PostCategory
└── belongsTo → User (author)
```

### Critical Fields

#### Products Table
- `name`, `slug`, `description`
- `price`, `sale_price`, `cost_price`
- `sku`, `barcode`
- `stock_quantity`, `weight`
- `is_featured`, `is_active`
- `category_id`, `brand_id`

#### Orders Table
- `order_number` (unique)
- `user_id`, `shipping_address_id`
- `status` (pending, processing, shipped, delivered, cancelled)
- `payment_method`, `payment_status`
- `subtotal`, `tax`, `shipping_fee`, `total`
- `coupon_id`, `discount_amount`

---

## 💻 Development Workflow

### Local Development Setup

```bash
# Clone repository (when shared)
git clone <repository-url>
cd websamnghe

# Install PHP dependencies
composer install

# Install Node dependencies
npm install

# Environment setup
cp .env.example .env
php artisan key:generate

# Configure database in .env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=samnghethaycu
DB_USERNAME=your_user
DB_PASSWORD=your_password

# Run migrations
php artisan migrate

# Seed database (optional)
php artisan db:seed

# Create storage symlink
php artisan storage:link

# Create admin user
php artisan make:filament-user

# Start development servers
php artisan serve
npm run dev
```

### Daily Workflow

1. **Start of Day**
   ```bash
   git fetch origin
   git pull origin main
   composer install
   npm install
   php artisan migrate
   ```

2. **Create Feature Branch**
   ```bash
   git checkout -b feature/your-feature-name
   ```

3. **Development**
   - Make code changes
   - Test locally
   - Write/update tests
   - Check code quality

4. **Before Commit**
   ```bash
   php artisan test
   php artisan pint  # Code formatting (if configured)
   ```

5. **Commit & Push**
   ```bash
   git add .
   git commit -m "feat(scope): description"
   git push origin feature/your-feature-name
   ```

---

## 🚢 VPS Deployment

### Server Information

```
VPS IP: 69.62.82.145
OS: Ubuntu 24.04 LTS
User: deploy (group: deploy, sudo, www-data)
Web Root: /var/www/samnghethaycu.com
```

### Deployment Process

#### Method 1: Manual Deployment (Current)

```bash
# 1. On local Windows machine
cd C:\path\to\local\laravel
Compress-Archive -Path * -DestinationPath C:\update.zip -Force

# 2. Upload with WinSCP
# Host: 69.62.82.145
# User: deploy
# Password: Deploy@2025
# Upload C:\update.zip to /tmp/

# 3. On VPS
ssh deploy@69.62.82.145

cd /var/www/samnghethaycu.com

# Backup current state
sudo tar -czf ~/backup-$(date +%Y%m%d-%H%M%S).tar.gz .env storage/

# Remove old code (preserve .env and storage)
sudo rm -rf app config database routes resources public/{index.php,*.php}

# Extract new code
sudo unzip /tmp/update.zip -x ".env" "storage/*"
sudo rm /tmp/update.zip

# Critical: Fix bootstrap/cache if symlink exists
if [ -L bootstrap/cache ]; then
    sudo rm -f bootstrap/cache
    mkdir -p bootstrap/cache
fi

# Install dependencies
composer install --no-dev --optimize-autoloader --no-interaction

# Run migrations
php artisan migrate --force

# Clear and cache
php artisan optimize:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Fix permissions
sudo chown -R www-data:www-data storage bootstrap/cache
sudo chmod -R 775 storage bootstrap/cache

# Reload PHP-FPM
sudo systemctl reload php8.4-fpm

echo "✅ Deployment complete!"
```

#### Method 2: Git Deployment (Recommended for Production)

```bash
# Setup (one-time)
cd /var/www/samnghethaycu.com
git init
git remote add origin <your-repo-url>

# Deploy
git fetch origin
git reset --hard origin/main  # or specific branch
composer install --no-dev --optimize-autoloader
php artisan migrate --force
php artisan optimize
sudo chown -R www-data:www-data storage bootstrap/cache
sudo systemctl reload php8.4-fpm
```

### Critical Deployment Checks

Before deploying, verify:

- [ ] `.env` file is NOT in git (listed in .gitignore)
- [ ] Database credentials are correct
- [ ] `APP_ENV=production`
- [ ] `APP_DEBUG=false`
- [ ] `APP_KEY` is set
- [ ] Storage directories exist and have correct permissions
- [ ] `bootstrap/cache` is a real directory, not a symlink

After deployment, test:

```bash
# Health check
curl https://samnghethaycu.com/health
# Should return: healthy

# Admin panel
curl -Ik https://samnghethaycu.com/admin
# Should return: HTTP/2 200

# Check logs for errors
tail -50 /var/log/nginx/samnghethaycu-error.log
tail -50 /var/www/samnghethaycu.com/storage/logs/laravel.log
```

### Permission Fix Scripts

If permission issues occur:

```bash
# Script 1: Fix ownership
sudo chown -R deploy:www-data /var/www/samnghethaycu.com
sudo chown -R www-data:www-data /var/www/samnghethaycu.com/storage
sudo chown -R www-data:www-data /var/www/samnghethaycu.com/bootstrap/cache

# Script 2: Fix permissions
sudo chmod -R 775 /var/www/samnghethaycu.com/storage
sudo chmod -R 775 /var/www/samnghethaycu.com/bootstrap/cache
sudo chmod 775 /var/www/samnghethaycu.com/bootstrap

# Script 3: ACL (for VS Code editing)
sudo setfacl -R -m u:deploy:rwx /var/www/samnghethaycu.com
sudo setfacl -R -d -m u:deploy:rwx /var/www/samnghethaycu.com
sudo setfacl -R -m u:www-data:rwx /var/www/samnghethaycu.com
sudo setfacl -R -d -m u:www-data:rwx /var/www/samnghethaycu.com
```

---

## 📝 Coding Conventions

### Laravel-Specific Conventions

#### Naming Conventions

- **Controllers**: `PascalCase` + `Controller` suffix
  - Example: `ProductController`, `OrderController`
- **Models**: `PascalCase`, singular
  - Example: `Product`, `Order`, `OrderItem`
- **Migrations**: Snake case with timestamp prefix
  - Example: `2024_01_01_000000_create_products_table.php`
- **Routes**: Kebab case
  - Example: `/product-details`, `/checkout-success`
- **Blade Views**: Kebab case
  - Example: `product-list.blade.php`, `order-details.blade.php`
- **Database Tables**: Snake case, plural
  - Example: `products`, `order_items`, `product_variants`
- **Database Columns**: Snake case
  - Example: `created_at`, `product_name`, `is_active`
- **Methods**: camelCase
  - Example: `getProducts()`, `calculateTotal()`
- **Variables**: camelCase
  - Example: `$productList`, `$totalAmount`

#### File Organization

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

/**
 * Product Model
 *
 * Represents a product in the catalog
 */
class Product extends Model
{
    use HasFactory, SoftDeletes, HasSlug;

    // 1. Constants
    public const STATUS_ACTIVE = 'active';
    public const STATUS_INACTIVE = 'inactive';

    // 2. Properties
    protected $fillable = [
        'name',
        'slug',
        'description',
        // ...
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'price' => 'decimal:2',
        // ...
    ];

    // 3. Relationships
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function variants(): HasMany
    {
        return $this->hasMany(ProductVariant::class);
    }

    // 4. Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    // 5. Accessors & Mutators
    public function getFormattedPriceAttribute()
    {
        return number_format($this->price, 0, ',', '.') . ' ₫';
    }

    // 6. Business Logic Methods
    public function isInStock(): bool
    {
        return $this->stock_quantity > 0;
    }
}
```

### Blade Templates

```blade
{{-- resources/views/products/show.blade.php --}}

@extends('layouts.app')

@section('title', $product->name)

@section('content')
    <div class="container">
        <h1>{{ $product->name }}</h1>

        @if($product->isInStock())
            <button>Add to Cart</button>
        @else
            <p class="text-red-500">Out of Stock</p>
        @endif
    </div>
@endsection
```

### Code Style

- **Indentation**: 4 spaces (Laravel standard)
- **Line Length**: Max 120 characters
- **Braces**: Opening brace on same line (PSR-12)
- **Array Syntax**: Short syntax `[]` instead of `array()`
- **String Quotes**: Single quotes for strings, double for interpolation
- **Docblocks**: Required for all classes and public methods

### Security Best Practices

1. **Always use mass assignment protection**
   ```php
   protected $fillable = ['name', 'email']; // Whitelist
   // OR
   protected $guarded = ['id']; // Blacklist
   ```

2. **Validate all input**
   ```php
   $validated = $request->validate([
       'email' => 'required|email|max:255',
       'password' => 'required|min:8',
   ]);
   ```

3. **Use query builder or Eloquent (no raw SQL)**
   ```php
   // Good
   Product::where('is_active', true)->get();

   // Avoid (SQL injection risk)
   DB::select("SELECT * FROM products WHERE id = " . $id);
   ```

4. **CSRF protection (automatic in Laravel)**
   ```blade
   <form method="POST">
       @csrf
       <!-- form fields -->
   </form>
   ```

5. **XSS protection**
   ```blade
   {{ $userInput }}  <!-- Escaped automatically -->
   {!! $trustedHtml !!}  <!-- Unescaped, use carefully -->
   ```

---

## 🔀 Git Workflow

### Branch Naming Convention

```
feature/product-gallery       # New features
fix/cart-calculation-bug      # Bug fixes
hotfix/payment-gateway        # Production hotfixes
refactor/order-service        # Code refactoring
docs/api-documentation        # Documentation
test/checkout-flow            # Testing
chore/update-dependencies     # Maintenance
```

### Commit Message Format

Follow [Conventional Commits](https://www.conventionalcommits.org/):

```
<type>(<scope>): <subject>

<body>

<footer>
```

**Types:**
- `feat`: New feature
- `fix`: Bug fix
- `docs`: Documentation
- `style`: Formatting (no code change)
- `refactor`: Code restructuring
- `test`: Adding tests
- `chore`: Maintenance
- `perf`: Performance improvement

**Examples:**

```
feat(products): add product image gallery

Implement multi-image upload for products with drag-and-drop
reordering. Images are stored in Cloudflare R2 and cached locally.

Closes #45
```

```
fix(checkout): resolve tax calculation rounding error

Tax was being calculated on individual items instead of subtotal,
causing 1-2 VND discrepancies on large orders.

Fixes #67
```

### Git Workflow

```bash
# 1. Start from main
git checkout main
git pull origin main

# 2. Create feature branch
git checkout -b feature/product-filters

# 3. Make changes and commit
git add .
git commit -m "feat(products): add category and price filters"

# 4. Keep branch updated
git fetch origin main
git rebase origin/main

# 5. Push to remote
git push -u origin feature/product-filters

# 6. Create Pull Request (via GitHub)

# 7. After merge, cleanup
git checkout main
git pull origin main
git branch -d feature/product-filters
```

### Important Git Rules

1. **NEVER commit to main/master directly**
2. **NEVER commit `.env` file** (already in .gitignore)
3. **NEVER commit vendor/ or node_modules/** (in .gitignore)
4. **ALWAYS** pull before starting new work
5. **ALWAYS** write descriptive commit messages
6. **ALWAYS** test before pushing
7. **Keep commits atomic** (one logical change per commit)

---

## 🧪 Testing Strategy

### Test Types

#### 1. Unit Tests
Test individual methods and classes in isolation.

```php
<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Product;

class ProductTest extends TestCase
{
    public function test_product_can_calculate_discount()
    {
        $product = new Product([
            'price' => 100000,
            'sale_price' => 80000,
        ]);

        $this->assertEquals(20, $product->getDiscountPercentage());
    }

    public function test_product_is_in_stock()
    {
        $product = new Product(['stock_quantity' => 5]);
        $this->assertTrue($product->isInStock());

        $product = new Product(['stock_quantity' => 0]);
        $this->assertFalse($product->isInStock());
    }
}
```

#### 2. Feature Tests
Test complete user flows and HTTP requests.

```php
<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Product;

class CheckoutTest extends TestCase
{
    public function test_user_can_complete_checkout()
    {
        $user = User::factory()->create();
        $product = Product::factory()->create(['price' => 50000]);

        $response = $this->actingAs($user)
            ->post('/checkout', [
                'products' => [$product->id],
                'payment_method' => 'cod',
                'shipping_address' => [
                    'name' => 'Test User',
                    'phone' => '0123456789',
                    'address' => '123 Test St',
                ],
            ]);

        $response->assertStatus(302);
        $this->assertDatabaseHas('orders', [
            'user_id' => $user->id,
            'total' => 50000,
        ]);
    }
}
```

### Running Tests

```bash
# Run all tests
php artisan test

# Run specific test file
php artisan test tests/Feature/CheckoutTest.php

# Run with coverage
php artisan test --coverage

# Run specific test method
php artisan test --filter test_user_can_complete_checkout
```

### Test Database

Configure in `phpunit.xml`:

```xml
<env name="DB_CONNECTION" value="sqlite"/>
<env name="DB_DATABASE" value=":memory:"/>
```

### Coverage Goals

- **Models**: 80%+ (business logic methods)
- **Controllers**: 70%+ (user flows)
- **Services**: 90%+ (critical business logic)
- **Utilities**: 100% (helper functions)

---

## 🤖 AI Assistant Guidelines

### When Working on This Laravel Project

#### 1. Always Read Before Writing

```bash
# Before modifying a model
Read: app/Models/Product.php

# Before creating a controller
Read: app/Http/Controllers/ (check existing patterns)

# Before adding a migration
Read: database/migrations/ (check naming, structure)
```

#### 2. Understand Laravel Context

- **Check Laravel version**: This project uses Laravel 12
- **Filament version**: v3.2+ (admin panel framework)
- **Follow Laravel conventions**: Don't reinvent the wheel
- **Use Eloquent relationships**: Avoid manual joins
- **Leverage Laravel features**: Events, Jobs, Policies, etc.

#### 3. Database Migrations

**ALWAYS create migrations for schema changes:**

```bash
# Create migration
php artisan make:migration add_featured_to_products_table

# Check migration order (timestamp-based)
ls database/migrations/

# Run migrations
php artisan migrate

# Rollback if needed
php artisan migrate:rollback
```

**Migration best practices:**
- One migration per logical change
- Always provide `up()` and `down()` methods
- Test rollback functionality
- Never edit old migrations (create new ones)

#### 4. Filament Resources

When creating/modifying admin resources:

```php
// app/Filament/Resources/ProductResource.php

public static function form(Form $form): Form
{
    return $form->schema([
        TextInput::make('name')
            ->required()
            ->maxLength(255),

        // Use Filament's rich components
        RichEditor::make('description'),
        FileUpload::make('image')
            ->image()
            ->directory('products'),
    ]);
}
```

#### 5. Security Checklist

Before committing:

- [ ] All user input is validated
- [ ] Mass assignment is protected ($fillable or $guarded)
- [ ] SQL injection prevented (use Eloquent/Query Builder)
- [ ] XSS prevented (Blade auto-escapes {{ }})
- [ ] CSRF tokens present in forms (@csrf)
- [ ] Authorization checks (policies/gates) implemented
- [ ] Sensitive data not in git (.env in .gitignore)
- [ ] No hardcoded credentials
- [ ] File uploads validated (type, size)

#### 6. Performance Considerations

- **Eager loading**: Prevent N+1 queries
  ```php
  // Bad (N+1 queries)
  $products = Product::all();
  foreach ($products as $product) {
      echo $product->category->name;  // Query per product
  }

  // Good (2 queries)
  $products = Product::with('category')->get();
  foreach ($products as $product) {
      echo $product->category->name;
  }
  ```

- **Chunk large datasets**
  ```php
  Product::chunk(200, function ($products) {
      // Process 200 products at a time
  });
  ```

- **Cache expensive queries**
  ```php
  $categories = Cache::remember('categories', 3600, function () {
      return Category::with('products')->get();
  });
  ```

#### 7. Code Quality Checklist

Before marking task as complete:

- [ ] Code follows Laravel conventions
- [ ] No `dd()`, `dump()`, or debug code left behind
- [ ] Proper error handling (try-catch where appropriate)
- [ ] Validation messages are user-friendly
- [ ] Comments explain "why", not "what"
- [ ] No duplicate code (DRY principle)
- [ ] Method names are descriptive
- [ ] Tests written and passing
- [ ] No breaking changes to existing functionality

#### 8. VPS Deployment Awareness

When making changes:

- **Consider deployment**: Will this work on production VPS?
- **Environment differences**: Check .env for prod vs dev
- **Permissions**: Will www-data have access?
- **File paths**: Use Laravel helpers (`storage_path()`, etc.)
- **Cache**: Remember to clear cache after deployment

#### 9. Filament-Specific Rules

- **User must implement FilamentUser interface**
  ```php
  use Filament\Models\Contracts\FilamentUser;
  use Filament\Panel;

  class User extends Authenticatable implements FilamentUser
  {
      public function canAccessPanel(Panel $panel): bool
      {
          return true; // Customize based on your needs
      }
  }
  ```

- **Resource naming**: `ProductResource`, not `ProductsResource`
- **Use Filament components**: Don't create custom HTML for admin
- **Follow Filament docs**: https://filamentphp.com/docs

#### 10. Common Laravel Pitfalls to Avoid

❌ **Don't:**
- Use raw SQL queries (SQL injection risk)
- Fetch all records without pagination (`->get()` on large tables)
- Store files in `public/` directly (use `storage/app/public/`)
- Hardcode configuration (use `config/` and `.env`)
- Bypass validation for "quick fixes"
- Commit `.env` file
- Edit `vendor/` files directly
- Use deprecated methods (check Laravel docs)

✅ **Do:**
- Use Eloquent or Query Builder
- Paginate large datasets (`->paginate(15)`)
- Use `Storage` facade for file operations
- Use `config()` and `env()` helpers
- Always validate input
- Use `.env.example` as template
- Create custom service providers when needed
- Stay updated with Laravel release notes

---

## 📖 Common Tasks

### Adding a New Model with Migration

```bash
# 1. Create model and migration
php artisan make:model Wishlist -m

# 2. Define migration schema
# Edit: database/migrations/YYYY_MM_DD_HHMMSS_create_wishlists_table.php

# 3. Define model
# Edit: app/Models/Wishlist.php

# 4. Run migration
php artisan migrate

# 5. Test in tinker
php artisan tinker
>>> App\Models\Wishlist::count()
```

### Adding a Filament Resource

```bash
# 1. Generate resource
php artisan make:filament-resource Wishlist --generate

# 2. Customize resource
# Edit: app/Filament/Resources/WishlistResource.php

# 3. Test in admin panel
# Visit: https://samnghethaycu.com/admin/wishlists
```

### Adding a New Page Route

```php
// routes/web.php

Route::get('/products', [ProductController::class, 'index'])->name('products.index');
Route::get('/products/{slug}', [ProductController::class, 'show'])->name('products.show');
```

### Creating a Seeder

```bash
# 1. Create seeder
php artisan make:seeder ProductSeeder

# 2. Define seeder logic
# Edit: database/seeders/ProductSeeder.php

# 3. Run seeder
php artisan db:seed --class=ProductSeeder
```

### Clearing Cache

```bash
# Clear all cache
php artisan optimize:clear

# Or individually:
php artisan config:clear
php artisan route:clear
php artisan view:clear
php artisan cache:clear

# Rebuild cache
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

### Checking Database

```bash
# Laravel CLI
php artisan db:show          # Show database info
php artisan db:table users   # Show table structure

# Or tinker
php artisan tinker
>>> Schema::hasTable('products')
>>> DB::table('products')->count()
>>> App\Models\Product::with('category')->first()
```

### Storage Operations

```bash
# Create storage symlink
php artisan storage:link

# Upload file in controller
use Illuminate\Support\Facades\Storage;

$path = $request->file('image')->store('products', 'public');
// File saved to: storage/app/public/products/filename.jpg
// Accessible at: /storage/products/filename.jpg
```

---

## 🔧 Troubleshooting

### Permission Issues

**Symptom**: "Permission denied" errors in logs

```bash
# Fix storage permissions
sudo chown -R www-data:www-data /var/www/samnghethaycu.com/storage
sudo chmod -R 775 /var/www/samnghethaycu.com/storage

# Fix bootstrap/cache permissions
sudo chown -R www-data:www-data /var/www/samnghethaycu.com/bootstrap/cache
sudo chmod -R 775 /var/www/samnghethaycu.com/bootstrap/cache

# Check if bootstrap/cache is symlink (CRITICAL ERROR)
ls -la /var/www/samnghethaycu.com/bootstrap/cache

# If it's a symlink, remove and recreate as directory
sudo rm -f /var/www/samnghethaycu.com/bootstrap/cache
mkdir -p /var/www/samnghethaycu.com/bootstrap/cache
sudo chown www-data:www-data /var/www/samnghethaycu.com/bootstrap/cache
sudo chmod 775 /var/www/samnghethaycu.com/bootstrap/cache
```

### 500 Internal Server Error

```bash
# Check Laravel logs
tail -50 /var/www/samnghethaycu.com/storage/logs/laravel.log

# Check Nginx error logs
sudo tail -50 /var/log/nginx/samnghethaycu-error.log

# Common fixes:
php artisan optimize:clear
sudo chown -R www-data:www-data storage bootstrap/cache
sudo systemctl restart php8.4-fpm
```

### Database Connection Issues

```bash
# Test MySQL connection
mysql -u samnghethaycu_user -p samnghethaycu

# Check credentials
cat ~/credentials/database.txt

# Verify .env file
grep DB_ /var/www/samnghethaycu.com/.env
```

### Composer Issues

```bash
# Clear composer cache
composer clear-cache

# Reinstall dependencies
rm -rf vendor/
composer install --no-dev --optimize-autoloader

# Check PHP version
php -v  # Should be 8.4
```

### Filament Admin Not Loading

```bash
# Clear Filament cache
php artisan filament:optimize-clear

# Rebuild assets
php artisan filament:assets

# Check User model implements FilamentUser
grep -A 5 "implements FilamentUser" app/Models/User.php
```

### SSL Certificate Issues

```bash
# Check certificate status
sudo certbot certificates

# Renew certificate (if expired)
sudo certbot renew

# Test renewal
sudo certbot renew --dry-run
```

### Logs Locations

```bash
# Laravel application logs
/var/www/samnghethaycu.com/storage/logs/laravel.log

# Nginx access logs
/var/log/nginx/samnghethaycu-access.log

# Nginx error logs
/var/log/nginx/samnghethaycu-error.log

# PHP-FPM logs
/var/log/php8.4-fpm.log

# System logs
/var/log/syslog
```

---

## 📚 Resources

### Official Documentation

- **Laravel**: https://laravel.com/docs/12.x
- **Filament**: https://filamentphp.com/docs/3.x
- **Livewire**: https://livewire.laravel.com/docs/3.x
- **Tailwind CSS**: https://tailwindcss.com/docs
- **Alpine.js**: https://alpinejs.dev/start-here

### Key Laravel Packages Used

- **Filament**: `filament/filament` - Admin panel framework
- **Sluggable**: `spatie/laravel-sluggable` - Automatic slug generation
- **Image**: `intervention/image` - Image manipulation
- **PDF**: `barryvdh/laravel-dompdf` - PDF generation

### Project Credentials File

```bash
# On VPS
cat ~/credentials/database.txt

# Contains:
# - MySQL root password
# - Database name
# - Database user
# - Database password
```

### Important URLs

- **Production**: https://samnghethaycu.com
- **Admin**: https://samnghethaycu.com/admin
- **Health Check**: https://samnghethaycu.com/health

---

## 🔄 Maintenance

### Regular Tasks

#### Daily
- [ ] Check error logs
- [ ] Monitor disk space
- [ ] Verify backup jobs

#### Weekly
- [ ] Review failed jobs queue
- [ ] Check SSL certificate expiry
- [ ] Update dependencies (composer update)

#### Monthly
- [ ] Security audit
- [ ] Performance review
- [ ] Database optimization

### Backup Strategy

```bash
# Database backup
mysqldump -u root -p samnghethaycu > backup-$(date +%Y%m%d).sql

# Full application backup
cd /var/www
sudo tar -czf ~/backup-full-$(date +%Y%m%d).tar.gz samnghethaycu.com/

# Backup .env and storage only
cd /var/www/samnghethaycu.com
sudo tar -czf ~/backup-data-$(date +%Y%m%d).tar.gz .env storage/
```

### Updating This Document

Update CLAUDE.md when:
- New features are added
- Technology stack changes
- New conventions are adopted
- Deployment process changes
- New team members join
- After encountering and solving new issues

---

## 📊 Version History

| Date       | Version | Changes                                       | Updated By |
|------------|---------|-----------------------------------------------|------------|
| 2025-11-16 | 2.0     | Complete rewrite for samnghethaycu.com project | Claude AI  |
| 2025-11-16 | 1.0     | Initial template for new repository           | Claude AI  |

---

## 🎯 Current Status Summary

**Infrastructure**: ✅ Complete (100%)
**Backend Setup**: ✅ Complete (100%)
**Database**: ✅ 23 tables created
**Models**: ✅ 15 Eloquent models
**Admin Panel**: ✅ Functional with 9 resources
**Frontend**: 🔄 Planned
**Payment Integration**: 🔄 Planned

**Next Steps**: Complete Phase 3 (Backend Customization)

---

**Note to AI Assistants**: This is a **real production Laravel e-commerce project** actively being developed. Always:

1. Test changes locally before deploying to VPS
2. Follow Laravel best practices and conventions
3. Be aware of VPS deployment implications
4. Never commit sensitive data (.env, credentials)
5. Always fix permissions after deployment
6. Check both Laravel and Nginx logs when debugging
7. Remember: `bootstrap/cache` must be a real directory, never a symlink!

**When in doubt, ask the user for clarification rather than making assumptions.**

---

**End of CLAUDE.md** 📝
