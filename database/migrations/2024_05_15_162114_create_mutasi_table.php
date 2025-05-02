<?php

use App\Models\BeritaAcaraModel;
use App\Models\KabupatenModel;
use App\Models\OpdModel;
use App\Models\SubKegiatanAktifModel;
use App\Models\UnitModel;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('mutasi', function (Blueprint $table) {
            $table->id('id_mutasi');
            $table->string('id_barang');
            $table->foreign('id_barang')->references('id_barang')->on('barang')->onDelete('cascade');
            $table->foreignIdFor(SubKegiatanAktifModel::class, 'id_sub_kegiatan')->nullable()->constrained('sub_kegiatan_aktif', 'id_sub_kegiatan')->onDelete('cascade');
            $table->foreignIdFor(KabupatenModel::class, 'id_kabupaten')->constrained('kabupaten', 'id_kabupaten')->onDelete('cascade');
            $table->foreignIdFor(OpdModel::class, 'id_opd')->constrained('opd', 'id_opd')->onDelete('cascade');
            $table->foreignIdFor(UnitModel::class, 'id_unit')->constrained('unit', 'id_unit')->onDelete('cascade');
            $table->foreignIdFor(BeritaAcaraModel::class, 'id_berita_acara')->constrained('berita_acara', 'id_berita_acara')->onDelete('cascade');
            $table->integer('jumlah')->default(1);
            $table->decimal('pajak', 3, 2)->default(0);
            $table->decimal('penyesuaian', 19, 2)->default(0);
            $table->decimal('harga',19,2)->default(0);
            $table->decimal('tot_harga',19,2)->default(0);
            $table->enum('type', ['barang_masuk', 'barang_keluar','draft_masuk','saldo_awal','saldo_akhir','rusak','expired'])->default('draft_masuk');
            $table->date('tgl_beli')->nullable();
            $table->date('tgl_expired')->nullable();
            $table->unsignedInteger('tahun_buat')->nullable();
            $table->unsignedInteger('created_by')->nullable();
            $table->foreign('created_by')->references('id')->on('users')->nullOnDelete();
            $table->unsignedInteger('updated_by')->nullable();
            $table->foreign('updated_by')->references('id')->on('users')->nullOnDelete();
            $table->unsignedInteger('deleted_by')->nullable();
            $table->foreign('deleted_by')->references('id')->on('users')->nullOnDelete();
            $table->unsignedInteger('tahun');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('mutasi');
    }
};
