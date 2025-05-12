<?php

use App\Models\AkunBelanjaModel;
use App\Models\KabupatenModel;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
{
    Schema::create('akun_aktif', function (Blueprint $table) {
        $table->id('id_akun_aktif');
        $table->foreignIdFor(KabupatenModel::class, 'id_kabupaten')->nullable()->constrained('kabupaten', 'id_kabupaten')->onDelete('cascade');
        $table->foreignIdFor(AkunBelanjaModel::class, 'id_akun')->nullable()->constrained('akun_belanja', 'id_akun')->onDelete('cascade');
        $table->unsignedInteger('tahun');
        $table->unique(['id_akun', 'id_kabupaten', 'tahun'], 'unique_akun_aktif');
    });
}
    public function down(): void
    {
        Schema::dropIfExists('akun_aktif');
    }
};
