<?php

/**
 * ProdukController
 *
 * Menangani proses Manajemen Produk untuk role penjual:
 * menampilkan, menambah, mengubah, dan menghapus produk pada katalog.
 *
 * Unit kompetensi : J.620100.017.02 - Mengimplementasikan pemrograman terstruktur
 * Studi kasus     : MarketPlace Sederhana - LSP Junior Web Programmer
 */

namespace App\Http\Controllers\Penjual;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProdukRequest;
use App\Models\Produk;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ProdukController extends Controller
{
    /** Folder penyimpanan gambar produk pada disk `public`. */
    private const FOLDER_GAMBAR = 'produk';

    /**
     * Daftar produk milik penjual yang sedang login.
     */
    public function index(): View
    {
        // Filter berdasarkan penjual yang login agar produk milik penjual lain tidak ikut tampil
        $produk = Produk::where('penjual_id', auth()->id())
            ->latest('id_product')
            ->get();

        return view('penjual.produk.index', compact('produk'));
    }

    /**
     * Formulir tambah produk.
     */
    public function create(): View
    {
        return view('penjual.produk.create');
    }

    /**
     * Menyimpan produk baru milik penjual yang sedang login.
     */
    public function store(ProdukRequest $request): RedirectResponse
    {
        $data = $request->validated();

        // penjual_id diambil dari sesi login, bukan dari masukan form,
        // supaya penjual tidak dapat menitipkan produk atas nama akun lain
        $data['penjual_id'] = auth()->id();
        $data['gambar_product'] = $this->simpanGambar($request);

        Produk::create($data);

        return redirect()->route('penjual.produk.index')
            ->with('success', 'Produk berhasil ditambahkan.');
    }

    /**
     * Formulir ubah produk.
     */
    public function edit(int $id): View
    {
        $produk = $this->cariProdukMilikPenjual($id);

        return view('penjual.produk.edit', compact('produk'));
    }

    /**
     * Memperbarui data produk.
     */
    public function update(ProdukRequest $request, int $id): RedirectResponse
    {
        $produk = $this->cariProdukMilikPenjual($id);
        $data = $request->validated();

        $gambarBaru = $this->simpanGambar($request);
        if ($gambarBaru !== null) {
            $this->hapusGambar($produk->gambar_product);
            $data['gambar_product'] = $gambarBaru;
        } else {
            // Tanpa unggahan baru, gambar lama dipertahankan
            unset($data['gambar_product']);
        }

        $produk->update($data);

        return redirect()->route('penjual.produk.index')
            ->with('success', 'Produk berhasil diperbarui.');
    }

    /**
     * Menghapus produk beserta berkas gambarnya.
     */
    public function destroy(int $id): RedirectResponse
    {
        $produk = $this->cariProdukMilikPenjual($id);

        $this->hapusGambar($produk->gambar_product);
        $produk->delete();

        return redirect()->route('penjual.produk.index')
            ->with('success', 'Produk berhasil dihapus.');
    }

    /**
     * Mengambil satu produk dengan jaminan kepemilikan.
     * Produk milik penjual lain menghasilkan 404, bukan halaman edit.
     */
    private function cariProdukMilikPenjual(int $id): Produk
    {
        return Produk::where('id_product', $id)
            ->where('penjual_id', auth()->id())
            ->firstOrFail();
    }

    /**
     * Menyimpan berkas gambar bila ada unggahan, mengembalikan path relatifnya.
     */
    private function simpanGambar(ProdukRequest $request): ?string
    {
        if (! $request->hasFile('gambar_product')) {
            return null;
        }

        return $request->file('gambar_product')->store(self::FOLDER_GAMBAR, 'public');
    }

    /**
     * Menghapus berkas gambar lama agar tidak menumpuk di storage.
     */
    private function hapusGambar(?string $path): void
    {
        if ($path !== null && Storage::disk('public')->exists($path)) {
            Storage::disk('public')->delete($path);
        }
    }
}
