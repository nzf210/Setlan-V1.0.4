<?php

namespace Database\Seeders;

use App\Models\AkunBelanjaModel;
use Illuminate\Database\Seeder;

class AkunBelanjaSeeders extends Seeder
{
    public function run(): void
    {
        AkunBelanjaModel::factory(2181)->create();
    }
}
