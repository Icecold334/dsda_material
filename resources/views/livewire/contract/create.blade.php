<div class="space-y-4">
    <div class="{{ $nomorContract ? 'grid' :'hidden' }} grid grid-cols-2">
        <div class="">
            <div class="text-3xl font-semibold">Tambah Kontrak {{ $nomorContract ?? '' }}</div>
        </div>
        <div class="text-right">
            <button type="button" x-on:click="$dispatch('open-modal', 'confirm-contract-api')"
                class="text-white bg-primary-700 hover:bg-primary-800 focus:ring-4 focus:ring-primary-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2  focus:outline-none">Detail
                Kontrak</button>

            <a href="{{ route('contract.index') }}" wire:navigate
                class="text-white bg-primary-700 hover:bg-primary-800 focus:ring-4 focus:ring-primary-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2  focus:outline-none">Kembali</a>
        </div>
    </div>
    <livewire:contract.search-contract-api />
    <livewire:contract.confirm-contract-api />
    @if ($nomorContract)
    <livewire:contract.create-table />
    @endif
</div>