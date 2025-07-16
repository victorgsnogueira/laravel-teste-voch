<?php

namespace App\Livewire\GrupoEconomico;

use App\Models\GrupoEconomico;
use Livewire\Attributes\On;
use Livewire\Component;
use Spatie\Activitylog\Facades\Activity;

class ModalGrupoEconomico extends Component
{
    public string $nome = '';
    public ?GrupoEconomico $grupoEconomico = null;

    public function updateGrupoEconomico(): void
    {
        try {
            $grupo = GrupoEconomico::find($this->grupoEconomico->id);
            $grupo->nome = $this->nome;
            $grupo->save();
            
            Activity::performedOn($grupo)
                ->causedBy(auth()->user())
                ->withProperties([
                    'nome' => $grupo->nome
                ])
                ->log('Atualizou um colaborador');

            $this->resetForm();
            $this->dispatch('close-modal', name: 'modal-grupo-economico');
            $this->dispatch('refresh-table')->to(TabelaGrupoEconomico::class);
            $this->dispatch('notify', message: 'Grupo econômico atualizado com sucesso', variant: 'success', title: 'Sucesso');
        } catch (\Throwable $th) {
            $this->dispatch('notify', message: 'Erro ao atualizar grupo econômico', variant: 'error', title: 'Erro');
        }
    }

    public function createGrupoEconomico(): void
    {
        $this->validate([
            'nome' => 'required|string|max:255',
        ]);

        try {
            if ($this->grupoEconomico) {
                $this->updateGrupoEconomico();
                return;
            }

            $grupo = GrupoEconomico::create([
                'nome' => $this->nome,
            ]);

            Activity::performedOn($grupo)
            ->causedBy(auth()->user())
            ->log('Criou um novo grupo econômico');

            $this->resetForm();
            $this->dispatch('close-modal', name: 'modal-grupo-economico');
            $this->dispatch('refresh-table')->to(TabelaGrupoEconomico::class);
            $this->dispatch('notify', message: 'Grupo econômico criado com sucesso', variant: 'success', title: 'Sucesso');
        } catch (\Throwable $th) {
            $this->dispatch('notify', message: 'Erro ao criar grupo econômico', variant: 'danger', title: 'Erro');
        }
    }

    #[On('edit-grupo-economico')]
    public function editGrupoEconomico(GrupoEconomico $grupoEconomico): void
    {
        $this->nome = $grupoEconomico->nome;
        $this->grupoEconomico = $grupoEconomico;
        $this->dispatch('open-modal-grupo-economico', name: 'modal-grupo-economico');
    }

    public function resetForm(): void
    {
        $this->reset([
            'nome',
            'grupoEconomico',
        ]);
    }

    public function render()
    {
        return view('livewire.grupo-economico.modal-grupo-economico');
    }
}
// Ajustando o modal para seguir o padrão do modal de Unidade
