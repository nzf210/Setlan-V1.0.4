<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('m_units', function (Blueprint $table) {
            $table->id();
            $table->string('id_unit');
            $table->string('id_opd');
            $table->foreign('id_opd')->references('id_opd')->on('m_opds')->onDelete('cascade');
            $table->string('nama_unit');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('m_units');
    }
};

