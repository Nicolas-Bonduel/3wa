<?php

namespace App\Livewire\Header;

use App\Facades\Cart;
use Livewire\Attributes\On;
use Livewire\Component;

class Minicart extends Component
{

    /**
     * Removes product variation from cart
     * @param int $id - product variation id
     * @return void
     */
    public function remove(int $id)
    {
        Cart::remove($id);

        $this->dispatch('cart-update');
    }


    #[On('cart-update')]
    public function render()
    {
        $cart_items = Cart::content();
        $total = Cart::total();

        return view('livewire.header.minicart', compact('cart_items', 'total'));
    }
}
