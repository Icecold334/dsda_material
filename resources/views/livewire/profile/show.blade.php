<div class="space-y-4">
    <div class="grid grid-cols-2">
        <div class="">
            <div class="text-3xl font-semibold">Profil Saya</div>
        </div>
        <div class="text-right">
            <x-button type="button" x-on:click="$dispatch('open-modal', 'edit-profile')">
                Edit Profil
            </x-button>
        </div>
    </div>

    <div class="grid grid-cols-2 gap-4">
        <x-card title="Informasi Pribadi">
            <div class="">
                <table class="table-auto w-full text-md space-y-2">
                    <tr>
                        <td class="font-semibold w-1/2 py-2">Nama</td>
                        <td>{{ auth()->user()->name }}</td>
                    </tr>
                    <tr>
                        <td class="font-semibold py-2">Email</td>
                        <td>{{ auth()->user()->email }}</td>
                    </tr>
                    <tr>
                        <td class="font-semibold py-2">Sudin</td>
                        <td>{{ auth()->user()->sudin?->name ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td class="font-semibold py-2">Divisi</td>
                        <td>{{ auth()->user()->division?->name ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td class="font-semibold py-2">Jabatan</td>
                        <td>{{ auth()->user()->position?->name ?? '-' }}</td>
                    </tr>
                </table>
            </div>
        </x-card>

        <x-card title="Tanda Tangan">
            @if(auth()->user()->ttd)
                <div class="flex justify-center items-center">
                    <img src="{{ asset('storage/' . auth()->user()->ttd) }}" alt="Tanda Tangan"
                        class="max-w-full h-auto border border-gray-300 rounded-md">
                </div>
            @else
                <div class="flex justify-center items-center h-48">
                    <p class="text-gray-500">Belum ada tanda tangan</p>
                </div>
            @endif
        </x-card>
    </div>

    <div>
        <x-card title="Keamanan Akun">
            <div class="space-y-4">
                <div class="flex items-center justify-between">
                    <div>
                        <h4 class="font-semibold">Password</h4>
                        <p class="text-sm text-gray-600">Terakhir diubah:
                            {{ auth()->user()->updated_at->diffForHumans() }}
                        </p>
                    </div>
                    <x-button type="button" x-on:click="$dispatch('open-modal', 'edit-profile')">
                        Ubah Password
                    </x-button>
                </div>
            </div>
        </x-card>
    </div>

    @livewire('profile.edit')
</div>