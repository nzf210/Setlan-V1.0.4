<?php

namespace Database\Seeders;

use App\Models\AkunBelanja as ModelsAkunBelanja;
use Database\Factories\AkunBelanjaFactory;
use Illuminate\Database\Seeder;

class AkunBelanja extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $factory = new AkunBelanjaFactory();
        foreach ($factory->predefinedData() as $data) {
            ModelsAkunBelanja::upsert($data,['id_akun']);
        }
    }
}
