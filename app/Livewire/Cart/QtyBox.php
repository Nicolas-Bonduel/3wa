<?php

namespace App\Livewire\Cart;

use App\Facades\Cart;
use App\Models\ProductVariation;
use Livewire\Component;

class QtyBox extends Component
{
    public ProductVariation $product;
    public int $stock;

    public int $qty;

    private bool $mounted = false;

    public function mount(ProductVariation $product, int $qty = 1)
    {
        $this->product = $product;
        $this->qty = $qty;
        $this->stock = $product->stock(); // ouch..
        $this->mounted = true;
    }

    public function decrease()
    {
        if ($this->qty <= 1)
            return;

        $this->qty--;
        Cart::update($this->product->id, $this->qty);
        $this->dispatch('cart-update');
        $this->dispatch('notify', 'Panier mis à jour ('.$this->qty.')', 'success');
    }
    public function increase()
    {
        if ($this->qty >= $this->stock)
            return;

        $this->qty++;
        Cart::update($this->product->id, $this->qty);
        $this->dispatch('cart-update');
        $this->dispatch('notify', 'Panier mis à jour ('.$this->qty.')', 'success');
    }


    public function render()
    {
        if (! $this->mounted && $this->qty > 0 && $this->qty <= $this->stock) {
            Cart::update($this->product->id, $this->qty);
            $this->dispatch('cart-update');
        }

        return view('livewire.cart.qty-box');
    }
}
