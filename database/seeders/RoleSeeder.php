<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        foreach (['Admin', 'InventoryManager', 'Customer'] as $roleName) {
            DB::table('roles')->updateOrInsert(
                ['role_name' => $roleName],
                ['role_name' => $roleName]
            );
        }
    }
}
