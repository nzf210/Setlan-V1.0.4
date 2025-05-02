<?php

namespace Database\Seeders;

use App\Models\MKeg;
use Database\Factories\KegFactory;
use Illuminate\Database\Seeder;

class Keg extends Seeder
{
   
    public function run(): void
    {
       
        $factory = new KegFactory();
        foreach ($factory->predefinedData() as $data) {
          MKeg::upsert($data, ['id_keg']);
        }
    }
}
