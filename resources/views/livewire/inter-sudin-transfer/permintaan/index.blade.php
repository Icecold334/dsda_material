<div class="space-y-4">
    <div class="grid grid-cols-2 ">
        <div class="">
            <div class="text-3xl font-semibold">Daftar Transfer Permintaan</div>
        </div>
        <div class="text-right">
            <a href="{{ route('transfer.permintaan.create') }}" wire:navigate
                class="inline-flex items-center px-4 py-2 border border-transparent rounded-md font-semibold text-xs uppercase tracking-widest focus:outline-none focus:ring-2 focus:ring-offset-2 disabled:opacity-25 transition ease-in-out duration-150 bg-gray-800 text-white hover:bg-gray-700 active:bg-gray-900 focus:ring-indigo-500">
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