# Sistem Tamu Digital - KPU Kota Ternate

Sistem Tamu Digital KPU Kota Ternate adalah aplikasi berbasis web untuk pencatatan, pengelolaan, dan pelaporan kunjungan tamu di kantor Komisi Pemilihan Umum (KPU) Kota Ternate. Aplikasi ini menggantikan buku tamu manual dengan alur digital yang aman, cepat, dan terstruktur.

## Fitur Utama

- Pendaftaran tamu cepat (nama, instansi, tujuan kunjungan, identitas kontak)
- Pencatatan waktu kedatangan dan kepulangan
- Pencetakan tanda pengenal sementara (visitor badge)
- Pencarian dan filter riwayat kunjungan
- Eksport laporan kunjungan (CSV/PDF)
- Dashboard statistik kunjungan harian/mingguan/bulanan
- Pengaturan peran pengguna (resepsionis, admin)

## Tujuan

- Meningkatkan efisiensi dan akurasi pencatatan tamu
- Menjamin keamanan akses dengan rekam jejak kunjungan
- Memudahkan pembuatan laporan untuk keperluan administrasi dan audit

## Instalasi (Ringkas)

Persyaratan:
- PHP 8.5+
- Composer
- MySQL atau database lain yang didukung Laravel

Langkah singkat pemasangan:

1. Clone repositori:
   git clone https://github.com/fauzanmansur080305-gif/kpu-kota-ternate.git
2. Masuk ke direktori proyek:
   cd kpu-kota-ternate
3. Install dependensi PHP:
   composer install
4. Salin file lingkungan dan atur konfigurasi database:
   cp .env.example .env
   (edit .env sesuai pengaturan database Anda)
5. Generate aplikasi key:
   php artisan key:generate
6. Jalankan migrasi dan seeder (jika tersedia):
   php artisan migrate --seed
7. Jalankan server lokal:
   php artisan serve

## Penggunaan

- Buka aplikasi di browser (mis. http://localhost:8000)
- Masuk sebagai resepsionis atau admin
- Gunakan form tamu untuk mencatat kedatangan
- Kelola riwayat kunjungan dari menu Dashboard atau Laporan

## Kontribusi

Kontribusi untuk penambahbaikan fitur, perbaikan bug, atau dokumentasi sangat diterima. Silakan ajukan Pull Request dan ikuti panduan kontribusi di repo.

## Lisensi

Proyek ini dilisensikan di bawah MIT License. Lihat file LICENSE untuk detail.

---

KPU Kota Ternate — Sistem Tamu Digital

Jika Anda ingin revisi isi README (mis. menambahkan diagram alur, screenshots, atau instruksi deploy), beri tahu saya apa yang perlu ditambahkan.