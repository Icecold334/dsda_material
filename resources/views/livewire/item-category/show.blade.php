<div class="space-y-4">
    <div class="grid grid-cols-2 ">
        <div class="">
            <div class="text-3xl font-semibold"> Barang: {{ $itemCategory->name }}</div>
        </div>
        <div class="text-right">
            <a href="{{ route('item-category.index') }}" wire:navigate
                class="text-white bg-primary-700 hover:bg-primary-800 focus:ring-4 focus:ring-primary-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2  focus:outline-none">Kembali</a>
        </div>
    </div>
    <div class="grid grid-cols-2 gap-4">

        <x-card title="Detail Barang">
            <div class="">
                <table class="table-auto w-full text-md space-y-2 ">
                    <tr>
                        <td class="font-semibold w-1/2">Nama Barang</td>
                        <td>{{ $itemCategory->name }}</td>
                    </tr>
                    <tr>
                        <td class="font-semibold">Satuan</td>
                        <td>{{ $itemCategory->unit?->name ?? '-' }}</td>
                    </tr>
                </table>
            </div>
        </x-card>

    </div>

    <div>
        <x-card title="Daftar Spesifikasi">
            <div class="mb-4">
                <button type="button" x-on:click="$dispatch('open-modal', 'create-item-{{ $itemCategory->id }}')"
                    class="text-white bg-primary-700 hover:bg-primary-800 focus:ring-4 focus:ring-primary-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 focus:outline-none">
                    Tambah Spesifikasi
                </button>
            </div>

            <div data-grid data-api="{{ route('item-category.show.json', $itemCategory) }}" data-columns='[
                { "name": "Spesifikasi", "id": "name","width": "40%" },
                { "name": "Sudin", "id": "sudin","width": "30%" },
                { "name": "Status", "id": "status","width": "15%" },
                { "name": "", "id": "action","width": "15%", "sortable": false }
            ]' wire:ignore x-data="{ reloadGrid() { this.$el.dispatchEvent(new CustomEvent('reload-grid')); } }"
                @refresh-grid.window="reloadGrid()">
            </div>
        </x-card>
    </div>

    <livewire:item.create :itemCategory="$itemCategory" />

    @foreach($items as $item)
        <livewire:item.edit :item="$item" :key="'edit-item-' . $item->id" />
    @endforeach
</div>