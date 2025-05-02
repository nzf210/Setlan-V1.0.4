<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\UserOpd as UserOpds;

class UserOPD extends Seeder
{
    /**
     * Untuk Super_Admin dan Admin_Kab tidak butuhkan pengecekan OPD
     */
    public function run(): void
    {
        UserOpds::factory()->create(
            [
                'id_user' => 3,
                'id_opd' => '4.01.1.06.8.01.01.0000'
            ]);
        UserOpds::factory()->create(
            [
                'id_user' => 4,
                'id_opd' => '4.01.1.06.8.01.01.0000'
            ]);
    }
}
