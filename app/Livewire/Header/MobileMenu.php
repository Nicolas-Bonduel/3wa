<?php

namespace App\Livewire\Header;

use App\Models\ProductBrand;
use App\Models\ProductCategory;
use Livewire\Attributes\On;
use Livewire\Component;

class MobileMenu extends Component
{
    #[On('cart-update')]
    public function render()
    {
        $categories = ProductCategory::query()
            ->has('products') // only those which have products
            ->get()
            ->sortByDesc(fn($b) => $b->products()->count()); // ordered by products count

        $brands = ProductBrand::query()
            ->has('products') // only those which have products
            ->get()
            ->sortByDesc(fn($b) => $b->products()->count()); // ordered by products count


        return view('livewire.header.mobile-menu', compact('categories', 'brands'));
    }
}
