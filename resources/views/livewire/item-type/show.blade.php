<div class="space-y-4">
    <div class="grid grid-cols-2 ">
        <div class="">
            <div class="text-3xl font-semibold"> Tipe Barang {{ $itemType->name }}</div>
        </div>
        <div class="text-right">
            <a href="{{ route('item-type.index') }}" wire:navigate
                class="text-white bg-primary-700 hover:bg-primary-800 focus:ring-4 focus:ring-primary-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2  focus:outline-none">Kembali</a>
        </div>
    </div>
    <div class="grid grid-cols-2 gap-4">

        <x-card title="Detail Tipe Barang">
            <div class="">
                <table class="table-auto w-full text-md space-y-2 ">
                    <tr>
                        <td class="font-semibold w-1/2">Nama Tipe Barang</td>
                        <td>{{ $itemType->name }}</td>
                    </tr>
                    <tr>
                        <td class="font-semibold">Slug</td>
                        <td>{{ $itemType->slug }}</td>
                    </tr>
                    <tr>
                        <td class="font-semibold">Status</td>
                        <td>
                            @if($itemType->active)
                                <span
                                    class="bg-green-100 text-green-800 text-xs font-medium px-2.5 py-0.5 rounded">Aktif</span>
                            @else
                                <span class="bg-red-100 text-red-800 text-xs font-medium px-2.5 py-0.5 rounded">Tidak
                                    Aktif</span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td class="font-semibold">Jumlah Kategori</td>
                        <td>{{ $itemType->itemCategories->count() }}</td>
                    </tr>
                </table>
            </div>
        </x-card>

    </div>
</div>