<?php

namespace App\Livewire\Pages\Later;

use Livewire\Attributes\Layout;
use Livewire\Component;

class PrivacyPolicy extends Component
{
    #[Layout('layouts.app')]
    public function render()
    {
        return view('livewire.pages.later.privacy-policy');
    }
}
