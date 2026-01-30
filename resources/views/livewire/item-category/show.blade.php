<div class="space-y-4">
    <div class="grid grid-cols-2 ">
        <div class="">
            <div class="text-3xl font-semibold"> Barang: {{ $itemCategory->name }}</div>
        </div>
        <div class="text-right">
            <a href="{{ route('item-category.index') }}" wire:navigate
                class="inline-flex items-center px-4 py-2 border border-transparent rounded-md font-semibold text-xs uppercase tracking-widest focus:outline-none focus:ring-2 focus:ring-offset-2 disabled:opacity-25 transition ease-in-out duration-150 bg-white text-gray-700 border-gray-300 hover:bg-gray-50 active:bg-gray-100 focus:ring-indigo-500">Kembali</a>
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
                <button type="button" x-on:click="$dispatch('open-modal', 'create-item-{{ $itemCategory->id }}')">
                    Tambah Spesifikasi
                </button>
            </div>

            <div data-grid data-api="{{ route('item-category.show.json', $itemCategory) }}"
                data-columns='{{ json_encode($data) }}' wire:ignore
                x-data="{ reloadGrid() { this.$el.dispatchEvent(new CustomEvent('reload-grid')); } }"
                @refresh-grid.window="reloadGrid()">
            </div>
        </x-card>
    </div>

    <livewire:item.create :itemCategory="$itemCategory" />

    @foreach($items as $item)
        <livewire:item.edit :item="$item" :key="'edit-item-' . $item->id" />
    @endforeach
</div>