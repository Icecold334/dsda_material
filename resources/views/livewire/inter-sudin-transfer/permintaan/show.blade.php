<div class="space-y-4">
    <div class="grid grid-cols-2 ">
        <div class="">
            <div class="text-3xl font-semibold">Detail Transfer Permintaan</div>
        </div>
        <div class="text-right flex gap-2 justify-end">
            <x-secondary-button @click="$dispatch('open-modal', 'transfer-information-modal')" type="button">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                Informasi Transfer
            </x-secondary-button>
            <x-button variant="secondary" href="{{ route('transfer.permintaan.index') }}" wire:navigate>
                Kembali
            </x-button>
        </div>
    </div>

    <div>
        <x-card title="Daftar Barang">
            <div data-grid data-api="{{ route('transfer.permintaan.show.json', $transfer) }}"
                data-columns='{{ json_encode($data) }}' wire:ignore>
            </div>
        </x-card>
    </div>

    <!-- Modal Components -->
    <livewire:components.transfer-information-modal mode="show" :key="'transfer-info-modal-' . $transfer->id" />
</div>