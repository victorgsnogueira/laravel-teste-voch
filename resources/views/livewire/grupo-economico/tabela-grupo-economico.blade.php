<div class="py-12 text-gray-900 dark:text-gray-100">
    <x-select class="text-black" label="Grupo Econômico" placeholder="Selecione um grupo econômico" :async-data="route('grupo-economico.search')" option-label="nome"
        option-value="id" wire:model.live="grupoEconomicoId" multiselect />

    @if (count($this->gruposEconomicos) > 0)
        <table
            class="w-full text-left text-sm text-on-surface dark:text-on-surface-dark>
            <thead
                class="border-b
            border-outline bg-surface-alt text-sm text-on-surface-strong dark:border-outline-dark
            dark:bg-surface-dark-alt dark:text-on-surface-dark-strong">
            <tr>
                <th scope="col" class="p-4">ID</th>
                <th scope="col" class="p-4">Nome</th>
                <th scope="col" class="p-4">Ações</th>
            </tr>
            </thead>
            <tbody class="divide-y divide-outline dark:divide-outline-dark">
                @foreach ($this->gruposEconomicos as $grupoEconomico)
                    <tr>
                        <td class="p-4">{{ $grupoEconomico->id }}</td>
                        <td class="p-4">{{ $grupoEconomico->nome }}</td>
                        <td class="p-4">
                            <x-primary-button wire:click="editGrupoEconomico({{ $grupoEconomico->id }})">
                                {{ __('Editar') }}
                            </x-primary-button>
                            <x-primary-button wire:click="deleteGrupoEconomico({{ $grupoEconomico->id }})">
                                {{ __('Excluir') }}
                            </x-primary-button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="mt-4 flex justify-center">
            <x-pagination :paginator="$this->gruposEconomicos" />
        </div>
    @else
        <div class="mt-4 flex justify-center">
            <p class="text-sm text-gray-500">Nenhum grupo econômico encontrado</p>
        </div>
    @endif
</div>
