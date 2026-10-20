<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::with('category')->latest()->paginate(15);
        $categories = Category::orderBy('nama')->get();
        $product = new Product();

        return view('products.index', compact('products', 'categories', 'product'));
    }

    public function create()
    {
        return view('products.form', [
            'product'    => new Product(),
            'categories' => Category::orderBy('nama')->get(),
        ]);
    }

    public function store(Request $request)
    {
        $data = $this->validated($request);
        unset($data['hapus_gambar']);

        if ($request->hasFile('gambar')) {
            $data['gambar'] = $request->file('gambar')->store('products', 'public');
        }

        $data['aktif'] = $request->has('aktif');
        Product::create($data);

        return redirect()->route('products.index')
            ->with('success', 'Produk berhasil ditambahkan.');
    }

    public function edit(Product $product)
    {
        return view('products.form', [
            'product'    => $product,
            'categories' => Category::orderBy('nama')->get(),
        ]);
    }

    public function update(Request $request, Product $product)
    {
        $data = $this->validated($request);
        unset($data['hapus_gambar']);

        $pathLama = $product->gambar;

        if ($request->hasFile('gambar')) {
            $data['gambar'] = $request->file('gambar')->store('products', 'public');
            $this->hapusFile($pathLama);
        } elseif ($request->boolean('hapus_gambar')) {
            $data['gambar'] = null;
            $this->hapusFile($pathLama);
        }

        $data['aktif'] = $request->has('aktif');
        $product->update($data);

        return redirect()->route('products.index')
            ->with('success', 'Produk berhasil diperbarui.');
    }

    // Nyalakan / matikan ketersediaan produk
    public function toggle(Product $product)
    {
        $product->update(['aktif' => ! $product->aktif]);

        return back()->with(
            'success',
            $product->aktif ? 'Produk diaktifkan.' : 'Produk dinonaktifkan.'
        );
    }

    public function destroy(Product $product)
    {
        // Produk yang pernah terjual jangan dihapus, cukup dinonaktifkan
        if ($product->transactionItems()->exists()) {
            return back()->with(
                'error',
                'Produk sudah pernah terjual dan tidak bisa dihapus. Nonaktifkan saja.'
            );
        }

        $this->hapusFile($product->gambar);
        $product->delete();

        return back()->with('success', 'Produk dihapus.');
    }

    private function validated(Request $request): array
    {
        return $request->validate([
            'category_id'  => 'required|exists:categories,id',
            'nama'         => 'required|string|max:100',
            'harga'        => 'required|integer|min:0',
            'deskripsi'    => 'nullable|string',
            'gambar'       => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',
            'hapus_gambar' => 'nullable|boolean',
        ], [
            'category_id.required' => 'Kategori wajib dipilih.',
            'category_id.exists'   => 'Kategori tidak valid.',
            'nama.required'        => 'Nama produk wajib diisi.',
            'nama.max'             => 'Nama produk maksimal 100 karakter.',
            'harga.required'       => 'Harga wajib diisi.',
            'harga.integer'        => 'Harga harus berupa angka bulat.',
            'harga.min'            => 'Harga tidak boleh negatif.',
            'gambar.image'         => 'File harus berupa gambar.',
            'gambar.mimes'         => 'Format gambar harus jpg, jpeg, png, atau webp.',
            'gambar.max'           => 'Ukuran gambar maksimal 5 MB.',
            'gambar.uploaded'      => 'Upload gagal. Ukuran file mungkin melebihi batas server (upload_max_filesize).',
        ]);
    }

    private function hapusFile(?string $path): void
    {
        if ($path && Storage::disk('public')->exists($path)) {
            Storage::disk('public')->delete($path);
        }
    }
}