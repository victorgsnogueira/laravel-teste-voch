<div>
    <x-primary-button x-on:click="$openModal('modal-colaborador')">
        {{ __('Novo Colaborador') }}
    </x-primary-button>

    <x-modal name="modal-colaborador" width="lg" x-on:open-modal-colaborador.window="$openModal('modal-colaborador')"
        x-on:close="$wire.resetForm()">
        <x-card class="border border-gray-300 rounded-lg">
            <div class="p-2 sm:p-6 flex flex-col gap-6">
                <h2 class="text-2xl font-bold mb-2 text-center tracking-tight">
                    {{ __('Novo Colaborador') }}
                </h2>

                <form wire:submit.prevent="createColaborador" class="flex flex-col gap-4">
                    <div>
                        <x-input-label for="nome" :value="__('Nome')" />
                        <x-text-input wire:model="nome" id="nome" name="nome" type="text"
                            class="mt-1 block w-full border rounded-lg placeholder-gray-500 focus:ring-2 focus:ring-blue-500 transition-all" :value="old('nome')" required />
                        @error('nome')
                            <span class="mt-1 text-xs text-red-500">{{ $message }}</span>
                        @enderror
                    </div>
                    <div>
                        <x-input-label for="email" :value="__('Email')" />
                        <x-text-input wire:model="email" id="email" name="email" type="email"
                            class="mt-1 block w-full border rounded-lg placeholder-gray-500 focus:ring-2 focus:ring-blue-500 transition-all" :value="old('email')" required />
                        @error('email')
                            <span class="mt-1 text-xs text-red-500">{{ $message }}</span>
                        @enderror
                    </div>
                    <div>
                        <x-input-label for="cpf" :value="__('CPF')" />
                        <x-text-input wire:model="cpf" id="cpf" name="cpf" type="text"
                            class="mt-1 block w-full border rounded-lg placeholder-gray-500 focus:ring-2 focus:ring-blue-500 transition-all"
                            required />
                        @error('cpf')
                            <span class="mt-1 text-xs text-red-500">{{ $message }}</span>
                        @enderror
                    </div>
                    <div>
                        <x-input-label for="unidadeId" :value="__('Unidade')" />
                        <x-select
                            placeholder="Selecione uma unidade"
                            :async-data="route('unidade.search')"
                            option-label="nome"
                            option-value="id"
                            wire:model="unidadeId"
                            class="mt-1 block w-full border rounded-lg placeholder-gray-500 focus:ring-2 focus:ring-blue-500 transition-all"
                            required
                        />
                        @error('unidadeId')
                            <span class="mt-1 text-xs text-red-500">{{ $message }}</span>
                        @enderror
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
