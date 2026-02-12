<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('catering_requests', function (Blueprint $table) {
            $table->string('nama_menu')->after('subject');
            $table->integer('jumlah_porsi')->after('nama_menu');
            $table->dateTime('tanggal_kebutuhan')->after('jumlah_porsi');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('catering_requests', function (Blueprint $table) {
            $table->dropColumn(['nama_menu', 'jumlah_porsi', 'tanggal_kebutuhan']);
        });
    }
};
