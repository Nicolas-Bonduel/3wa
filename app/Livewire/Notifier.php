<?php

namespace App\Livewire;

use Livewire\Attributes\On;
use Livewire\Component;

class Notifier extends Component
{

    public string $message;
    public string $type;
    public bool $show = false;

    #[On('notify')]
    public function show(string $message, string $type = 'info')
    {
        $this->message = $message;
        $this->type = $type;
        $this->show = true;
        $this->dispatch('hide-notify', 3500);
    }


    public function render()
    {
        return view('livewire.notifier');
    }
}
