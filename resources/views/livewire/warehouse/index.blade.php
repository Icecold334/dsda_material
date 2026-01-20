<div class="space-y-4">
    @if (session()->has('message'))
        <div class="p-4 mb-4 text-sm text-green-800 rounded-lg bg-green-50" role="alert">
            {{ session('message') }}
        </div>
    @endif

    <div class="grid grid-cols-2 ">
        <div class="">
            <div class="text-3xl font-semibold"> Daftar Gudang </div>
        </div>
        <div class="text-right">
            <x-button x-on:click="$dispatch('open-modal', 'create-warehouse')">
                Tambah Gudang
                </x-button>
        </div>
    </div>

    <div data-grid data-api="{{ route('warehouse.json') }}" data-columns='[
        { "name": "Nama", "id": "name","width": "30%" },
        { "name": "Sudin", "id": "sudin","width": "30%"  },
        { "name": "Lokasi", "id": "location","width": "30%"  },
        { "name": "", "id": "action" ,"width": "10%"}
    ]' data-limit="10" wire:ignore
        x-data="{ reloadGrid() { this.$el.dispatchEvent(new CustomEvent('reload-grid')); } }"
        @refresh-grid.window="reloadGrid()">
    </div>

    <livewire:warehouse.create />

    @foreach($warehouses as $warehouse)
        <livewire:warehouse.edit :warehouse="$warehouse" :key="'edit-' . $warehouse->id" />
    @endforeach
</div>