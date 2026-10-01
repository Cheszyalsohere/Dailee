# Dailee

Aplikasi daily log, agenda, dan memories untuk mahasiswa. Dibangun dengan CodeIgniter 4, Tailwind CSS (CDN), dan MySQL.

## Fitur
- Daily log foto (kamera atau galeri) dengan mood dan visibilitas
- Agenda dan to-do dengan sinkronisasi kalender (.ics)
- Memories, kalender, dan rekap bulanan
- Pertemanan, follow, direct message, dan notifikasi
- Asisten AI (Gemini)

## Menjalankan secara lokal
Butuh PHP 8.2+ (ekstensi `intl`, `mbstring`, `mysqli`, `curl`), Composer, dan MySQL.

```bash
composer install
cp .env.example .env     # isi kredensial database dan GEMINI_API_KEY
php spark migrate
php spark db:seed AdminSeeder
php spark serve
```

Akun admin awal dibuat dari `ADMIN_USERNAME`, `ADMIN_EMAIL`, dan `ADMIN_PASSWORD` di `.env`.
Kalau `ADMIN_PASSWORD` kosong, seeder membuat password acak dan menampilkannya sekali.

## Deploy
- Document root harus mengarah ke folder `public/`.
- Atur `CI_ENVIRONMENT = production` dan `app.baseURL` di `.env`.
- Jalankan `composer install --no-dev --optimize-autoloader`, lalu `php spark migrate`.
- Folder `writable/` dan `public/uploads/` harus bisa ditulis.
