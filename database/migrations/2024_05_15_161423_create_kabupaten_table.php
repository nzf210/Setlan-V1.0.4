<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        Schema::create('kabupaten', function (Blueprint $table) {
            $table->id('id_kabupaten');
            $table->string('kode_kabupaten')->unique();
            $table->string('nama_kabupaten');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kabupaten');
    }
};
