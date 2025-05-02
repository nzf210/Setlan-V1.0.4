<?php

namespace Database\Seeders;

use App\Models\UserKabupaten;
use Illuminate\Database\Seeder;

define('ID_KAB', '91.19');
class Userkab extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        /**
         * Untuk Super_Admin tidak dilakukan pengecekan Kabupaten
         */
        UserKabupaten::factory()->create(
            [
                'id_user' => 2,
                'id_kab' => ID_KAB
            ]);
        UserKabupaten::factory()->create(
            [
                'id_user' => 3,
                'id_kab' => ID_KAB
            ]);
        UserKabupaten::factory()->create(
            [
                'id_user' => 4,
                'id_kab' => ID_KAB
            ]);
        UserKabupaten::factory()->create(
            [
                'id_user' => 5,
                'id_kab' => '01.01'
            ]);
    }
}
