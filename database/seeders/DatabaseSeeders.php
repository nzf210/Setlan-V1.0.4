<?php

namespace Database\Seeders;

use App\Models\BarangModel;
use App\Models\KabupatenModel;
use App\Models\KodeBarangModel;
use App\Models\OpdModel;
use App\Models\SatuanModel;
use App\Models\UnitModel;
use Illuminate\Database\Seeder;

class DatabaseSeeders extends Seeder
{
    public function run(): void
    {
        $this->call([
            KabupatenModel::class,
            OpdModel::class,
            BarangModel::class,
            KodeBarangModel::class,
            UnitModel::class,
            SatuanModel::class,
        ]);
    }
}
