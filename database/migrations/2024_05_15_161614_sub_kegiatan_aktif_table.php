<?php

use App\Models\KabupatenModel;
use App\Models\OpdModel;
use App\Models\SubKegiatanModel;
use App\Models\UnitModel;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        Schema::create('sub_kegiatan_aktif', function (Blueprint $table) {
            $table->id('id_sub_kegiatan_aktif');
            $table->foreignIdFor(KabupatenModel::class, 'id_kabupaten')->constrained('kabupaten', 'id_kabupaten')->onDelete('cascade');
            $table->foreignIdFor(OpdModel::class, 'id_opd')->constrained('opd', 'id_opd')->onDelete('cascade');
            $table->foreignIdFor(UnitModel::class, 'id_unit')->constrained('unit', 'id_unit')->onDelete('cascade');
            $table->foreignIdFor(SubKegiatanModel::class, 'id_sub_kegiatan')->constrained('sub_kegiatan', 'id_sub_kegiatan')->onDelete('cascade');
            $table->unsignedInteger('tahun');
            $table->unique(['id_sub_kegiatan', 'tahun' ,'id_kabupaten', 'id_opd', 'id_unit'], 'unique_unit_sub_kegiatan');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sub_kegiatan_aktif');
    }
};
