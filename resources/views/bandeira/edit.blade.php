<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                <div class="w-full">
                    <header class="mb-6">
                        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                            {{ __('Editar Bandeira') }}
                        </h2>
                        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                            {{ __('Edite as informações da bandeira') }} {{ $bandeira->nome }}
                        </p>
                    </header>

                    <form method="POST" action="{{ route('bandeira.update', $bandeira) }}" class="space-y-6">
                        @csrf
                        @method('PUT')

                        <!-- Nome -->
                        <div>
                            <x-input-label for="nome" :value="__('Nome da Bandeira')" />
                            <x-text-input id="nome" name="nome" type="text" class="mt-1 block w-full" 
                                :value="old('nome', $bandeira->nome)" required autofocus />
                            <x-input-error class="mt-2" :messages="$errors->get('nome')" />
                        </div>

                        <!-- Grupo Econômico -->
                        <div>
                            <x-input-label for="grupo_economico_id" :value="__('Grupo Econômico')" />
                            <select id="grupo_economico_id" name="grupo_economico_id" 
                                class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm" 
                                required>
                                <option value="">{{ __('Selecione um grupo econômico') }}</option>
                                @foreach($gruposEconomicos as $grupo)
                                    <option value="{{ $grupo->id }}" 
                                        {{ old('grupo_economico_id', $bandeira->grupo_economico_id) == $grupo->id ? 'selected' : '' }}>
                                        {{ $grupo->nome }}
                                    </option>
                                @endforeach
                            </select>
                            <x-input-error class="mt-2" :messages="$errors->get('grupo_economico_id')" />
                        </div>

                        <!-- Informações Adicionais -->
                        <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                            <h3 class="text-md font-medium text-gray-900 dark:text-gray-100 mb-3">
                                {{ __('Informações Adicionais') }}
                            </h3>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                                <div>
                                    <span class="font-medium text-gray-600 dark:text-gray-400">{{ __('Status:') }}</span>
                                    <span class="ml-2 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $bandeira->isAtiva() ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                        {{ $bandeira->isAtiva() ? __('Ativa') : __('Inativa') }}
                                    </span>
                                </div>
                                
                                <div>
                                    <span class="font-medium text-gray-600 dark:text-gray-400">{{ __('Total de Unidades:') }}</span>
                                    <span class="ml-2 text-gray-900 dark:text-gray-100">{{ $bandeira->total_unidades }}</span>
                                </div>
                                
                                <div>
                                    <span class="font-medium text-gray-600 dark:text-gray-400">{{ __('Criado em:') }}</span>
                                    <span class="ml-2 text-gray-900 dark:text-gray-100">{{ $bandeira->created_at->format('d/m/Y H:i') }}</span>
                                </div>
                                
                                <div>
                                    <span class="font-medium text-gray-600 dark:text-gray-400">{{ __('Atualizado em:') }}</span>
                                    <span class="ml-2 text-gray-900 dark:text-gray-100">{{ $bandeira->updated_at->format('d/m/Y H:i') }}</span>
                                </div>
                            </div>
                        </div>

                        <div class="flex items-center gap-4">
                            <x-primary-button>{{ __('Atualizar') }}</x-primary-button>
                            <a href="{{ route('bandeira.show', $bandeira) }}" 
                                class="inline-flex items-center px-4 py-2 bg-gray-800 dark:bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-white dark:text-gray-800 uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-white focus:bg-gray-700 dark:focus:bg-white active:bg-gray-900 dark:active:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                                {{ __('Cancelar') }}
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

