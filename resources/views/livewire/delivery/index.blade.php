<div class="space-y-4">
    <div class="grid grid-cols-2 ">
        <div class="">
            <div class="text-3xl font-semibold"> Daftar Pengiriman Barang </div>
        </div>
    </div>
    <div data-grid data-api="{{ route('delivery.json') }}" data-columns='[
        { "name": "Nomor Pengiriman", "id": "nomor","width": "50%" },
        { "name": "Gudang Pengiriman", "id": "gudang","width": "20%" },
        { "name": "Status", "id": "status","width": "20%", "className": "text-center"  },
        { "name": "", "id": "action" ,"width": "10%","className": "text-center"}
    ]' data-limit="10" wire:ignore>
    </div>


</div>