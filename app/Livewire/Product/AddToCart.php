<?php

namespace App\Livewire\Product;

use App\Models\ProductVariation;
use App\Facades\Cart;
use Livewire\Attributes\On;
use Livewire\Component;

class AddToCart extends Component
{
    public ProductVariation $product_variation; // product (variation) to be added to cart
    public int $stock;                          // product (variation) stock

    public int $qty = 1;                        // quantity box current quantity
    public int $remaining_stock;                // product (variation) remaining stock (= stock - quantity in cart)

    /**
     * @param ProductVariation $product_variation - product (variation) to be added to cart
     * @param int $stock - product (variation) stock
     * @return void
     */
    public function mount(ProductVariation $product_variation, int $stock)
    {
        $this->product_variation = $product_variation;
        $this->stock = $stock;
    }

    /**
     * Decrease product variation quantity (by 1)
     * @return void
     */
    public function decrease()
    {
        if ($this->qty > 1)
            $this->qty--;
    }
    /**
     * Increase product variation quantity (by 1)
     * @return void
     */
    public function increase()
    {
        if ($this->qty < $this->stock)
            $this->qty++;
    }

    /**
     * Add product variation to cart
     * @return void
     */
    public function add_to_cart()
    {
        if ($this->qty < 1) {
            $this->dispatch('notify', 'Quantité à zéro', 'error');
            return;
        }
        if ($this->qty > $this->remaining_stock) {
            $this->dispatch('notify', "Ce produit n'a plus de stock", 'info');
            return;
        }

        Cart::add($this->product_variation, $this->qty);
        $this->dispatch('cart-update');
        $this->dispatch('notify', 'Produit ajouté au panier', 'success');
    }


    #[On('cart-update')]
    public function render()
    {
        // remaining sotck computation
        $cart_items = Cart::content();
        $product_in_cart = $cart_items->get($this->product_variation->id);
        if ($product_in_cart)
            $this->remaining_stock = $this->stock - $product_in_cart->get('quantity');
        else
            $this->remaining_stock = $this->stock;

        return view('livewire.product.add-to-cart');
    }
}
