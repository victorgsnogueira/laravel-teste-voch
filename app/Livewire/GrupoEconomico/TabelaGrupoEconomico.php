<?php

namespace App\Livewire\GrupoEconomico;

use App\Models\GrupoEconomico;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Livewire\Component;
use Spatie\Activitylog\Facades\Activity;

class TabelaGrupoEconomico extends Component
{
    public int $perPage = 10;
    public int $page = 1;
    // Removido service, usaremos o Model diretamente
    public ?array $grupoEconomicoId = [];
    public array $filtros = [];

    public function render()
    {
        return view('livewire.grupo-economico.tabela-grupo-economico');
    }

    // Removido método boot

    public function setPage(int $page): void
    {
        $this->page = $page;
    }

    #[On('refresh-table')]
    #[Computed]
    public function gruposEconomicos(): LengthAwarePaginator
    {
        $query = GrupoEconomico::query();
        if (!empty($this->grupoEconomicoId)) {
            $query->whereIn('id', $this->grupoEconomicoId);
        }
        // Adicione outros filtros conforme necessário
        return $query->paginate($this->perPage, ['*'], 'page', $this->page);
    }

    public function editGrupoEconomico(int $id): void
    {
        try {
            $grupoEconomico = GrupoEconomico::findOrFail($id);
            $this->dispatch('edit-grupo-economico', grupoEconomico: $grupoEconomico)->to(ModalGrupoEconomico::class);
        } catch (\Throwable $th) {
            $this->dispatch('notify', message: 'Erro ao editar grupo econômico', variant: 'danger', title: 'Erro');
        }
    }

    public function Exportar(){
        dd("Exportar");
    }

    public function deleteGrupoEconomico(int $id): void
    {
        try {
            $grupo = GrupoEconomico::findOrFail($id);
            $grupo->delete();

            Activity::performedOn($grupo)
                ->causedBy(auth()->user())
                ->withProperties([
                    'nome' => $grupo->nome
                ])
                ->log('Excluiu um Grupo Economido');

            $this->dispatch('notify', message: 'Grupo econômico excluído com sucesso', variant: 'success', title: 'Sucesso');
        } catch (\Throwable $th) {
            $this->dispatch('notify', message: 'Erro ao excluir grupo econômico', variant: 'danger', title: 'Erro');
        }
    }
}
