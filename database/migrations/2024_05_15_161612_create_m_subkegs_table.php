<?php

use App\Models\MKab;
use App\Models\MOpd;
use App\Models\MUnit;
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
        Schema::create('m_subkegs', function (Blueprint $table) {
            $table->id();
            $table->string('id_subkeg',50);
            $table->unsignedInteger('tahun');
            $table->foreign('tahun')
                ->references('year')
                ->on('years')
                ->onDelete('cascade');
            $table->string('id_keg');
            $table->foreign('id_keg')->references('id_keg')->on('m_kegs')->onDelete('cascade');
            $table->longText('nama');
            $table->unique(['id_keg',  'tahun' ,'id_subkeg']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('m_subkegs');
    }
};
