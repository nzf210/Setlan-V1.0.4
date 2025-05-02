<?php

use App\Models\Year;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('m_kegs', function (Blueprint $table) {
            $table->id();
            $table->string('id_keg');
            $table->longText('nama');
            $table->unsignedInteger('tahun');
            $table->foreign('tahun')
                  ->references('year')
                  ->on('years')
                  ->onDelete('cascade');
            $table->unique(['id_keg', 'tahun']);
        });
    }


    public function down(): void
    {
        Schema::dropIfExists('m_kegs');
    }
};
