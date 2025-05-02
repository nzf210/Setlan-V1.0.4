<?php

namespace Database\Seeders;
use App\Models\UserUnitModel;
use Illuminate\Database\Seeder;

class UserUnitSeeders extends Seeder
{
    public function run(): void
    {
            UserUnitModel::factory()->create(
            [
                'id_user' => 4,
                'id_unit' => '4.01.1.06.8.01.01.0000'
            ]);
            UserUnitModel::factory()->create(
            [
                'id_user' => 4,
                'id_unit' => '4.01.1.06.8.01.01.0001'
            ]);
            UserUnitModel::factory()->create(
            [
                'id_user' => 4,
                'id_unit' => '4.01.1.06.8.01.01.0002'
            ]);
            UserUnitModel::factory()->create(
            [
                'id_user' => 4,
                'id_unit' => '4.01.1.06.8.01.01.0003'
            ]);
            UserUnitModel::factory()->create(
            [
                'id_user' => 4,
                'id_unit' => '4.01.1.06.8.01.01.0004'
            ]);
    }
}
