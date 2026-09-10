<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class TestDataSeeder extends Seeder
{
    public function run(): void
    {
        $this->command->info('📦 Début de l\'insertion des données de test...');

        // Récupérer l'ID de l'admin (créé dans RolePermissionSeeder)
        $adminId = DB::table('users')->where('email', 'admin@jrcomputer.com')->value('id');
        if (!$adminId) {
            $this->command->error('❌ Admin non trouvé. Exécutez d\'abord RolePermissionSeeder.');
            return;
        }
        $this->command->info('👤 Utilisateur admin ID: ' . $adminId);

        // ============================================================
        // FOURNISSEURS (5)
        // ============================================================
        $this->command->info('🏭 Insertion des fournisseurs...');
        $suppliers = [
            ['name' => 'Dell Cameroun', 'code' => 'DELL-CMR', 'contact_person' => 'Jean Mbarga', 'email' => 'contact@dell.cm', 'phone' => '699123456', 'address' => 'Douala - Akwa', 'tax_number' => 'CM123456789', 'payment_terms' => 30],
            ['name' => 'HP Cameroun', 'code' => 'HP-CMR', 'contact_person' => 'Pauline Ngo', 'email' => 'commercial@hp.cm', 'phone' => '699234567', 'address' => 'Yaoundé - Mvan', 'tax_number' => 'CM987654321', 'payment_terms' => 45],
            ['name' => 'Logitech', 'code' => 'LOG-CMR', 'contact_person' => 'François Tchoffo', 'email' => 'sales@logitech.cm', 'phone' => '699345678', 'address' => 'Douala - Bonanjo', 'tax_number' => 'CM456789123', 'payment_terms' => 30],
            ['name' => 'Samsung Cameroun', 'code' => 'SAM-CMR', 'contact_person' => 'Marie Bela', 'email' => 'contact@samsung.cm', 'phone' => '699456789', 'address' => 'Yaoundé - Bastos', 'tax_number' => 'CM789123456', 'payment_terms' => 60],
            ['name' => 'Intel Corporation', 'code' => 'INT-CMR', 'contact_person' => 'Peter Njiki', 'email' => 'africa@intel.com', 'phone' => '699567890', 'address' => 'Douala - Makepe', 'tax_number' => 'CM321654987', 'payment_terms' => 30],
        ];
        foreach ($suppliers as $s) {
            DB::table('suppliers')->insert(array_merge($s, ['created_at' => now(), 'updated_at' => now()]));
        }
        $this->command->info('✅ 5 fournisseurs créés');

        // ============================================================
        // PRODUITS (8)
        // ============================================================
        $this->command->info('📦 Insertion des produits...');
        $products = [
            ['name' => 'Ordinateur Dell XPS 15', 'reference' => 'PC-DELL-001', 'serial_number' => 'XPS15-001', 'description' => 'Portable haut de gamme', 'purchase_price' => 850000, 'selling_price' => 1250000, 'quantity' => 15, 'alert_threshold' => 3, 'category' => 'Informatique', 'supplier' => 'Dell Cameroun'],
            ['name' => 'Ordinateur HP EliteBook 840', 'reference' => 'PC-HP-002', 'serial_number' => 'ELITE-002', 'description' => 'Ultrabook professionnel', 'purchase_price' => 720000, 'selling_price' => 1050000, 'quantity' => 8, 'alert_threshold' => 3, 'category' => 'Informatique', 'supplier' => 'HP Cameroun'],
            ['name' => 'Souris Logitech MX Master 3S', 'reference' => 'SOU-LOG-003', 'serial_number' => 'MX-003', 'description' => 'Souris sans fil', 'purchase_price' => 45000, 'selling_price' => 75000, 'quantity' => 50, 'alert_threshold' => 10, 'category' => 'Accessoires', 'supplier' => 'Logitech'],
            ['name' => 'Clavier Mécanique RGB', 'reference' => 'CLA-MEC-004', 'serial_number' => 'MEC-004', 'description' => 'Clavier mécanique', 'purchase_price' => 25000, 'selling_price' => 45000, 'quantity' => 30, 'alert_threshold' => 5, 'category' => 'Accessoires', 'supplier' => 'Logitech'],
            ['name' => 'Écran Samsung 27" Curve', 'reference' => 'ECR-SAM-005', 'serial_number' => 'S27-005', 'description' => 'Écran incurvé 144Hz', 'purchase_price' => 180000, 'selling_price' => 275000, 'quantity' => 12, 'alert_threshold' => 3, 'category' => 'Périphériques', 'supplier' => 'Samsung Cameroun'],
            ['name' => 'SSD 1To NVMe', 'reference' => 'SSD-SAM-007', 'serial_number' => 'SSD-007', 'description' => 'Stockage ultra rapide', 'purchase_price' => 55000, 'selling_price' => 85000, 'quantity' => 20, 'alert_threshold' => 5, 'category' => 'Stockage', 'supplier' => 'Western Digital'],
            ['name' => 'Webcam HD Logitech C920', 'reference' => 'WEB-LOG-008', 'serial_number' => 'CAM-008', 'description' => 'Webcam 1080p', 'purchase_price' => 25000, 'selling_price' => 45000, 'quantity' => 25, 'alert_threshold' => 5, 'category' => 'Périphériques', 'supplier' => 'Logitech'],
            ['name' => 'Routeur Wi-Fi 6', 'reference' => 'ROU-TP-012', 'serial_number' => 'TP-012', 'description' => 'Routeur gigabit', 'purchase_price' => 45000, 'selling_price' => 75000, 'quantity' => 8, 'alert_threshold' => 2, 'category' => 'Réseau', 'supplier' => 'TP-Link'],
        ];
        foreach ($products as $p) {
            DB::table('products')->insert(array_merge($p, ['created_at' => now(), 'updated_at' => now()]));
        }
        $this->command->info('✅ 8 produits créés');

        // ============================================================
        // CLIENTS (5)
        // ============================================================
        $this->command->info('👥 Insertion des clients...');
        $customers = [
            ['name' => 'Dupont SARL', 'email' => 'contact@dupont.com', 'phone' => '699123789', 'address' => 'Douala - Akwa', 'tax_number' => 'CM123456'],
            ['name' => 'ETS Mbarga', 'email' => 'mbarga@ets.com', 'phone' => '699234890', 'address' => 'Yaoundé - Mvan', 'tax_number' => 'CM234567'],
            ['name' => 'Université de Ngaoundéré', 'email' => 'info@univ-ndere.cm', 'phone' => '699345901', 'address' => 'Ngaoundéré - Campus', 'tax_number' => 'CM345678'],
            ['name' => 'Mme FOTSO Alice', 'email' => 'alice.fotso@gmail.com', 'phone' => '699456012', 'address' => 'Douala - Makepe', 'tax_number' => null],
            ['name' => 'STE NKOA SARL', 'email' => 'contact@nkoa.com', 'phone' => '699678234', 'address' => 'Yaoundé - Bastos', 'tax_number' => 'CM456789'],
        ];
        foreach ($customers as $c) {
            DB::table('customers')->insert(array_merge($c, [
                'total_purchased' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ]));
        }
        $this->command->info('✅ 5 clients créés');

        // ============================================================
        // PIÈCES DÉTACHÉES (5)
        // ============================================================
        $this->command->info('🔩 Insertion des pièces détachées...');
        $spareParts = [
            ['part_number' => 'ECR-15-001', 'name' => 'Écran 15.6" FHD', 'quantity_in_stock' => 5, 'min_stock_alert' => 3, 'selling_price' => 185000],
            ['part_number' => 'BAT-HP-002', 'name' => 'Batterie HP EliteBook 840', 'quantity_in_stock' => 3, 'min_stock_alert' => 2, 'selling_price' => 65000],
            ['part_number' => 'RAM-8G-008', 'name' => 'Module RAM 8Go DDR4', 'quantity_in_stock' => 10, 'min_stock_alert' => 3, 'selling_price' => 25000],
            ['part_number' => 'SSD-256-009', 'name' => 'SSD 256Go', 'quantity_in_stock' => 6, 'min_stock_alert' => 2, 'selling_price' => 35000],
            ['part_number' => 'CHA-DEL-010', 'name' => 'Chargeur Dell 130W', 'quantity_in_stock' => 3, 'min_stock_alert' => 2, 'selling_price' => 55000],
        ];
        foreach ($spareParts as $sp) {
            DB::table('spare_parts')->insert(array_merge($sp, ['created_at' => now(), 'updated_at' => now()]));
        }
        $this->command->info('✅ 5 pièces détachées créées');

        // ============================================================
        // BONS DE COMMANDE (3)
        // ============================================================
        $this->command->info('📄 Insertion des bons de commande...');
        $purchaseOrders = [
            ['reference' => 'BC-2025-001', 'supplier_id' => 1, 'order_date' => '2025-06-01', 'status' => 'received', 'notes' => 'Commande PC Dell', 'created_by' => $adminId],
            ['reference' => 'BC-2025-002', 'supplier_id' => 3, 'order_date' => '2025-06-10', 'status' => 'sent', 'notes' => 'Commande souris', 'created_by' => $adminId],
            ['reference' => 'BC-2025-003', 'supplier_id' => 4, 'order_date' => '2025-06-15', 'status' => 'draft', 'notes' => 'Commande écrans', 'created_by' => $adminId],
        ];
        foreach ($purchaseOrders as $po) {
            DB::table('purchase_orders')->insert(array_merge($po, [
                'expected_delivery_date' => Carbon::parse($po['order_date'])->addDays(7)->format('Y-m-d'),
                'subtotal' => 0,
                'discount' => 0,
                'tax' => 0,
                'total' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ]));
        }
        $this->command->info('✅ 3 bons de commande créés');

        // ============================================================
        // MOUVEMENTS DE STOCK (2)
        // ============================================================
        $this->command->info('📊 Insertion des mouvements de stock...');
        $stockMovements = [
            ['product_id' => 1, 'type' => 'in', 'quantity' => 5, 'reason' => 'Réception BC-2025-001', 'user_id' => $adminId],
            ['product_id' => 3, 'type' => 'in', 'quantity' => 10, 'reason' => 'Réception BC-2025-002', 'user_id' => $adminId],
        ];
        foreach ($stockMovements as $sm) {
            DB::table('stock_movements')->insert(array_merge($sm, ['created_at' => now(), 'updated_at' => now()]));
            DB::table('products')->where('id', $sm['product_id'])->increment('quantity', $sm['quantity']);
        }
        $this->command->info('✅ 2 mouvements de stock créés');

        // ============================================================
        // STATISTIQUES FINALES
        // ============================================================
        $this->command->info('');
        $this->command->info('═══════════════════════════════════════════════════════════');
        $this->command->info('📊 RÉCAPITULATIF DES DONNÉES INSÉRÉES');
        $this->command->info('═══════════════════════════════════════════════════════════');
        $this->command->info('🏭 Fournisseurs      : ' . DB::table('suppliers')->count());
        $this->command->info('📦 Produits          : ' . DB::table('products')->count());
        $this->command->info('👥 Clients           : ' . DB::table('customers')->count());
        $this->command->info('🔩 Pièces détachées  : ' . DB::table('spare_parts')->count());
        $this->command->info('📄 Bons de commande  : ' . DB::table('purchase_orders')->count());
        $this->command->info('📊 Mouvements stock  : ' . DB::table('stock_movements')->count());
        $this->command->info('═══════════════════════════════════════════════════════════');
        $this->command->info('✅ SEED TERMINÉ AVEC SUCCÈS !');
    }
}