# ZyDocs

Aplikasi web berbasis Laravel untuk membuat dan mengelola dokumen secara online layaknya Google Docs — dengan kolaborasi teks real-time yang ringan dan responsif.

## Fitur

- **Autentikasi:** Register, Login, Logout, Update Profil
- **Dashboard:** Manajemen dokumen pribadi dan dokumen yang dibagikan
- **Real-time Editor:**
  - Kolaborasi mengetik bersamaan secara *real-time* berbasis textarea ringan
  - Perubahan dikirim langsung lewat **WebSocket channel (whisper)** peer-to-peer tanpa menunggu database — pengguna lain melihat perubahan instan
  - Autosave ke database berjalan di background untuk menyimpan data secara permanen
  - Live cursor tracking — melihat posisi kursor pengguna lain secara dinamis
  - Penanganan konflik pengetikan cerdas (merge, keep mine, keep theirs)
  - Ekspor dokumen ke PDF dan TXT
- **Berbagi Akses:** Bagikan dokumen ke email tertentu dengan opsi `Hanya Lihat` atau `Bisa Edit`
- **Riwayat Versi:** Simpan titik penting tulisan dan restore kapan saja

## Teknologi

- PHP & Laravel 12
- SQLite / MySQL
- Vanilla JavaScript (tanpa library berat)
- CSS Native

## Cara Install

1. Clone repo ini
2. Copy `.env.example` jadi `.env`
3. Sesuaikan konfigurasi database di `.env`
4. Jalankan perintah berikut:

```bash
composer install
php artisan key:generate
php artisan migrate
php artisan serve
```

5. Buka `http://localhost:8000` di browser
