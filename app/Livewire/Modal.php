<?php

namespace App\Http\Livewire;

use Livewire\Component;

class Modal extends Component
{
    public string $modalContent = '';
    public string $modalTitle = '';

    protected $listeners = ['openModal' => 'setModalContent'];

    public function setModalContent(string $content, string $title): void
    {
        $this->modalContent = $content;
        $this->modalTitle = $title;
    }

    public function render()
    {
        return view('livewire.modal');
    }
}
