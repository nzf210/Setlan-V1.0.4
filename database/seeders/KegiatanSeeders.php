<?php

namespace Database\Seeders;

use App\Models\KegiatanModel;
use Illuminate\Database\Seeder;

class KegiatanSeeders extends Seeder
{

    public function run(): void
    {
        KegiatanModel::factory()->count(304)->create();
    }
}
