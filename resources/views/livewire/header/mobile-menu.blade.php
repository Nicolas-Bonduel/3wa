<div id="mobile-menu">

    {{-- Button --}}
    <svg class="toggle" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512" width="24" height="24">
        <path fill="currentColor" d="M0 96C0 78.3 14.3 64 32 64l384 0c17.7 0 32 14.3 32 32s-14.3 32-32 32L32 128C14.3 128 0 113.7 0 96zM0 256c0-17.7 14.3-32 32-32l384 0c17.7 0 32 14.3 32 32s-14.3 32-32 32L32 288c-17.7 0-32-14.3-32-32zM448 416c0 17.7-14.3 32-32 32L32 448c-17.7 0-32-14.3-32-32s14.3-32 32-32l384 0c17.7 0 32 14.3 32 32z"/>
    </svg>

    {{-- Menu --}}
    <div class="menu">

        <div class="header">
            <svg class="close-btn" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512" width="24" height="24">
                <path fill="currentColor" d="M9.4 233.4c-12.5 12.5-12.5 32.8 0 45.3l160 160c12.5 12.5 32.8 12.5 45.3 0s12.5-32.8 0-45.3L109.2 288 416 288c17.7 0 32-14.3 32-32s-14.3-32-32-32l-306.7 0L214.6 118.6c12.5-12.5 12.5-32.8 0-45.3s-32.8-12.5-45.3 0l-160 160z"/>
            </svg>
            <span>
                {{ __("Menu") }}
            </span>
        </div>

        <ul class="content">
            <li>
                <a href="{{ route('public.home') }}" wire:navigate>
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512" width="15" height="15">
                        <path fill="currentColor" d="M575.8 255.5c0 18-15 32.1-32 32.1l-32 0 .7 160.2c0 2.7-.2 5.4-.5 8.1l0 16.2c0 22.1-17.9 40-40 40l-16 0c-1.1 0-2.2 0-3.3-.1c-1.4 .1-2.8 .1-4.2 .1L416 512l-24 0c-22.1 0-40-17.9-40-40l0-24 0-64c0-17.7-14.3-32-32-32l-64 0c-17.7 0-32 14.3-32 32l0 64 0 24c0 22.1-17.9 40-40 40l-24 0-31.9 0c-1.5 0-3-.1-4.5-.2c-1.2 .1-2.4 .2-3.6 .2l-16 0c-22.1 0-40-17.9-40-40l0-112c0-.9 0-1.9 .1-2.8l0-69.7-32 0c-18 0-32-14-32-32.1c0-9 3-17 10-24L266.4 8c7-7 15-8 22-8s15 2 21 7L564.8 231.5c8 7 12 15 11 24z"/>
                    </svg>
                    <span>
                        {{ __("Accueil") }}
                    </span>
                </a>
            </li>
            @if (isset($categories) && count($categories))
                <li>
                    <input id="dropdown-input-1" class="dropdown-input" type="checkbox"/>
                    <label for="dropdown-input-1">
                        <span class="flex items-center">
                            <svg class="mr-2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" width="15" height="15">
                                <path fill="currentColor" d="M64 144a48 48 0 1 0 0-96 48 48 0 1 0 0 96zM192 64c-17.7 0-32 14.3-32 32s14.3 32 32 32l288 0c17.7 0 32-14.3 32-32s-14.3-32-32-32L192 64zm0 160c-17.7 0-32 14.3-32 32s14.3 32 32 32l288 0c17.7 0 32-14.3 32-32s-14.3-32-32-32l-288 0zm0 160c-17.7 0-32 14.3-32 32s14.3 32 32 32l288 0c17.7 0 32-14.3 32-32s-14.3-32-32-32l-288 0zM64 464a48 48 0 1 0 0-96 48 48 0 1 0 0 96zm48-208a48 48 0 1 0 -96 0 48 48 0 1 0 96 0z"/>
                            </svg>
                            <span>
                                {{ __("Nos Catégories") }}
                            </span>
                            <svg class="ml-auto" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 320 512" width="18" height="18">
                                <path fill="currentColor" d="M278.6 233.4c12.5 12.5 12.5 32.8 0 45.3l-160 160c-12.5 12.5-32.8 12.5-45.3 0s-12.5-32.8 0-45.3L210.7 256 73.4 118.6c-12.5-12.5-12.5-32.8 0-45.3s32.8-12.5 45.3 0l160 160z"/>
                            </svg>
                        </span>
                        <span class="dropdown-content">
                            @php $n = 0; @endphp
                            @foreach ($categories as $cat)
                                @php $n++; @endphp
                                <input id="dropdown-input-1-{{ $n }}" class="dropdown-input" type="checkbox"/>
                                <label for="dropdown-input-1-{{ $n }}" class="li ml-2 @if (in_array($n, [2,3])) lh-2 @else lh-1 @endif" style="font-weight: normal;">
                                    <span class="flex items-center justify-between">
                                        <span>
                                            {{ htmlspecialchars_decode($cat->label) }}
                                        </span>
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 320 512" width="18" height="18">
                                            <path fill="currentColor" d="M278.6 233.4c12.5 12.5 12.5 32.8 0 45.3l-160 160c-12.5 12.5-32.8 12.5-45.3 0s-12.5-32.8 0-45.3L210.7 256 73.4 118.6c-12.5-12.5-12.5-32.8 0-45.3s32.8-12.5 45.3 0l160 160z"/>
                                        </svg>
                                    </span>
                                    <span class="dropdown-content">
                                        @foreach ($cat->subcategories()->getResults() as $subcat)
                                            <a class="li"
                                               href="/products?filters={{ http_build_query(['subcategory' => [$subcat->id => true]]) }}"
                                               wire:navigate
                                            >
                                                {{ htmlspecialchars_decode($subcat->label) }}
                                            </a>
                                        @endforeach
                                    </span>
                                </label>
                            @endforeach
                        </span>
                    </label>
                </li>
            @endif
            @if (isset($brands) && count($brands))
                <li>
                    <input id="dropdown-input-2" class="dropdown-input" type="checkbox"/>
                    <label for="dropdown-input-2">
                        <span class="flex items-center">
                            <svg class="mr-2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" width="15" height="15">
                                <path fill="currentColor" d="M64 144a48 48 0 1 0 0-96 48 48 0 1 0 0 96zM192 64c-17.7 0-32 14.3-32 32s14.3 32 32 32l288 0c17.7 0 32-14.3 32-32s-14.3-32-32-32L192 64zm0 160c-17.7 0-32 14.3-32 32s14.3 32 32 32l288 0c17.7 0 32-14.3 32-32s-14.3-32-32-32l-288 0zm0 160c-17.7 0-32 14.3-32 32s14.3 32 32 32l288 0c17.7 0 32-14.3 32-32s-14.3-32-32-32l-288 0zM64 464a48 48 0 1 0 0-96 48 48 0 1 0 0 96zm48-208a48 48 0 1 0 -96 0 48 48 0 1 0 96 0z"/>
                            </svg>
                            <span>
                                {{ __("Nos Marques") }}
                            </span>
                            <svg class="ml-auto" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 320 512" width="18" height="18">
                                <path fill="currentColor" d="M278.6 233.4c12.5 12.5 12.5 32.8 0 45.3l-160 160c-12.5 12.5-32.8 12.5-45.3 0s-12.5-32.8 0-45.3L210.7 256 73.4 118.6c-12.5-12.5-12.5-32.8 0-45.3s32.8-12.5 45.3 0l160 160z"/>
                            </svg>
                        </span>
                        <span class="dropdown-content">
                            @php $n = 0; @endphp
                            @foreach ($brands as $brand)
                                @php $n++; @endphp
                                <a class="li @if ($n < 10) font-bold @endif"
                                   href="/products?filters={{ http_build_query(['brand' => [$brand->id => true]]) }}"
                                   wire:navigate
                                >
                                    {{ htmlspecialchars_decode($brand->label) }}
                                </a>
                            @endforeach
                        </span>
                    </label>
                </li>
            @endif
            <li>
                <a href="{{ route('public.home') }}" wire:navigate>
                    {{ __("Qui sommes-nous ?") }}
                </a>
            </li>
            <li>
                <a href="{{ route('public.home') }}" wire:navigate>
                    {{ __("Blog") }}
                </a>
            </li>
            <li class="secondary">
                <a href="{{ route('customer.dashboard') }}" wire:navigate>
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512" width="15" height="15">
                        <path fill="currentColor" d="M224 256A128 128 0 1 0 224 0a128 128 0 1 0 0 256zm-45.7 48C79.8 304 0 383.8 0 482.3C0 498.7 13.3 512 29.7 512l388.6 0c16.4 0 29.7-13.3 29.7-29.7C448 383.8 368.2 304 269.7 304l-91.4 0z"/>
                    </svg>
                    <span>
                        @if(auth()->user())
                            {{ __("Mon Compte") }}
                        @else
                            {{ __("Connexion") }}
                        @endif
                    </span>
                </a>
            </li>
            <li class="secondary">
                <a href="{{ route('public.cart') }}" wire:navigate class="relative">
                    <svg class="relative" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512" width="15" height="15">
                        <path fill="currentColor" d="M0 24C0 10.7 10.7 0 24 0L69.5 0c22 0 41.5 12.8 50.6 32l411 0c26.3 0 45.5 25 38.6 50.4l-41 152.3c-8.5 31.4-37 53.3-69.5 53.3l-288.5 0 5.4 28.5c2.2 11.3 12.1 19.5 23.6 19.5L488 336c13.3 0 24 10.7 24 24s-10.7 24-24 24l-288.3 0c-34.6 0-64.3-24.6-70.7-58.5L77.4 54.5c-.7-3.8-4-6.5-7.9-6.5L24 48C10.7 48 0 37.3 0 24zM128 464a48 48 0 1 1 96 0 48 48 0 1 1 -96 0zm336-48a48 48 0 1 1 0 96 48 48 0 1 1 0-96z"/>
                    </svg>
                    <span class="cart-count">
                        {{ array_reduce(\App\Facades\Cart::content()->toArray(), fn($acc, $i) => $acc + $i['quantity'], 0) }}
                    </span>
                    <span>
                        {{ __("Mon Panier") }}
                    </span>
                </a>
            </li>
        </ul>

    </div>

    {{-- Overlay --}}
    <div class="overlay"></div>

</div>
