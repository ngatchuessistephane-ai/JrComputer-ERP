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
        // Reset cache des permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // ============================================================
        // MODULE 1 : PRODUITS & STOCK
        // ============================================================
        Permission::firstOrCreate(['name' => 'view products']);
        Permission::firstOrCreate(['name' => 'create products']);
        Permission::firstOrCreate(['name' => 'edit products']);
        Permission::firstOrCreate(['name' => 'delete products']);
        Permission::firstOrCreate(['name' => 'manage stock']);
        Permission::firstOrCreate(['name' => 'import products']);
        Permission::firstOrCreate(['name' => 'export products']);

        // ============================================================
        // MODULE 2 : ACHATS & FOURNISSEURS
        // ============================================================
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

        // ============================================================
        // MODULE 3 : VENTES & POS
        // ============================================================
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

        // ============================================================
        // MODULE 5 : SAV (SERVICES APRÈS-VENTE)
        // ============================================================
        Permission::firstOrCreate(['name' => 'view sav tickets']);
        Permission::firstOrCreate(['name' => 'create sav tickets']);
        Permission::firstOrCreate(['name' => 'edit sav tickets']);
        Permission::firstOrCreate(['name' => 'delete sav tickets']);
        Permission::firstOrCreate(['name' => 'close sav tickets']);
        Permission::firstOrCreate(['name' => 'use sav parts']);

        // ============================================================
        // ESPACE TECHNICIEN WEB (NOUVEAU)
        // ============================================================
        Permission::firstOrCreate(['name' => 'view own tickets']);
        Permission::firstOrCreate(['name' => 'update own ticket status']);
        Permission::firstOrCreate(['name' => 'close own tickets']);
        Permission::firstOrCreate(['name' => 'view ticket details']);

        // ============================================================
        // MODULE 8 : ANALYTICS & BI
        // ============================================================
        Permission::firstOrCreate(['name' => 'view analytics']);
        Permission::firstOrCreate(['name' => 'export reports']);
        Permission::firstOrCreate(['name' => 'manage settings']);

        // ============================================================
        // GESTION DES UTILISATEURS
        // ============================================================
        Permission::firstOrCreate(['name' => 'view users']);
        Permission::firstOrCreate(['name' => 'create users']);
        Permission::firstOrCreate(['name' => 'edit users']);
        Permission::firstOrCreate(['name' => 'delete users']);

        // ============================================================
        // CRÉATION DES RÔLES
        // ============================================================

        // ---------- RÔLE ADMIN (Tous les droits) ----------
        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        $adminRole->givePermissionTo(Permission::all());

        // ---------- RÔLE MANAGER (Droits étendus) ----------
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
            // SAV
            'view sav tickets',
            'create sav tickets',
            'edit sav tickets',
            // Analytics
            'view analytics',
            'export reports',
        ]);

        // ---------- RÔLE VENDEUR (Droits commerciaux limités) ----------
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
            // Produits (consultation seulement)
            'view products',
        ]);

        // ---------- RÔLE TECHNICIEN SAV (Droits d'intervention complet) ----------
        $techRole = Role::firstOrCreate(['name' => 'technicien_sav']);
        $techRole->syncPermissions([
            // SAV général
            'view sav tickets',
            'close sav tickets',
            'use sav parts',
            
            // Espace technicien web (atelier)
            'view own tickets',
            'update own ticket status',
            'close own tickets',
            'view ticket details',
            
            // Consultation (nécessaire pour les interventions)
            'view products',
            //'view customers',
        ]);

        // ============================================================
        // CRÉATION DES UTILISATEURS PAR DÉFAUT
        // ============================================================

        // 1. Administrateur
        $admin = User::firstOrCreate(
            ['email' => 'admin@jrcomputer.com'],
            [
                'name' => 'Admin Jr',
                'password' => bcrypt('password')
            ]
        );
        $admin->assignRole('admin');

        // 2. Manager
        $manager = User::firstOrCreate(
            ['email' => 'manager@jrcomputer.com'],
            [
                'name' => 'Manager Jr',
                'password' => bcrypt('manager123')
            ]
        );
        $manager->assignRole('manager');

        // 3. Vendeur
        $vendeur = User::firstOrCreate(
            ['email' => 'vendeur@jrcomputer.com'],
            [
                'name' => 'Vendeur Jr',
                'password' => bcrypt('vendeur123')
            ]
        );
        $vendeur->assignRole('vendeur');

        // 4. Technicien SAV
        $technicien = User::firstOrCreate(
            ['email' => 'technicien@jrcomputer.com'],
            [
                'name' => 'Technicien SAV',
                'password' => bcrypt('technicien123')
            ]
        );
        $technicien->assignRole('technicien_sav');

        // ============================================================
        // MESSAGE DE CONFIRMATION
        // ============================================================
        $this->command->info('✅ Rôles et permissions créés avec succès !');
        $this->command->info('📋 Utilisateurs créés :');
        $this->command->info('   - admin@jrcomputer.com (password)');
        $this->command->info('   - manager@jrcomputer.com (manager123)');
        $this->command->info('   - vendeur@jrcomputer.com (vendeur123)');
        $this->command->info('   - technicien@jrcomputer.com (technicien123)');
        $this->command->info('');
        $this->command->info('🔧 Permissions espace technicien web ajoutées :');
        $this->command->info('   - view own tickets');
        $this->command->info('   - update own ticket status');
        $this->command->info('   - close own tickets');
        $this->command->info('   - view ticket details');
    }
}