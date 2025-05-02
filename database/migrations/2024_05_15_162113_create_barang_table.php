<?php

use App\Models\AkunAktif;
use App\Models\Kabupaten;
use App\Models\KodeBarang;
use App\Models\MKab;
use App\Models\MOpd;
use App\Models\MUnit;
use App\Models\Opd;
use App\Models\Satuan;
use App\Models\Unit;
use App\Models\User;
use App\Models\Year;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('barang', function (Blueprint $table) {
            $table->string('id_barang', 6)->primary();
            $table->string('nama_barang');
            $table->string('merek');
            $table->string('type')->nullable();
            $table->decimal('harga', 10, 2)->default(0);
            $table->foreignIdFor(Kabupaten::class, 'id_kabuten')->constrained('kabupaten', 'id_kabupaten')->onDelete('cascade');
            $table->foreignIdFor(Unit::class, 'id_unit')->constrained('unit', 'id_unit')->onDelete('cascade');
            $table->foreignIdFor(Opd::class, 'id_opd')->constrained('opd', 'id_opd')->onDelete('cascade');
            $table->foreignIdFor(KodeBarang::class, 'id_kode_barang')->constrained('kode_barang', 'id_kode_barang')->onDelete('cascade');
            $table->foreignIdFor(AkunAktif::class, 'id_akun')->constrained('akun_aktif', 'id_akun')->onDelete('cascade');
            $table->foreignIdFor(Satuan::class, 'id_satuan')->constrained('satuan', 'id_satuan')->onDelete('cascade');
            $table->foreignIdFor(User::class, 'created_by');
            $table->foreignIdFor(User::class, 'updated_by')->nullable();
            $table->foreignIdFor(User::class, 'deleted_by')->nullable();
            $table->unsignedInteger('tahun');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('barang');
    }
};

