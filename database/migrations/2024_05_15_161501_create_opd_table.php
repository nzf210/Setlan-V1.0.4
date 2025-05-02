<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('opd', function (Blueprint $table) {
            $table->id('id_opd');
            $table->foreignId('id_kabupaten')->constrained('kabupaten', 'id_kabupaten')->onDelete('cascade');
            $table->string('nama_opd');
            $table->string('kode_opd');
            $table->unique(['id_kabupaten', 'kode_opd']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('opd');
    }
};
