<div class="space-y-4">
    @if (session()->has('message'))
        <div class="p-4 mb-4 text-sm text-green-800 rounded-lg bg-green-50" role="alert">
            {{ session('message') }}
        </div>
    @endif

    <div class="grid grid-cols-2 ">
        <div class="">
            <div class="text-3xl font-semibold"> Daftar Barang </div>
        </div>
        <div class="text-right">
            <button type="button" x-on:click="$dispatch('open-modal', 'create-item')"
                class="text-white bg-primary-700 hover:bg-primary-800 focus:ring-4 focus:ring-primary-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 focus:outline-none">
                Tambah Barang
            </button>
        </div>
    </div>

    <div data-grid data-api="{{ route('item.json') }}" data-columns='[
        { "name": "Nama", "id": "name","width": "25%" },
        { "name": "Kategori", "id": "category","width": "20%"  },
        { "name": "Sudin", "id": "sudin","width": "20%"  },
        { "name": "Satuan", "id": "unit","width": "10%"  },
        { "name": "Status", "id": "status","width": "10%"  },
        { "name": "", "id": "action" ,"width": "15%"}
    ]' data-limit="10" wire:ignore
        x-data="{ reloadGrid() { this.$el.dispatchEvent(new CustomEvent('reload-grid')); } }"
        @refresh-grid.window="reloadGrid()">
    </div>

    <livewire:item.create />

    @foreach($items as $item)
        <livewire:item.edit :item="$item" :key="'edit-' . $item->id" />
    @endforeach
</div>