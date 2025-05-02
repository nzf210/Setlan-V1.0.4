<?php

namespace Database\Seeders;

use App\Models\SatuanModel;
use Database\Factories\SatuanFactory;
use Illuminate\Database\Seeder;

class SatuanSeeders extends Seeder
{
    public function run(): void
    {
        $factory = new SatuanFactory();
        foreach ($factory->predefinedData() as $data) {
            SatuanModel::create($data);
        }
    }
}
