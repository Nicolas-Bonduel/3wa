<div id="cart-page">

    @vite(['resources/css/cart.scss'])

    @php
        $variant_extra = [
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


    <h1>
        Mon Panier
    </h1>

    @if (! $cart_items->count())
        <div class="flex justify-center">
            Vous n'avez aucun produit dans votre panier
        </div>
    @else
        <table class="cart-table">
            <thead>
                <tr>
                    <th></th>
                    <th>
                        Produit
                    </th>
                    <th>
                        Prix
                    </th>
                    <th>
                        Quantité
                    </th>
                    <th>
                        Total
                    </th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @foreach ($cart_items as $cart_item)
                    @php
                        $product = $cart_item['product'];
                        $variation_name = \App\Services\Helper::get_variation_name($product->sku);
                        $rel_path = 'produit/' . $product->name . '/' . $product->name . '_1.jpg';
                        $img_src = \Illuminate\Support\Facades\Storage::disk('public')->path('produit/' . $product->name . '/' . $product->name . '_1.jpg');
                    @endphp
                    <tr wire:key="{{ $product->id }}">
                        <td>
                            <img class="thumbnail" alt="{{ $product->name }}" src="{{ file_exists($img_src) ? asset('storage/' . $rel_path) : asset('img/placeholder.png') }}" width="92" height="92"/>
                        </td>
                        <td class="details">
                            <a href="{{ route('public.product', $product->name) }}" wire:navigate>
                                {{ $product->name }}
                            </a>
                            <span style="color: #777;">
                                <small>( état : {{ $variation_name }} )</small>
                            </span>
                            <span style="color: {{ $variant_extra[$variation_name]['color'] }};">
                                expédition sous {{ $variant_extra[$variation_name]['shipping_delay'] }}
                            </span>
                        </td>
                        <td class="price">
                            {{ number_format($cart_item['price'], 2) }} € HT
                        </td>
                        <td>
                            <livewire:cart.qty-box :product="$product" :qty="$cart_item['quantity']" wire:key="{{ $product->id }}" />
                        </td>
                        <td class="total">
                            {{ number_format($cart_item['price'] * $cart_item['quantity'], 2) }} € HT
                        </td>
                        <td class="remove">
                            <svg wire:click="remove({{ $product->id }})" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512" fill="currentColor" width="24" height="24">
                                <path d="M163.8 0L284.2 0c12.1 0 23.2 6.8 28.6 17.7L320 32l96 0c17.7 0 32 14.3 32 32s-14.3 32-32 32L32 96C14.3 96 0 81.7 0 64S14.3 32 32 32l96 0 7.2-14.3C140.6 6.8 151.7 0 163.8 0zM32 128l384 0 0 320c0 35.3-28.7 64-64 64L96 512c-35.3 0-64-28.7-64-64l0-320zm192 64c-6.4 0-12.5 2.5-17 7l-80 80c-9.4 9.4-9.4 24.6 0 33.9s24.6 9.4 33.9 0l39-39L200 408c0 13.3 10.7 24 24 24s24-10.7 24-24l0-134.1 39 39c9.4 9.4 24.6 9.4 33.9 0s9.4-24.6 0-33.9l-80-80c-4.5-4.5-10.6-7-17-7z"/>
                            </svg>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <button class="btn-primary place-order" wire:click="placeOrder">
            Passer la commande
        </button>
    @endif

</div>
