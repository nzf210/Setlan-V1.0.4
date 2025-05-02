<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('m_opds', function (Blueprint $table) {
            $table->id('id_opd');
            $table->string('kode_opd');
            // $table->string('id_kab');
            // $table->foreign('id_kab')->references('id_kab')->on('m_kabs')->onDelete('cascade');
            $table->foreignId('kode_kab')->constrained('m_kabs', 'kode_kab')->onDelete('cascade');
            $table->string('nama_opd');
            $table->unique(['kode_opd', 'id_kab']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('m_opds');
    }
};
