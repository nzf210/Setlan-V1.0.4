<?php

namespace Database\Seeders;

use App\Models\OpdModel;
use Illuminate\Database\Seeder;

class OpdSeeders extends Seeder
{
    public function run(): void
    {
        OpdModel::factory()->count(38)->create();
    }
}
