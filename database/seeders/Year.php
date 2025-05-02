<?php

namespace Database\Seeders;

use Database\Factories\YearFactory;
use Illuminate\Database\Seeder;
use App\Models\Year as Tahun;
class Year extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $factory = new YearFactory();
        foreach ($factory->predefinedData() as $data) {
            Tahun::upsert($data, ['year']);
        }
    }
}
