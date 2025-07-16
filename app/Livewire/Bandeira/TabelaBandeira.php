<?php

namespace App\Livewire\Bandeira;

use App\Models\Bandeira;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Livewire\Component;
use Spatie\Activitylog\Facades\Activity;

class TabelaBandeira extends Component
{
    public int $perPage = 10;
    public int $page = 1;
    // Removido service, usaremos o Model diretamente
    public array $filtros = [];
    public array $grupoEconomicoId = [];
    public array $bandeiraId = [];

    public function render()
    {
        return view('livewire.bandeira.tabela-bandeira');
    }

    // Removido método boot

    public function setPage(int $page): void
    {
        $this->page = $page;
    }

    #[On('refresh-table')]
    #[Computed]
public function bandeiras(): LengthAwarePaginator
{
    $query = Bandeira::query();
    if (!empty($this->grupoEconomicoId)) {
        $query->whereIn('grupo_economico_id', $this->grupoEconomicoId);
    }
    if (!empty($this->bandeiraId)) {
        $query->whereIn('id', $this->bandeiraId);
    }
    // Adicione outros filtros conforme necessário
    return $query->paginate($this->perPage, ['*'], 'page', $this->page);
}

    
    public function editBandeira(int $id): void
    {
        try {
            $bandeira = Bandeira::findOrFail($id);
            $this->dispatch('edit-bandeira', bandeira: $bandeira)->to(ModalBandeira::class);
        } catch (\Throwable $th) {
            $this->dispatch('notify', message: 'Erro ao editar bandeira', variant: 'danger', title: 'Erro');
        }
    }

    public function deleteBandeira(int $id): void
    {
        try {
            $bandeira = Bandeira::findOrFail($id);
            $bandeira->delete();

            Activity::performedOn($bandeira)
                ->causedBy(auth()->user())
                ->withProperties([
                    'nome' => $bandeira->nome,
                    'grupo_economico_id' => $bandeira->grupo_economico_id,
                ])
                ->log('Excluiu uma bandeira');

            $this->dispatch('notify', message: 'Bandeira excluída com sucesso', variant: 'success', title: 'Sucesso');
        } catch (\Throwable $th) {
            $this->dispatch('notify', message: 'Erro ao excluir bandeira', variant: 'danger', title: 'Erro');
        }
    }
}
