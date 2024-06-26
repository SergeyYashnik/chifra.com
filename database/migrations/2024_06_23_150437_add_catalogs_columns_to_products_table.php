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
        Schema::table('products', function (Blueprint $table) {
            $table->unsignedBigInteger('catalogs_lvl_1')->nullable()->after('id_catalog');
            $table->unsignedBigInteger('catalogs_lvl_2')->nullable()->after('catalogs_lvl_1');
            $table->unsignedBigInteger('catalogs_lvl_3')->nullable()->after('catalogs_lvl_2');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn('catalogs_lvl_1');
            $table->dropColumn('catalogs_lvl_2');
            $table->dropColumn('catalogs_lvl_3');
        });
    }
};
