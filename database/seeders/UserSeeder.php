<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\Role;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $adminRole = Role::where('role_name', 'Admin')->first();
        $managerRole = Role::where('role_name', 'Manager')->first();
        $customerRole = Role::where('role_name', 'Customer')->first();

        $users = [];
        
        if ($adminRole) {
            $users[] = [
                'role_id' => $adminRole->role_id,
                'first_name' => 'Admin',
                'last_name' => 'User',
                'email' => 'admin@laptophub.com',
                'password_hash' => Hash::make('password'),
                'contact_number' => '1234567890',
                'is_active' => 1,
                'email_verified_at' => now(),
            ];
        }

        if ($managerRole) {
            $users[] = [
                'role_id' => $managerRole->role_id,
                'first_name' => 'Manager',
                'last_name' => 'User',
                'email' => 'manager@laptophub.com',
                'password_hash' => Hash::make('password'),
                'contact_number' => '1234567891',
                'is_active' => 1,
                'email_verified_at' => now(),
            ];
        }

        if ($customerRole) {
            $firstNames = ['John', 'Jane', 'Michael', 'Sarah', 'David', 'Emily', 'Robert', 'Jessica', 'William', 'Ashley'];
            $lastNames = ['Smith', 'Johnson', 'Williams', 'Brown', 'Jones', 'Garcia', 'Miller', 'Davis', 'Rodriguez', 'Martinez'];

            for ($i = 0; $i < 10; $i++) {
                $users[] = [
                    'role_id' => $customerRole->role_id,
                    'first_name' => $firstNames[$i],
                    'last_name' => $lastNames[$i],
                    'email' => strtolower($firstNames[$i] . '.' . $lastNames[$i]) . '@example.com',
                    'password_hash' => Hash::make('password'),
                    'contact_number' => '55512345' . str_pad($i, 2, '0', STR_PAD_LEFT),
                    'is_active' => 1,
                    'email_verified_at' => now(),
                ];
            }
        }

        foreach ($users as $user) {
            DB::table('users')->updateOrInsert(
                ['email' => $user['email']],
                $user
            );
        }
    }
}
