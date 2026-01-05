<div class="space-y-4">
    <div class="grid grid-cols-2 ">
        <div class="">
            <div class="text-3xl font-semibold"> Rencana Anggaran Biaya #{{ $rab->nomor }}</div>
        </div>
        <div class="text-right">
            <a href="{{ route('rab.index') }}" wire:navigate
                class="text-white bg-primary-700 hover:bg-primary-800 focus:ring-4 focus:ring-primary-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2  focus:outline-none">Kembali</a>
        </div>
    </div>
    <div class="grid grid-cols-2 gap-4">

        <x-card title="Detail RAB">
            <div class="">
                <table class="table-auto w-full text-md space-y-2 ">
                    <tr>
                        <td class="font-semibold w-1/2">Nomor RAB</td>
                        <td>{{ $rab->nomor }}</td>
                    </tr>
                    <tr>
                        <td class="font-semibold">Status</td>

                        <td><span
                                class="bg-{{ $rab->status_color }}-600 text-{{ $rab->status_color }}-100 text-xs font-medium me-2 px-2.5 py-0.5 rounded-full">{{
                                $rab->status_text }}</span></td>
                    </tr>

                </table>
            </div>
        </x-card>
        <x-card title="Dokumen RAB">

            <ul class="divide-y divide-default">
                @for ($i = 0; $i < 5; $i++) <li class="p-1">
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
            <div data-grid data-api="{{ route('rab.show.json', $rab) }}" data-columns='[
        { "name": "Item", "id": "item" },
        { "name": "Jumlah", "id": "qty" },
        { "name": "", "id": "action","width": "10%" , "sortable": false }
    ]' wire:ignore>
            </div>

        </x-card>
    </div>
</div>