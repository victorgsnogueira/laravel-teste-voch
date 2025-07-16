<form method="post" action="{{ route('grupo-economico.update', ['id' => $grupoeconomico->id]) }}" class="mt-6 space-y-6">
    @csrf
    @method('PUT')

    <div>
        <x-input-label for="nome" :value="__('Nome')" />
        <x-text-input id="nome" name="nome" type="text" class="mt-1 block w-full"
            :value="old('nome', $grupoeconomico->nome)" required />
        <x-input-error class="mt-2" :messages="$errors->get('nome')" />
    </div>

    <div class="flex items-center gap-4">
        <x-primary-button>{{ __('Salvar') }}</x-primary-button>
    </div>
</form>
