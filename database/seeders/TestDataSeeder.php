<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Module1\Product;
use App\Models\Module2\Supplier;
use App\Models\Module3\Customer;
use App\Models\Module5\SparePart;
use App\Models\Module1\StockMovement;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class TestDataSeeder extends Seeder
{
    public function run()
    {
        // ============================================================
        // 1. UTILISATEURS DE TEST (si non existants)
        // ============================================================
        $admin = User::firstOrCreate(
            ['email' => 'admin@jrcomputer.com'],
            ['name' => 'Admin Jr', 'password' => Hash::make('password')]
        );
        $admin->assignRole('admin');

        $manager = User::firstOrCreate(
            ['email' => 'manager@jrcomputer.com'],
            ['name' => 'Manga Joseph', 'password' => Hash::make('manager123')]
        );
        $manager->assignRole('manager');

        $vendeur = User::firstOrCreate(
            ['email' => 'vendeur@jrcomputer.com'],
            ['name' => 'Ngo Laure', 'password' => Hash::make('vendeur123')]
        );
        $vendeur->assignRole('vendeur');

        $technicien = User::firstOrCreate(
            ['email' => 'technicien@jrcomputer.com'],
            ['name' => 'Atangana Paul', 'password' => Hash::make('technicien123')]
        );
        $technicien->assignRole('technicien_sav');

        // ============================================================
        // 2. FOURNISSEURS (Module 2)
        // ============================================================
        Supplier::insert([
            [
                'name' => 'Dell Cameroon SARL',
                'code' => 'DELL-CMR',
                'contact_person' => 'Mbarga Jean',
                'email' => 'contact@dell-cm.com',
                'phone' => '699123456',
                'address' => 'Rue de l\'Aqua, Douala, Cameroun',
                'tax_number' => 'CM123456789',
                'payment_terms' => 30,
                'total_purchased' => 0,
                'total_paid' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'HP Cameroon',
                'code' => 'HP-CMR',
                'contact_person' => 'Mokam Suzy',
                'email' => 'suzy.mokam@hp-cm.com',
                'phone' => '677889900',
                'address' => 'Boulevard de la République, Yaoundé',
                'tax_number' => 'CM987654321',
                'payment_terms' => 45,
                'total_purchased' => 0,
                'total_paid' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'DistriTech Africa',
                'code' => 'DTA-CMR',
                'contact_person' => 'Nkolo Franck',
                'email' => 'franck@distritech.com',
                'phone' => '690001122',
                'address' => 'Carrefour Bessengue, Douala',
                'tax_number' => 'CM456123789',
                'payment_terms' => 60,
                'total_purchased' => 0,
                'total_paid' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Espace Micro',
                'code' => 'EM-CMR',
                'contact_person' => 'Tchoumi Armel',
                'email' => 'armel@espacmicro.com',
                'phone' => '695554433',
                'address' => 'Rue de la Gare, Bafoussam',
                'tax_number' => 'CM789123456',
                'payment_terms' => 30,
                'total_purchased' => 0,
                'total_paid' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Toshiba Central Africa',
                'code' => 'TOS-CMR',
                'contact_person' => 'Mvondo Alice',
                'email' => 'alice@toshiba-ca.com',
                'phone' => '698877665',
                'address' => 'Avenue Kennedy, Douala',
                'tax_number' => 'CM321654987',
                'payment_terms' => 30,
                'total_purchased' => 0,
                'total_paid' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        // ============================================================
        // 3. CLIENTS (Module 3)
        // ============================================================
        Customer::insert([
            [
                'name' => 'Etele Joseph',
                'email' => 'joseph.etele@gmail.com',
                'phone' => '699112233',
                'address' => 'Quartier Makepe, Douala',
                'tax_number' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Mbarga Danielle',
                'email' => 'danielle.mbarga@yahoo.fr',
                'phone' => '655443322',
                'address' => 'Carrefour Mvan, Yaoundé',
                'tax_number' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Tchoffo Rodrigue',
                'email' => 'rodrigue.tchoffo@outlook.com',
                'phone' => '690998877',
                'address' => 'Rue de l\'Hôpital, Bafoussam',
                'tax_number' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Essomba Céline',
                'email' => 'celine.essomba@yahoo.com',
                'phone' => '677665544',
                'address' => 'Quartier Nlongkak, Yaoundé',
                'tax_number' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Mouliom Fabrice',
                'email' => 'fabrice.mouliom@gmail.com',
                'phone' => '699445566',
                'address' => 'Bali, Douala',
                'tax_number' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Nguea Jeannette',
                'email' => 'jeannette.nguea@yahoo.fr',
                'phone' => '698776655',
                'address' => 'Rue Piere, Bertoua',
                'tax_number' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        // ============================================================
        // 4. PRODUITS (Module 1)
        // ============================================================
        Product::insert([
            [
                'name' => 'Dell Latitude 3420',
                'reference' => 'DELL-3420',
                'serial_number' => 'SN-DELL-001',
                'description' => 'Ordinateur portable, Intel Core i5, 8Go RAM, SSD 256Go',
                'purchase_price' => 450000,
                'selling_price' => 550000,
                'quantity' => 12,
                'alert_threshold' => 3,
                'category' => 'Ordinateur portable',
                'supplier' => 'Dell Cameroon SARL',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'HP Pavilion 15',
                'reference' => 'HP-PAV15',
                'serial_number' => 'SN-HP-002',
                'description' => 'Ordinateur HP Pavilion, Ryzen 5, 16Go RAM, SSD 512Go',
                'purchase_price' => 520000,
                'selling_price' => 650000,
                'quantity' => 8,
                'alert_threshold' => 2,
                'category' => 'Ordinateur portable',
                'supplier' => 'HP Cameroon',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Souris Logitech MX Master 3',
                'reference' => 'LOG-MX3',
                'serial_number' => 'SN-LOG-003',
                'description' => 'Souris sans fil haute précision',
                'purchase_price' => 45000,
                'selling_price' => 65000,
                'quantity' => 25,
                'alert_threshold' => 5,
                'category' => 'Périphérique',
                'supplier' => 'DistriTech Africa',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Clavier Mécanique TKL RGB',
                'reference' => 'CLAV-TKL',
                'serial_number' => 'SN-CLAV-004',
                'description' => 'Clavier mécanique avec switchs bleus',
                'purchase_price' => 35000,
                'selling_price' => 55000,
                'quantity' => 15,
                'alert_threshold' => 4,
                'category' => 'Périphérique',
                'supplier' => 'DistriTech Africa',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Ecran Dell 24"',
                'reference' => 'DELL-24',
                'serial_number' => 'SN-DELL-005',
                'description' => 'Moniteur Dell P2422H, Full HD, IPS',
                'purchase_price' => 120000,
                'selling_price' => 165000,
                'quantity' => 10,
                'alert_threshold' => 2,
                'category' => 'Écran',
                'supplier' => 'Dell Cameroon SARL',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Ecran HP 22"',
                'reference' => 'HP-22',
                'serial_number' => 'SN-HP-006',
                'description' => 'Moniteur HP V22, Full HD',
                'purchase_price' => 95000,
                'selling_price' => 125000,
                'quantity' => 6,
                'alert_threshold' => 2,
                'category' => 'Écran',
                'supplier' => 'HP Cameroon',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        // ============================================================
        // 5. PIÈCES DÉTACHÉES SAV (Module 5)
        // ============================================================
        SparePart::insert([
            [
                'part_number' => 'Nappe-ECRAN-HP',
                'name' => 'Nappe d\'écran HP 15.6"',
                'compatibility' => 'HP Pavilion 15, HP 250 G7',
                'purchase_price' => 8500,
                'selling_price' => 12500,
                'quantity_in_stock' => 20,
                'min_stock_alert' => 5,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'part_number' => 'VENTILATEUR-DELL',
                'name' => 'Ventilateur Dell Latitude',
                'compatibility' => 'Dell Latitude 3420, 3440, 3450',
                'purchase_price' => 12500,
                'selling_price' => 18500,
                'quantity_in_stock' => 12,
                'min_stock_alert' => 3,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'part_number' => 'BATTERIE-HP-41',
                'name' => 'Batterie HP Pavilion 15',
                'compatibility' => 'HP Pavilion 15, HP 15-BS',
                'purchase_price' => 35000,
                'selling_price' => 55000,
                'quantity_in_stock' => 8,
                'min_stock_alert' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'part_number' => 'CARTE-MERE-DELL',
                'name' => 'Carte mère Dell Latitude 3420',
                'compatibility' => 'Dell Latitude 3420 uniquement',
                'purchase_price' => 125000,
                'selling_price' => 185000,
                'quantity_in_stock' => 3,
                'min_stock_alert' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'part_number' => 'CLAPIER-US',
                'name' => 'Clavier AZERTY HP Pavilion',
                'compatibility' => 'HP Pavilion 15, HP 250 G7',
                'purchase_price' => 15000,
                'selling_price' => 25000,
                'quantity_in_stock' => 15,
                'min_stock_alert' => 3,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        // ============================================================
        // 6. MOUVEMENTS DE STOCK INITIAUX (Module 1)
        // ============================================================
        // On récupère les produits créés pour ajouter des historiques réalistes
        $products = Product::all();
        foreach ($products as $product) {
            StockMovement::create([
                'product_id' => $product->id,
                'type' => 'in',
                'quantity' => $product->quantity,
                'reason' => 'Stock initial',
                'user_id' => 1,
                'created_at' => now()->subDays(rand(1, 30)),
                'updated_at' => now(),
            ]);
        }

        $this->command->info(' Données de test insérées avec succès !');
        $this->command->info('');
        $this->command->info(' Comptes de test :');
        $this->command->info('   Admin    : admin@jrcomputer.com / password');
        $this->command->info('   Manager  : manager@jrcomputer.com / manager123');
        $this->command->info('   Vendeur  : vendeur@jrcomputer.com / vendeur123');
        $this->command->info('   Technicien SAV : technicien@jrcomputer.com / technicien123');
    }
}