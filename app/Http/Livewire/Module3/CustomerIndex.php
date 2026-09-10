<?php

namespace App\Http\Livewire\Module3;

use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use App\Models\Module3\Customer;

#[Layout('layouts.appProd')]
class CustomerIndex extends Component
{
    use WithPagination, WithFileUploads;

    public $search = '';
    public $showForm = false;
    public $customerId, $name, $email, $phone, $address, $tax_number;

    protected $rules = [
        'name' => 'required|string|max:255',
        'email' => 'nullable|email|unique:customers,email',
        'phone' => 'nullable|string|max:20',
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        $customers = Customer::where('name', 'like', '%'.$this->search.'%')
            ->orWhere('email', 'like', '%'.$this->search.'%')
            ->orderBy('id', 'desc')
            ->paginate(10);
        return view('livewire.module3.customer-index', ['customers' => $customers]);
    }

    public function create()
    {
        $this->resetInput();
        $this->showForm = true;
    }

    public function edit($id)
    {  
        $customer = Customer::findOrFail($id);
        $this->customerId = $customer->id;
        $this->name = $customer->name;
        $this->email = $customer->email;
        $this->phone = $customer->phone;
        $this->address = $customer->address;
        $this->tax_number = $customer->tax_number;
        $this->showForm = true;
    }

    public function save()
    {
        if ($this->customerId) {
            $this->rules['email'] = 'nullable|email|unique:customers,email,'.$this->customerId;
            $this->validate();
            Customer::find($this->customerId)->update([
                'name' => $this->name,
                'email' => $this->email,
                'phone' => $this->phone,
                'address' => $this->address,
                'tax_number' => $this->tax_number,
            ]);
        } else {
            $this->validate();
            Customer::create([
                'name' => $this->name,
                'email' => $this->email,
                'phone' => $this->phone,
                'address' => $this->address,
                'tax_number' => $this->tax_number,
            ]);
        }
        $this->resetInput();
        $this->showForm = false;
        session()->flash('message', 'Client sauvegardé.');
        $this->dispatch('scroll-to-top');
    }

    public function delete($id)
    {
        Customer::find($id)?->delete();
        session()->flash('message', 'Client supprimé.');
        $this->dispatch('scroll-to-top');
    }

    private function resetInput()
    {
        $this->customerId = null;
        $this->name = '';
        $this->email = '';
        $this->phone = '';
        $this->address = '';
        $this->tax_number = '';
    }
}