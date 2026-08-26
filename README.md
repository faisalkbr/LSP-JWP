# MarketPlace Sederhana

Aplikasi web marketplace sederhana yang dibangun sebagai jawaban Kelompok Pekerjaan 2
(Menulis Kode Sumber) uji kompetensi **LSP Junior Web Programmer**.

Aplikasi memungkinkan pengunjung melihat katalog produk, mendaftar sebagai pembeli
atau penjual, masuk ke sistem, dan — bagi penjual — mengelola produk toko sendiri
secara penuh (tambah, ubah, hapus, unggah gambar).

---

## 1. Unit Kompetensi yang Dicakup

| Kode Unit | Judul Unit | Bukti pada proyek ini |
|---|---|---|
| J.620100.011.01 | Melakukan instalasi software tools pemrograman | Laravel 12 dipasang via Composer, MySQL 8.0, `php artisan serve`; langkah instalasi ada di Bagian 4 |
| J.620100.016.01 | Menulis kode dengan prinsip sesuai guidelines dan best practices | Penamaan konsisten (PascalCase/camelCase/snake_case), komentar header di setiap berkas, komponen Blade `badge-status` untuk menghindari pengulangan |
| J.620100.017.02 | Mengimplementasikan pemrograman terstruktur | Pemisahan Controller — Form Request — Model — View, middleware `EnsureRole`, method privat pembantu pada controller |
| J.620100.019.02 | Menggunakan library atau komponen pre-existing | Framework Laravel 12, Eloquent ORM, Blade, dan Bootstrap 5 via CDN |
| J.620100.023.02 | Membuat dokumen kode program | Berkas README ini, komentar pada seluruh berkas sumber, serta dokumen di folder `docs/` |
| J.620100.025.02 | Melakukan debugging | [`docs/debugging.md`](docs/debugging.md) dan [`docs/pengujian.md`](docs/pengujian.md), ditambah satu feature test otomatis |

---

## 2. Kebutuhan Sistem

| Komponen | Versi minimum | Versi yang dipakai saat pengembangan |
|---|---|---|
| PHP | 8.2 | 8.4.16 |
| Composer | 2.x | 2.9.5 |
| MySQL / MariaDB | MySQL 5.7 | MySQL 8.0.45 |
| Ekstensi PHP | `pdo_mysql`, `mbstring`, `openssl`, `fileinfo`, `gd` | aktif semua |

Node.js dan npm **tidak diperlukan**. Bootstrap 5 dimuat lewat CDN sehingga proyek
tidak memakai Vite maupun proses build aset.

---

## 3. Teknologi yang Digunakan

- **Laravel 12** — routing, Eloquent ORM, Blade, validasi lewat Form Request, autentikasi via facade `Auth`
- **Bootstrap 5.3** (CDN) — seluruh tampilan; tidak ada CSS kustom
- **MySQL 8** — basis data sesuai PDM hasil Kelompok Pekerjaan 1

Autentikasi ditulis manual di atas facade `Auth` bawaan Laravel. Starter kit
(Breeze/Jetstream/Livewire) sengaja tidak dipasang agar tampilan tetap mengikuti
mock-up Bootstrap dan proyek bebas dari Tailwind serta Vite.

---

## 4. Langkah Instalasi

```bash
# 1. Ambil kode sumber
git clone <url-repositori> marketplace
cd marketplace

# 2. Pasang dependensi PHP
composer install

# 3. Siapkan berkas konfigurasi
cp .env.example .env

# 4. Buat kunci aplikasi
php artisan key:generate

# 5. Buat basis data kosong bernama `marketplace`
#    lalu sesuaikan bagian berikut pada .env:
#      DB_CONNECTION=mysql
#      DB_HOST=127.0.0.1
#      DB_PORT=3306
#      DB_DATABASE=marketplace
#      DB_USERNAME=root
#      DB_PASSWORD=<password MySQL Anda>

# 6. Buat tabel dan isi data contoh
php artisan migrate --seed

# 7. Buat tautan storage agar gambar produk dapat diakses peramban
php artisan storage:link

# 8. Jalankan aplikasi
php artisan serve
```

Aplikasi dapat dibuka di <http://127.0.0.1:8000>.

Untuk mengulang basis data dari nol beserta data contohnya:

```bash
php artisan migrate:fresh --seed
```

---

## 5. Akun Demo

Keduanya dibuat oleh `DemoSeeder` dengan password yang di-hash memakai `Hash::make()`.

| Peran | Email | Password | Tujuan setelah login |
|---|---|---|---|
| Penjual | `penjual@demo.test` | `password` | `/penjual/produk` (Manajemen Produk) |
| Pembeli | `pembeli@demo.test` | `password` | `/` (Landing Page) |

Data contoh lain: 5 produk milik akun penjual di atas, salah satunya berstatus `habis`
dan seluruhnya tanpa gambar agar penanganan gambar kosong ikut terlihat.

---

## 6. Fitur per Peran

### Pengunjung (belum login)
- Melihat landing page beserta katalog seluruh produk dari basis data
- Melihat halaman detail produk
- Mendaftar sebagai pembeli atau penjual
- Masuk ke sistem

### Pembeli
- Seluruh fitur pengunjung
- Navbar menampilkan nama dan peran akun
- Menambah produk ke keranjang, mengubah jumlah, dan menghapus item
- Checkout: mengisi alamat pengiriman lalu membuat pesanan
- Melihat riwayat pesanan beserta rinciannya
- Mengunggah bukti pembayaran per pesanan
- Keluar dari sistem
- Tidak dapat membuka area penjual (ditolak middleware `role:penjual`)

### Penjual
- Seluruh fitur pengunjung
- Melihat daftar produk **miliknya sendiri**
- Menambah produk baru beserta gambar
- Mengubah data produk; gambar lama dipertahankan bila tidak diganti
- Menghapus produk dengan dialog konfirmasi; berkas gambarnya ikut dihapus
- Tidak dapat melihat maupun mengubah produk milik penjual lain
- Tidak dapat membuka area pembeli (keranjang, checkout, pesanan)

---

## 7. Struktur Berkas Utama

```
app/
  Http/
    Controllers/
      LandingController.php            # landing page + detail produk
      AuthController.php               # register, login, logout
      KeranjangController.php          # keranjang belanja berbasis session
      OrderController.php              # checkout, riwayat pesanan, bukti bayar
      Penjual/ProdukController.php     # resource controller manajemen produk
    Middleware/EnsureRole.php          # pembatas akses berdasarkan kolom role
    Requests/
      RegisterRequest.php              # validasi pendaftaran
      LoginRequest.php                 # validasi bentuk masukan login
      ProdukRequest.php                # validasi tambah & ubah produk
      KeranjangRequest.php             # validasi perubahan quantity
      CheckoutRequest.php              # validasi alamat pengiriman
      BuktiBayarRequest.php            # validasi unggahan bukti bayar
  Models/
    User.php  Produk.php  Order.php  OrderDetail.php
database/
  migrations/                          # 4 migrasi sesuai PDM
  seeders/DemoSeeder.php               # 1 penjual + 5 produk + 1 pembeli
resources/views/
  layouts/app.blade.php                # Bootstrap CDN, navbar, flash message
  components/badge-status.blade.php    # <x-badge-status :status="..." />
  landing.blade.php
  produk-detail.blade.php
  checkout.blade.php
  keranjang/index.blade.php
  pesanan/index.blade.php
  auth/register.blade.php
  auth/login.blade.php
  penjual/produk/index.blade.php
  penjual/produk/create.blade.php
  penjual/produk/edit.blade.php
routes/web.php
tests/Feature/ProdukTest.php
docs/
  pengujian.md                         # 25 skenario uji manual + hasilnya
  debugging.md                         # catatan galat, penyebab, dan solusinya
```

---

## 8. Daftar Route

| Metode | URL | Nama Route | Akses |
|---|---|---|---|
| GET | `/` | `landing` | Publik |
| GET | `/produk/{id}` | `produk.show` | Publik |
| GET | `/register` | `register` | Tamu |
| POST | `/register` | `register.store` | Tamu |
| GET | `/login` | `login` | Tamu |
| POST | `/login` | `login.store` | Tamu |
| POST | `/logout` | `logout` | Sudah login |
| GET | `/penjual/produk` | `penjual.produk.index` | Penjual |
| GET | `/penjual/produk/create` | `penjual.produk.create` | Penjual |
| POST | `/penjual/produk` | `penjual.produk.store` | Penjual |
| GET | `/penjual/produk/{id}/edit` | `penjual.produk.edit` | Penjual |
| PUT | `/penjual/produk/{id}` | `penjual.produk.update` | Penjual |
| DELETE | `/penjual/produk/{id}` | `penjual.produk.destroy` | Penjual |
| GET | `/keranjang` | `keranjang.index` | Pembeli |
| POST | `/keranjang/{id}` | `keranjang.store` | Pembeli |
| PUT | `/keranjang/{id}` | `keranjang.update` | Pembeli |
| DELETE | `/keranjang/{id}` | `keranjang.destroy` | Pembeli |
| GET | `/checkout` | `checkout.create` | Pembeli |
| POST | `/checkout` | `checkout.store` | Pembeli |
| GET | `/pesanan` | `order.index` | Pembeli |
| POST | `/pesanan/{id}/bukti-bayar` | `order.bukti` | Pembeli |

---

## 9. Struktur Basis Data

Empat tabel, diterjemahkan langsung dari PDM hasil Kelompok Pekerjaan 1
(`marketplace_lspjwp.sql`) menjadi migrasi Laravel.

| Tabel | Primary Key | Relasi |
|---|---|---|
| `users` | `id_user` | — |
| `produk` | `id_product` | `penjual_id` → `users.id_user` |
| `orders` | `id_order` | `pembeli_id` → `users.id_user` |
| `order_detail` | `id_order_detail` | `order_id` → `orders.id_order`, `product_id` → `produk.id_product` |

Nilai ENUM:

- `users.role` → `pembeli`, `penjual`
- `users.status_user` → `aktif`, `tidak_aktif`
- `produk.status_product` → `tersedia`, `habis`
- `orders.status_order` → `menunggu_bayar`, `menunggu_konfirmasi`, `diproses`, `dikirim`, `selesai`, `dibatalkan`

---

## 10. Pengujian

Pengujian manual: 43 skenario yang mencakup jalur sukses dan jalur gagal setiap
proses, terdokumentasi lengkap di [`docs/pengujian.md`](docs/pengujian.md).

Pengujian otomatis:

```bash
php artisan test --filter=ProdukTest
```

Berjalan di atas SQLite in-memory sesuai `phpunit.xml`, sehingga tidak menyentuh
data pengembangan di MySQL.

---

## 11. Catatan Keamanan

- Password selalu melewati `Hash::make()` (bcrypt) dan tidak pernah disimpan dalam bentuk polos, termasuk pada seeder.
- Kolom `password` masuk daftar `$hidden` pada model `User`.
- Setiap formulir menyertakan token `@csrf`.
- Pesan gagal login sengaja dibuat umum ("Email atau password salah") agar tidak membocorkan email mana yang terdaftar.
- `penjual_id` diambil dari sesi login, bukan dari masukan formulir, sehingga produk tidak dapat dititipkan atas nama akun lain.
- Aksi edit, update, dan hapus selalu memfilter `where('penjual_id', auth()->id())`; percobaan menebak URL produk milik penjual lain menghasilkan HTTP 404.
- Pesanan hanya dapat diakses pemiliknya; `order.bukti` memfilter `where('pembeli_id', auth()->id())` sehingga pesanan pembeli lain menghasilkan HTTP 404.
- Pembuatan pesanan dibungkus `DB::transaction()` agar tidak pernah tersimpan `orders` tanpa `order_detail`.
- Seluruh kredensial basis data berada di `.env` dan tidak ada yang ditulis di dalam kode.

---

## 12. Ruang Lingkup yang Dikerjakan

Naskah soal meminta **minimal 2 proses**. Proyek ini mengerjakan **4 proses** secara penuh:

1. **Pendaftaran** — Landing Page dan Formulir Pendaftaran
2. **Login / Logout** — Halaman Login beserta pengalihan sesuai peran
3. **Manajemen Produk** — CRUD lengkap dengan unggah gambar
4. **Checkout dan Order** — Keranjang, Checkout, dan riwayat pesanan pembeli

Proses **Validasi Order** dari sisi penjual (konfirmasi, tolak, kirim, selesai)
belum dikerjakan. Seluruh nilai ENUM `status_order` sudah tersedia pada migrasi,
sehingga proses tersebut dapat ditambahkan tanpa mengubah struktur basis data.

---

## 13. Asumsi Pengerjaan

Beberapa bagian naskah soal merupakan sisa adaptasi dari studi kasus lain.
Asumsi berikut diambil secara terbuka:

1. **"Buku" dibaca sebagai "produk".** Naskah soal masih memuat istilah "daftar buku" dan "manajemen buku", sementara judul studi kasus adalah MarketPlace. Seluruh istilah diseragamkan menjadi produk.
2. **"Admin" dibaca sebagai "penjual".** Sistem hanya memiliki dua peran pada kolom `role`, yaitu `pembeli` dan `penjual`; tidak ada peran admin terpisah.
3. **Tujuan setelah login dibedakan menurut peran.** Pembeli diarahkan ke landing page, penjual ke halaman Manajemen Produk. Ini memenuhi kedua pernyataan pada naskah soal yang tampak saling bertentangan.
4. **Tabel `order_detail` ditambahkan di luar daftar tabel pada soal.** Tanpa tabel ini, satu order tidak dapat memuat lebih dari satu produk karena quantity per produk tidak punya tempat penyimpanan.
5. **Alamat pengiriman ditempatkan pada halaman Checkout,** bukan halaman Keranjang, mengikuti mock-up dan alur belanja yang lazim.
6. **Keranjang belanja disimpan di session,** bukan tabel baru, karena naskah soal tidak mencantumkan tabel keranjang. Yang disimpan hanya pasangan `id_product => quantity`; harga selalu dibaca ulang dari basis data agar keranjang lama tetap memakai harga yang berlaku.
7. **Stok produk tidak dikurangi saat pesanan dibuat.** Naskah soal hanya meminta pembuatan baris `orders` dan `order_detail`, tidak menyebut mutasi stok. Pengurangan stok umumnya baru dilakukan setelah pembayaran dikonfirmasi penjual, yang termasuk proses Validasi Order dan belum dikerjakan. Sebagai pengaman, quantity di keranjang tetap divalidasi agar tidak melebihi stok yang tersedia.
8. **Tabel `sessions`, `cache`, dan `jobs` bawaan Laravel tidak dibuat.** Driver session dan cache diarahkan ke `file` agar isi basis data persis empat tabel sesuai PDM.
9. **Kolom `remember_token` tidak ditambahkan** ke tabel `users` karena tidak ada pada PDM; fitur "ingat saya" karenanya tidak disediakan.

---

## 14. Informasi Proyek

| | |
|---|---|
| Studi kasus | MarketPlace Sederhana |
| Skema sertifikasi | Junior Web Programmer |
| Kelompok pekerjaan | 2 — Menulis Kode Sumber |
| Referensi desain | Figma — Mockup UI MarketPlace Sederhana |
| Sumber skema basis data | `marketplace_lspjwp.sql` (hasil Kelompok Pekerjaan 1) |
