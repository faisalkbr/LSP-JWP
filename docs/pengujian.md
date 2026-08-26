# Dokumen Pengujian — MarketPlace Sederhana

**Unit kompetensi:** J.620100.025.02 — Melakukan debugging
**Studi kasus:** MarketPlace Sederhana — LSP Junior Web Programmer
**Metode:** pengujian manual melalui peramban pada `http://127.0.0.1:8000`, data awal dari `DemoSeeder`.

Akun yang dipakai selama pengujian:

| Peran | Email | Password |
|---|---|---|
| Penjual | `penjual@demo.test` | `password` |
| Pembeli | `pembeli@demo.test` | `password` |

---

## A. Proses Pendaftaran

| No | Skenario | Input | Hasil Diharapkan | Hasil Aktual | Ket |
|---|---|---|---|---|---|
| 1 | Pendaftaran dengan data lengkap dan benar | nama `Siti Aminah`, email `siti@demo.test`, password `rahasia123`, konfirmasi sama, role `pembeli` | Data tersimpan, `tgl_daftar` terisi otomatis, `status_user` = `aktif`, password tersimpan sebagai hash bcrypt, dialihkan ke halaman login dengan pesan sukses | Sesuai. Redirect ke `/login`, muncul "Pendaftaran berhasil. Silakan masuk menggunakan akun Anda.", kolom `password` berisi hash berawalan `$2y$` | OK |
| 2 | Daftar dengan email yang sudah terpakai | email `siti@demo.test` (duplikat) | Muncul pesan "Email sudah terdaftar" di bawah field email, nilai isian lain tetap terisi | Sesuai. Pesan tampil, `old()` mengembalikan nama yang sudah diketik | OK |
| 3 | Konfirmasi password tidak cocok | password `rahasia123`, konfirmasi `salah999` | Muncul pesan "Konfirmasi password tidak cocok", data tidak tersimpan | Sesuai | OK |
| 4 | Password kurang dari 8 karakter | password `123` | Muncul pesan "Password minimal 8 karakter" | Sesuai | OK |

## B. Proses Login dan Logout

| No | Skenario | Input | Hasil Diharapkan | Hasil Aktual | Ket |
|---|---|---|---|---|---|
| 5 | Login penjual dengan kredensial benar | `penjual@demo.test` / `password` | Berhasil masuk dan dialihkan ke `/penjual/produk` | Sesuai. HTTP 302 menuju `/penjual/produk` | OK |
| 6 | Login pembeli dengan kredensial benar | `pembeli@demo.test` / `password` | Berhasil masuk dan dialihkan ke landing page `/` | Sesuai. HTTP 302 menuju `/` | OK |
| 7 | Login dengan password salah | `pembeli@demo.test` / `salahbanget` | Kembali ke halaman login dengan pesan umum "Email atau password salah" tanpa menyebut field mana yang keliru | Sesuai | OK |
| 8 | Login dengan email tidak terdaftar | `tidakada@demo.test` / `password` | Pesan yang sama persis dengan No. 7 sehingga tidak membocorkan email mana yang terdaftar | Sesuai | OK |
| 9 | Navbar setelah login | — | Navbar menampilkan nama user dan badge perannya, tombol Masuk/Daftar berganti tombol Keluar | Sesuai. Tampil "Siti Aminah" dengan badge `pembeli` | OK |
| 10 | Logout | Klik tombol Keluar | Sesi berakhir, kembali ke landing page dengan pesan "Anda telah keluar", navbar kembali menampilkan Masuk/Daftar | Sesuai | OK |

## C. Proses Manajemen Produk (Penjual)

| No | Skenario | Input | Hasil Diharapkan | Hasil Aktual | Ket |
|---|---|---|---|---|---|
| 11 | Menampilkan daftar produk milik sendiri | Login sebagai `penjual@demo.test` | Tabel menampilkan 5 produk hasil seeder milik akun tersebut | Sesuai | OK |
| 12 | Tambah produk dengan data valid | nama `Sarung Tangan Kulit`, harga `95000`, stok `15`, status `tersedia` | Produk tersimpan dengan `penjual_id` akun yang login, redirect ke daftar produk dengan pesan "Produk berhasil ditambahkan" | Sesuai | OK |
| 13 | Tambah produk dengan harga negatif | harga `-5000` | Validasi menolak, muncul pesan "Harga tidak boleh kurang dari 0", data tidak tersimpan | Sesuai | OK |
| 14 | Tambah produk tanpa nama produk | nama dikosongkan | Validasi menolak, muncul pesan "Nama produk wajib diisi" | Sesuai | OK |
| 15 | Ubah produk | nama menjadi `Sarung Tangan Kulit Premium`, harga `120000`, stok `9`, status `habis` | Data diperbarui di basis data, badge status berubah menjadi merah (Habis), muncul pesan "Produk berhasil diperbarui" | Sesuai. Nilai di tabel `produk` menjadi `120000.00 / 9 / habis` | OK |
| 16 | Unggah gambar produk | berkas PNG < 2 MB | Berkas tersimpan di `storage/app/public/produk`, gambar tampil pada tabel dan katalog | Sesuai. Berkas dapat diakses lewat `/storage/produk/...` dengan HTTP 200 | OK |
| 17 | Ubah produk tanpa mengganti gambar | field gambar dikosongkan | Gambar lama dipertahankan, tidak menjadi kosong | Sesuai | OK |
| 18 | Hapus produk | Klik Hapus lalu setujui dialog konfirmasi | Baris hilang dari tabel dan dari basis data, berkas gambarnya ikut terhapus | Sesuai. `SELECT COUNT(*)` mengembalikan 0 | OK |

## D. Hak Akses dan Keamanan

| No | Skenario | Input | Hasil Diharapkan | Hasil Aktual | Ket |
|---|---|---|---|---|---|
| 19 | Tamu membuka `/penjual/produk` | Belum login | Dialihkan ke halaman login | Sesuai. HTTP 302 ke `/login` | OK |
| 20 | Pembeli membuka `/penjual/produk` | Login sebagai pembeli | Ditolak middleware `role:penjual`, dialihkan ke landing page dengan pesan "Halaman tersebut hanya dapat diakses oleh penjual" | Sesuai | OK |
| 21 | Penjual membuka halaman edit produk milik penjual lain | Akses `/penjual/produk/{id}/edit` dengan id milik akun lain | Halaman 404, bukan formulir edit | Sesuai | OK |
| 22 | Produk penjual lain bocor ke tabel | Login sebagai penjual kedua | Tabel hanya berisi produk milik akun yang login | Sesuai. Produk penjual pertama tidak muncul | OK |

## E. Halaman Publik

| No | Skenario | Input | Hasil Diharapkan | Hasil Aktual | Ket |
|---|---|---|---|---|---|
| 23 | Landing page menampilkan katalog dari basis data | Buka `/` | Kartu produk berisi data tabel `produk`, harga terformat Rupiah, badge status sesuai nilai kolom | Sesuai. "Kemeja Flanel Pria" tampil dengan harga "Rp 149.000" | OK |
| 24 | Produk tanpa gambar | Produk hasil seeder (`gambar_product` = NULL) | Tampil kotak placeholder abu-abu bertuliskan "Tanpa Gambar", tinggi kartu tetap rapi | Sesuai | OK |
| 25 | Halaman detail produk | Buka `/produk/1` | Menampilkan gambar/placeholder, nama, kategori, penjual, harga, stok, status, dan deskripsi | Sesuai | OK |

---

## F. Pengujian Otomatis

Satu feature test disertakan pada `tests/Feature/ProdukTest.php` untuk skenario
"penjual dapat menambah produk".

```
php artisan test --filter=ProdukTest
```

Hasil eksekusi terakhir:

```
PASS  Tests\Feature\ProdukTest
✓ penjual dapat menambah produk

Tests:    1 passed (5 assertions)
```

Pengujian berjalan di atas basis data sementara (SQLite in-memory) sesuai
konfigurasi `phpunit.xml`, sehingga data pengembangan pada MySQL tidak terpengaruh.

---

## Kesimpulan

Seluruh 25 skenario pada tiga proses yang dikerjakan (Pendaftaran, Login, dan
Manajemen Produk) memberikan hasil sesuai dengan yang diharapkan, mencakup jalur
sukses maupun jalur gagal. Tidak ada cacat terbuka yang tersisa pada saat dokumen
ini ditutup.
