<?php

namespace App\Livewire\Colaborador;

// Import de Colaborador já existe, removido duplicidade
use App\Models\Colaborador;
use Livewire\Component;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Spatie\Activitylog\Facades\Activity;
use App\Exports\ColaboradoresExport;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class TabelaColaborador extends Component
{
    public int $perPage = 10;
    public int $page = 1;
    // Removido service, usaremos o Model diretamente
    public ?array $grupoEconomicoId = [];
    public ?array $unidadeId = [];
    public ?array $bandeiraId = [];
    public ?array $colaboradorId = [];
    public array $filtros = [];

    // Removido método boot

    public function setPage(int $page): void
    {
        $this->page = $page;
    }

    public function render()
    {
        return view('livewire.colaborador.tabela-colaborador');
    }

    #[On('refresh-table')]
    #[Computed]
    public function colaboradores(): LengthAwarePaginator
    {
        $query = Colaborador::query();
        if (!empty($this->grupoEconomicoId)) {
            $query->whereHas('unidade.bandeira.grupoEconomico', function($q) {
                $q->whereIn('id', $this->grupoEconomicoId);
            });
        }
        if (!empty($this->unidadeId)) {
            $query->whereIn('unidade_id', $this->unidadeId);
        }
        if (!empty($this->bandeiraId)) {
            $query->whereHas('unidade.bandeira', function($q) {
                $q->whereIn('id', $this->bandeiraId);
            });
        }
        if (!empty($this->colaboradorId)) {
            $query->whereIn('id', $this->colaboradorId);
        }
        return $query->paginate($this->perPage, ['*'], 'page', $this->page);
    }

   public function exportar(): BinaryFileResponse
{
    $filtro = $this->filtros;

    $dados = Colaborador::query()
        ->when(data_get($filtro, 'colaboradorId'), fn($q) => $q->whereIn('id', $filtro['colaboradorId']))
        ->when(data_get($filtro, 'unidadeId'), fn($q) => $q->whereIn('unidade_id', $filtro['unidadeId']))
        ->when(data_get($filtro, 'bandeiraId'), function ($q) use ($filtro) {
            $q->whereHas('unidade', fn($q) => $q->whereIn('bandeira_id', $filtro['bandeiraId']));
        })
        ->when(data_get($filtro, 'grupoEconomicoId'), function ($q) use ($filtro) {
            $q->whereHas('unidade.bandeira', fn($q) => $q->whereIn('grupo_economico_id', $filtro['grupoEconomicoId']));
        })
        ->get(['id', 'nome', 'email', 'cpf', 'unidade_id']);
        // dd($dados);
        //  Excel::queue(new ColaboradoresExport($dados), 'colaboradores.xlsx');
            return Excel::download(new ColaboradoresExport($dados), 'colaboradores.xlsx');
    }

    public function editColaborador(int $id): void
    {
        try {
            $colaborador = Colaborador::findOrFail($id);
            $this->dispatch('edit-colaborador', colaborador: $colaborador)->to(ModalColaborador::class);
        } catch (\Throwable $th) {
            $this->dispatch('notify', message: 'Erro ao editar colaborador', variant: 'danger', title: 'Erro');
        }
    }

    public function deleteColaborador(int $id): void
    {
        try {
            $colaborador = Colaborador::findOrFail($id);
            $colaborador->delete();

            Activity::performedOn($colaborador)
                ->causedBy(auth()->user())
                ->withProperties([
                    'nome' => $colaborador->nome,
                    'cpf' => $colaborador->cpf,
                ])
                ->log('Excluiu um colaborador');

            $this->dispatch('refresh-table')->to(TabelaColaborador::class);
            $this->dispatch('notify', message: 'Colaborador excluído com sucesso', variant: 'success', title: 'Sucesso');
        } catch (\Throwable $th) {
            $this->dispatch('notify', message: 'Erro ao excluir colaborador', variant: 'danger', title: 'Erro');
        }
    }
}
