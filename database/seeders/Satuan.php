<?php

namespace Database\Seeders;

use Database\Factories\SatuanFactory;
use Illuminate\Database\Seeder;

class Satuan extends Seeder
{
    public function run(): void
    {
        $factory = new SatuanFactory();
        foreach ($factory->predefinedData() as $data) {
            \App\Models\Satuan::create($data);
        }
    }
}
