<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\GrupoEconomico;

class GrupoEconomicoComponent extends Component
{
    public $nome;
    public $grupoId;
    public $editando = false;

    protected $rules = [
        'nome' => 'required|min:3|max:255',
    ];
    

    public function save()
    {
        $this->validate();

        GrupoEconomico::updateOrCreate(
            ['id' => $this->grupoId],
            ['nome' => $this->nome]
        );

        $this->reset(['nome', 'grupoId', 'editando']);
    }

    public function edit($id)
    {
        $grupo = GrupoEconomico::findOrFail($id);
        $this->grupoId = $grupo->id;
        $this->nome = $grupo->nome;
        $this->editando = true;
    }

     public function delete($id)
    {
        GrupoEconomico::destroy($id);
    }

    public function render()
    {
        return view('livewire.grupo-economico-component', [
            'grupos' => GrupoEconomico::latest()->get()
        ]);
    }
}
