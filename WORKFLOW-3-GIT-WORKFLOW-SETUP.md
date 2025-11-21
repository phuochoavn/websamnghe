# 🔄 WORKFLOW 3: THIẾT LẬP GIT WORKFLOW

> **Dự án:** samnghethaycu.com - E-Commerce Platform
> **Version:** 4.0 Professional Vietnamese (Standardized Edition)
> **Thời gian thực tế:** 10-15 phút
> **Mục tiêu:** Setup SSH authentication và Deploy User cho Git automation
> **Cập nhật:** 2025-11-21 - Standardized format + Deploy user setup

---

## 📋 ĐIỀU KIỆN TIÊN QUYẾT

### ✅ Phải hoàn thành trước

```
✅ WORKFLOW-1: VPS Infrastructure (LEMP Stack + SSL)
✅ WORKFLOW-2: Laravel Installation (Laravel 12 đã deploy)
✅ Git repository: https://github.com/phuochoavn/websamnghe.git
✅ Code đã push lên GitHub từ Windows local
✅ VPS đã clone code từ GitHub (dùng root + HTTPS)
```

### ✅ Kiểm Tra Nhanh

**📍 Trên Windows:**

```powershell
# Kiểm tra Git local
cd C:\Projects\samnghethaycu
git remote -v
# ✅ Phải thấy: origin  https://github.com/phuochoavn/websamnghe.git

git log --oneline -3
# ✅ Phải thấy commits Laravel
```

**📍 Trên VPS:**

```bash
# Kiểm tra Laravel đã deploy
curl https://samnghethaycu.com/health
# ✅ Phải trả về JSON với database + redis connected

# Kiểm tra git repo
cd /var/www/samnghethaycu.com
git remote -v
# ✅ Phải thấy: origin  https://github.com/phuochoavn/websamnghe.git
```

**Tất cả OK?** → Tiếp tục!

---

## 🎯 NHỮNG GÌ CHÚNG TA SẼ XÂY DỰNG

```
HIỆN TẠI (Sau WORKFLOW-2):
  Windows → Git push (HTTPS + password/token)
  VPS     → Git clone (root user + HTTPS)

MỤC TIÊU WORKFLOW-3:
  Windows → Git push (SSH - không cần password)
  VPS     → Git pull (deploy user + SSH automation)

Chuẩn bị cho WORKFLOW-4: Deployment automation script!
```

**Triết lý:** Setup SSH authentication và deploy user để automation an toàn!

---

## PHẦN 1: SETUP DEPLOY USER TRÊN VPS

**Thời gian:** 5 phút

⚠️ **LƯU Ý:** Nếu đã tạo deploy user trong WORKFLOW-1, bỏ qua section 1.1-1.2

### 1.1. Kiểm Tra Deploy User

**📍 Trên VPS:**

```bash
# SSH vào VPS với root
ssh root@69.62.82.145

# Kiểm tra deploy user có chưa
id deploy

# ✅ Nếu thấy: uid=1000(deploy) gid=1000(deploy)... → Đã có, skip đến 1.3
# ❌ Nếu thấy: id: 'deploy': no such user → Chưa có, làm tiếp 1.2
```

---

### 1.2. Tạo Deploy User (Nếu Chưa Có)

**📍 Trên VPS (root):**

```bash
# Tạo user deploy
sudo useradd -m -s /bin/bash deploy

# Set password
sudo passwd deploy
# Nhập password: Deploy@2025
# Nhập lại: Deploy@2025

# Add vào sudo group
sudo usermod -aG sudo deploy

# Add vào www-data group (để deploy Laravel)
sudo usermod -aG www-data deploy

# Verify
id deploy
# ✅ Phải thấy: groups=1000(deploy),27(sudo),33(www-data)
```

**Giải thích:**
- `useradd -m`: Tạo user với home directory `/home/deploy`
- `-s /bin/bash`: Set default shell là bash
- `sudo group`: Cho phép deploy user chạy sudo commands
- `www-data group`: Cho phép deploy user ghi vào Laravel folders

---

### 1.3. Grant Deploy User Permissions

**📍 Trên VPS (root):**

```bash
# Chuyển ownership của Laravel folder cho deploy user
sudo chown -R deploy:www-data /var/www/samnghethaycu.com

# Set permissions cho storage và cache
sudo chown -R www-data:www-data /var/www/samnghethaycu.com/storage
sudo chown -R www-data:www-data /var/www/samnghethaycu.com/bootstrap/cache
sudo chmod -R 775 /var/www/samnghethaycu.com/storage
sudo chmod -R 775 /var/www/samnghethaycu.com/bootstrap/cache

# Verify
ls -la /var/www/samnghethaycu.com
# ✅ Owner phải là: deploy www-data
```

**Giải thích:**
- `deploy:www-data`: Deploy user owns files, www-data (Nginx) có quyền đọc
- Folders `storage/` và `bootstrap/cache/` owned by www-data để ghi logs, cache
- `775`: Owner & group có full quyền, others chỉ đọc

✅ **Checkpoint 1:** Deploy user created & permissions set

---

## PHẦN 2: SETUP SSH KEY CHO GITHUB

**Thời gian:** 5 phút

### 2.1. Generate SSH Key (Deploy User)

**📍 Trên VPS:**

```bash
# Exit khỏi root, SSH lại với deploy user
exit

# SSH với deploy user
ssh deploy@69.62.82.145
# Password: Deploy@2025

# Generate SSH key
ssh-keygen -t ed25519 -C "deploy@samnghethaycu.com"

# Press Enter 3 lần (không dùng passphrase cho automation)
# Output:
# Your identification has been saved in /home/deploy/.ssh/id_ed25519
# Your public key has been saved in /home/deploy/.ssh/id_ed25519.pub
```

**Giải thích:**
- `-t ed25519`: Sử dụng ED25519 algorithm (nhanh, an toàn)
- `-C "deploy@samnghethaycu.com"`: Comment để nhận diện key
- No passphrase: Để automation script có thể git pull không cần nhập password

---

### 2.2. Hiển Thị Public Key

**📍 Trên VPS (deploy user):**

```bash
# Hiển thị public key
cat ~/.ssh/id_ed25519.pub

# ✅ Output sẽ bắt đầu với: ssh-ed25519 AAAA...
```

**Copy toàn bộ output** (từ `ssh-ed25519` đến hết dòng)

---

### 2.3. Add SSH Key to GitHub

**📍 Trên GitHub.com:**

1. Click **avatar** (góc phải) → **Settings**
2. Sidebar bên trái → **SSH and GPG keys**
3. Click **"New SSH key"** (nút xanh lá)
4. **Title:** `VPS Deploy User - samnghethaycu.com`
5. **Key type:** Authentication Key
6. **Key:** Paste public key vừa copy
7. Click **"Add SSH key"**
8. Nhập GitHub password để confirm

✅ **Checkpoint 2:** SSH key added to GitHub

---

### 2.4. Test SSH Connection

**📍 Trên VPS (deploy user):**

```bash
# Test GitHub SSH
ssh -T git@github.com

# Lần đầu sẽ hỏi:
# The authenticity of host 'github.com (140.82.113.4)'...
# Are you sure you want to continue connecting (yes/no/[fingerprint])?
# → Gõ: yes

# ✅ Expected output:
# Hi phuochoavn! You've successfully authenticated, but GitHub does not provide shell access.
```

**❌ Nếu thấy "Permission denied (publickey)":**
- Kiểm tra đã copy đúng public key chưa
- Kiểm tra đã add key vào đúng GitHub account chưa
- Thử generate lại SSH key

✅ **Checkpoint 2.4:** GitHub SSH authentication working!

---

## PHẦN 3: CẤU HÌNH GIT TRÊN VPS

**Thời gian:** 3 phút

### 3.1. Configure Git Identity

**📍 Trên VPS (deploy user):**

```bash
# Set git identity cho deploy user
git config --global user.name "Deploy User"
git config --global user.email "deploy@samnghethaycu.com"

# Verify
git config --global --list
# ✅ Phải thấy:
# user.name=Deploy User
# user.email=deploy@samnghethaycu.com
```

---

### 3.2. Reconfigure Remote to Use SSH

**📍 Trên VPS (deploy user):**

```bash
# Di chuyển vào Laravel folder
cd /var/www/samnghethaycu.com

# Xem remote hiện tại (đang dùng HTTPS)
git remote -v
# origin  https://github.com/phuochoavn/websamnghe.git (fetch)
# origin  https://github.com/phuochoavn/websamnghe.git (push)

# Đổi sang SSH URL
git remote set-url origin git@github.com:phuochoavn/websamnghe.git

# Verify
git remote -v
# ✅ Phải thấy:
# origin  git@github.com:phuochoavn/websamnghe.git (fetch)
# origin  git@github.com:phuochoavn/websamnghe.git (push)
```

**Giải thích:**
- HTTPS URL: `https://github.com/phuochoavn/websamnghe.git` → Cần password/token
- SSH URL: `git@github.com:phuochoavn/websamnghe.git` → Dùng SSH key (tự động)

---

### 3.3. Test Git Pull

**📍 Trên VPS (deploy user):**

```bash
cd /var/www/samnghethaycu.com

# Test pull
git pull origin main

# ✅ Expected:
# Already up to date.
# (Hoặc pull về code mới nếu có changes trên GitHub)

# ❌ KHÔNG được hỏi username/password! Nếu hỏi → SSH chưa đúng
```

✅ **Checkpoint 3:** Git pull với SSH thành công (không cần password)!

---

## PHẦN 4: TEST FULL WORKFLOW

**Thời gian:** 2 phút

### 4.1. Test Deployment Workflow

**📍 Trên Windows:**

```powershell
cd C:\Projects\samnghethaycu

# Tạo test file
echo "# Test Git Workflow" > TEST-WORKFLOW-3.md

# Add, commit, push
git add TEST-WORKFLOW-3.md
git commit -m "test: verify Git workflow after WORKFLOW-3"
git push origin main
```

**📍 Trên VPS (deploy user):**

```bash
cd /var/www/samnghethaycu.com

# Pull changes (KHÔNG cần password!)
git pull origin main

# Verify file đã về
ls -la TEST-WORKFLOW-3.md
# ✅ File phải có!

cat TEST-WORKFLOW-3.md
# ✅ Phải thấy: # Test Git Workflow
```

**Xóa test file:**

```bash
# Xóa trên VPS
rm TEST-WORKFLOW-3.md
```

**📍 Trên Windows:**

```powershell
# Xóa trên Windows
git rm TEST-WORKFLOW-3.md
git commit -m "chore: remove test file"
git push origin main
```

✅ **Checkpoint 4:** Full workflow tested successfully!

---

## ✅ HOÀN THÀNH WORKFLOW 3!

### Git Workflow Sẵn Sàng:

```
✅ Deploy user created: deploy@samnghethaycu.com
✅ SSH key generated và added to GitHub
✅ GitHub SSH authentication working (không cần password)
✅ Git identity configured (deploy user)
✅ Deploy user có quyền trên /var/www/samnghethaycu.com
✅ Git remote đã đổi sang SSH URL
✅ Git pull hoạt động tự động (không cần password)
✅ Full workflow tested: Windows → GitHub → VPS
```

### Git Workflow Diagram:

```
┌─────────────────────────────────────────────────────────────┐
│                     GIT WORKFLOW                            │
├─────────────────────────────────────────────────────────────┤
│                                                             │
│  Windows Local (Dev)                                        │
│  ───────────────────                                        │
│  C:\Projects\samnghethaycu                                  │
│  User: Hoa Nguyen                                           │
│                                                             │
│  1. Make changes                                            │
│  2. git add . && git commit -m "..."                        │
│  3. git push origin main (HTTPS + token)                    │
│         │                                                   │
│         ▼                                                   │
│  ┌──────────────────────────────────┐                       │
│  │  GitHub Repository (Remote)      │                       │
│  │  ────────────────────────────    │                       │
│  │  phuochoavn/websamnghe           │                       │
│  │  (Single source of truth)        │                       │
│  └──────────────────────────────────┘                       │
│         │                                                   │
│         ▼                                                   │
│  VPS Production Server                                      │
│  ──────────────────────                                     │
│  /var/www/samnghethaycu.com                                 │
│  User: deploy                                               │
│                                                             │
│  4. git pull origin main (SSH - auto auth!)                 │
│  5. php artisan migrate --force                             │
│  6. php artisan optimize                                    │
│  7. Website updated! ✅                                      │
│                                                             │
└─────────────────────────────────────────────────────────────┘
```

### Kiểm Tra Tổng Thể:

**📍 Trên VPS (deploy user):**

```bash
# Kiểm tra Git config
git config --global --list
# ✅ user.name=Deploy User
# ✅ user.email=deploy@samnghethaycu.com

# Kiểm tra remote
cd /var/www/samnghethaycu.com
git remote -v
# ✅ origin  git@github.com:phuochoavn/websamnghe.git

# Kiểm tra SSH
ssh -T git@github.com
# ✅ Hi phuochoavn! You've successfully authenticated...

# Kiểm tra permissions
ls -la /var/www/samnghethaycu.com
# ✅ deploy www-data

# Test pull
git pull origin main
# ✅ Already up to date. (KHÔNG hỏi password!)
```

### Bước Tiếp Theo:

```
→ WORKFLOW-4: DEPLOYMENT AUTOMATION
  Tạo script deploy-sam để tự động:
  - git pull origin main
  - composer install
  - php artisan migrate --force
  - php artisan optimize

  Thay vì 10+ lệnh → Chỉ còn: deploy-sam ✨
```

---

## 🔄 ROLLBACK: XÓA SẠCH VỀ WORKFLOW-2

⚠️ **KHI NÀO CẦN ROLLBACK:**
- WORKFLOW-3 gặp lỗi không fix được
- Muốn làm lại SSH key setup
- Test lại quy trình deployment
- Deploy user gặp vấn đề permissions

**MỤC TIÊU:** Xóa deploy user và SSH keys, trở về trạng thái sau WORKFLOW-2

---

### BƯỚC 1: Xóa SSH Key Khỏi GitHub

**📍 Trên GitHub.com:**

1. **Settings** → **SSH and GPG keys**
2. Tìm key: `VPS Deploy User - samnghethaycu.com`
3. Click **"Delete"**
4. Confirm deletion

---

### BƯỚC 2: Reconfigure Git Remote to HTTPS

**📍 Trên VPS (root):**

```bash
# SSH vào VPS với root
ssh root@69.62.82.145

# Đổi Git remote về HTTPS
cd /var/www/samnghethaycu.com
git remote set-url origin https://github.com/phuochoavn/websamnghe.git

# Verify
git remote -v
# ✅ Phải thấy: origin  https://github.com/phuochoavn/websamnghe.git
```

---

### BƯỚC 3: Reset Permissions về Root

**📍 Trên VPS (root):**

```bash
# Chuyển ownership về root
sudo chown -R root:www-data /var/www/samnghethaycu.com

# Fix storage và cache permissions
sudo chown -R www-data:www-data /var/www/samnghethaycu.com/storage
sudo chown -R www-data:www-data /var/www/samnghethaycu.com/bootstrap/cache

# Verify
ls -la /var/www/samnghethaycu.com
# ✅ Owner phải là: root www-data
```

---

### BƯỚC 4: Xóa Deploy User (Optional)

⚠️ **CHỈ XÓA NẾU:** Bạn không còn cần deploy user nữa

**📍 Trên VPS (root):**

```bash
# Xóa deploy user
sudo userdel -r deploy

# Verify
id deploy
# ✅ Phải thấy: id: 'deploy': no such user
```

**Giải thích:**
- `userdel -r`: Xóa user và home directory `/home/deploy`
- SSH keys của deploy user cũng bị xóa theo

---

### BƯỚC 5: Verify Rollback Hoàn Tất

**📍 Trên VPS (root):**

```bash
# Kiểm tra Git remote
cd /var/www/samnghethaycu.com
git remote -v
# ✅ Phải thấy HTTPS URL

# Kiểm tra permissions
ls -la /var/www/samnghethaycu.com
# ✅ Owner phải là: root www-data

# Kiểm tra deploy user
id deploy
# ✅ Phải thấy: no such user (nếu đã xóa)

# Test website
curl https://samnghethaycu.com/health
# ✅ Phải trả về JSON health check
```

**Trên trình duyệt:**

```
https://samnghethaycu.com
```

**✅ Phải thấy:** Laravel welcome page hoạt động bình thường

---

### ✅ Rollback Hoàn Tất!

**Bây giờ VPS về trạng thái sau WORKFLOW-2:**
- ✅ Laravel running (root user owns files)
- ✅ Git repository (HTTPS authentication)
- ✅ Không có deploy user
- ✅ Không có SSH keys
- ✅ Website vẫn hoạt động bình thường

**Để làm lại WORKFLOW-3:**
- Quay lại PHẦN 1 và làm từ đầu

---

## 🔧 XỬ LÝ SỰ CỐ

### Sự cố: Permission denied (publickey)

**Triệu chứng:**

```bash
git pull origin main
# Permission denied (publickey).
# fatal: Could not read from remote repository.
```

**Kiểm tra:**

```bash
# Test SSH connection
ssh -T git@github.com
# Nếu thấy "Permission denied" → SSH key chưa đúng
```

**Fix:**

```bash
# Option 1: Check SSH key exists
ls -la ~/.ssh/id_ed25519.pub
# Nếu không có → Generate lại (Section 2.1)

# Option 2: Verify key on GitHub
cat ~/.ssh/id_ed25519.pub
# Copy và check trên GitHub Settings → SSH keys

# Option 3: Use HTTPS temporarily
git remote set-url origin https://github.com/phuochoavn/websamnghe.git
```

---

### Sự cố: Deploy User Permission Denied

**Triệu chứng:**

```bash
cd /var/www/samnghethaycu.com
# Permission denied
```

**Fix:**

```bash
# SSH với root
ssh root@69.62.82.145

# Fix permissions
sudo chown -R deploy:www-data /var/www/samnghethaycu.com
sudo chmod -R 755 /var/www/samnghethaycu.com

# Test
exit
ssh deploy@69.62.82.145
cd /var/www/samnghethaycu.com
# ✅ Phải vào được
```

---

### Sự cố: Git Pull Hỏi Password

**Triệu chứng:**

```bash
git pull origin main
# Username for 'https://github.com':
```

**Nguyên nhân:** Remote vẫn dùng HTTPS thay vì SSH

**Fix:**

```bash
# Check remote
git remote -v
# Nếu thấy https:// → Đổi sang SSH

git remote set-url origin git@github.com:phuochoavn/websamnghe.git

# Test lại
git pull origin main
# ✅ Không hỏi password nữa
```

---

## 📊 TỔNG KẾT

**Tạo ngày:** 2025-11-16
**Cập nhật:** 2025-11-21
**Version:** 4.0 Professional Vietnamese (Standardized Edition)
**Thời gian:** 10-15 phút thực tế
**Số bước:** 4 phần chính + Rollback

**Những gì đã làm:**
- ✅ Tạo deploy user cho deployment automation
- ✅ Generate SSH key cho GitHub authentication
- ✅ Configure Git identity cho deploy user
- ✅ Reconfigure Git remote to SSH (no password needed)
- ✅ Test full workflow: Windows → GitHub → VPS
- ✅ Rollback procedure chi tiết

**So với WORKFLOW-2:**
- WORKFLOW-2: Setup Git với HTTPS (manual authentication)
- WORKFLOW-3: Upgrade to SSH (automated authentication)
- Chuẩn bị cho WORKFLOW-4: Deployment automation

**Kết quả:**
- ✅ Git workflow hoàn toàn tự động
- ✅ Deploy user separated from root (security)
- ✅ SSH authentication (no password needed)
- ✅ Sẵn sàng cho deployment automation scripts
- ✅ Rollback procedure rõ ràng

**Test Cases Đã Kiểm Tra:**
- ✅ Deploy user creation and permissions
- ✅ SSH key generation and GitHub authentication
- ✅ Git pull without password prompt
- ✅ Full workflow: code change → push → pull → updated
- ✅ Rollback về WORKFLOW-2 state

---

**KẾT THÚC WORKFLOW 3** 🔄
