<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                <div class="w-full">
                    <section class="flex justify-between items-center">
                        <header>
                            <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                                {{ __('Unidades') }} </h2>
                            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                                {{ __('Lista de Unidades') }}
                            </p>
                        </header>
                        <livewire:unidade.modal-unidade />
                    </section>
                    <livewire:unidade.tabela-unidade />
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
