<?php

namespace App\Http\Livewire\Module5;

use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Module5\SparePart;
use App\Models\Module1\StockMovement;
use Illuminate\Support\Facades\Auth;

#[Layout('layouts.appProd')]
class SparePartIndex extends Component
{
    use WithPagination;

    public $search = '';
    public $showForm = false;
    public $partId, $part_number, $name, $compatibility, $purchase_price, $selling_price, $quantity_in_stock, $min_stock_alert;

    // Ajustement stock
    public $adjustPartId, $adjustQuantity, $adjustReason;

    protected $rules = [
        'part_number' => 'required|string|unique:spare_parts,part_number',
        'name' => 'required|string|max:255',
        'selling_price' => 'required|numeric|min:0',
        'quantity_in_stock' => 'integer|min:0',
        'min_stock_alert' => 'integer|min:0',
    ];

    public function updatingSearch() { $this->resetPage(); }

    public function render()
    {
        $parts = SparePart::where('name', 'like', '%'.$this->search.'%')
            ->orWhere('part_number', 'like', '%'.$this->search.'%')
            ->paginate(10);
        return view('livewire.module5.spare-part-index', ['parts' => $parts]);
    }

    public function create() { $this->resetInput(); $this->showForm = true; }

    public function edit($id)
    {
        $part = SparePart::findOrFail($id);
        $this->partId = $part->id;
        $this->part_number = $part->part_number;
        $this->name = $part->name;
        $this->compatibility = $part->compatibility;
        $this->purchase_price = $part->purchase_price;
        $this->selling_price = $part->selling_price;
        $this->quantity_in_stock = $part->quantity_in_stock;
        $this->min_stock_alert = $part->min_stock_alert;
        $this->showForm = true;
    }

    public function save()
    {
        if ($this->partId) {
            $this->rules['part_number'] = 'required|string|unique:spare_parts,part_number,'.$this->partId;
            $this->validate();
            SparePart::find($this->partId)->update($this->except(['partId', 'showForm']));
        } else {
            $this->validate();
            SparePart::create($this->except(['partId', 'showForm']));
        }
        $this->resetInput();
        $this->showForm = false;
        session()->flash('message', 'Pièce sauvegardée.');
    }

    public function delete($id)
    {
        SparePart::find($id)?->delete();
        session()->flash('message', 'Pièce supprimée.');
    }

    // Ajustement de stock
    public function openAdjustStock($id)
    {
        $this->adjustPartId = $id;
        $this->adjustQuantity = 0;
        $this->adjustReason = '';
        $this->dispatch('open-adjust-modal');
    }

    public function adjustStock()
    {
        $this->validate([
            'adjustQuantity' => 'required|integer|not_in:0',
            'adjustReason' => 'required|string|min:3',
        ]);

        $part = SparePart::find($this->adjustPartId);
        if (!$part) {
            session()->flash('error', 'Pièce introuvable.');
            return;
        }

        $newQty = $part->quantity_in_stock + $this->adjustQuantity;
        if ($newQty < 0) {
            session()->flash('error', 'Le stock ne peut pas devenir négatif.');
            return;
        }

        $part->quantity_in_stock = $newQty;
        $part->save();

        // Enregistrement du mouvement dans stock_movements
        StockMovement::create([
            'spare_part_id' => $part->id,
            'type' => $this->adjustQuantity > 0 ? 'in' : 'out',
            'quantity' => abs($this->adjustQuantity),
            'reason' => $this->adjustReason,
            'user_id' => Auth::id(),
        ]);

        $this->adjustPartId = null;
        session()->flash('message', 'Stock ajusté avec succès.');
    }

    private function resetInput()
    {
        $this->partId = null;
        $this->part_number = '';
        $this->name = '';
        $this->compatibility = '';
        $this->purchase_price = '';
        $this->selling_price = '';
        $this->quantity_in_stock = 0;
        $this->min_stock_alert = 5;
    }
}