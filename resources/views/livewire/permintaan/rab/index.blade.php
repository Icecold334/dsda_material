<div class="space-y-4">
    <div class="grid grid-cols-2 ">
        <div class="">
            <div class="text-3xl font-semibold"> Daftar Permintaan Menggunakan RAB </div>
        </div>
    </div>
    <div data-grid data-api="{{ route('permintaan.rab.json') }}" data-columns='[
        { "name": "Nomor Permintaan", "id": "nomor","width": "70%" },
        { "name": "Status", "id": "status","width": "20%", "className": "text-center"  },
        { "name": "", "id": "action" ,"width": "10%"}
    ]' data-limit="10" wire:ignore>
    </div>


</div>