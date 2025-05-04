<?php

namespace Database\Seeders;

use App\Models\KodeBarangModel;
use Illuminate\Database\Seeder;

class KodeBarangSeeders extends Seeder
{
    public function run(): void
    {
        KodeBarangModel::factory(503)->create();
    }
}
