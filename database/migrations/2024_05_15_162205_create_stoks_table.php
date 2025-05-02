<?php

use App\Models\SubKegiatanAktifModel;
use App\Models\UnitModel;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('stoks', function (Blueprint $table) {
            $table->id('id_stok');
            $table->string('id_barang');
            $table->foreign('id_barang')->references('id_barang')->on('barang')->onDelete('restrict');
            $table->foreignIdFor(UnitModel::class, 'id_unit')->constrained('unit', 'id_unit')->onDelete('cascade');
            $table->foreignIdFor(SubKegiatanAktifModel::class, 'id_sub_kegiatan_aktif')->constrained('sub_kegiatan_aktif', 'id_sub_kegiatan_aktif')->onDelete('cascade');
            $table->enum('kondisi', ['baik', 'rusak', 'expired', 'layak'])->default('baik');
            $table->unsignedInteger('stok');
            $table->unsignedInteger('tahun');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('stoks');
    }
};
