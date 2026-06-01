<?php

namespace App\Http\Livewire;

use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

#[Layout('layouts.appProd')]
class Profile extends Component
{
    use WithFileUploads;

    public $name;
    public $email;
    public $avatar;            // instance du fichier uploadé
    public $current_avatar;    // chemin actuel de l'avatar
    public $current_password;
    public $new_password;
    public $new_password_confirmation;

    protected function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . Auth::id(),
            'avatar' => 'nullable|image|max:2048', // 2 Mo max
        ];
    }

    protected $messages = [
        'email.unique' => 'Cet email est déjà utilisé par un autre compte.',
        'avatar.image' => 'Le fichier doit être une image (jpeg, png, bmp, gif, svg).',
        'avatar.max'   => 'L\'image ne doit pas dépasser 2 Mo.',
    ];

    public function mount()
    {
        $user = Auth::user();
        $this->name = $user->name;
        $this->email = $user->email;
        $this->current_avatar = $user->avatar;
    }

    public function updatedAvatar()
    {
        $this->validateOnly('avatar');
    }

    public function updateProfile()
    {
        $this->validate($this->rules());

        $user = Auth::user();
        $user->name = $this->name;
        $user->email = $this->email;

        // Gestion de l'avatar
        if ($this->avatar) {
            // Supprimer l'ancien avatar s'il existe
            if ($user->avatar && Storage::disk('public')->exists($user->avatar)) {
                Storage::disk('public')->delete($user->avatar);
            }
            // Stocker la nouvelle image
            $path = $this->avatar->store('avatars', 'public');
            $user->avatar = $path;
        }

        $user->save();

        // Mettre à jour la variable current_avatar pour refléter le changement immédiatement
        $this->current_avatar = $user->avatar;
        $this->reset('avatar');

        session()->flash('message', 'Profil mis à jour avec succès.');
    }

    public function updatePassword()
    {
        $this->validate([
            'current_password' => 'required|string',
            'new_password' => 'required|string|min:8|confirmed',
        ]);

        $user = Auth::user();

        if (!Hash::check($this->current_password, $user->password)) {
            $this->addError('current_password', 'Le mot de passe actuel est incorrect.');
            return;
        }

        $user->update([
            'password' => Hash::make($this->new_password),
        ]);

        $this->reset(['current_password', 'new_password', 'new_password_confirmation']);
        session()->flash('message', 'Mot de passe modifié avec succès.');
    }

    public function render()
    {
        return view('livewire.profile');
    }
}