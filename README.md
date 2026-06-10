# LibSpace - Aplikasi Perpustakaan Digital

## Status Implementasi: ✅ SELESAI DAN BERFUNGSI

Aplikasi perpustakaan digital telah berhasil dibuat menggunakan CodeIgniter 4, Bootstrap 5, dan MySQL dengan semua fitur yang diperlukan sudah berfungsi dengan sempurna.

---

## Fitur yang Telah Diimplementasikan

### 1. **Authentication & Authorization** ✅
- Login dengan username dan password
- Password hashing menggunakan `password_hash()` dan `password_verify()`
- Session management dengan CSRF protection
- Logout functionality
- Role-based access control (Admin & User)
- Auth filter untuk proteksi route

### 2. **Admin Panel** ✅

#### Dashboard Admin
- Statistik dashboard (Jumlah Buku, User, Peminjaman, Sedang Dipinjam)
- Daftar buku terbaru
- Modern UI dengan sidebar navigation

#### Manajemen Buku (CRUD)
- ✅ **Create**: Form tambah buku baru dengan validasi
- ✅ **Read**: Tabel daftar semua buku dengan pagination
- ✅ **Update**: Form edit buku dengan pre-filled data
- ✅ **Delete**: Hapus buku dengan konfirmasi
- Data fields: Nama, Penulis, Penerbit, Tahun Terbit, Stok

#### Manajemen Peminjaman
- Melihat semua data peminjaman dengan user & buku info
- Status peminjaman (Dipinjam/Dikembalikan)
- Kolom denda otomatis
- Approve pengembalian buku dari admin

### 3. **Landing Page** ✅
- Navbar dengan link login/register
- Hero section dengan welcome message
- Daftar buku terbaru (6 buku)
- Responsive design dengan Bootstrap 5
- Footer dengan copyright

### 4. **User Dashboard** ✅
- Daftar semua buku yang tersedia
- Search/Pencarian buku (by nama, penulis, penerbit)
- Filter berdasarkan status stok
- Tombol "Pinjam Buku" yang responsive
- Sidebar user panel dengan link ke history

### 5. **Sistem Peminjaman Buku** ✅
- User dapat meminjam buku max 1 copy per user
- Stok buku berkurang 1 saat dipinjam
- Batas waktu peminjaman otomatis 7 hari
- Validasi stok dan duplikasi peminjaman
- History peminjaman user dengan detail lengkap

### 6. **Sistem Pengembalian & Denda** ✅
- User dapat mengembalikan buku dari history
- Kalkulasi denda otomatis: Rp2000 x jumlah hari telat
- Stok buku bertambah 1 saat dikembalikan
- Status peminjaman berubah menjadi "Dikembalikan"
- Display denda dalam format currency

### 7. **User Interface** ✅
- **Bootstrap 5 CDN** untuk styling modern
- **Color Scheme**: Biru & Putih (Primary: #2c3e50, Secondary: #3498db)
- **Responsive Design** untuk mobile & desktop
- **Sidebar Navigation** untuk Admin & User
- **Card-based Layout** untuk daftar buku dengan hover effect
- **Alert Messages** dengan auto-dismiss untuk feedback user
- **Tabel Responsive** dengan hover effect
- **Footer** dengan copyright & kredit
- **Emoji Icons** untuk visual appeal

### 8. **Database** ✅
- Database: **LibSpace** (MySQL)
- 3 tabel utama:
  - `users` (id, username, password, role, full_name, created_at)
  - `books` (id, nama_buku, penulis, penerbit, tahun_terbit, stok, created_at)
  - `peminjaman` (id, user_id, book_id, tanggal_pinjam, tanggal_kembali, tanggal_dikembalikan, status, denda, created_at)
- Foreign key constraints untuk data integrity
- Sample data: 5 buku + 1 admin user

### 9. **Routing & Navigation** ✅
- Landing page `/`
- Auth: `/auth/login`, `/auth/register`, `/auth/logout`
- Admin: `/admin/dashboard`, `/admin/books*`, `/admin/peminjaman`, `/admin/approve-return/:id`
- User: `/user/dashboard`, `/user/search`, `/user/borrow/:id`, `/user/history`, `/user/return/:id`

### 10. **Security** ✅
- Session-based login dengan token CSRF
- Password hashing BCRYPT (PASSWORD_BCRYPT)
- Input sanitasi dengan esc()
- Form CSRF protection
- Role-based route filter
- No direct database access dari user

---

## Cara Menjalankan

### 1. **Prasyarat**
- XAMPP (Apache + MySQL + PHP 8.2+)
- Composer

### 2. **Setup Database**
Database sudah dibuat otomatis saat project setup. Data sudah tercantum di `database.sql`.

### 3. **Jalankan Server**
```bash
cd C:\xampp\htdocs\LibSpace
php spark serve
```

Server akan berjalan di: **http://localhost:8080**

### 4. **Login Credentials**

**Admin**
- Username: `admin`
- Password: `admin123`

**User Registration**
- Buka `/auth/register`
- Isi username & password
- Login dengan credential yang dibuat

---

## Struktur Project

```
LibSpace/
├── app/
│   ├── Controllers/
│   │   ├── Auth.php          → Login, Register, Logout
│   │   ├── Admin.php         → Dashboard Admin, CRUD Buku, Peminjaman
│   │   ├── Home.php          → Landing Page
│   │   └── User.php          → Dashboard User, Peminjaman
│   ├── Models/
│   │   ├── UserModel.php     → User database queries
│   │   ├── BookModel.php     → Book database queries
│   │   └── PeminjamanModel.php → Loan database queries
│   ├── Views/
│   │   ├── layout.php        → Master template
│   │   ├── auth/, home/, admin/, user/ → Page templates
│   ├── Filters/
│   │   └── Auth.php          → Authentication filter
│   └── Config/
│       ├── Routes.php        → URL routing
│       └── Filters.php       → Filter configuration
└── database.sql              → Database setup script
```

---

## Testing Checklist

- ✅ Landing page dengan daftar buku
- ✅ Login admin berfungsi
- ✅ Admin dashboard menampilkan statistik
- ✅ CRUD buku lengkap (Create, Read, Update, Delete)
- ✅ User registration berfungsi
- ✅ User login berfungsi
- ✅ User dashboard menampilkan buku
- ✅ Search buku berfungsi
- ✅ Peminjaman mengurangi stok
- ✅ History peminjaman menampilkan riwayat
- ✅ Pengembalian menambah stok
- ✅ Denda dihitung otomatis
- ✅ Session & logout berfungsi
- ✅ Role-based access control
- ✅ Alert messages (success/error)
- ✅ Responsive di mobile & desktop

---

## Teknologi

| Aspek | Teknologi |
|-------|-----------|
| Backend Framework | CodeIgniter 4.7.3 |
| Database | MySQL 8.0+ |
| Frontend | Bootstrap 5.3.0 (CDN) |
| Server | XAMPP (Apache 2.4, PHP 8.2) |
| Bahasa | PHP & HTML/CSS |

---

## Author

Dibuat dengan ❤️ menggunakan **CodeIgniter 4** & **Bootstrap 5**

**Status**: ✅ SELESAI & FULLY FUNCTIONAL


When updating, check the release notes to see if there are any changes you might need to apply
to your `app` folder. The affected files can be copied or merged from
`vendor/codeigniter4/framework/app`.

## Setup

Copy `env` to `.env` and tailor for your app, specifically the baseURL
and any database settings.

## Important Change with index.php

`index.php` is no longer in the root of the project! It has been moved inside the *public* folder,
for better security and separation of components.

This means that you should configure your web server to "point" to your project's *public* folder, and
not to the project root. A better practice would be to configure a virtual host to point there. A poor practice would be to point your web server to the project root and expect to enter *public/...*, as the rest of your logic and the
framework are exposed.

**Please** read the user guide for a better explanation of how CI4 works!

## Repository Management

We use GitHub issues, in our main repository, to track **BUGS** and to track approved **DEVELOPMENT** work packages.
We use our [forum](http://forum.codeigniter.com) to provide SUPPORT and to discuss
FEATURE REQUESTS.

This repository is a "distribution" one, built by our release preparation script.
Problems with it can be raised on our forum, or as issues in the main repository.

## Server Requirements

PHP version 8.2 or higher is required, with the following extensions installed:

- [intl](http://php.net/manual/en/intl.requirements.php)
- [mbstring](http://php.net/manual/en/mbstring.installation.php)

> [!WARNING]
> - The end of life date for PHP 7.4 was November 28, 2022.
> - The end of life date for PHP 8.0 was November 26, 2023.
> - The end of life date for PHP 8.1 was December 31, 2025.
> - If you are still using below PHP 8.2, you should upgrade immediately.
> - The end of life date for PHP 8.2 will be December 31, 2026.

Additionally, make sure that the following extensions are enabled in your PHP:

- json (enabled by default - don't turn it off)
- [mysqlnd](http://php.net/manual/en/mysqlnd.install.php) if you plan to use MySQL
- [libcurl](http://php.net/manual/en/curl.requirements.php) if you plan to use the HTTP\CURLRequest library
