<div class="space-y-4">
    <div class="grid grid-cols-2">
        <div>
            <div class="text-3xl font-semibold">Detail Stok Gudang</div>
        </div>
        <div class="text-right">
            <a href="{{ route('stock.index') }}" wire:navigate
                class="text-white bg-primary-700 hover:bg-primary-800 focus:ring-4 focus:ring-primary-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 focus:outline-none">
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
        <div data-grid data-api="{{ route('stock.show.json', $warehouse) }}" data-columns='[
            { "name": "Kategori Barang", "id": "category", "width": "15%" },
            { "name": "Kode Barang", "id": "code", "width": "15%" },
            { "name": "Nama Barang", "id": "name", "width": "20%" },
            { "name": "Spek", "id": "spec", "width": "20%" },
            { "name": "Satuan/Unit", "id": "unit", "width": "10%" },
            { "name": "Jumlah", "id": "qty", "width": "10%" },
            { "name": "Status", "id": "status", "width": "10%", "sortable": false }
        ]' data-limit="10" wire:ignore>
        </div>
    </x-card>
</div>