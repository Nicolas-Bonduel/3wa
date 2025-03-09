<div class="my-account">

    <img class="fake-link" data-href="{{ route('customer.dashboard') }}" src="{{ asset('img/my_account_icon_36x36.webp') }}" alt="mon compte" width="36" height="36"/>
    <span>
        Mon Compte
    </span>

    <div class="hover-content">
        @if (auth()->user())
            <a href="{{ route('customer.dashboard') }}" wire:navigate>
                Mes informations
            </a>
            <a href="{{ route('customer.orders') }}" wire:navigate>
                Mes commandes
            </a>
            <a href="{{ route('customer.addresses') }}" wire:navigate>
                Carnet d'adresses
            </a>
            <a href="{{ route('customer.dashboard') }}" wire:navigate>
                Changer mon mot de passe
            </a>
            <a href="{{ route('logout') }}" class="text-red">
                Déconnexion
            </a>
        @else
            <a href="{{ route('login') }}" wire:navigate>
                Connexion
            </a>
        @endif
    </div>
</div>
