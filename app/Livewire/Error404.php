<?php

namespace App\Livewire;

use Livewire\Attributes\Layout;
use Livewire\Component;

class Error404 extends Component
{
    #[Layout('layouts.app')]
    public function render()
    {
        return view('errors.404');
    }

}
