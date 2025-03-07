<?php

namespace App\Livewire\Admin;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Livewire\Attributes\On;
use Livewire\Attributes\Validate;
use Livewire\Component;

class AddUserForm extends Component
{

    #[Validate('required', message: "L'email est requis")]
    #[Validate('string', message: "Format d'email incorrect")]
    #[Validate('email', message: "Format d'email incorrect")]
    #[Validate('unique:'.User::class, message: "Cet email est déjà pris")]
    public string $email = '';

    #[Validate('required', message: "Le mot de passe est requis")]
    #[Validate('confirmed', message: "Le mot de passe doit être identique à sa confirmation")]
    public string $password = '';
    public string $password_confirmation = '';

    #[Validate('required', message: "Le rôle est requis")]
    #[Validate('in:user,admin', message: "Le rôle est invalide")]
    public string $role = 'user';

    public bool $visible = false;


    #[On('admin.show-add-user-form')]
    public function showForm()
    {
        $this->visible = true;
    }

    public function hideForm()
    {
        $this->visible = false;
    }

    public function addUser()
    {
        $validated = $this->validate();

        $validated['password'] = Hash::make($validated['password']);

        User::create($validated);

        $this->hideForm();
        $this->dispatch('admin.refresh-users');
    }


    public function render()
    {
        return view('livewire.admin.add-user-form');
    }
}
