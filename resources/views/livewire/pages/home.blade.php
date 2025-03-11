<div id="homepage">

    @vite(['resources/css/homepage.scss', 'resources/css/products.scss'])


    <!-- Loader -->
    <div x-data x-show="$store.navigate.to !== null">
        <div class="livewire-loader-wrapper set-height">
            <div class="loader"></div>
        </div>
    </div>

    <section class="banner">
        <img class="banner-mobile" alt="banner" src="{{ asset('img/banner.webp') }}" />
        <a class="banner-desktop" href="{{ route('public.blog') }}" wire:navigate>
            <img alt="banner" src="{{ asset('img/banner_desktop.webp') }}" />
        </a>
    </section>

    <section class="top-sales">

        <img alt="icone top ventes" src="{{ asset('img/icon-top-ventes_150x150.webp') }}" width="150" height="150"/>
        <h1>Top Ventes</h1>
        <p class="sub-text">
            Ne passez pas à côté des meilleures références de pièces électroniques industrielles !
        </p>

        <div class="products-listing">
            @php $n = 0; @endphp
            @foreach ($featured_products as $product)
                @php
                    $n++;
                    $stocks = $product->stocks;
                @endphp
                <div wire:key="slot-{{ $product->id }}" class="slot">
                    @if ($n <= 6)
                        <livewire:product.card wire:key="product-{{ $product->id }}" :product="$product" />
                    @else
                        <livewire:product.card lazy wire:key="product-{{ $product->id }}" :product="$product" :stocks="$stocks" />
                    @endif
                </div>
            @endforeach
        </div>

    </section>

    <section class="activities">

        <img alt="icone nos activites" src="{{ asset('img/icon-nos-activites_75x71.webp') }}" width="75" height="71"/>
        <h1>Nos Activités</h1>
        <p class="sub-text">
            My Electronics, spécialiste en reconditionnement, distribution et vente de matériel électronique pour l'industrie <br/>
            Nos engagements : qualité et réactivité garanties par le label RecQ* <br />
            (Reconditionnement de Qualité)
        </p>

        <div class="articles-wrapper">
            <article>
                <img alt="collecter" src="{{ asset('img/collecter_500x349.webp') }}"/>
                <h2 class="text-red">
                    Collecter
                </h2>
                <p>
                    My Electronics rachète vos équipements inutilisés ou usagés, en faible ou grande quantité.
                </p>
            </article>

            <article>
                <img alt="reconditionner" src="{{ asset('img/reconditionner__500x349.webp') }}"/>
                <h2 class="text-red">
                    Reconditionner
                </h2>
                <p>
                    Reconditionnement de pièces et équipements électroniques industriels, selon les normes strictes du label RecQ et garanties 2 ans.
                </p>
            </article>

            <article>
                <img alt="distribuer" src="{{ asset('img/distribuer_500x349.webp') }}"/>
                <h2 class="text-red">
                    Distribuer
                </h2>
                <p>
                    Professionnel de la distribution de pièces neuves et reconditionnées, garanties 2 ans.
                </p>
            </article>
        </div>

    </section>

</div>
