<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Use raw statement as Doctrine/DBAL often struggles with modifying enums
        DB::statement("ALTER TABLE `menu` CHANGE `tipe` `tipe` ENUM('satuan', 'paket', 'paket_harian') NOT NULL DEFAULT 'satuan'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Note: Reversing this might cause data truncation if 'paket_harian' data exists
        DB::statement("ALTER TABLE `menu` CHANGE `tipe` `tipe` ENUM('satuan', 'paket') NOT NULL DEFAULT 'satuan'");
    }
};
