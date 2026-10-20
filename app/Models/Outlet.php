<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Outlet extends Model
{
    protected $fillable = ['nama', 'alamat', 'deskripsi', 'telepon', 'jam_buka', 'gambar'];

    /** Satu-satunya data outlet; dibuat otomatis jika belum ada. */
    public static function utama(): self
    {
        return static::firstOrCreate(
            ['id' => 1],
            ['nama' => 'Martabak Mas Bejo', 'alamat' => 'Jl. Kerkof, Pondok Ciptamas']
        );
    }

    public function getGambarUrlAttribute(): ?string
    {
        return $this->gambar ? asset('storage/' . $this->gambar) : null;
    }
}