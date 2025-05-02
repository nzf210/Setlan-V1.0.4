<?php

use App\Models\OpdModel;
use App\Models\UserModel;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('user_opd', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(UserModel::class, 'id_user')->nullable()->constrained('users', 'id')->onDelete('cascade');
            $table->foreignIdFor(OpdModel::class, 'id_opd')->nullable()->constrained('opd', 'id_opd')->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_opd');
    }
};
