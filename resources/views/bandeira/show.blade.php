<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                <div class="w-full">
                    <header class="mb-6">
                        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                            {{ __('Detalhes da Bandeira') }}
                        </h2>
                        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                            {{ __('Informações detalhadas da bandeira') }} {{ $bandeira->nome }}
                        </p>
                    </header>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Informações Básicas -->
                        <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                            <h3 class="text-md font-medium text-gray-900 dark:text-gray-100 mb-4">
                                {{ __('Informações Básicas') }}
                            </h3>
                            
                            <div class="space-y-3">
                                <div>
                                    <span class="text-sm font-medium text-gray-600 dark:text-gray-400">{{ __('Nome:') }}</span>
                                    <p class="text-sm text-gray-900 dark:text-gray-100">{{ $bandeira->nome }}</p>
                                </div>
                                
                                <div>
                                    <span class="text-sm font-medium text-gray-600 dark:text-gray-400">{{ __('Grupo Econômico:') }}</span>
                                    <p class="text-sm text-gray-900 dark:text-gray-100">{{ $bandeira->grupoEconomico->nome }}</p>
                                </div>
                                
                                <div>
                                    <span class="text-sm font-medium text-gray-600 dark:text-gray-400">{{ __('Status:') }}</span>
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $bandeira->isAtiva() ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                        {{ $bandeira->isAtiva() ? __('Ativa') : __('Inativa') }}
                                    </span>
                                </div>
                                
                                <div>
                                    <span class="text-sm font-medium text-gray-600 dark:text-gray-400">{{ __('Total de Unidades:') }}</span>
                                    <p class="text-sm text-gray-900 dark:text-gray-100">{{ $bandeira->total_unidades }}</p>
                                </div>
                            </div>
                        </div>

                        <!-- Estatísticas -->
                        <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                            <h3 class="text-md font-medium text-gray-900 dark:text-gray-100 mb-4">
                                {{ __('Estatísticas') }}
                            </h3>
                            
                            <div class="space-y-3">
                                <div>
                                    <span class="text-sm font-medium text-gray-600 dark:text-gray-400">{{ __('Data de Criação:') }}</span>
                                    <p class="text-sm text-gray-900 dark:text-gray-100">{{ $bandeira->created_at->format('d/m/Y H:i') }}</p>
                                </div>
                                
                                <div>
                                    <span class="text-sm font-medium text-gray-600 dark:text-gray-400">{{ __('Última Atualização:') }}</span>
                                    <p class="text-sm text-gray-900 dark:text-gray-100">{{ $bandeira->updated_at->format('d/m/Y H:i') }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Unidades Associadas -->
                    @if($bandeira->unidades->count() > 0)
                        <div class="mt-8">
                            <h3 class="text-md font-medium text-gray-900 dark:text-gray-100 mb-4">
                                {{ __('Unidades Associadas') }}
                            </h3>
                            
                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                    <thead class="bg-gray-50 dark:bg-gray-700">
                                        <tr>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                                {{ __('Nome Fantasia') }}
                                            </th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                                {{ __('Razão Social') }}
                                            </th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                                {{ __('CNPJ') }}
                                            </th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                                {{ __('Colaboradores') }}
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                        @foreach($bandeira->unidades as $unidade)
                                            <tr>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                                    {{ $unidade->nome_fantasia }}
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                                    {{ $unidade->razao_social }}
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                                    {{ $unidade->cnpj_formatado }}
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                                    {{ $unidade->total_colaboradores }}
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    @endif

                    <div class="flex items-center gap-4 mt-6">
                        <a href="{{ route('bandeira.edit', $bandeira) }}" 
                            class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            {{ __('Editar') }}
                        </a>
                        <a href="{{ route('bandeira.index') }}" 
                            class="inline-flex items-center px-4 py-2 bg-gray-800 dark:bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-white dark:text-gray-800 uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-white focus:bg-gray-700 dark:focus:bg-white active:bg-gray-900 dark:active:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                            {{ __('Voltar') }}
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

