<?php

namespace App\Livewire\Header;

use App\Facades\Cart;
use Livewire\Attributes\On;
use Livewire\Component;

class Minicart extends Component
{

    public function remove(int $id)
    {
        Cart::remove($id);
    }


    #[On('cart-update')]
    public function render()
    {
        $cart_items = Cart::content();
        $total = Cart::total();

        return view('livewire.header.minicart', compact('cart_items', 'total'));
    }
}
