# 📝 Docs — Real-Time Productivity App

Aplikasi kolaborasi dokumen real-time mirip Google Docs, dibangun dengan **Laravel 13** + **Laravel Reverb** (WebSocket).

## ✨ Fitur

- ✅ Editor rich-text (Bold, Italic, Underline, List, dll)
- ✅ Sinkronisasi teks **real-time** via WebSocket (Laravel Reverb)
- ✅ Lihat siapa saja yang sedang online di dokumen
- ✅ **Tracking editor**: Lihat siapa yang terakhir mengedit dokumen
- ✅ **Informasi editor real-time**: Nama dan warna editor ditampilkan saat ada yang mengedit
- ✅ Auto-save setiap 1.2 detik setelah berhenti mengetik
- ✅ Simpan manual dengan **Ctrl+S**
- ✅ Multiple dokumen
- ✅ Bisa diakses dari LAN (satu jaringan lokal)
- ✅ Nama editor muncul saat ada yang mengedit

## 🛠 Tech Stack

| Layer      | Teknologi                    |
|------------|------------------------------|
| Backend    | Laravel 13 (PHP 8.3)         |
| WebSocket  | Laravel Reverb               |
| Database   | MySQL (via XAMPP)            |
| Frontend   | Blade + Vanilla JS           |
| Realtime   | Laravel Echo + Pusher.js     |

## 🚀 Cara Menjalankan

### 1. Clone & Install

```bash
git clone https://github.com/AzrilRyzcho/gdocs.git
cd gdocs/app
composer install
npm install
```

### 2. Setup environment

```bash
cp .env.example .env
php artisan key:generate
```

Edit `.env`:
```
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

### 3. Migrate database

```bash
php artisan migrate
```

### 4. Jalankan server

Buka **3 terminal** berbeda:

```bash
# Terminal 1 — Laravel
php artisan serve --host=0.0.0.0 --port=8000

# Terminal 2 — Reverb WebSocket
php artisan reverb:start --host=0.0.0.0 --port=8080

# Terminal 3 — Vite (opsional, untuk development)
npm run dev
```

### 5. Akses dari perangkat lain (LAN)

Cek IP lokal kamu dengan `ipconfig`, lalu buka di browser:
```
http://192.168.x.x:8000
```

> Pastikan semua perangkat terhubung ke jaringan/WiFi yang sama.

## 📁 Struktur Utama

```
app/
├── app/
│   ├── Events/
│   │   ├── DocumentUpdated.php        ← Broadcast event untuk perubahan dokumen
│   │   ├── UserPresence.php           ← Broadcast event untuk presence user
│   │   └── CursorMoved.php            ← Broadcast event untuk posisi cursor
│   ├── Http/Controllers/
│   │   └── DocumentController.php     ← CRUD + broadcast + tracking editor
│   └── Models/Document.php            ← Model dengan field last_editor
├── database/migrations/
│   ├── *_create_documents_table.php
│   └── *_add_editor_tracking_to_documents_table.php
├── resources/views/documents/
│   ├── index.blade.php                ← Landing page dengan info editor
│   └── edit.blade.php                 ← Editor real-time dengan sidebar editor
└── routes/web.php
```

## 🎯 Fitur Tracking Editor

Sistem tracking editor mencatat siapa yang terakhir mengedit dokumen:

### Di Database
Setiap dokumen menyimpan:
- `last_editor_id` — ID unik editor
- `last_editor_name` — Nama editor
- `last_editor_color` — Warna avatar editor
- `last_edited_at` — Waktu terakhir diedit

### Di Halaman Index
Menampilkan "Diedit oleh [Nama] • [waktu]" di setiap kartu dokumen

### Di Halaman Editor
Sidebar menampilkan:
- Semua user yang sedang online
- Info "Terakhir Diedit" dengan avatar dan waktu
- Log aktivitas (join, leave, edit)

### Real-time Updates
Saat user dari device lain mengedit:
- Nama dan warna mereka muncul di sidebar
- Log aktivitas otomatis bertambah
- Informasi "Terakhir Diedit" ter-update setelah save

## 👨‍💻 Developer

**AzrilRyzcho** — 2026
