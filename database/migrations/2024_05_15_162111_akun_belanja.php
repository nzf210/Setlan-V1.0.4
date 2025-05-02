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
        Schema::create('akun_belanja', function (Blueprint $table) {
            $table->id();
            $table->string('id_akun');
            $table->string('nama');
            $table->unsignedInteger('tahun');
            $table->foreign('tahun')
                  ->references('year')
                  ->on('years')
                  ->onDelete('cascade');
            $table->unique(['id_akun', 'tahun']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('akun_belanja');
    }
};
