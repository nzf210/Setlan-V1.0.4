<?php

namespace Database\Seeders;

use App\Models\UserModel;
use Illuminate\Database\Seeder;

class UsersSeeders extends Seeder
{
    public function run(): void
    {
        $admin = UserModel::factory()->create([
            'name' => 'Test Super Admin',
            'username' => 'test',
            'password' => bcrypt('password'),
            'phone' => '081355492003'
        ]);
        $admin->assignRole('super_admin');

        $userAdminKab = UserModel::factory()->create([
            'name' => 'Test Admin KAB',
            'username' => 'test1',
            'password' => bcrypt('password'),
            'phone' => '081355492004'
        ]);
        $userAdminKab->assignRole('admin_kab');

        $opdMulti = UserModel::factory()->create([
            'name' => 'Test Admin OPD',
            'username' => 'test2',
            'password' => bcrypt('password'),
            'phone' => '082355492003'
        ]);
        $opdMulti->assignRole('admin_opd');


        $operator = UserModel::factory()->create([
            'name' => 'Test Operator',
            'username' => 'test3',
            'password' => bcrypt('password'),
            'phone' => '083355492003'
        ]);
        $operator->assignRole('operator');

        $opdMultiTes = UserModel::factory()->create([
            'name' => 'Tes kab',
            'username' => 'testkab',
            'password' => bcrypt('password'),
            'phone' => '084355492003'
        ]);
        $opdMultiTes->assignRole('admin_kab');
    }
}

