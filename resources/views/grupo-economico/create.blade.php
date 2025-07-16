<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                <div class="max-w-xl">
                    <section>
                        <header>
                            <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100"> {{ __('Novo Grupo Economico') }} </h2>
                            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                                {{ __('Preencha as informações para criar um novo Grupo.') }}
                            </p>
                        </header>
                        <form method="post" action="{{ route('grupo-economico.store') }}" class="mt-6 space-y-6">
                            @csrf
                            <div> <x-input-label for="nome" :value="__('ID Externo')" /> <x-text-input id="nome"
                                    name="nome" type="text" class="mt-1 block w-full" :value="old('nome')" required />
                                <x-input-error class="mt-2" :messages="$errors->get('nome')" />
                            </div>
                            <div class="flex items-center gap-4">
                                <x-primary-button>{{ __('Criar') }}</x-primary-button>
                            </div>
                        </form>
                    </section>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>