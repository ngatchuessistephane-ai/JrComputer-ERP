<?php

namespace App\Http\Livewire;

use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;

#[Layout('layouts.appProd')]
class UserIndex extends Component
{
    use WithPagination;

    public $search = '';
    public $showForm = false;
    public $userId, $name, $email, $password, $password_confirmation, $role;

    protected $rules = [
        'name' => 'required|string|max:255',
        'email' => 'required|email|unique:users,email',
        'password' => 'required|string|min:8|confirmed',
        'role' => 'required|exists:roles,name',
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        $users = User::where('name', 'like', '%'.$this->search.'%')
            ->orWhere('email', 'like', '%'.$this->search.'%')
            ->paginate(10);
        $roles = Role::all();

        return view('livewire.user-index', [
            'users' => $users,
            'roles' => $roles,
        ]);
    }

    public function create()
    {
        $this->resetInput();
        $this->showForm = true;
    }

    public function edit($id)
    {
        $user = User::findOrFail($id);
        $this->userId = $user->id;
        $this->name = $user->name;
        $this->email = $user->email;
        $this->role = $user->roles->first()?->name;
        $this->showForm = true;
        $this->reset(['password', 'password_confirmation']);
    }

    public function save()
    {
        if ($this->userId) {
            $this->rules['email'] = 'required|email|unique:users,email,'.$this->userId;
            $this->rules['password'] = 'nullable|string|min:8|confirmed';
            $this->validate();

            $user = User::find($this->userId);
            $user->name = $this->name;
            $user->email = $this->email;
            if ($this->password) {
                $user->password = Hash::make($this->password);
            }
            $user->save();
            $user->syncRoles($this->role);
        } else {
            $this->validate();
            $user = User::create([
                'name' => $this->name,
                'email' => $this->email,
                'password' => Hash::make($this->password),
            ]);
            $user->assignRole($this->role);
        }

        $this->resetInput();
        $this->showForm = false;
        session()->flash('message', 'Utilisateur sauvegardé.');
    }

    public function delete($id)
    {
        $user = User::find($id);
        if ($user && $user->id !== auth()->id()) {
            $user->delete();
            session()->flash('message', 'Utilisateur supprimé.');
        } else {
            session()->flash('error', 'Vous ne pouvez pas supprimer votre propre compte.');
        }
    }

    private function resetInput()
    {
        $this->userId = null;
        $this->name = '';
        $this->email = '';
        $this->password = '';
        $this->password_confirmation = '';
        $this->role = '';
    }
}