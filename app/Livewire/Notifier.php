<?php

namespace App\Livewire;

use Livewire\Attributes\On;
use Livewire\Component;

class Notifier extends Component
{

    public string $message;
    public string $type;
    public bool $show = false;

    public function mount()
    {
        if (session()->has('notify')) {
            $this->message = session('notify')[0];
            $this->type = count(session('notify')) > 1 ? session('notify')[1] : 'info';
            $this->show = true;
        }
    }

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
