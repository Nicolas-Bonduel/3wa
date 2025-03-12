<?php

namespace App\Livewire\Pages;

use Livewire\Attributes\Layout;
use Livewire\Component;

class Product extends Component
{
    public $slug; // actually the product name, wanted to implement slugs at some point but I'm a little too lazy for this

    public function mount(string $slug)
    {
        $this->slug = $slug;
    }


    #[Layout('layouts.app')]
    public function render()
    {
        $product_master = \App\Models\Product::query()->where('name', $this->slug)->first();
        if (!$product_master) {
            abort(404);
        }
        $product_variation = $product_master->default_variation();


        return view('livewire.pages.product', compact('product_master', 'product_variation'));
    }
}
