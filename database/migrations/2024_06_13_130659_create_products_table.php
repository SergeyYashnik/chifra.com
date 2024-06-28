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
            $table->unsignedInteger('brand');
            $table->integer('id_catalog');
            $table->integer('sale')->nullable();
            $table->unsignedInteger('orders')->nullable(); // количество заказов
            $table->unsignedInteger('linked_product')->nullable(); // к какому товару привязан
            $table->unsignedInteger('visits')->nullable(); // количество посещений страницы
            $table->unsignedInteger('quantity')->nullable(); // количество товара на складе

            $table->timestamps();
            $table->softDeletes();
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
