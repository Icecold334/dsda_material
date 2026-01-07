<div class="space-y-4">
    <div class="grid grid-cols-2">
        <div>
            <div class="text-3xl font-semibold">Stok Gudang</div>
        </div>
    </div>

    <div data-grid data-api="{{ route('stock.json') }}" data-columns='[
        { "name": "Nama Sudin", "id": "sudin", "width": "30%" },
        { "name": "Nama Gudang", "id": "warehouse", "width": "30%" },
        { "name": "Jumlah Barang", "id": "total_items", "width": "20%" },
        { "name": "", "id": "action", "width": "20%", "sortable": false }
    ]' data-limit="10" wire:ignore>
    </div>
</div>