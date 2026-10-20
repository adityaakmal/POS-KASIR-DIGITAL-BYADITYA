<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (! Schema::hasColumn('products', 'gambar')) {
            Schema::table('products', function (Blueprint $table) {
                $table->string('gambar')->nullable()->after('harga');
            });
        }
    }

    public function down(): void
    {
        // Sengaja kosong: kolom gambar mungkin sudah ada sebelum migration ini.
    }
};