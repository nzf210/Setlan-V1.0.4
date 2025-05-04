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
        Schema::create('sub_kegiatan', function (Blueprint $table) {
            $table->id('id_sub_kegiatan');
            $table->string('kode_sub_kegiatan');
            $table->text('nama_sub_kegiatan');
            $table->unsignedInteger('tahun');
            $table->foreignId('id_kegiatan')
                    ->constrained('kegiatan', 'id_kegiatan')
                    ->onDelete('cascade');
            $table->unique(
                ['kode_sub_kegiatan', 'tahun', 'id_kegiatan'],
                'unique_sub_kegiatan'
            );
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sub_kegiatan');
    }
};
