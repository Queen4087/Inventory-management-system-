<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $adminRole = \App\Models\Role::where('name', 'Admin')->first();
        $staffRole = \App\Models\Role::where('name', 'Staff')->first();

        if ($adminRole) {
            \App\Models\User::firstOrCreate(
                ['email' => 'admin@example.com'],
                [
                    'name' => 'System Admin',
                    'password' => \Illuminate\Support\Facades\Hash::make('password123'),
                    'role_id' => $adminRole->id,
                ]
            );
        }

        if ($staffRole) {
            \App\Models\User::firstOrCreate(
                ['email' => 'staff@example.com'],
                [
                    'name' => 'Store Staff',
                    'password' => \Illuminate\Support\Facades\Hash::make('password123'),
                    'role_id' => $staffRole->id,
                ]
            );
        }
    }
}
