<div class="space-y-4">
    <form wire:submit.prevent="save" class="space-y-2">
        <div>
            <label for="nome" class="block text-sm font-medium">Nome do Grupo</label>
            <input wire:model.defer="nome" type="text" id="nome" class="border p-2 rounded w-full">
            @error('nome') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>

        <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">
            {{ $editando ? 'Atualizar' : 'Criar' }}
        </button>
    </form>

    <hr>

    <h2 class="text-lg font-bold">Grupos Econômicos</h2>
    <ul class="space-y-2">
        @foreach($grupos as $grupo)
            <li class="flex justify-between items-center border p-2 rounded">
                <span>{{ $grupo->nome }}</span>
                <div class="space-x-2">
                    <button wire:click="edit({{ $grupo->id }})" class="text-blue-500">Editar</button>
                    <button wire:click="delete({{ $grupo->id }})" class="text-red-500">Excluir</button>
                </div>
            </li>
        @endforeach
    </ul>
</div>
