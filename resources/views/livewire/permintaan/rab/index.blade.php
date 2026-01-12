<div class="space-y-4">
    <div class="grid grid-cols-2 ">
        <div class="">
            <div class="text-3xl font-semibold"> Daftar Permintaan Menggunakan RAB </div>
        </div>
        <div class="text-right">
            <a href="{{ route('permintaan.rab.create') }}" wire:navigate
                class="text-white bg-primary-700 hover:bg-primary-800 focus:ring-4 focus:ring-primary-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 focus:outline-none">
                Buat Permintaan
            </a>
        </div>
    </div>
    <div data-grid data-api="{{ route('permintaan.rab.json') }}" data-columns='[
        { "name": "Nomor Permintaan", "id": "nomor","width": "20%" },
        { "name": "Pemohon", "id": "user","width": "30%" },
        { "name": "Status", "id": "status","width": "20%", "className": "text-center"  },
        { "name": "", "id": "action" ,"width": "10%"}
    ]' data-limit="10" wire:ignore>
    </div>


</div>