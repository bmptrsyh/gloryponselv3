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
        Schema::table('beli_ponsel', function (Blueprint $table) {
            $table->string('code')->nullable();
            $table->string('status_pengiriman')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('beli_ponsel', function (Blueprint $table) {
            $table->dropColumn('code');
            $table->dropColumn('status_pengiriman');
        });
    }
};
