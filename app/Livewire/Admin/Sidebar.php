<?php

namespace App\Livewire\Admin;

use Livewire\Attributes\On;
use Livewire\Component;

class Sidebar extends Component
{

    public bool $collapsed = false;

    #[On('admin.toggle-menu')]
    public function toggleMenu()
    {
        $this->collapsed = ! $this->collapsed;
    }


    public function render()
    {
        return view('livewire.admin.sidebar');
    }
}
