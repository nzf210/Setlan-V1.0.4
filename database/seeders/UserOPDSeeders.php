<?php

namespace Database\Seeders;

use App\Models\UserOpdModel;
use Illuminate\Database\Seeder;

class UserOPDSeeders extends Seeder
{
    public function run(): void
    {
        UserOpdModel::factory()->create(
            [
                'id_user' => 3,
                'id_opd' => 1
            ]);
        UserOpdModel::factory()->create(
            [
                'id_user' => 4,
                'id_opd' => 2
            ]);
    }
}
