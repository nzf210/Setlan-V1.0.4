<?php

namespace Database\Seeders;

use App\Models\MSubkeg;
use Illuminate\Database\Seeder;
use Database\Factories\SubKegFactory;

class SubKeg extends Seeder
{
    public function run(): void
    {
        $factory = new SubKegFactory();
        foreach ($factory->predefinedData() as $data) {
          MSubkeg::upsert($data,['id_subkeg']);
        }
    }
}
