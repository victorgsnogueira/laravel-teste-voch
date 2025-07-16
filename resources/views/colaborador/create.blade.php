<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                <div class="max-w-xl">
                    <section>
                        <header>
                            <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100"> {{ __('Novo Colaborador') }} </h2>
                            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                                {{ __('Preencha as informações para criar um novo colaborador.') }}
                            </p>
                        </header>
                        <form method="post" action="{{ route('colaborador.store') }}" class="mt-6 space-y-6">
                            @csrf
                            <div> <x-input-label for="nome" :value="__('Nome')" /> <x-text-input id="nome"
                                    name="nome" type="text" class="mt-1 block w-full" :value="old('nome')" required />
                                <x-input-error class="mt-2" :messages="$errors->get('nome')" />
                            </div>

                            <div> <x-input-label for="email" :value="__('Email')" /> <x-text-input id="email"
                                    name="email" type="text" class="mt-1 block w-full" :value="old('email')" required />
                                <x-input-error class="mt-2" :messages="$errors->get('email')" />
                            </div>

                            <div> <x-input-label for="cpf" :value="__('CPF')" /> <x-text-input id="cpf"
                                    name="cpf" type="text"
                                    oninput="this.value = this.value.replace(/[^0-9]/g, '');" class="mt-1 block w-full"
                                    :value="old('cpf')" required /> <x-input-error class="mt-2" :messages="$errors->get('cpf')" />
                            </div>
                            
                            <div>
                                <x-input-label for="unidade_id" :value="__('Bandeira')" /> <select id="unidade_id" name="unidade_id"
                                    class="mt-1 block w-full" required>
                                    <option value="">Selecione uma bandeira</option>
                                    @foreach ($unidades as $unidade)
                                        <option value="{{ $unidade->id }}">{{ $unidade->nome_fantasia }}</option>
                                    @endforeach
                                </select> <x-input-error class="mt-2" :messages="$errors->get('unidade_id')" />
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