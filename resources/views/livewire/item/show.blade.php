<div class="space-y-4">
    <div class="grid grid-cols-2 ">
        <div class="">
            <div class="text-3xl font-semibold"> Barang {{ $item->name }}</div>
        </div>
        <div class="text-right">
            <a href="{{ route('item.index') }}" wire:navigate
                class="inline-flex items-center px-4 py-2 border border-transparent rounded-md font-semibold text-xs uppercase tracking-widest focus:outline-none focus:ring-2 focus:ring-offset-2 disabled:opacity-25 transition ease-in-out duration-150 bg-white text-gray-700 border-gray-300 hover:bg-gray-50 active:bg-gray-100 focus:ring-indigo-500">Kembali</a>
        </div>
    </div>
    <div class="grid grid-cols-2 gap-4">

        <x-card title="Detail Barang">
            <div class="">
                <table class="table-auto w-full text-md space-y-2 ">
                    <tr>
                        <td class="font-semibold w-1/2">Nama Barang</td>
                        <td>{{ $item->name }}</td>
                    </tr>
                    <tr>
                        <td class="font-semibold">Kategori</td>
                        <td>{{ $item->category?->name ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td class="font-semibold">Sudin</td>
                        <td>{{ $item->sudin?->name ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td class="font-semibold">Spesifikasi</td>
                        <td>{{ $item->spec ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td class="font-semibold">Satuan</td>
                        <td>{{ $item->unit ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td class="font-semibold">Status</td>
                        <td>
                            @if($item->active)
                                <span class="bg-green-600 text-white text-xs font-medium px-2 py-0.5 rounded">Aktif</span>
                            @else
                                <span class="bg-gray-600 text-white text-xs font-medium px-2 py-0.5 rounded">Nonaktif</span>
                            @endif
                        </td>
                    </tr>
                </table>
            </div>
        </x-card>

    </div>
</div>