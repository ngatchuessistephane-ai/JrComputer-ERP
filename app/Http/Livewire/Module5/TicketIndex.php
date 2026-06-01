<?php

namespace App\Http\Livewire\Module5;

use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Module5\SavTicket;

#[Layout('layouts.appProd')]
class TicketIndex extends Component
{
    use WithPagination;

    public $search = '';
    public $statusFilter = '';
    public $priorityFilter = '';

    protected $queryString = ['search', 'statusFilter', 'priorityFilter'];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        $tickets = SavTicket::with('customer', 'technician')
            ->when($this->search, function ($query) {
                $query->where('ticket_number', 'like', '%'.$this->search.'%')
                      ->orWhereHas('customer', fn($q) => $q->where('name', 'like', '%'.$this->search.'%'));
            })
            ->when($this->statusFilter, fn($q) => $q->where('status', $this->statusFilter))
            ->when($this->priorityFilter, fn($q) => $q->where('priority', $this->priorityFilter))
            ->orderBy('id', 'desc')
            ->paginate(10);

        return view('livewire.module5.ticket-index', ['tickets' => $tickets]);
    }

    public function delete($id)
    {
        if (!auth()->user()->can('delete sav tickets')) {
            abort(403);
        }
        SavTicket::find($id)?->delete();
        session()->flash('message', 'Ticket supprimé.');
    }
}