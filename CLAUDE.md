# CLAUDE.md - AI Assistant Guide

**Project**: samnghethaycu.com - E-Commerce Platform
**Repository**: websamnghe
**Last Updated**: 2025-11-22
**Status**: Fresh Laravel Installation with Comprehensive Workflow Guides

---

## 📋 Table of Contents

1. [Important: Repository Structure](#important-repository-structure)
2. [Project Overview](#project-overview)
3. [Technology Stack](#technology-stack)
4. [Workflow Documentation Files](#workflow-documentation-files)
5. [Project Structure](#project-structure)
6. [Development Phases](#development-phases)
7. [Database Schema](#database-schema)
8. [Development Workflow](#development-workflow)
9. [VPS Deployment](#vps-deployment)
10. [Coding Conventions](#coding-conventions)
11. [Git Workflow](#git-workflow)
12. [Testing Strategy](#testing-strategy)
13. [AI Assistant Guidelines](#ai-assistant-guidelines)
14. [Common Tasks](#common-tasks)
15. [Troubleshooting](#troubleshooting)

---

## ⚠️ IMPORTANT: Repository Structure

**VPS production environment has completed WORKFLOW 1-5. Local repository needs synchronization.**

### What This Repository Contains

✅ **Fresh Laravel 12 Installation (Local)**
- Default Laravel structure
- Only 1 model (User.php) - **needs FilamentUser interface**
- Only 3 default migrations
- Tailwind CSS v4 configured
- **⚠️ LOCAL CODE CHƯA ĐỒNG BỘ VỚI VPS**

✅ **9 Professional Workflow Guides** (2-3 hours total implementation time)
- **WORKFLOW-1**: VPS Infrastructure setup ✅ **COMPLETED on VPS**
- **WORKFLOW-2**: Laravel deployment to VPS ✅ **COMPLETED on VPS**
- **WORKFLOW-3**: Git workflow configuration ✅ **COMPLETED on VPS**
- **WORKFLOW-4**: Deployment automation script ✅ **COMPLETED on VPS**
- **WORKFLOW-5**: Filament Admin Panel installation ✅ **COMPLETED on VPS**
- **WORKFLOW-6**: Database schema with 15 migrations 📋 **READY TO START**
- **WORKFLOW-7**: Model business logic and relationships 📋 Pending
- **WORKFLOW-8**: Professional Filament resources 📋 Pending
- **WORKFLOW-9**: Vietnamese sample data seeders 📋 Pending

### What DOES Exist on VPS (Production)

✅ **VPS Infrastructure (69.62.82.145)** - WORKFLOW-1 completed
- LEMP stack (Nginx, MySQL 8, PHP 8.4)
- Redis 7 cache
- SSL certificate (Let's Encrypt)
- Firewall & security (UFW)

✅ **Laravel Deployment** - WORKFLOW-2 completed
- Laravel 12 deployed to /var/www/samnghethaycu.com
- Nginx virtual host configured
- Storage symlinks setup
- Production .env configured

✅ **Git Workflow** - WORKFLOW-3 completed
- GitHub repository connected
- VPS Git setup with SSH keys
- Git workflow: Local → GitHub → VPS

✅ **Deployment Automation** - WORKFLOW-4 completed
- deploy-sam automation script
- One-command deployment
- Deployment workflow tested

✅ **Filament Admin Panel** - WORKFLOW-5 completed
- Filament v4 installed
- Admin panel accessible at https://samnghethaycu.com/admin
- User model with FilamentUser interface (on VPS)
- Dashboard working

### What Does NOT Exist Yet

❌ **Local code out of sync** - needs to pull from VPS or re-install Filament locally
❌ **E-commerce models** (Product, Order, etc.) - ready to start WORKFLOW-6
❌ **Database migrations** beyond defaults - ready to start WORKFLOW-6
❌ **Sample data** - guide in WORKFLOW-9

**Next Steps**:
1. Synchronize local with VPS (pull changes or re-install Filament locally)
2. Start WORKFLOW-6 to build database schema
3. Continue WORKFLOW-7 through WORKFLOW-9

---

## 🎯 Project Overview

**samnghethaycu.com** is a full-stack e-commerce platform specializing in natural health products (Ginseng, Turmeric, Herbal medicines) with an integrated blog for health knowledge.

### Project Goals

- 📋 E-commerce website for natural health products (in development)
- 📋 Blog system for health knowledge sharing (in development)
- ✅ Standardized boilerplate with workflow documentation (complete)
- ✅ Professional admin panel with Filament **COMPLETED on VPS**
- ✅ Production-ready deployment on VPS **COMPLETED (WORKFLOW 1-5)**

### Active Deployment Information

**Production Environment (ACTIVE):**

- **Website**: https://samnghethaycu.com ✅ LIVE
- **Admin Panel**: https://samnghethaycu.com/admin ✅ WORKING
- **VPS IP**: 69.62.82.145 ✅ CONFIGURED

### Active Credentials

**Admin User** (created during WORKFLOW-5):
```
Email: admin@samnghethaycu.com
Password: Admin@123456
```

**SSH Access** (verified working):
```bash
ssh deploy@69.62.82.145
Password: Deploy@2025
```

✅ **Verified**: VPS access working, SSL certificate active, Filament admin panel accessible

---

## 🛠 Technology Stack

### Backend

- **Framework**: Laravel 12 ✅ Installed (Local + VPS)
- **Admin Panel**: Filament v4 ✅ **INSTALLED on VPS** (local needs sync)
- **Real-time**: Livewire 3 ✅ **INSTALLED** (comes with Filament)
- **Language**: PHP 8.4 ✅ Installed (Local + VPS)

### Frontend

- **Template Engine**: Blade ✅ (Laravel Default)
- **JavaScript**: Alpine.js ✅ **INSTALLED** (comes with Filament)
- **CSS Framework**: Tailwind CSS v4 ✅ Installed (Local + VPS)

### Database & Cache

- **Primary Database**: MySQL 8 ✅ **CONFIGURED on VPS**
- **Cache/Sessions**: Redis 7 ✅ **CONFIGURED on VPS**
- **ORM**: Eloquent ✅ (Laravel Default)

### Storage & CDN

- **Cloud Storage**: Cloudflare R2 (S3-compatible) 📋 Planned
- **Local Storage**: Laravel Storage with symlinks ✅ **CONFIGURED on VPS**

### Payment Gateways

- **Phase 1**: VNPay + COD (Cash on Delivery) 📋 Planned
- **Phase 2**: MoMo integration 📋 Planned

### Infrastructure

- **Server**: VPS (Ubuntu 24.04 LTS) ✅ **CONFIGURED**
- **Web Server**: Nginx ✅ **CONFIGURED with SSL**
- **Process Manager**: PHP-FPM 8.4 ✅ **CONFIGURED**
- **SSL**: Let's Encrypt (Certbot) ✅ **ACTIVE**
- **Firewall**: UFW ✅ **CONFIGURED**
- **Security**: Fail2ban ✅ **CONFIGURED**

### Development Tools

- **Package Manager**: Composer 2.x ✅ Installed
- **Build Tools**: Vite ✅ Installed
- **Node.js**: v20.x (required, verify version)
- **Version Control**: Git ✅ Initialized

---

## 📚 Workflow Documentation Files

This repository includes 9 comprehensive workflow guides that provide step-by-step instructions for building the complete e-commerce platform:

| Workflow | File | Description | Est. Time |
|----------|------|-------------|-----------|
| 1 | `WORKFLOW-1-VPS-INFRASTRUCTURE.md` | VPS setup with LEMP stack, Redis, SSL | 20-25 min |
| 2 | `WORKFLOW-2-LARAVEL-INSTALLATION.md` | Deploy Laravel to VPS with Nginx | 15-20 min |
| 3 | `WORKFLOW-3-GIT-WORKFLOW-SETUP.md` | Configure Git workflow (Local → GitHub → VPS) | 15-20 min |
| 4 | `WORKFLOW-4-DEPLOYMENT-AUTOMATION.md` | Automated deployment script | 10-15 min |
| 5 | `WORKFLOW-5-FILAMENT-ADMIN-PANEL.md` | Install Filament v4 admin panel | 10-15 min |
| 6 | `WORKFLOW-6-DATABASE-SCHEMA.md` | Create 15 migrations and models | 25-35 min |
| 7 | `WORKFLOW-7-MODEL-BUSINESS-LOGIC.md` | Add relationships and business logic | 30-40 min |
| 8 | `WORKFLOW-8-FILAMENT-PROFESSIONAL.md` | Customize Filament resources | 35-45 min |
| 9 | `WORKFLOW-9-SEEDERS-SAMPLE-DATA.md` | Vietnamese sample data seeders | 20-30 min |

**Total Estimated Time**: 3-3.5 hours (for experienced developers)

Each workflow is:
- ✅ Self-contained and can be referenced independently
- ✅ Includes rollback instructions where applicable
- ✅ Written in Vietnamese with code examples
- ✅ Production-tested and verified

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

### Key Directories (Current State)

- **`app/Models/`**: Currently 1 model (User.php) - 15 models planned
- **`app/Filament/Resources/`**: Does not exist yet - 9 resources planned
- **`database/migrations/`**: 3 default Laravel migrations - 23 tables planned
- **`storage/app/public/`**: Exists - User uploads will go here
- **`bootstrap/cache/`**: Framework cache (must be real directory, not symlink)
- **`WORKFLOW-*.md`**: 9 comprehensive workflow documentation files ✅

---

## 🚀 Development Phases

### ✅ Current Status: Infrastructure Complete, Backend Development Ready

**COMPLETED on VPS**: WORKFLOW 1-5 (Infrastructure, Deployment, Filament Admin)
**NEXT**: WORKFLOW-6 (Database Schema with 15 migrations)

**Important**: VPS production environment is ready. Local development environment needs synchronization with VPS before starting WORKFLOW-6.

#### **WORKFLOW-1: VPS Infrastructure** ✅ **COMPLETED** (20-25 min)
- [x] LEMP stack (Nginx, MySQL 8, PHP 8.4)
- [x] Redis 7 cache
- [x] Composer, Node.js 20
- [x] SSL certificate (Let's Encrypt)
- [x] Firewall & security (UFW)
- [x] Database credentials saved

**Status:** ✅ Complete - VPS infrastructure fully configured at 69.62.82.145

#### **WORKFLOW-2: Laravel Installation** ✅ **COMPLETED** (15-20 min)
- [x] Laravel 12 locally installed (fresh)
- [x] VPS deployment
- [x] Nginx virtual host configuration
- [x] Storage symlink setup
- [x] .env production configuration
- [x] Health check endpoint
- [x] Permissions with ACL

**Status:** ✅ Complete - Laravel deployed to /var/www/samnghethaycu.com

#### **WORKFLOW-3: Git Workflow Setup** ✅ **COMPLETED** (15-20 min)
- [x] Local Git initialized
- [x] GitHub repository created
- [x] VPS Git setup with SSH keys
- [x] Repository cloned to VPS
- [x] Git workflow: Local → GitHub → VPS

**Status:** ✅ Complete - Git workflow automated

#### **WORKFLOW-4: Deployment Automation** ✅ **COMPLETED** (10-15 min)
- [x] deploy-sam automation script (8-step deployment)
- [x] One-command deployment workflow
- [x] Sudo configuration for deploy user
- [x] Deployment alias setup
- [x] End-to-end workflow tested

**Status:** ✅ Complete - `deploy-sam` command ready to use

#### **WORKFLOW-5: Filament Admin Panel** ✅ **COMPLETED** (10-15 min)
- [x] Filament v4 installation
- [x] Admin panel at /admin
- [x] Admin user creation (admin@samnghethaycu.com)
- [x] User model with FilamentUser interface
- [x] Dashboard accessible

**Status:** ✅ Complete - Admin panel accessible at https://samnghethaycu.com/admin
**Note:** ⚠️ Local code needs sync - User.php missing FilamentUser interface locally

#### **WORKFLOW-6: Database Schema** 📋 **READY TO START** (25-35 min)
- [ ] 15 migrations (23 tables total) - with CORRECT dependency order
- [ ] 15 Eloquent models with fillable & casts
- [ ] 9 Filament resources auto-generated
- [ ] CRUD operations working
- [ ] Deployed to production

**Status:** 📋 Ready to begin - Prerequisites met (WORKFLOW 1-5 complete)
**See:** `WORKFLOW-6-DATABASE-SCHEMA.md` - Database design and migration guide
**Note:** Workflow already includes fix for migration order to prevent foreign key errors

#### **WORKFLOW-7: Model Business Logic** 📋 Guide Available (30-40 min)
- [ ] 50+ relationships (belongsTo, hasMany, etc.)
- [ ] 30+ scopes for filtering
- [ ] 40+ accessors & mutators
- [ ] 25+ business logic methods
- [ ] Helper methods (URLs, labels, calculations)
- [ ] All models production-ready

**See:** `WORKFLOW-7-MODEL-BUSINESS-LOGIC.md` - Model relationships and business logic guide

#### **WORKFLOW-8: Filament Professional** 📋 Guide Available (35-45 min)
- [ ] ProductResource: Tabs, filters, bulk actions
- [ ] OrderResource: Custom actions, status management
- [ ] Dashboard widgets (stats + latest orders)
- [ ] Review approve/reject workflow
- [ ] Coupon validity tracking
- [ ] Professional UI with badges and colors

**See:** `WORKFLOW-8-FILAMENT-PROFESSIONAL.md` - Advanced Filament customization guide

#### **WORKFLOW-9: Seeders & Sample Data** 📋 Guide Available (20-30 min)
- [ ] 9 categories (Vietnamese health products)
- [ ] 5 brands (Vietnamese brands)
- [ ] 5 realistic products
- [ ] 3 blog posts
- [ ] 9 product reviews
- [ ] 3 active coupons
- [ ] All data in Vietnamese

**See:** `WORKFLOW-9-SEEDERS-SAMPLE-DATA.md` - Sample data creation guide

---

### 📊 Current Project Status

```
✅ Laravel 12 Fresh Installation (Local + VPS)
✅ PHP 8.4 Configured (Local + VPS)
✅ Tailwind CSS v4 Setup (Local + VPS)
✅ Composer Dependencies Installed (Local + VPS)
✅ Git Repository Initialized (Local + GitHub + VPS)
✅ 9 Comprehensive Workflow Guides Created
✅ VPS Infrastructure COMPLETED (WORKFLOW-1)
✅ Laravel Deployment COMPLETED (WORKFLOW-2)
✅ Git Workflow COMPLETED (WORKFLOW-3)
✅ Deployment Automation COMPLETED (WORKFLOW-4)
✅ Filament v4 Admin Panel COMPLETED on VPS (WORKFLOW-5)
⚠️ Local Code Out of Sync - needs Filament installation locally
📋 Database Schema READY TO START (WORKFLOW-6 - 23 tables planned)
📋 Eloquent Models (15 models planned - WORKFLOW-6)
📋 Business Logic (WORKFLOW-7)
📋 Sample Data Seeders (WORKFLOW-9)
```

**Current State**: VPS production environment fully operational with WORKFLOW 1-5 complete
**Completed Time**: ~1.5 hours (WORKFLOW 1-5 completed on VPS)
**Remaining Time**: ~2 hours (WORKFLOW 6-9 to complete full backend)

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

## 🗄 Database Schema (Planned)

**Current State**: Only 3 default Laravel migrations exist (users, cache, jobs)

**Planned**: 23 tables total as documented in WORKFLOW-6

### 23 Tables Overview (Planned Design)

#### Core E-Commerce Tables (8) - 📋 To Be Created

1. **users** - User accounts (customers, admin) ✅ Default migration exists
2. **products** - Product catalog 📋 Planned
3. **product_variants** - Product variations (size, color, etc.) 📋 Planned
4. **product_images** - Product image gallery 📋 Planned
5. **categories** - Product categories (nested) 📋 Planned
6. **brands** - Product brands 📋 Planned
7. **orders** - Customer orders 📋 Planned
8. **order_items** - Order line items 📋 Planned

#### Supporting Tables (7) - 📋 To Be Created

9. **addresses** - Customer shipping addresses 📋 Planned
10. **reviews** - Product reviews & ratings 📋 Planned
11. **coupons** - Discount coupons 📋 Planned
12. **coupon_usages** - Coupon usage tracking 📋 Planned
13. **order_status_histories** - Order status audit trail 📋 Planned
14. **posts** - Blog posts 📋 Planned
15. **post_categories** - Blog categories 📋 Planned

#### Laravel System Tables (8) - ✅ Exist

16. **password_reset_tokens** ✅
17. **sessions** ✅
18. **cache** ✅
19. **cache_locks** ✅
20. **jobs** ✅
21. **job_batches** ✅
22. **failed_jobs** ✅
23. **migrations** ✅

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

### Current Production Workflow (WORKFLOW 1-5 Complete)

Our deployment is fully automated with **8-step automated deployment** via `deploy-sam` command.

#### **Local Development → Production Flow:**

```bash
# 1. LOCAL (Windows): Make changes
cd C:\Projects\samnghethaycu
# Edit code, create migrations, update models, etc.

# 2. LOCAL: Test changes
php artisan migrate      # Test migrations
php artisan test         # Run tests (optional)

# 3. LOCAL: Commit to GitHub
git add .
git commit -m "feat(products): add product gallery feature"
git push origin main     # Push to GitHub

# 4. VPS: Deploy (SSH as deploy user)
ssh deploy@69.62.82.145
deploy-sam              # One command deployment!
# ✅ 8 automated steps:
#    1. Git pull from GitHub
#    2. Check .env exists
#    3. Fix bootstrap/cache
#    4. Composer install (production)
#    5. Run migrations
#    6. Clear & rebuild cache
#    7. Fix permissions
#    8. Reload PHP-FPM
```

#### **8-Step Deployment Process (`deploy-sam`):**

Created in WORKFLOW-4, the `deploy-sam` command automates:

1. ✅ **Git Pull** - Latest code from GitHub
2. ✅ **Environment Check** - Verify .env exists (not in Git)
3. ✅ **Bootstrap Cache Fix** - Ensure real directory, not symlink
4. ✅ **Dependencies** - `composer install --no-dev --optimize-autoloader`
5. ✅ **Database** - `php artisan migrate --force`
6. ✅ **Cache** - Clear all, rebuild config/route/view cache
7. ✅ **Permissions** - `www-data:www-data` for storage & cache
8. ✅ **Reload** - PHP-FPM reload for zero-downtime

**Time:** ~30-60 seconds per deployment

---

### Local Development Setup (For New Developers)

```bash
# Clone repository
git clone https://github.com/phuochoavn/websamnghe.git
cd websamnghe

# Install PHP dependencies
composer install

# Install Node dependencies
npm install

# Environment setup
cp .env.example .env
php artisan key:generate

# Configure local database (SQLite or MySQL)
# SQLite (easier for local):
DB_CONNECTION=sqlite
DB_DATABASE=/absolute/path/to/database/database.sqlite

# OR MySQL:
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=samnghethaycu_local
DB_USERNAME=root
DB_PASSWORD=

# Install Filament (to sync with VPS)
composer require filament/filament:"^4.0" -W
php artisan filament:install --panels

# Run migrations
php artisan migrate

# Create storage symlink
php artisan storage:link

# Create admin user
php artisan make:filament-user

# Start development servers
php artisan serve
npm run dev
```

**Access:**
- Website: http://localhost:8000
- Admin: http://localhost:8000/admin

---

### Daily Development Workflow

#### **For Backend Features (Models, Migrations, Filament):**

```bash
# 1. Pull latest from main
git pull origin main

# 2. Create feature branch (optional, for team work)
git checkout -b feature/add-product-filters

# 3. Make changes locally
# - Create migrations
# - Update models
# - Modify Filament resources
# - Test locally

# 4. Commit changes
git add .
git commit -m "feat(products): add category and price filters"

# 5. Push to GitHub
git push origin main  # or your branch

# 6. Deploy to VPS
ssh deploy@69.62.82.145
deploy-sam

# 7. Verify on production
curl https://samnghethaycu.com/health
# Visit https://samnghethaycu.com/admin
```

#### **For Frontend Features (Blade, CSS, JS):**

```bash
# 1. Make changes locally
# Edit resources/views/, resources/css/, resources/js/

# 2. Build assets
npm run build  # Production build

# 3. Commit & push
git add .
git commit -m "feat(ui): add product carousel on homepage"
git push origin main

# 4. Deploy
ssh deploy@69.62.82.145
deploy-sam
```

---

### Quick Commands Reference

#### **Local Development:**
```bash
php artisan serve               # Start dev server (port 8000)
php artisan migrate             # Run migrations
php artisan db:seed             # Seed database
php artisan tinker              # Laravel REPL
php artisan route:list          # List all routes
php artisan make:model Product  # Create model
npm run dev                     # Vite dev server (hot reload)
npm run build                   # Build for production
```

#### **VPS Production:**
```bash
ssh deploy@69.62.82.145        # SSH to VPS
deploy-sam                      # Deploy latest code
tail -f storage/logs/laravel.log  # Watch logs
php artisan optimize:clear      # Clear all cache
sudo systemctl status nginx     # Check Nginx
sudo systemctl status mysql     # Check MySQL
```

---

## 🚢 VPS Deployment

### Server Information

```
VPS IP: 69.62.82.145
OS: Ubuntu 24.04 LTS
Domain: samnghethaycu.com (with SSL)
Web Root: /var/www/samnghethaycu.com
```

### Credentials Quick Reference

**⚠️ IMPORTANT:** These credentials are for development/staging. Change in production!

#### **VPS Access:**
```bash
# Deploy User (daily use)
ssh deploy@69.62.82.145
Password: Deploy@2025

# Root User (VPS setup only)
ssh root@69.62.82.145
Password: [From VPS provider]
```

#### **MySQL Database:**
```bash
# Laravel Database User
Host: localhost (127.0.0.1)
Port: 3306
Database: samnghethaycu
Username: samnghethaycu_user
Password: SamNghe@DB2025

# Root User (admin tasks)
Username: root
Password: RootMySQL@2025

# Connection command:
mysql -u samnghethaycu_user -p samnghethaycu
```

#### **Laravel Admin Panel:**
```
URL: https://samnghethaycu.com/admin
Email: admin@samnghethaycu.com
Password: Admin@123456
```

#### **GitHub Repository:**
```
Repository: https://github.com/phuochoavn/websamnghe
SSH: git@github.com:phuochoavn/websamnghe.git
Branch: main
```

#### **Credentials File on VPS:**
```bash
# View saved credentials
cat ~/credentials/database.txt
```

#### **Key File Locations:**
```bash
# VPS
/var/www/samnghethaycu.com          # Laravel root
/var/www/samnghethaycu.com/.env     # Production config
~/scripts/deploy-sam.sh             # Deployment script
~/credentials/database.txt          # Database credentials
/etc/nginx/sites-available/samnghethaycu.com  # Nginx config
/var/log/nginx/samnghethaycu-*.log  # Nginx logs
storage/logs/laravel.log            # Laravel logs

# Local (Windows)
C:\Projects\samnghethaycu           # Laravel root
C:\Projects\samnghethaycu\.env      # Local config
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
- **Filament version**: v4.0+ (admin panel framework)
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
- **Filament**: https://filamentphp.com/docs/4.x
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
| 2025-11-22 | 4.0     | **MAJOR UPDATE**: Reflect WORKFLOW 1-5 completion on VPS - Infrastructure, Deployment, Git, Automation, Filament fully operational | Claude AI  |
| 2025-11-22 | 3.0     | **MAJOR UPDATE**: Corrected to reflect actual repository state - fresh Laravel installation with workflow guides | Claude AI  |
| 2025-11-16 | 2.0     | Complete rewrite for samnghethaycu.com project (aspirational) | Claude AI  |
| 2025-11-16 | 1.0     | Initial template for new repository           | Claude AI  |

---

## 🎯 Current Status Summary

**Project Stage**: 🚀 Infrastructure Complete, Backend Development Ready
**Laravel**: ✅ Version 12 Installed (Local + VPS)
**PHP**: ✅ Version 8.4 Configured (Local + VPS)
**Tailwind CSS**: ✅ Version 4 Setup (Local + VPS)
**Infrastructure**: ✅ **COMPLETED** (VPS fully configured with SSL)
**Database**: ✅ MySQL 8 + Redis 7 configured on VPS, ready for schema (WORKFLOW-6)
**Models**: 📋 1 model exists (User) - 14 more planned in WORKFLOW-6
**Admin Panel**: ✅ **Filament INSTALLED on VPS** (local needs sync)
**Frontend**: 📋 Planned (customer-facing pages)
**Payment Integration**: 📋 Planned (VNPay + COD)

**Completed**: WORKFLOW 1-5 (Infrastructure, Deployment, Git, Automation, Filament)
**Next Steps**:
1. Sync local code with VPS (install Filament locally or pull from VPS)
2. Start WORKFLOW-6 to build database schema (15 migrations, 15 models)
3. Continue WORKFLOW-7 (relationships & business logic)
4. Continue WORKFLOW-8 (customize Filament resources)
5. Continue WORKFLOW-9 (sample data seeders)

### 🚀 How to Get Started (Current State)

**✅ COMPLETED STEPS:**
1. ~~**Set Up VPS**~~ - WORKFLOW-1 DONE (LEMP stack, Redis, SSL)
2. ~~**Deploy Laravel**~~ - WORKFLOW-2 DONE (Laravel on VPS with Nginx)
3. ~~**Git Workflow**~~ - WORKFLOW-3 DONE (Local → GitHub → VPS)
4. ~~**Deployment Automation**~~ - WORKFLOW-4 DONE (deploy-sam command)
5. ~~**Install Filament**~~ - WORKFLOW-5 DONE (Admin panel at /admin)

**📋 NEXT STEPS:**
1. **Sync Local Code** (RECOMMENDED): Install Filament locally or pull from VPS to sync
2. **Start WORKFLOW-6**: Build database schema (15 migrations, 15 models)
3. **Continue WORKFLOW-7**: Add relationships & business logic to models
4. **Continue WORKFLOW-8**: Customize Filament resources (filters, actions, widgets)
5. **Continue WORKFLOW-9**: Create Vietnamese sample data seeders
6. **Develop Frontend**: Create customer-facing pages using Blade templates
7. **Integrate Payments**: Implement VNPay and COD payment gateways
8. **Test & Optimize**: Run tests, optimize performance, and security audit

---

**Note to AI Assistants**: This repository is **ACTIVELY DEPLOYED** with VPS infrastructure and Filament admin panel fully operational.

**IMPORTANT - Current State:**
- ✅ Laravel 12 installed (Local + VPS)
- ✅ 9 detailed workflow guides (WORKFLOW-1 through WORKFLOW-9)
- ✅ **WORKFLOW 1-5 COMPLETED on VPS** (Infrastructure, Deployment, Git, Automation, Filament)
- ✅ VPS production environment LIVE at https://samnghethaycu.com
- ✅ Filament admin panel WORKING at https://samnghethaycu.com/admin
- ⚠️ **Local code OUT OF SYNC** - User.php missing FilamentUser interface locally
- 📋 WORKFLOW-6 READY TO START (Database schema with 15 migrations)
- ❌ NO e-commerce models yet (Product, Order, etc. - planned in WORKFLOW-6)
- ❌ NO sample data yet (planned in WORKFLOW-9)

**When Working on This Project:**
1. **WORKFLOW 1-5 are COMPLETE on VPS** - focus on WORKFLOW 6-9 now
2. Recommend syncing local code before starting WORKFLOW-6
3. Follow migration order carefully in WORKFLOW-6 (dependency levels documented)
4. Always test migrations locally before deploying to VPS
5. Use `deploy-sam` command for VPS deployment (from WORKFLOW-4)
6. Follow Laravel best practices and conventions
7. Never commit sensitive data (.env, credentials)
8. Remember: `bootstrap/cache` must be a real directory, never a symlink!

**When in doubt, ask the user for clarification rather than making assumptions.**

---

**End of CLAUDE.md** 📝
