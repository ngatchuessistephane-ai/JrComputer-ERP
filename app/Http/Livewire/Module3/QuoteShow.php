<?php

namespace App\Http\Livewire\Module3;

use Livewire\Attributes\Layout;
use Livewire\Component;
use App\Models\Module3\Quote;

#[Layout('layouts.appProd')]
class QuoteShow extends Component
{
    public $quote;

    public function mount($id)
    {
        $this->quote = Quote::with(['customer', 'items.product'])->findOrFail($id);
    }

    public function render()
    {
        return view('livewire.module3.quote-show', [
            'quote' => $this->quote,
        ]);
    }
}