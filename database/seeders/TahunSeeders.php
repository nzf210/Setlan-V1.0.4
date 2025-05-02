<?php

namespace Database\Seeders;

use App\Models\TahunModel;
use Database\Factories\TahunFactory;
use Illuminate\Database\Seeder;
class TahunSeeders extends Seeder
{
    public function run(): void
    {
        $factory = new TahunFactory();
        foreach ($factory->predefinedData() as $data) {
            TahunModel::upsert($data, ['year']);
        }
    }
}
