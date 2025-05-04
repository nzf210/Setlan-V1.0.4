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
                'id_user' => 1,
                'id_unit' => 1
            ]);
            UserUnitModel::factory()->create(
            [
                'id_user' => 2,
                'id_unit' => 23
            ]);
            UserUnitModel::factory()->create(
            [
                'id_user' => 3,
                'id_unit' => 2
            ]);
            UserUnitModel::factory()->create(
            [
                'id_user' => 1,
                'id_unit' => 3
            ]);
            UserUnitModel::factory()->create(
            [
                'id_user' => 4,
                'id_unit' => 3
            ]);
    }
}
