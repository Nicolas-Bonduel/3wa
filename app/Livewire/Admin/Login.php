<?php

namespace App\Livewire\Admin;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\ValidationException;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Validate;
use Livewire\Component;

class Login extends Component
{

    #[Validate('required', message: "L'email est requis")]
    #[Validate('string', message: "Format d'email incorrect")]
    #[Validate('email', message: "Format d'email incorrect")]
    public $email;

    #[Validate('required', message: "Le mot de passe est requis")]
    public $password;

    public function login()
    {

        $this->validate();

        if (! Auth::guard('user')->attempt($this->only(['email', 'password']), false)) {
            throw ValidationException::withMessages([
                'email' => "Authentification échouée",
            ]);
        }

        Session::regenerate();

        return redirect()->route('admin.dashboard');
    }


    #[Layout('layouts.empty')]
    public function render()
    {
        return view('livewire.admin.login');
    }
}
