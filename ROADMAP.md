# 🗺️ Google Docs Clone - Feature Roadmap

## 📊 Status Fitur Saat Ini

### ✅ SUDAH ADA (Implemented)
- **Dashboard Dasar**
  - ✅ Daftar dokumen
  - ✅ Membuat dokumen baru
  - ✅ Mencari dokumen
  - ✅ Menghapus dokumen
  
- **Realtime Editor Dasar**
  - ✅ Rich Text Editor (Bold, Italic, Underline, List)
  - ✅ Auto Save (setiap 1.2 detik)
  - ✅ Multi-user editing
  - ✅ Cursor realtime
  - ✅ Status online
  - ✅ Tracking editor terakhir

### ❌ BELUM ADA (Need Implementation)
- **Authentication** (Login/Register/Logout)
- **Profil Pengguna**
- **Collaboration Advanced** (Share, Permissions, Comments, Mention)
- **Version History**
- **Export** (PDF, DOCX)
- **Notifications**

---

## 🎯 Roadmap Implementasi

### FASE 1: Authentication & User Management (Priority: HIGH)
**Estimasi: 1-2 hari**

#### 1.1 Authentication System
- [ ] Install Laravel Breeze/UI
- [ ] Login page
- [ ] Register page
- [ ] Logout functionality
- [ ] Password reset
- [ ] Remember me

#### 1.2 User Profile
- [ ] User model enhancement (avatar, bio)
- [ ] Profile page
- [ ] Edit profile
- [ ] Upload avatar
- [ ] User preferences

#### 1.3 Integration dengan Dokumen
- [ ] Dokumen ownership (user_id)
- [ ] Only show user's documents
- [ ] Access control

**Files to create/modify:**
```
- database/migrations/*_add_user_id_to_documents.php
- app/Http/Controllers/Auth/* (Breeze)
- resources/views/auth/*
- resources/views/profile/*
- app/Http/Middleware/Authenticate.php
```

---

### FASE 2: Advanced Collaboration (Priority: HIGH)
**Estimasi: 2-3 hari**

#### 2.1 Share Document
- [ ] Share modal/dialog
- [ ] Generate share link
- [ ] Share via email
- [ ] Copy link to clipboard

#### 2.2 Permissions System
- [ ] Owner role
- [ ] Editor role (can edit)
- [ ] Viewer role (read only)
- [ ] Permission middleware
- [ ] Share settings (public/private)

#### 2.3 Comments System
- [ ] Add comment button
- [ ] Comment sidebar
- [ ] Reply to comments
- [ ] Resolve comments
- [ ] Comment notifications

#### 2.4 Mention System
- [ ] @ mention in editor
- [ ] User search dropdown
- [ ] Mention notification
- [ ] Highlight mentioned user

**Files to create:**
```
- database/migrations/*_create_document_shares_table.php
- database/migrations/*_create_comments_table.php
- app/Models/DocumentShare.php
- app/Models/Comment.php
- app/Http/Controllers/ShareController.php
- app/Http/Controllers/CommentController.php
- resources/views/components/share-modal.blade.php
- resources/views/components/comments-sidebar.blade.php
```

---

### FASE 3: Version History (Priority: MEDIUM)
**Estimasi: 2 hari**

#### 3.1 Version Tracking
- [ ] Create versions table
- [ ] Auto-save version every X minutes
- [ ] Store diff/snapshot
- [ ] Version metadata (author, timestamp)

#### 3.2 Version History UI
- [ ] Version history sidebar
- [ ] Timeline view
- [ ] Preview version
- [ ] Compare versions
- [ ] Restore version

**Files to create:**
```
- database/migrations/*_create_document_versions_table.php
- app/Models/DocumentVersion.php
- app/Http/Controllers/VersionController.php
- resources/views/components/version-history.blade.php
- public/js/version-diff.js
```

---

### FASE 4: Export Features (Priority: MEDIUM)
**Estimasi: 1 hari**

#### 4.1 PDF Export
- [ ] Install DomPDF or similar
- [ ] Convert HTML to PDF
- [ ] Preserve formatting
- [ ] Download PDF

#### 4.2 DOCX Export
- [ ] Install PHPWord
- [ ] Convert HTML to DOCX
- [ ] Preserve formatting
- [ ] Download DOCX

**Files to create:**
```
- app/Services/ExportService.php
- app/Http/Controllers/ExportController.php
- routes: /documents/{id}/export/pdf
- routes: /documents/{id}/export/docx
```

**Dependencies:**
```bash
composer require barryvdh/laravel-dompdf
composer require phpoffice/phpword
```

---

### FASE 5: Notification System (Priority: LOW)
**Estimasi: 1-2 hari**

#### 5.1 Notification Infrastructure
- [ ] Laravel notifications setup
- [ ] Database notifications
- [ ] Real-time notifications (Reverb)
- [ ] Email notifications

#### 5.2 Notification Types
- [ ] Document shared notification
- [ ] New comment notification
- [ ] Mention notification
- [ ] User joined document notification

#### 5.3 Notification UI
- [ ] Bell icon in navbar
- [ ] Notification dropdown
- [ ] Mark as read
- [ ] Notification settings

**Files to create:**
```
- database/migrations/*_create_notifications_table.php
- app/Notifications/DocumentShared.php
- app/Notifications/CommentAdded.php
- app/Notifications/UserMentioned.php
- resources/views/components/notifications.blade.php
```

---

## 📦 Dependencies yang Dibutuhkan

### Authentication
```bash
composer require laravel/breeze --dev
php artisan breeze:install blade
```

### Export
```bash
composer require barryvdh/laravel-dompdf
composer require phpoffice/phpword
```

### Image/Avatar
```bash
composer require intervention/image
```

### Search (Optional)
```bash
composer require laravel/scout
composer require meilisearch/meilisearch-php
```

---

## 🔧 Environment Variables Tambahan

```env
# Email (untuk notifications & share)
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your-email@gmail.com
MAIL_PASSWORD=your-app-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=your-email@gmail.com
MAIL_FROM_NAME="${APP_NAME}"

# Storage (untuk avatar)
FILESYSTEM_DISK=public
```

---

## 📈 Prioritas Implementasi

1. **CRITICAL (Harus Segera)**
   - Authentication (tanpa ini, tidak ada user management)
   - Document Ownership (setiap dokumen harus punya owner)

2. **HIGH (Penting)**
   - Share & Permissions (inti dari kolaborasi)
   - Comments (komunikasi antar user)

3. **MEDIUM (Nice to Have)**
   - Version History (data safety)
   - Export (portability)

4. **LOW (Bonus)**
   - Notifications (UX enhancement)
   - Mention (advanced collaboration)

---

## 🚀 Quick Start Implementation

### Step 1: Install Authentication (5 menit)
```bash
cd c:\xampp\gdocs\app
composer require laravel/breeze --dev
php artisan breeze:install blade
php artisan migrate
npm install && npm run build
```

### Step 2: Add User ID to Documents (10 menit)
```bash
php artisan make:migration add_user_id_to_documents_table
```

Kemudian edit migration:
```php
public function up()
{
    Schema::table('documents', function (Blueprint $table) {
        $table->foreignId('user_id')->nullable()->constrained()->onDelete('cascade');
    });
}
```

```bash
php artisan migrate
```

### Step 3: Update DocumentController (15 menit)
Tambahkan authentication middleware dan user_id saat create dokumen.

---

## 📝 Notes

- Implementasi dilakukan secara bertahap
- Setiap fase harus di-test sebelum lanjut ke fase berikutnya
- Dokumentasi API akan dibuat seiring implementasi
- UI/UX mengikuti design Google Docs yang sudah ada

---

**Last Updated:** 2026-06-27
**Version:** 1.0.0
