<div class="variation-content">

    @php
        $stock_extra = [
            'Reconditionné' => [
                'shipping_delay' => '24 heures ouvrées',
                'color' => '#0a9928'
            ],
            'Neuf' => [
                'shipping_delay' => '5 jours ouvrés',
                'color' => '#09f'
            ],
        ];
    @endphp


    <!-- Loader -->
    <div wire:loading>
        <div class="livewire-loader-wrapper">
            <div class="loader"></div>
        </div>
    </div>

    <!-- Price -->
    <span class="price">
        {{ number_format(htmlspecialchars_decode($product_variation->price), 2) }} € HT
    </span>

    <!-- Variation Selection -->
    <div class="variations">
        <span class="label">
            État
        </span>
        <div class="variation-select ml-2">
            @foreach($product_variations as $variation)
                <div wire:click="swap({{ $variation->id }})" class="option @if($product_variation->id == $variation->id) selected @endif">
                    {{ \App\Services\Helper::get_variation_name($variation->sku) }}
                </div>
            @endforeach
        </div>
    </div>

    <!-- Stock -->
    <div class="stock mt-3 pb-3">
        <span class="label">
            Stock
        </span>
        @php
            $stock = $product_stocks[$product_variation->id];
            $c_plural = $stock > 1 ? 's' : '';
            $extra = $stock_extra[\App\Services\Helper::get_variation_name($product_variation->sku)];
        @endphp
        <span class="block ml-2" style="color: {{ $stock > 0 ? $extra['color'] : '#777' }};">
            @if ($stock > 0)
                {{ $stock }} produit{{$c_plural}} en stock expédié{{$c_plural}} sous {{ $extra['shipping_delay'] }}
            @else
                rupture de stock
            @endif
        </span>
    </div>

    <!-- Quantity + Add to Cart -->
    @if ($stock > 0)
        <livewire:product.add-to-cart
            :product_variation="$product_variation"
            :stock="$stock"
        />
    @endif

</div>
