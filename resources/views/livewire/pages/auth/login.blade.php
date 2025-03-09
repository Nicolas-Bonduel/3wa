<?php

use App\Livewire\Forms\LoginForm;
use Illuminate\Support\Facades\Session;
use Livewire\Attributes\Layout;
use Livewire\Attributes\On;
use Livewire\Volt\Component;

new #[Layout('layouts.app')] class extends Component
{
    public LoginForm $form;

    public ?string $previous_url = null;
    public function mount()
    {
        $this->dispatch('get-previous-url');
    }
    #[On('set-previous-url')]
    public function setPreviousUrl($previous_url)
    {
        $this->previous_url = $previous_url;
    }

    /**
     * Handle an incoming authentication request.
     */
    public function login(): void
    {
        $this->validate();

        $this->form->authenticate();

        Session::regenerate();

        if ($this->previous_url == route('public.cart'))
            $this->redirect(route('public.cart'), navigate: true);
        else
            $this->redirectIntended(default: route('customer.dashboard', absolute: false), navigate: true);
    }
}; ?>


<div id="login-page">

    @vite(['resources/css/login.scss'])

    <span class="header">
        Connexion
    </span>

    <form id="login-form" wire:submit="login">

        <!-- Email Address -->
        <div class="form-group">
            <x-input-label for="email" :value="__('Email')" />

            <x-text-input wire:model.blur="form.email" id="email"
                          class="{{ $form->email ? ($errors->get('form.email') ? 'invalid' : 'valid') : '' }}"
                          type="text"
                          name="email"
                          autofocus autocomplete="username" />

            <x-input-error :messages="$errors->get('form.email')" class="validation-errors" />
        </div>

        <!-- Password -->
        <div class="form-group relative">
            <x-input-label for="password" :value="__('Mot de passe')" />

            <x-text-input wire:model.blur="form.password" id="password"
                            class="{{ $form->password ? ($errors->get('form.password') ? 'invalid' : 'valid') : '' }}"
                            type="password"
                            name="password"
                            autocomplete="current-password" />

            <a href="" style="position: absolute; right: 15px; bottom: 0; height: calc(100% - 1.5rem); display: flex; align-items: center;">
                <span class="text-red">
                    Oublié ?
                </span>
            </a>

            <x-input-error :messages="$errors->get('form.password')" class="mt-2" />
        </div>

        <!-- Remember Me -->
        <div class="remember-me">
            <label for="remember" class="inline-flex items-center">
                <input wire:model="form.remember" id="remember"
                       type="checkbox"
                       name="remember"
                       class="rounded dark:bg-gray-900 border-gray-300 dark:border-gray-700 text-indigo-600 shadow-sm focus:ring-indigo-500 dark:focus:ring-indigo-600 dark:focus:ring-offset-gray-800">

                <span class="ms-2 text-sm text-red">{{ __('Se souvenir de moi') }}</span>
            </label>
        </div>

        <button type="submit" class="btn-primary">
            {{ __('Se connecter') }}
        </button>

        <div class="mt-4 flex flex-col items-center">
            <span class="text-gray-500">
                Vous n'avez pas de compte ?
            </span>
            <a href="{{ route('register') }}" class="text-red font-semibold">
                S'inscrire
            </a>
        </div>

    </form>

</div>
