<?php

namespace App\Livewire\Header;

use App\Models\ProductBrand;
use Livewire\Component;

class Navbar extends Component
{
    public function render()
    {
        $brands = ProductBrand::query()
            ->has('products') // only those which have products
            ->get()
            ->sortByDesc(fn($b) => $b->products()->count()); // ordered by products count

        return view('livewire.header.navbar', compact('brands'));
    }
}
