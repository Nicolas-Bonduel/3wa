<?php

namespace App\Livewire\Pages;

use Livewire\Attributes\Layout;
use Livewire\Attributes\On;
use Livewire\Component;
use App\Facades\Cart as Cart_;

class Cart extends Component
{

    public function remove($id)
    {
        Cart_::remove($id);
        $this->dispatch('cart-update');
    }


    #[Layout('layouts.app')]
    #[On('cart-update')]
    public function render()
    {
        $cart_items = Cart_::content();
        $total = Cart_::total();


        return view('livewire.pages.cart', compact('cart_items', 'total'));
    }
}
