<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class GraphTestDataSeeder extends Seeder
{
    public function run(): void
    {
        $this->command->info('📊 Insertion des données de test pour les graphiques...');

        // Récupérer l'ID de l'admin
        $userId = DB::table('users')->where('email', 'admin@jrcomputer.com')->value('id');
        if (!$userId) {
            $this->command->error('❌ Admin non trouvé. Exécutez d\'abord RolePermissionSeeder et TestDataSeeder.');
            return;
        }

        $productIds = DB::table('products')->pluck('id')->toArray();
        $customerIds = DB::table('customers')->pluck('id')->toArray();
        $supplierIds = DB::table('suppliers')->pluck('id')->toArray();

        if (empty($productIds) || empty($customerIds) || empty($supplierIds)) {
            $this->command->error('❌ Données manquantes. Exécutez d\'abord TestDataSeeder.');
            return;
        }

        // ============================================================
        // 1. FACTURES sur 3 mois glissants
        // ============================================================
        $this->command->info('📄 Insertion des factures...');

        $startDate = Carbon::now()->subMonths(3)->startOfMonth();
        $endDate = Carbon::now()->endOfMonth();

        $invoices = [];
        $invoiceItems = [];
        $invoiceCounter = 1;

        for ($date = $startDate->copy(); $date <= $endDate; $date->addMonth()) {
            $monthlyDate = $date->copy()->addDays(rand(5, 20));
            $numInvoices = rand(2, 4);
            for ($i = 0; $i < $numInvoices; $i++) {
                $customerId = $customerIds[array_rand($customerIds)];
                $total = rand(200000, 1500000);
                $statuses = ['sent', 'paid'];
                $status = $statuses[array_rand($statuses)];
                $invoiceDate = $monthlyDate->copy()->addDays(rand(0, 10))->format('Y-m-d');

                $invoices[] = [
                    'reference' => 'FACT-' . date('YmdHis') . '-' . $invoiceCounter,
                    'customer_id' => $customerId,
                    'date' => $invoiceDate,
                    'due_date' => Carbon::parse($invoiceDate)->addDays(30)->format('Y-m-d'),
                    'subtotal' => $total,
                    'discount' => 0,
                    'tax' => 0,
                    'total' => $total,
                    'status' => $status,
                    'notes' => 'Facture test',
                    'created_by' => $userId,
                    'created_at' => $invoiceDate . ' 00:00:00',
                    'updated_at' => $invoiceDate . ' 00:00:00',
                ];
                $invoiceCounter++;
            }
        }

        // Insérer les factures
        foreach ($invoices as $inv) {
            DB::table('invoices')->insert($inv);
        }
        $this->command->info('✅ ' . count($invoices) . ' factures créées');

        // Récupérer les IDs des factures insérées
        $insertedInvoiceIds = DB::table('invoices')->orderBy('id')->pluck('id')->toArray();

        // Préparer les articles avec les bons invoice_id
        $itemsToInsert = [];
        foreach ($insertedInvoiceIds as $idx => $invoiceId) {
            // On associe chaque facture à 2-4 articles
            $numItems = rand(2, 4);
            for ($j = 0; $j < $numItems; $j++) {
                $productId = $productIds[array_rand($productIds)];
                $product = DB::table('products')->find($productId);
                $quantity = rand(1, 3);
                $unitPrice = $product ? $product->selling_price : rand(20000, 500000);
                $lineTotal = $quantity * $unitPrice;

                $itemsToInsert[] = [
                    'invoice_id' => $invoiceId,
                    'product_id' => $productId,
                    'quantity' => $quantity,
                    'unit_price' => $unitPrice,
                    'total' => $lineTotal,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
        }

        // Insérer les articles
        foreach ($itemsToInsert as $item) {
            DB::table('invoice_items')->insert($item);
        }
        $this->command->info('✅ ' . count($itemsToInsert) . ' articles de facture créés');

        // Mettre à jour les totaux clients
        DB::statement("UPDATE customers SET total_purchased = (SELECT COALESCE(SUM(total), 0) FROM invoices WHERE customer_id = customers.id AND status = 'paid')");

        // Décrémenter les stocks
        foreach ($itemsToInsert as $item) {
            DB::table('products')->where('id', $item['product_id'])->decrement('quantity', $item['quantity']);
        }

        // ============================================================
        // 2. BONS DE COMMANDE REÇUS sur la même période
        // ============================================================
        $this->command->info('📦 Insertion des bons de commande reçus...');

        $poItems = [];
        $purchaseOrders = [];
        $poCounter = 1;

        for ($date = $startDate->copy(); $date <= $endDate; $date->addMonth()) {
            $monthlyDate = $date->copy()->addDays(rand(5, 20));
            $numPO = rand(1, 2);
            for ($i = 0; $i < $numPO; $i++) {
                $supplierId = $supplierIds[array_rand($supplierIds)];
                $orderDate = $monthlyDate->copy()->addDays(rand(0, 10))->format('Y-m-d');
                $total = rand(150000, 1200000);

                $purchaseOrders[] = [
                    'reference' => 'BC-TEST-' . date('YmdHis') . '-' . $poCounter,
                    'supplier_id' => $supplierId,
                    'order_date' => $orderDate,
                    'expected_delivery_date' => Carbon::parse($orderDate)->addDays(7)->format('Y-m-d'),
                    'status' => 'received',
                    'subtotal' => $total,
                    'discount' => 0,
                    'tax' => 0,
                    'total' => $total,
                    'notes' => 'BC reçu pour graphique',
                    'created_by' => $userId,
                    'created_at' => $orderDate . ' 00:00:00',
                    'updated_at' => $orderDate . ' 00:00:00',
                ];
                $poCounter++;
            }
        }

        foreach ($purchaseOrders as $po) {
            DB::table('purchase_orders')->insert($po);
        }
        $this->command->info('✅ ' . count($purchaseOrders) . ' bons de commande reçus créés');

        // Récupérer les IDs des PO insérés
        $insertedPoIds = DB::table('purchase_orders')->orderBy('id')->pluck('id')->toArray();

        // Articles des PO
        $poItemsToInsert = [];
        foreach ($insertedPoIds as $poId) {
            $numItems = rand(2, 4);
            for ($j = 0; $j < $numItems; $j++) {
                $productId = $productIds[array_rand($productIds)];
                $product = DB::table('products')->find($productId);
                $quantity = rand(1, 3);
                $unitPrice = $product ? $product->purchase_price : rand(10000, 300000);
                $lineTotal = $quantity * $unitPrice;

                $poItemsToInsert[] = [
                    'purchase_order_id' => $poId,
                    'product_id' => $productId,
                    'quantity_ordered' => $quantity,
                    'quantity_received' => $quantity,
                    'unit_price' => $unitPrice,
                    'total' => $lineTotal,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
        }

        foreach ($poItemsToInsert as $item) {
            DB::table('purchase_order_items')->insert($item);
        }
        $this->command->info('✅ ' . count($poItemsToInsert) . ' articles de bons de commande créés');

        // Incrémenter les stocks
        foreach ($poItemsToInsert as $item) {
            DB::table('products')->where('id', $item['product_id'])->increment('quantity', $item['quantity_ordered']);
        }

        // ============================================================
        // RÉCAPITULATIF
        // ============================================================
        $this->command->info('');
        $this->command->info('═══════════════════════════════════════════════════════════');
        $this->command->info('📊 RÉCAPITULATIF DES DONNÉES DE TEST POUR GRAPHIQUES');
        $this->command->info('═══════════════════════════════════════════════════════════');
        $this->command->info('📄 Factures créées                 : ' . count($invoices));
        $this->command->info('📄 Articles de factures            : ' . count($itemsToInsert));
        $this->command->info('📦 Bons de commande reçus          : ' . count($purchaseOrders));
        $this->command->info('📦 Articles de bons de commande    : ' . count($poItemsToInsert));
        $this->command->info('📆 Période couverte                : ' . $startDate->format('M Y') . ' → ' . $endDate->format('M Y'));
        $this->command->info('═══════════════════════════════════════════════════════════');
        $this->command->info('✅ SEED TERMINÉ AVEC SUCCÈS !');
    }
}