<?php

namespace App\Services;

use App\Models\Module3\Invoice;
use App\Models\Module3\InvoiceItem;
use App\Models\Module5\SavTicket;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class SavInvoiceService
{
    /**
     * Génère une facture pour un ticket SAV hors garantie
     */
    public function generateInvoice(SavTicket $ticket, string $technicalReport, ?int $durationMinutes, array $partsData): ?Invoice
    {
        try {
            // Récupérer les pièces du ticket (si non fournies)
            if (empty($partsData)) {
                $partsData = $this->getPartsDataFromTicket($ticket);
            }

            // Calcul des totaux
            $partsTotal = array_sum(array_column($partsData, 'total'));
            $diagnosticFee = $ticket->diagnostic_fee ?? env('SAV_DIAGNOSTIC_FEE', 10000);
            
            // Main d'œuvre : calcul basé sur la durée (si disponible)
            $hourlyRate = env('SAV_HOURLY_RATE', 5000);
            $laborCost = $durationMinutes ? ($durationMinutes / 60) * $hourlyRate : 0;

            $subtotal = $partsTotal + $laborCost + $diagnosticFee;
            $tax = $subtotal * 0.1925; // TVA 19.25%
            $total = $subtotal + $tax;

            // Création de la facture
            $invoice = Invoice::create([
                'reference' => $this->generateReference(),
                'customer_id' => $ticket->customer_id,
                'date' => now()->toDateString(),
                'due_date' => now()->addDays(30)->toDateString(),
                'status' => 'sent',
                'subtotal' => $subtotal,
                'discount' => 0,
                'tax' => $tax,
                'total' => $total,
                'paid_amount' => 0,
                'notes' => "Facture SAV - Ticket #{$ticket->ticket_number}\nAppareil: " . ($ticket->device_model ?? 'Non spécifié'),
                'created_by' => Auth::id(),
            ]);

            // 1. Ligne Diagnostic
            InvoiceItem::create([
                'invoice_id' => $invoice->id,
                'product_id' => null,
                'description' => 'Diagnostic technique (forfait)',
                'quantity' => 1,
                'unit_price' => $diagnosticFee,
                'total' => $diagnosticFee,
            ]);

            // 2. Lignes Pièces
            foreach ($partsData as $part) {
                InvoiceItem::create([
                    'invoice_id' => $invoice->id,
                    'product_id' => null,
                    'description' => 'Pièce: ' . ($part['name'] ?? 'N/A') . ' (Réf: ' . ($part['reference'] ?? 'N/A') . ')',
                    'quantity' => $part['quantity'] ?? 1,
                    'unit_price' => $part['unit_price'] ?? 0,
                    'total' => $part['total'] ?? 0,
                ]);
            }

            // 3. Ligne Main d'œuvre (si > 0)
            if ($laborCost > 0) {
                InvoiceItem::create([
                    'invoice_id' => $invoice->id,
                    'product_id' => null,
                    'description' => 'Main d\'œuvre technique (' . ($durationMinutes ?? 0) . ' minutes)',
                    'quantity' => 1,
                    'unit_price' => $laborCost,
                    'total' => $laborCost,
                ]);
            }

            // Mettre à jour le ticket avec l'ID de la facture
            $ticket->invoice_id = $invoice->id;
            $ticket->save();

            Log::info('Facture SAV générée', [
                'ticket_id' => $ticket->id,
                'invoice_id' => $invoice->id,
                'total' => $total
            ]);

            return $invoice;

        } catch (\Exception $e) {
            Log::error('Erreur génération facture SAV: ' . $e->getMessage(), [
                'ticket_id' => $ticket->id,
                'trace' => $e->getTraceAsString()
            ]);
            return null;
        }
    }

    /**
     * Récupère les pièces du ticket
     */
    private function getPartsDataFromTicket(SavTicket $ticket): array
    {
        $partsData = [];
        $items = $ticket->items()->with('sparePart')->get();

        foreach ($items as $item) {
            $partsData[] = [
                'id' => $item->spare_part_id,
                'name' => $item->sparePart->name ?? 'Pièce',
                'reference' => $item->sparePart->part_number ?? 'N/A',
                'quantity' => $item->quantity,
                'unit_price' => $item->unit_price,
                'total' => $item->quantity * $item->unit_price,
            ];
        }

        return $partsData;
    }

    /**
     * Génère une référence de facture
     */
    private function generateReference(): string
    {
        $last = Invoice::orderBy('id', 'desc')->first();
        $number = $last ? intval(substr($last->reference, -5)) + 1 : 1;
        return 'FAC-SAV-' . str_pad($number, 5, '0', STR_PAD_LEFT);
    }
}