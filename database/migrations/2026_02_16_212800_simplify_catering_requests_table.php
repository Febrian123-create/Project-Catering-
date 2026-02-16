<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('catering_requests', function (Blueprint $table) {
            // Add new columns
            $table->text('deskripsi')->nullable()->after('nama_menu');
            $table->string('asal_daerah')->nullable()->after('deskripsi');

            // Drop old columns
            if (Schema::hasColumn('catering_requests', 'jumlah_porsi')) {
                $table->dropColumn('jumlah_porsi');
            }
            if (Schema::hasColumn('catering_requests', 'tanggal_kebutuhan')) {
                $table->dropColumn('tanggal_kebutuhan');
            }
        });
    }

    public function down(): void
    {
        Schema::table('catering_requests', function (Blueprint $table) {
            $table->integer('jumlah_porsi')->nullable()->after('nama_menu');
            $table->dateTime('tanggal_kebutuhan')->nullable()->after('jumlah_porsi');
            $table->dropColumn(['deskripsi', 'spesifikasi']);
        });
    }
};
