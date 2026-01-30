<div class="space-y-4">
    <div class="grid grid-cols-2 ">
        <div class="">
            <div class="text-3xl font-semibold"> Detail Pengiriman #{{ $delivery->nomor }}</div>
        </div>
        <div class="text-right">
            <a href="{{ route('delivery.index') }}" wire:navigate
                class="inline-flex items-center px-4 py-2 border border-transparent rounded-md font-semibold text-xs uppercase tracking-widest focus:outline-none focus:ring-2 focus:ring-offset-2 disabled:opacity-25 transition ease-in-out duration-150 bg-white text-gray-700 border-gray-300 hover:bg-gray-50 active:bg-gray-100 focus:ring-indigo-500">Kembali</a>
        </div>
    </div>
    <div class="grid grid-cols-2 gap-4">

        <x-card title="Detail Permintaan">
            <div class="">
                <table class="table-auto w-full text-md space-y-2 ">
                    <tr>
                        <td class="font-semibold w-1/2">Nomor Permintaan</td>
                        <td>{{ $delivery->nomor }}</td>
                    </tr>
                    <tr>
                        <td class="font-semibold w-1/2">Gudang delivery</td>
                        <td>{{ $delivery->warehouse->name }}</td>
                    </tr>
                    <tr>
                        <td class="font-semibold">Status</td>

                        <td><span
                                class="bg-{{ $delivery->status_color }}-600 text-{{ $delivery->status_color }}-100 text-xs font-medium me-2 px-2.5 py-0.5 rounded-full">{{
    $delivery->status_text }}</span></td>
                    </tr>

                </table>
            </div>
        </x-card>
        <x-card title="Dokumen Permintaan">

            <ul class="divide-y divide-default">
                @for ($i = 0; $i < 5; $i++)
                    <li class="p-1">
                        <div class="flex items-center space-x-4 rtl:space-x-reverse">
                            <div class="shrink-0 text-success-600">
                                <i class="fa-solid fa-file"></i>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-medium text-heading truncate">
                                    {{ fake()->sentence }}
                                </p>
                            </div>
                        </div>
                    </li>
                @endfor
            </ul>

        </x-card>

    </div>
    <div>
        <x-card title="Daftar Barang">
            <div data-grid data-api="{{ route('delivery.show.json', $delivery) }}"
                data-columns='{{ json_encode($data) }}' wire:ignore>
            </div>

        </x-card>
    </div>
</div>