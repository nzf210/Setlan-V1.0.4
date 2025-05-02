<?php

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
        Schema::create('years', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('year')->unsigned()->unique();
            $table->unsignedInteger('akun')->unsigned()->default(0);
            $table->unsignedInteger('kd_id_barang')->unsigned()->default(0);
            $table->unsignedInteger('keg')->unsigned()->default(0);
            $table->unsignedInteger('sub_keg')->unsigned()->default(0);

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('years');
    }
};
