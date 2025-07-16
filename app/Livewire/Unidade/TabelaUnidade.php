<?php

namespace App\Livewire\Unidade;

use App\Models\Unidade;
use Spatie\Activitylog\Facades\Activity;
use Livewire\Component;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class TabelaUnidade extends Component
{
    public int $perPage = 10;
    public int $page = 1;
    // Removido service, usaremos o Model diretamente
    // Removido service, usaremos o Model diretamente

    // Removido método boot

    public function setPage(int $page): void
    {
        $this->page = $page;
    }

    public function render()
    {
        return view('livewire.unidade.tabela-unidade');
    }

    #[On('refresh-table')]
    #[Computed]
    public function unidades(): LengthAwarePaginator
    {
        return Unidade::paginate($this->perPage, ['*'], 'page', $this->page);
    }

    public function editUnidade(int $id): void
    {
        $unidade = Unidade::findOrFail($id);
        $this->dispatch('edit-unidade', unidade: $unidade)->to(ModalUnidade::class);
    }

    public function deleteUnidade(int $id): void
    {
        try {
            $unidade = Unidade::findOrFail($id);
            $unidade->delete();

            Activity::performedOn($unidade)
                ->causedBy(auth()->user())
                ->withProperties([
                    'nome_fantasia' => $unidade->nome_fantasia,
                    'razao_social' => $unidade->razao_social,
                    'cnpj' => $unidade->cnpj,
                    'bandeira_id' => $unidade->bandeira_id,
                ])
                ->log('Excluiu uma unidade');

            $this->dispatch('refresh-table')->to(TabelaUnidade::class);
            $this->dispatch('notify', message: 'Unidade excluída com sucesso', variant: 'success', title: 'Sucesso');
        } catch (\Throwable $th) {
            $this->dispatch('notify', message: 'Erro ao excluir unidade', variant: 'danger', title: 'Erro');
        }
    }
}
