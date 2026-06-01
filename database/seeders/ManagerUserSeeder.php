<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Role;

class ManagerUserSeeder extends Seeder
{
    public function run()
    {
        $user = User::firstOrCreate(
            ['email' => 'manager@jrcomputer.com'],
            [
                'name' => 'Manager Test',
                'password' => bcrypt('manager123')
            ]
        );
        $user->assignRole('manager');
    }
}