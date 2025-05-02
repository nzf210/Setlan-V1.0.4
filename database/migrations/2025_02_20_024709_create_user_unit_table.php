<?php

use App\Models\UnitModel;
use App\Models\UserModel;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        Schema::create('user_unit', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(UserModel::class, 'id_user')->nullable()->constrained('users', 'id')->onDelete('cascade');
            $table->foreignIdFor(UnitModel::class, 'id_unit')->nullable()->constrained('unit', 'id_unit')->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_unit');
    }
};
