<?php

namespace App\Http\Livewire\Module5;

use Livewire\Attributes\Layout;
use Livewire\Component;
use App\Models\Module3\Customer;
use App\Models\Module1\Product;
use App\Models\Module5\SavTicket;
use App\Models\Module5\TicketItem;
use App\Models\Module5\SparePart;
use App\Models\Module3\Invoice;
use App\Models\Module3\InvoiceItem;
use Illuminate\Support\Facades\Auth;
use App\Events\TicketAssigned;
use App\Events\TicketUrgent;
use Illuminate\Support\Facades\Log;

#[Layout('layouts.appProd')]
class TicketForm extends Component
{
    public $ticketId;
    public $customer_id, $product_id, $serial_number, $device_model, $description_failure;
    public $status = 'pending', $priority = 'medium', $assigned_to;
    public $is_warranty = false;
    public $warranty_end_date;
    public $parts = [];

    public $labor_cost;
    public $diagnostic_fee = 10000;
    public $generate_invoice_on_update = false;

    public $selectedPart, $partQuantity, $partUnitPrice;

    protected $rules = [
        'customer_id' => 'required|exists:customers,id',
        'product_id' => 'nullable|exists:products,id',
        'serial_number' => 'nullable|string|max:255',
        'device_model' => 'nullable|string|max:255',
        'description_failure' => 'required|string|min:10',
        'priority' => 'required|in:low,medium,high,critical',
        'assigned_to' => 'nullable|exists:users,id',
        'parts' => 'array',
        'parts.*.spare_part_id' => 'required|exists:spare_parts,id',
        'parts.*.quantity' => 'required|integer|min:1',
        'parts.*.unit_price' => 'required|numeric|min:0',
        'labor_cost' => 'nullable|numeric|min:0',
        'diagnostic_fee' => 'nullable|numeric|min:0',
    ];

    public function mount($id = null)
    {
        if ($id) {
            $ticket = SavTicket::with('items')->findOrFail($id);
            $this->ticketId = $ticket->id;
            $this->customer_id = $ticket->customer_id;
            $this->product_id = $ticket->product_id;
            $this->serial_number = $ticket->serial_number;
            $this->device_model = $ticket->device_model;
            $this->description_failure = $ticket->description_failure;
            $this->status = $ticket->status;
            $this->priority = $ticket->priority;
            $this->assigned_to = $ticket->assigned_to;
            $this->is_warranty = $ticket->is_warranty;
            $this->warranty_end_date = $ticket->warranty_end_date;
            
            $this->labor_cost = $ticket->labor_cost;
            $this->diagnostic_fee = $ticket->diagnostic_fee ?? 10000;
            
            $this->generate_invoice_on_update = (!$ticket->is_warranty && $ticket->status === 'completed' && !$ticket->invoice_id);
            
            $this->parts = $ticket->items->map(fn($item) => [
                'spare_part_id' => $item->spare_part_id,
                'spare_part_name' => $item->sparePart->name,
                'quantity' => $item->quantity,
                'unit_price' => $item->unit_price,
                'total' => $item->quantity * $item->unit_price,
            ])->toArray();
        } else {
            $this->status = 'pending';
            $this->priority = 'medium';
            $this->diagnostic_fee = 10000;
        }
        
        $this->partQuantity = 1;
    }

    public function updatedSelectedPart($value)
    {
        if ($value) {
            $part = SparePart::find($value);
            if ($part) {
                $this->partUnitPrice = $part->selling_price;
            }
        } else {
            $this->partUnitPrice = null;
        }
    }

    public function updatedProductId($value)
    {
        if ($value) {
            $product = Product::find($value);
            $invoice = Invoice::whereHas('items', function($q) use ($product) {
                $q->where('product_id', $product->id);
            })->where('status', 'paid')->latest()->first();

            if ($invoice && $product->serial_number) {
                $warrantyMonths = (int) config('sav.warranty_months', 6);
                $endDate = $invoice->date->copy()->addMonths($warrantyMonths);
                $this->is_warranty = now()->lessThanOrEqualTo($endDate);
                $this->warranty_end_date = $endDate->toDateString();
            } else {
                $this->is_warranty = false;
                $this->warranty_end_date = null;
            }
        }
    }

    public function updatedStatus($value)
    {
        if ($this->ticketId) {
            $ticket = SavTicket::find($this->ticketId);
            if ($ticket) {
                if (!$ticket->is_warranty && in_array($value, ['completed', 'restituted']) && !$ticket->invoice_id) {
                    $this->generate_invoice_on_update = true;
                } else {
                    $this->generate_invoice_on_update = false;
                }
            }
        }
    }

    public function addPart()
    {
        $this->validate([
            'selectedPart' => 'required|exists:spare_parts,id',
            'partQuantity' => 'required|integer|min:1',
            'partUnitPrice' => 'required|numeric|min:0',
        ]);

        $part = SparePart::find($this->selectedPart);
        
        // ✅ VÉRIFICATION DU STOCK
        if ($part && $part->quantity_in_stock < $this->partQuantity) {
            session()->flash('error', " Stock insuffisant pour '{$part->name}'. Disponible: {$part->quantity_in_stock}, Demandé: {$this->partQuantity}");
            $this->dispatch('scroll-to-top');
            return;
        }
        
        $total = $this->partQuantity * $this->partUnitPrice;

        $this->parts[] = [
            'spare_part_id' => $part->id,
            'spare_part_name' => $part->name,
            'quantity' => $this->partQuantity,
            'unit_price' => $this->partUnitPrice,
            'total' => $total,
        ];

        $this->reset(['selectedPart', 'partUnitPrice']);
        $this->partQuantity = 1;
        
        $this->dispatch('part-added');
    }

    public function removePart($index)
    {
        unset($this->parts[$index]);
        $this->parts = array_values($this->parts);
    }

    public function save()
    {
        $this->validate();

        // ✅ VÉRIFICATION DU STOCK AVANT DE SAUVEGARDER
        $stockErrors = [];
        foreach ($this->parts as $index => $part) {
            $sparePart = SparePart::find($part['spare_part_id']);
            if ($sparePart && $sparePart->quantity_in_stock < $part['quantity']) {
                $stockErrors[] = "Stock insuffisant pour '{$sparePart->name}'. Disponible: {$sparePart->quantity_in_stock}, Demandé: {$part['quantity']}";
            }
        }

        if (!empty($stockErrors)) {
            session()->flash('error', ' Erreur de stock :<br>' . implode('<br>', $stockErrors));
            $this->dispatch('scroll-to-top');
            return;
        }

        $oldAssignedTo = $this->ticketId ? SavTicket::find($this->ticketId)->assigned_to : null;

        $data = [
            'ticket_number' => $this->ticketId ? SavTicket::find($this->ticketId)->ticket_number : $this->generateTicketNumber(),
            'customer_id' => $this->customer_id,
            'product_id' => $this->product_id,
            'serial_number' => $this->serial_number,
            'device_model' => $this->device_model,
            'description_failure' => $this->description_failure,
            'status' => $this->status,
            'priority' => $this->priority,
            'assigned_to' => $this->assigned_to,
            'is_warranty' => $this->is_warranty,
            'warranty_end_date' => $this->warranty_end_date,
            'labor_cost' => $this->labor_cost,
            'diagnostic_fee' => $this->diagnostic_fee ?? 10000,
        ];

        if ($this->ticketId) {
            $ticket = SavTicket::find($this->ticketId);
            $ticket->update($data);
            
            Log::info('Ticket mis à jour - labor_cost = ' . $this->labor_cost);
            Log::info('Ticket mis à jour - status = ' . $this->status);
            
            if ($this->status === 'completed' && !$ticket->closed_at) {
                $ticket->closed_at = now();
                $ticket->save();
            }
            
            $ticket->items()->delete();
            
            $ticket->refresh();
            
            Log::info('Ticket après refresh - labor_cost = ' . $ticket->labor_cost);
            
        } else {
            $ticket = SavTicket::create($data);
            
            if ($this->status === 'completed') {
                $ticket->closed_at = now();
                $ticket->save();
            }
        }

        if ($this->assigned_to && $this->assigned_to != $oldAssignedTo) {
            event(new TicketAssigned($ticket, $this->assigned_to));
        }

        // ✅ DÉDUIRE LE STOCK POUR CHAQUE PIÈCE
        foreach ($this->parts as $part) {
            TicketItem::create([
                'ticket_id' => $ticket->id,
                'spare_part_id' => $part['spare_part_id'],
                'quantity' => $part['quantity'],
                'unit_price' => $part['unit_price'],
            ]);
            
            // ✅ DÉDUIRE LE STOCK
            $sparePart = SparePart::find($part['spare_part_id']);
            if ($sparePart) {
                $sparePart->quantity_in_stock -= $part['quantity'];
                $sparePart->save();
            }
        }

        // GÉNÉRATION DE LA FACTURE SI CONDITIONS RÉUNIES
        if ($this->generate_invoice_on_update && !$ticket->is_warranty && $ticket->status === 'restituted') {
            $invoice = $this->generateInvoice($ticket);
            $ticket->invoice_id = $invoice->id;
            $ticket->save();
            
            session()->flash('message', ' Ticket SAV sauvegardé et facture générée avec succès.');
            $this->dispatch('scroll-to-top');
            Log::info('Facture générée pour ticket #' . $ticket->ticket_number . ' avec labor_cost = ' . $ticket->labor_cost);
        } else {
            session()->flash('message', 'Ticket SAV sauvegardé.');
            $this->dispatch('scroll-to-top');
        }

        return redirect()->route('module5.tickets.show', $ticket->id);
    }

    /**
     * GÉNÉRATION DE LA FACTURE COMPLÈTE AVEC MAIN D'ŒUVRE
     */
    private function generateInvoice($ticket)
    {
        $partsTotal = 0;
        $partsData = [];

        $ticketItems = TicketItem::where('ticket_id', $ticket->id)->with('sparePart')->get();
        
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

        $diagnosticFee = $ticket->diagnostic_fee ?? 10000;
        $laborCost = $ticket->labor_cost ?? 0;
        
        Log::info('Génération facture - labor_cost récupéré = ' . $laborCost);
        
        $subtotal = $partsTotal + $laborCost + $diagnosticFee;
        $tax = $subtotal * 0.1925;
        $total = $subtotal + $tax;

        $invoice = Invoice::create([
            'reference' => $this->generateInvoiceReference(),
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
            Log::info('Main d\'œuvre ajoutée à la facture : ' . $laborCost . ' FCFA');
        } else {
            Log::warning('Aucune main d\'œuvre dans la facture - labor_cost = ' . $laborCost);
        }

        return $invoice;
    }

    private function generateTicketNumber()
    {
        $last = SavTicket::orderBy('id', 'desc')->first();
        $number = $last ? intval(substr($last->ticket_number, -5)) + 1 : 1;
        return 'TK-' . str_pad($number, 5, '0', STR_PAD_LEFT);
    }

    private function generateInvoiceReference()
    {
        $last = Invoice::orderBy('id', 'desc')->first();
        $number = $last ? intval(substr($last->reference, -5)) + 1 : 1;
        return 'FAC-SAV-' . str_pad($number, 5, '0', STR_PAD_LEFT);
    }

    public function render()
    {
        $customers = Customer::orderBy('name')->get();
        $products = Product::orderBy('name')->get();
        $technicians = \App\Models\User::role('technicien_sav')->get();
        $spareParts = SparePart::orderBy('name')->get();

        return view('livewire.module5.ticket-form', [
            'customers' => $customers,
            'products' => $products,
            'technicians' => $technicians,
            'spareParts' => $spareParts,
        ]);
    }
}