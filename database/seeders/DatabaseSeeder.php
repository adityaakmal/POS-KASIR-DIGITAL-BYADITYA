<?php
namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name' => 'Pemilik', 'email' => 'admin@masbejo.com',
            'password' => Hash::make('password'), 'role' => 'admin',
        ]);
        User::create([
            'name' => 'Kasir 1', 'email' => 'kasir@masbejo.com',
            'password' => Hash::make('password'), 'role' => 'kasir',
        ]);

        $manis = Category::create(['nama' => 'Martabak Manis']);
        $telur = Category::create(['nama' => 'Martabak Telur']);
        $minum = Category::create(['nama' => 'Minuman']);

        $data = [
            [$manis->id, 'Coklat Keju', 35000, 'Coklat meses dan keju parut melimpah.'],
            [$manis->id, 'Coklat Kacang', 30000, 'Coklat dan kacang tanah sangrai.'],
            [$manis->id, 'Green Tea Keju', 38000, 'Perpaduan green tea dan keju.'],
            [$telur->id, 'Telur Ayam Spesial', 32000, 'Isian daging, telur ayam, dan daun bawang.'],
            [$telur->id, 'Telur Bebek', 40000, 'Isian telur bebek gurih.'],
            [$minum->id, 'Es Teh Manis', 5000, 'Teh manis dingin segar.'],
            [$minum->id, 'Air Mineral', 4000, 'Air mineral botol.'],
        ];

        foreach ($data as [$cat, $nama, $harga, $desk]) {
            Product::create([
                'category_id' => $cat, 'nama' => $nama,
                'harga' => $harga, 'deskripsi' => $desk,
            ]);
        }
    }
}