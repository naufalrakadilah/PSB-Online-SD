# Panduan Bukti 17 Unit Kompetensi SKKNI - Web PSB Online SD

Aplikasi **PSB Online SD Negeri Nusantara** dibangun menggunakan framework **Laravel 11** dan database **SQLite** (tanpa membutuhkan Laragon / XAMPP / server database pihak ketiga).

Berikut adalah pemetaan lengkap **17 Unit Kompetensi** yang diujikan beserta bukti implementasi kode sumber dan cara demonstrasi kepada asesor:

---

## Ringkasan Pemetaan 17 Unit Kompetensi

| No | Kode Unit | Judul Unit Kompetensi | Bukti Implementasi di Proyek |
|---|---|---|---|
| 1 | **J.620100.001.01** | Menganalisis Tools | PHP 8.5+, Composer, SQLite, Artisan CLI, Git |
| 2 | **J.620100.002.01** | Menganalisis Skalabilitas Perangkat Lunak | Database Indexing, Pagination data, SQLite Zero-Config, Storage terpisah |
| 3 | **J.620100.003.01** | Mengidentifikasi Library, Komponen atau Framework | Laravel 11, Bootstrap 5.3, Bootstrap Icons, AOS Animation |
| 4 | **J.620100.006.01** | Merancang User Experience (UX) | Multi-step form interaktif, status timeline, desain responsif, feedback visual |
| 5 | **J.620100.017.02** | Mengimplementasikan Pemrograman Terstruktur | Modular routing, Blade templating inheritance, middleware, helper methods |
| 6 | **J.620100.018.02** | Mengimplementasikan Pemrograman Berorientasi Objek (OOP) | Model `Pendaftaran`, `User`, Controller inheritance, encapsulation, Eloquent |
| 7 | **J.620100.020.02** | Menggunakan SQL | Raw aggregate SQL (`COUNT`, `SUM CASE`, `GROUP BY`) di dashboard & statistik |
| 8 | **J.620100.021.02** | Menerapkan Akses Basis Data | CRUD Pendaftaran, Database Migrations, Database Seeding (`AdminSeeder`, `PendaftaranSeeder`) |
| 9 | **J.620100.022.02** | Mengimplementasikan Algoritma Pemrograman | Algoritma auto-increment nomor pendaftaran (`PSB-YYYY-XXXX`), validasi usia SD, directory size rekursif |
| 10 | **J.620100.024.02** | Melakukan Migrasi Ke Teknologi Baru | Skema migrasi Laravel SQLite tanpa Laragon, struktur modern Laravel 11 |
| 11 | **J.620100.025.02** | Melakukan Debugging | Penanganan exception (`try-catch`), logging menyeluruh via `Log::info` dan `Log::error` |
| 12 | **J.620100.030.02** | Menerapkan Pemrograman Multimedia | Fitur upload foto siswa (JPEG/PNG), Akta Kelahiran (PDF/Image), Kartu Keluarga, dan preview dokumen |
| 13 | **J.620100.032.01** | Menerapkan Code Review | Kepatuhan standar PSR-12, dokumentasi PHPDoc terstruktur, arsitektur MVC |
| 14 | **J.620100.036.02** | Melaksanakan Pengujian Kode Program Secara Statis | Unit & Feature automated testing via PHPUnit (`php artisan test`) |
| 15 | **J.620100.044.01** | Menerapkan Alert Notification Jika Aplikasi Bermasalah | Banner alert kegagalan dinamis, flash error messages, indikator error visual |
| 16 | **J.620100.045.01** | Melakukan Pemantauan Resource yang Digunakan Aplikasi | Widget Server & Resource Monitor di Dashboard Admin (RAM, SQLite size, Disk space, Uptime) |
| 17 | **J.620100.047.01** | Melakukan Pembaharuan Perangkat Lunak | Manajemen skema database versi via `artisan migrate` dan update dependency via Composer |

---

## Penjelasan Rinci Setiap Kompetensi & Bukti Kode

### 1. J.620100.001.01 - Menganalisis Tools
- **Lokasi Kode**: `composer.json`, `.env`, `package.json`
- **Bukti**:
  - Pemilihan tool runtime: PHP 8.5+ dan Composer sebagai dependency manager.
  - Pemilihan database engine: SQLite lokal pada `database/database.sqlite` sehingga portabel, tanpa server eksternal/Laragon.
  - Frontend tools: Bootstrap 5.3 CDN dan Bootstrap Icons untuk efisiensi loading tanpa build tools berat.

### 2. J.620100.002.01 - Menganalisis Skalabilitas Perangkat Lunak
- **Lokasi Kode**: `database/migrations/2024_01_01_000001_create_pendaftaran_table.php`, `app/Http/Controllers/AdminController.php`
- **Bukti**:
  - Penambahan database index pada kolom pencarian: `$table->index('no_pendaftaran');`, `$table->index('status');`, `$table->index('nama_lengkap');`.
  - Pagination data: Menggunakan `$query->paginate(15)` agar server tidak overhead saat jutaan calon siswa mendaftar.
  - Penyimpanan berkas terpisah menggunakan `Storage::disk('public')`.

### 3. J.620100.003.01 - Identifikasi Library, Komponen atau Framework
- **Lokasi Kode**: `composer.json`, `resources/views/layouts/app.blade.php`
- **Bukti**:
  - Identifikasi framework backend: Laravel 11 untuk routing, ORM, autentikasi, dan validasi data.
  - Identifikasi library frontend: Bootstrap 5.3 untuk responsivitas layout, AOS (Animate on Scroll) untuk estetika micro-animations modern.

### 4. J.620100.006.01 - Merancang User Experience (UX)
- **Lokasi Kode**: `resources/views/pendaftaran/create.blade.php`, `resources/views/pendaftaran/cek-status.blade.php`
- **Bukti**:
  - Multi-step Form dengan Step Indicator visual (Langkah 1: Data Siswa -> Langkah 2: Data Orang Tua -> Langkah 3: Upload Berkas).
  - Status Tracking Timeline interaktif: Calon siswa dapat melihat posisi berkas (Pendaftaran diajukan -> Verifikasi Berkas -> Pengumuman Hasil Seleksi).
  - Feedback interaktif: Validasi form langsung, auto-dismiss notifikasi flash message.

### 5. J.620100.017.02 - Mengimplementasikan Pemrograman Terstruktur
- **Lokasi Kode**: `routes/web.php`, `resources/views/layouts/app.blade.php`
- **Bukti**:
  - Route Grouping terstruktur dengan prefix `admin` dan middleware `auth`.
  - Templating inheritance: Layout master `layouts/app.blade.php` dan `layouts/admin.blade.php` yang di-extend oleh halaman anak menggunakan `@section` dan `@yield`.

### 6. J.620100.018.02 - Mengimplementasikan Pemrograman Berorientasi Objek (OOP)
- **Lokasi Kode**: `app/Models/Pendaftaran.php`, `app/Http/Controllers/PendaftaranController.php`
- **Bukti**:
  - Enkapsulasi data dengan properti `$fillable` dan `$casts`.
  - Method statis dan scope query: `Pendaftaran::generateNoPendaftaran()`, `scopeByStatus()`, `scopeSearch()`.
  - Pewarisan class (Inheritance): Controller meng-extend base `App\Http\Controllers\Controller`.

### 7. J.620100.020.02 - Menggunakan SQL
- **Lokasi Kode**: `app/Http/Controllers/AdminController.php` (baris 110-147), `app/Http/Controllers/PengumumanController.php` (baris 51-58)
- **Bukti**:
  - Query aggregate lanjutan raw SQL:
    ```sql
    SELECT 
        COUNT(*) as total,
        SUM(CASE WHEN status = 'menunggu' THEN 1 ELSE 0 END) as menunggu,
        SUM(CASE WHEN status = 'diverifikasi' THEN 1 ELSE 0 END) as diverifikasi,
        SUM(CASE WHEN status = 'diterima' THEN 1 ELSE 0 END) as diterima,
        SUM(CASE WHEN status = 'ditolak' THEN 1 ELSE 0 END) as ditolak
    FROM pendaftaran 
    WHERE tahun_ajaran = ?
    ```
  - Query filtering berdasarkan tanggal: `date('now', '-7 days')`.

### 8. J.620100.021.02 - Menerapkan Akses Basis Data
- **Lokasi Kode**: `app/Http/Controllers/PendaftaranController.php`, `app/Http/Controllers/AdminController.php`
- **Bukti**:
  - Operasi Create: `Pendaftaran::create($data)` saat siswa mendaftar.
  - Operasi Read: `Pendaftaran::findOrFail($id)` dan query pagination.
  - Operasi Update: `$pendaftaran->update(['status' => $request->status])` di panel verifikasi admin.
  - Operasi Delete: `$pendaftaran->delete()` beserta pembersihan file berkas terkait di storage.

### 9. J.620100.022.02 - Mengimplementasikan Algoritma Pemrograman
- **Lokasi Kode**: `app/Models/Pendaftaran.php` (method `generateNoPendaftaran`), `app/Http/Controllers/AdminController.php` (method `getDirectorySize` & `formatBytes`)
- **Bukti**:
  - Algoritma auto-increment nomor pendaftaran otomatis dengan format string padded `PSB-YYYY-XXXX`:
    ```php
    $lastNumber = (int) substr($lastRegistration->no_pendaftaran, -4);
    $newNumber = $lastNumber + 1;
    return $prefix . str_pad($newNumber, 4, '0', STR_PAD_LEFT);
    ```
  - Algoritma rekursif traversal direktori untuk menghitung kapasitas penyimpanan dokumen.
  - Algoritma konversi ukuran bytes ke string human-readable (B, KB, MB, GB).

### 10. J.620100.024.02 - Melakukan Migrasi Ke Teknologi Baru
- **Lokasi Kode**: `database/migrations/2024_01_01_000001_create_pendaftaran_table.php`, `.env`
- **Bukti**:
  - Migrasi arsitektur dari sistem konvensional (MySQL/Laragon) ke SQLite tanpa instalasi server pihak ketiga.
  - Skema database versioning yang dapat di-deploy ke environment mana pun hanya dengan satu perintah: `php artisan migrate`.

### 11. J.620100.025.02 - Melakukan Debugging
- **Lokasi Kode**: `app/Http/Controllers/PendaftaranController.php`, `app/Http/Controllers/AdminController.php`
- **Bukti**:
  - Blok penanganan kesalahan `try-catch` di seluruh alur bisnis kritis.
  - Logging diagnostik menggunakan `Illuminate\Support\Facades\Log`:
    - `Log::info()` saat pendaftaran/login berhasil.
    - `Log::warning()` saat validasi form atau percobaan login gagal.
    - `Log::error()` menangkap `$e->getMessage()`, line, dan stack trace untuk pelacakan bug.

### 12. J.620100.030.02 - Menerapkan Pemrograman Multimedia
- **Lokasi Kode**: `app/Http/Controllers/PendaftaranController.php` (baris 120-135), `resources/views/admin/detail.blade.php`
- **Bukti**:
  - Validasi file multimedia (MIME type check): Foto Siswa (`jpeg,png,jpg`), Akta Kelahiran, KK, Ijazah TK (`pdf,jpeg,png`).
  - Pembatasan ukuran berkas (2MB untuk foto, 5MB untuk dokumen).
  - Penamaan file unik berbasis nomor pendaftaran dan timestamp untuk mencegah collision.
  - Tampilan preview multimedia interaktif di detail pendaftar admin.

### 13. J.620100.032.01 - Menerapkan Code Review
- **Lokasi Kode**: Seluruh file Controller, Model, dan Migrasi
- **Bukti**:
  - Kepatuhan struktur penulisan kode terhadap standar PSR-12 (PHP Standard Recommendation).
  - Standarisasi penamaan variabel (camelCase), nama tabel (snake_case), dan class (PascalCase).
  - Blok komentar PHPDoc lengkap dengan anotasi `@param`, `@return`, dan penanda unit kompetensi.

### 14. J.620100.036.02 - Melaksanakan Pengujian Kode Program Secara Statis
- **Lokasi Kode**: `tests/Feature/PsbOnlineTest.php`
- **Bukti**:
  - 10 test case otomatis menggunakan PHPUnit yang memvalidasi landing page, alur formulir, validasi NIK 16 digit, autentikasi admin, dan cek status.
  - Menjalankan uji kode otomatis dengan perintah:
    ```bash
    php artisan test
    ```
    Hasil: **10 passed, 22 assertions**.

### 15. J.620100.044.01 - Menerapkan Alert Notification Jika Aplikasi Bermasalah
- **Lokasi Kode**: `resources/views/admin/dashboard.blade.php` (baris 7-16), `resources/views/layouts/app.blade.php` (baris 565-582)
- **Bukti**:
  - Banner darurat sistem jika database atau storage mengalami gangguan.
  - Komponen alert dinamis: `alert-psb error` dan `alert-psb success` dengan animasi transisi dan auto-dismiss.

### 16. J.620100.045.01 - Melakukan Pemantauan Resource yang Digunakan Aplikasi
- **Lokasi Kode**: `app/Http/Controllers/AdminController.php` (method `getResourceInfo`), `resources/views/admin/dashboard.blade.php`
- **Bukti**:
  - Widget server monitoring real-time di Dashboard Admin menampilkan:
    - Penggunaan Memory RAM aplikasi (`memory_get_usage()`).
    - Puncak Memory RAM (`memory_get_peak_usage()`).
    - Ukuran berkas database SQLite (`database/database.sqlite`).
    - Ukuran total direktori berkas terupload (`storage/app/public`).
    - Sisa ruang disk kosong (`disk_free_space()`).
    - Uptime server sistem.

### 17. J.620100.047.01 - Melakukan Pembaharuan Perangkat Lunak
- **Lokasi Kode**: `database/migrations/`, `composer.json`
- **Bukti**:
  - Kemampuan rollback dan pembaruan struktur basis data tanpa kehilangan data melalui `php artisan migrate`.
  - Integrasi migrasi otomatis saat versi aplikasi diperbarui.

---

## Cara Menjalankan & Mendemonstrasikan Aplikasi

1. **Jalankan Development Server**:
   ```bash
   php artisan serve --port=8000
   ```
2. **Akses Halaman Publik**:
   - **Beranda**: [http://127.0.0.1:8000](http://127.0.0.1:8000)
   - **Form Pendaftaran**: [http://127.0.0.1:8000/pendaftaran](http://127.0.0.1:8000/pendaftaran)
   - **Cek Status Pendaftaran**: [http://127.0.0.1:8000/cek-status](http://127.0.0.1:8000/cek-status) *(Coba nomor contoh: `PSB-2026-0001` atau `PSB-2026-0005`)*
   - **Pengumuman Kelulusan**: [http://127.0.0.1:8000/pengumuman](http://127.0.0.1:8000/pengumuman)

3. **Akses Dashboard Admin**:
   - **URL Login**: [http://127.0.0.1:8000/admin/login](http://127.0.0.1:8000/admin/login)
   - **Email Admin**: `admin@psbsd.test`
   - **Password**: `admin123`
   - Di dashboard, klik tombol **"Cek 17 Unit Kompetensi SKKNI"** untuk menampilkan bukti interaktif pada asesor.

4. **Jalankan Pengujian Otomatis (Testing)**:
   ```bash
   php artisan test
   ```
