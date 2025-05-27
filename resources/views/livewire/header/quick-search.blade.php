<div class="quick-search">
    @vite(['resources/css/components/quick-search.scss', 'resources/js/components/quick-search.js'])

    <div class="input relative">

        <!-- Loader -->
        <div wire:loading>
            <div class="livewire-loader-wrapper tiny">
                <div class="loader"></div>
            </div>
        </div>

        <input type="text" placeholder="..." wire:model.debounce.300ms.live='input'/>
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" width="24" height="24">
            <path fill="currentColor" d="M416 208c0 45.9-14.9 88.3-40 122.7L502.6 457.4c12.5 12.5 12.5 32.8 0 45.3s-32.8 12.5-45.3 0L330.7 376c-34.4 25.2-76.8 40-122.7 40C93.1 416 0 322.9 0 208S93.1 0 208 0S416 93.1 416 208zM208 352a144 144 0 1 0 0-288 144 144 0 1 0 0 288z"/>
        </svg>

    </div>

    <div class="result visible">
        @if (! is_null($products))
            <div class="flex justify-center py-2" style="color: #777777aa;">
                {{ count($products) }} résultat{{ count($products) > 1 ? "s" : "" }} {!! count($products) == $max_results ? " <small style='padding: 0 .25rem;'>(max)</small>" : "" !!} parmi {{ $total_products }}
            </div>
            @forelse($products as $product)
                @if (! $product->default_variation())
                    @continue
                @endif
                @php
                    $img_src = \Illuminate\Support\Facades\Storage::disk('public')->path('produit/' . $product->name . '/' . $product->name . '_1.jpg');
                    $rel_path = 'produit/' . $product->name . '/' . $product->name . '_1.jpg';
                @endphp
                <a class="row" href="{{ route('public.product', $product->name) }}" wire:navigate>
                    <img class="product-thumbnail" src="{{ file_exists($img_src) ? asset('storage/' . $rel_path) : asset('img/placeholder.png') }}" alt="{{ htmlspecialchars_decode($product->name) }}" width="90" height="90"/>
                    <div class="product-content">
                    <span class="product-name">
                        {{ $product->name }}
                    </span>
                        <span class="product-description">
                        <small>{{ htmlspecialchars_decode($product->description) }}</small>
                    </span>
                        <span class="product-price">
                        {{ $product->default_variation()->price }} € HT
                    </span>
                    </div>
                </a>
            @empty
                <div class="flex justify-center p-4">
                    Aucun produit ne correspond à votre recherche
                </div>
            @endforelse
        @endif
    </div>
</div>
