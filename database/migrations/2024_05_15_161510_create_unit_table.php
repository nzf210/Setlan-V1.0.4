<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('unit', function (Blueprint $table) {
            $table->id('id_unit');
            $table->foreignId('id_opd')->constrained('opd', 'id_opd')->onDelete('cascade');
            $table->string('kode_unit');
            $table->string('nama_unit');
            $table->unique(['id_opd', 'kode_unit']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('unit');
    }
};

