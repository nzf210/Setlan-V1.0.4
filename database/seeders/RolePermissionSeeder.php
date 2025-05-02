<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * permission = ['add_akun','delete_akun', 'add_barang',
     * 'edit_barang', 'delete_barang', 'update_barang']
     * role = ['super_admin','admin_opd', 'admin_kab','operator']
     */
    public function run(): void
    {
        Permission::create(['name' => 'add_akun']);
        Permission::create(['name' => 'delete_akun']);

        Permission::create(['name' => 'add_subkeg']);
        Permission::create(['name' => 'edit_subkeg']);
        Permission::create(['name' => 'delete_subkeg']);

        Permission::create(['name' => 'add_opd']);
        Permission::create(['name' => 'edit_opd']);
        Permission::create(['name' => 'delete_opd']);

        Permission::create(['name' => 'add_unit']);
        Permission::create(['name' => 'edit_unit']);
        Permission::create(['name' => 'delete_unit']);

        Permission::create(['name' => 'add_draft']);
        Permission::create(['name' => 'edit_draft']);
        Permission::create(['name' => 'delete_draft']);

        Permission::create(['name' => 'add_barang']);
        Permission::create(['name' => 'edit_barang']);
        Permission::create(['name' => 'delete_barang']);

        Permission::create(['name' => 'add_user']);
        Permission::create(['name' => 'edit_user']);
        Permission::create(['name' => 'delete_user']);

        Role::create(['name' => 'super_admin']);
        Role::create(['name' => 'admin_kab']);
        Role::create(['name' => 'admin_opd']);
        Role::create(['name' => 'operator']);

        $roleAdmin = Role::findByName('super_admin');
        $roleAdmin->givePermissionTo([
            'add_akun',
            'delete_akun',

            'add_subkeg',
            'edit_subkeg',
            'delete_subkeg',

            'add_opd',
            'edit_opd',
            'delete_opd',

            'add_unit',
            'edit_unit',
            'delete_unit',

            'add_draft',
            'edit_draft',
            'delete_draft',

            'add_barang',
            'edit_barang',
            'delete_barang',

            'add_user',
            'edit_user',
            'delete_user'
        ]);


        $roleKeuangan = Role::findByName('admin_kab');
        $roleKeuangan->givePermissionTo([
            'add_akun',
            'delete_akun',

            'add_subkeg',
            'edit_subkeg',
            'delete_subkeg',

            'add_opd',
            'edit_opd',
            'delete_opd',

            'add_unit',
            'edit_unit',
            'delete_unit',

            'add_draft',
            'edit_draft',
            'delete_draft',

            'add_barang',
            'edit_barang',
            'delete_barang',

            'add_user',
            'edit_user',
            'delete_user'
        ]);

        $roleOpd = Role::findByName('admin_opd');
        $roleOpd->givePermissionTo([
            'add_subkeg',
            'edit_subkeg',
            'delete_subkeg',

            'add_opd',
            'edit_opd',
            'delete_opd',

            'add_unit',
            'edit_unit',
            'delete_unit',

            'add_draft',
            'edit_draft',
            'delete_draft',

            'add_barang',
            'edit_barang',
            'delete_barang',

            'add_user',
            'edit_user',
            'delete_user'
            ]);

        $roleAdminOpd = Role::findByName('operator');
        $roleAdminOpd->givePermissionTo([
            'add_draft',
            'edit_draft',
            'delete_draft',

            'add_barang',
            'edit_barang',
            'delete_barang'

        ]);

    }
}
