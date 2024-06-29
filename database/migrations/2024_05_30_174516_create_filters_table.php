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
        Schema::create('filters', function (Blueprint $table) {
            $table->id();
            $table->string('name');

            $table->unsignedInteger('id_catalog')->nullable();
            $table->foreign('id_catalog')->references('id')->on('catalogs')->onDelete('cascade');

            $table->unsignedInteger('id_filter')->nullable();
            $table->foreign('id_filter')->references('id')->on('filters')->onDelete('cascade');

            $table->integer('lvl');
            $table->boolean('is_custom_input')->nullable();             # Пользователь вводит сам
            $table->boolean('required_to_fill_out')->nullable();        # обязательно для заполнения

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('filters');
    }
};
