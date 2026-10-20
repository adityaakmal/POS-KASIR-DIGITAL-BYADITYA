<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'category_id',
        'nama',
        'harga',
        'deskripsi',
        'gambar',
        'aktif',
    ];

    protected $casts = [
        'harga' => 'integer',
        'aktif' => 'boolean',
    ];

    // Relasi: satu produk milik satu kategori
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    // Relasi: produk ini muncul di banyak item transaksi
    public function transactionItems()
    {
        return $this->hasMany(TransactionItem::class);
    }

    // Hanya produk yang tersedia
    public function scopeAktif($query)
    {
        return $query->where('aktif', true);
    }

    // URL gambar: $product->gambar_url
    public function getGambarUrlAttribute(): ?string
    {
        return $this->gambar ? asset('storage/' . $this->gambar) : null;
    }

    // Harga berformat: $product->harga_rupiah
    public function getHargaRupiahAttribute(): string
    {
        return 'Rp ' . number_format($this->harga, 0, ',', '.');
    }
}