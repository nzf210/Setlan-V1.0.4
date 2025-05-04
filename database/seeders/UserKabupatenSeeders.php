<?php

namespace Database\Seeders;

use App\Models\UserKabupatenModel;
use Illuminate\Database\Seeder;

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
                'id_kabupaten' => 1
            ]);
        UserKabupatenModel::factory()->create(
            [
                'id_user' => 3,
                'id_kabupaten' => 1
            ]);
        UserKabupatenModel::factory()->create(
            [
                'id_user' => 4,
                'id_kabupaten' => 1
            ]);
        UserKabupatenModel::factory()->create(
            [
                'id_user' => 5,
                'id_kabupaten' => 1
            ]);
    }
}
