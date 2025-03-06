<div class="minicart fake-link" data-href="{{ route('public.cart') }}">

    <img src="{{ asset('img/cart_icon_36x36.webp') }}" alt="mon compte" width="36" height="36"/>
    <span>
        Mon Panier
    </span>

    <span class="counter">{{ array_reduce($cart_items->toArray(), fn($acc, $i) => $acc + $i['quantity'], 0) }}</span>

    <div class="hover-content" onclick="((e) => e.stopPropagation())(arguments[0])">
        @if ($cart_items->count())
            @foreach($cart_items as $cart_item)
                @php
                    $img_src = \Illuminate\Support\Facades\Storage::disk('public')->path('produit/' . $cart_item['product']->name . '/' . $cart_item['product']->name . '_1.jpg');
                    $rel_path = 'produit/' . $cart_item['product']->name . '/' . $cart_item['product']->name . '_1.jpg';
                @endphp
                <div class="row">

                    <img class="thumbnail" alt="{{ $cart_item['product']->name }}" src="{{ file_exists($img_src) ? asset('storage/' . $rel_path) : asset('img/placeholder.png') }}" width="92" height="92"/>

                    <div class="content">
                        <span class="fake-link name" data-href="{{ route('public.product', $cart_item['product']->name) }}">
                            {{ $cart_item['product']->name }}
                        </span>
                        <span class="price">
                            {{ $cart_item['price'] }} € HT
                        </span>
                        <span class="qty">
                            ( x{{ $cart_item['quantity'] }} )
                        </span>
                        <span class="variation">
                            <small>( état : {{ \App\Services\Helper::get_variation_name($cart_item['product']->sku) }} )</small>
                        </span>
                    </div>

                    <svg wire:click="remove({{ $cart_item['product']->id }})" class="remove" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512" fill="currentColor" width="24" height="24">
                        <path d="M163.8 0L284.2 0c12.1 0 23.2 6.8 28.6 17.7L320 32l96 0c17.7 0 32 14.3 32 32s-14.3 32-32 32L32 96C14.3 96 0 81.7 0 64S14.3 32 32 32l96 0 7.2-14.3C140.6 6.8 151.7 0 163.8 0zM32 128l384 0 0 320c0 35.3-28.7 64-64 64L96 512c-35.3 0-64-28.7-64-64l0-320zm192 64c-6.4 0-12.5 2.5-17 7l-80 80c-9.4 9.4-9.4 24.6 0 33.9s24.6 9.4 33.9 0l39-39L200 408c0 13.3 10.7 24 24 24s24-10.7 24-24l0-134.1 39 39c9.4 9.4 24.6 9.4 33.9 0s9.4-24.6 0-33.9l-80-80c-4.5-4.5-10.6-7-17-7z"/>
                    </svg>
                </div>
            @endforeach

            <span class="subtotal">
                Sous-Total : {{ $total }} € HT
            </span>

            <button class="btn-primary fake-link" data-href="{{ route('public.cart') }}">
                Voir le panier
            </button>

        @else
            <span class="flex justify-center p-4">
                Vous n'avez aucun produit dans votre panier
            </span>
        @endif
    </div>
</div>
