<?php

namespace Database\Seeders;

use App\Models\SubKegiatanModel;
use Database\Factories\SubKegiatanFactory;
use Illuminate\Database\Seeder;

class SubKegiatanSeeders extends Seeder
{
    public function run(): void
    {

        SubKegiatanModel::factory()->count(1120)->create();
    }
}
