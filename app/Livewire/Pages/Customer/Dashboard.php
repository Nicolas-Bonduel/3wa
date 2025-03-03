<?php

namespace App\Livewire\Pages\Customer;

use Livewire\Attributes\Layout;
use Livewire\Component;

class Dashboard extends Component
{
    #[Layout('layouts.app')]
    public function render()
    {
        return view('livewire.pages.customer.dashboard');
    }
}
