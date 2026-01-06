<div class="space-y-4">
    <div class="grid grid-cols-2 ">
        <div class="">
            <div class="text-3xl font-semibold"> Driver {{ $driver->name }}</div>
        </div>
        <div class="text-right">
            <a href="{{ route('driver.index') }}" wire:navigate
                class="text-white bg-primary-700 hover:bg-primary-800 focus:ring-4 focus:ring-primary-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2  focus:outline-none">Kembali</a>
        </div>
    </div>
    <div class="grid grid-cols-2 gap-4">

        <x-card title="Detail Driver">
            <div class="">
                <table class="table-auto w-full text-md space-y-2 ">
                    <tr>
                        <td class="font-semibold w-1/2">Nama Driver</td>
                        <td>{{ $driver->name }}</td>
                    </tr>
                    <tr>
                        <td class="font-semibold">Sudin</td>
                        <td>{{ $driver->sudin?->name ?? '-' }}</td>
                    </tr>

                </table>
            </div>
        </x-card>

    </div>
</div>