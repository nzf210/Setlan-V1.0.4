<?php

namespace Database\Seeders;

use App\Models\AkunBelanjaModel;
use Database\Factories\AkunBelanjaFactory;
use Illuminate\Database\Seeder;

class AkunBelanjaSeeders extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $factory = new AkunBelanjaFactory();
        foreach ($factory->predefinedData() as $data) {
            AkunBelanjaModel::upsert($data,['id_akun']);
        }
    }
}
