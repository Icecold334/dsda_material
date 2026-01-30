<div class="space-y-4">
    <div class="grid grid-cols-2">
        <div>
            <div class="text-3xl font-semibold">Detail Stok Gudang</div>
        </div>
        <div class="text-right">
            <x-button type="button" variant="success"
                wire:click="$dispatch('openAdjustmentModal', { warehouseId: '{{ $warehouse->id }}' })">Penyesuaian
                Stok</x-button>
            <x-button type="button" variant="info"
                wire:click="$dispatch('openStokOpnameModal', { warehouseId: '{{ $warehouse->id }}' })">Stok
                Opname</x-button>
            <a href="{{ route('stock.index') }}" wire:navigate
                class="inline-flex items-center px-4 py-2 border border-transparent rounded-md font-semibold text-xs uppercase tracking-widest focus:outline-none focus:ring-2 focus:ring-offset-2 disabled:opacity-25 transition ease-in-out duration-150 bg-white text-gray-700 border-gray-300 hover:bg-gray-50 active:bg-gray-100 focus:ring-indigo-500">
                Kembali
            </a>

        </div>
    </div>

    <x-card title="Informasi Gudang">
        <div>
            <table class="table-auto w-full text-md space-y-2">
                <tr>
                    <td class="font-semibold w-1/3 py-2">Nama Gudang</td>
                    <td>{{ $warehouse->name }}</td>
                </tr>
                <tr>
                    <td class="font-semibold py-2">Sudin</td>
                    <td>{{ $warehouse->sudin->name }}</td>
                </tr>
                <tr>
                    <td class="font-semibold py-2">Lokasi</td>
                    <td>{{ $warehouse->location ?? '-' }}</td>
                </tr>
                <tr>
                    <td class="font-semibold py-2">Total Item</td>
                    <td>{{ $warehouse->stocks->count() }} item</td>
                </tr>
            </table>
        </div>
    </x-card>

    <x-card title="Daftar Barang">
        <div data-grid data-api="{{ route('stock.show.json', $warehouse) }}" data-columns='{{ json_encode($data) }}'
            data-limit="10" wire:ignore>
        </div>
    </x-card>

    <!-- Modal Stok Opname -->
    <livewire:stock.stok-opname-modal />

    <!-- Modal Penyesuaian Stok -->
    <livewire:stock.adjustment-modal />
</div>