<div>
    <x-primary-button x-on:click="$openModal('modal-unidade')">
        {{ __('Nova Unidade') }}
    </x-primary-button>

    <x-modal name="modal-unidade" width="lg" x-on:open-modal-unidade.window="$openModal('modal-unidade')"
        x-on:close="$wire.resetForm()">
        <x-card class="border border-gray-300 rounded-lg">
            <div class="p-2 sm:p-6 flex flex-col gap-6">
                <h2 class="text-2xl font-bold mb-2 text-center tracking-tight">
                    {{ __('Nova Unidade') }}
                </h2>

                <form wire:submit.prevent="createUnidade" class="flex flex-col gap-4">
                    <div>
                        <x-input-label for="nomeFantasia" :value="__('Nome Fantasia')" />
                        <x-text-input wire:model="nomeFantasia" id="nomeFantasia" name="nomeFantasia" type="text"
                            class="mt-1 block w-full border rounded-lg placeholder-gray-500 focus:ring-2 focus:ring-blue-500 transition-all" :value="old('nomeFantasia')" required />
                        @error('nomeFantasia')
                            <span class="mt-1 text-xs text-red-500">{{ $message }}</span>
                        @enderror
                    </div>
                    <div>
                        <x-input-label for="razaoSocial" :value="__('Razão Social')" />
                        <x-text-input wire:model="razaoSocial" id="razaoSocial" name="razaoSocial" type="text"
                            class="mt-1 block w-full border rounded-lg placeholder-gray-500 focus:ring-2 focus:ring-blue-500 transition-all" :value="old('razaoSocial')" required />
                        @error('razaoSocial')
                            <span class="mt-1 text-xs text-red-500">{{ $message }}</span>
                        @enderror
                    </div>
                    <div>
                        <x-input-label for="cnpj" :value="__('CNPJ')" />
                        <x-text-input wire:model="cnpj" id="cnpj" name="cnpj" type="text"
                            class="mt-1 block w-full border rounded-lg placeholder-gray-500 focus:ring-2 focus:ring-blue-500 transition-all" :value="old('cnpj')" required x-data x-mask="99.999.999/9999-99" />
                        @error('cnpj')
                            <span class="mt-1 text-xs text-red-500">{{ $message }}</span>
                        @enderror
                    </div>
                    <div>
                        <x-input-label for="bandeiraId" :value="__('Bandeira')" />
                        <x-select placeholder="Selecione uma bandeira" :async-data="route('bandeira.search')"
                            option-label="nome" option-value="id" wire:model="bandeiraId"
                            class="mt-1 block w-full border rounded-lg placeholder-gray-500 focus:ring-2 focus:ring-blue-500 transition-all" />
                    </div>
                    <div class="flex justify-end mt-6">
                        <x-primary-button type="submit"
                            class="px-6 py-2 rounded-lg shadow font-semibold transition-all focus:ring-2 focus:ring-blue-500">
                            {{ __('Salvar') }}
                        </x-primary-button>
                    </div>
                </form>
            </div>
        </x-card>
    </x-modal>
</div>
