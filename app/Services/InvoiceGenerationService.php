<?php

namespace App\Services;

use App\Models\Module3\Invoice;
use App\Models\Module3\InvoiceItem;
use App\Models\Module5\SavTicket;
use App\Models\Module5\TicketItem;
use Illuminate\Support\Facades\Auth;

class InvoiceGenerationService
{
    private $diagnosticFee = 10000; // Forfait diagnostic

    public function generateFromTicket(SavTicket $ticket): Invoice
    {
        // Récupérer les pièces
        $ticketItems = TicketItem::where('ticket_id', $ticket->id)->with('sparePart')->get();
        $partsTotal = 0;
        $partsData = [];

        foreach ($ticketItems as $item) {
            $total = $item->quantity * $item->unit_price;
            $partsTotal += $total;
            $partsData[] = [
                'name' => $item->sparePart->name ?? 'Pièce',
                'reference' => $item->sparePart->part_number ?? 'N/A',
                'quantity' => $item->quantity,
                'unit_price' => $item->unit_price,
                'total' => $total,
            ];
        }

        // Calcul des totaux
        $diagnosticFee = $ticket->diagnostic_fee ?? $this->diagnosticFee;
        $laborCost = $ticket->labor_cost ?? 0;
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

        // Lignes de facture
        InvoiceItem::create([
            'invoice_id' => $invoice->id,
            'product_id' => null,
            'description' => 'Diagnostic technique (forfait)',
            'quantity' => 1,
            'unit_price' => $diagnosticFee,
            'total' => $diagnosticFee,
        ]);

        foreach ($partsData as $part) {
            InvoiceItem::create([
                'invoice_id' => $invoice->id,
                'product_id' => null,
                'description' => 'Pièce: ' . $part['name'] . ' (Réf: ' . $part['reference'] . ')',
                'quantity' => $part['quantity'],
                'unit_price' => $part['unit_price'],
                'total' => $part['total'],
            ]);
        }

        if ($laborCost > 0) {
            InvoiceItem::create([
                'invoice_id' => $invoice->id,
                'product_id' => null,
                'description' => 'Main d\'œuvre technique',
                'quantity' => 1,
                'unit_price' => $laborCost,
                'total' => $laborCost,
            ]);
        }

        return $invoice;
    }

    private function generateReference(): string
    {
        $last = Invoice::orderBy('id', 'desc')->first();
        $number = $last ? intval(substr($last->reference, -5)) + 1 : 1;
        return 'FAC-SAV-' . str_pad($number, 5, '0', STR_PAD_LEFT);
    }
}