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
use Illuminate\Support\Facades\Auth;
use App\Events\TicketAssigned;

#[Layout('layouts.appProd')]
class TicketForm extends Component
{
    public $ticketId;
    public $customer_id, $product_id, $serial_number, $device_model, $description_failure;
    public $status = 'pending', $priority = 'medium', $assigned_to;
    public $is_warranty = false;
    public $warranty_end_date;
    public $parts = [];

    // Pour l'ajout de pièces
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
        }
    }

    // Vérification automatique de la garantie
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

    public function addPart()
    {
        $this->validate([
            'selectedPart' => 'required|exists:spare_parts,id',
            'partQuantity' => 'required|integer|min:1',
            'partUnitPrice' => 'required|numeric|min:0',
        ]);

        $part = SparePart::find($this->selectedPart);
        $total = $this->partQuantity * $this->partUnitPrice;

        $this->parts[] = [
            'spare_part_id' => $part->id,
            'spare_part_name' => $part->name,
            'quantity' => $this->partQuantity,
            'unit_price' => $this->partUnitPrice,
            'total' => $total,
        ];

        $this->reset(['selectedPart', 'partQuantity', 'partUnitPrice']);
    }

    public function removePart($index)
    {
        unset($this->parts[$index]);
        $this->parts = array_values($this->parts);
    }

    public function save()
    {
        $this->validate();

        // Sauvegarde de l'ancienne valeur assignée (pour déclencher l'événement)
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
        ];

        if ($this->ticketId) {
            $ticket = SavTicket::find($this->ticketId);
            $ticket->update($data);
            $ticket->items()->delete();
        } else {
            $ticket = SavTicket::create($data);
        }

        // Déclencher l'événement de notification si le technicien a changé
        if ($this->assigned_to && $this->assigned_to != $oldAssignedTo) {
            event(new TicketAssigned($ticket));
        }

        foreach ($this->parts as $part) {
            TicketItem::create([
                'ticket_id' => $ticket->id,
                'spare_part_id' => $part['spare_part_id'],
                'quantity' => $part['quantity'],
                'unit_price' => $part['unit_price'],
            ]);
        }

        session()->flash('message', 'Ticket SAV sauvegardé.');
        return redirect()->route('module5.tickets.show', $ticket->id);
    }

    private function generateTicketNumber()
    {
        $last = SavTicket::orderBy('id', 'desc')->first();
        $number = $last ? intval(substr($last->ticket_number, -5)) + 1 : 1;
        return 'TK-'.str_pad($number, 5, '0', STR_PAD_LEFT);
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