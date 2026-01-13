<div class="space-y-4">
    <div class="">
        <div class="{{ $contractNumber && $warehouse ? 'grid' :'hidden' }} grid grid-cols-2">
            <div class="">
                <div class="text-3xl font-semibold">Tambah Pengiriman</div>
            </div>
            <div class="text-right">
                <a href="{{ route('contract.index') }}" wire:navigate
                    class="text-white bg-primary-700 hover:bg-primary-800 focus:ring-4 focus:ring-primary-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2  focus:outline-none">Kembali</a>
            </div>
        </div>
    </div>
    <livewire:delivery.search-contract />
    <livewire:delivery.confirm-contract />
    <livewire:delivery.choose-warehouse />
    @if ($contractNumber && $warehouse)
    <livewire:delivery.create-table />
    @endif
</div>