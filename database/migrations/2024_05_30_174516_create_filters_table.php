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
            $table->integer('id_catalog')->nullable();
            $table->integer('id_filter')->nullable();
            $table->integer('lvl');
            $table->boolean('is_custom_input')->nullable();             # Пользователь вводит сам
            $table->boolean('required_to_fill_out')->nullable();        # обязательно для заполнения
            # $table->boolean('user_input');
            $table->timestamps();

            $table->softDeletes();
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
