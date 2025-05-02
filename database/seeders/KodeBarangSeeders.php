<?php

namespace Database\Seeders;

use App\Models\KodeBarangModel;
use Database\Factories\KodeBarangFactory;
use Illuminate\Database\Seeder;

class KodeBarangSeeders extends Seeder
{
    public function run(): void
    {
        $factory = new KodeBarangFactory();
        foreach ($factory->predefinedData() as $data) {
            KodeBarangModel::upsert($data,['id_kd_barang']);
        }
    }
}
