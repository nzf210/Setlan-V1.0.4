<?php

namespace Database\Seeders;

use App\Models\KegiatanModel;
use Database\Factories\KegiatanFactory;
use Illuminate\Database\Seeder;

class KegiatanSeeders extends Seeder
{

    public function run(): void
    {
        $factory = new KegiatanFactory();
        foreach ($factory->predefinedData() as $data) {
            KegiatanModel::upsert($data, ['id_keg']);
        }
    }
}
