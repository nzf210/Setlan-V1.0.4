<?php

use App\Models\Akun1;
use App\Models\Akun2;
use App\Models\Akun3;
use App\Models\Akun4;
use App\Models\Akun5;
use App\Models\AkunBelanja;
use App\Models\MKab;
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
    Schema::create('akunaktif', function (Blueprint $table) {
        $table->id();
        $table->foreignIdFor(AkunBelanja::class, 'ids')->nullable()->constrained('akun_belanja', 'id')->onDelete('cascade');
        $table->foreignIdFor(MKab::class, 'id_kab')->nullable()->constrained('m_kabs', 'id_kab')->onDelete('cascade');
        $table->string('id_akun');
        $table->unsignedInteger('tahun');
        $table->string('nama');
        $table->foreign('tahun')
              ->references('year')
              ->on('years')
              ->onDelete('cascade');
        $table->unique(['ids', 'id_kab', 'tahun']);
    });
}
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('akunaktif');
    }
};
