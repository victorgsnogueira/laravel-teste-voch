<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                <div class="max-w-xl">
                    <section>
                        <header>
                            <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100"> {{ __('Nova Unidade') }} </h2>
                            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                                {{ __('Preencha as informações para criar uma nova unidade.') }}
                            </p>
                        </header>
                        <form method="post" action="{{ route('unidade.store') }}" class="mt-6 space-y-6">
                            @csrf
                            <div> <x-input-label for="nome_fantasia" :value="__('Nome fantasia')" /> <x-text-input id="nome_fantasia"
                                    name="nome_fantasia" type="text" class="mt-1 block w-full" :value="old('nome_fantasia')" required />
                                <x-input-error class="mt-2" :messages="$errors->get('nome_fantasia')" />
                            </div>

                            <div> <x-input-label for="razao_social" :value="__('Razao Social')" /> <x-text-input id="razao_social"
                                    name="razao_social" type="text" class="mt-1 block w-full" :value="old('razao_social')" required />
                                <x-input-error class="mt-2" :messages="$errors->get('razao_social')" />
                            </div>

                            <div> <x-input-label for="cnpj" :value="__('CNPJ')" /> <x-text-input id="cnpj"
                                    name="cnpj" type="text"
                                    oninput="this.value = this.value.replace(/[^0-9]/g, '');" class="mt-1 block w-full"
                                    :value="old('cnpj')" required /> <x-input-error class="mt-2" :messages="$errors->get('cnpj')" />
                            </div>
                            
                            <div>
                                <x-input-label for="bandeira_id" :value="__('Bandeira')" /> <select id="bandeira_id" name="bandeira_id"
                                    class="mt-1 block w-full" required>
                                    <option value="">Selecione uma bandeira</option>
                                    @foreach ($bandeiras as $bandeira)
                                        <option value="{{ $bandeira->id }}">{{ $bandeira->nome }}</option>
                                    @endforeach
                                </select> <x-input-error class="mt-2" :messages="$errors->get('bandeira_id')" />
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