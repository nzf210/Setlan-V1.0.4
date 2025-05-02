<?php

use App\Models\BeritaAcara;
use App\Models\Kabupaten;
use App\Models\MKab;
use App\Models\MKeg;
use App\Models\MOpd;
use App\Models\MSubkeg;
use App\Models\MUnit;
use App\Models\Opd;
use App\Models\SubKegiatan;
use App\Models\SubKegiatanAktif;
use App\Models\Unit;
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
        Schema::create('mutasi', function (Blueprint $table) {
            $table->id('id_mutasi');
            $table->string('id_barang');
            $table->foreign('id_barang')->references('id_barang')->on('barang')->onDelete('cascade');
            $table->foreignIdFor(SubKegiatanAktif::class, 'id_sub_kegiatan')->nullable()->constrained('sub_kegiatan_aktif', 'id_sub_kegiatan')->onDelete('cascade');
            $table->foreignIdFor(Kabupaten::class, 'id_kabupaten')->constrained('kabupaten', 'id_kabupaten')->onDelete('cascade');
            $table->foreignIdFor(Opd::class, 'id_opd')->constrained('opd', 'id_opd')->onDelete('cascade');
            $table->foreignIdFor(Unit::class, 'id_unit')->constrained('unit', 'id_unit')->onDelete('cascade');
            $table->foreignIdFor(BeritaAcara::class, 'id_berita_acara')->constrained('berita_acara', 'id_berita_acara')->onDelete('cascade');
            $table->integer('jumlah')->default(1);
            $table->float('pajak')->default(0);
            $table->float('penyesuaian')->default(0);
            $table->float('harga')->default(0);
            $table->float('tot_harga')->default(0);
            $table->enum('type', ['barang_masuk', 'barang_keluar','draft_masuk','saldo_awal','saldo_akhir','rusak','expired'])->default('draft_masuk');
            $table->date('tgl_beli')->nullable();
            $table->date('tgl_expired')->nullable();
            $table->string('tahun_buat', 4)->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->foreign('created_by')->references('id')->on('users')->nullOnDelete();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->foreign('updated_by')->references('id')->on('users')->nullOnDelete();
            $table->unsignedBigInteger('deleted_by')->nullable();
            $table->foreign('deleted_by')->references('id')->on('users')->nullOnDelete();
            $table->unsignedInteger('tahun');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mutasi');
    }
};
