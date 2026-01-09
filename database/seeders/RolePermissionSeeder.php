<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // ---- Roles ----
        $roles = [
            'superadmin',
            'admin_provinsi',
            'admin_sudin',
            'kasudin',
            'kasubag',
            'kepala_seksi',
            'ppk',
            'pptk',
            'pengurus_barang',
            'staff',
        ];

        foreach ($roles as $r) {
            Role::firstOrCreate(['name' => $r]);
        }

        // ---- Permissions per modul ----
        $permissions = [
            // Master Data
            'view_master',
            'create_master',
            'edit_master',
            'delete_master',

            // RAB
            'view_rab',
            'create_rab',
            'edit_rab',

            // Contract
            'view_kontrak',
            'create_kontrak',
            'edit_kontrak',

            // Permintaan
            'view_permintaan',
            'create_permintaan',

            // Delivery
            'view_delivery',
            'create_delivery',

            // Stok
            'view_stok',
            'opname_stok',
            'adjust_stok',

            // Transfer Sudin
            'transfer_sudin',
        ];

        foreach ($permissions as $p) {
            Permission::firstOrCreate(['name' => $p]);
        }

        // Grant super-admin semua
        $super = Role::where('name', 'superadmin')->first();
        $super->givePermissionTo(Permission::all());
    }
}
