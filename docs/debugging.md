# Catatan Debugging — MarketPlace Sederhana

**Unit kompetensi:** J.620100.025.02 — Melakukan debugging
**Studi kasus:** MarketPlace Sederhana — LSP Junior Web Programmer

Dokumen ini diisi selama pengerjaan, bukan disusun di akhir.

Kolom **Status** dibedakan menjadi tiga agar catatan ini jujur menggambarkan
apa yang benar-benar terjadi:

- **Diperbaiki** — galat benar-benar muncul saat pengerjaan lalu diperbaiki.
- **Dicegah** — risiko dikenali lebih dulu dari pesan/konfigurasi bawaan, ditangani sebelum sempat menimbulkan galat.
- **Diuji, tidak terbukti** — dugaan cacat yang diuji langsung namun ternyata tidak terjadi; pengaman tetap dipertahankan.

## Alat bantu yang dipakai

| Alat | Kegunaan |
|---|---|
| `APP_DEBUG=true` + halaman error Laravel | Membaca pesan pengecualian, berkas, dan nomor baris penyebab galat |
| `storage/logs/laravel.log` | Menelusuri galat yang terjadi di luar tampilan peramban |
| `php artisan route:list` | Memverifikasi nama route, metode HTTP, dan middleware yang menempel |
| `php artisan migrate:status` | Memeriksa migrasi mana yang sudah dijalankan |
| MySQL client / phpMyAdmin | Memeriksa langsung isi tabel setelah setiap aksi |
| `php artisan test` | Menjalankan pengujian otomatis proses Manajemen Produk |

---

## Daftar Temuan

| No | Gejala / Risiko | Penyebab | Solusi | Status |
|---|---|---|---|---|
| 1 | `laravel new` memasang Laravel 13, sedangkan target pengerjaan adalah Laravel 11/12 | Laravel Installer selalu mengambil rilis mayor terbaru | Proyek dibuat ulang dengan versi yang dipatok: `composer create-project laravel/laravel:^12.0 marketplace`. Versi terpasang: 12.68.0 | Diperbaiki |
| 2 | `ERROR 1045 (28000): Access denied for user 'root'@'localhost' (using password: NO)` saat menguji koneksi ke server MySQL | Layanan MySQL 8.0 pada mesin ini memakai password untuk user `root`, sementara `.env` bawaan mengosongkan `DB_PASSWORD` | Mengisi `DB_USERNAME` dan `DB_PASSWORD` pada `.env`. Tidak ada kredensial yang ditulis di dalam kode | Diperbaiki |
| 3 | Migrasi bawaan Laravel membuat tabel `users` berkolom `id`, `name`, `email_verified_at` — tidak sesuai PDM soal yang memakai `id_user`, `nama_user`, `role`, `tgl_daftar` | Skeleton Laravel sudah menyertakan `0001_01_01_000000_create_users_table.php` | Menghapus tiga migrasi bawaan (`users`, `cache`, `jobs`) dan menggantinya dengan empat migrasi hasil terjemahan PDM | Dicegah |
| 4 | Risiko `SQLSTATE[42S02] Base table or view not found: 'marketplace.sessions'` pada permintaan pertama | Konfigurasi bawaan Laravel 12 mengarahkan `SESSION_DRIVER`, `CACHE_STORE`, dan `QUEUE_CONNECTION` ke tabel basis data, padahal tabel `sessions`, `cache`, dan `jobs` tidak termasuk skema soal | Mengubah `.env` menjadi `SESSION_DRIVER=file`, `CACHE_STORE=file`, `QUEUE_CONNECTION=sync`. Basis data akhirnya hanya berisi 4 tabel skema + tabel `migrations` | Dicegah |
| 5 | Risiko `SQLSTATE[42S22] Unknown column 'id'` begitu model dipanggil | Nama tabel dan primary key pada skema soal tidak mengikuti konvensi Laravel (`produk`/`id_product`, bukan `produks`/`id`) | `protected $table` dan `protected $primaryKey` dideklarasikan eksplisit pada keempat model sejak awal, termasuk `User` yang memakai `id_user`. Diverifikasi lewat login yang berhasil | Dicegah |
| 6 | Risiko relasi Eloquent mengembalikan koleksi kosong walaupun data ada | Laravel menebak foreign key dan owner key dari nama model (`user_id` → `id`), sedangkan skema memakai `penjual_id` → `id_user` | Kunci relasi ditulis eksplisit, contoh `hasMany(Produk::class, 'penjual_id', 'id_user')`. Diverifikasi lewat nama penjual yang muncul di halaman detail produk | Dicegah |
| 7 | `Route::resource` menghasilkan parameter `{produk}`, tidak selaras dengan daftar route `/penjual/produk/{id}/edit` pada brief | Laravel menurunkan nama parameter resource dari nama resource-nya | Menambahkan `->parameters(['produk' => 'id'])`. Diperiksa ulang dengan `php artisan route:list` | Diperbaiki |
| 8 | Risiko gambar produk tampil sebagai ikon rusak (HTTP 404) | Berkas tersimpan di `storage/app/public`, yang tidak dapat diakses peramban tanpa tautan simbolik ke `public/storage` | Menjalankan `php artisan storage:link` dan memanggil gambar dengan `asset('storage/' . $produk->gambar_product)`. Diverifikasi: berkas hasil unggahan diakses dengan HTTP 200 | Dicegah |
| 9 | Dugaan: mengubah produk tanpa memilih berkas baru akan mengosongkan kolom `gambar_product` | Dugaan bahwa `$request->validated()` ikut menyertakan kunci `gambar_product` bernilai `null` saat input file dibiarkan kosong | Diuji langsung dengan mengirim bagian multipart `filename=""` persis seperti kiriman peramban, dengan pengaman sengaja dinonaktifkan. Hasil: nilai lama **tetap utuh** — Laravel 12 tidak memasukkan input file kosong ke hasil validasi. Pengaman `unset($data['gambar_product'])` tetap dipertahankan agar perilaku ini tidak bergantung pada versi framework | Diuji, tidak terbukti |
| 10 | Isian harga pada form edit tampil sebagai `149000.00` di input bertipe `number` | Cast `decimal:2` pada model mengembalikan string berdesimal | Nilai ditampilkan dengan `old('harga', (int) $produk->harga)` khusus di form edit; tipe kolom di basis data tetap `DECIMAL(12,2)` | Diperbaiki |
| 11 | Login akun berstatus `tidak_aktif` dialihkan ke landing page `/`, bukan kembali ke halaman login, sehingga pesan penolakan tidak muncul di tempat yang tepat | Helper `back()` membaca URL sebelumnya dari sesi; pemanggilan `$request->session()->invalidate()` tepat sebelumnya menghapus data tersebut sehingga `back()` jatuh ke URL default `/` | Mengganti `back()` dengan `redirect()->route('login')` pada kedua cabang kegagalan login. Diuji ulang: kedua kasus kini mengembalikan `302` ke `/login` dan pesannya tampil | Diperbaiki |
| 12 | Risiko produk milik penjual lain terbuka lewat URL `/penjual/produk/{id}/edit` yang ditebak manual | Pencarian data yang hanya memakai `findOrFail($id)` tidak memeriksa kepemilikan baris | Seluruh aksi edit, update, dan destroy melewati satu method `cariProdukMilikPenjual()` yang menambahkan `where('penjual_id', auth()->id())`. Diuji dengan akun penjual kedua: hasilnya HTTP 404 dan produk tidak bocor ke tabel | Dicegah |

---

## Verifikasi Akhir

Setelah seluruh temuan di atas ditangani, aplikasi diuji ulang dari awal sebagai
pengguna baru mengikuti daftar skenario pada [pengujian.md](pengujian.md).
Seluruh 25 skenario memberikan hasil sesuai harapan dan tidak ada galat tersisa
pada `storage/logs/laravel.log`.
