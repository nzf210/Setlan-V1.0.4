<?php

use App\Models\MSubkeg;
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
        Schema::create('stoks', function (Blueprint $table) {
            $table->id();
            $table->string('id_barang');
            $table->foreign('id_barang')->references('id_barang')->on('barangs')->onDelete('restrict');
            $table->string('id_unit');
            $table->foreign('id_unit')->references('id_unit')->on('m_units')->onDelete('restrict');
            $table->foreignIdFor(MSubkeg::class, 'id_subkeg')->constrained('m_subkegs', 'id')->onDelete('cascade');
            $table->enum('kondisi', ['baik', 'rusak', 'expired', 'layak'])->default('baik');
            $table->unsignedInteger('stok');
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
        Schema::dropIfExists('stoks');
    }
};
