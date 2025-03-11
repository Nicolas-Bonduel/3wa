<?php

namespace App\Livewire\Pages;

use App\Services\ProductService;
use Livewire\Attributes\Layout;
use Livewire\Component;

class Home extends Component
{
    #[Layout('layouts.app')]
    public function render()
    {

        $featured_products = ProductService::get_products([
            'is_featured' => true,
            'limit' => 50
        ]);


        return view('livewire.pages.home', compact('featured_products'));

    }
}
