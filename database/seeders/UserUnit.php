<?php

namespace Database\Seeders;
use Illuminate\Database\Seeder;
use App\Models\UserUnit as UserUnits;

class UserUnit extends Seeder
{
    /**
     * Untuk Super_Admin, admin_kab dan admin_opd tidak di butuhkan pengecekan unit
     */
    public function run(): void
    {
        UserUnits::factory()->create(
            [
                'id_user' => 4,
                'id_unit' => '4.01.1.06.8.01.01.0000'
            ]);
            UserUnits::factory()->create(
            [
                'id_user' => 4,
                'id_unit' => '4.01.1.06.8.01.01.0001'
            ]);
            UserUnits::factory()->create(
            [
                'id_user' => 4,
                'id_unit' => '4.01.1.06.8.01.01.0002'
            ]);
            UserUnits::factory()->create(
            [
                'id_user' => 4,
                'id_unit' => '4.01.1.06.8.01.01.0003'
            ]);
            UserUnits::factory()->create(
            [
                'id_user' => 4,
                'id_unit' => '4.01.1.06.8.01.01.0004'
            ]);
    }
}
