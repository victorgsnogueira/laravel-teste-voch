<div class="py-12 text-gray-900 dark:text-gray-100">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-gray-800 shadow sm:rounded-lg p-8">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
                <div>
                    <label class="block text-sm font-medium mb-1">Grupo Econômico</label>
                    <x-select placeholder="Selecione um grupo econômico" :async-data="route('grupo-economico.search')" option-label="nome" option-value="id" wire:model.live="grupoEconomicoId" multiselect />
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1">Unidade</label>
                    <x-select placeholder="Selecione uma unidade" :async-data="route('unidade.search')" option-label="nome" option-value="id" wire:model.live="unidadeId" multiselect />
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1">Bandeira</label>
                    <x-select placeholder="Selecione uma bandeira" :async-data="route('bandeira.search')" option-label="nome" option-value="id" wire:model.live="bandeiraId" multiselect />
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1">Colaborador</label>
                    <x-select placeholder="Selecione um colaborador" :async-data="route('colaborador.search')" option-label="nome" option-value="id" wire:model.live="colaboradorId" multiselect />
                </div>
            </div>

            <div class="flex justify-end mb-4">
                <x-primary-button wire:click="exportar" class="font-normal">
                    {{ __('Exportar') }}
                </x-primary-button>
            </div>

            @if (count($this->colaboradores) > 0)
                <table class="w-full text-left text-sm text-on-surface dark:text-on-surface-dark">
                    <thead class="border-b border-outline bg-surface-alt text-sm text-on-surface-strong dark:border-outline-dark dark:bg-surface-dark-alt dark:text-on-surface-dark-strong">
                        <tr>
                            <th scope="col" class="p-4">ID</th>
                            <th scope="col" class="p-4">Nome</th>
                            <th scope="col" class="p-4">Email</th>
                            <th scope="col" class="p-4">CPF</th>
                            <th scope="col" class="p-4">Unidade</th>
                            <th scope="col" class="p-4">Ações</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-outline dark:divide-outline-dark">
                        @foreach ($this->colaboradores as $colaborador)
                            <tr>
                                <td class="p-4">{{ $colaborador->id }}</td>
                                <td class="p-4">{{ $colaborador->nome }}</td>
                                <td class="p-4">{{ $colaborador->email }}</td>
                                <td class="p-4">{{ $colaborador->cpf }}</td>
                                <td class="p-4">{{ $colaborador->unidade->nome_fantasia }}</td>
                                <td class="p-4">
                                    <x-primary-button wire:click="editColaborador({{ $colaborador->id }})">
                                        {{ __('Editar') }}
                                    </x-primary-button>
                                    <x-primary-button wire:click="deleteColaborador({{ $colaborador->id }})">
                                        {{ __('Excluir') }}
                                    </x-primary-button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <div class="mt-4 flex justify-center">
                    <x-pagination :paginator="$this->colaboradores" />
                </div>
            @else
                <div class="mt-4 flex justify-center">
                    <p class="text-sm text-gray-500">Nenhum colaborador encontrado</p>
                </div>
            @endif
        </div>
    </div>
</div>
