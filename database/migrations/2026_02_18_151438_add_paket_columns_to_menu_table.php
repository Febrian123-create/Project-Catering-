<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('menu', function (Blueprint $table) {
            $table->string('tipe', 10)->default('satuan')->after('menu_id');
            $table->string('nama_paket', 80)->nullable()->after('tipe');
            $table->integer('harga_paket')->nullable()->after('nama_paket');
            $table->text('deskripsi_paket')->nullable()->after('harga_paket');
            $table->string('foto_paket', 255)->nullable()->after('deskripsi_paket');
        });

        // Make product_id nullable for paket menus
        Schema::table('menu', function (Blueprint $table) {
            $table->string('product_id', 12)->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('menu', function (Blueprint $table) {
            $table->dropColumn(['tipe', 'nama_paket', 'harga_paket', 'deskripsi_paket', 'foto_paket']);
            $table->string('product_id', 12)->nullable(false)->change();
        });
    }
};
