<?php

use App\Models\BarangModel;
use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('draft_barang_masuk', function (Blueprint $table) {
            $table->id('id_draft');
            $table->foreignId('id_unit')->constrained('unit', 'id_unit')->onDelete('cascade');
            $table->string('id_barang');
            $table->foreign('id_barang')->references('id_barang')->on('barang')->onDelete('cascade');
            $table->foreignIdFor(User::class, 'created_by')->nullable()->constrained('users', 'id')->onDelete('set null');
            $table->foreignId('id_sub_kegiatan_aktif')->constrained('sub_kegiatan_aktif', 'id_sub_kegiatan_aktif')->onDelete('cascade');
            $table->foreignId('id_berita_acara')->nullable()->constrained('berita_acara', 'id_berita_acara')->onDelete('set null');
            $table->integer('jumlah')->default(1);
            $table->decimal('harga', 19, 2)->default(0);
            $table->decimal('pajak', 19, 2)->default(0);
            $table->date('tanggal_draft');
            $table->text('keterangan')->nullable();
            $table->enum('status', ['draft', 'confirmed'])->default('draft');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('draft_barang_masuk');
    }
};
