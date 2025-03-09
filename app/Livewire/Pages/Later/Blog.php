<?php

namespace App\Livewire\Pages\Later;

use Livewire\Attributes\Layout;
use Livewire\Component;

class Blog extends Component
{
    #[Layout('layouts.app')]
    public function render()
    {
        return view('livewire.pages.later.blog');
    }
}
