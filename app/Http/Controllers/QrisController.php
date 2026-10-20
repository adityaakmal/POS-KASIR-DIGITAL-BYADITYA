<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class QrisController extends Controller
{
    public function update(Request $request)
    {
        $request->validate([
            'qris' => 'required|image|mimes:png,jpg,jpeg,webp|max:2048',
        ]);

        Storage::disk('public')->deleteDirectory('qris'); // hapus gambar lama
        $request->file('qris')->store('qris', 'public');

        return back()->with('success', 'Gambar QRIS berhasil diperbarui.');
    }

    public function destroy()
    {
        Storage::disk('public')->deleteDirectory('qris');

        return back()->with('success', 'Gambar QRIS dihapus.');
    }
}