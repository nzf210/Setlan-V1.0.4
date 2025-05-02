<?php

namespace Database\Seeders;

use App\Models\UserKabupatenModel;
use Illuminate\Database\Seeder;

define('ID_KAB', '91.19');
class UserKabupatenSeeders extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        UserKabupatenModel::factory()->create(
            [
                'id_user' => 2,
                'id_kab' => ID_KAB
            ]);
        UserKabupatenModel::factory()->create(
            [
                'id_user' => 3,
                'id_kab' => ID_KAB
            ]);
        UserKabupatenModel::factory()->create(
            [
                'id_user' => 4,
                'id_kab' => ID_KAB
            ]);
        UserKabupatenModel::factory()->create(
            [
                'id_user' => 5,
                'id_kab' => '01.01'
            ]);
    }
}
