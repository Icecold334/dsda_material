<div class="space-y-4">
    <div class="grid grid-cols-2 ">
        <div class="">
            <div class="text-3xl font-semibold">Daftar Transfer Pengiriman</div>
            <p class="text-sm text-gray-600 mt-1">Daftar permintaan transfer dari Sudin lain kepada Anda</p>
        </div>
    </div>
    <div data-grid data-api="{{ route('transfer.pengiriman.json') }}" data-columns='[
        { "name": "Tanggal", "id": "tanggal","width": "12%" },
        { "name": "Sudin Peminta", "id": "sudin_pengirim","width": "22%" },
        { "name": "Sudin Diminta", "id": "sudin_penerima","width": "22%" },
        { "name": "Pembuat", "id": "user","width": "17%" },
        { "name": "Status", "id": "status","width": "12%", "className": "text-center"  },
        { "name": "", "id": "action" ,"width": "10%"}
    ]' data-limit="10" wire:ignore>
    </div>
</div>