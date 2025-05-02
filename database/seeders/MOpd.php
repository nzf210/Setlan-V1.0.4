<?php

namespace Database\Seeders;

use App\Models\MOpd as ModelsMOpd;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MOpd extends Seeder
{
    public function run(): void
    {
        ModelsMOpd::factory()->count(38)->create();
    }
}
