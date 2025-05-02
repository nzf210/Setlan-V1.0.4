<?php

use App\Models\MKab;
use App\Models\MOpd;
use App\Models\MUnit;
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
        Schema::create('unit_sub_kegiatan', function (Blueprint $table) {
            $table->id();
            $table->string('id_subkeg',50);
            $table->foreignIdFor(MKab::class, 'id_kab')->constrained('m_kabs', 'id_kab')->onDelete('cascade');
            $table->foreignIdFor(MOpd::class, 'id_opd')->constrained('m_opds', 'id_opd')->onDelete('cascade');
            $table->foreignIdFor(MUnit::class, 'id_unit')->constrained('m_units', 'id_unit')->onDelete('cascade');
            $table->unsignedInteger('tahun');
            $table->foreign('tahun')
                ->references('year')
                ->on('years')
                ->onDelete('cascade');
            $table->unique(['id_subkeg', 'tahun' ,'id_kab', 'id_opd', 'id_unit']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('unit_sub_kegiatan');
    }
};
