<h2 class="text-xl font-bold mb-4">Confirmar Exclusão</h2>
<p class="mb-6">Tem certeza que deseja excluir "{{ $grupo->nome }}"?</p>

<form id="delete-form" method="POST" action="{{ route('grupo-economico.destroy', ['id' => $grupo->id]) }}">
    @csrf
    @method('DELETE')

    <div class="flex justify-end space-x-4">
        <button type="button"
            @click="open = false"
            class="px-4 py-2 bg-gray-300 text-gray-800 rounded hover:bg-gray-400"
        >
            Cancelar
        </button>

        <button type="submit"
            class="px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700"
        >
            Excluir
        </button>
    </div>
</form>