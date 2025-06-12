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
        Schema::create('tukar_tambah', function (Blueprint $table) {
            $table->id('id_tukar_tambah');
            $table->unsignedBigInteger('id_customer');
            $table->unsignedBigInteger('produk_tujuan_id'); // ID produk yang ingin ditukar
            $table->string('merk');
            $table->string('model');
            $table->string('warna');
            $table->integer('ram');
            $table->integer('storage');
            $table->string('processor');
            $table->string('kondisi');
            $table->text('deskripsi');
            $table->integer('harga_estimasi');
            $table->string('gambar');
            $table->enum('status', ['menunggu', 'di setujui', 'di tolak'])->default('menunggu');
            $table->timestamps();
            $table->foreign('id_customer')->references('id_customer')->on('customer');
            $table->foreign('produk_tujuan_id')->references('id_ponsel')->on('ponsel');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tukar_tambah');
    }
};
