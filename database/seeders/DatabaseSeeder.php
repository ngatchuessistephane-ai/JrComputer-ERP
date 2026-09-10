<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        // 1. Rôles, permissions et utilisateurs
        $this->call(RolePermissionSeeder::class);
        
        // 2. Données de base (fournisseurs, produits, clients, etc.)
        $this->call(TestDataSeeder::class);
    }
}