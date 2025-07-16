<div class="py-12 text-gray-900 dark:text-gray-100">
    @if (count($this->unidades) > 0)
        <table
            class="w-full text-left text-sm text-on-surface dark:text-on-surface-dark>
            <thead
                class="border-b
            border-outline bg-surface-alt text-sm text-on-surface-strong dark:border-outline-dark
            dark:bg-surface-dark-alt dark:text-on-surface-dark-strong">
            <tr>
                <th scope="col" class="p-4">ID</th>
                <th scope="col" class="p-4">Nome</th>
                <th scope="col" class="p-4">Razão Social</th>
                <th scope="col" class="p-4">CNPJ</th>
                <th scope="col" class="p-4">Bandeira</th>
                <th scope="col" class="p-4">Ações</th>
            </tr>
            </thead>
            <tbody class="divide-y divide-outline dark:divide-outline-dark">
                @foreach ($this->unidades as $unidade)
                    <tr>
                        <td class="p-4">{{ $unidade->id }}</td>
                        <td class="p-4">{{ $unidade->nome_fantasia }}</td>
                        <td class="p-4">{{ $unidade->razao_social }}</td>
                        <td class="p-4">{{ $unidade->cnpj }}</td>
                        <td class="p-4">{{ $unidade->bandeira->nome }}</td>
                        <td class="p-4">
                            <x-primary-button wire:click="editUnidade({{ $unidade->id }})">
                                {{ __('Editar') }}
                            </x-primary-button>
                            <x-primary-button wire:click="deleteUnidade({{ $unidade->id }})">
                                {{ __('Excluir') }}
                            </x-primary-button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="mt-4 flex justify-center">
            <x-pagination :paginator="$this->unidades" />
        </div>
    @else
        <div class="mt-4 flex justify-center">
            <p class="text-sm text-gray-500">Nenhuma unidade encontrada</p>
        </div>
    @endif
</div>
