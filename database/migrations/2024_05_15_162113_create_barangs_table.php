<?php

use App\Models\AkunAktif;
use App\Models\KodeBarang;
use App\Models\MKab;
use App\Models\MOpd;
use App\Models\MUnit;
use App\Models\Satuan;
use App\Models\User;
use App\Models\Year;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('barangs', function (Blueprint $table) {
            $table->string('id_barang', 6)->primary();
            $table->string('nama_barang');
            $table->string('merek');
            $table->string('type')->nullable();
            $table->decimal('harga', 10, 2)->default(0);
            $table->foreignIdFor(KodeBarang::class, 'id_kd_barang')->constrained('kode_barang', 'id')->onDelete('cascade');
            $table->foreignIdFor(Satuan::class, 'satuan_id');
            $table->foreignIdFor(User::class, 'created_by');
            $table->foreignIdFor(User::class, 'updated_by')->nullable();
            $table->foreignIdFor(User::class, 'deleted_by')->nullable();
            $table->unsignedInteger('tahun');
            $table->foreign('tahun')
                  ->references('year')
                  ->on('years')
                  ->onDelete('cascade');
            $table->foreignIdFor(MUnit::class, 'id_unit')->constrained('m_units', 'id_unit')->onDelete('cascade');
            $table->foreignIdFor(MOpd::class, 'id_opd')->constrained('m_opds', 'id_opd')->onDelete('cascade');
            $table->foreignIdFor(MKab::class, 'id_kab')->constrained('m_kabs', 'id_kab')->onDelete('cascade');
            $table->foreignIdFor(AkunAktif::class, 'id_akun')->constrained('akunaktif', 'id')->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('barangs');
    }
};

