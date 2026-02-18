<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Use raw SQL to match the exact collation of the menu table
        DB::statement("
            CREATE TABLE `menu_product` (
                `menu_id` varchar(12) NOT NULL,
                `product_id` varchar(12) NOT NULL,
                PRIMARY KEY (`menu_id`, `product_id`),
                KEY `menu_product_product_id_foreign` (`product_id`),
                CONSTRAINT `menu_product_menu_id_foreign` FOREIGN KEY (`menu_id`) REFERENCES `menu` (`menu_id`) ON DELETE CASCADE ON UPDATE CASCADE,
                CONSTRAINT `menu_product_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `product` (`product_id`) ON DELETE CASCADE ON UPDATE CASCADE
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci
        ");
    }

    public function down(): void
    {
        Schema::dropIfExists('menu_product');
    }
};
