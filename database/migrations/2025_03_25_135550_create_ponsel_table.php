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
        Schema::create('ponsel', function (Blueprint $table) {
            $table->id('id_ponsel');
            $table->string('merk');
            $table->string('model');
            $table->integer('harga_jual');
            $table->integer('harga_beli');
            $table->integer('stok');
            $table->enum('status', ['baru', 'bekas']);
            $table->string('processor');
            $table->string('dimension');
            $table->integer('ram');
            $table->integer('storage');
            $table->string('gambar');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ponsel');
    }
};
