<?php

use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;
use \App\Models\Customer;
use \App\Models\CustomerAddress;

new #[Layout('layouts.app')] class extends Component
{
    public string $name = '';
    public string $email = '';
    public string $password = '';
    public string $password_confirmation = '';
    public string $company = '';
    public string $country = 'FR';
    public string $address = '';
    public string $zip = '';
    public string $city = '';
    public string $siren = '';
    public string $phone = '';
    public string $vat = '';
    public bool $policy = false;


    public function rules(): array
    {
        return [
            'email' => ['required', 'email', 'lowercase', 'max:255', 'unique:'.Customer::class],
            'password' => ['required', 'min:6', 'regex:/[a-z]/', 'regex:/[A-Z]/', 'regex:/[0-9]/', 'regex:/[@$!%*#?&]/', 'confirmed'],
            'company' => ['required', 'string', 'min:2', 'max:255'],
            'country' => ['required', 'string', 'size:2'],
            'address' => ['required', 'string', 'min:4', 'max:255'],
            'zip' => ['required', 'min:2', 'max:255'],
            'city' => ['required', 'string', 'min:4', 'max:255'],
            'siren' => ['required', 'size:9'],
            'phone' => ['required', 'regex:/^(?:(?:\+|00)33|0)\s*[1-9](?:[\s.-]*\d{2}){4}$/', 'max:255'],
            'vat' => ['required', 'string', 'size:13'],
            'policy' => ['required', 'boolean', 'accepted'],
        ];
    }
    public function messages(): array
    {
        return [
            'email' => [
                'required' => "L'email est requis",
                'email' => "Le format de l'email est incorrect",
                'lowercase' => "L'email doit être écrit en minuscules",
                'max' => "L'email en doit pas dépasser 255 caractères",
                'unique' => "Cet email est déjà pris"
            ],
            'password' => [
                'required' => "Le mot de passe est requis",
                'min' => "Le mot de passe doit contenir au moins 6 caractères",
                'regex' => "Le mot de passe doit contenir au moins une minuscule, une majuscule, un chiffre et un caractère spécial",
                'confirmed' => "Le mot de passe doit être identique à sa confirmation"
            ],
            'company' => [
                'required' => "La raison sociale est requise",
                'string' => "La raison sociale doit être un texte",
                'min' => "La raison sociale doit contenir au moins 2 caractères",
                'max' => "La raison sociale ne peut pas dépasser 255 caractères"
            ],
            'country' => [
                'required' => 'Le pays est requis',
                'size:2' => "Le code pays doit contenir 2 caractères"
            ],
            'address' => [
                'required' => "L'adresse est requise",
                'string' => "L'adresse doit être un texte",
                'min' => "L'adresse doit contenir au moins 4 caractères",
                'max' => "L'adresse ne peut pas dépasser 255 caractères"
            ],
            'zip' => [
                'required' => "Le code postal est requis",
                'min' => "Le code postal doit contenir au moins 2 caractères",
                'max' => "Le code postal ne peut pas dépasser 255 caractères"
            ],
            'city' => [
                'required' => "La ville est requise",
                'string' => "La ville doit être un texte",
                'min' => "La ville doit contenir au moins 4 caractères",
                'max' => "La ville ne peut pas dépasser 255 caractères"
            ],
            'siren' => [
                'required' => "Le n° de siren est requis",
                'size' => "Le n° de siren doit contenir 9 caractères"
            ],
            'phone' => [
                'required' => "Le numéro de téléphone est requis",
                'regex' => "Le format du numéro de téléphone est incorrect",
                'max' => "Le numéro de téléphone ne peut pas dépasser 255 caractères"
            ],
            'vat' => [
                'required' => "Le numéro de TVA est requis",
                'string' => "Le numéro de TVA doit être un texte",
                'size' => "Le numéro de TVA doit contenir 13 caractères"
            ],
            'policy' => [
                'accepted' => "Veuillez accepter les conditions"
            ],
        ];
    }

    public function updated($key)
    {
        $this->validateOnly($key);
    }

    /**
     * Handle an incoming registration request.
     */
    public function register(): void
    {
        $validated = $this->validate();

        $validated['password'] = Hash::make($validated['password']);

        $new_customer = Customer::create($validated);
        $new_customer_address = CustomerAddress::create(array_merge($validated, [
            'customer_id' => $new_customer->id,
            'name' => "Facturation",
            'is_default' => true
        ]));

        event(new Registered($new_customer));

        Auth::login($new_customer);

        $this->redirect(route('customer.dashboard', absolute: false), navigate: true);
    }
}; ?>

<div id="register-page">

    @vite(['resources/css/register.scss'])


    <!-- Loader -->
    <div x-data x-show="$store.navigate.to !== null">
        <div class="livewire-loader-wrapper set-height">
            <div class="loader"></div>
        </div>
    </div>

    <span class="header">
        Créer un compte
    </span>

    <form id="register-form" wire:submit="register">

        <span class="text-red font-bold mb-3">
            Informations de connexion
        </span>

        <!-- Email Address -->
        <div class="form-group">
            <x-input-label for="email" :value="__('Email')" />

            <x-text-input wire:model.blur="email" id="email"
                          class="{{ $email ? ($errors->get('email') ? 'invalid' : 'valid') : '' }}"
                          type="text"
                          name="email"
                          autocomplete="username" />

            <x-input-error :messages="$errors->get('email')" class="validation-errors" />
        </div>

        <!-- Password -->
        <div class="form-group">
            <x-input-label for="password" :value="__('Mot de passe')" />

            <x-text-input wire:model.blur="password" id="password"
                            class="{{ $password ? ($errors->get('password') ? 'invalid' : 'valid') : '' }}"
                            type="password"
                            name="password"
                            autocomplete="new-password" />

            <x-input-error :messages="$errors->get('password')" class="validation-errors" />
        </div>

        <!-- Confirm Password -->
        <div class="form-group">
            <x-input-label for="password_confirmation" :value="__('Confirmation du mot de passe')" />

            <x-text-input wire:model.blur="password_confirmation" id="password_confirmation"
                            class="{{ $password_confirmation ? ($errors->get('password_confirmation') ? 'invalid' : 'valid') : '' }}"
                            type="password"
                            name="password_confirmation"
                            autocomplete="new-password" />

            <x-input-error :messages="$errors->get('password_confirmation')" class="validation-errors" />
        </div>


        <span class="text-red font-bold mb-3">
            Informations Compte de Facturation
        </span>
        <span class="text-red mb-3">
            Merci de renseigner les informations de votre centre de Facturation.
        </span>

        <!-- Company Name -->
        <div class="form-group">
            <x-input-label for="company" :value="__('Raison Sociale')" />

            <x-text-input wire:model.blur="company" id="company"
                          class="{{ $company ? ($errors->get('company') ? 'invalid' : 'valid') : '' }}"
                          type="text"
                          name="company"
                          autocomplete="" />

            <x-input-error :messages="$errors->get('company')" class="validation-errors" />
        </div>

        <!-- Country -->
        <div class="form-group">
            <label class="block font-medium text-sm text-gray-700 dark:text-gray-300" for="country">
                Pays (Facturation)
            </label>

            <select wire:model.blur="country"  id="country"
                          class="{{ $country ? ($errors->get('country') ? 'invalid' : 'valid') : 'valid' }}"
                          name="country"
                          autocomplete="country" >
                <option value="FR" selected>France</option>
                <option value="UK">Test</option>
            </select>

            <x-input-error :messages="$errors->get('country')" class="validation-errors" />
        </div>

        <!-- Address -->
        <div class="form-group">
            <x-input-label for="address" :value="__('Adresse (Facturation)')" />

            <x-text-input wire:model.blur="address" id="address"
                          class="{{ $address ? ($errors->get('address') ? 'invalid' : 'valid') : '' }}"
                          type="text"
                          name="address"
                          autocomplete="street-address" />

            <x-input-error :messages="$errors->get('address')" class="validation-errors" />
        </div>

        <!-- Zip Code -->
        <div class="form-group">
            <x-input-label for="zip" :value="__('Code postal (Facturation)')" />

            <x-text-input wire:model.blur="zip" id="zip"
                          class="{{ $zip ? ($errors->get('zip') ? 'invalid' : 'valid') : '' }}"
                          type="text"
                          name="zip"
                          autocomplete="postal-code" />

            <x-input-error :messages="$errors->get('zip')" class="validation-errors" />
        </div>

        <!-- City -->
        <div class="form-group">
            <x-input-label for="city" :value="__('Ville (Facturation)')" />

            <x-text-input wire:model.blur="city" id="city"
                          class="{{ $city ? ($errors->get('city') ? 'invalid' : 'valid') : '' }}"
                          type="text"
                          name="city"
                          autocomplete="address-level2" />

            <x-input-error :messages="$errors->get('city')" class="validation-errors" />
        </div>

        <!-- Siren -->
        <div class="form-group">
            <x-input-label for="siren" :value="__('N° Siren')" />

            <x-text-input wire:model.blur="siren" id="siren"
                          class="{{ $siren ? ($errors->get('siren') ? 'invalid' : 'valid') : '' }}"
                          type="text"
                          name="siren"
                          autocomplete="" />

            <x-input-error :messages="$errors->get('siren')" class="validation-errors" />
        </div>

        <!-- Phone -->
        <div class="form-group">
            <x-input-label for="phone" :value="__('Numéro de téléphone')" />

            <x-text-input wire:model.blur="phone" id="phone"
                          class="{{ $phone ? ($errors->get('phone') ? 'invalid' : 'valid') : '' }}"
                          type="text"
                          name="phone"
                          autocomplete="tel" />

            <x-input-error :messages="$errors->get('phone')" class="validation-errors" />
        </div>

        <!-- VAT Number -->
        <div class="form-group">
            <x-input-label for="vat" :value="__('N° TVA')" />

            <x-text-input wire:model.blur="vat" id="vat"
                          class="{{ $vat ? ($errors->get('vat') ? 'invalid' : 'valid') : '' }}"
                          type="text"
                          name="vat"
                          autocomplete="" />

            <x-input-error :messages="$errors->get('vat')" class="validation-errors" />
        </div>


        <!-- Policy -->
        <span><small>
            Vos données personnelles seront exclusivement utilisées pour faciliter votre expérience sur ce site web, gérer l'accès à votre compte et les autres raisons décrites dans notre politique de confidentialité.
        </small></span>
        <div class="policy">
            <label for="policy" class="inline-flex items-center">
                <input wire:model.blur="policy" id="policy"
                       type="checkbox"
                       name="policy"
                       class="rounded dark:bg-gray-900 border-gray-300 dark:border-gray-700 text-indigo-600 shadow-sm focus:ring-indigo-500 dark:focus:ring-indigo-600 dark:focus:ring-offset-gray-800">

                <span class="ms-2 text-sm text-red">J'accepte les conditions et la politique.</span>
            </label>
            <x-input-error :messages="$errors->get('policy')" class="validation-errors" />
        </div>

        <!-- Register -->
        <button type="submit" class="btn-primary relative">

            <!-- Loader -->
            <div wire:loading>
                <div class="livewire-loader-wrapper tiny">
                    <div class="loader"></div>
                </div>
            </div>

            S'inscrire

        </button>

        <!-- Login -->
        <div class="mt-4 flex flex-col items-center">
            <span class="text-gray-500">
                Vous avez déjà un compte ?
            </span>
            <a href="{{ route('login') }}" class="text-red font-semibold">
                Se connecter
            </a>
        </div>

    </form>
</div>
