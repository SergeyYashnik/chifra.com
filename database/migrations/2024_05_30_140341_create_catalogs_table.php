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
        Schema::create('catalogs', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('image')->nullable();

            $table->unsignedInteger('id_catalog')->nullable();
            $table->foreign('id_catalog')->references('id')->on('catalogs')->onDelete('cascade');

            $table->unsignedInteger('catalogs_lvl_1')->nullable();
            $table->foreign('catalogs_lvl_1')->references('id')->on('catalogs')->onDelete('cascade');

            $table->unsignedInteger('catalogs_lvl_2')->nullable();
            $table->foreign('catalogs_lvl_2')->references('id')->on('catalogs')->onDelete('cascade');

            $table->unsignedInteger('catalogs_lvl_3')->nullable();
            $table->foreign('catalogs_lvl_3')->references('id')->on('catalogs')->onDelete('cascade');


            $table->integer('lvl');

            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('catalogs');
    }
};
