<?php

namespace Database\Seeders;

use App\Models\UnitModel;
use Illuminate\Database\Seeder;

class UnitSeeders extends Seeder
{
    public function run(): void
    {
        UnitModel::factory()->count(47)->create();
    }
}
