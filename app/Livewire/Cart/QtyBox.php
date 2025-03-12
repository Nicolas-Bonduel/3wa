<?php

namespace App\Livewire\Cart;

use App\Facades\Cart;
use App\Models\ProductVariation;
use Livewire\Component;

class QtyBox extends Component
{
    public ProductVariation $product;   // product (variation) in cart associated with quantity box
    public int $stock;                  // product (variation) stock

    public int $qty;                    // quantity box current quantity

    private bool $mounted = false;      // first render notifier

    /**
     * @param ProductVariation $product - product variation in cart associated with quantity box
     * @param int $qty - initial quantity
     * @return void
     */
    public function mount(ProductVariation $product, int $qty = 1)
    {
        $this->product = $product;
        $this->qty = $qty;
        $this->stock = $product->stock(); // ouch..
        $this->mounted = true;
    }

    /**
     * Decrease product variation quantity in cart (by 1)
     * @return void
     */
    public function decrease()
    {
        if ($this->qty <= 1)
            return;

        $this->qty--;
        Cart::update($this->product->id, $this->qty);
        $this->dispatch('cart-update');
        $this->dispatch('notify', 'Panier mis à jour', 'success');
    }

    /**
     * Increase product variation quantity in cart (by 1)
     * @return void
     */
    public function increase()
    {
        if ($this->qty >= $this->stock)
            return;

        $this->qty++;
        Cart::update($this->product->id, $this->qty);
        $this->dispatch('cart-update');
        $this->dispatch('notify', 'Panier mis à jour', 'success');
    }


    public function render()
    {
        // attempt to update cart when manually inputting quantity (not first render means manual input)
        if (! $this->mounted && $this->qty > 0 && $this->qty <= $this->stock) {
            Cart::update($this->product->id, $this->qty);
            $this->dispatch('cart-update');
        }

        return view('livewire.cart.qty-box');
    }
}
