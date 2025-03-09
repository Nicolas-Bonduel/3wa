<?php

namespace App\Livewire\Pages\Later;

use Livewire\Attributes\Layout;
use Livewire\Component;

class TermsOfSale extends Component
{
    #[Layout('layouts.app')]
    public function render()
    {
        return view('livewire.pages.later.terms-of-sale');
    }
}
