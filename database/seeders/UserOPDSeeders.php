<?php

namespace Database\Seeders;

use App\Models\UserOpdModel;
use Illuminate\Database\Seeder;

class UserOPDSeeders extends Seeder
{
    /**
     * Untuk Super_Admin dan Admin_Kab tidak butuhkan pengecekan OPD
     */
    public function run(): void
    {
        UserOpdModel::factory()->create(
            [
                'id_user' => 3,
                'id_opd' => '4.01.1.06.8.01.01.0000'
            ]);
        UserOpdModel::factory()->create(
            [
                'id_user' => 4,
                'id_opd' => '4.01.1.06.8.01.01.0000'
            ]);
    }
}
