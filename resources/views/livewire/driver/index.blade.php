<div class="space-y-4">
    <div class="grid grid-cols-2 ">
        <div class="">
            <div class="text-3xl font-semibold"> Daftar Driver </div>
        </div>
    </div>
    <div data-grid data-api="{{ route('driver.json') }}" data-columns='[
        { "name": "Nama", "id": "name","width": "50%" },
        { "name": "Sudin", "id": "sudin","width": "40%"  },
        { "name": "", "id": "action" ,"width": "10%"}
    ]' data-limit="10" wire:ignore>
    </div>


</div>