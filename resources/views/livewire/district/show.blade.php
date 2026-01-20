<div class="space-y-4">
    <div class="grid grid-cols-2 ">
        <div class="">
            <div class="text-3xl font-semibold"> Kecamatan {{ $district->name }}</div>
        </div>
        <div class="text-right">
            <a href="{{ route('district.index') }}" wire:navigate
                class="inline-flex items-center px-4 py-2 border border-transparent rounded-md font-semibold text-xs uppercase tracking-widest focus:outline-none focus:ring-2 focus:ring-offset-2 disabled:opacity-25 transition ease-in-out duration-150 bg-white text-gray-700 border-gray-300 hover:bg-gray-50 active:bg-gray-100 focus:ring-indigo-500">Kembali</a>
        </div>
    </div>
    <div class="grid grid-cols-2 gap-4">

        <x-card title="Detail Kecamatan">
            <div class="">
                <table class="table-auto w-full text-md space-y-2 ">
                    <tr>
                        <td class="font-semibold w-1/2">Nama Kecamatan</td>
                        <td>{{ $district->name }}</td>
                    </tr>
                    <tr>
                        <td class="font-semibold">Sudin</td>
                        <td>{{ $district->sudin?->name ?? '-' }}</td>
                    </tr>
                </table>
            </div>
        </x-card>

    </div>

    <div>
        <x-card title="Daftar Kelurahan">
            <div class="mb-4">
                <x-button type="button" x-on:click="$dispatch('open-modal', 'create-subdistrict-{{ $district->id }}')">
                    Tambah Kelurahan
                </x-button>
            </div>

            <div data-grid data-api="{{ route('district.show.json', $district) }}" data-columns='[
                { "name": "Nama", "id": "name","width": "80%" },
                { "name": "", "id": "action","width": "20%", "sortable": false }
            ]' wire:ignore x-data="{ reloadGrid() { this.$el.dispatchEvent(new CustomEvent('reload-grid')); } }"
                @refresh-grid.window="reloadGrid()">
            </div>
        </x-card>
    </div>

    <livewire:subdistrict.create :district="$district" />

    @foreach($subdistricts as $subdistrict)
        <livewire:subdistrict.edit :subdistrict="$subdistrict" :district="$district" :key="'edit-subdistrict-' . $subdistrict->id" />
    @endforeach
</div>