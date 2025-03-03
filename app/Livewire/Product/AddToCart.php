<?php

namespace App\Livewire\Product;

use App\Models\ProductVariation;
use App\Facades\Cart;
use Livewire\Component;

class AddToCart extends Component
{
    public ProductVariation $product_variation;
    public int $stock;

    public int $qty = 1;

    public function mount(ProductVariation $product_variation, int $stock)
    {
        $this->product_variation = $product_variation;
        $this->stock = $stock;
    }

    public function decrease()
    {
        if ($this->qty > 1)
            $this->qty--;
    }
    public function increase()
    {
        if ($this->qty < $this->stock)
            $this->qty++;
    }

    public function add_to_cart()
    {
        if ($this->qty < 1 || $this->qty > $this->stock)
            return;

        Cart::add($this->product_variation, $this->qty);
        $this->dispatch('cart-update');
    }


    public function render()
    {
        return view('livewire.product.add-to-cart');
    }
}
