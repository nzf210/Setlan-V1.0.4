<?php

namespace Database\Seeders;

use App\Models\KodeBarang as ModelsKodeBarang;
use Database\Factories\KodeBarangFactory;
use Illuminate\Database\Seeder;

class KodeBarang extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $factory = new KodeBarangFactory();
        foreach ($factory->predefinedData() as $data) {
            ModelsKodeBarang::upsert($data,['id_kd_barang']);
        }
    }
}
