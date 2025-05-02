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
            $table->unsignedInteger('tahun')->unsigned()->default(0);
            $table->unsignedInteger('tahun_akun')->unsigned()->default(0);
            $table->unsignedInteger('tahun_kode_barang')->unsigned()->default(0);
            $table->unsignedInteger('tahun_kegiatan')->unsigned()->default(0);
            $table->unsignedInteger('tahun_sub_kegiatan')->unsigned()->default(0);
            $table->unique(['id_kabupaten', 'tahun']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tahun');
    }
};
