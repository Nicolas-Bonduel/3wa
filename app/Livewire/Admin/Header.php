<?php

namespace App\Livewire\Admin;

use Livewire\Component;

class Header extends Component
{

    public function toggleMenu()
    {
        $this->dispatch('admin.toggle-menu');
    }


    public function render()
    {
        return view('livewire.admin.header');
    }
}
