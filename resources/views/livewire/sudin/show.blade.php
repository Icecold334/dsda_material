<div class="space-y-4">
    <div class="grid grid-cols-2 ">
        <div class="">
            <div class="text-3xl font-semibold"> Sudin {{ $sudin->name }}</div>
        </div>
        <div class="text-right">
            <a href="{{ route('sudin.index') }}" wire:navigate
                class="text-white bg-primary-700 hover:bg-primary-800 focus:ring-4 focus:ring-primary-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2  focus:outline-none">Kembali</a>
        </div>
    </div>
    <div class="grid grid-cols-2 gap-4">

        <x-card title="Detail Sudin">
            <div class="">
                <table class="table-auto w-full text-md space-y-2 ">
                    <tr>
                        <td class="font-semibold w-1/2">Nama Sudin</td>
                        <td>{{ $sudin->name }}</td>
                    </tr>
                    <tr>
                        <td class="font-semibold">Singkatan</td>
                        <td>{{ $sudin->short }}</td>
                    </tr>
                    <tr>
                        <td class="font-semibold">Alamat</td>
                        <td>{{ $sudin->address ?? '-' }}</td>
                    </tr>
                </table>
            </div>
        </x-card>

    </div>
</div>