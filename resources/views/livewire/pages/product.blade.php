<div id="product-page">

    @vite(['resources/css/product.scss'])


    <!-- Gallery -->
    <x-product-gallery product="{{ $product_master->name }}" />

    <!-- Product Content -->
    <div class="product-content">

        <!-- Master Content -->
        <div class="master-content">
            <div class="product-details">
                <span class="name">
                    {{ htmlspecialchars_decode($product_master->name) }}
                </span>
                <span class="brand">
                    {{ htmlspecialchars_decode($product_master->brand()->getResults()->label) }}
                </span>
                <span class="range">
                    {{ htmlspecialchars_decode($product_master->range()->getResults()->label) }}
                </span>
                <span class="description">
                    <small>{{ htmlspecialchars_decode($product_master->description) }}</small>
                </span>
            </div>
        </div>

        <!-- Variation Content -->
        <livewire:product.variation-content
            :product_master="$product_master"
            :product_variation="$product_variation"
        />

    </div>

    <!-- Extra -->
    <div class="extra-labels">
        <div class="inner">
            <div class="row">
                <img alt="label RecQ" src="{{ asset('img/picto_recq.svg') }}" width="33" height="33"/>
                <span>
                    Label RecQ
                </span>
            </div>
            <div class="row">
                <img alt="label RecQ" src="{{ asset('img/picto_warranty.svg') }}" width="33" height="33"/>
                <span>
                    Garantie 2 ans
                </span>
            </div>
            <div class="row">
                <img alt="label RecQ" src="{{ asset('img/picto_shipping.svg') }}" width="33" height="33"/>
                <span>
                    Livraison par UPS, FEDEX
                </span>
            </div>
            <div class="row">
                <img alt="label RecQ" src="{{ asset('img/picto_payment.svg') }}" width="33" height="33"/>
                <span>
                    Paiement Sécurisé
                </span>
            </div>
        </div>
    </div>

</div>

