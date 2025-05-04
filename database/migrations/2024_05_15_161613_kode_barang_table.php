<?php

use App\Models\Year;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kode_barang', function (Blueprint $table) {
            $table->id('id_kode_barang');
            $table->string('kode_barang');
            $table->foreignId('id_kabupaten')->constrained('kabupaten', 'id_kabupaten')->onDelete('cascade');
            $table->unsignedInteger('tahun');
            $table->text('nama_kode_barang');
            $table->unique(['kode_barang', 'id_kabupaten', 'tahun'], 'unique_kode_barang');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kode_barang');
    }

};
