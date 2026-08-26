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

## G. Proses Checkout dan Order (Opsional A)

| No | Skenario | Input | Hasil Diharapkan | Hasil Aktual | Ket |
|---|---|---|---|---|---|
| 26 | Tamu membuka halaman keranjang | Belum login | Dialihkan ke halaman login | Sesuai. HTTP 302 ke `/login` | OK |
| 27 | Keranjang masih kosong | Login sebagai pembeli, buka `/keranjang` | Muncul pesan "Keranjang Anda masih kosong" beserta tombol menuju katalog | Sesuai | OK |
| 28 | Checkout saat keranjang kosong | Buka `/checkout` langsung | Dialihkan kembali ke keranjang dengan pesan penjelas, tidak menampilkan formulir | Sesuai. HTTP 302 ke `/keranjang` | OK |
| 29 | Menambah dua produk ke keranjang | Klik Tambah ke Keranjang pada produk 1 dan 2 | Kedua produk muncul di keranjang, total = Rp 469.000 (149.000 + 320.000) | Sesuai | OK |
| 30 | Menambah produk berstatus habis | Produk "Tas Ransel Laptop" (stok 0) | Ditolak dengan pesan "Produk sedang tidak tersedia", keranjang tidak berubah | Sesuai | OK |
| 31 | Mengubah quantity | Kemeja Flanel dari 1 menjadi 3 | Subtotal dan total ikut menyesuaikan menjadi Rp 767.000 | Sesuai | OK |
| 32 | Quantity melebihi stok | Sepatu Sneakers (stok 8) diisi 99 | Validasi menolak dengan pesan "Jumlah pesanan melebihi stok yang tersedia" | Sesuai | OK |
| 33 | Menghapus item keranjang | Klik Hapus lalu setujui konfirmasi | Item hilang dari keranjang dan dari perhitungan total | Sesuai | OK |
| 34 | Checkout dengan alamat terlalu singkat | alamat = "Jl A" | Validasi menolak, tidak ada baris `orders` yang tersimpan | Sesuai. `SELECT COUNT(*) FROM orders` tetap 0 | OK |
| 35 | Checkout dengan data valid | alamat lengkap | Tersimpan 1 baris `orders` berstatus `menunggu_bayar` dan N baris `order_detail` dalam satu transaksi | Sesuai. `orders` = 1 baris Rp 447.000, `order_detail` = 1 baris qty 3 @149.000 | OK |
| 36 | Keranjang dikosongkan setelah checkout | — | Session keranjang dibersihkan sehingga pesanan tidak terkirim dua kali | Sesuai | OK |
| 37 | Riwayat pesanan pembeli | Buka `/pesanan` | Pesanan tampil lengkap dengan rincian produk, alamat, badge status, dan formulir unggah bukti bayar | Sesuai | OK |
| 38 | Unggah bukti bayar berupa berkas non-gambar | berkas `.txt` | Validasi menolak dengan pesan "Bukti pembayaran harus berupa berkas gambar", status pesanan tidak berubah | Sesuai. Status tetap `menunggu_bayar`, `bukti_bayar` tetap NULL | OK |
| 39 | Unggah bukti bayar tanpa memilih berkas | field dikosongkan | Validasi menolak dengan pesan "Berkas bukti pembayaran wajib diunggah" | Sesuai | OK |
| 40 | Unggah bukti bayar valid | berkas PNG < 2 MB | Berkas tersimpan di `storage/app/public/bukti`, `status_order` berubah menjadi `menunggu_konfirmasi` | Sesuai. Berkas diakses lewat `/storage/bukti/...` dengan HTTP 200, badge berubah menjadi "Menunggu Konfirmasi" | OK |
| 41 | Unggah ulang setelah status berubah | Kirim bukti kedua kali | Ditolak dengan pesan bahwa unggahan hanya berlaku untuk pesanan yang menunggu pembayaran | Sesuai | OK |
| 42 | Penjual membuka area pembeli | Login sebagai penjual, buka `/keranjang`, `/checkout`, `/pesanan` | Ketiganya ditolak middleware `role:pembeli` dan dialihkan ke landing page | Sesuai. Ketiganya HTTP 302 ke `/` | OK |
| 43 | Pembeli lain mengunggah bukti ke pesanan orang | POST ke `/pesanan/1/bukti-bayar` dengan akun pembeli berbeda | Menghasilkan 404, dan pesanan orang lain tidak muncul di riwayatnya | Sesuai | OK |

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

Seluruh 43 skenario pada empat proses yang dikerjakan (Pendaftaran, Login,
Manajemen Produk, serta Checkout dan Order) memberikan hasil sesuai dengan yang
diharapkan, mencakup jalur sukses maupun jalur gagal. Tidak ada cacat terbuka yang tersisa pada saat dokumen
ini ditutup.
