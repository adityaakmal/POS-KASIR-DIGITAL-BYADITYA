<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasColumn('transactions', 'metode')) {
            Schema::table('transactions', function (Blueprint $table) {
                $table->string('metode', 20)->default('tunai')->after('total');
            });
        }

        if (! Schema::hasColumn('transaction_items', 'catatan')) {
            Schema::table('transaction_items', function (Blueprint $table) {
                $table->string('catatan', 255)->nullable()->after('qty');
            });
        }
    }

    public function down(): void
    {
        // Dikosongkan supaya rollback tidak menghapus kolom milik migrasi lain
    }
};