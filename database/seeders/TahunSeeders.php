<?php

namespace Database\Seeders;

use App\Models\TahunModel;
use Illuminate\Database\Seeder;
class TahunSeeders extends Seeder
{
    public function run(): void
    {
        TahunModel::factory()->count(4)->create();
    }
}
