<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;

class RolePermissionSeeder extends Seeder
{
    public function run()
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // ========== MODULE 1 : Produits & Stock ==========
        Permission::firstOrCreate(['name' => 'view products']);
        Permission::firstOrCreate(['name' => 'create products']);
        Permission::firstOrCreate(['name' => 'edit products']);
        Permission::firstOrCreate(['name' => 'delete products']);
        Permission::firstOrCreate(['name' => 'manage stock']);
        Permission::firstOrCreate(['name' => 'import products']);
        Permission::firstOrCreate(['name' => 'export products']);

        // ========== MODULE 2 : Achats & Fournisseurs ==========
        Permission::firstOrCreate(['name' => 'view suppliers']);
        Permission::firstOrCreate(['name' => 'create suppliers']);
        Permission::firstOrCreate(['name' => 'edit suppliers']);
        Permission::firstOrCreate(['name' => 'delete suppliers']);
        Permission::firstOrCreate(['name' => 'import suppliers']);
        Permission::firstOrCreate(['name' => 'export suppliers']);

        Permission::firstOrCreate(['name' => 'view purchase orders']);
        Permission::firstOrCreate(['name' => 'create purchase orders']);
        Permission::firstOrCreate(['name' => 'edit purchase orders']);
        Permission::firstOrCreate(['name' => 'delete purchase orders']);
        Permission::firstOrCreate(['name' => 'receive purchase orders']);

        // ========== MODULE 3 : Ventes & POS ==========
        Permission::firstOrCreate(['name' => 'view customers']);
        Permission::firstOrCreate(['name' => 'create customers']);
        Permission::firstOrCreate(['name' => 'edit customers']);
        Permission::firstOrCreate(['name' => 'delete customers']);
        Permission::firstOrCreate(['name' => 'import customers']);
        Permission::firstOrCreate(['name' => 'export customers']);

        Permission::firstOrCreate(['name' => 'view quotes']);
        Permission::firstOrCreate(['name' => 'create quotes']);
        Permission::firstOrCreate(['name' => 'edit quotes']);
        Permission::firstOrCreate(['name' => 'delete quotes']);
        Permission::firstOrCreate(['name' => 'convert quotes']);

        Permission::firstOrCreate(['name' => 'view invoices']);
        Permission::firstOrCreate(['name' => 'create invoices']);
        Permission::firstOrCreate(['name' => 'edit invoices']);
        Permission::firstOrCreate(['name' => 'delete invoices']);
        Permission::firstOrCreate(['name' => 'record payments']);
        Permission::firstOrCreate(['name' => 'use pos']);

        // ========== GESTION DES UTILISATEURS ==========
Permission::firstOrCreate(['name' => 'view users']);
Permission::firstOrCreate(['name' => 'create users']);
Permission::firstOrCreate(['name' => 'edit users']);
Permission::firstOrCreate(['name' => 'delete users']);

        // ========== MODULE 5 : SAV ==========
        Permission::firstOrCreate(['name' => 'view sav tickets']);
        Permission::firstOrCreate(['name' => 'create sav tickets']);
        Permission::firstOrCreate(['name' => 'edit sav tickets']);
        Permission::firstOrCreate(['name' => 'delete sav tickets']);
        Permission::firstOrCreate(['name' => 'close sav tickets']);
        Permission::firstOrCreate(['name' => 'use sav parts']);

        // ========== MODULE 8 : Analytics ==========
        Permission::firstOrCreate(['name' => 'view analytics']);
        Permission::firstOrCreate(['name' => 'export reports']);
        Permission::firstOrCreate(['name' => 'manage settings']);

        // ========== RÔLES ==========
        // Admin (tous droits)
        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        $adminRole->givePermissionTo(Permission::all());

        // ========== CONFIGURATION ==========

        // Manager (droits étendus mais sans suppression ni configuration)
        $managerRole = Role::firstOrCreate(['name' => 'manager']);
        $managerRole->syncPermissions([
    // Produits & Stock
    'view products',
    'manage stock',
    'import products',
    'export products',
    // Fournisseurs & Achats
    'view suppliers',
    'view purchase orders',
    'create purchase orders',
    'receive purchase orders',
    // Clients
    'view customers',
    'create customers',
    'edit customers',
    // Devis
    'view quotes',
    'create quotes',
    'edit quotes',
    'convert quotes',
    // Factures
    'view invoices',
    'create invoices',
    'record payments',
    // POS
    'use pos',
    // SAV (futur)
    'view sav tickets',
    'create sav tickets',
    'edit sav tickets',
    // Analytics
    'view analytics',
    'export reports',
]);

// Vendeur (droits commerciaux limités)
$vendeurRole = Role::firstOrCreate(['name' => 'vendeur']);
$vendeurRole->syncPermissions([
    // Clients
    'view customers',
    'create customers',
    'edit customers',
    // Devis
    'view quotes',
    'create quotes',
    'edit quotes',
    'convert quotes',
    // Factures
    'view invoices',
    'create invoices',
    // POS
    'use pos',
    // Produits
    'view products',
]);

        // (Optionnel) Rôle Technicien SAV – à décommenter quand le module SAV sera prêt
       
        $techRole = Role::firstOrCreate(['name' => 'technicien_sav']);
        $techRole->syncPermissions([
            'view sav tickets',
            'create sav tickets',
            'edit sav tickets',
            'close sav tickets',
            'use sav parts',
            'view products',
            'view customers',
        ]);

        // ========== UTILISATEURS PAR DÉFAUT ==========
        // Admin
        $admin = User::firstOrCreate(
            ['email' => 'admin@jrcomputer.com'],
            ['name' => 'Admin Jr', 'password' => bcrypt('password')]
        );
        $admin->assignRole('admin');

        // Manager (test)
        $manager = User::firstOrCreate(
            ['email' => 'manager@jrcomputer.com'],
            ['name' => 'Manager Test', 'password' => bcrypt('manager123')]
        );
        $manager->assignRole('manager');

        // Vendeur (test)
        $vendeur = User::firstOrCreate(
           ['email' => 'vendeur@jrcomputer.com'],
           ['name' => 'Vendeur Test', 'password' => bcrypt('vendeur123')]
        );
        $vendeur->assignRole('vendeur');

    }
}