<div id="login-page">

    @vite(['resources/css/login.scss'])


    <span class="header">
        Connexion
    </span>

    <form id="login-form" wire:submit="login">

        <!-- Email Address -->
        <div class="form-group">
            <x-input-label for="email" :value="__('Email')" />

            <x-text-input wire:model.blur="email" id="email"
                          class="{{ $email ? ($errors->get('email') ? 'invalid' : 'valid') : '' }}"
                          type="text"
                          name="email"
                          autofocus autocomplete="username" />

            <x-input-error :messages="$errors->get('email')" class="validation-errors" />
        </div>

        <!-- Password -->
        <div class="form-group relative">
            <x-input-label for="password" :value="__('Mot de passe')" />

            <x-text-input wire:model.blur="password" id="password"
                          class="{{ $password ? ($errors->get('password') ? 'invalid' : 'valid') : '' }}"
                          type="password"
                          name="password"
                          autocomplete="current-password" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <button type="submit" class="btn-primary">
            {{ __('Se connecter') }}
        </button>

{{--        <div class="mt-4 flex flex-col items-center">--}}
{{--            <span class="text-gray-500">--}}
{{--                Vous n'avez pas de compte ?--}}
{{--            </span>--}}
{{--            <a href="{{ route('register') }}" class="text-red font-semibold">--}}
{{--                S'inscrire--}}
{{--            </a>--}}
{{--        </div>--}}

    </form>

</div>
