<div class="space-y-4">
    <div class="grid grid-cols-2">
        <div>
            <div class="text-3xl font-semibold">Detail Pengiriman #{{ $delivery->nomor }}</div>
        </div>
        <div class="text-right flex gap-2 justify-end">
            <x-secondary-button wire:click="openInformasiModal" type="button">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                Detail Pengiriman
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
            <x-button variant="secondary" href="{{ route('delivery.index') }}" wire:navigate>
                Kembali
            </x-button>
        </div>
    </div>

    <!-- Modal Detail Informasi Lengkap -->
    <livewire:components.delivery-information-modal mode="show" :sudin_id="$delivery->sudin_id"
        :nomor_kontrak="$delivery->contract->nomor" :warehouse_id="$delivery->warehouse_id"
        :tanggal_kirim="$delivery->tanggal_delivery" :delivery="$delivery" modal-id="detail-informasi-modal"
        :key="'delivery-info-modal-' . $delivery->id" />

    <!-- Modal Surat Jalan -->
    <livewire:components.document-upload mode="show" :model-type="'App\\Models\\Delivery'" :model-id="$delivery->id"
        category="surat_jalan" label="Surat Jalan" modal-id="surat-jalan-modal" :key="'surat-jalan-show-' . $delivery->id" />

    <!-- Modal Foto Pengiriman -->
    <livewire:components.document-upload mode="show" :model-type="'App\\Models\\Delivery'" :model-id="$delivery->id"
        category="foto_pengiriman" label="Foto Pengiriman" modal-id="foto-pengiriman-modal"
        :key="'foto-pengiriman-show-' . $delivery->id" />

    <!-- Daftar Barang -->
    <x-card title="Daftar Barang">
        <div data-grid data-api="{{ route('delivery.show.json', $delivery) }}" data-columns='[
        { "name": "Kode Barang", "id": "kode", "width": "15%" },
        { "name": "Item", "id": "item" },
        { "name": "Jumlah", "id": "qty", "width": "15%", "className": "text-right" },
        { "name": "", "id": "action","width": "10%" , "sortable": false, "className": "text-center" }
    ]' wire:ignore>
        </div>
    </x-card>
</div>