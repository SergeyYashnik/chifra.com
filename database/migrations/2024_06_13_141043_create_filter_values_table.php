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
        Schema::create('filter_values', function (Blueprint $table) {
            $table->id();

            $table->unsignedInteger('id_filter');
            $table->foreign('id_filter')->references('id')->on('filters')->onDelete('cascade');

            $table->string('value');
            $table->boolean('user_input');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('filter_values');
    }
};
