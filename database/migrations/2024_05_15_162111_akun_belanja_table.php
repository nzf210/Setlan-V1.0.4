<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('akun_belanja', function (Blueprint $table) {
            $table->id('id_akun');
            $table->string('kode_akun');
            $table->string('nama_akun');
            $table->unsignedInteger('tahun');
            $table->unique(['kode_akun', 'tahun'],'unique_akun');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('akun_belanja');
    }
};
