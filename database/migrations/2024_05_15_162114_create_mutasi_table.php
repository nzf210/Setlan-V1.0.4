<?php

use App\Models\MKab;
use App\Models\MKeg;
use App\Models\MOpd;
use App\Models\MSubkeg;
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
        Schema::create('mutasi', function (Blueprint $table) {
            $table->id();
            $table->string('id_barang');
            $table->foreign('id_barang')->references('id_barang')->on('barangs')->onDelete('cascade');
            $table->foreignIdFor(MSubkeg::class, 'id_subkeg')->nullable()->constrained('m_subkegs', 'id')->onDelete('cascade');
            $table->foreignIdFor(MKab::class, 'id_kab')->constrained('m_kabs', 'id_kab')->onDelete('cascade');
            $table->foreignIdFor(MOpd::class, 'id_opd')->constrained('m_opds', 'id_opd')->onDelete('cascade');
            $table->foreignIdFor(MUnit::class, 'id_unit')->constrained('m_units', 'id_unit')->onDelete('cascade');
            $table->integer('jumlah')->default(1);
            $table->float('pajak')->default(0);
            $table->float('penyesuaian')->default(0);
            $table->float('harga')->default(0);
            $table->float('tot_harga')->default(0);
            $table->enum('type', ['brg_masuk', 'brg_keluar','draft_masuk','saldo_awal','saldo_akhir','rusak','expired'])->default('draft_masuk');
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
            $table->foreign('tahun')
                ->references('year')
                ->on('years')
                ->onDelete('cascade');
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
