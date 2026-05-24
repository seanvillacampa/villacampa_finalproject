<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use App\Models\User;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'web']);
        Role::firstOrCreate(['name' => 'staff', 'guard_name' => 'web']);

        $admin = User::where('email', 'eulamaingenshin@gmail.com')->first();

        if ($admin) {
            $admin->update(['role' => 'admin']);
            $admin->syncRoles(['admin']);
        }
    }
}