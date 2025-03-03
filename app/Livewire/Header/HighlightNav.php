<?php

namespace App\Livewire\Header;

use App\Models\ProductCategory;
use Livewire\Component;

class HighlightNav extends Component
{
    public function render()
    {
        $categories = ProductCategory::all();

        return view('livewire.header.highlight-nav', compact('categories'));
    }
}
