<?php

use App\Models\AkunBelanja;
use App\Models\Kabupaten;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
{
    Schema::create('akun_aktif', function (Blueprint $table) {
        $table->id('id_akun_aktif');
        $table->foreignIdFor(Kabupaten::class, 'id_kabupaten')->nullable()->constrained('kabupaten', 'id_kabupaten')->onDelete('cascade');
        $table->foreignIdFor(AkunBelanja::class, 'id_akun')->nullable()->constrained('akun_belanja', 'id_akun')->onDelete('cascade');
        $table->unsignedInteger('tahun');
        $table->string('nama_akun_aktif');
        $table->unique(['id_akun', 'id_kabupaten', 'tahun'], 'unique_akun_aktif');
    });
}
    public function down(): void
    {
        Schema::dropIfExists('akun_aktif');
    }
};
