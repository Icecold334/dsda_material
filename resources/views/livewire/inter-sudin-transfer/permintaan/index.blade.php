<div class="space-y-4">
    <div class="grid grid-cols-2 ">
        <div class="">
            <div class="text-3xl font-semibold">Daftar Transfer Permintaan</div>
        </div>
        <div class="text-right">
            <a href="{{ route('transfer.permintaan.create') }}" wire:navigate
                class="text-white bg-primary-700 hover:bg-primary-800 focus:ring-4 focus:ring-primary-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 focus:outline-none">
                Buat Permintaan Transfer
            </a>
        </div>
    </div>
    <div data-grid data-api="{{ route('transfer.permintaan.json') }}" data-columns='[
        { "name": "Tanggal", "id": "tanggal","width": "12%" },
        { "name": "Sudin Peminta", "id": "sudin_pengirim","width": "22%" },
        { "name": "Sudin Diminta", "id": "sudin_penerima","width": "22%" },
        { "name": "Pembuat", "id": "user","width": "17%" },
        { "name": "Status", "id": "status","width": "12%", "className": "text-center"  },
        { "name": "", "id": "action" ,"width": "10%"}
    ]' data-limit="10" wire:ignore>
    </div>
</div>