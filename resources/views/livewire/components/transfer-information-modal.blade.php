<div>
    <x-modal name="transfer-information-modal" :show="$showModal" :dismissable="$mode === 'show'" maxWidth="2xl">
        <div class="p-6">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-xl font-semibold">
                    {{ $mode === 'create' ? 'Informasi Transfer' : 'Detail Informasi Transfer' }}
                </h2>
                <button wire:click="closeModal" type="button" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <div class="space-y-4">
                <div>
                    <x-input-label for="modal_sudin_pengirim_id" value="Sudin Peminta" />
                    <livewire:components.select-input wire:model.live="sudin_pengirim_id"
                        :options="$sudins->pluck('name', 'id')" placeholder="-- Pilih Sudin Peminta --"
                        :disabled="$mode === 'show'" :key="'modal-sudin-pengirim-' . $mode" />
                    <x-input-error :messages="$errors->get('sudin_pengirim_id')" class="mt-2" />
                </div>

                <div>
                    <x-input-label for="modal_sudin_penerima_id" value="Sudin Diminta" />
                    <livewire:components.select-input wire:model.live="sudin_penerima_id"
                        :options="$sudins->pluck('name', 'id')" placeholder="-- Pilih Sudin Diminta --"
                        :disabled="$mode === 'show'" :key="'modal-sudin-penerima-' . $mode" />
                    <x-input-error :messages="$errors->get('sudin_penerima_id')" class="mt-2" />
                </div>

                <div>
                    <x-input-label for="modal_tanggal_transfer" value="Tanggal Transfer" />
                    <x-text-input id="modal_tanggal_transfer" wire:model="tanggal_transfer" type="date"
                        class="mt-1 block w-full" :disabled="$mode === 'show'" />
                    <x-input-error :messages="$errors->get('tanggal_transfer')" class="mt-2" />
                </div>

                <div>
                    <x-input-label for="modal_notes" value="Keterangan" />
                    <textarea id="modal_notes" wire:model="notes" rows="4" :disabled="$mode === 'show'"
                        class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm block w-full mt-1"
                        placeholder="Masukkan keterangan (opsional)"></textarea>
                    <x-input-error :messages="$errors->get('notes')" class="mt-2" />
                </div>
            </div>

            <div class="mt-6 flex justify-end gap-2">
                <x-secondary-button wire:click="closeModal" type="button">
                    {{ $mode === 'create' ? 'Batal' : 'Tutup' }}
                </x-secondary-button>
                @if ($mode === 'create')
                    <x-button wire:click="saveInformation" type="button">
                        Simpan
                    </x-button>
                @endif
            </div>
        </div>
    </x-modal>
</div>