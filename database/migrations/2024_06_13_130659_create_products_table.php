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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('description')->nullable();
            $table->unsignedInteger('price');

            $table->unsignedInteger('brand_id')->nullable();
            $table->foreign('brand_id')->references('id')->on('brands')->onDelete('set null');

            $table->unsignedInteger('id_catalog');
            $table->foreign('id_catalog')->references('id')->on('catalogs')->onDelete('cascade');

            $table->integer('sale')->nullable();
            $table->unsignedInteger('orders')->nullable();              // количество заказов
            $table->unsignedInteger('linked_product')->nullable();      // к какому товару привязан
            $table->unsignedInteger('visits')->nullable();              // количество посещений страницы
            $table->unsignedInteger('quantity')->nullable();            // количество товара на складе

            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
