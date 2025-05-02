<?php

namespace Database\Seeders;

use App\Models\SubKegiatanModel;
use Database\Factories\SubKegiatanFactory;
use Illuminate\Database\Seeder;

class SubKegiatanSeeders extends Seeder
{
    public function run(): void
    {
        $factory = new SubKegiatanFactory();
        foreach ($factory->predefinedData() as $data) {
          SubKegiatanModel::upsert($data,['id_subkeg']);
        }
    }
}
