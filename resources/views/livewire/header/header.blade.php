<header id="header">

    @vite(['resources/css/header.scss', 'resources/js/header.js'])


    {{-- Mobile Menu --}}
    <livewire:header.mobile-menu />

    {{-- Site Logo --}}
    <a class="site-logo" href="{{ route('public.home') }}" wire:navigate>
        <picture>
            <source media="(max-width: 767px)" srcset="{{ asset('img/logo.png') }}" width="150" height="93">
            <source media="(min-width: 768px)" srcset="{{ asset('img/logo.png') }}" width="200" height="124">
            <img src="{{ asset('img/logo.png') }}" alt="My Electronics" width="150" height="93">
        </picture>
    </a>

    {{-- My Account (desktop only) --}}
    <livewire:header.my-account />

    {{-- My Cart (desktop only) --}}
    <livewire:header.minicart />

    {{-- RecQ Logo --}}
    <a class="recq-logo" href="{{ route('public.home') }}" wire:navigate>
        <img src="{{ asset('svg/RecQ_label.svg') }}" alt="icone label recQ" width="84" height="75">
        <div class="hover-content">
            Produits certifiés Reconditionnement de Qualité, garantis 2 ans
        </div>
    </a>

    {{-- Quick Search --}}
    <div class="quick-search-wrapper">
        <livewire:header.quick-search />
    </div>

    {{-- Nav (desktop only) --}}
    <div class="w-full" style="order: 8;">
        <livewire:header.highlight-nav />
        <livewire:header.navbar />
    </div>

</header>
