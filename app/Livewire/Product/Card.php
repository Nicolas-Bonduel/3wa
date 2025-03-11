<?php

namespace App\Livewire\Product;

use App\Models\Product;
use Livewire\Component;

class Card extends Component
{
    public Product $product;
    public ?array $stocks;

    public function mount(Product $product, array $stocks = null)
    {
        $this->product = $product;
        $this->stocks = $stocks;
    }


    public function render()
    {
        return view('livewire.product.card');
    }
}
