<?php

use App\Models\AkunAktifModel;
use App\Models\AkunBelanjaAktifModel;
use App\Models\KabupatenModel;
use App\Models\KodeBarangModel;
use App\Models\OpdModel;
use App\Models\SatuanModel;
use App\Models\UnitModel;
use App\Models\User;
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
            $table->decimal('harga', 19, 2)->default(0);
            $table->foreignIdFor(KabupatenModel::class, 'id_kabuten')->constrained('kabupaten', 'id_kabupaten')->onDelete('cascade');
            $table->foreignIdFor(UnitModel::class, 'id_unit')->constrained('unit', 'id_unit')->onDelete('cascade');
            $table->foreignIdFor(OpdModel::class, 'id_opd')->constrained('opd', 'id_opd')->onDelete('cascade');
            $table->foreignIdFor(KodeBarangModel::class, 'id_kode_barang')->constrained('kode_barang', 'id_kode_barang')->onDelete('cascade');
            $table->foreignIdFor(AkunBelanjaAktifModel::class, 'id_akun')->constrained('akun_aktif', 'id_akun')->onDelete('cascade');
            $table->foreignIdFor(SatuanModel::class, 'id_satuan')->constrained('satuan', 'id_satuan')->onDelete('cascade');
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

