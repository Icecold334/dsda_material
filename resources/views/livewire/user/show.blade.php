<div class="space-y-4">
    <div class="grid grid-cols-2 ">
        <div class="">
            <div class="text-3xl font-semibold"> Pengguna {{ $user->name }}</div>
        </div>
        <div class="text-right">
            <a href="{{ route('user.index') }}" wire:navigate
                class="text-white bg-primary-700 hover:bg-primary-800 focus:ring-4 focus:ring-primary-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2  focus:outline-none">Kembali</a>
        </div>
    </div>
    <div class="grid grid-cols-2 gap-4">

        <x-card title="Detail Pengguna">
            <div class="">
                <table class="table-auto w-full text-md space-y-2 ">
                    <tr>
                        <td class="font-semibold w-1/2">Nama</td>
                        <td>{{ $user->name }}</td>
                    </tr>
                    <tr>
                        <td class="font-semibold">Email</td>
                        <td>{{ $user->email }}</td>
                    </tr>
                    <tr>
                        <td class="font-semibold">Sudin</td>
                        <td>{{ $user->sudin?->name ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td class="font-semibold">Divisi</td>
                        <td>{{ $user->division?->name ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td class="font-semibold">Jabatan</td>
                        <td>{{ $user->position?->name ?? '-' }}</td>
                    </tr>
                </table>
            </div>
        </x-card>

    </div>
</div>