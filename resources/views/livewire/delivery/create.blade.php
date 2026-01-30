<div class="space-y-4">
    <div class="grid grid-cols-2">
        <div>
            <div class="text-3xl font-semibold">Tambah Pengiriman</div>
        </div>
        <div class="text-right flex gap-2 justify-end">
            @if($contract && $informationFilled)
                <x-secondary-button @click="$dispatch('open-modal', 'delivery-information-modal')" type="button">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    Informasi
                </x-secondary-button>
                <x-secondary-button wire:click="openSuratJalanModal" type="button">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                    </svg>
                    Surat Jalan
                </x-secondary-button>
                <x-secondary-button wire:click="openFotoPengirimanModal" type="button">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                    Foto Pengiriman
                </x-secondary-button>
            @endif
            <x-button variant="secondary" href="{{ route('delivery.index') }}" wire:navigate>
                Kembali
            </x-button>
        </div>
    </div>

    <!-- Modal Pencarian Kontrak -->
    <x-modal name="input-contract-number" :show="$showModal && !$contract" :dismissable="false">
        <div class="p-6 space-y-4">
            <div class="flex items-center gap-2">
                <div class="text-lg text-primary-700">
                    <a href="{{ route('delivery.index') }}" wire:navigate>
                        <i class="fa-solid fa-circle-chevron-left"></i>
                    </a>
                </div>
                <div class="font-semibold text-2xl">Masukkan Nomor Kontrak</div>
            </div>

            <div class="flex">
                <input type="text" wire:model="contract_nomor" wire:keydown.enter="searchContract"
                    class="rounded-none rounded-s-lg bg-gray-50 border border-gray-300 text-gray-900 block flex-1 text-sm p-2.5"
                    placeholder="Masukkan Nomor Kontrak" />

                <x-button variant="info" type="button" wire:click="searchContract">
                    Cari
                </x-button>
            </div>

            @error('contract_nomor')
                <div class="text-sm text-red-600">{{ $message }}</div>
            @enderror
        </div>
    </x-modal>


    @if ($contract)
        <!-- Modal Informasi Pengiriman -->
        <livewire:components.delivery-information-modal :mode="$informationFilled ? 'show' : 'create'"
            :sudin_id="$contract->sudin_id" :nomor_kontrak="$contract->nomor" :key="'delivery-info-modal-' . $contract->id" />

        @if(!$informationFilled)
            <div class="p-4 text-center text-amber-800 rounded-lg bg-amber-50" role="alert">
                <svg class="w-12 h-12 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <p class="text-lg font-semibold">Silakan isi informasi pengiriman terlebih dahulu</p>
            </div>
        @endif

        @if($informationFilled)
            <livewire:delivery.create-table :warehouse="$warehouse_id" :contract="$contract->id" :tanggal_kirim="$tanggal_kirim"
                :key="'create-table-' . $contract->id . '-' . $warehouse_id" />

            <!-- Modal Upload Surat Jalan -->
            <livewire:components.document-upload mode="create" :model-type="'App\\Models\\Delivery'" category="surat_jalan"
                label="Upload Surat Jalan" :multiple="true" accept=".pdf,.jpg,.jpeg,.png" modal-id="surat-jalan-modal"
                :key="'surat-jalan-' . $contract->id" />

            <!-- Modal Upload Foto Pengiriman -->
            <livewire:components.document-upload mode="create" :model-type="'App\\Models\\Delivery'" category="foto_pengiriman"
                label="Upload Foto Pengiriman" :multiple="false" accept=".jpg,.jpeg,.png" modal-id="foto-pengiriman-modal"
                :key="'foto-pengiriman-' . $contract->id" />
        @endif
    @endif
</div>