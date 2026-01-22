<div class="space-y-4">
    <div class="grid grid-cols-2 ">
        <div class="">
            <div class="text-3xl font-semibold">Detail Transfer Pengiriman</div>
        </div>
        <div class="text-right flex gap-2 justify-end">
            <x-secondary-button @click="$dispatch('open-modal', 'transfer-information-modal')" type="button">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                Informasi
            </x-secondary-button>
            <x-button variant="secondary" href="{{ route('transfer.pengiriman.index') }}" wire:navigate>
                Kembali
            </x-button>
        </div>
    </div>
    <div>
        <x-card title="Detail Transfer">
            <div>
                <table class="table-auto w-full text-md space-y-2">
                    <tr>
                        <td class="font-semibold w-1/2">Status</td>
                        <td><span
                                class="bg-{{ $transfer->status_color }}-600 text-{{ $transfer->status_color }}-100 text-xs font-medium me-2 px-2.5 py-0.5 rounded-full">{{
    $transfer->status_text }}</span></td>
                    </tr>
                    <tr>
                        <td class="font-semibold">Pembuat</td>
                        <td>{{ $transfer->user?->name ?? '-' }}</td>
                    </tr>
                </table>
            </div>
        </x-card>
    </div>

    <div>
        <x-card title="Daftar Barang">
            <div data-grid data-api="{{ route('transfer.pengiriman.show.json', $transfer) }}" data-columns='[
        { "name": "No", "id": "no", "width": "8%" },
        { "name": "Kode Barang", "id": "kode", "width": "12%" },
        { "name": "Barang", "id": "barang", "width": "20%" },
        { "name": "Spesifikasi", "id": "spec" },
        { "name": "Jumlah", "id": "qty", "width": "15%" },
        { "name": "Catatan", "id": "notes", "width": "15%" }
    ]' wire:ignore>
            </div>
        </x-card>
    </div>

    <!-- Modal Components -->
    <livewire:components.transfer-information-modal mode="show" :key="'transfer-info-modal-' . $transfer->id" />
</div>