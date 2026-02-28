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
        Schema::table('cart', function (Blueprint $table) {
            $table->string('bundle_id', 36)->nullable()->after('qty');
            $table->string('bundle_name', 100)->nullable()->after('bundle_id');
            $table->integer('bundle_price')->nullable()->after('bundle_name');
        });

        Schema::table('order_detail', function (Blueprint $table) {
            $table->string('bundle_id', 36)->nullable()->after('qty');
            $table->string('bundle_name', 100)->nullable()->after('bundle_id');
            $table->integer('bundle_price')->nullable()->after('bundle_name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('cart', function (Blueprint $table) {
            $table->dropColumn(['bundle_id', 'bundle_name', 'bundle_price']);
        });

        Schema::table('order_detail', function (Blueprint $table) {
            $table->dropColumn(['bundle_id', 'bundle_name', 'bundle_price']);
        });
    }
};
