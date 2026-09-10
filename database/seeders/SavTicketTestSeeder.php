<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class SavTicketTestSeeder extends Seeder
{
    public function run(): void
    {
        $this->command->info('🔧 Insertion des tickets SAV pour les graphiques...');

        // Récupérer l'ID de l'admin
        $userId = DB::table('users')->where('email', 'admin@jrcomputer.com')->value('id');
        if (!$userId) {
            $this->command->error('❌ Admin non trouvé. Exécutez d\'abord RolePermissionSeeder.');
            return;
        }

        // ✅ Récupérer les techniciens (id 4 et 5)
        $technicians = DB::table('users')
            ->whereIn('id', [4, 5])
            ->pluck('id')
            ->toArray();

        if (empty($technicians)) {
            $this->command->warn('⚠️ Techniciens (id 4 et 5) non trouvés, utilisation de l\'admin comme technicien.');
            $technicians = [$userId];
        }

        $customerIds = DB::table('customers')->pluck('id')->toArray();
        $productIds = DB::table('products')->pluck('id')->toArray();

        if (empty($customerIds) || empty($productIds)) {
            $this->command->error('❌ Clients ou produits manquants. Exécutez d\'abord TestDataSeeder.');
            return;
        }

        // ✅ Récupérer les pièces détachées avec leurs IDs
        $spareParts = DB::table('spare_parts')
            ->select('id', 'selling_price')
            ->get()
            ->toArray();

        if (empty($spareParts)) {
            $this->command->error('❌ Aucune pièce détachée trouvée. Exécutez d\'abord TestDataSeeder.');
            return;
        }

        // Nettoyer les tables SAV pour éviter les doublons
        $this->command->info('🧹 Nettoyage des tables SAV...');
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        DB::table('interventions')->truncate();
        DB::table('ticket_items')->truncate();
        DB::table('sav_tickets')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1');
        $this->command->info('✅ Tables SAV nettoyées');

        // ============================================================
        // GÉNÉRATION DES TICKETS SUR 3 MOIS
        // ============================================================
        $this->command->info('📄 Insertion des tickets SAV...');

        $startDate = Carbon::now()->subMonths(3)->startOfMonth();
        $endDate = Carbon::now()->endOfMonth();

        $tickets = [];
        $ticketItems = [];
        $ticketCounter = 1;

        // Statuts possibles (avec pondération pour plus de réalisme)
        $statuses = [
            'pending' => 15,      // 15% des tickets
            'assigned' => 15,     // 15%
            'diagnosing' => 10,   // 10%
            'repairing' => 10,    // 10%
            'completed' => 35,    // 35% → le plus fréquent
            'restituted' => 15,   // 15%
        ];

        // Priorités (pondérées)
        $priorities = [
            'low' => 20,
            'medium' => 40,
            'high' => 30,
            'critical' => 10,
        ];

        // Fonction de sélection pondérée
        $weightedRandom = function($weights) {
            $total = array_sum($weights);
            $rand = rand(1, $total);
            foreach ($weights as $key => $weight) {
                $rand -= $weight;
                if ($rand <= 0) return $key;
            }
            return array_keys($weights)[0];
        };

        $deviceModels = [
            'Ordinateur Dell XPS 15',
            'Ordinateur HP EliteBook 840',
            'Souris Logitech MX Master 3S',
            'Clavier Mécanique RGB',
            'Écran Samsung 27" Curve',
            'SSD 1To NVMe',
            'Webcam HD Logitech C920',
            'Routeur Wi-Fi 6'
        ];

        $failureDescriptions = [
            'Écran noir au démarrage',
            'La souris ne répond plus',
            'Le clavier ne fonctionne pas',
            'Écran qui clignote',
            'Le SSD n\'est pas reconnu',
            'La webcam affiche une image floue',
            'Le routeur ne diffuse plus le Wi-Fi',
            'L\'ordinateur s\'éteint aléatoirement',
            'Bruit étrange provenant du ventilateur',
            'La batterie ne se charge plus',
            'Problème de connectivité réseau',
            'Le système ne démarre pas'
        ];

        $technicalReports = [
            'Diagnostic effectué : carte mère défectueuse, remplacement nécessaire.',
            'Problème de pilote résolu après mise à jour du système.',
            'Pièce défectueuse identifiée et remplacée.',
            'Résolution du problème après nettoyage des composants.',
            'Remplacement du module RAM défectueux.',
            'Réinstallation du système d\'exploitation requise.',
            'Mise à jour du firmware effectuée.',
            'Connexion réseau rétablie après reconfiguration.',
            'Ventilateur remplacé, température stabilisée.',
            'Batterie remplacée, charge normale.'
        ];

        // Parcourir chaque mois sur 3 mois
        for ($month = 0; $month < 3; $month++) {
            $currentDate = $startDate->copy()->addMonths($month);
            $monthlyDate = $currentDate->copy()->addDays(rand(5, 20));
            
            // ✅ Logique métier : plus de tickets en fin de mois et en début de semaine
            $numTickets = rand(5, 9);
            
            // ✅ Si c'est le mois actuel, moins de tickets clôturés (travail en cours)
            $isCurrentMonth = $month === 2;
            
            for ($i = 0; $i < $numTickets; $i++) {
                $customerId = $customerIds[array_rand($customerIds)];
                $productId = $productIds[array_rand($productIds)];
                
                // ✅ Sélection pondérée du statut
                $status = $weightedRandom($statuses);
                
                // ✅ Ajustement pour le mois en cours : plus de tickets en cours
                if ($isCurrentMonth && rand(0, 1) === 1) {
                    // Pour le mois en cours, on favorise les statuts "en cours"
                    $inProgressStatuses = ['pending', 'assigned', 'diagnosing', 'repairing'];
                    $status = $inProgressStatuses[array_rand($inProgressStatuses)];
                }
                
                $priority = $weightedRandom($priorities);
                
                // ✅ Répartition équilibrée des techniciens
                $technicianId = $technicians[array_rand($technicians)];
                
                // Date de création (dans le mois courant)
                $createdDate = $monthlyDate->copy()->addDays(rand(0, 10))->format('Y-m-d');
                $createdAt = $createdDate . ' ' . rand(8, 17) . ':' . str_pad(rand(0, 59), 2, '0', STR_PAD_LEFT) . ':00';
                
                // Date de clôture (si le ticket est clôturé)
                $closedAt = null;
                if (in_array($status, ['completed', 'restituted'])) {
                    // ✅ Logique métier : délai de clôture dépend de la priorité
                    $maxDays = $priority === 'critical' ? 3 : ($priority === 'high' ? 5 : ($priority === 'medium' ? 8 : 14));
                    $closedDate = Carbon::parse($createdDate)->addDays(rand(1, $maxDays));
                    if ($closedDate->gt(Carbon::now())) {
                        $closedDate = Carbon::now();
                    }
                    $closedAt = $closedDate->format('Y-m-d H:i:s');
                }

                // Générer un numéro de ticket unique
                $ticketNumber = 'SAV-TEST-' . date('Ymd') . '-' . str_pad($ticketCounter, 3, '0', STR_PAD_LEFT);

                $tickets[] = [
                    'ticket_number' => $ticketNumber,
                    'customer_id' => $customerId,
                    'product_id' => $productId,
                    'serial_number' => 'SN-' . strtoupper(substr(uniqid(), -6)),
                    'device_model' => $deviceModels[array_rand($deviceModels)],
                    'description_failure' => $failureDescriptions[array_rand($failureDescriptions)],
                    'status' => $status,
                    'priority' => $priority,
                    'assigned_to' => $technicianId,
                    'is_warranty' => rand(0, 1) === 1,
                    'warranty_end_date' => rand(0, 1) === 1 ? Carbon::now()->addMonths(rand(1, 12))->format('Y-m-d') : null,
                    'technical_report' => in_array($status, ['diagnosing', 'repairing', 'completed', 'restituted']) 
                        ? $technicalReports[array_rand($technicalReports)] 
                        : null,
                    'duration_minutes' => in_array($status, ['completed', 'restituted']) 
                        ? rand(30, 240) 
                        : null,
                    'closed_at' => $closedAt,
                    'created_by' => $userId,
                    'created_at' => $createdAt,
                    'updated_at' => $createdAt,
                ];

                // ✅ Ajouter des pièces détachées pour les tickets de réparation
                if (in_array($status, ['repairing', 'completed', 'restituted']) && rand(0, 1) === 1) {
                    $randomPart = $spareParts[array_rand($spareParts)];
                    $partId = $randomPart->id;
                    $unitPrice = $randomPart->selling_price;
                    $quantity = rand(1, 2);
                    
                    $ticketItems[] = [
                        'ticket_id' => $ticketCounter,
                        'spare_part_id' => $partId,
                        'quantity' => $quantity,
                        'unit_price' => $unitPrice,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                }

                $ticketCounter++;
            }
        }

        // Insérer les tickets
        foreach ($tickets as $ticket) {
            DB::table('sav_tickets')->insert($ticket);
        }
        $this->command->info('✅ ' . count($tickets) . ' tickets SAV créés');

        // Récupérer les IDs des tickets insérés dans l'ordre
        $insertedTicketIds = DB::table('sav_tickets')->orderBy('id')->pluck('id')->toArray();

        // Mettre à jour les ticket_id des items
        $itemsWithTicketIds = [];
        $ticketIndex = 0;
        foreach ($ticketItems as $item) {
            $ticketId = $insertedTicketIds[$ticketIndex] ?? end($insertedTicketIds);
            $item['ticket_id'] = $ticketId;
            $itemsWithTicketIds[] = $item;
            $ticketIndex++;
            if ($ticketIndex >= count($insertedTicketIds)) {
                $ticketIndex = count($insertedTicketIds) - 1;
            }
        }

        // Insérer les articles des tickets
        foreach ($itemsWithTicketIds as $item) {
            DB::table('ticket_items')->insert($item);
        }
        $this->command->info('✅ ' . count($itemsWithTicketIds) . ' articles de tickets créés');

        // ============================================================
        // RÉCAPITULATIF
        // ============================================================
        $this->command->info('');
        $this->command->info('═══════════════════════════════════════════════════════════');
        $this->command->info('📊 RÉCAPITULATIF DES DONNÉES SAV');
        $this->command->info('═══════════════════════════════════════════════════════════');
        $this->command->info('🎫 Tickets SAV créés              : ' . count($tickets));
        $this->command->info('🔧 Articles de tickets            : ' . count($itemsWithTicketIds));
        
        // Statistiques par statut
        $statusStats = DB::table('sav_tickets')
            ->select('status', DB::raw('COUNT(*) as count'))
            ->groupBy('status')
            ->get();
        
        $this->command->info('📋 Répartition par statut :');
        foreach ($statusStats as $stat) {
            $this->command->info('   - ' . $stat->status . ' : ' . $stat->count);
        }
        
        // ✅ Statistiques par technicien
        $techStats = DB::table('sav_tickets')
            ->join('users', 'sav_tickets.assigned_to', '=', 'users.id')
            ->select('users.name', DB::raw('COUNT(*) as count'))
            ->groupBy('users.id', 'users.name')
            ->get();
        
        $this->command->info('👨‍🔧 Répartition par technicien :');
        foreach ($techStats as $tech) {
            $this->command->info('   - ' . $tech->name . ' : ' . $tech->count . ' tickets');
        }
        
        $this->command->info('📆 Période couverte                : ' . $startDate->format('M Y') . ' → ' . $endDate->format('M Y'));
        $this->command->info('═══════════════════════════════════════════════════════════');
        $this->command->info('✅ SEED SAV TERMINÉ AVEC SUCCÈS !');
        $this->command->info('');
        $this->command->info('🔄 Rechargez votre dashboard et sélectionnez "Année" pour voir l\'évolution du graphique SAV.');
    }
}