# ✍️ Writly — Real-Time Productivity App

Aplikasi catatan kolaboratif real-time berbasis web, dibangun dengan **Laravel 13** + **Laravel Reverb** (WebSocket). Dapat diakses oleh siapa saja dalam satu jaringan lokal (LAN) maupun di-deploy ke hosting.

---

## ✨ Fitur

### 🔐 Authentication
- ✅ Register akun baru
- ✅ Login & Logout
- ✅ Remember me
- ✅ Setiap dokumen terikat ke akun pemilik

### 📊 Dashboard
- ✅ Daftar semua dokumen milik user
- ✅ Statistik: Total catatan, diedit hari ini, minggu ini, terakhir diedit
- ✅ Template cepat (Kosong, Resume, Proposal, Catatan Rapat, Laporan)
- ✅ Pencarian dokumen real-time
- ✅ Sort (Terbaru / A–Z)
- ✅ Toggle tampilan Grid / List

### 📝 Manajemen Dokumen
- ✅ Membuat dokumen baru (dari template atau kosong)
- ✅ Mengedit judul dokumen
- ✅ Menghapus dokumen (dengan konfirmasi)
- ✅ Ganti nama dokumen

### ⚡ Realtime Editor
- ✅ Rich Text Editor (Bold, Italic, Underline, Strikethrough)
- ✅ Heading (H1, H2, H3), Font, Font Size
- ✅ Alignment (Kiri, Tengah, Kanan)
- ✅ Bullet List & Numbered List
- ✅ Auto-save setiap 2 detik setelah berhenti mengetik
- ✅ Simpan manual dengan **Ctrl+S**
- ✅ Status simpan: Menyimpan... / Tersimpan / Gagal
- ✅ **Multi-user editing secara real-time** via WebSocket
- ✅ Cursor real-time (lihat posisi kursor user lain)
- ✅ Indikator "Sedang mengetik..."

### 👥 Kolaborasi
- ✅ Lihat siapa saja yang sedang online di dokumen
- ✅ Avatar + nama setiap kolaborator
- ✅ Status online real-time (join/leave/ping)
- ✅ Log aktivitas (bergabung, mengedit, keluar)
- ✅ Tracking editor terakhir (nama + waktu + warna)

### 🕐 Version History
- ✅ Auto-save versi setiap ~60 detik
- ✅ Lihat daftar riwayat perubahan
- ✅ Preview versi sebelumnya langsung di editor
- ✅ Restore ke versi sebelumnya (versi saat ini otomatis disimpan dulu)
- ✅ Maksimal 50 versi per dokumen

### 📤 Export
- ✅ Export ke **PDF** (print-friendly, dialog cetak otomatis)
- ✅ Export ke **DOCX** (Open XML, tanpa package tambahan)

### 🌐 Akses LAN
- ✅ Bisa diakses dari perangkat lain dalam satu jaringan
- ✅ Semua perubahan ter-sync real-time antar device

---

## 🛠 Tech Stack

| Layer | Teknologi |
|-------|-----------|
| Backend | Laravel 13 (PHP 8.3) |
| WebSocket | Laravel Reverb |
| Database | MySQL (via XAMPP) |
| Frontend | Blade + Vanilla JS |
| Realtime | Laravel Echo + Pusher.js |
| Styling | Custom CSS (Writly Design System) |
| Export | PHP ZipArchive (DOCX), Browser Print (PDF) |

---

## 🚀 Cara Menjalankan

### 1. Clone & Install

```bash
git clone https://github.com/AzrilRyzcho/gdocs_real-time.git
cd gdocs_real-time/app
composer install
npm install
```

### 2. Setup Environment

```bash
cp .env.example .env
php artisan key:generate
```

Edit `.env`:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=gdocs
DB_USERNAME=root
DB_PASSWORD=

BROADCAST_CONNECTION=reverb
REVERB_APP_ID=your_id
REVERB_APP_KEY=your_key
REVERB_APP_SECRET=your_secret
REVERB_HOST=localhost
REVERB_PORT=8080
```

### 3. Migrate Database

```bash
php artisan migrate
```

### 4. Jalankan Server

Buka **3 terminal** berbeda:

```bash
# Terminal 1 — Laravel
php artisan serve --host=0.0.0.0 --port=8000

# Terminal 2 — Reverb WebSocket
php artisan reverb:start --host=0.0.0.0 --port=8080
```

### 5. Akses dari Perangkat Lain (LAN)

Cek IP lokal dengan `ipconfig`, lalu buka:
```
http://192.168.x.x:8000
```

> Pastikan semua perangkat terhubung ke jaringan/WiFi yang sama.

---

## 📁 Struktur Utama

```
app/
├── app/
│   ├── Events/
│   │   ├── DocumentUpdated.php       ← Broadcast event perubahan dokumen
│   │   ├── UserPresence.php          ← Broadcast event presence user
│   │   └── CursorMoved.php           ← Broadcast event posisi kursor
│   ├── Http/Controllers/
│   │   ├── Auth/
│   │   │   ├── LoginController.php   ← Login & Logout
│   │   │   └── RegisterController.php← Register
│   │   └── DocumentController.php   ← CRUD + broadcast + version + export
│   └── Models/
│       ├── Document.php              ← Model dokumen
│       ├── DocumentVersion.php       ← Model riwayat versi
│       └── User.php                  ← Model user
├── database/migrations/
│   ├── *_create_users_table.php
│   ├── *_create_documents_table.php
│   ├── *_add_editor_tracking_to_documents_table.php
│   ├── *_add_user_id_to_documents_table.php
│   └── *_create_document_versions_table.php
├── public/
│   ├── css/
│   │   ├── writly-app.css            ← CSS dashboard
│   │   └── writly-auth.css           ← CSS halaman auth
│   └── js/
│       └── writly-app.js             ← JS dashboard
├── resources/views/
│   ├── auth/
│   │   ├── login.blade.php           ← Halaman login
│   │   └── register.blade.php        ← Halaman register
│   └── documents/
│       ├── index.blade.php           ← Dashboard
│       ├── edit.blade.php            ← Editor real-time
│       └── export-pdf.blade.php      ← View export PDF
└── routes/web.php
```

---

## 👨‍💻 Developer

**AzrilRyzcho** — 2026
