<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tahun', function (Blueprint $table) {
            $table->id('id_tahun');
            $table->foreignId('id_kabupaten')->constrained('kabupaten', 'id_kabupaten')->onDelete('cascade');
            $table->unsignedInteger('tahun')->default(0);
            $table->unsignedInteger('tahun_akun')->default(0);
            $table->unsignedInteger('tahun_kegiatan')->default(0);
            $table->unsignedInteger('tahun_kode_barang')->default(0);
            $table->unsignedInteger('tahun_sub_kegiatan')->default(0);
            $table->unique(['id_kabupaten', 'tahun']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tahun');
    }
};
