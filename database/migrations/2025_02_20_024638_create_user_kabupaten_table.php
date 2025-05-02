<?php

use App\Models\KabupatenModel;
use App\Models\UserModel;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        Schema::create('user_kabupaten', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(UserModel::class, 'id_user')->nullable()->constrained('users', 'id')->onDelete('cascade');
            $table->foreignIdFor(KabupatenModel::class, 'id_kabupaten')->nullable()->constrained('kabupaten', 'id_kabupaten')->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_kabupaten');
    }
};
