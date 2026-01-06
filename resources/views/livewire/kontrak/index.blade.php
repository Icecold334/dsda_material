<div class="space-y-4">
    <div class="grid grid-cols-2 ">
        <div class="">
            <div class="text-3xl font-semibold"> Daftar Kontrak </div>
        </div>
        <div class="text-right">
            <a href="{{ route('kontrak.create') }}" wire:navigate
                class="text-white bg-primary-700 hover:bg-primary-800 focus:ring-4 focus:ring-primary-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2  focus:outline-none">Tambah
                Kontrak</a>
        </div>
    </div>
    <div data-grid data-api="{{ route('kontrak.json') }}" data-columns='[
        { "name": "Nomor Kontrak", "id": "nomor","width": "70%" },
        { "name": "Status", "id": "status","width": "20%", "className": "text-center"  },
        { "name": "", "id": "action" ,"width": "10%"}
    ]' data-limit="10" wire:ignore>
    </div>


</div>