<a class="product-card" href="{{ route('public.product', $product->name) }}" wire:navigate>

    @php
        $img_src = \Illuminate\Support\Facades\Storage::disk('public')->path('produit/' . $product->name . '/' . $product->name . '_1.webp');
        $rel_path = 'produit/' . $product->name . '/' . $product->name . '_1.webp';
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

    @php
        $stocks = $stocks ?? $product->stocks;
        if (isset($stocks['RECOND']) && $stocks['RECOND'] > 0) {
            $stock = $stocks['RECOND'];
            $price = $product->variations()->getResults()->first(fn($v) => str_ends_with($v->sku, '_RECOND'))->price;
        }
        elseif (isset($stocks['NEUF']) && $stocks['NEUF'] > 0) {
            $stock = $stocks['NEUF'];
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

</a>
