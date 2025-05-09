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
        Schema::create('saldo', function (Blueprint $table) {
            $table->id('id_saldo');
            $table->string('id_unit');
            $table->string('id_barang');
            $table->string('id_sub_kegiatan')->nullable();
            $table->decimal('jumlah');
            $table->enum('type', ['saldo_awal', 'saldo_akhir'])->default('saldo_awal');
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->unsignedInteger('tahun');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('saldo');
    }
};
