<?php

namespace Database\Seeders;

use App\Models\MUnit as ModelsMunit;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MUnit extends Seeder
{
    public function run(): void
    {
        ModelsMunit::factory()->count(47)->create();
    }
}
