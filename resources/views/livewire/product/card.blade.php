<div class="product-card" href="{{ route('public.product', $product->name) }}">

    @php
        $img_src = \Illuminate\Support\Facades\Storage::disk('public')->path('produit/' . $product->name . '/' . $product->name . '_1.jpg');
        $rel_path = 'produit/' . $product->name . '/' . $product->name . '_1.jpg';
    @endphp

    <img class="thumbnail" alt="{{ $product->name }}"
         src="{{ file_exists($img_src) ? asset('storage/' . $rel_path) : asset('img/placeholder.png') }}"/>

    <span class="name">
        {{ htmlspecialchars_decode($product->name) }}
    </span>

    <span class="brand">
        {{ htmlspecialchars_decode($product->brand()->getResults()->label) }}
    </span>

    <span class="range">
        {{ htmlspecialchars_decode($product->range()->getResults()->label) }}
    </span>

    <span class="description">
        {{ htmlspecialchars_decode($product->description) }}
    </span>

{{--    @dump($product->stocks)--}}
    @php
        if (isset($product->stocks['RECOND']) && $product->stocks['RECOND'] > 0) {
            $stock = $product->stocks['RECOND'];
            $price = $product->variations()->getResults()->first(fn($v) => str_ends_with($v->sku, '_RECOND'))->price;
        }
        elseif (isset($product->stocks['NEUF']) && $product->stocks['NEUF'] > 0) {
            $stock = $product->stocks['NEUF'];
            $price = $product->variations()->getResults()->first(fn($v) => str_ends_with($v->sku, '_NEUF'))->price;
        }
        else {
            $stock = 0;
            $price = $product->default_variation()->price;
        }
    @endphp
    <span class="price">
        {{ number_format($price, 2) }} € HT
    </span>

    <span class="stock {{ $stock ? '' : 'out' }}">
        {{ $stock ? 'En stock' : 'rupture' }}
    </span>

    <button class="btn-primary fake-link" data-href="{{ route('public.product', $product->name) }}">
        Voir les détails
    </button>

</div>
