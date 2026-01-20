<div class="space-y-4">
    <div class="grid grid-cols-2 ">
        <div class="">
            <div class="text-3xl font-semibold"> Pengguna {{ $user->name }}</div>
        </div>
        <div class="text-right">
            <a href="{{ route('user.index') }}" wire:navigate
                class="inline-flex items-center px-4 py-2 border border-transparent rounded-md font-semibold text-xs uppercase tracking-widest focus:outline-none focus:ring-2 focus:ring-offset-2 disabled:opacity-25 transition ease-in-out duration-150 bg-white text-gray-700 border-gray-300 hover:bg-gray-50 active:bg-gray-100 focus:ring-indigo-500">Kembali</a>
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