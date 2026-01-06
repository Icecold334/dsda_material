<div class="space-y-4">
    <div class="grid grid-cols-2 ">
        <div class="">
            <div class="text-3xl font-semibold"> Kecamatan {{ $district->name }}</div>
        </div>
        <div class="text-right">
            <a href="{{ route('district.index') }}" wire:navigate
                class="text-white bg-primary-700 hover:bg-primary-800 focus:ring-4 focus:ring-primary-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2  focus:outline-none">Kembali</a>
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
        <x-card title="Daftar Subdistrik">
            <div class="mb-4">
                <button type="button" x-on:click="$dispatch('open-modal', 'create-subdistrict-{{ $district->id }}')"
                    class="text-white bg-primary-700 hover:bg-primary-800 focus:ring-4 focus:ring-primary-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 focus:outline-none">
                    Tambah Subdistrik
                </button>
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