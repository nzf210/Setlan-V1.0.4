<?php

use App\Models\Kabupaten;
use App\Models\Opd;
use App\Models\Unit;
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
        Schema::create('berita_acara', function (Blueprint $table) {
            $table->id('id_berita_acara');
            $table->string('id_barang');
            $table->string('no_ba');
            $table->date('tgl_ba');
            $table->string('text1')->nullable();
            $table->string('text2')->nullable();
            $table->string('text3')->nullable();
            $table->string('text4')->nullable();
            $table->jsonb('ttd1');
            $table->jsonb('ttd2');
            $table->jsonb('ttd3');
            $table->jsonb('ttd4');
            $table->enum('type_ba', ['nota_pesanan', 'bast', 'ba_keluar', 'ba_masuk'])->default('nota_pesanan');
            $table->jsonb('image');
            $table->foreignIdFor(Kabupaten::class, 'id_kabupaten')->constrained('kabupaten', 'id_kabupaten')->onDelete('cascade');
            $table->foreignIdFor(Opd::class, 'id_opd')->constrained('opd', 'id_opd')->onDelete('cascade');
            $table->foreignIdFor(Unit::class, 'id_unit')->constrained('unit', 'id_unit')->onDelete('cascade');
            $table->unsignedInteger('tahun');
            $table->timestamps();
            $table->unique(['id_barang', 'no_ba', 'id_kabupaten', 'id_opd', 'id_unit', 'tahun'],'unique_berita_acara');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('berita_acara');
    }
};
