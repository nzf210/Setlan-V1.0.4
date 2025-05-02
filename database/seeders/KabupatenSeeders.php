<?php

namespace Database\Seeders;

use App\Models\KabupatenModel;
use Illuminate\Database\Seeder;

class KabupatenSeeders extends Seeder
{

    public function run(): void
    {
        KabupatenModel::factory()->count(3)->create();

        $this->call([
            OpdSeeders::class,
            UnitSeeders::class,
            TahunSeeders::class,
            KegiatanSeeders::class,
            SubKegiatanSeeders::class,
            RolePermissionSeeder::class,
            UsersSeeders::class,
            UserKabupatenSeeders::class,
            UserOPDSeeders::class,
            UserUnitSeeders::class,
            AkunBelanjaSeeders::class,
            KodeBarangSeeders::class,
            SatuanSeeders::class
        ]);
    }
}
