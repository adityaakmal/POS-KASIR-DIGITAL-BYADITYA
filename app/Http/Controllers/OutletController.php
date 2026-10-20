<?php
namespace App\Http\Controllers;

use App\Models\Outlet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class OutletController extends Controller
{
    public function index()
    {
        return view('outlet.index', ['outlet' => Outlet::utama()]);
    }

    public function edit()
    {
        return view('outlet.edit', ['outlet' => Outlet::utama()]);
    }

    public function update(Request $request)
    {
        $data = $request->validate([
            'nama'         => 'required|string|max:255',
            'alamat'       => 'required|string|max:255',
            'deskripsi'    => 'nullable|string|max:2000',
            'telepon'      => 'nullable|string|max:30',
            'jam_buka'     => 'nullable|string|max:255',
            'gambar'       => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',
            'hapus_gambar' => 'nullable|boolean',
        ], [
            'nama.required'   => 'Nama outlet wajib diisi.',
            'alamat.required' => 'Alamat wajib diisi.',
            'gambar.image'    => 'File harus berupa gambar.',
            'gambar.mimes'    => 'Format gambar harus jpg, jpeg, png, atau webp.',
            'gambar.max'      => 'Ukuran gambar maksimal 5 MB.',
            'gambar.uploaded' => 'Upload gagal. Ukuran file mungkin melebihi batas server (upload_max_filesize).',
        ]);
        unset($data['hapus_gambar']);

        $outlet   = Outlet::utama();
        $pathLama = $outlet->gambar;

        if ($request->hasFile('gambar')) {
            $data['gambar'] = $request->file('gambar')->store('outlet', 'public');
            $this->hapusFile($pathLama);
        } elseif ($request->boolean('hapus_gambar')) {
            $data['gambar'] = null;
            $this->hapusFile($pathLama);
        }

        $outlet->update($data);

        return redirect()->route('outlet.index')->with('ok', 'Data outlet berhasil diperbarui.');
    }

    private function hapusFile(?string $path): void
    {
        if ($path && Storage::disk('public')->exists($path)) {
            Storage::disk('public')->delete($path);
        }
    }
}   