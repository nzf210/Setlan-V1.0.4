<?php

use App\Models\Year;
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
        Schema::create('kode_barang', function (Blueprint $table) {
            $table->id()->primary();
            $table->string('id_kd_barang');
            $table->string('id_kab');
            $table->unsignedInteger('tahun');
            $table->foreign('tahun')
                  ->references('year')
                  ->on('years')
                  ->onDelete('cascade');
            $table->string('nama');
            $table->unique(['id_kd_barang', 'id_kab', 'tahun']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kode_barang');
    }

};
