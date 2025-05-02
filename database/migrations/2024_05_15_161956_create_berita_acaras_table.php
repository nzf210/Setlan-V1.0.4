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
        Schema::create('berita_acara', function (Blueprint $table) {
            $table->id();
            $table->string('id_barang');
            $table->date('no_ba');
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
            $table->unsignedInteger('tahun');
            $table->foreign('tahun')
                ->references('year')
                ->on('years')
                ->onDelete('cascade');
            $table->timestamps();
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
