<?php

namespace Database\Seeders;

use App\Models\MKab as ModelsMKab;
use Illuminate\Database\Seeder;

class MKab extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        ModelsMKab::factory()->count(3)->create();

        $this->call([
            MOpd::class,
            MUnit::class,
            Year::class,
            Keg::class,
            SubKeg::class,
            RolePermissionSeeder::class,
            Users::class,
            Userkab::class,
            UserOPD::class,
            UserUnit::class,
            AkunBelanja::class,
            KodeBarang::class,
            Satuan::class
        ]);
    }
}
