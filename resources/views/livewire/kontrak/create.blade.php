<div>
    <div class="grid grid-cols-2 ">
        <div class="">
            <div class="text-3xl font-semibold">Tambah Kontrak {{ $nomorKontrak ?? '' }}</div>
        </div>
        <div class="text-right">
            <a href="{{ route('kontrak.index') }}" wire:navigate
                class="text-white bg-primary-700 hover:bg-primary-800 focus:ring-4 focus:ring-primary-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2  focus:outline-none">Kembali</a>
        </div>
    </div>
    <livewire:kontrak.search-kontrak-api />
    <livewire:kontrak.confirm-kontrak-api />

</div>